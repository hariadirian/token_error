<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        <div class="panel-body bg-bright">
            @foreach($tickets as $ticket)
            @php $cartHd    = $ticket->toCartProductHd @endphp
            @php $user_hd   = $cartHd->toUsFrontendHd @endphp
            @php $user_dt   = $user_hd->toUsFrontendDt @endphp
                <h3 style="margin-top:10px;margin-left:20px;margin-bottom:0px;color:#535353" class="pull-left">Detail Order</h3>
                <div class="col-md-12" style="padding-right: 20px;padding-left: 20px;margin-bottom:0px">
                    <hr style="border-top:1px solid #babeff"></hr>
                </div>  
                <div class="body">
                    <div>@php 
                        $summaryCartHd = $cartHd->summaryCartHd($ticket->id_et_cart_product_hd)->first();
                        
                        $product = '';
                        if ($summaryCartHd) {
                            if ($summaryCartHd->qty_regular and $summaryCartHd->qty_promo) {
                                $product = $summaryCartHd->qty_regular.' Product regular,  '.$summaryCartHd->qty_promo.' Product promotion ';
                            } elseif ($summaryCartHd->qty_regular) {
                                $product = 'Product regular '.$summaryCartHd->qty_regular;
                            } elseif ($summaryCartHd->qty_promo) {
                                $product = 'Product promotion '.$summaryCartHd->qty_promo;
                            }
                        }   
                        @endphp

                        <label style="width:100%">Ordered: {{ $product }} 
                            <span class="pull-right">Total ticket that will be generated: 
                                {{ $summaryCartHd->sum_qty_product }} Tickets 
                            </span> 
                        </label>
                        <br />
                        <br />
                        <div style="box-shadow: 1.5px 1.5px #cecece;padding: 10px 5px 20px 5px;margin-bottom: 15px;background-color:white">
                            <table class="table table-condensed" style="background-color: inherit;margin-bottom: 0px;">
                                <tbody>
                                    <tr>
                                        <td width="20%" class="td-condensed"><b>Email</b></td>
                                        <td width="30%" class="border-right td-condensed" >: {{ $user_hd->email }}</td>
                                        <td width="20%" class="td-condensed"><b>Full Name</b></td>
                                        <td width="30%" class="border-right td-condensed" >: {{ $user_dt[0]->first_name . ' ' . $user_dt[0]->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td  class="td-condensed"><b>Mobile Phone</b></td>
                                        <td class="border-right td-condensed" >: {{ $user_dt[0]->mobile_phone }}</td>
                                        <td class="td-condensed" ><b>ID Card</b></td>
                                        <td class="td-condensed" >: {{ $user_dt[0]->id_card }}</td>
                                    </tr>
                                    <tr>
                                        <td  class="td-condensed"><b>Address</b></td>
                                        <td class="border-right td-condensed" colspan="3">: {{ $user_dt[0]->address }}</td>
                                    </tr>
                                    <tr>
                                        <td class="td-condensed" ><b>Ordered At</b></td>
                                        <td class="border-right td-condensed" >: {{ $ticket->ordered_at }}</td>
                                        <td class="td-condensed" ><b>Ordered By</b></td>
                                        <td class="td-condensed" >: {{ isset($ticket->toCreatedBy->email)? $ticket->toCreatedBy->email : ''  }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div id="accordion_8" role="tablist" aria-multiselectable="true">
                            <div class="panel-warning" style="width: 100%; height: 20px; border-bottom: 1px solid black; text-align: center;margin-bottom:30px;cursor:pointer">
                                <span  role="button" data-toggle="collapse" data-parent="#accordion_8" href="#collapseOne_8" aria-expanded="true" aria-controls="collapseOne_8" style="font-size: 13px; background-color: #ECF3FF; padding: 0 10px;position:relative">
                                <i> Payment </i><i class="material-icons" style="top: 5px;position: relative">arrow_drop_down</i><!--Padding is optional-->
                                </span>
                            </div>
                            <div id="collapseOne_8" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne_8">
                                <div class="panel-body" style="padding-top:0px">

                                <div style="box-shadow: 1.5px 1.5px #cecece;padding: 10px 5px 5px 5px;margin-bottom: 15px;background-color:{{ ($ticket->paid_state == '1')? '#ff9600' : (($ticket->paid_state == '2')? 'green' : 'red') }}">
                                    <div style="margin:30px 20px 20px 20px;border:1px solid #a1a1a1;text-align:center">
                                        <span style="font-size: 30px;color:white;background-color: {{ ($ticket->paid_state == '1')? '#ff9600' : (($ticket->paid_state == '2')? 'green' : 'red') }};padding: 0 10px;position: relative;top: -20px;" class="">
                                        <b>{{ $ticket->toMsPaidState->paid_state_name }}</b> 
                                        </span>
                                        <table class="table table-condensed" style="background-color: inherit;margin-bottom: 0px;color:white;position:relative;top:-10px">
                                            <tbody>
                                                <tr>
                                                    <td width="20%" class="td-condensed"><b>Total Amount</b></td>
                                                    <td width="30%" class="border-right td-condensed">: {{ "Rp " . number_format($ticket->total_amount,0,',','.') }}</td>
                                                    <td width="20%" class="td-condensed"><b>Invoice</b></td>
                                                    <td width="30%" class="border-right td-condensed">: <a href="">Resend</a></td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" class="td-condensed"><b>Payment Method</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $cartHd->payment_method }}</td>
                                                    <td width="20%" class="td-condensed"><b>Still in cart</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $cartHd->is_done == 'N'? 'Yes' : 'No' }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" class="td-condensed"><b>Bank Name</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $cartHd->bank_name }}</td>
                                                    <td width="20%" class="td-condensed"><b>Account Name</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $cartHd->account_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%" class="td-condensed"><b>Paid At</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $ticket->paid_at }}</td>
                                                    <td width="20%" class="td-condensed"><b>Created At</b></td>
                                                    <td width="30%" class="border-right td-condensed" >: {{ $ticket->created_at }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                        @php $cartBd = $cartHd->toCartProductBd->where('state', 'Y')->where('is_active', 'Y') @endphp
                        <div class="panel-group" id="accordion_1" role="tablist" aria-multiselectable="true">
                            @foreach($cartBd as $bd)
                                <div class="panel panel-primary">
                                    <div class="panel-heading" role="tab" id="heading_{{ $bd->cd_et_cart_product_bd }}">
                                        <h4 class="panel-title">
                                            <a role="button" data-toggle="collapse" data-parent="#accordion_1" href="#collapse_{{ $bd->cd_et_cart_product_bd }}" aria-expanded="true" aria-controls="collapse_{{ $bd->cd_et_cart_product_bd }}">
                                            TIKET {{ $bd->product_name }}
                                            
                                        <span style="float:right;font-size:12px;font-weight:initial;">{{ $bd->ticket_type }}</span>
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="collapse_{{ $bd->cd_et_cart_product_bd }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_{{ $bd->cd_et_cart_product_bd }}">
                                        <div class="panel-body" style="padding-left: 0px;padding-right: 0px;padding-bottom: 0px;">
                                            <table class="table table-condensed table-hover" style="margin-bottom: 0px;">
                                                <thead>
                                                    <tr>
                                                        <th colspan="4" style="text-align:center">Detail Product</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="td-wrap" style="width:15%"><b>Ticket Name</b></td>
                                                        <td class="td-wrap" style="width:75%;text-align:left" colspan="3">: @foreach($bd->toCartProductDt->where('is_active', 'Y')->where('state', 'Y') as  $key => $dt) 
                                                        {{ $key == 0? $dt->ticket_name : ', ' . $dt->ticket_name }}
                                                        @endforeach</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-wrap" style="width:15%"><b>Ticket Date</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: {{ $bd->ticket_date }}</td>
                                                        <td class="td-wrap" style="width:15%"><b>Qty Product</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: {{ $bd->qty_product }} Tickets</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-wrap" style="width:15%"><b>Total Amount</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: {{ "Rp " . number_format($bd->total_amount,0,',','.') }}</td>
                                                        <td class="td-wrap" style="width:15%"><b>Discount</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: 
                                                            @php 
                                                            $promo_hd = $bd->toPromoTXHD->where('state', 'Y')->where('is_active', 'Y')->first();
                                                            $promo_dt = $promo_hd? $promo_hd->toPromoTxDt->where('state', 'Y')->where('is_active', 'Y')->first() : '-';
                                                            @endphp
                                                            {{  $promo_dt != '-'? $promo_dt->type_promo == 'ABSOLUTE'? "Rp " . number_format($promo_dt->amount_promo * $bd->qty_product,0,',','.') : $promo_dt->amount_promo.'%' : '-'}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-wrap" style="width:15%"><b>Created At</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: {{ $bd->created_at }}</td>
                                                        <td class="td-wrap" style="width:15%"><b>Created By</b></td>
                                                        <td class="td-wrap" style="width:35%;text-align:left">: {{ isset($bd->toCreatedBy->email)? $bd->toCreatedBy->email : '' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
            @endforeach
        </div>
    </div>
</div>
