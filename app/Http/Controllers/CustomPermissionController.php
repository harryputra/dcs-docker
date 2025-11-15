<?php

namespace App\Http\Controllers;

use Itstructure\LaRbac\Http\Controllers\PermissionController;
use Itstructure\LaRbac\Http\Requests\Delete;

class CustomPermissionController extends PermissionController
{
    /**
     * Delete permission data with flash message.
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

            \Itstructure\LaRbac\Models\Permission::destroy($item);
            $deletedCount++;
        }

        if ($deletedCount > 0) {
            return redirect()->route('list_permissions')->with('success', $deletedCount . ' permission berhasil dihapus.');
        }

        return redirect()->route('list_permissions')->with('error', 'Tidak ada permission yang dihapus.');
    }
}
