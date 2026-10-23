<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class RoleManagementService
{
    public function changeRole(User $actor, User $target, string $newRole): bool
    {
        return DB::transaction(function () use ($actor, $target, $newRole): bool {
            // Re-read current roles and lock in stable primary-key order.
            $users = User::whereKey([$actor->getKey(), $target->getKey()])
                ->orderBy('id_user')->lockForUpdate()->get()->keyBy('id_user');
            $currentActor = $users->get($actor->getKey());
            $currentTarget = $users->get($target->getKey());
            abort_unless($currentActor && $currentTarget, 404);
            Gate::forUser($currentActor)->authorize('manage-roles');

            if (!in_array($newRole, ['customer', 'staff'], true)) {
                throw ValidationException::withMessages(['role' => 'Role hanya boleh customer atau staff.']);
            }
            if ($currentActor->is($currentTarget) || $currentTarget->role === 'admin') {
                throw ValidationException::withMessages(['role' => 'Role akun sendiri atau akun admin tidak dapat diubah.']);
            }
            if (!in_array($currentTarget->role, ['customer', 'staff'], true)) {
                throw ValidationException::withMessages(['role' => 'Role target tidak termasuk alur perubahan yang diizinkan.']);
            }
            if ($currentTarget->role === $newRole) return false;

            $currentTarget->role = $newRole;
            $currentTarget->save();
            return true;
        });
    }
}
