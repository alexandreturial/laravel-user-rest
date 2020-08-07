<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRequest extends Notification
{
    use Queueable;
    protected $remember_token;
    protected $user;
    protected $senha;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $senha)
    {
        //$this->remember_token = $remember_token;
        $this->user = $user->email;
        $this->senha = $senha['senha'];
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
//        $url = url('http://recicla.myappnow.com.br/recicla/login');
        $senha = $this->senha;
        return (new MailMessage)->view('auth.email.email',compact('senha'));
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
