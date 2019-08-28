@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />
  <link href="{{ asset('css/my-frontend-style.css') }}" rel="stylesheet">
	<style media="screen">
		#btnPrint {
			background-color: rgba(54, 153, 49, 1);
			color: #fff;
			border: 1px solid rgba(53, 166, 78, 1);
			padding: 0 1rem;
			height: 35px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
	</style>
@endsection

@section('content')
<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ URL::asset('/') }}">Home</a></li>
				<li class="active">Cart</li>
			</ul>
		</div>
	</div>


	<!-- section -->
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row" id="row-cart" style="display:none">

				@include('frontend.cart.list-cart')

			</div>
			<div class="row" id="row-prep" style="display:none">
				@include('frontend.cart.prepare-payment')
			</div>
			<div class="row" id="row-paystat" style="display:none">
				@include('frontend.cart.payment-status')
			</div>
			<div class="loader2" id="loader2" style="display:none">Loading...</div>
		</div>
	</div>
	<iframe id="sgoplus-iframe" src="" scrolling="no" style="display:none" frameborder="0">
        <h1>Pembayaran via Mandiri Supply Chain</h1>

    </iframe>
@endsection

@section('js')

	<script src="{{ asset('frontend/js/main.js') }}"></script>
	<script src="{{ asset('js/pages/frontend/_Cart.js') }}"></script>
	<script type="text/javascript" src="https://sandbox-kit.espay.id/public/signature/js"></script>
	<script type="text/javascript">
		$( document ).ready(function() {
			var amount;
			var total_amount;
			if ($("input.diskon").length > 1) {
				$("input.diskon").attr("name", "diskons[]");
			}
			else {
				$("input.diskon").attr("name", "diskon");
			}
			$('#loader2').show();
			if($('#hdCd').val() == undefined){
				window.location.replace(window.location.origin);
			}else{
				$('#row-cart').show();
				var order_number = $('#hdCd').val();
				$('#loader2').hide();
			}
			var rbval, rbarr;
			console.log(order_number);

            $.ajax({
                type: "POST",
                url: window.location.origin + "/payment_status2",
                data: {
                    "id": order_number,
                    "_token": $('meta[name="csrf-token"]').attr('content'),

                },
                success: function(ret0){
                    alert('BERHASIL!');
                },
                error: function () {
                    alert('Gagal!');
                },


            });

			$.ajax({
				type: "POST",
				url: window.location.origin + "/payment_status",
				data: {
					"id": order_number,
				},
				success: function(ret0){
					console.log('Laporan 1: '+ret0);
					$('#loader2').hide();
					switch(ret0['state']){
						case 1:
							$('#row-cart').show();
							break;
						case 5:
							$("#row-cart").hide();
							$("#row-prep").hide();
							$('#vanum').html(ret0.account_number);
							$('#full_user_name').html(ret0.full_user_name);
							$('#expdate').html(ret0.expired);
							$('#tot_amount').html("Rp. "+ret0.amount);
							$('#adminfee').html("Rp. "+ret0.admin_fee);
							$("#row-paystat").show();
							break;

						default:
							window.location.replace(window.location.origin);
					}
				}
			});
		});

		function createOrder($orderNumber, $totalAmount){
			var x = 1;
			$(".ticket-date-form").each(function(){

				if(!$(this).val()){
					alert('Tanggal belum diisi, isi semua tanggal pada setiap pemesanan!');
					x = 0;
					return false;
				}
				if(new Date($(this).val()+" 23:59:59") < new Date()){
					alert('Tanggal tidak boleh lebih kecil dari tanggal sekarang!');
					x = 0;
					return false;
				}
			});
			$(".cart-qty-form").each(function(){
				if(!$(this).val() || $(this).val() == 0){
					alert('Qty belum diisi, isi semua qty pada setiap pemesanan!');
					x = 0;
					return false;
				}
			});
			if(x){
				$("#row-cart").hide();
				$("#row-prep").show();
				total_amount = $totalAmount;
				order_number = $orderNumber;
			}
		}
		function number_format(number, decimals=2, dec_point=',', thousands_sep='.') {
			number = number.toFixed(decimals);

			var nstr = number.toString();
			nstr += '';
			x = nstr.split('.');
			x1 = x[0];
			x2 = x.length > 1 ? dec_point + x[1] : '';
			var rgx = /(\d+)(\d{3})/;

			while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + thousands_sep + '$2');

			return x1 + x2;
		}
		$(document).on('click', '.btnPay', function(e) {
			if (!$('#terms').is(':checked')) {
				$("html, body").animate({scrollTop: 0}, "slow");
				alert("Anda belum menyetujui syarat dan ketentuan yang belaku!");
				return false;
			}

			rbval = $(this).attr("payment-method-data");
			rbarr = rbval.split(";");
			var oCharge = rbarr[3];

			amount = total_amount + parseInt(rbarr[3]);

			$("#payment-method-name").html("Metode pembayaran : " + rbarr[4]);
			$("input[name='payment_method']").val(rbarr[4]);
			var charge_message = "Biaya transaksi untuk metode pembayaran ini <b>Rp " + oCharge + "</span>,-</b>.";
			$("#charge-message").html(charge_message);
			$("#amount-beforecharge, .total_tiket").val(total_amount);
			$("#amount-admin, input[name='biaya_admin']").val(rbarr[3]);
			$("#amount-aftercharge, input[name='sub_total']").val(amount);
			$("#btn-nextcharge").removeAttr("disabled");
			$("#btn-cancel").removeAttr("disabled");
			$('#modal-charge').modal({show: true, backdrop: 'static', keyboard: false});
			return false;

		});
		$(document).on('click', '#btn-cancel', function(e) { $('#modal-charge').modal('hide'); });
		$(document).on('click', '#btn-nextcharge', function(e) {
			$('#modal-charge').modal('hide');
			$('#loader2').show();
			if (typeof rbval == 'undefined')
				return;
            // alert('Mohon Tunggu sebentar...') ;
			jQuery.ajax({
				type: "POST",
				url: window.location.origin+"/prepare_order",
				data: {
					// _token : $('meta[name="csrf-token"]').attr('content'),
					"id"   :order_number,
					"payment_method": rbarr[2],
                    "sub_total" : amount,
                    "biaya_admin" : rbarr[3],
				},
				success: function(ret) {

				    alert('Paymnent method:'+rbarr[2]+ '\n Transaksi Kode:'+ret.kode+'\n Id Cart:'+ret.id_cart+'\n Jumlah Rp'+ret.amount+' ,SELESAI DIPROSES!') ;
                    // alert('Transaksi: '+ret.status) ;

					if (ret.status == 0) {

					    JSHelper.NeonNotifError('Report lagi'+ret.message); return false; }

					$("#divnotif_redirect").show();
					$('#loader2').hide();
					$("#to_page").html("Harap tunggu! Anda akan diarahkan ke-halaman untuk melanjutkan prosess pembayaran.");
					$(".conf_div").hide();

					if (rbarr[0] == "2" || rbarr[0] == "4") {
						var ret = {
							key: "c398d0fb7dc5ddd0f811d148b41765aa",
							paymentId: order_number,
							paymentAmount: parseInt(total_amount) + parseInt(rbarr[3]),//$("#total-topay-hidden").val(),
                            backUrl: window.location.origin,
                            // backUrl: 'http://35.240.234.157/payment_status',
                            bankCode: rbarr[1],
							bankProduct: rbarr[2]
						}, sgoPlusIframe = document.getElementById("sgoplus-iframe");
						console.log("Alamat redirect ke:"+ SGOSignature.getIframeURL(ret));

                        var newWin = window.open(SGOSignature.getIframeURL(ret),'_blank');

                        if(!newWin || newWin.closed || typeof newWin.closed=='undefined')
                        {
                            alert('Browser Anda mem-block Pop Up!');
                        }

                        window.open( SGOSignature.getIframeURL(ret),'_blank');
						if (sgoPlusIframe !== null) {
							sgoPlusIframe.src = SGOSignature.getIframeURL(ret);
						}

						SGOSignature.receiveForm();
						return;
					}else{
					    // alert('via ATM');
						$('#loader2').show();
						$("#row-cart").hide();
						$("#row-prep").hide();
						// console.log("jarang");alert(amount);return;
						$.ajax({
							type: "POST",
							url: window.location.origin + "/sendinv",
							data: {
								"bank_code": rbarr[1],
								"bank_product": rbarr[2],
								"order_number": order_number,
								"total_amount": total_amount,
								"adminfee": rbarr[3]
							},
							success: function(ret1){
								$("#row-cart").hide();
								$("#row-prep").hide();
								$('#vanum').html(ret1.VA_Number);
								$('#full_user_name').html(ret1.Full_User_Name);
								$('#expdate').html(ret1.Expired);
								$('#tot_amount').html("Rp. "+amount);
								$('#adminfee').html("Rp. "+rbarr[3]);
								$("#row-paystat").show();
								$('#loader2').hide();
								if ($("input[name='payment_method']").val("ATM")) {
									$("#sendMail").submit();
								}
							},
                            error: function(xhr, status, error) {
                              alert('Gagal kirim invoice') ;
                            }

						});
						// alert('order number: ' + order_number);
						// // window.location.href = "https://url/" + rbarr[1] + "/" + $("#f_invoice_code").val();
						// return;
					}

                    sendinv();
					// if (send === true){
                    //     // window.location.href = "/cart";
                    //     alert('Berhasi!');
                    // }
					// else
                    // {
                    //     // window.location.href = "/taikucing";
                    //     alert('Gagal');
                    // }

				}//, error: function(ret) { console.log(JSON.stringify(ret)); }, complete: function() { }
			});

			function sendinv (){
            let result ;
                jQuery.ajax({
                    type: "POST",
                    url: window.location.origin+"/sendinv2",
                    data: {
                        // _token : $('meta[name="csrf-token"]').attr('content'),
                        "input"  : "123",

                    },
                    success: function(results) {
                        alert('Berhasil! Result:' + results.hasil);
                        result = true ;
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        alert("Gagal 22! ");
                        result = false;
                    }
                });
                return result ;
            }
			    //send invoice



		});
	</script>
@endsection
