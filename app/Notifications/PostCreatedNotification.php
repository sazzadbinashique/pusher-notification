<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $post;
    /**
     * Create a new notification instance.
     */
    public function __construct($post)
    {
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Post Created')
            ->line("A new post titled '{$this->post->title}' has been created.")
            ->line('Click the button below to view the post.')
            ->action('View Post', url('/posts/' . $this->post->id))
            ->line('Thank you for staying updated!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->post->title,
            'message' => "[{$this->post->created_at}] A new post titled '{$this->post->title}' has been created.",
            'post_id' => $this->post->id,
            'created_at' => $this->post->created_at,
        ];
    }
}
