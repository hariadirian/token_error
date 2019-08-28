@extends('layouts.frontend-skeleton')

@section('css')

	<link type="text/css" rel="stylesheet" href="{{asset('frontend/css/nouislider.min.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')

	<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ URL::asset('/') }}">Home</a></li>
				<li><a href="{{ URL::asset('promotion') }}">Promotion</a></li>
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
					<div class="col-md-12">
						<div id="product-main-view" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">
							<div class="product-view">
								@php $srcname = '' @endphp
								@php $filename = '' @endphp
								@if(isset($det_promo->toTicketImgHd))
									@if(isset($det_promo->toTicketImgHd->toTicketImgDt))
										@foreach($det_promo->toTicketImgHd->toTicketImgDt as $img)
											<?php /*@if($img->img_type == 'HORIZONTAL')*/ ?>
												@php $srcname = Storage::url($img->srcname) @endphp
												@php $filename = $img->filename @endphp
												@break
												<?php /*@endif*/ ?>
										@endforeach
									@endif
								@endif
								<img src="{{ $srcname }}" alt="Tiket TMII">
							</div>
						</div>
					</div>
					<div class="col-md-12" style="margin-top:20px">
						<form id="add-to-cart" action="{{ route('ticket.add_to_cart') }}" method="post">
							{{ csrf_field() }}
							<div class="product-body col-sm-7" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);padding: 10px 20px 20px 20px;background:#fff">
								<!-- <div class="product-label">
									<span>New</span>
									<span class="sale">-20%</span>
								</div> -->
								<input type="hidden" id="type_ticket" name="type_ticket" value="PROMOTION" />
								<input type="hidden" id="cd" name="cd" value="{{ $det_promo->m_promotion_uu }}" />
								<input type="hidden" id="type_customer" name="type_customer" value="{{ $det_promo->type_customer }}" />
								<h3 class="product-name" style="margin-bottom: 15px;padding-bottom:10px;border-bottom: 1px solid #DADADA;"> {{ ucwords($det_promo->description) }}</h3>

									@if( ($det_promo->promo_pekan_type == 'P' and $det_promo->promo_weekdays_type == 'P' and $det_promo->promo_holiday_type == 'P') or ($det_promo->promo_pekan_type == 'A' and $det_promo->promo_weekdays_type == 'A' and $det_promo->promo_holiday_type == 'A') )
										@php $min_discount = get_min_discount( get_min_discount( $det_promo->promo_pekan, $det_promo->promo_weekdays ), $det_promo->promo_holiday ) @endphp
										@php $max_discount = get_max_discount( get_max_discount( $det_promo->promo_pekan, $det_promo->promo_weekdays ), $det_promo->promo_holiday ) @endphp
										@if($min_discount == $max_discount)
											@php $sum_promo = $det_promo->promo_pekan_type == 'A'? "Rp " . number_format( $max_discount,0,',','.') : $max_discount.'%' @endphp
										@else
											@php $sum_promo = $det_promo->promo_pekan_type == 'A'? "Rp " . number_format( $min_discount,0,',','.') : $min_discount.'%' @endphp
											@php $sum_promo .= ' - ' @endphp
											@php $sum_promo .= $det_promo->promo_pekan_type == 'A'? "Rp " . number_format( $max_discount,0,',','.') : $max_discount.'%' @endphp
										@endif
									@else
										@if($det_promo->promo_pekan == $det_promo->promo_weekdays)
											@php $sum_promo = $det_promo->promo_pekan_type == 'A'? "Rp " . number_format( $det_promo->promo_pekan,0,',','.') :  $det_promo->promo_pekan .'%' @endphp
										@else
											@php $sum_promo = $det_promo->promo_pekan_type == 'A'? "Rp " . number_format( $det_promo->promo_pekan,0,',','.') :  $det_promo->promo_pekan .'%'  @endphp
											@php $sum_promo .= ', ' @endphp
											@php $sum_promo .= $det_promo->promo_weekdays_type == 'A'? "Rp " . number_format( $det_promo->promo_weekdays,0,',','.') :  $det_promo->promo_weekdays .'%'  @endphp
										@endif
										@if($det_promo->promo_pekan != $det_promo->promo_holiday and $det_promo->promo_weekdays != $det_promo->promo_holiday)
											@php $sum_promo .=  ', '.$det_promo->promo_holiday_type == 'A'? "Rp " . number_format( $det_promo->promo_holiday,0,',','.') :  $det_promo->promo_holiday .'%' @endphp
										@endif
									@endif

									@php
										$start_price_min 	=  get_min_discount( get_min_discount( $det_promo->totalproductprice_pekan, $det_promo->totalproductprice_weekdays ), $det_promo->totalproductprice_holiday );
										$start_price_max 	=  get_max_discount( get_max_discount( $det_promo->totalproductprice_pekan, $det_promo->totalproductprice_weekdays ), $det_promo->totalproductprice_holiday );
										$end_price_min 		=  get_min_discount( get_min_discount( $det_promo->pekan_value, $det_promo->weekdays_value ), $det_promo->holiday_value );
										$end_price_max 		=  get_max_discount( get_max_discount( $det_promo->pekan_value, $det_promo->weekdays_value ), $det_promo->holiday_value );
									@endphp
								<h3 class="product-price" style="color:#b44224;">{{  $end_price_min == $end_price_max? "Rp " . number_format( round($end_price_min),0,',','.') : "Rp " . number_format( round($end_price_min),0,',','.').' - '."Rp " . number_format(round($end_price_max),0,',','.') }}</h3>
								<div class="col-sm-12 " style="padding:0">
									<div class="col-sm-7 " style="padding:0">
										<p style="color:#424242"><b>Ketersediaan:</b> Tersedia</p>
									</div>
									<div class="col-sm-5 " style="padding:0">
										<p style="color:#424242"><b>Tipe Product:</b> {{ ucwords($det_promo->type_customer) }}</p>
									</div>
									<div class="col-sm-7 " style="padding-left:0px;padding-bottom:10px">
										<ul style="color:#424242"><b>Tiket yang akan anda dapatkan:</b>
											@foreach(json_decode($det_promo->product) as $prod)
												<li style="list-style-type: disc;margin-left:20px">{{ $prod }}</li>
											@endforeach
										</ul>
									</div>
									<div class="col-sm-5 " style="padding:0">
										<p style="color:#424242;margin-bottom:3px"><b>Minimal Pembelian:</b> {{ $det_promo->min_val / $det_promo->min_val }} Promo</p>
										<p style="color:#424242;margin-bottom:3px"><b>Maksimal Pembelian:</b> {{ $det_promo->max_val != ''? $det_promo->max_val / $det_promo->min_val : '-' }} Promo</p>
									</div>
								</div>

								<div class="col-sm-12 " style="padding:0">
									<h4><strong class="">Harga Promo (per-{{ $det_promo->min_val }} Tiket):</strong></h4>
								</div>
								<div class="col-sm-12">
									<div class="col-sm-5">
										<li style="list-style-type: disc;">Hari kerja (Senin-Jumat)</li>
									</div>
									<div class="col-sm-7">
										:
										<del class="product-old-price">{{ "Rp " . number_format( $det_promo->totalproductprice_weekdays,0,',','.') }}</del>
										<strong>{{ "Rp " . number_format( $det_promo->weekdays_value,0,',','.') }}</strong>
										({!! $det_promo->promo_weekdays_type == 'P'? "Diskon <strong style='color:red'>". $det_promo->promo_weekdays."%</strong>" : "Potongan  <strong style='color:red'>". "Rp " . number_format( $det_promo->promo_weekdays,0,',','.')."</strong>" !!} )
									</div>
									<div class="col-sm-5">
										<li style="list-style-type: disc;">Hari libur (Sabtu, Minggu, Liburan)</li>
									</div>
									<div class="col-sm-7">: <del class="product-old-price">{{ "Rp " . number_format( $det_promo->totalproductprice_holiday,0,',','.') }}</del> <strong>{{ "Rp " . number_format( $det_promo->holiday_value,0,',','.') }}</strong> ({!! $det_promo->promo_holiday_type == 'P'? "Diskon <strong style='color:red'>". $det_promo->promo_holiday."%</strong>" : "Potongan  <strong style='color:red'>". "Rp " . number_format( $det_promo->promo_holiday,0,',','.')."</strong>" !!} )</div>
									<div class="col-sm-5">
										<li style="list-style-type: disc;">Event Taman Mini</li>
									</div>
									<div class="col-sm-7">: <del class="product-old-price">{{ "Rp " . number_format( $det_promo->totalproductprice_pekan,0,',','.') }}</del> <strong>{{ "Rp " . number_format( $det_promo->pekan_value,0,',','.') }}</strong> ({!! $det_promo->promo_pekan_type == 'P'? "Diskon <strong style='color:red'>". $det_promo->promo_pekan."%</strong>" : "Potongan  <strong style='color:red'>". "Rp " . number_format( $det_promo->promo_pekan,0,',','.')."</strong>" !!} )</div>
								</div>

								<div class="col-sm-12 product-options" style="padding-top:0px">
								</div>

								<div class="product-btns">
									<div class="qty-input">
										<span class="text-uppercase">QTY: </span>
										<input class="input" id="quantity" min="1" max="29" value="1" name="quantity" type="number" autofocus required>
									</div>
									<div class="qty-input" style="margin-left:20px">
										<span class="text-uppercase">Tanggal Tiket: </span>
										<input id="ticket_date" name="ticket_date" type="text" class="input datepicker" style="cursor:pointer;width:120px" value="{{ isset($carts->ticket_date)? $carts->ticket_date : '' }}" readonly>
									</div>
									@if ( $det_promo->promotioncode != null )
									<div class="qty-input" style="margin-left:20px">
										<span class="text-uppercase">Kode Promo: </span>
										<input class="input" id="promo_code" name="promo_code" type="text" style="width:120px">
									</div>
									@endif
								</div>
							</div>

							<div id="price-div"  class="col-sm-5"></div>
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
