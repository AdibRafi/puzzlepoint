<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateExpertSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $minuteCounter;
    public $secondCounter;
    public $transitionMinuteCounter;
    public $transitionSecondCounter;


    /**
     * Create a new event instance.
     */
    public function __construct($minuteCounter, $secondCounter, $transitionMinuteCounter, $transitionSecondCounter)
    {
        $this->minuteCounter = $minuteCounter;
        $this->secondCounter = $secondCounter;
        $this->transitionMinuteCounter = $transitionMinuteCounter;
        $this->transitionSecondCounter = $transitionSecondCounter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn()
    {
        return new Channel('update-expert-session');
    }
}
