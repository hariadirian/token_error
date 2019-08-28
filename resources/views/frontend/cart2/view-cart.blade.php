@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />
    <link href="{{ asset('css/my-frontend-style.css') }}" rel="stylesheet">

@endsection

@section('content')
<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li class="active">Cart</li>
			</ul>
		</div>
	</div>
	

	<!-- section -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row" id="row-cart">
			
				@include('frontend.cart.list-cart')

			</div>
			<div class="row" id="row-prep">			
				@include('frontend.cart.prepare-payment')
			</div>
		</div>
	</div>

@endsection

@section('js')

	<script src="{{ asset('frontend/js/main.js') }}"></script>
	<script src="{{ asset('js/pages/frontend/_Cart.js') }}"></script>
	<script type="text/javascript" src="https://sandbox-kit.espay.id/public/signature/js"></script>
	<script type="text/javascript">
		$( document ).ready(function() {
			var total_amount;
			var rbval, rbarr;
			$("#row-cart").css('display','');
			$("#row-prep").css('display','none');
			$('#modal-charge').css('display', 'none');

		});
		function createOrder($orderNumber, $totalAmount){
			$("#row-cart").css('display','none');
			$("#row-prep").css('display','');
			total_amount = $totalAmount;

			console.log('Orderan dengan no: '+$orderNumber+ ' OnProgress');
		}
		$(document).on('click', '.btnPay', function(e) {
			// console.log('Orderan dengan no: '+total_amount+' OnProgress');
			// if (!$('#terms').is(':checked')) {
			// 	$("html, body").animate({scrollTop: 0}, "slow");
			// 	JSHelper.Alert("Anda belum menyetujui syarat dan ketentuan yang belaku!");
			// 	return false;
			// }

			rbval = $(this).attr("payment-method-data");
			rbarr = rbval.split(";");
			var oCharge = rbarr[3];//accounting.formatNumber(rbarr[3], 0, ".");
			var amount = total_amount;//parseInt($("#total-topay-hidden").val());
			
			amount = amount + parseInt(rbarr[3]);
			// alert(rbarr[3]);	
			
			$("#payment-method-name").html("Metode pembayaran : " + rbarr[4]);
			var charge_message = "Biaya transaksi untuk metode pembayaran ini <b>Rp " + oCharge + "</span>,-</b>.";
			$("#charge-message").html(charge_message);
			$("#amount-aftercharge").val(amount);
			$("#btn-nextcharge").removeAttr("disabled");
			$("#btn-cancel").removeAttr("disabled");
			$('#modal-charge').modal({show: true, backdrop: 'static', keyboard: false});
			return false;

		});
		$(document).on('click', '#btn-cancel', function(e) { $('#modal-charge').modal('hide'); });
		$(document).on('click', '#btn-nextcharge', function(e) {
			if (typeof rbval == 'undefined')
				return;

			jQuery.ajax({
				type: "GET",
				url: "https://ticket.tamanmini.com/confirm/confirmordersave/?type_id=" + rbarr[0] + "&bank_code=" + rbarr[1],
				success: function(data) {
					//console.log(JSON.stringify(data));
					if (data.status == 0) { JSHelper.NeonNotifError(data.message); return false; }

					$("#divnotif_redirect").show();
					$("#to_page").html("Harap tunggu! Anda akan diarahkan ke-halaman untuk melanjutkan prosess pembayaran.");
					$(".conf_div").hide();

					if (rbarr[0] == "3") {
						window.location.href = "https://ticket.tamanmini.com/confirm/donemandiriva/" + rbarr[1] + "/" + $("#f_invoice_code").val();
						return;
					}

					if (rbarr[0] == "2" || rbarr[0] == "4") {
						var data = {
							key: "f11dc4dc682cf4e2df560d23a9c4d323",
							paymentId: $("#f_invoice_code").val(),
							paymentAmount: $("#total-topay-hidden").val(),
							backUrl: "https://ticket.tamanmini.com/confirm/donepayment/" + $("#f_invoice_code").val(),
							bankCode: rbarr[1],
							bankProduct: rbarr[2]
						}, sgoPlusIframe = document.getElementById("sgoplus-iframe");

						if (sgoPlusIframe !== null) {
							sgoPlusIframe.src = SGOSignature.getIframeURL(data);
						}

						SGOSignature.receiveForm();
						return;
					}
				}, error: function(data) { console.log(JSON.stringify(data)); }, complete: function() { }
			});
		});
		// function includeAsJsString($template)
		// {
		// 	$string = view($template);
		// 	return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));
		// }
	</script>
	

@endsection