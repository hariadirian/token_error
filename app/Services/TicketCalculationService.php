<?php
namespace App\Services;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Idempiere\M_View\MV_Product;
use stdClass;
use DateTime;
use App\Models\M_Event_Calendar;
  
class TicketCalculationService{
    //VALIDASI PROMOTION, VALIDASI TANGGAL, MENDAPATKAN HARGA DARI IDEMPIERE
    public function getPrice($type, $date, $cd, $qty, $promo_code = '', $by_pass = 0){
      
      $result           = new stdClass();
      $result->qty      = $qty;
      $result->type     = $type;
      $result->cd       = $cd;

      if ($type == 'PROMOTION') {
          
        $datas                  = MV_Promo::where('m_promotion_uu', $cd)->first();
        $result->min_val        = $datas->min_val;
        $result->product        = $datas->product;
        $result->product_uu     = $datas->product_uu;
        $result->name           = $datas->description;
        $result->promotioncode  = $datas->promotioncode;
        
        //VALIDATING QTY AND RULES FOR PROMOTION
        if ($datas->promotioncode != '' || $datas->promotioncode != null) {
            if ($promo_code == '') {
                return ['state' => 0, 'message' => 'Kode promo tidak boleh kosong!'];
            } else {
                if (strtolower($promo_code) != strtolower($datas->promotioncode)) {
                    return ['state' => 0, 'message' => 'Kode promo salah!'];
                }
            }
        }

        $check_qty        = $this->validateQty(
            $datas->min_operand, 
            $datas->min_val, 
            $datas->max_operand, 
            $datas->max_val, 
            $qty
        );

      }elseif($type == 'PRODUCT'){

            $datas                  = MV_Product::where('m_product_uu', $cd)->first();
            $result->min_val        = 1;
            $result->product        = $datas->product;
            $result->product_uu     = $datas->product_uu;
            $result->name           = $datas->name;

      }else{
        return ['state' => 0, 'message' => 'Jenis tiket tidak ditemukan!'];
      }
      
        if($by_pass){
            return ['result' => $result];
        }

        if(isset($check_qty['state'])){
            if($check_qty['state'] == 0){
                return $check_qty;
            }
        }

      $new_date_format  = new DateTime($date);
      $result->date     = $new_date_format->format('d M Y');

      //MENDAPATKAN TIPE TIKET BERDASARKAN TANGGAL
      $ticket_category  = $this->getTicketCategory($date);

      if($ticket_category == 'WEEK'){
          $result->promo = $datas->promo_pekan;            
          $result->amount_raw = $datas->pekan_value;            
          $result->amount_fix = $datas->pekan_value * $qty;
          $result->amount_text = 'Rp. ' . number_format( $datas->pekan_value * $qty,0,',','.');
          $result->amount_text_satuan_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format( $datas->satuanproductprice_pekan,0,',','.') : 'Rp. ' . number_format($datas->pekan_value,0,',','.');
          $result->text_amount_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->satuanproductprice_pekan * $qty, 0, ',', '.') : $datas->pekan_value * $qty;
          $result->promo_type           = ($type == 'PROMOTION')? $datas->promo_pekan_type : '';
          $result->promo_value          = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->promo_pekan, 0, ',', '.') : '';
          $result->product_value          = ($type == 'PROMOTION')? $datas->productprice_pekan : '';

      }elseif($ticket_category == 'HOLIDAY'){
          $result->promo = $datas->promo_holiday;   
          $result->amount_raw = $datas->holiday_value;
          $result->amount_fix = $datas->holiday_value * $qty;
          $result->amount_text = 'Rp. ' . number_format( $datas->holiday_value * $qty,0,',','.');
          $result->amount_text_satuan_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format( $datas->satuanproductprice_holiday,0,',','.') : 'Rp. ' . number_format($datas->holiday_value,0,',','.');
          $result->text_amount_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->satuanproductprice_holiday  * $qty, 0, ',', '.') : $datas->holiday_value * $qty;
          $result->promo_type           = ($type == 'PROMOTION')? $datas->promo_holiday_type : '';
          $result->promo_value          = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->promo_holiday, 0, ',', '.') : '';
          $result->product_value          = ($type == 'PROMOTION')?  $datas->productprice_holiday : '';

      }elseif($ticket_category == 'WEEKDAYS'){
          $result->promo = $datas->promo_weekdays;   
          //NILAI FIX BELUM DIJUMLAH DENGAN QTY
          $result->amount_raw = $datas->weekdays_value;
          //NILAI YANG SUDAH FIX TERMANTAP SUDAH DIHITUNG DENGAN PROMO DAN JUMLAH MANTAP
          $result->amount_fix = $datas->weekdays_value * $qty;
          //NILAI YANG SUDAH FIX TERMANTAP SUDAH DIHITUNG DENGAN PROMO DAN JUMLAH MANTAP DITAMBAH CURRENCY IDR
          $result->amount_text = 'Rp. ' . number_format($datas->weekdays_value * $qty,0,',','.');
          //NILAI SATUAN *UNTUK PROMO NILAI TOTAL SEMUA PRODUCT DIJUMLAHKAN NAMUN BELUM DIJUMLAHKAN DENGAN QTY 
          //BELUM DIHITUNG DENGAN PROMO
          $result->amount_text_satuan_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format( $datas->satuanproductprice_weekdays,0,',','.') : 'Rp. ' . number_format($datas->weekdays_value,0,',','.');
          //NILAI SATUAN *UNTUK PROMO NILAI TOTAL SEMUA PRODUCT DIJUMLAHKAN DAN SUDAH DIJUMLAHKAN DENGAN QTY 
          //BELUM DIHITUNG DENGAN PROMO
          $result->text_amount_b4_promo =  ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->satuanproductprice_weekdays * $qty, 0, ',', '.') : 'Rp. ' . number_format($datas->weekdays_value * $qty, 0, ',', '.');
          //TIPE PROMO ABSOLUT VALUE ATAU PERCENT
          $result->promo_type           =   ($type == 'PROMOTION')? $datas->promo_weekdays_type : '';
          //NILAI PROMO
          $result->promo_value          =  ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->promo_weekdays, 0, ',', '.') : '';
          //NILAI PRODUCT
          $result->product_value          = ($type == 'PROMOTION')? $datas->productprice_weekdays : '';
      }
      $results['result'] = $result;
      return $results;
    }

    public function validateQty($min_operand, $min_val, $max_operand, $max_val, $qty){

          $qty = $qty * $min_val;
          if($min_operand == '>=' OR $min_operand == '<=' OR $min_operand == '=' OR $min_operand == ''){
              $min_val = $min_val;
          }elseif($min_operand == '>'){
              $min_val = $min_val + 1;
          }elseif($min_operand == '<'){
              $min_val = $min_val - 1;
          }elseif($min_val == ''){
              $min_val = 1;
          }
          if($max_operand == '>=' OR $max_operand == '<=' OR $max_operand == '='){
              $max_val = $max_val;
          }elseif($max_operand == '>'){
              $max_val = $max_val + 1;
          }elseif($max_operand == '<'){
              $max_val = $max_val - 1;
          }elseif($max_val == ''){
              $max_val = 99999999;
          }

          if($qty > $max_val){
              return ['state' => 0, 'message' => 'Melebihi batas maksimal promo!', 'type' => 'max', 'val' => $max_val / $min_val];
          }
          if($qty < $min_val){
              return ['state' => 0, 'message' => 'Melebihi batas minimal promo!', 'type' => 'min', 'val' => $min_val / $min_val];
          }
        //   if($qty % $min_val != 0){
        //       return ['state' => 0, 'message' => 'Kuota tiket harus sesuai dengan persyaratan dan kelipatan minimum pembelian!'];
        //   }
          
          return ['state' => 1];
    }

    public function getTicketCategory($date){
    
      $get_result_cal = '';
      $calendar = M_Event_Calendar::where('state', 'Y')->where('is_active', 'Y')->where('event_startdate', '<=', $date)->where('event_enddate', '>=', $date)->get();
    
      if($calendar->count()){
          foreach($calendar as $cal1){
              if($cal1->event_type == 'WEEK'){
                  $get_result_cal = 'WEEK';
                  break;
              }elseif($cal1->event_type == 'HOLIDAY'){
                  $get_result_cal = 'HOLIDAY';
              }
          }
      }else{
          $weekEnd = date('w', strtotime($date));
          if($weekEnd == 0 || $weekEnd == 6){
              $get_result_cal = 'HOLIDAY';
          }else{
              $get_result_cal = 'WEEKDAYS';
          }
      }
      return $get_result_cal;
  }
}