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
        $currentStatus = $activity->status;
        $newStatus = $data['status'];

        $allowedTransitions = [
            'Planned' => ['Planned', 'Ongoing'],
            'Ongoing' => ['Ongoing', 'Done'],
            'Done' => ['Done'],
        ];

        if (
            ! in_array(
                $newStatus,
                $allowedTransitions[$currentStatus] ?? [],
                true
            )
        ) {
            throw new DomainException(
                'Perubahan status aktivitas tidak diperbolehkan.'
            );
        }

        $activity->update($data);

        return $activity;
    }
}
