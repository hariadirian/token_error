<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $user;

    public function __construct(\App\Models\M_User_Management\M_Us_Frontend_HD $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->view('mail.email-user-verification')->with([
            'link' => route('activating-account',$this->user->registered_token)
        ]);
    }
}
