<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Traits\DefaultTableAttribute;

class M_Event_Calendar extends Model
{
    use DefaultTableAttribute;
    
    protected $table ='tmii_ms_event_calendar';
    protected $primaryKey = 'id_ms_event_calendar';
    
    public $incrementing = true; 
    public $timestamps = false;

    protected $fillable = [
        'cd_ms_event_calendar',
        'event_name',
        'event_startdate',
        'event_enddate',
        'event_type',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
        'is_active',
    ];

}
