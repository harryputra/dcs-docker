<?php

namespace App\Listeners;

use App\Events\DocumentStatusUpdated;
use App\Models\User;
use App\Notifications\DocumentStatusNotification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendStatusUpdateNotification
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
    public function handle(DocumentStatusUpdated $event): void
    {
        // Kirim notifikasi ke uploader dokumen
        $uploader = User::find($event->uploaderId);

        if ($uploader) {
            // Kirim email notification
            Notification::send($uploader, new DocumentStatusNotification(
                $event->document,
                $event->message,
                $event->status
            ));

            // Kirim WhatsApp notification
            $this->whatsappService->sendDocumentStatusNotification(
                $uploader,
                $event->document,
                $event->oldStatus ?? 'unknown',
                $event->status
            );
        }
    }
}
