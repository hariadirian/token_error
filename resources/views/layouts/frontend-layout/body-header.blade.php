<!-- header -->
<div id="header" style="background-color:white">
			<div class="container">
				<div class="pull-left">
					<!-- Logo -->
					<div class="header-logo">
						<a class="logo" href="{{ URL::asset('/') }}">
							<img src="{{ URL::asset('img/logo.png') }}" alt="">
						</a>
					</div>
					<!-- /Logo -->

					<!-- Search -->
					<div class="header-search">
						<form>
							<input class="input search-input" type="text" placeholder="Enter your keyword">
							<select class="input search-categories">
								<option value="0">All Categories</option>
								<option value="1">Promo</option>
								<option value="1">Reguler</option>
							</select>
							<button class="search-btn"><i class="fa fa-search"></i></button>
						</form>
					</div>
					<!-- /Search -->
				</div>
				<div class="pull-right">
					<ul class="header-btns">
						<!-- Account -->
						<li class="header-account dropdown default-dropdown" style="min-width:160px">

							@if(Auth::user())
								<div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
									<div class="header-btns-icon">
										<i class="fa fa-user-o"></i>
									</div>
									<strong class="text-uppercase">{{  Session::get('name')? Session::get('name') : 'LOGIN' }} 
										<i class="fa fa-caret-down"></i>
									</strong>
								</div>
							@else
								<div class="dropdown-toggle" role="button" aria-expanded="true">
									<div class="header-btns-icon">
										<i class="fa fa-user-o"></i>
									</div>
									<a href="{{ route('login') }}">
										<strong class="text-uppercase">LOGIN</strong>
									</a>
								</div>
							@endif
							<div aria-expanded="true" style="width:100%">
								@if(Auth::user())
									<a href="#" class="text-uppercase">PROFIL SAYA</a>
								@else
									<a href="{{ route('register') }}" class="text-uppercase">Daftar Baru</a>
								@endif
							</div>
							<ul class="custom-menu">
								
								@if(Auth::user())
									<li>
										<a href="#"><i class="fa fa-user-o"></i> My Account</a>
									</li>
									<li>
										<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
											{{ csrf_field() }}
										</form>
										<a href="{{ URL::asset('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
										<i class="fa fa-unlock-alt"></i>Logout
										</a>
									</li>
								@endif
							</ul>
						</li>
						<!-- /Account -->

						<!-- Cart -->
						<li class="header-cart dropdown default-dropdown" style="width:150px">
							<a class="dropdown-toggle" href="{{ URL::asset('cart') }}" aria-expanded="true">
								<div class="header-btns-icon">
									<i class="fa fa-shopping-cart"></i>
									<span id="cart-qty" class="qty">{{ $cart_count }}</span>
								</div>
								<strong class="text-uppercase">My Cart:</strong>
								<br>
								<span id="cart-sum">{{ 'Rp. ' .number_format($cart_sum, 0, ',', '.').',-' }}</span>
							</a>
							<div class="custom-menu">
								<div id="shopping-cart">
									<div class="shopping-cart-list">
										<div class="product product-widget">
											<div class="product-thumb">
												<img src="./img/thumb-product01.jpg" alt="">
											</div>
											<div class="product-body">
												<h3 class="product-price">$32.50 <span class="qty">x3</span></h3>
												<h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
											</div>
											<button class="cancel-btn"><i class="fa fa-trash"></i></button>
										</div>
										<div class="product product-widget">
											<div class="product-thumb">
												<img src="./img/thumb-product01.jpg" alt="">
											</div>
											<div class="product-body">
												<h3 class="product-price">$32.50 <span class="qty">x3</span></h3>
												<h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
											</div>
											<button class="cancel-btn"><i class="fa fa-trash"></i></button>
										</div>
									</div>
									<div class="shopping-cart-btns">
										<button class="main-btn">View Cart</button>
										<button class="primary-btn">Checkout <i class="fa fa-arrow-circle-right"></i></button>
									</div>
								</div>
							</div>
						</li>
						<!-- /Cart -->

						<!-- Mobile nav toggle-->
						<li class="nav-toggle">
							<button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
						</li>
						<!-- / Mobile nav toggle -->
					</ul>
				</div>
			</div>
			<!-- header -->
		</div>
		<!-- container -->