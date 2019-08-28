@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

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
			<div class="row">
				<form id="checkout-form" class="clearfix">
					<div class="section-title">
						<h3 class="title">Kerangjang Belanja</h3>
					</div>
					<div class="col-md-12" style="background-color:white;padding-top:10px;padding-bottom:20px">
						<div class="order-summary clearfix">
							<table class="shopping-cart-table table">
								<thead>
									<tr>
										<th>Product</th>
										<th></th>
										<th class="text-center">Price</th>
										<th class="text-center">Quantity</th>
										<th class="text-center">Total</th>
										<th class="text-right"></th>
									</tr>
								</thead>
								<tbody>
									@php $total_product = 0  @endphp
									@foreach($carts as $cartHd)
										@foreach($cartHd->toCartProductBd as $cartBd)
										@php $total_product += $cartBd->total_amount  @endphp
											<tr>
												<td class="thumb"><img src="{{ Storage::url($cartBd->toTicketImgHd->toTicketImgDt->first()->srcname) }}" alt=""></td>
												<td class="details">
													<a href="{{ URL::asset(strtolower($cartBd->ticket_type).'/view/'.$cartBd->cd_product_ref) }}">{{ $cartBd->product_name }}</a>
													<ul>
														<li><span>Tipe Produk: {{ $cartBd->ticket_type }}</span></li>
														<li><span>Tanggal Kedatangan: {{ $cartBd->ticket_date }}</span></li>
													</ul>
												</td>
												<td class="price text-center"><strong>{{ 'Rp. ' . number_format( $cartBd->total_amount / $cartBd->qty_product,0,',','.') }}</strong><br>
												<!-- <del class="font-weak"><small>$40.00</small></del> -->
												</td>
												<td class="qty text-center"><input class="input" type="number" value="{{  $cartBd->qty_product }}"></td>
												<td class="total text-center"><strong class="primary-color">{{ 'Rp. ' . number_format( $cartBd->total_amount,0,',','.') }}</strong></td>
												<td class="text-right"><button class="main-btn icon-btn"><i class="fa fa-close"></i></button></td>
											</tr>
										@endforeach
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<th class="empty" colspan="3"></th>
										<th>TOTAL HARGA PRODUK</th>
										<th colspan="2" class="total">{{ 'Rp. ' . number_format( $total_product,0,',','.') }}</th>
									</tr>
								</tfoot>
							</table>
							<div class="pull-right">
								<button class="primary-btn" onClick="readorder('20190424')">Place Order</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

@endsection

@section('js')

	<script src="{{ asset('frontend/js/main.js') }}"></script>
	<script src="{{ asset('js/pages/frontend/_Cart.js') }}"></script>
	<script type="text/javascript" src="https://sandbox-kit.espay.id/public/signature/js"></script>
	<script type="text/javascript">
		function readorder($ordernumber){
			alert('Orderan dengan no: '+$ordernumber+ ' telah kelar');
		}
	</script>

@endsection