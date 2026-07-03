<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/password/reset/' . $this->token . '?email=' . urlencode($this->email));

        return (new MailMessage)
            ->subject('Restablecer Contrasena - UNAS')
            ->greeting('Hola!')
            ->line('Recibiste este correo porque solicitaste restablecer la contrasena de tu cuenta en el Sistema de Gestion de Docencia de la UNAS.')
            ->line('Haz clic en el boton de abajo para crear una nueva contrasena:')
            ->action('Restablecer Contrasena', $url)
            ->line('Este enlace expirara en 3 horas.')
            ->line('Si no solicitaste este cambio, ignora este mensaje.')
            ->salutation('Atentamente, UNAS');
    }
}
