<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Auth;
use PDF;
use App\Models\M_Ordered_Ticket_Txes;
use App\Models\M_Cart_Product_HD;
use App\Models\M_User_Management\M_Us_Frontend_HD;


class TicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(M_Ordered_Ticket_Txes $order, M_Cart_Product_HD $cartHd, M_Us_Frontend_HD $usHd){
        $this->order    = $order;
        $this->cartHd   = $cartHd;
        $this->usHd     = $usHd;
    }

    public function build()
    {
        $ticket_path = storage_path("app/ticket/".$this->order->toGenerateTicketHd->file_path);
        return $this->view('mail.email-ticket')->attach($ticket_path, [
            'as' => 'TICKET.pdf',
            'mime' => 'application/pdf',
        ]);
    }
}
