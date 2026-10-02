<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Registration;
use App\Models\Category;
use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Illuminate\Http\Request;
use App\Services\ActivityService;
use DomainException;

    class ActivityController extends Controller
    {
    public function index(Request $request)
{
    $query = Activity::with('category');

    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('code', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->sort == 'oldest') {
        $query->orderBy('start_at', 'asc');
    } else {
        $query->orderBy('start_at', 'desc');
    }

    $activities = $query->paginate(2)->withQueryString();
    $categories = \App\Models\Category::all(); // Ambil kategori untuk dropdown filter

    return view('activities.index', compact('activities', 'categories'));
}

    public function create()
{
    $categories = \App\Models\Category::all();
    return view('activities.create', compact('categories'));
}

public function store(Request $request)
{
    // 1. Validasi opsional untuk poster (mimes & max size 2MB)
    $request->validate([
        'poster' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // 2. Ambil semua input
    $data = [
        'category_id' => $request->category_id,
        'code'        => $request->code,
        'title'       => $request->title,
        'description' => $request->description,
        'start_at'    => $request->start_at,
        'end_at'      => $request->end_at,
        'location'    => $request->location,
        'capacity'    => $request->quota ?? $request->capacity,
        'status'      => 'draft',
    ];

    // 3. Cek apakah ada file poster yang diunggah
    if ($request->hasFile('poster')) {
        // Simpan file ke storage/app/public/posters
        $data['poster'] = $request->file('poster')->store('posters', 'public');
    }

    // 4. Simpan ke database
    Activity::create($data);

    return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dibuat!');
}

    public function show(Activity $activity)
    {
        // Muat relasi kategori agar bisa ditampilkan di halaman detail
        $activity->load('category');
        return view('activities.show', compact('activity'));
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        $categories = Category::all();

        return view('activities.edit', compact('activity', 'categories'));
    }

    public function update(UpdateActivityRequest $request, Activity $activity)
{
    // Ambil data yang sudah lolos validasi dari FormRequest
    $data = $request->validated();

    // Cek apakah ada file poster baru yang diunggah
    if ($request->hasFile('poster')) {
        // 1. Hapus poster lama dari storage jika filenya ada
        if ($activity->poster && Storage::disk('public')->exists($activity->poster)) {
            Storage::disk('public')->delete($activity->poster);
        }

        // 2. Simpan poster baru ke folder posters
        $data['poster'] = $request->file('poster')->store('posters', 'public');
    }

    // Update data kegiatan di database
    $activity->update($data);

    return redirect()->route('activities.show', $activity)
                     ->with('success', 'Kegiatan berhasil diperbarui.');
}

    public function destroy(Activity $activity)
    {
        $activity->delete();
        
        return redirect()->route('activities.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }

        public function publish(Activity $activity)
    {
        // BR-05 & BR-06: Hanya draft yang bisa dipublish
        if ($activity->status !== 'draft') {
            return back()->withErrors(['error' => 'Hanya kegiatan berstatus draft yang dapat dipublikasikan.']);
        }

        // Validasi kelengkapan data sebelum publish
        if (!$activity->category_id || !$activity->code || !$activity->title || !$activity->location || !$activity->start_at || !$activity->capacity) {
            return back()->withErrors(['error' => 'Gagal publish! Data kegiatan belum lengkap.']);
        }

        $activity->update(['status' => 'published']);
        return back()->with('success', 'Kegiatan berhasil dipublikasikan!');
    }

    public function complete(Activity $activity)
    {
        // BR-06 & BR-07: Hanya published yang bisa jadi completed
        if ($activity->status !== 'published') {
            return back()->withErrors(['error' => 'Hanya kegiatan berstatus published yang dapat diselesaikan.']);
        }

        $activity->update(['status' => 'completed']);
        return back()->with('success', 'Kegiatan telah selesai (completed)!');
    }

    public function restore($id)
    {
        $activity = Activity::withTrashed()->findOrFail($id);
        $activity->restore();

        return redirect()->route('activities.index')->with('success', 'Aktivitas berhasil dipulihkan!');
    }

    public function storeRegistration(Request $request, $activityId)
{
    $request->validate([
        'email' => 'required|email',
    ]);

    return DB::transaction(function () use ($request, $activityId) {
        // Lock for update untuk mencegah race condition saat mengecek kapasitas
        $activity = Activity::lockForUpdate()->findOrFail($activityId);

        // 1. Aturan: Pendaftaran hanya untuk activity published
        if ($activity->status !== 'published') {
            return back()->with('error', 'Pendaftaran gagal: Aktivitas belum dipublikasikan.');
        }

        // 2. Aturan: Pendaftaran ditolak jika start_at sudah lewat
        if (now()->greaterThan($activity->start_at)) {
            return back()->with('error', 'Pendaftaran gagal: Waktu pelaksanaan aktivitas sudah lewat.');
        }

        // 3. Aturan: Jumlah pendaftar tidak boleh melebihi capacity
        if ($activity->registrations()->count() >= $activity->capacity) {
            return back()->with('error', 'Pendaftaran gagal: Kapasitas peserta sudah penuh.');
        }

        // 4. Aturan: Email tidak boleh mendaftar dua kali
        $exists = Registration::where('activity_id', $activityId)
            ->where('user_email', $request->email)
            ->exists();

        if ($exists) {
            return back()->with('error', 'Pendaftaran gagal: Email ini sudah terdaftar pada aktivitas ini.');
        }

        // Eksekusi Pendaftaran (Berada dalam 1 Transaction)
        Registration::create([
            'activity_id' => $activityId,
            'user_email' => $request->email,
        ]);

        return back()->with('success', 'Pendaftaran peserta berhasil!');
    });
}
}