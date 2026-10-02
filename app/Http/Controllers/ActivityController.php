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
        // Tangkap query string '?status=' dari URL
        $status = $request->query('status');
        
        // Daftar status yang diizinkan untuk difilter
        $validStatuses = ['Planned', 'Ongoing', 'Done'];

        // Gunakan with('category') untuk Eager Loading agar query efisien
        $activities = Activity::with('category')
            ->when(in_array($status, $validStatuses), function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->get();

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('activities.create', compact('categories'));
    }

    // Menggunakan ActivityService agar semua kolom wajib (activity_date, status, dll) terisi otomatis
    public function store(Request $request)
{
    // 1. Validasi
    $request->validate([
        'title'       => 'required',
        'description' => 'nullable',
    ]);

    // 2. Simpan kolom yang benar-benar ada di database
    Activity::create([
        'title'         => $request->title,
        'description'   => $request->description,
        'activity_date' => now(),
        'category_id'   => $request->category_id ?? 1, // Kolom dari migration terbaru
        'status'        => 'Planned',
    ]);

    return redirect()->route('activities.create')->with('success', 'Data berhasil disimpan!');
}

    public function show(Activity $activity)
    {
        // Muat relasi kategori agar bisa ditampilkan di halaman detail
        $activity->load('category');
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        $categories = Category::all();
        return view('activities.edit', compact('categories', 'activity'));
    }

    // Tanggung jawab diserahkan ke ActivityService, dan menangkap penolakan
    public function update(UpdateActivityRequest $request, Activity $activity, ActivityService $service)
    {
        try {
            $service->update($activity, $request->validated());
        } catch (DomainException $exception) {
            // Kalau Service menolak transisinya, lemparkan error ke halaman Form!
            return back()->withErrors(['status' => $exception->getMessage()])->withInput();
        }
        
        return redirect()->route('activities.show', $activity)
                         ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        
        return redirect()->route('activities.index')
                         ->with('success', 'Kegiatan berhasil dihapus.');
    }
}