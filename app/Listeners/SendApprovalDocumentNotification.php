<?php

namespace App\Listeners;

use App\Events\NewApprovalDocument;
use App\Models\User;
use App\Notifications\DocumentApprovalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendApprovalDocumentNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NewApprovalDocument $event): void
    {
        // Cari user dengan role yang berwenang
        $users = User::whereHas('roles', function ($query) use ($event) {
            $query->whereIn('id', array_filter($event->roles, fn($r) => $r != 1)); // Exclude admin dari roles
        })->get();

        // Tambahkan Admin yang mau terima semua notif
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1); // Administrator
        })->get();

        $users = $users->merge($admins);

        Notification::send($users, new DocumentApprovalNotification($event->document, $event->message, $event->link));
    }
}
