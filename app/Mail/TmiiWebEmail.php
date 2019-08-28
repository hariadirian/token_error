<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TmiiWebEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function build()
    {
        return $this->view('mail.test')
            ->attach(
               storage_path('/pdf/ticket/test1.pdf'),  [
                    'as' => 'qq.pdf',
                'mime' => 'application/pdf'
        ])
            ->attach(
               storage_path('/pdf/ticket/mockup eticketing(2).pdf'),  [
                    'as' => 'ww.pdf',
                'mime' => 'application/pdf'
        ]);
    }
}
