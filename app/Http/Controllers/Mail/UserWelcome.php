<?php

namespace App\Http\Controllers\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class UserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($id, $password)
    {
        $this->user = User::find($id);
        $this->password = $password;
    }
    public function build()
    {
        $user = $this->user;
        $password = $this->password;
        $subject = "Seja bem vindo a " . env('APP_NAME')."!";
        return $this->view('email.welcome', compact('user', 'password'))
            ->from('contato@danilonoleto.com.br',  env('APP_NAME') )
            ->cc($user->email, $user->name)
            ->bcc($user->email, $user->name)
            ->replyTo($user->email, $user->name)
            ->subject($subject);
    }
}
