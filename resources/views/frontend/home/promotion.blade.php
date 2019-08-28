@extends('layouts.frontend-skeleton')

@section('css')

    <link type="text/css" rel="stylesheet" href="{{asset('frontend/css/style.css')}}" />

@endsection

@section('content')
	<div id="breadcrumb">
		<div class="container">
			<ul class="breadcrumb">
				<li><a href="{{ URL::asset('/') }}">Home</a></li>
				<li class="active">Promotion</li>
			</ul>
		</div>
  </div>


  <!-- section -->
<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!-- section-title -->
				<div class="col-md-12">
					<div class="section-title">
						<h2 class="title">PROMO {{ Request::segment(2) == 'popular'? 'PALING POPULER' : 'TERBARU' }}</h2>
						<div class="pull-right">
							<div class="product-slick-dots-1 custom-dots"></div>
						</div>
					</div>
				</div>
				<!-- /section-title -->

				<!-- banner -->
				<div class="col-md-1 col-sm-3 col-xs-3" style="width:12.5%">
				</div>
				<!-- /banner -->

				<!-- Product Slick -->
				<div class="col-md-9 col-sm-6 col-xs-6">
					<div class="row">
						<div class="slider product-slick">
							<!-- Product Single -->
							@foreach($promotion as $promo)
								<div class="product product-single" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);background:#fff;height:410px">
									<div class="product-thumb">
										<div class="product-label" style="width:100%">
											@if(date("Y-m-d", strtotime("-1 week")) <= $promo->startdate )
											 <span style="float:left">BARU</span>
											 @endif
											 <span style="background-color:#2A0;float:right">
												 @if( ($promo->promo_pekan_type == 'P' and $promo->promo_weekdays_type == 'P' and $promo->promo_holiday_type == 'P') or ($promo->promo_pekan_type == 'A' and $promo->promo_weekdays_type == 'A' and $promo->promo_holiday_type == 'A') )
													@php $min_discount = get_min_discount( get_min_discount( $promo->promo_pekan, $promo->promo_weekdays ), $promo->promo_holiday ) @endphp
													@php $max_discount = get_max_discount( get_max_discount( $promo->promo_pekan, $promo->promo_weekdays ), $promo->promo_holiday ) @endphp
													@if($min_discount == $max_discount)
														@php $sum_promo = $promo->promo_pekan_type == 'A'? "Rp " . number_format( $max_discount,0,',','.') : $max_discount.'%' @endphp
													@else
														@php $sum_promo = $promo->promo_pekan_type == 'A'? "Rp " . number_format( $min_discount,0,',','.') : $min_discount.'%' @endphp
														@php $sum_promo .= ' - ' @endphp
														@php $sum_promo .= $promo->promo_pekan_type == 'A'? "Rp " . number_format( $max_discount,0,',','.') : $max_discount.'%' @endphp
													@endif
												@else
													@if($promo->promo_pekan == $promo->promo_weekdays)
														@php $sum_promo = $promo->promo_pekan_type == 'A'? "Rp " . number_format( $promo->promo_pekan,0,',','.') :  $promo->promo_pekan .'%' @endphp
													@else
														@php $sum_promo = $promo->promo_pekan_type == 'A'? "Rp " . number_format( $promo->promo_pekan,0,',','.') :  $promo->promo_pekan .'%'  @endphp
														@php $sum_promo .= ', ' @endphp
														@php $sum_promo .= $promo->promo_weekdays_type == 'A'? "Rp " . number_format( $promo->promo_weekdays,0,',','.') :  $promo->promo_weekdays .'%'  @endphp
													@endif
													@if($promo->promo_pekan != $promo->promo_holiday and $promo->promo_weekdays != $promo->promo_holiday)
														@php $sum_promo .=  ', '.$promo->promo_holiday_type == 'A'? "Rp " . number_format( $promo->promo_holiday,0,',','.') :  $promo->promo_holiday .'%' @endphp
													@endif
												@endif
												{!!'Potongan ' . '<span style="color:#840707">'.$sum_promo.'</span>' !!} / {{ $promo->min_val }} tiket
											</span>
										</div>
										@php
											$start = new \DateTime(date('Y-m-d H:i:s'));
											$end   = new \DateTime($promo->enddate);
											$interval = $end->diff($start);
										@endphp
										@if(!$interval->m)
											<ul class="product-countdown">
												<li><span>{{ $interval->d  }} Hari</span></li>
												<li><span>{{ $interval->h }} Jam</span></li>
												<li><span>{{ $interval->i }} Menit</span></li>
											</ul>
										@endif
										<a href="{{ URL::asset('promotion/view/'.$promo->m_promotion_uu) }}" class="main-btn quick-view"><i class="fa fa-search-plus"></i> DETAIL TIKET</a>
										@php $srcname = '' @endphp
										@php $filename = '' @endphp
										@if(isset($promo->toTicketImgHd))
											@if(isset($promo->toTicketImgHd['toTicketImgDt']))
												@foreach($promo->toTicketImgHd['toTicketImgDt'] as $img)
													@if($img['img_type'] == 'HORIZONTAL')
														@php $srcname = Storage::url($img['srcname']) @endphp
														@php $filename = $img['filename'] @endphp
														@break
													@endif
												@endforeach
											@endif
										@endif
										<img src="{{ $srcname  }}" title="{{ $filename }}" alt="">
									</div>
									<div class="product-body">
										@php
											$start_price_min 	=  get_min_discount( get_min_discount( $promo->totalproductprice_pekan, $promo->totalproductprice_weekdays ), $promo->totalproductprice_holiday );
											$start_price_max 		=  get_max_discount( get_max_discount( $promo->totalproductprice_pekan, $promo->totalproductprice_weekdays ), $promo->totalproductprice_holiday );
											$end_price_min 	=  get_min_discount( get_min_discount( $promo->pekan_value, $promo->weekdays_value ), $promo->holiday_value );
											$end_price_max 		=  get_max_discount( get_max_discount( $promo->pekan_value, $promo->weekdays_value ), $promo->holiday_value );
										@endphp
										<div class="col-sm-12">
											<h3 class="product-price">{{  $end_price_min == $end_price_max? "Rp " . number_format( round($end_price_min),0,',','.') : "Rp " . number_format( round($end_price_min),0,',','.').' - '."Rp " . number_format(round($end_price_max),0,',','.') }} <del class="product-old-price">{{  $start_price_min == $start_price_max?  "Rp " . number_format(round($start_price_min),0,',','.') : "Rp " . number_format(round($start_price_min),0,',','.') .' - '. "Rp " . number_format(round($start_price_max),0,',','.') }}</del>
											<span style="font-size:15px;font-weight:100"> / {{ $promo->min_val }} tiket</span>
											</h3>
											<div class="product-rating">
                        <span class="valid_info pull-right" style="color:#295c7a">Valid sampai {{ date("M d, Y", strtotime($promo->enddate)) }}</span>
											</div>
										</div>
										<div class="col-sm-8">
											<h2 class="product-name"><a href="#">{{
												str_replace('[ ', '[', ucwords(str_replace('[', '[ ', strtolower($promo->description))))
											}}</a></h2>
											<p>
												<span class="promo_span">Minimum <span style="color:#f5a623">{{ $promo->min_val }} Tiket</span> | Maximum <span style="color:#f5a623">{{ $promo->max_val }} Tiket</span>
											</p>
										</div>
										<div class="col-sm-4">
											<div class="product-btns pull-right" style="margin-top:0px;margin-bottom:20px;">
												<form action="{{ route('ticket.add_to_cart') }}" method="post">
                          {{ csrf_field() }}
                          <input type="hidden" name="cd" value="{{ $promo->m_promotion_uu }}" readonly>
                          <input type="hidden" name="type_ticket" value="PROMOTION" readonly>
                          <input type="hidden" name="by_pass" value="1" readonly>
                          <button class="primary-btn add-to-cart" type="submit"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                        </form>
											</div>
										</div>
									</div>
								</div>
							@endforeach
							<!-- /Product Single -->
						</div>
					</div>
				</div>
				<!-- /Product Slick -->
			</div>
			<!-- /row -->
		</div>
		<!-- /container -->
	</div>
	<!-- /section -->

@endsection

@section('js')

@endsection
