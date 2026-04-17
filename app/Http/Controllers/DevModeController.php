<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevModeController extends Controller
{
    public function toggle(Request $request)
    {
        $user = Auth::user();

        if (!$user || !$user->isRole('Administrator')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $user->is_dev_mode = !$user->is_dev_mode;
        $user->save();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_dev_mode' => $user->is_dev_mode,
                'message' => 'Dev Mode ' . ($user->is_dev_mode ? 'Activated' : 'Deactivated')
            ]);
        }

        return back()->with('success', 'Dev Mode ' . ($user->is_dev_mode ? 'Activated' : 'Deactivated'));
    }
}
