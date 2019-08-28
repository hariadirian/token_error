@extends('layouts.frontend-skeleton')

@section('content')
<script type="text/javascript" src="https://sandbox-kit.espay.id/public/signature/js"></script>
<div class="cart">
	 <div class="container">
			 <ol class="breadcrumb">
		  <li><a href="{{ URL::asset('/') }}">Home</a></li>
		  <li class="active">Cart</li>
		  <a href="{{ URL::asset('riwayat/pemesanan') }}"><p class="pull-right">Riwayat Pemesanan</p></a>
		 </ol>
			

		 <div class="col-md-9 cart-items">
			 <h2>Tiket pembelian</h2>
				@php $total = 0; @endphp
				@if(isset($carts->toCartProductDt))
					@foreach($carts->toCartProductDt as $cart)
						@if($carts->is_done == 'N')
							<script>
								$(document).ready(function(c) {
									$('#cartclose-{{ $cart->cd_et_cart_product_dt }}').on('click', function(c){
										$('#cartheader-{{ $cart->cd_et_cart_product_dt }}').fadeOut('slow', function(c){
											$.ajax({
												url: $('.mainurl').val() +'/cart/remove/{{ $cart->cd_et_cart_product_dt }}',
												type: "GET",
												cache: false,
												success:function(data)
												{
													if($.trim(data) == "true"){
														$('#cartheader-{{ $cart->cd_et_cart_product_dt }}').remove();
														$.fn.change_price();
													}
												}
											});
										});
										});	  
									});
							</script>
						@endif
						<div class="cart-header" id="cartheader-{{ $cart->cd_et_cart_product_dt }}">
							@if($carts->is_done == 'N')
								<div class="close" id="cartclose-{{ $cart->cd_et_cart_product_dt }}"> </div>
							@endif
							<div class="cart-sec">
									<div class="cart-item cyc">
										<img src="new_fashions/images/ticket.png"/>
									</div>
								<div class="cart-item-info">
									<h3>{{ $product[$cart->idem_ticket_uu]['name'] }}<span>Tiket untuk tanggal : {{ date("M d, Y", strtotime($product[$cart->idem_ticket_uu]['ticket_date'])) }}</span></h3>
									<h4><span>Rp. </span>{{ number_format($product[$cart->idem_ticket_uu]['price'],0,',','.') }}</h4>
									<p class="qty">Qty ::</p>
										<input min="{{ $product[$cart->idem_ticket_uu]['min_qty'] }}" max="{{ $product[$cart->idem_ticket_uu]['max_qty'] }}" type="number"  name="qty-{{$cart->cd_et_cart_product_dt}}" value="{{ $cart->qty }}" class="form-control input-small quantity" style="width:13%">
								</div>
								<div class="clearfix"></div>
									<div class="delivery">
										<p>Minimum <span style="color:#f5a623;float:none;">{{ $product[$cart->idem_ticket_uu]['min_qty'] }} Tiket</span> | Maximum <span style="color:#f5a623;float:none;">{{ $product[$cart->idem_ticket_uu]['max_qty'] }} Tiket</span>  </p>
										<!-- <span>Delivered in 2-3 bussiness days</span> -->
										<div class="clearfix"></div>
									</div>						
							</div>
						</div>
						@php $total += $product[$cart->idem_ticket_uu]['price'] * $product[$cart->idem_ticket_uu]['qty_fix'] @endphp
					@endforeach
				@endif
				</div>

		@if(isset($carts->toCartProductDt))
			<div class="col-md-3 cart-total">
				
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog" role="document" style="margin-top:190px">
						<div class="modal-content">
							<div class="modal-header">
								<h4 class="modal-title" id="exampleModalLabel">Data Diri Anda</h4>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
								</button>
							</div>

							{!! Form::open(['route' => 'assign.profile', 'autocomplete' => 'off']) !!}

								<div class="modal-body">
									<div class="row"> 
										<div class="form-group"> 

											{{ Form::hidden('cd', $identity->cd_us_frontend_dt, ['readonly']) }}

											<div class="col-sm-6">
												{!! Form::label('nama_depan', 'Nama Depan') !!}
											</div>
											<div class="col-sm-6">
												{!! Form::label('nama_belakang', 'Nama Belakang') !!}
											</div>
											<div class="col-sm-6">
												{!! Form::text('nama_depan', $identity->first_name, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nama Depan...']) !!}

												@if ($errors->has('nama_depan'))
													<span class="help-block">
														<strong>{{ $errors->first('nama_depan') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-6">
												{!! Form::text('nama_belakang', $identity->last_name, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nama Belakang...']) !!}

												@if ($errors->has('nama_belakang'))
													<span class="help-block">
														<strong>{{ $errors->first('nama_belakang') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-6">
												{!! Form::label('ktp', 'Nomor KTP') !!}
											</div>
											<div class="col-sm-6">
												{!! Form::label('no_hp', 'Nomor HP') !!}
											</div>
											<div class="col-sm-6">
												{!! Form::text('ktp', $identity->id_card, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nomor KTP...']) !!}

												@if ($errors->has('ktp'))
													<span class="help-block">
														<strong>{{ $errors->first('ktp') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-6">
												{!! Form::text('no_hp', $identity->mobile_phone? : 0, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nomor HP...']) !!}

												@if ($errors->has('no_hp'))
													<span class="help-block">
														<strong>{{ $errors->first('no_hp') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-12">
												{!! Form::label('alamat', 'Alamat') !!}
											</div>
											<div class="col-sm-12">
												{!! Form::textarea('alamat', $identity->address, ['class' => 'form-control', 'style' => 'width:100%;height:90px;', 'placeholder' => 'Masukkan Alamat...']) !!}

												@if ($errors->has('alamat'))
													<span class="help-block">
														<strong>{{ $errors->first('alamat') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-12">
												{!! Form::label('institusi', 'Institusi') !!}
											</div>
											<div class="col-sm-12">
												{!! Form::text('institusi', $identity->institute, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Institusi (Kosongkan apabila perorangan)...']) !!}

												@if ($errors->has('alamat'))
													<span class="help-block">
														<strong>{{ $errors->first('institusi') }}</strong>
													</span>
												@endif
											</div>
											<div class="col-sm-12" style="padding-top:30px;color:grey">
												<p>Semua informasi termasuk tiket akan dikirimkan melalui email : {{ Auth::user()->email }}, hubungi cs eticketing tmii apabila terjadi kesalahan.</p>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">
									{!! Form::submit('Submit', ['class' => 'btn btn-primary form-control', 'style' => 'background-color:#3c2f90']) !!}
								</div>

							{!! Form::close() !!}
						</div>
					</div>
				</div>
				<div class="price-details" style="padding-bottom:27px">
					<h3>Price Details</h3>
					<span>Total</span>
					<span class="total" id="total">{{ number_format($total,0,',','.') }}</span>
				</div>	
				<h4 class="last-price">TOTAL</h4>
				<span class="total final" id="total_final">{{ number_format($total,0,',','.') }}</span>
				<div class="clearfix"></div>
				
				@if($payment == true)
					<button type="button" class="btn btn-warning col-sm-12" onclick="create_order(this)" id="<?= $carts->cd_et_cart_product_hd; ?>" style="margin-bottom:20px;margin-top:20px" >Lakukan Pembayaran</button>
					<!-- data-toggle=modal data-target=#paymentModal  -->
				@else
					<button type="button" class="btn btn-danger col-sm-12 cd_p" onclick="alert('Isi data diri terlebih dahulu'); "  style="margin-bottom:20px;margin-top:20px" >Lakukan Pembayaran</button>
					<!-- <button type="button" class="btn btn-warning col-sm-12" data-toggle=modal data-target=#paymentModal   style="margin-bottom:20px;margin-top:20px" >Lakukan Pembayaran</button> -->
				@endif

				@if($payment == true)
					<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						<div class="modal-dialog" role="document" style="margin-top:190px">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title" id="exampleModalLabel">Pembayaran Tiket</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<?php /*
									{!! Form::open(['route' => 'assign.payment', 'autocomplete' => 'off']) !!}

										<div class="modal-body">
											<div class="row"> 
												<div class="form-group"> 

													{{ Form::hidden('cd_p', $carts->cd_et_cart_product_hd, ['readonly']) }}

													<div class="col-sm-6">
														{!! Form::label('metode_pembayaran', 'Metode Pembayaran') !!}
													</div>
													<div class="col-sm-12">
														<div class="col-sm-4">
															<label for="atm">
																<img src="{{ URL::asset('img/atm_bersama.png') }}" />
															</label>
															{!! Form::radio('metode_pembayaran', 'DEBIT', false, ['class' => 'form-control', 'id' => 'atm']); !!}
														</div>
														<div class="col-sm-4">
															<label for="mandiri">
																<img src="{{ URL::asset('img/mandiri.png') }}" />
															</label>
															{!! Form::radio('metode_pembayaran', 'MANDIRI_SUPPLY_CHAIN', false, ['class' => 'form-control', 'id' => 'mandiri']); !!}
														</div>
														<div class="col-sm-4">
															<label for="kredit">
																<img  src="{{ URL::asset('img/master.jpg') }}" />
															</label>
															{!! Form::radio('metode_pembayaran', 'CREDIT', false, ['class' => 'form-control', 'id' => 'kredit']); !!}
														</div>
														<!-- {!! Form::select('metode_pembayaran', ['' => '', 'DEBIT' => 'KARTU DEBIT/ATM BERSAMA', 'CREDIT' => 'KARTU KREDIT']); !!} -->

														@if ($errors->has('metode_pembayaran'))
															<span class="help-block">
																<strong>{{ $errors->first('metode_pembayaran') }}</strong>
															</span>
														@endif
													</div>
													<div class="col-sm-12 debit-form">
														{!! Form::label('nama_bank', 'Nama Bank') !!}
													</div>
													<div class="col-sm-12 debit-form">
														{!! Form::text('nama_bank', $identity->bank_name, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nama Bank...']) !!}

														@if ($errors->has('nama_bank'))
															<span class="help-block">
																<strong>{{ $errors->first('nama_bank') }}</strong>
															</span>
														@endif
													</div>
													<div class="col-sm-12 debit-form">
														{!! Form::label('nama_rek', 'Nama Rekening') !!}
													</div>
													<div class="col-sm-12 debit-form">
														{!! Form::text('nama_rek', $identity->account_name, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nama Rekening...']) !!}

														@if ($errors->has('nama_rek'))
															<span class="help-block">
																<strong>{{ $errors->first('nama_rek') }}</strong>
															</span>
														@endif
													</div>

													<div class="col-sm-12 credit-form">
														{!! Form::label('nomor_kartu', 'Nomor_Kartu') !!}
													</div>
													<div class="col-sm-12 credit-form">
														{!! Form::text('nomor_kartu', $identity->cc_number, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Nomor Kartu...']) !!}

														@if ($errors->has('nomor_kartu'))
															<span class="help-block">
																<strong>{{ $errors->first('nomor_kartu') }}</strong>
															</span>
														@endif
													</div>
													<div class="col-sm-6 credit-form">
														{!! Form::label('tgl_expired', 'Tanggal Expired') !!}
													</div>
													<div class="col-sm-6 credit-form">
														{!! Form::label('cvv', 'CVV') !!}
													</div>
													<div class="col-sm-6 credit-form">
													{!! Form::text('tgl_expired', $identity->cc_expired_date, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan Tanggal Expired Kartu...']) !!}

														@if ($errors->has('tgl_expired'))
															<span class="help-block">
																<strong>{{ $errors->first('tgl_expired') }}</strong>
															</span>
														@endif
													</div>
													<div class="col-sm-6  credit-form">
														{!! Form::text('cvv', $identity->cc_cvv, ['class' => 'form-control col-sm-6', 'placeholder' => 'Masukkan CVV...']) !!}

														@if ($errors->has('cvv'))
															<span class="help-block">
																<strong>{{ $errors->first('cvv') }}</strong>
															</span>
														@endif
													</div>
													<div class="col-sm-12" style="padding-top:30px;color:grey">
														<p>Semua informasi termasuk tiket akan dikirimkan melalui email : {{ Auth::user()->email }}, hubungi cs eticketing tmii apabila terjadi kesalahan.</p>
													</div>
												</div>
											</div>
										</div> */ ?>
										<div class="modal-body">
											<div id="content-modal"></div>
										</div>
										<div class="modal-footer">
											<?php //{!! Form::submit('Submit', ['class' => 'btn btn-primary form-control', 'style' => 'background-color:#3c2f90']) !!} //?>
										</div>

									<?php /*{!! Form::close() !!} */ ?>
							</div>
						</div>
					</div>
				@endif
			</div>
		@else
				<div class="col-sm-12">
					<p style="text-align:center;margin-top:30px"><i>Keranjang belanjaan anda kosong</i></p>
				</div>
		@endif
	</div>
</div>
<style>
.cart-item-info h4{
	margin: 1.7em 4em 0em 0em
}
.cart-item-info h3 {
    margin-top: 0em;
}

.close{
  background: url('new_fashions/images/close.png') no-repeat 0px 0px;
  cursor: pointer;
  width: 28px;
  height: 28px;
  position: absolute;
  right: 0px;
  top: 0px;
}
</style>
<script>
	function create_order(order){
		// console.log(order.id);
		// $.ajax({
		// 	url: $('.mainurl').val() +'/assign/order',
		// 	type: "POST",
		// 	data:{
		// 		_token              : $('meta[name="csrf-token"]').attr('content'),
		// 		cd_p 								: order.id
		// 	},
		// 	cache: false,
		// 	success:function(data)
		// 	{
		// 		// console.log(data);
		// 		// --------------				
				jQuery("#paymentModal").modal('show', function(e){
					$('#content-modal').append('<iframe id="sgoplus-iframe" src="" frameborder="0"></iframe>');
				});
				tesmodal(order.id);
		// 		// --------------
				
		// 	},
		// 	error:function(err){
		// 		console.log(err);
		// 	}
		// });	
	}

	function tesmodal(orderID){
	// -----------------
	$('#paymentModal').on("shown.bs.modal", function(e){
		// console.dir(e.relatedTarget).data(data);
				var data = {
					paymentId: orderID,
					key: "<?php echo env('ESPAY_KEY'); ?>",
					backUrl: 'http://35.240.234.157/payment_status',
					display : 'option',
				},
				sgoPlusIframe = document.getElementById("Fsgoplus-iframe");
				if (sgoPlusIframe !== null) {
					sgoPlusIframe.src = SGOSignature.getIframeURL(data);
				}
				SGOSignature.receiveForm();
				$('#content-modal').append('<iframe id="sgoplus-iframe" src=""  frameborder="0"></iframe>');
		});	
	// -----------------
	}


	$(function () {
		$('.debit-form').hide();
		$('.credit-form').hide();

		$("input:radio").click(function() {
			if($(this).val() == 'DEBIT'){
				$('.debit-form').show();
				$('.credit-form').hide();
			}else if($(this).val() == 'CREDIT'){
				$('.debit-form').hide();
				$('.credit-form').show();
			}else{
				$('.debit-form').hide();
				$('.credit-form').hide();
			}
		});

		$(".quantity").bind('keyup mouseup', function () {
			$.fn.change_price();
		});

		$.fn.change_price = function(){
			
			$.ajax({
				url: $('.mainurl').val() +'/cart/total_price',
				type: "POST",
				data:{
					_token              : $('meta[name="csrf-token"]').attr('content'),
					val                 : $( ".quantity" ).serializeArray(),
				},
				cache: false,
				success:function(data)
				{

					var data = $.parseJSON(data);
					if(data[0] == 'failed'){
						alert(data[1]);
					}else{
						if(data == 0){
							alert('Qty harus diisi');
						}else{
							$('.total').empty();
							$('.total').html(data[0]);
							$('.total_final').empty();
							$('.total_final').html(data[0]);
							$('#navbar-price').empty();
							$('#navbar-price').html(data[0]);
							$('#navbar-qty').empty();
							$('#navbar-qty').html(data[1]);
						}
					}
				
				}
			});   
		}
	})
</script>
@endsection