<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreatePassword extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Author account created - Open Book Publishers')
            ->line('We have set up a new online reporting system for OBP authors, which allows you to see the current readership and sales data for your publications at any time.')
            ->line('An account has been created for you, using the same email address to which this message has been sent. Please use the link below to set up a new password.')
            ->action('Create Password', url(config('app.url').route('password.reset', $this->token, false)))
            ->line('After you have created your password you may revisit these reports on any occasion at ' . url(config('app.url')) . '.');
    }
}
