<div class="product-body col-sm-12" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);padding: 10px 20px 20px 20px;background:#fff">
	<h5 class="product-name" style="margin-bottom: 15px;padding-bottom:10px;border-bottom: 1px solid #DADADA;">Rincian Pemesanan</h5>
	
	<div class="col-sm-12" style="padding:0;border-bottom: 1px dotted #ccc;">
		<div class="col-sm-9" style="padding:0">
			<h5><?= strtoupper($result->name) ?></h5>
		</div>
		<div class="col-sm-3" style="padding:0">
			<p class=" pull-right" style="color:#888"><span style="margin-left:20px">{{ $result->date }}</span></p>
		</div>
	</div>
	
	<div class="col-sm-12" style="padding:0;padding:10px 0px 0px 0px"></div>
	<div class="col-sm-5 " style="padding:0">
		<p style="color:#424242">Harga Tiket Normal</p>
	</div>
	<div class="col-sm-3 " style="padding:0">
		<p style="color:#424242">{{ $result->type == 'PROMOTION'? 'Syarat Promo' : 'Qty' }}</p>
	</div>
	<div class="col-sm-4" style="padding:0">
		<p style="color:#424242" class=" pull-right">Total</p>
	</div>
	<div class="col-sm-12" style="padding:0;border-bottom: 1px dotted #DADADA;">
		<div class="col-sm-5 " style="padding:0">
			<p style="color:#888">{{ $result->amount_text_satuan_b4_promo }}</p>
		</div>
		<div class="col-sm-3 " style="padding:0;text-align:center">
			<p style="color:#888">x {{ $result->type == 'PROMOTION'? $result->min_val : $result->qty }} Tiket</p>
		</div>
		<div class="col-sm-4" style="padding:0">
			<p style="color:#888" class=" pull-right">{{ $result->text_amount_b4_promo }}</p>
		</div>
	</div>
	<div class="col-sm-12" style="padding:0;margin-top:10px">
		<div class="col-sm-8 pull-left" style="padding:0">
			<p style="color:#888"><strong>Jumlah Pembelian Promo</strong></p>
		</div>
		<div class="col-sm-4 " style="padding:0">
			<p style="color:#888" class="pull-right">x {{ $result->qty }}</p>
		</div>
	</div>
	<div class="col-sm-12" style="padding:0;border-bottom: 1px solid #DADADA;margin-top:10px">
		<div class="col-sm-8 pull-left" style="padding:0">
			<p style="color:#888"><strong>[PROMO] TOTAL POTONGAN</strong> <br />(Potongan per {{ $result->min_val }} tiket adalah {{ $result->promo_type == 'P'? $result->promo."%" : $result->promo_value }})</p>
		</div>
		<div class="col-sm-4 " style="padding:0">
			<p style="color:#888" class="pull-right">Rp. {{ $result->promo_type == 'P'? $result->promo_value : number_format($result->promo * $result->qty, 0, ',', '.')  }}</p>
		</div>
	</div>
	<div class="col-sm-12" style="padding:0;margin-top:20px !important">
		<div class="col-sm-6" style="padding:0;">
			<a href="{{ !Auth::user()?  $result->type == 'PROMOTION'?  URL::asset('promotion/view/'.$result->cd.'/Y')  : URL::asset('product/view/'.$result->cd.'/Y') : '#' }}" style="text-align:center" class="primary-btn add-to-cart col-sm-12" @if(Auth::user()) onclick="event.preventDefault(); document.getElementById('add-to-cart').submit();"  @endif><i class="fa fa-shopping-cart"></i> Add to Cart</a>
		</div>
		<div class="col-sm-6 " style="padding:10px 0px 0px 20px;">
			<h4>TOTAL: <span class="pull-right">{{ $result->amount_text }}</span></h4>
		</div>
	</div>
</div>