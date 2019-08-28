<div class="col-sm-12" style="background-color:#0d7fff">
	<div class="flower-type col-sm-12" style="padding-bottom:0px;margin-top:1em !important; border:1px solid #cbcbcb;margin-bottom:15px">
		<div class="col-sm-12">
			<h3 style="color:#ffffffa1;margin-bottom: 0px;"><b>Harga Product</b></h3>
			
			<div style="color:white;margin-bottom:10px;margin-top:0px;font-size:40px;text-align:center">Rp. {{ number_format( $total_amountraw,0,',','.') }}</div>
		</div>
	</div>
</div>
<div class="btn_form pull-right" style="margin-top: 1em;">
	<a href="#" onclick="event.preventDefault(); document.getElementById('cart-form').submit();">
		Add to cart
	</a>
</div>