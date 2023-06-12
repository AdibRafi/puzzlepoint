<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateJigsawSession implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $minuteCounter;
    public $secondCounter;
    public $transitionMinuteCounter;
    public $transitionSecondCounter;
    public $moduleMinuteCounter;
    public $moduleSecondCounter;
    public $moduleNumber;

    /**
     * Create a new event instance.
     */
    public function __construct($minuteCounter, $secondCounter, $transitionMinuteCounter, $transitionSecondCounter, $moduleMinuteCounter, $moduleSecondCounter, $moduleNumber)
    {
        $this->minuteCounter = $minuteCounter;
        $this->secondCounter = $secondCounter;
        $this->transitionMinuteCounter = $transitionMinuteCounter;
        $this->transitionSecondCounter = $transitionSecondCounter;
        $this->moduleMinuteCounter = $moduleMinuteCounter;
        $this->moduleSecondCounter = $moduleSecondCounter;
        $this->moduleNumber = $moduleNumber;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     */
    public function broadcastOn()
    {
        return new Channel('update-jigsaw-session');
    }
}
