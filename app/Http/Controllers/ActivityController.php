<?php

namespace App\Http\Controllers;

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

        // Ambil data kegiatan. Jika statusnya valid, jalankan filternya.
        $activities = Activity::when(in_array($status, $validStatuses), function ($query) use ($status) {
            return $query->where('status', $status);
        })->get();

        return view('activities.index', compact('activities'));
    }

    public function create()
    {
        return view('activities.create');
    }


    // Tanggung jawab diserahkan ke ActivityService
    public function store(StoreActivityRequest $request, ActivityService $service)
    {
        $activity = $service->create($request->validated());
        
        return redirect()->route('activities.show', $activity)
                         ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
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