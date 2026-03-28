<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmationNotification extends Notification
{
    use Queueable;

    public function __construct(public Payment $payment)
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
            ->subject('Payment Confirmed! 🔓')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your payment was successful.')
            ->line('**Post:** ' . $this->payment->post->title)
            ->line('**Amount:** $' . number_format($this->payment->amount, 2))
            ->line('**Transaction ID:** ' . $this->payment->transaction_id)
            ->action('Read Post', url('/reader/posts/' . $this->payment->post->slug))
            ->line('Thank you for your purchase! 🎉');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title'          => 'Payment Confirmed! 🔓',
            'message'        => 'You unlocked: ' . $this->payment->post->title,
            'url'            => '/reader/posts/' . $this->payment->post->slug,
            'amount'         => $this->payment->amount,
            'transaction_id' => $this->payment->transaction_id,
        ];
    }
}
