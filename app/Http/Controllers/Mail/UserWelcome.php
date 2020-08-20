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
        return $this->view('email.welcome', compact('user', 'password'))->from('contato@diretoriodigital.com.br', 'Diretório Digital')->to($user->email, $user->name)->subject('Seja bem vindo a Diretório Digital!');;
    }
}
