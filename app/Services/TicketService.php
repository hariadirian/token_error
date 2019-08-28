<?php
namespace App\Services;
use App\Models\M_Idempiere\M_View\MV_Promo;
use App\Models\M_Idempiere\M_View\MV_Product;
use App\Models\M_Cart_Product_BD;
use stdClass;
use DateTime;
use App\Models\M_Event_Calendar;
use App\Models\M_Ordered_Ticket_Txes;
use App\Models\M_Generated_Ticket_HD;
use App\Models\M_Generated_Ticket_DT;
use Illuminate\Support\Facades\Mail;
use Auth;
use QrCode;
use PDF;
use File;
use Storage;
use DB;

class TicketService{
    //VALIDASI PROMOTION, VALIDASI TANGGAL, MENDAPATKAN HARGA DARI IDEMPIERE
    public function getPrice($type, $date, $cd, $qty, $by_pass = 0, $promo_code = ''){

      $result           = new stdClass();
      $result->qty      = $qty;
      $result->type     = $type;
      $result->cd       = $cd;

      if ($type == 'PROMOTION') {

        $datas                  = MV_Promo::where('m_promotion_uu', $cd)->first();
        $result->min_val        = $datas->min_val;
        $result->product        = $datas->product;
        $result->product_uu     = $datas->product_uu;
        $result->m_attributeset_uu = $datas->attributeset_uu;
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
        if (Auth::user()) {
            //check produk rombongan
            $check_product_rombongan = M_Cart_Product_BD::where('state', 'Y')
                                    ->where('is_active', 'Y')
                                    ->where('created_by', Auth::user()->id_us_frontend_hd)
                                    ->pluck('cd_product_ref');

            $count_product_rombongan = MV_Promo::where('type_customer', 'Rombongan')
                                ->whereIn('m_promotion_uu', $check_product_rombongan)
                                ->get();

            if ($datas->type_customer == 'Rombongan' && $count_product_rombongan->count() == 1) {
                return ['state' => 0, 'message' => 'Maaf, hanya diperbolehkan 1 produk rombongan dalam cart!'];
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
            $result->product_uu     = $datas->m_product_uu;
            $result->m_attributeset_uu = $datas->attributeset_uu;
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
          $result->text_amount_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->totalproductprice_pekan * $qty, 0, ',', '.') : $datas->pekan_value * $qty;
          $result->promo_type           = ($type == 'PROMOTION')? $datas->promo_pekan_type : '';
          $result->promo_value          = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->promo_pekan, 0, ',', '.') : '';
          $result->product_value          = ($type == 'PROMOTION')? $datas->productprice_pekan : '';

      }elseif($ticket_category == 'HOLIDAY'){
          $result->promo = $datas->promo_holiday;
          $result->amount_raw = $datas->holiday_value;
          $result->amount_fix = $datas->holiday_value * $qty;
          $result->amount_text = 'Rp. ' . number_format( $datas->holiday_value * $qty,0,',','.');
          $result->amount_text_satuan_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format( $datas->satuanproductprice_holiday,0,',','.') : 'Rp. ' . number_format($datas->holiday_value,0,',','.');
          $result->text_amount_b4_promo = ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->totalproductprice_holiday  * $qty, 0, ',', '.') : $datas->holiday_value * $qty;
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
          $result->text_amount_b4_promo =  ($type == 'PROMOTION')? 'Rp. ' . number_format($datas->totalproductprice_weekdays * $qty, 0, ',', '.') : 'Rp. ' . number_format($datas->weekdays_value * $qty, 0, ',', '.');
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

    public function generate($cd){

        if($cd){
            $order = M_Ordered_Ticket_Txes::where('state', 'Y')->where('is_active', 'Y')->where('cd_et_ordered_ticket_txes', $cd);
            if($order->count()){
                $order_paid = $order->where('paid_state', 2);
                if ($order_paid->count()) {
                    $id_et_cart_product_hd = $order_paid->first()->id_et_cart_product_hd;

                    $cart = $order_paid->with(['toCartProductHd' => function ($query1) use ($id_et_cart_product_hd) {
                        $query1->where('state', 'Y');
                        $query1->where('is_active', 'Y');
                        $query1->where('is_done', 'Y');
                        $query1->with(['toCartProductBd' => function ($query2) {
                            $query2->where('state', 'Y');
                            $query2->where('is_active', 'Y');
                            $query2->with(['toCartProductDt' => function ($query3) {
                                $query3->where('state', 'Y');
                                $query3->where('is_active', 'Y');
                            }]);
                        }]);
                    }]);

                    if ($order_paid->first()->total_amount >= $cart->first()->toCartProductHd->toCartProductBd->sum('total_amount')) {

                        $generate = $order->with(['toGenerateTicketHd' => function ($query3)  {
                            $query3->where('state', 'Y');
                            $query3->with(['toGeneratedTicketDt' => function ($query4) {
                                $query4->where('state', 'Y');
                                $query4->where('is_active', 'Y');
                            }]);
                        }]);

                        if($generate->first()->toGenerateTicketHd){

                            $cd_et_generated_ticket_hd = $generate->first()->toGenerateTicketHd->first()->cd_et_generated_ticket_hd;
                            $id_et_generated_ticket_hd = $generate->first()->toGenerateTicketHd->first()->id_et_generated_ticket_hd;
                            $generatedHd_created_at = $generate->first()->toGenerateTicketHd->first()->created_at;
                            $year          = substr($generatedHd_created_at, 0, 4);
                            $month         =  substr($generatedHd_created_at, 5, 2);
                            $day           = substr($generatedHd_created_at, 8, 2);
                            $path          = $generate->first()->toGenerateTicketHd->first()->file_path;
                            $check_dt = true;

                        }else{

                            $created_at                 = date('Y-m-d H:i:s');
                            $year                       = substr($created_at, 0, 4);
                            $month                      =  substr($created_at, 5, 2);
                            $day                        = substr($created_at, 8, 2);
                            $cd_et_generated_ticket_hd  = get_prefix('et_generated_ticket_hd');

                            $path = $year . '/'. $month. '/' . $day.'/'.$year.$month.$day.'_' .$cd_et_generated_ticket_hd.'.pdf';

                            $ticketHDData = M_Generated_Ticket_HD::create([
                                'cd_et_generated_ticket_hd'     => $cd_et_generated_ticket_hd,
                                'id_et_ordered_ticket_txes'     => $order_paid->first()->id_et_ordered_ticket_txes,
                                'created_at'                    => date('Y-m-d H:i:s'),
                                'file_path'                     => $path,
                                'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                            ]);

                            $cd_et_generated_ticket_hd = $ticketHDData->cd_et_generated_ticket_hd;
                            $id_et_generated_ticket_hd = $ticketHDData->id_et_generated_ticket_hd;
                            $generatedHd_created_at = $ticketHDData->created_at;
                            $check_dt = false;

                        }

                        $cd_generated_batch = strtoupper(uniqid());
                        $cartHd = $cart->first()->toCartProductHd;
                        $total_qty = 0;

                        if(!File::isDirectory(storage_path('app/ticket/'.$year.'/'.$month.'/'.$day)))
                            File::makeDirectory(storage_path('app/ticket/'.$year.'/'.$month.'/'.$day), 0777, true, true);
                        if(File::exists(storage_path('app/ticket/'.$path))){
                            gc_collect_cycles();
                            Storage::delete('app/ticket/'.$path);
                        }

                        if($check_dt and $generate->first()->toGenerateTicketHd){
                            $generatesDt = $generate->first()->toGenerateTicketHd->first()->toGeneratedTicketDt;
                            $x = 0;
                            foreach ($cartHd->toCartProductBd as $cartBd) {
                                foreach ($cartBd->toCartProductDt as $cartDt) {
                                    $found = false;
                                    foreach ($generatesDt as $generatedDt) {

                                        if ($cartDt->id_et_cart_product_dt == $generatedDt->id_et_cart_product_dt and $cartDt->qty_ticket == $generatedDt->qty) {

                                            //update
                                            M_Generated_Ticket_DT::where('id_et_generated_ticket_dt', $generatedDt->id_et_generated_ticket_dt)
                                            ->update([
                                                'cd_generated_batch'    => $cd_generated_batch,
                                                'total_generated'       => DB::raw(
                                                    "total_generated + 1"
                                                    ),
                                                'generated_at'          => date('Y-m-d H:i:s'),
                                                'generated_by'          => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'

                                            ]);
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if (!$found) {
                                        //create new
                                        $generatedDt = M_Generated_Ticket_DT::create([
                                            'cd_et_generated_ticket_dt'     => get_prefix('et_generated_ticket_dt'),
                                            'id_et_generated_ticket_hd'     => $id_et_generated_ticket_hd,
                                            'id_et_cart_product_dt'         => $cartDt->id_et_cart_product_dt,
                                            'cd_generated_batch'            => $cd_generated_batch,
                                            'total_generated'               => 1,
                                            'qty'                           => $cartDt->qty_ticket,
                                            'generated_at'                  => date('Y-m-d H:i:s'),
                                            'generated_by'                  => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM',
                                            'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                                        ]);
                                    }

                                    $data['qr'][$x]['qr'] = QrCode::size(250)->margin(0)->format('png')->generate(
                                        $generatedDt->cd_et_generated_ticket_dt
                                    );
                                    $data['qr'][$x]['cartBd'] = $cartBd;
                                    $data['qr'][$x]['cartDt'] = $cartDt;
                                    $data['qr'][$x]['generatedDt'] = $generatedDt;
                                    $x++;
                                    $total_qty += $cartDt->qty_ticket;
                                }
                            }

                            //delete
                            M_Generated_Ticket_DT::where('id_et_generated_ticket_hd', $id_et_generated_ticket_hd)->where('cd_generated_batch', '!=', $cd_generated_batch)
                            ->update([
                                'is_active'           => 'N',
                                'deleted_at'          => date('Y-m-d H:i:s'),
                                'deleted_by'          => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                            ]);
                        }else{
                            $x = 0;
                            foreach ($cartHd->toCartProductBd as $cartBd) {
                                foreach ($cartBd->toCartProductDt as $cartDt) {
                                    $generatedDt_new = M_Generated_Ticket_DT::create([
                                        'cd_et_generated_ticket_dt'     => get_prefix('et_generated_ticket_dt'),
                                        'id_et_generated_ticket_hd'     => $id_et_generated_ticket_hd,
                                        'id_et_cart_product_dt'         => $cartDt->id_et_cart_product_dt,
                                        'cd_generated_batch'            => $cd_generated_batch,
                                        'total_generated'               => 1,
                                        'qty'                           => $cartDt->qty_ticket,
                                        'is_active'                     => 'Y',
                                        'generated_at'                  => date('Y-m-d H:i:s'),
                                        'generated_by'                   => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM',
                                        'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                                    ]);

                                    $data['qr'][$x]['qr'] = QrCode::size(250)->format('png')->generate(
                                        $generatedDt_new->cd_et_generated_ticket_dt
                                    );
                                    $data['qr'][$x]['cartBd'] = $cartBd;
                                    $data['qr'][$x]['cartDt'] = $cartDt;
                                    $data['qr'][$x]['generatedDt'] = $generatedDt_new;
                                    $x++;
                                    $total_qty += $cartDt->qty_ticket;
                                }
                            }
                        }
                        $data['email'] = $cartHd->toUsFrontendHd->email;
                        $detail = $cartHd->with(['toUsFrontendHd' => function ($query)  {
                            $query->with(['toUsFrontendDt' => function ($query2)  {
                                $query2->where('state', 'Y');
                            }]);
                        }]);

                        $data['detail'] = $detail->first()->toUsFrontendHd->toUsFrontendDt->first();
                        $data['total_ticket'] = $total_qty;
                        $data['order'] =  $order_paid->first();
                        PDF::loadView('PDF/template_ticket', $data)->save(storage_path('app/ticket/'.$path));
                        return redirect()->back()->with('success_custom', 'You successfully generate ticket for '.$data['detail']->first_name.' '.$data['detail']->last_name.' ('.$data['email'].') which ordered at '.$data['order']->created_at.'. Check generate result by downloading it.');
                    }else{
                        return  [
                            'success' => false,
                            'msg'       => 'Pembayaran kurang dari total jumlah harga tiket.'
                        ];
                    }
                }else{
                    return  [
                        'success' => false,
                        'msg'       => 'Order belum melakukan pembayaran.'
                    ];
                }
            }else{
                return  [
                    'success' => false,
                    'msg'       => 'Order tidak ditemukan.'
                ];
            }
        }
    }

    public function send_email($cd){
        if ($cd) {

            $order = M_Ordered_Ticket_Txes::where('state', 'Y')
                        ->where('is_active', 'Y')
                        ->where('cd_et_ordered_ticket_txes', $cd)
                        ->first();

            $cartHd = $order->toCartProductHd;
            $usHd = $cartHd->toUsFrontendHd;
            return Mail::to($usHd->email)->send(new \App\Mail\TicketMail($order, $cartHd, $usHd));
        }
    }

    public function generate_invoice($cd){

        if($cd){
            $order = M_Ordered_Ticket_Txes::where('state', 'Y')->where('is_active', 'Y')->where('cd_et_ordered_ticket_txes', $cd);
            if($order->count()){
                $order_paid = $order->where('paid_state', 2);
                if ($order_paid->count()) {
                    $id_et_cart_product_hd = $order_paid->first()->id_et_cart_product_hd;

                    $cart = $order_paid->with(['toCartProductHd' => function ($query1) use ($id_et_cart_product_hd) {
                        $query1->where('state', 'Y');
                        $query1->where('is_active', 'Y');
                        $query1->where('is_done', 'Y');
                        $query1->with(['toCartProductBd' => function ($query2) {
                            $query2->where('state', 'Y');
                            $query2->where('is_active', 'Y');
                            $query2->with(['toCartProductDt' => function ($query3) {
                                $query3->where('state', 'Y');
                                $query3->where('is_active', 'Y');
                            }]);
                        }]);
                    }]);

                    if ($order_paid->first()->total_amount >= $cart->first()->toCartProductHd->toCartProductBd->sum('total_amount')) {

                        $generate = $order->with(['toGenerateTicketHd' => function ($query3)  {
                            $query3->where('state', 'Y');
                            $query3->with(['toGeneratedTicketDt' => function ($query4) {
                                $query4->where('state', 'Y');
                                $query4->where('is_active', 'Y');
                            }]);
                        }]);

                        if($generate->first()->toGenerateTicketHd){

                            $cd_et_generated_ticket_hd = $generate->first()->toGenerateTicketHd->first()->cd_et_generated_ticket_hd;
                            $id_et_generated_ticket_hd = $generate->first()->toGenerateTicketHd->first()->id_et_generated_ticket_hd;
                            $generatedHd_created_at = $generate->first()->toGenerateTicketHd->first()->created_at;
                            $year          = substr($generatedHd_created_at, 0, 4);
                            $month         =  substr($generatedHd_created_at, 5, 2);
                            $day           = substr($generatedHd_created_at, 8, 2);
                            $path          = $generate->first()->toGenerateTicketHd->first()->file_path;
                            $check_dt = true;

                        }else{

                            $created_at                 = date('Y-m-d H:i:s');
                            $year                       = substr($created_at, 0, 4);
                            $month                      =  substr($created_at, 5, 2);
                            $day                        = substr($created_at, 8, 2);
                            $cd_et_generated_ticket_hd  = get_prefix('et_generated_ticket_hd');

                            $path = $year . '/'. $month. '/' . $day.'/'.$year.$month.$day.'_' .$cd_et_generated_ticket_hd.'.pdf';

                            $ticketHDData = M_Generated_Ticket_HD::create([
                                'cd_et_generated_ticket_hd'     => $cd_et_generated_ticket_hd,
                                'id_et_ordered_ticket_txes'     => $order_paid->first()->id_et_ordered_ticket_txes,
                                'created_at'                    => date('Y-m-d H:i:s'),
                                'file_path'                     => $path,
                                'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                            ]);

                            $cd_et_generated_ticket_hd = $ticketHDData->cd_et_generated_ticket_hd;
                            $id_et_generated_ticket_hd = $ticketHDData->id_et_generated_ticket_hd;
                            $generatedHd_created_at = $ticketHDData->created_at;
                            $check_dt = false;

                        }

                        $cd_generated_batch = strtoupper(uniqid());
                        $cartHd = $cart->first()->toCartProductHd;
                        $total_qty = 0;

                        if(!File::isDirectory(storage_path('app/ticket/'.$year.'/'.$month.'/'.$day)))
                            File::makeDirectory(storage_path('app/ticket/'.$year.'/'.$month.'/'.$day), 0777, true, true);
                        if(File::exists(storage_path('app/ticket/'.$path))){
                            gc_collect_cycles();
                            Storage::delete('app/ticket/'.$path);
                        }

                        if($check_dt and $generate->first()->toGenerateTicketHd){
                            $generatesDt = $generate->first()->toGenerateTicketHd->first()->toGeneratedTicketDt;
                            $x = 0;
                            foreach ($cartHd->toCartProductBd as $cartBd) {
                                foreach ($cartBd->toCartProductDt as $cartDt) {
                                    $found = false;
                                    foreach ($generatesDt as $generatedDt) {

                                        if ($cartDt->id_et_cart_product_dt == $generatedDt->id_et_cart_product_dt and $cartDt->qty_ticket == $generatedDt->qty) {

                                            //update
                                            M_Generated_Ticket_DT::where('id_et_generated_ticket_dt', $generatedDt->id_et_generated_ticket_dt)
                                            ->update([
                                                'cd_generated_batch'    => $cd_generated_batch,
                                                'total_generated'       => DB::raw(
                                                    "total_generated + 1"
                                                    ),
                                                'generated_at'          => date('Y-m-d H:i:s'),
                                                'generated_by'          => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'

                                            ]);
                                            $found = true;
                                            break;
                                        }
                                    }
                                    if (!$found) {
                                        //create new
                                        $generatedDt = M_Generated_Ticket_DT::create([
                                            'cd_et_generated_ticket_dt'     => get_prefix('et_generated_ticket_dt'),
                                            'id_et_generated_ticket_hd'     => $id_et_generated_ticket_hd,
                                            'id_et_cart_product_dt'         => $cartDt->id_et_cart_product_dt,
                                            'cd_generated_batch'            => $cd_generated_batch,
                                            'total_generated'               => 1,
                                            'qty'                           => $cartDt->qty_ticket,
                                            'generated_at'                  => date('Y-m-d H:i:s'),
                                            'generated_by'                  => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM',
                                            'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                                        ]);
                                    }

                                    $data['qr'][$x]['qr'] = QrCode::size(250)->margin(0)->format('png')->generate(
                                        $generatedDt->cd_et_generated_ticket_dt
                                    );
                                    $data['qr'][$x]['cartBd'] = $cartBd;
                                    $data['qr'][$x]['cartDt'] = $cartDt;
                                    $data['qr'][$x]['generatedDt'] = $generatedDt;
                                    $x++;
                                    $total_qty += $cartDt->qty_ticket;
                                }
                            }

                            //delete
                            M_Generated_Ticket_DT::where('id_et_generated_ticket_hd', $id_et_generated_ticket_hd)->where('cd_generated_batch', '!=', $cd_generated_batch)
                            ->update([
                                'is_active'           => 'N',
                                'deleted_at'          => date('Y-m-d H:i:s'),
                                'deleted_by'          => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                            ]);
                        }else{
                            $x = 0;
                            foreach ($cartHd->toCartProductBd as $cartBd) {
                                foreach ($cartBd->toCartProductDt as $cartDt) {
                                    $generatedDt_new = M_Generated_Ticket_DT::create([
                                        'cd_et_generated_ticket_dt'     => get_prefix('et_generated_ticket_dt'),
                                        'id_et_generated_ticket_hd'     => $id_et_generated_ticket_hd,
                                        'id_et_cart_product_dt'         => $cartDt->id_et_cart_product_dt,
                                        'cd_generated_batch'            => $cd_generated_batch,
                                        'total_generated'               => 1,
                                        'qty'                           => $cartDt->qty_ticket,
                                        'is_active'                     => 'Y',
                                        'generated_at'                  => date('Y-m-d H:i:s'),
                                        'generated_by'                   => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM',
                                        'created_by'                    => Auth::guard('administrator')->user()? Auth::guard('administrator')->user()->id_us_backend_hd : 'SYSTEM'
                                    ]);

                                    $data['qr'][$x]['qr'] = QrCode::size(250)->format('png')->generate(
                                        $generatedDt_new->cd_et_generated_ticket_dt
                                    );
                                    $data['qr'][$x]['cartBd'] = $cartBd;
                                    $data['qr'][$x]['cartDt'] = $cartDt;
                                    $data['qr'][$x]['generatedDt'] = $generatedDt_new;
                                    $x++;
                                    $total_qty += $cartDt->qty_ticket;
                                }
                            }
                        }
                        $data['email'] = $cartHd->toUsFrontendHd->email;
                        $detail = $cartHd->with(['toUsFrontendHd' => function ($query)  {
                            $query->with(['toUsFrontendDt' => function ($query2)  {
                                $query2->where('state', 'Y');
                            }]);
                        }]);

                        $data['detail'] = $detail->first()->toUsFrontendHd->toUsFrontendDt->first();
                        $data['total_ticket'] = $total_qty;
                        $data['order'] =  $order_paid->first();
                        PDF::loadView('PDF/template_ticket', $data)->save(storage_path('app/ticket/'.$path));
                        return redirect()->back()->with('success_custom', 'You successfully generate ticket for '.$data['detail']->first_name.' '.$data['detail']->last_name.' ('.$data['email'].') which ordered at '.$data['order']->created_at.'. Check generate result by downloading it.');
                    }else{
                        return  [
                            'success' => false,
                            'msg'       => 'Pembayaran kurang dari total jumlah harga tiket.'
                        ];
                    }
                }else{
                    return  [
                        'success' => false,
                        'msg'       => 'Order belum melakukan pembayaran.'
                    ];
                }
            }else{
                return  [
                    'success' => false,
                    'msg'       => 'Order tidak ditemukan.'
                ];
            }
        }
    }
}



    // Storage::disk('ticket')->put(
    //     $path . $generatedDt_new->cd_et_generated_ticket_dt,
    //         QrCode::size(500)->format('png')->generate($generatedDt_new->cd_et_generated_ticket_dt)
    // );
