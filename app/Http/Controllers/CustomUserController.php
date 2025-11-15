<?php

namespace App\Http\Controllers;

use Itstructure\LaRbac\Http\Controllers\UserController;
use Itstructure\LaRbac\Http\Requests\Delete;

class CustomUserController extends UserController
{
    /**
     * Delete user data with flash message and self-deletion protection.
     * @param Delete $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Delete $request)
    {
        $deletedCount = 0;

        // Ensure auth check before using auth()->user()
        $currentUserKey = auth()->check() ? auth()->user()->memberKey : null;
        $userModel = config('rbac.userModelClass');

        foreach ($request->items as $item) {
            if (!is_numeric($item)) {
                continue;
            }

            // Prevent deleting currently logged-in user
            if ($currentUserKey) {
                $userToDelete = $userModel::find($item);
                if ($userToDelete && $userToDelete->memberKey === $currentUserKey) {
                    return redirect()->route('list_users')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                }
            }

            $userModel::destroy($item);
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->route('list_users')->with('success', $deletedCount . ' user berhasil dihapus.');
        }

        return redirect()->route('list_users')->with('error', 'Tidak ada user yang dihapus.');
    }
}
