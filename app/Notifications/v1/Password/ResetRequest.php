<?php

namespace App\Notifications\v1\Password;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetRequest extends Notification
{
    use Queueable;
    protected $parameters;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($parameters)
    {
        $this->parameters=$parameters;
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
        $url = url('/api/v1/password/find/'. $this->parameters->token);
        return (new MailMessage)
                    ->greeting('Hello'. $this->parameters->name)
                    ->line('You are receiving this because we got a password reset request for your account')
                    ->action('Reset Password', url($url))
                    ->line('If you did not reuest a password reset no further actions required')
                    ->line('Thank you for using CERTISTORE!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
