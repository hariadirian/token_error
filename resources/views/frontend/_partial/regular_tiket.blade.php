<!-- section -->
<div class="section">
		<!-- container -->
		<div class="container">
			<!-- row -->
			<div class="row">
				<!-- section title -->
				<div class="col-md-12">
					<div class="section-title">
						<h2 class="title">Tiket Reguler {{ Request::segment(2) == 'popular'? 'Paling Populer' : '' }} </h2>
					</div>
				</div>
				<!-- section title -->

				<!-- Product Single -->
				
				@foreach($products as $key2 => $product)
					@if( Request::segment(1) == '')
						@if($key2 == '7') @break @endif
					@endif
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="product product-single" style="background-color:white;box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);background:#fff;">
							<div class="product-thumb" style="height: 261px;margin-bottom:0px">
								<a href="{{ URL::asset('product/view/'.$product->m_product_uu) }}" class="main-btn quick-view"><i class="fa fa-search-plus"></i> DETAIL TIKET</a>
								@php $srcname = '' @endphp
								@php $filename = '' @endphp
								@if(isset($product->toTicketImgHd))
									@if(isset($product->toTicketImgHd['toTicketImgDt']))
										@foreach($product->toTicketImgHd['toTicketImgDt'] as $img)
											@if($img['img_type'] == 'BOX')
												@php $srcname = Storage::url($img['srcname']) @endphp
												@php $filename = $img['filename'] @endphp
												@break
											@endif
										@endforeach
									@endif
								@endif
								<img src="{{ $srcname }}" alt="">
							</div>
							<div class="product-body">
								@php $min_value = get_min_discount( get_min_discount( $product->pekan_value, $product->holiday_value ), $product->weekdays_value ) @endphp 
								@php $max_value = get_max_discount( get_max_discount( $product->pekan_value, $product->holiday_value ), $product->weekdays_value ) @endphp
								@if($min_value == $max_value)
									@php $sum_product = "Rp " . number_format( $max_value,0,',','.') @endphp
								@else
									@php $sum_product = "Rp " . number_format( $min_value,0,',','.') @endphp
									@php $sum_product .= ' - ' @endphp
									@php $sum_product .= "Rp " . number_format( $max_value,0,',','.') @endphp
								@endif
								<p class="product-name" style="height:45px"><a href="#">{{ ucwords($product->name) }}</a></p>
								<h4 class="product-price">{{ $sum_product }}</h4>
								<div class="product-btns" style="margin-top:0px;">
									{!! Form::open(['route' => 'ticket.add_to_cart']) !!}

										{!! Form::hidden('cd', $product->m_product_uu) !!}
										{!! Form::hidden('type_ticket', 'PRODUCT') !!}
										{!! Form::hidden('by_pass', 1) !!}
			
										<button class="primary-btn add-to-cart"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
										
									{!! Form::close() !!}
								</div>
							</div>
						</div>
					</div>
					@endforeach
				<!-- /Product Single -->
				
				@if( Request::segment(1) == '')
					<div class="col-md-3 col-sm-6 col-xs-6">
						<div class="banner banner-2" style="box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);">
							<img src="{{asset('frontend/img/banner15.jpg')}}" alt="">
							<div class="banner-caption">
								<h2 class="white-color" style="text-shadow:2px 3px rgba(0, 0, 0, 0.22);">TIKET<br>LAINNYA</h2>
								<a href="{{ URL::asset('product') }}" class="primary-btn">LIHAT SEMUA</a>
							</div>
						</div>
					</div>
				@endif
			</div>
			<!-- /row -->

			
		</div>
		<!-- /container -->
	</div>
	<!-- /section -->
