<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $token;

    public function __construct()
    {
        $this->apiUrl = config('services.fonnte.api_url');
        $this->token = config('services.fonnte.token');
    }

    /**
     * Kirim pesan WhatsApp ke nomor tertentu
     *
     * @param string $phone Nomor WhatsApp (format: 628xxx)
     * @param string $message Pesan yang akan dikirim
     * @return array
     */
    public function sendMessage(string $phone, string $message): array
    {
        try {
            // Pastikan nomor dimulai dengan 62
            $phone = $this->formatPhoneNumber($phone);

            if (empty($this->token)) {
                Log::warning('Fonnte token not configured');
                return [
                    'success' => false,
                    'message' => 'WhatsApp service not configured'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => $this->token,
            ])->post($this->apiUrl, [
                'target' => $phone,
                'message' => $message,
                'countryCode' => '62',
            ]);

            $result = $response->json();

            if ($response->successful() && isset($result['status']) && $result['status']) {
                Log::info('WhatsApp message sent successfully', [
                    'phone' => $phone,
                    'response' => $result
                ]);

                return [
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'data' => $result
                ];
            }

            Log::error('Failed to send WhatsApp message', [
                'phone' => $phone,
                'response' => $result
            ]);

            return [
                'success' => false,
                'message' => $result['reason'] ?? 'Failed to send message',
                'data' => $result
            ];
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format nomor telepon ke format WhatsApp (62xxx)
     *
     * @param string $phone
     * @return string
     */
    protected function formatPhoneNumber(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Jika dimulai dengan 0, ganti dengan 62
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Jika dimulai dengan +62, hapus +
        if (substr($phone, 0, 3) === '+62') {
            $phone = substr($phone, 1);
        }

        // Jika belum ada 62 di depan, tambahkan
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }

    /**
     * Kirim notifikasi dokumen baru
     *
     * @param \App\Models\User $user
     * @param \App\Models\Document $document
     * @return array
     */
    public function sendDocumentCreatedNotification($user, $document): array
    {
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => 'User phone number not set'
            ];
        }

        $message = "*📄 Dokumen Baru Dibuat*\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Dokumen baru telah dibuat:\n";
        $message .= "📋 Judul: *{$document->title}*\n";
        $message .= "📁 Kategori: " . ($document->category ? $document->category->name : 'N/A') . "\n";
        $message .= "👤 Dibuat oleh: " . ($document->uploader ? $document->uploader->name : 'N/A') . "\n";
        $message .= "📅 Tanggal: " . $document->created_at->format('d M Y H:i') . "\n\n";
        $message .= "Silakan login ke sistem untuk melihat detail dokumen.";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Kirim notifikasi approval dokumen
     *
     * @param \App\Models\User $user
     * @param \App\Models\Document $document
     * @return array
     */
    public function sendDocumentApprovalNotification($user, $document): array
    {
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => 'User phone number not set'
            ];
        }

        $message = "*🔔 Perlu Approval Dokumen*\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Ada dokumen yang memerlukan approval Anda:\n";
        $message .= "📋 Judul: *{$document->title}*\n";
        $message .= "📁 Kategori: " . ($document->category ? $document->category->name : 'N/A') . "\n";
        $message .= "👤 Dibuat oleh: " . ($document->uploader ? $document->uploader->name : 'N/A') . "\n";
        $message .= "📅 Tanggal: " . $document->created_at->format('d M Y H:i') . "\n\n";
        $message .= "Silakan segera review dan approve dokumen ini.";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Kirim notifikasi status dokumen berubah
     *
     * @param \App\Models\User $user
     * @param \App\Models\Document $document
     * @param string $oldStatus
     * @param string $newStatus
     * @return array
     */
    public function sendDocumentStatusNotification($user, $document, $oldStatus, $newStatus): array
    {
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => 'User phone number not set'
            ];
        }

        $statusEmoji = [
            'draft' => '📝',
            'pending' => '⏳',
            'approved' => '✅',
            'rejected' => '❌',
            'active' => '🟢',
            'inactive' => '⚫',
        ];

        $message = "*🔄 Status Dokumen Berubah*\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Status dokumen telah diperbarui:\n";
        $message .= "📋 Judul: *{$document->title}*\n";
        $message .= "📁 Kategori: " . ($document->category ? $document->category->name : 'N/A') . "\n";
        $message .= "Status Lama: " . ($statusEmoji[$oldStatus] ?? '') . " *{$oldStatus}*\n";
        $message .= "Status Baru: " . ($statusEmoji[$newStatus] ?? '') . " *{$newStatus}*\n";
        $message .= "📅 Tanggal: " . now()->format('d M Y H:i') . "\n\n";
        $message .= "Silakan login ke sistem untuk melihat detail dokumen.";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Kirim notifikasi user baru
     *
     * @param \App\Models\User $user
     * @param string $tempPassword
     * @return array
     */
    public function sendNewUserNotification($user, $tempPassword): array
    {
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => 'User phone number not set'
            ];
        }

        $message = "*👋 Selamat Datang!*\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Akun Anda telah dibuat di Sistem Manajemen Dokumen.\n\n";
        $message .= "📧 Email: {$user->email}\n";
        $message .= "🔑 Password Sementara: `{$tempPassword}`\n\n";
        $message .= "⚠️ *PENTING:*\n";
        $message .= "1. Segera login dan ganti password Anda\n";
        $message .= "2. Jangan bagikan password ke siapapun\n";
        $message .= "3. Simpan kredensial ini dengan aman\n\n";
        $message .= "Silakan login untuk mengakses sistem.";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Kirim notifikasi dokumen lama diupload
     *
     * @param \App\Models\User $user
     * @param \App\Models\Document $document
     * @return array
     */
    public function sendOldDocumentNotification($user, $document): array
    {
        if (empty($user->phone)) {
            return [
                'success' => false,
                'message' => 'User phone number not set'
            ];
        }

        $message = "*📤 Dokumen Lama Diupload*\n\n";
        $message .= "Halo *{$user->name}*,\n\n";
        $message .= "Dokumen lama telah diupload:\n";
        $message .= "📋 Judul: *{$document->title}*\n";
        $message .= "📁 Kategori: " . ($document->category ? $document->category->name : 'N/A') . "\n";
        $message .= "👤 Diupload oleh: " . ($document->uploader ? $document->uploader->name : 'N/A') . "\n";
        $message .= "📅 Tanggal: " . $document->created_at->format('d M Y H:i') . "\n\n";
        $message .= "Silakan login ke sistem untuk melihat dokumen.";

        return $this->sendMessage($user->phone, $message);
    }
}
