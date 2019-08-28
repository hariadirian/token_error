@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/nouislider.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')

	<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="#">Home</a></li>
				<li><a href="{{ URL::asset('product') }}">Tiket Reguler</a></li>
				<li class="active">Detail</li>
			</ul>
		</div>
	</div>
	<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!--  Product Details -->
				<div class="product product-details clearfix">
					<div class="col-md-6">
						<div id="product-main-view" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">
							<div class="product-view">
								@php $srcname = '' @endphp
								@php $filename = '' @endphp
								@if(isset($det_product->toTicketImgHd))
									@if(isset($det_product->toTicketImgHd->toTicketImgDt))
										@foreach($det_product->toTicketImgHd->toTicketImgDt as $img)
											@if($img->img_type == 'BOX')
												@php $srcname = Storage::url($img->srcname) @endphp
												@php $filename = $img->filename @endphp
												@break
											@endif
										@endforeach
									@endif
								@endif
								<img src="{{ $srcname }}" alt="qq">
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<form id="add-to-cart" action="{{ route('ticket.add_to_cart') }}" method="post">
							{{ csrf_field() }}
							<div class="product-body" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);padding: 10px 20px 20px 20px;background:#fff">
								<!-- <div class="product-label">
									<span>New</span>
									<span class="sale">-20%</span>
								</div> -->
								<input type="hidden" id="type_ticket" name="type_ticket" value="PRODUCT" />
								<input type="hidden" id="cd" name="cd" value="{{ $det_product->m_product_uu }}" />
								<h1 class="product-name" style="margin-bottom: 15px;padding-bottom:10px;border-bottom: 1px solid #DADADA;"> {{ ucwords($det_product->name) }}</h1>
									@php $min_value = get_min_discount( get_min_discount( $det_product->pekan_value, $det_product->holiday_value ), $det_product->weekdays_value ) @endphp
									@php $max_value = get_max_discount( get_max_discount( $det_product->pekan_value, $det_product->holiday_value ), $det_product->weekdays_value ) @endphp
									@if($min_value == $max_value)
										@php $sum_product = "Rp " . number_format( $max_value,0,',','.') @endphp
									@else
										@php $sum_product = "Rp " . number_format( $min_value,0,',','.') @endphp
										@php $sum_product .= ' - ' @endphp
										@php $sum_product .= "Rp " . number_format( $max_value,0,',','.') @endphp
									@endif
								<h3 class="product-price" style="color:#b44224;">{{ $sum_product }}</h3>
								<div class="col-sm-6 " style="padding:0">
									<p style="color:#424242"><b>Ketersediaan:</b> Tersedia</p>
								</div>
								<div class="col-sm-6 " style="padding:0">
									<p style="color:#424242"><b>Tipe Product:</b> {{ ucwords($det_product->product_category) }}</p>
								</div>

								<div class="col-sm-12 " style="padding:0">
									<h4><strong class="">Harga Tiket :</strong></h4>
								</div>
								<div class="col-sm-12" style="padding-left:40px">
									<div class="col-sm-7">
										<li style="list-style-type: disc;">Hari kerja (Senin-Jumat)</li>
									</div>
									<div class="col-sm-1">:</div>
									<div class="col-sm-4">{{ "Rp " . number_format( $det_product->weekdays_value,0,',','.') }}</div>
									<div class="col-sm-7">
										<li style="list-style-type: disc;">Hari libur (Sabtu, Minggu, Liburan)</li>
									</div>
									<div class="col-sm-1">:</div>
									<div class="col-sm-4">{{ "Rp " . number_format( $det_product->holiday_value,0,',','.') }}</div>
									<div class="col-sm-7">
										<li style="list-style-type: disc;">Event Taman Mini</li>
									</div>
									<div class="col-sm-1">:</div>
									<div class="col-sm-4">{{ "Rp " . number_format( $det_product->pekan_value,0,',','.') }}</div>
								</div>

								<div class="col-sm-12 product-options" style="padding-top:0px">
								</div>

								<div class="product-btns">
									<div class="qty-input">
										<span class="text-uppercase">QTY: </span>
										<input class="input" id="quantity" min="1" max="29" value="1" name="quantity" type="number" style="width:140px">
									</div>
									<div class="qty-input" style="margin-left:20px">
										<span class="text-uppercase">Tanggal Tiket: </span>
										<input id="ticket_date" name="ticket_date" type="text" class="input datepicker" style="cursor:pointer;width:190px" value="{{ isset($carts->ticket_date)? $carts->ticket_date : '' }}" readonly>
									</div>
								</div>
							</div>

							<div id="price-div" style="margin-top:20px"></div>
						</form>
					</div>
				</div>
				<!-- /Product Details -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /section -->
@endsection

@section('js')
	<script src="{{ asset('frontend/js/main.js') }}"></script>
  <script src="{{ asset('js/pages/frontend/_DetailTicket.js') }}"></script>
	<script>
		$(document).ready(function() {
			$("input[type='number']").keydown(function(e) {
				if(!((e.keyCode > 95 && e.keyCode < 106) || (e.keyCode > 47 && e.keyCode < 58) || e.keyCode == 8)) {
		       return false;
		    }
			});
		});
	</script>
@endsection
