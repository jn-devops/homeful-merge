<?php

namespace App\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\Channel;
use App\Models\Template;

class TemplateDownloaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Template $template){}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('template-name'),
        ];
    }
}
