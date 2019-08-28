@extends('layouts.frontend-skeleton')

@section('content')

<div class="product-main">
	<div class="container">
	  <ol class="breadcrumb">
		  <li><a href="{{ URL::asset('/') }}">Home</a></li>
		  <li class="active">Promo-{{ Request::segment(2) }}</li>
		</ol>
		<div class="ctnt-bar cntnt" style="margin-bottom:30px">
		  <div class="content-bar">
			  <div class="single-page">					 
          <!--Include the Etalage files-->
          <link rel="stylesheet" href="{{ asset('new_fashions/css/etalage.css') }}">
          <script src="{{ asset('new_fashions/js/jquery.etalage.min.js') }}"></script>
          <!-- Include the Etalage files -->
          <script>
            jQuery(document).ready(function($){
        
              $('#etalage').etalage({
                thumb_image_width: 500,
                thumb_image_height: 400,
                // source_image_width: 750,
                // source_image_height: 600,
                show_hint: true,
                click_callback: function(image_anchor, instance_id){
                  alert('Callback example:\nYou clicked on an image with the anchor: "'+image_anchor+'"\n(in Etalage instance: "'+instance_id+'")');
                }
              });
              // This is for the dropdown list example:
              $('.dropdownlist').change(function(){
                etalage_show( $(this).find('option:selected').attr('class') );
              });
        
            });
          </script>

          <!---->
          <div class="features" id="features">
            <div class="container">
              <div class="tabs-box">

              <div class="clearfix"> </div>
                <div class="tab-grids">

                  <div id="tab1" class="tab-grid1">
                    @foreach($promo as $prom)    
                      @php
                        $pro = str_replace('{','',$prom->promoline);
                        $pro = str_replace('}','',$pro);
                        $arrProm1 = explode(',', $pro); @endphp
                        @foreach($arrProm1 as $dat1)
                            @php $arrProm2 = explode(';', $dat1); @endphp
                            @foreach($arrProm2 as $dat2)
                            
                              @php if($dat2 == 'I'){
                                $minOP = $arrProm2[1];
                                $minVal = $arrProm2[2];
                              } @endphp

                              @php if($dat2 == 'X'){
                                $maxOP = $arrProm2[1];
                                $maxVal = $arrProm2[2];
                              } @endphp
                            
                            @endforeach
                        @endforeach   
                        
                        @php 
                        if(!isset($minVal)){
                          $minVal = 1;
                          $minOP = '=';
                        }
                        if(!isset($maxVal)){
                          $maxVal = 1;
                          $maxOP = '=';
                        }
                        @endphp   
                      <div class="product-grid">
                        <div 
                        <?php /* style="height:300px;display: table-cell;vertical-align: middle;" */ ?>
                        >
                          <div class="more-product-info"><span>NEW</span></div>
                          <div class="product-img b-link-stripe b-animate-go thickbox" style="height:300px">
                          <img style="height:100%;width:100%" class="img-responsive"  src="{{ isset($prom->toTicketImgHd)? isset($prom->toTicketImgHd->toTicketImgDt)? Storage::url($prom->toTicketImgHd->toTicketImgDt->first()->srcname) : '' : ''  }}" title="{{ isset($prom->toTicketImgHd)? isset($prom->toTicketImgHd->toTicketImgDt)? Storage::url($prom->toTicketImgHd->toTicketImgDt->first()->filename) : '' : ''  }}"/>
                            <div class="b-wrapper">
                              <h4 class="b-animate b-from-left  b-delay03">
                                <a href="{{ url('promo/view/'.$prom->m_promotion_uu) }}">
                                  <button class="btns">ORDER NOW</button>
                                </a>
                              </h4>
                            </div>
                          </div>
                        </div>
                        <div class="product-info simpleCart_shelfItem">
                          <div class="product-info-cust" style="width:100%">
                            <h4 class="promo_font" style="height:40px"><b>{{ str_replace('[ ', '[', ucwords(str_replace('[', '[ ', strtolower($prom->description)))) }}</b></h4>

                            @php $min_defprice = ($prom->totalproductprice_pekan < $prom->totalproductprice_weekdays)? $prom->totalproductprice_pekan : $prom->totalproductprice_weekdays @endphp
                            @php $min_defprice = ($min_defprice < $prom->totalproductprice_holiday)? $min_defprice : $prom->totalproductprice_holiday @endphp
                            @php $max_defprice = ($prom->totalproductprice_pekan > $prom->totalproductprice_weekdays)? $prom->totalproductprice_pekan : $prom->totalproductprice_weekdays @endphp
                            @php $max_defprice = ($max_defprice > $prom->totalproductprice_holiday)? $max_defprice : $prom->totalproductprice_holiday @endphp

                              @if($max_defprice == $min_defprice)
                                <span class="promo_span" style="color:#f5a623 !important;height: 20px;">
                                  Harga normal 
                                    <span  style="text-decoration:line-through">
                                      {{ "Rp " . number_format( $min_defprice,0,',','.') }}
                                    / {{ $prom->min_val }} tiket
                                    </span>
                              </span>
                              @else
                                <span class="promo_span" style="color:#f5a623 !important">Harga normal mulai dari <span  style="text-decoration:line-through">{{ "Rp " . number_format( $min_defprice,0,',','.') }} - {{ "Rp " . number_format( $max_defprice,0,',','.') }}</span></span>
                              @endif
                              <br />
                            @php $min_price = ($prom->pekan_value < $prom->weekdays_value)? $prom->pekan_value : $prom->weekdays_value @endphp
                            @php $min_price = ($min_price < $prom->holiday_value)? $min_price : $prom->holiday_value @endphp
                            @php $max_price = ($prom->pekan_value > $prom->weekdays_value)? $prom->pekan_value : $prom->weekdays_value @endphp
                            @php $max_price = ($max_price > $prom->holiday_value)? $max_price : $prom->holiday_value @endphp
                              <span class="item_price" style="color:#0042ff">
                              @if($max_price == $min_price)
                                <span style="font-weight: 1 !important;color: black;">Harga</span> {{ "Rp " . number_format( $min_price,0,',','.') }}</span>
                              @else
                                <span style="font-weight: 1 !important;color: black;">Mulai dari</span> {{ "Rp " . number_format( $min_price,0,',','.') }} - {{ "Rp " . number_format( $max_price,0,',','.') }}</span>
                              @endif

                            <br />
                            <span class="promo_span">Minimum <span style="color:#f5a623">{{ $minVal }} Tiket</span> | Maximum <span style="color:#f5a623">{{ $maxVal }} Tiket</span> <br />
                            <span class="valid_info pull-right">Valid sampai {{ date("M d, Y", strtotime($prom->enddate)) }}</span>
                          </div>
                          <div class="clearfix"> </div>
                        </div>
                      </div>                      
                    @endforeach
                  </div>
                </div>
              </div>
            </div>
            <!-- tabs-box -->
            <!-- Comman-js-files -->
            <script>
              $(document).ready(function () {
                $("#tab2").hide();
                $("#tab3").hide();
                $(".tabs-menu a").click(function (event) {
                  event.preventDefault();
                  var tab = $(this).attr("href");
                  $(".tab-grid1,.tab-grid2,.tab-grid3").not(tab).css("display", "none");
                  $(tab).fadeIn("slow");
                });
                $("ul.tabs-menu li a").click(function () {
                  $(this).parent().addClass("active a");
                  $(this).parent().siblings().removeClass("active a");
                });
              });
            </script>
            <!-- Comman-js-files -->
          </div>
          <!--fotter-->

  <style>
    .promo_font{
      font-size: 14px !important;
      line-height: 1.3;
    }
    .item_price{
      font-size: 16px !important;
      line-height: 1.3;
      font-family:-apple-system, BlinkMacSystemFont,'Segoe UI','Roboto', 'Droid Sans','Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
    }
    .product-info-cust{
      height:170px;
    }
    ul.tabs-menu {
      width: 70% !important;
    }
    .promo_span{
      color:#999999 !important; 
    }
    .product-grid{
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.12), 0 -1px 3px 0 rgba(0, 0, 0, 0.08);
      border: none;
    }
    .product-info{
      background:white;
    }
    .valid_info{
      font-size:12px;
      color:#24b985;
    }
    .b-animate button{
      color: #363957;
      border: 2px solid #fff;
      background:ButtonFace;
    }
  </style>

@endsection