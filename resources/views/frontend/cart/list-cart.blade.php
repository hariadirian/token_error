
        <div class="section-title">
            <h3 class="title">Keranjang Belanja</h3>
        </div>
        <div id="cart-list-content" class="col-md-12" style="background-color:white;padding-top:10px;padding-bottom:20px">
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
                        @if($carts->count())
                            @foreach($carts as $cartHd)
                                <input type="hidden" id="hdCd" value="{{ $cartHd->id_et_cart_product_hd }}" />
                                @if($cartHd->toCartProductBd->count())
                                    @foreach($cartHd->toCartProductBd as $cartBd)
                                        @php $total_product += $cartBd->total_amount  @endphp
                                        <tr>
                                            @php $srcname = '' @endphp
                                            @php $filename = '' @endphp
                                            @if(isset($cartBd->toTicketImgHd))
                                                @if(isset($cartBd->toTicketImgHd->toTicketImgDt))
                                                    @if($cartBd->toTicketImgHd->toTicketImgDt->count())
                                                        @php $srcname = Storage::url($cartBd->toTicketImgHd->toTicketImgDt->first()->srcname) @endphp
                                                    @endif
                                                @endif
                                            @endif
                                            <td class="thumb"><img src="{{ $srcname }}" alt=""></td>
                                            <td class="details">
                                                <a href="{{ URL::asset(strtolower($cartBd->ticket_type).'/view/'.$cartBd->cd_product_ref) }}">{{ $cartBd->product_name }}</a>
                                                <input type="hidden" class="product-uu" value="{{ $cartBd->cd_product_ref }}" />
                                                <ul>
                                                    <li>Tipe Produk: <span class="type-ticket">{{ $cartBd->ticket_type }}</span></li>
                                                    <li>Tanggal Kedatangan: <input class="datepicker product-date product-date-form" value="{{ $cartBd->ticket_date? date("l, d F Y", strtotime($cartBd->ticket_date)) : 'Belum diisi' }}" data-date="{{ $cartBd->ticket_date }}" /><i class="fa fa-caret-down"></i>
                                                    <input class="ticket-date ticket-date-form" type="hidden" value="{{ $cartBd->ticket_date }}" /></li>
                                                </ul>
                                            </td>
                                            <td class="price text-center"><strong  class="product-price">{{ 'Rp. ' . number_format( $cartBd->total_amount / $cartBd->qty_product,0,',','.') }}</strong><br>
                                            <!-- <del class="font-weak"><small>$40.00</small></del> -->
                                            </td>
                                            <td class="qty text-center">
                                                <input class="input cart-qty cart-qty-form" min="1" max="29" type="number" value="{{  $cartBd->qty_product }}">
                                            </td>
                                            <td class="total text-center"><strong class="primary-color">{{ 'Rp. ' . number_format( $cartBd->total_amount,0,',','.') }}</strong></td>
                                            <td class="text-right">
                                                <button type="button" class="main-btn icon-btn remove-cart"><i class="fa fa-close"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" style="text-align:center">Keranjang kosong. Anda belum memasukan product ke dalam keranjang</td></tr>
                                @endif
                            @endforeach
                        @else
                            <tr><td colspan="4">Keranjang kosong. Anda belum memasukan product ke dalam keranjang</td></tr>
                        @endif
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
                    @if(count($carts) > 0)
                        <button class="btn primary-btn" onClick="createOrder({{ $cartHd->id_et_cart_product_hd }}, {{$total_product}})">
                          Pesan Sekarang
                        </button>
                    @endif
                </div>
            </div>
        </div>
