<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DocumentStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $document;
    public $status;
    public $message;
    public $uploaderId;

    /**
     * Create a new event instance.
     */
    public function __construct($document, $status, $message, $uploaderId)
    {
        $this->document = $document;
        $this->status = $status;
        $this->message = $message;
        $this->uploaderId = $uploaderId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
