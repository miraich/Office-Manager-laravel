<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerificationMail extends Notification
{
    use Queueable;

    public $token;

    public function __construct(string $token)
    {
        $this->afterCommit();
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(mixed $notifiable): MailMessage
    {

        return (new MailMessage)
            ->greeting('Здравствуйте!')
            ->line('Пожалуйста, подтвердите ваш адрес электронной почты')
            ->line('Код подтверждения: ' . $this->token)
            ->line('Спасибо, что выбрали нас!');
    }

}
