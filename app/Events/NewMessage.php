<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Laravel\Models\ReferAndTransactionDb\ThreadMessage;

class NewMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     *
     * @return void
     */
     public function __construct(ThreadMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('post.'.$this->message->post->id);
    }

    public function broadcastWith(){
        return [
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toFormattedDateString() ,
            'user' => [
                'name' => $this->message->sender->fname.' '.$this->message->sender->lname,
                'avatar' => $this->message->sender->directory? $this->message->sender->directory.'/'.$this->message->sender->filename: asset('backoffice/face0.jpg'),
            ]
        ];
    }
}
