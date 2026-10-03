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
    $allowedStatuses = ['draft', 'published', 'completed'];

    $search = $request->query('search');
    $status = $request->query('status');
    $categoryId = $request->query('category_id');
    $sort = $request->query('sort', 'oldest');

    $categories = \App\Models\Category::orderBy('name')->get();

    $activities = Activity::with('category')
        ->when(
            $search,
            function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                          ->orWhere('code', 'like', "%{$search}%");
                });
            }
        )
        ->when(
            in_array($status, $allowedStatuses, true),
            function ($query) use ($status) {
                $query->where('status', $status);
            }
        )
        ->when(
            $categoryId,
            function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        )
        ->orderBy(
    'start_at',
        $sort === 'newest' ? 'desc' : 'asc'
    )
    ->paginate(10)
    ->withQueryString();

    return view('activities.index', compact(
        'activities',
        'status',
        'allowedStatuses',
        'search',
        'categories',
        'categoryId',
        'sort'
    ));
}

    public function create()
    {
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('activities.create', compact('categories'));
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
    $categories = \App\Models\Category::orderBy('name')->get();

    return view('activities.edit', compact(
        'activity',
        'categories'
    ));
}

    public function update(
    UpdateActivityRequest $request,
    Activity $activity,
    ActivityService $activityService
) {
    try {
        $activityService->update(
            $activity,
            $request->validated()
        );

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity berhasil diperbarui.');
    } catch (\DomainException $e) {
        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}
    public function destroy(Activity $activity)
    {
        // Hapus aktivitas dari database
        $activity->delete();

        // Kembali ke halaman daftar aktivitas
        return redirect()->route('activities.index')
            ->with('success', 'Aktivitas berhasil dihapus!');
    }

    public function publish(
    Activity $activity,
    ActivityService $activityService
) {
    try {
        $activityService->publish($activity);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity berhasil dipublikasikan.');
    } catch (\DomainException $e) {
        return redirect()
            ->route('activities.index')
            ->with('error', $e->getMessage());
    }
}

public function complete(
    Activity $activity,
    ActivityService $activityService
) {
    try {
        $activityService->complete($activity);

        return redirect()
            ->route('activities.index')
            ->with('success', 'Activity berhasil diselesaikan.');
    } catch (\DomainException $e) {
        return redirect()
            ->route('activities.index')
            ->with('error', $e->getMessage());
    }
}

public function restore($id)
{
    $activity = Activity::onlyTrashed()->findOrFail($id);

    $activity->restore();

    return redirect()
        ->route('activities.index')
        ->with('success', 'Activity berhasil dipulihkan.');
}
}
