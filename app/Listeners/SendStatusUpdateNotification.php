<?php

namespace App\Listeners;

use App\Events\DocumentStatusUpdated;
use App\Models\User;
use App\Notifications\DocumentStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendStatusUpdateNotification
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
    public function handle(DocumentStatusUpdated $event): void
    {
        // Kirim notifikasi ke uploader dokumen
        $uploader = User::find($event->uploaderId);

        if ($uploader) {
            Notification::send($uploader, new DocumentStatusNotification(
                $event->document,
                $event->message,
                $event->status
            ));
        }
    }
}
