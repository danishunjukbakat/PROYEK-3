<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    public function update(
    Activity $activity,
    array $data
): Activity {
    $activity->update($data);

    return $activity;
}

public function publish(Activity $activity): Activity
{
    if ($activity->status !== 'draft') {
        throw new DomainException(
            'Hanya activity dengan status draft yang dapat dipublikasikan.'
        );
    }

    if (
        empty($activity->category_id) ||
        empty($activity->code) ||
        empty($activity->title) ||
        empty($activity->location) ||
        empty($activity->start_at) ||
        empty($activity->end_at) ||
        empty($activity->capacity)
    ) {
        throw new DomainException(
            'Activity belum lengkap sehingga tidak dapat dipublikasikan.'
        );
    }

    $activity->update([
        'status' => 'published',
    ]);

    return $activity;
}

public function complete(Activity $activity): Activity
{
    if ($activity->status !== 'published') {
        throw new DomainException(
            'Hanya activity dengan status published yang dapat diselesaikan.'
        );
    }

    $activity->update([
        'status' => 'completed',
    ]);

    return $activity;
}

}
