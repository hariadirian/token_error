<div class="row" style="text-align:left;color:white;">
    <div class="col-md-12">
        <div class="panel-body bg-bright">
            @foreach($tickets as $ticket)
            @php $cartHd    = $ticket->toCartProductHd @endphp
            @php $generated_hd    = $ticket->toGenerateTicketHd? $ticket->toGenerateTicketHd : '' @endphp
            @php $user_hd   = $cartHd->toUsFrontendHd @endphp
            @php $user_dt   = $user_hd->toUsFrontendDt->where('state', 'Y') @endphp
                <h3 style="margin-top:10px;color:black !important;margin-left:20px;margin-bottom:0px;color:#535353" class="pull-left">Detail Tickets</h3>
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
                            <span class="pull-right">Total Generated Ticket: 
                                @if($generated_hd) 
                                    <a href="{{ route('ticket.download') }}" onclick="event.preventDefault(); document.getElementById('download-form').submit();" style="color:red">
                                        {{ $summaryCartHd->sum_qty_product }} Ticket <span id="span-total-qty"></span>
                                    </a> 
                                @else
                                    <span onclick="alert('Ticket is not generated yet. Please complete the payment or click regenerate before downloading ticket.')" style="color:red;cursor:pointer">
                                        {{ $summaryCartHd->sum_qty_product }} Ticket <span id="span-total-qty"></span>
                                    </span> 
                                @endif
                                <a href="#" onclick="event.preventDefault(); document.getElementById('regenerate-form').submit(); document.getElementById('refresh').submit(); ">
                                    Regenerate Ticket
                                </a>
                            </span> 
                        </label>
                        @if($generated_hd)
                            {!! Form::open(['route' => 'ticket.download', 'id' => 'download-form', 'style' => 'display:none']) !!}
                            {!! Form::text('cd_generated_ticket', $generated_hd->cd_et_generated_ticket_hd, ['class' => 'form-control']) !!}
				            {!! Form::close() !!}
                        @endif

                        {!! Form::open(['route' => 'ticket.regenerate', 'id' => 'regenerate-form', 'style' => 'display:none']) !!}
                        {!! Form::text('cd_ordered_ticket', $ticket->cd_et_ordered_ticket_txes, ['class' => 'form-control']) !!}
				        {!! Form::close() !!}
                        @if($generated_hd)
                            <br />
                            <br />
                            <table class="table table-condensed">
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Email</b></td>
                                        <td width="30%" class="border-right">{{ $user_hd->email }}</td>
                                        <td width="20%"><b>Full Name</b></td>
                                        <td width="30%" class="border-right">{{ $user_dt[0]->first_name . ' ' . $user_dt[0]->last_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Email sent at</b></td>
                                        <td class="border-right">{{ $generated_hd->email_sent_at }} <a onclick="event.preventDefault(); document.getElementById('send-email').submit();">Resend</a></td>
                                        <td><b>Total Downloaded</b></td>
                                        <td>{{ $generated_hd->total_downloaded }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            
                            @if($generated_hd)
                                {!! Form::open(['route' => 'ticket.email.resend', 'id' => 'send-email', 'style' => 'display:none']) !!}
                                {!! Form::text('cd_ordered_ticket',  $ticket->cd_et_ordered_ticket_txes, ['class' => 'form-control']) !!}
                                {!! Form::close() !!}
                            @endif
                        @endif
                    </div>
                    @php $total_qty = 0 @endphp 
                    @if($generated_hd)
                        @foreach($generated_hd->toGeneratedTicketDt->where('state', 'Y')->where('is_active', 'Y') as $ticket_dt)
                            @php $cartDt = $ticket_dt->toCartProductDt @endphp
                            @php $cartBd = $cartDt->toCartProductBd @endphp
                            @php $total_qty += $cartDt->qty_ticket @endphp

                            @php $srcname = '' @endphp
                            @php $filename = '' @endphp
                            @if(isset($cartBd->toTicketImgHd))
                                @if(isset($cartBd->toTicketImgHd->toTicketImgDt))
                                    @if($cartBd->toTicketImgHd->toTicketImgDt->count())
                                        @php $srcname = Storage::url($cartBd->toTicketImgHd->toTicketImgDt->first()->srcname) @endphp
                                    @endif
                                @endif
                            @endif
                            <div class="list-group">
                                <div class="list-group-item" style="border:none;box-shadow: 1.5px 1.5px #ccc;">
                                    <div class="media" style="margin:10px 0 10px 0">
                                        <div class="media-left">
                                            <a href="javascript:void(0);">
                                                <img class="media-object" src="{{ $srcname }}" width="104" height="104">
                                            </a>
                                        </div>
                                        <div class="media-body">
                                            <h4 class="media-heading" style="color:black;">
                                                {{ ucwords($cartBd->product_name) }}<br />
                                                <small>{{ ucwords($cartDt->ticket_name) }}</small>
                                            </h4> 
                                            <div class="col-sm-12" style="margin:0;padding:0">
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Total Qty</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : {{ $cartDt->qty_ticket }} Ticket
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Ticket Type</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : {{ $cartBd->ticket_type }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>First redeem</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : @php $first_redeem = $ticket_dt->toRedeemedTicket()->where('state', 'Y')->where('is_active', 'Y')->orderBy('created_at', 'asc')->first() @endphp
                                                        {{ $first_redeem? $first_redeem->created_at : '-' }}
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Redeem Qty</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : @php $redeem_qty =  $ticket_dt->toRedeemedTicket->where('state', 'Y')->where('is_active', 'Y')->sum('qty_redeemed') @endphp 
                                                        {{ $redeem_qty }} Ticket
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Ticket Date</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : {{ $cartBd->ticket_date }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Last redeem</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : @php $last_redeem = $ticket_dt->toRedeemedTicket()->where('state', 'Y')->where('is_active', 'Y')->orderBy('created_at', 'desc')->first() @endphp
                                                        {{ $last_redeem? $last_redeem->created_at : '-' }}
                                                    </div>
                                                </div>
                                                <div class="col-sm-4" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-4" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Available Qty</b>
                                                    </div>
                                                    <div class="col-sm-8" style="padding:0;margin-bottom:0px;color:#157ca2"> 
                                                        : {{ $cartDt->qty_ticket - $redeem_qty }} Ticket
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-sm-8" style="padding:0;margin-bottom:5px">
                                                    <div class="col-sm-2" style="padding:0;margin-bottom:0px;color:#463ad0;">
                                                        <b>Organization</b>
                                                    </div>
                                                    <div class="col-sm-10" style="padding:0;margin-bottom:0px;color:#157ca2">
                                                        : 
                                                        @if($cartDt->toBackendOrganization)
                                                            @foreach($cartDt->toBackendOrganization as $key => $org)
                                                                {{ ($key == 0)? $org->organization_name : ', ' .$org->organization_name }} 
                                                            @endforeach
                                                        @else
                                                            {{ '<i>Not setup yet</i>' }}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
            <input id="input-total-qty" type="hidden" value="" />
            <script>
                $(function () {
                    $('#span-total-qty').html("{{ '('.$total_qty . ' qty)' }}");
                });
            </script>
        </div>
    </div>
</div>
