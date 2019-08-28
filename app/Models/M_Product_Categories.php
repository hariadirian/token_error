<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M_Product_Categories extends Model
{

    protected $connection = 'idem_db';

    protected $table = 'adempiere.m_product_category';
    protected $primarykey = 'm_product_category_id';

    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'ad_client_id',
        'ad_org_id',
        'isactive',
        'created',
        'createdby',
        'updated',
        'updatedby',
        'value',
        'name',
        'description',
        'isdefault',
        'plannedmargin',
        'a_asset_group_id',
        'isselfservice',
        'ad_printcolor_id',
        'mmpolicy',
        'm_product_category_parent_id',
        'm_product_category_uu'
        
    ];

    public function toBookingTime(){
        return $this->belongsTo(M_Booking_Time::class,'code_us_docter_booking_time','code_us_docter_schedule_time');
    }
    public function toPatient(){
        return $this->belongsTo(M_Patient::class,'code_patient','code_patient');
    }
    public function toQueueVisus(){
        return $this->belongsTo(M_Queue_Visus::class,'code_booking', 'code_booking');
    }
    public function toQueueGlasses(){
        return $this->belongsTo(M_Queue_Kacamata::class,'code_booking', 'code_booking');
    }
}
