<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RbacFlashMessage
{
    /**
     * Handle an incoming request and inject flash messages for RBAC delete operations.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if this is a delete operation for RBAC - BEFORE processing
        if ($request->isMethod('POST') && $request->is('rbac/*/delete')) {

            // Prevent user from deleting themselves
            if ($request->is('rbac/users/delete') && $request->has('items') && auth()->check()) {
                $currentUserKey = auth()->user()->memberKey;
                $userModel = config('rbac.userModelClass');

                foreach ($request->items as $item) {
                    if (is_numeric($item)) {
                        $userToDelete = $userModel::find($item);
                        if ($userToDelete && $userToDelete->memberKey === $currentUserKey) {
                            return redirect()->route('list_users')
                                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
                        }
                    }
                }
            }

            // Prepare flash message data BEFORE delete
            $items = $request->input('items', []);
            $count = count(array_filter($items, 'is_numeric'));

            if ($count > 0) {
                $type = 'user';
                if ($request->is('rbac/roles/delete')) {
                    $type = 'role';
                } elseif ($request->is('rbac/permissions/delete')) {
                    $type = 'permission';
                }

                // Store in session for after redirect
                session()->flash('success', $count . ' ' . $type . ' berhasil dihapus.');
            } else {
                session()->flash('error', 'Tidak ada data yang dihapus.');
            }
        }

        $response = $next($request);

        return $response;
    }
}
