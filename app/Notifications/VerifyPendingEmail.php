<?php

namespace App\Notifications;

use App\Models\PendingUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyPendingEmail extends Notification
{
    use Queueable;

    protected $pendingUser;

    public function __construct(PendingUser $pendingUser)
    {
        $this->pendingUser = $pendingUser;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/registro/verificar/' . $this->pendingUser->token);

        return (new MailMessage)
            ->subject('Verifica tu correo electronico - UNAS')
            ->greeting('Hola ' . $this->pendingUser->name . '!')
            ->line('Gracias por registrarte en el Sistema de Gestion de Docencia de la Universidad Nacional Agraria de la Selva.')
            ->line('Por favor, verifica tu direccion de correo electronico haciendo clic en el siguiente boton:')
            ->action('Verificar Correo', $url)
            ->line('Este enlace expirara en 24 horas.')
            ->salutation('Atentamente, UNAS');
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
