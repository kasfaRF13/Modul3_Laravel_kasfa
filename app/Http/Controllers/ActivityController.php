<?php

namespace App\Http\Controllers;

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
    // Simpan data (bisa tanpa validasi ketat/opsional agar bisa buat draft tidak lengkap)
    Activity::create([
        'category_id' => $request->category_id,
        'code'        => $request->code,
        'title'       => $request->title,
        'description' => $request->description,
        'start_at'    => $request->start_at,
        'end_at'      => $request->end_at,
        'location'    => $request->location,
        'capacity'    => $request->quota ?? $request->capacity,
        'status'      => 'draft', // default buat sebagai draft
    ]);

    return redirect()->route('activities.index')->with('success', 'Kegiatan draft berhasil dibuat!');
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

    // Tanggung jawab diserahkan ke ActivityService, dan menangkap penolakan
    public function update(UpdateActivityRequest $request, Activity $activity)
{
    // Menggunakan update biasa langsung ke Model untuk update data
    $activity->update($request->validated());
    
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
}