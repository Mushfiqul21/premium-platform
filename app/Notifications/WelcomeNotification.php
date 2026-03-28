<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Premium Platform! 🎉')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Welcome to Premium Platform — your go-to place for premium content.')
            ->line('You can now browse free posts and unlock premium content.')
            ->action('Browse Posts', url('/reader/posts'))
            ->line('Happy reading! 📖');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'   => 'Welcome to Premium Platform! 🎉',
            'message' => 'Your account has been created successfully.',
            'url'     => '/reader/posts',
        ];
    }
}
