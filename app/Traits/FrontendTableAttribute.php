<?php 
namespace App\Traits;
use App\Models\M_User_Management\M_Us_Frontend_HD;

trait FrontendTableAttribute
{
    public function toCreatedBy(){
        return $this->hasOne(M_Us_Frontend_HD::class, 'id_us_frontend_hd', 'created_by');
    }
    public function toUpdatedBy(){
        return $this->hasOne(M_Us_Frontend_HD::class, 'id_us_frontend_hd', 'updated_by');
    }
    public function toDeletedBy(){
        return $this->hasOne(M_Us_Frontend_HD::class, 'id_us_frontend_hd', 'deleted_by');
    }
    public function scopeIsActive($query, $active_state){
        return $query->where('is_active', $active_state);
    }
}