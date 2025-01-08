<?php

namespace App\Events;

use App\Notifications\PostApprovedNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostApproved implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $post;
    /**
     * Create a new event instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
        $this->notifyUsers();
    }

    public function notifyUsers()
    {
        // Notify all users or specific users (e.g., admins)
        $users = \App\Models\User::where('is_admin', 0)->get(); // Example: notify admins
        foreach ($users as $user) {
            $user->notify(new PostApprovedNotification($this->post));
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
        return new Channel('postApprove');
    }



    public function broadcastAs()
    {
        return 'approved';
    }


    public function broadcastWith(): array
    {
        return [
            'message' => "[{$this->post->updated_at}] New Post Approved with title '{$this->post->title}'."
        ];
    }
}
