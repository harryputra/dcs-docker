<?php

namespace App\Listeners;

use App\Events\NewCreatedUser;
use App\Notifications\NewUserPasswordChange;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class SendNewUserNotification
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
    public function handle(NewCreatedUser $event): void
    {
        // Kirim email notification
        Notification::send($event->user, new NewUserPasswordChange($event->user));

        // Kirim WhatsApp notification (tanpa password karena tidak disimpan)
        if ($event->user->phone) {
            $message = "*👋 Selamat Datang!*\n\n";
            $message .= "Halo *{$event->user->name}*,\n\n";
            $message .= "Akun Anda telah dibuat di Sistem Manajemen Dokumen.\n\n";
            $message .= "📧 Email: {$event->user->email}\n\n";
            $message .= "⚠️ *PENTING:*\n";
            $message .= "Segera login dan ganti password Anda untuk keamanan.\n\n";
            $message .= "Silakan login untuk mengakses sistem.";

            $this->whatsappService->sendMessage($event->user->phone, $message);
        }
    }
}
