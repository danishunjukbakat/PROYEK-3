<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $allowedStatuses = ['Planned', 'Ongoing', 'Done'];

        $status = $request->query('status');

        $activities = Activity::query()
            ->when(
                in_array($status, $allowedStatuses, true),
                function ($query) use ($status) {
                    $query->where('status', $status);
                }
            )
            ->orderBy('activity_date', 'asc')
            ->get();

        return view('activities.index', compact(
            'activities',
            'status',
            'allowedStatuses'
        ));
    }

    public function create()
    {
        return view('activities.create');
    }

    public function store(
        StoreActivityRequest $request,
        ActivityService $service
    ) {
        $service->create($request->validated());

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function show(Activity $activity)
    {
        return view('activities.show', compact('activity'));
    }

    public function edit(Activity $activity)
    {
        return view('activities.edit', compact('activity'));
    }

    public function update(
        UpdateActivityRequest $request,
        Activity $activity,
        ActivityService $service
    ) {
        $service->update(
            $activity,
            $request->validated()
        );

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(Activity $activity)
    {
        // Hapus aktivitas dari database
        $activity->delete();

        // Kembali ke halaman daftar aktivitas
        return redirect()->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus!');
    }
}
