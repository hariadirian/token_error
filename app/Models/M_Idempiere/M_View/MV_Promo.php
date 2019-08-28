<?php

namespace App\Models\M_Idempiere\M_View;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\M_Ticket_Img_HD;
use App\Models\M_Ordered_Ticket_DT;

class MV_Promo extends Authenticatable
{

    use Notifiable, EntrustUserTrait;
    
    protected $connection = 'idem_db';

    protected $table = 'adempiere.vc_promotions';
    protected $primarykey = 'm_promotion_id';

    public $timestamps = false;
    public $incrementing = false;
    
    public function toTicketImgHd(){
        return $this->hasOne(M_Ticket_Img_HD::class, 'idem_ticket_uu', 'm_promotion_uu');
    }
    
    public function toOrderedTicketDt(){
        return $this->hasOne(M_Ordered_Ticket_DT::class, 'idem_ticket_uu', 'm_promotion_uu');
    }
}
