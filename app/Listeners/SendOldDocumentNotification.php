<?php

namespace App\Listeners;

use App\Events\OldDocumentUploaded;
use App\Models\User;
use App\Notifications\OldDocumentNotification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendOldDocumentNotification
{
    protected $whatsappService;

    /**
     * Create the event listener.
     */
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
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
            // Kirim email notification
            Notification::send($user, new OldDocumentNotification($event->document, $event->message));

            // Kirim WhatsApp notification
            $this->whatsappService->sendOldDocumentNotification($user, $event->document);
        }
    }
}
