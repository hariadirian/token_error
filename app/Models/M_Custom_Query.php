<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;
use Session;
use DateTime;

class M_Custom_Query extends Model
{
    public static function allPromo($cd = null){
       $where = ($cd)? ' where id = '.$cd : '';
       return $datas = DB::select( 
            DB::raw("
                select * from tmii_temp_produk".$where)
        );
    }
    
    public static function randomProduk(){
        
       return $datas = DB::select( 
            DB::raw("
                SELECT 
                    *
                FROM
                    tmii_temp_produk
                ORDER BY RAND()
                LIMIT 4
            ")
        );
    }
    public static function sumAllProducts($id_products){
        
       return $datas = DB::select( 
            DB::raw("
                SELECT 
                    sum(harga) total
                FROM
                    tmii_temp_produk
                WHERE id in (".$id_products.")
            ")
        );
    }

    public static function idemProducts(){
        
       return $datas = DB::connection('idem_db')->select( 
            DB::raw("
            select
                a.created,
                a.description,
                a.name,
                b.startdate,
                b.enddate,
                b.promotionusagelimit,
                e.operation,
                e.qty,
                e.distributiontype,
                e.distributionsorting,
                f.rewardtype,
                f.amount,
                product,
                productprice
            from
                adempiere.m_promotion a
            left join adempiere.m_promotionprecondition b on
                a.m_promotion_id = b.m_promotion_id
            left join adempiere.m_promotionline c on
                a.m_promotion_id = c.m_promotion_id
            left join adempiere.m_promotiongroup d on
                c.m_promotiongroup_id = d.m_promotiongroup_id
            left join adempiere.m_promotiondistribution e on
                e.m_promotionline_id = c.m_promotionline_id
            left join adempiere.m_promotionreward f on
                f.m_promotiondistribution_id = e.m_promotiondistribution_id
            left join (
                select
                    v.m_promotiongroup_id,
                    array_agg(w.name) product,
                    array_agg(x.pricelist) productprice
                from
                    adempiere.m_promotiongroupline v
                left join adempiere.m_product w on
                    v.m_product_id = w.m_product_id
                left join adempiere.m_productprice x on
                    w.m_product_id = x.m_product_id
                left join adempiere.m_pricelist_version z
                on z.m_pricelist_version_id = x.m_pricelist_version_id
                left join adempiere.m_pricelist xz
                on xz.m_pricelist_id = z.m_pricelist_id
                left join (
                    select
                        *
                    from
                        adempiere.m_pricelist_version
                    where
                        ( validfrom,
                        m_pricelist_id ) in (
                        select
                            max( s.validfrom ) validfrom,
                            s.m_pricelist_id
                        from
                            adempiere.m_pricelist_version s
                        left join adempiere.m_pricelist t on
                            s.m_pricelist_id = t.m_pricelist_id
                        where
                            validfrom <= current_date
                            and upper(t.name) = 'SALES'
                            and s.isactive = 'Y'
                        group by
                            s.m_pricelist_id )) y on
                    y.m_pricelist_version_id = x.m_pricelist_version_id
                    where upper(xz.name) = 'SALES'
                group by
                    v.m_promotiongroup_id ) g on
                d.m_promotiongroup_id = g.m_promotiongroup_id
            where
                a.isactive = 'Y'
                and b.isactive = 'Y'
                and c.isactive = 'Y'
                and d.isactive = 'Y'
                and e.isactive = 'Y'
                and f.isactive = 'Y'
                and b.startdate <= current_date
                and b.enddate > current_date;
            ")
        );
    }

}
