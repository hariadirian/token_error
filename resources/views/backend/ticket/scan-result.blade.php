<div class="row" style="margin-top:20px">
    @if(isset($redeem_state))
        <div>
            <div class="col-sm-12" style="text-align:center">
                <label class="form-label" style="color:red !important">YOU HAVE SUCESSFULLY REEDEMED THE TICKET!</label>
            </div>
            <div class="col-sm-12" style="text-align:center;height:180px">
                <label id="leftover-tickets-info" class="form-label" style="font-size:230px;top:-80px;position:relative;color:red  !important"></label>
            </div>
            <div class="col-sm-12" style="text-align:center">
                <label class="form-label" style="color:red !important">Please check the redeemed ticket in history.</label>
            </div>
            <div class="col-sm-12" style="text-align:center">
                <a class="btn btn-warning" href="{{ URL::asset('ticket/scan') }}">Back</a>
            </div>
        </div>
    @else
        <div>
            <div class="col-sm-12" style="text-align:center">
                <label class="form-label" style="color:red !important"><h3>TIKET WAHANA {{ $product->attributeset_name }}</h3></label>
            </div>
            <div class="col-sm-12" style="text-align:center">
                <label class="form-label" style="color:red !important">YOU HAVE</label>
            </div>
            <div class="col-sm-12" style="text-align:center;height:180px">
                <label id="leftover-tickets-info" class="form-label" style="font-size:230px;top:-80px;position:relative;color:red  !important"></label>
            </div>
            <div class="col-sm-12" style="text-align:center">
                <label class="form-label" style="color:red !important">Ticket left to redeem!</label>
            </div>
            <div class="col-sm-12" style="text-align:center">
                <button class="btn btn-primary" id="redeem">Redeem Ticket!</label>
            </div>
        </div>
    @endif
    <div class="col-sm-12" style="text-align:center">
        <hr>
    </div>

    <div class="col-sm-12">
        <label class="form-label">Redeem History</label>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-hover table-condensed table-striped">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Qty Redeem</th>
                    <th>Redeemed By</th>
                    <th>Redeemed At</th>
                </tr>
            </thead>
            <tbody>
                @php $used = 0 @endphp
                @foreach($redeem as $redeem_key => $redeem_data)
                    <tr>
                        <td>{{ $redeem_key + 1 }}</td>
                        <td>{{ $redeem_data->qty_redeemed }} Ticket</td>
                        <td>{{ $redeem_data->toCreatedBy->username }}</td>
                        <td>{{ $redeem_data->created_at }}</td>
                    </tr>
                    @php $used = $used + $redeem_data->qty_redeemed @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <input id="leftovers-val" readonly type="hidden" value="{{ $ticket->qty - $used }}" />
    <input id="cd" readonly type="hidden" value="{{ $ticket->cd_et_generated_ticket_dt }}" />

    <div class="col-sm-12">
        <label class="form-label">Ticket Detail</label>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-hover table-condensed table-striped">
            <tbody>
                <tr>
                    <td style="width:40%"><label>Ticket Name</label></td>
                    <td style="width:60%">{{ $product->name }}</td>
                </tr>
                <tr>
                    <td><label>Category</label></td>
                    <td>{{ $product->product_category }}</td>
                </tr>
                <tr>
                    <td><label>Ticket Type</label></td>
                    <td>{{ $ticket_cart->toCartProductBd->ticket_type }}</td>
                </tr>
                @if($ticket_cart->toCartProductBd->ticket_type == 'PROMOTION')
                    <tr>
                        <td><label>Promo Name</label></td>
                        <td>{{ $ticket_cart->toCartProductBd->product_name }}</td>
                    </tr>
                @endif
                <tr>
                    <td><label>QTY</label></td>
                    <td><span id="total-ticket">{{ $ticket->qty }}</span> Ticket</td>
                </tr>
                <tr>
                    <td><label>Ticket Date</label></td>
                    <td>{{ $ticket_cart->toCartProductBd->ticket_date }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="col-sm-12">
        <label class="form-label">Payment Detail</label>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-hover table-condensed table-striped">
            <tbody>
                <tr>
                    <td style="width:40%"><label>Ordered At</label></td>
                    <td style="width:60%">{{ $order->created_at }}</td>
                </tr>
                <tr>
                    <td><label>Paid At</label></td>
                    <td>{{ $order->paid_at }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-sm-12 ">
        <a class="btn btn-warning pull-right" href="{{ URL::asset('ticket/scan') }}">Back to scanner</a>
    </div>
</div>