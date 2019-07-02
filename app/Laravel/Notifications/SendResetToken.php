<?php

namespace App\Laravel\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendResetToken extends Notification implements ShouldQueue
{
    use Queueable;

    protected $token;
    protected $source;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $source = "backend")
    {
        $this->token = $token;
        $this->source = $source;
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
        switch ($this->source) {
            case 'api':
                return (new MailMessage)
                    ->from("no-reply@thingsilikeapp.com")
                    ->subject('Reset Password')
                    ->line('Reset your password by entering the code below to Things I Like App.')
                    ->line($this->token);
            break;
            default:
                return (new MailMessage)
                    ->from("no-reply@thingsilikeapp.com")
                    ->subject('Reset Password')
                    ->line('Reset your password by clicking the button below.')
                    ->action('Click Me', route('backoffice.auth.reset_password', [$this->token]));
        }
        
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
