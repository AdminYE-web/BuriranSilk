<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailCustom extends Notification
{
    use Queueable;

    public function __construct(private readonly string $verificationUrl)
    {
    }

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('【ThaiSilk】メールアドレスの確認')
            ->greeting('ThaiSilkへようこそ')
            ->line('会員登録を完了するため、下のボタンからメールアドレスを確認してください。')
            ->action('メールアドレスを確認する', $this->verificationUrl)
            ->line('この確認リンクの有効期限は5分です。');
    }
}
