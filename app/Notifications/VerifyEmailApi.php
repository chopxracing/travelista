<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class VerifyEmailApi extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Генерируем безопасную ссылку
        $verifyUrl = URL::temporarySignedRoute(
            'verify-email-redirect',          // имя маршрута web
            Carbon::now()->addMinutes(60),   // время действия ссылки
            ['id' => $notifiable->id, 'hash' => sha1($notifiable->email)],
            true                              // абсолютный URL
        );


        return (new MailMessage)
            ->subject('Подтверждение email')
            ->greeting('Привет!') // убрали имя из глагола, чтобы очередь не падала
            ->line('Нажмите на ссылку, чтобы подтвердить ваш email:')
            ->action('Подтвердить email', $verifyUrl)
            ->line('Если вы не регистрировались, просто проигнорируйте это письмо.');
    }
}
