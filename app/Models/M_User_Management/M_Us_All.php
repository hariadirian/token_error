<?php

namespace App\Models\M_User_Management;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class M_Us_All extends Authenticatable
{
    use Notifiable, EntrustUserTrait;

    protected $table = 'v_us_hd';

}
