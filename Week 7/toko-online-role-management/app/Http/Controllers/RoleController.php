<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeRoleRequest;
use App\Models\User;
use App\Services\RoleManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        return view('admin.users', ['users' => User::orderBy('id_user')->get()]);
    }

    public function update(ChangeRoleRequest $request, User $user, RoleManagementService $service): JsonResponse|RedirectResponse
    {
        $changed = $service->changeRole($request->user(), $user, $request->validated('role'));
        $message = $changed ? 'Role berhasil diperbarui.' : 'Role sama; tidak ada perubahan.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message, 'changed' => $changed,
                'user' => ['id_user' => $user->getKey(), 'role' => $user->fresh()->role]]);
        }
        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
