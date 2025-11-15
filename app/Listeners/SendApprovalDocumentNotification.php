<?php

namespace App\Listeners;

use App\Events\NewApprovalDocument;
use App\Models\User;
use App\Notifications\DocumentApprovalNotification;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendApprovalDocumentNotification
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
    public function handle(NewApprovalDocument $event): void
    {
        // Cari user dengan role yang berwenang
        $users = User::whereHas('roles', function ($query) use ($event) {
            $query->whereIn('id', array_filter($event->roles, fn($r) => $r != 1)); // Exclude admin dari roles
        })->get();

        // Tambahkan Admin yang mau terima semua notif
        $admins = User::whereHas('roles', function ($query) {
            $query->where('id', 1); // Administrator
        })->where('receive_all_notifications', true)->get();

        $users = $users->merge($admins);

        // Kirim email notification
        Notification::send($users, new DocumentApprovalNotification($event->document, $event->message, $event->link));

        // Kirim WhatsApp notification
        foreach ($users as $user) {
            $this->whatsappService->sendDocumentApprovalNotification($user, $event->document);
        }
    }
}
