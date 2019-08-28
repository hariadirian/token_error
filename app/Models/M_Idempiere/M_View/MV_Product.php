<?php

namespace App\Models\M_Idempiere\M_View;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\Models\M_Ticket_Img_HD;

class MV_Product extends Authenticatable
{

    use Notifiable, EntrustUserTrait;
    
    protected $connection = 'idem_db';

    protected $table = 'adempiere.vc_products';
    protected $primarykey = 'm_product_id';

    public $timestamps = false;
    public $incrementing = false;
    
    public function toTicketImgHd(){
        return $this->hasOne(M_Ticket_Img_HD::class, 'idem_ticket_uu', 'm_product_uu');
    }
}
