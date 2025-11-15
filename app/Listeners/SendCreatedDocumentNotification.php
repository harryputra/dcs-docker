<?php

namespace App\Listeners;

use App\Events\NewCreatedDocument;
use App\Models\User;
use App\Notifications\DocumentCreatedNotification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendCreatedDocumentNotification
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
    public function handle(NewCreatedDocument $event): void
    {
        // Kirim ke Pengendali Dokumen
        $users = User::whereHas('roles', function ($query) {
            $query->where('id', 2); // Pengendali Dokumen
        })->get();

        // Tambahkan Admin yang mau terima semua notif
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1); // Administrator
        })->where('receive_all_notifications', true)->get();

        $users = $users->merge($admins);

        foreach ($users as $user) {
            // Kirim email notification
            Notification::send($user, new DocumentCreatedNotification($event->document, $event->message));

            // Kirim WhatsApp notification
            $this->whatsappService->sendDocumentCreatedNotification($user, $event->document);
        }
    }
}
