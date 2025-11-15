<?php

namespace App\Listeners;

use App\Events\OldDocumentUploaded;
use App\Models\User;
use App\Notifications\OldDocumentNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOldDocumentNotification
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
    public function handle(OldDocumentUploaded $event): void
    {
        // Kirim notifikasi ke Administrator untuk monitoring
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', 1); // Hanya Administrator
        })->get();

        foreach ($users as $user) {
            Notification::send($user, new OldDocumentNotification($event->document, $event->message));
        }
    }
}
