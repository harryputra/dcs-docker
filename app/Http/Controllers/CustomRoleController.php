<?php

namespace App\Http\Controllers;

use Itstructure\LaRbac\Http\Controllers\RoleController;
use Itstructure\LaRbac\Http\Requests\Delete;

class CustomRoleController extends RoleController
{
    /**
     * Delete role data with flash message.
     * @param Delete $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Delete $request)
    {
        $deletedCount = 0;

        foreach ($request->items as $item) {
            if (!is_numeric($item)) {
                continue;
            }

            \Itstructure\LaRbac\Models\Role::destroy($item);
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->route('list_roles')->with('success', $deletedCount . ' role berhasil dihapus.');
        }

        return redirect()->route('list_roles')->with('error', 'Tidak ada role yang dihapus.');
    }
}
