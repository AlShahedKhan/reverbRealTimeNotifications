<?php
namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PostCreate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    /**
     * Create a new event instance.
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [new Channel('posts')];
    }

    /**
     * Customize the payload data sent with the broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'message' => "[{$this->post->created_at}] New post created: {$this->post->title}",
            'post' => [
                'id' => $this->post->id,
                'title' => $this->post->title,
                'body' => $this->post->body,
            ],
        ];
    }

    /**
     * Set the broadcast event name.
     */
    public function broadcastAs(): string
    {
        return 'create';
    }
}
