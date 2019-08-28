<!DOCTYPE html>

<html>

<head>

</head>

<body>

	<div style="width:100%;float:left;padding-bottom:10px">
		<img src="{{ URL::asset('img/logo.png') }}">
		<span style="float:right">
		<img src="{{ URL::asset('img/tmii-logo.png') }}">
		</span>
	</div>
	<div style="width:100%;">
		<p style="font-family:calibri">Unit Pelayanan E-Ticketing TMII | Gedung Pengelola Taman Mini Indonesia Indah Lt.1 
		<br />Jl. Raya Taman Mini Pintu 1, Jakarta Timur Jakarta, Indonesia 13560 
		<br />Telephone/Fax : +62 21 - 229-844-22 | cs@tamanmini.com | https://ticket.tamanmini.com	</p>
	</div>
	
	<div class="box-title" style="width:100%">
		<b>
			TIKET ONLINE TAMAN MINI INDONESIA INDAH
		</b>
	</div>
	<div style="width:100%;height:20px">
		<span style="width:20%;float:left">
			Email
		</span>
		<span style="width:5%;float:left">
			:
		</span>
		<span style="width:75%;float:left">
			{{ $email }}
		</span>
	</div>
	<div style="width:100%;height:20px">
		<span style="width:20%;float:left">
			Nama Lengkap
		</span>
		<span style="width:5%;float:left">
			:
		</span>
		<span style="width:75%;float:left">
			{{ $detail->first_name . ' ' . $detail->last_name }}
		</span>
	</div>
	<div style="width:100%;height:20px">
		<span style="width:20%;float:left">
			Total Ticket
		</span>
		<span style="width:5%;float:left">
			:
		</span>
		<span style="width:75%;float:left">
			{{ count($qr) }}
		</span>
	</div>
	<div style="width:100%;height:20px">
		<span style="width:20%;float:left">
			Total Pengunjung
		</span>
		<span style="width:5%;float:left">
			:
		</span>

		<span style="width:75%;float:left">
			{{ $total_ticket }}
		</span>
	</div>
	<div  style="width:100%;height:20px">
		<span style="width:20%;float:left">
			Dipesan Pada
		</span>
		<span style="width:5%;float:left">
			:
		</span>
		<span style="width:75%;float:left">
			{{ $order->created_at }}
		</span>
	</div>
	<div style="width:100%;height:20px">
		<span  style="width:20%;float:left">
			Dibayar Pada
		</span>
		<span  style="width:5%;float:left">
			:
		</span>
		<span style="width:75%;float:left">
			{{ $order->paid_at }}
		</span>
	</div>
	<div style="width:100%;">
		<hr />
	</div>

	@foreach($qr as $qrcode)
		<div style="width:730px;height:250px">
			<div style='height:250px;background-image: url("{{ url("img/template_ticket.png") }}"); background-size: 700px 300px;background-position: center;background-repeat: no-repeat;'>
			<div style="display: block;transform: rotate(-90deg) translate(-100%);white-space: nowrap;width:50px;left:36px;color:white;font-size:11px;top:65px;position:relative">
				{{ substr($qrcode['generatedDt']->cd_et_generated_ticket_dt, 4) }}
			</div>
			<div  style="left:80px;top:35px;position:relative;width: 145px;">
    			<img width="130px" src="data:image/png;base64, {{ base64_encode($qrcode['qr']) }} ">
			 </div>
			<div  style="left:270px;top:-108px;position:relative;width: 385px;">
    			<div><b>{{ ucwords($qrcode['cartDt']->ticket_name) }}</b></div>
			 </div>
			 
			<div style="left:280px;top:-88px;position:relative;width:380px;height:100px;font-size:11px">
				<div>
					<div  style="width: 25%;float:left;">
						<div>Tanggal Tiket</div>
					</div>
					<div  style="width: 5%;float:left;">
						<div>:</div>
					</div>
					<div  style="width: 70%;float:left;">
						<div>{{ $qrcode['cartBd']->ticket_date }} </div>
					</div>
					
				</div>
				<div style="top:25px;position:relative;">
					<div  style="width: 25%;float:left;">
						<div>Jumlah Tiket</div>
					</div>
					<div  style="width: 5%;float:left;">
						<div>:</div>
					</div>
					<div  style="width: 70%;float:left;">
						<div>{{ $qrcode['generatedDt']->qty }} Tiket</div>
					</div>
				</div>
				<div style="top:50px;position:relative;">
					<div  style="width: 25%;float:left;">
						<div>Tipe Pengunjung</div>
					</div>
					<div  style="width: 5%;float:left;">
						<div>:</div>
					</div>
					<div  style="width: 70%;float:left;">
						<div>{{ $qrcode['cartBd']->ticket_type }}</div>
					</div>
				</div>
				<div style="top:75px;position:relative;">
					<div  style="width: 25%;float:left;">
						<div>Jenis Tiket</div>
					</div>
					<div  style="width: 5%;float:left;">
						<div>:</div>
					</div>
					<div  style="width: 70%;float:left;">
						<div>{{ ( $qrcode['cartBd']->ticket_type == 'PROMOTION')? $qrcode['cartBd']->product_name : 'TIKET REGULAR' }}</div>
					</div>
				</div>
			</div>
		</div>
		<br />
		<br />
	@endforeach  
</body>
<style>
body{
	font-family:calibri;
}
.box-title{
	width:100%;
	padding:10px;
	text-align:center;
	background-color:blue;
	font-family:calibri;
	font-size:22px;
	color:white;
	margin-bottom:20px;
}
.box-info{
	padding-top:20px;
	padding-bottom:20px;
}
.col-sm-12{
	width:750px;
	float:left;
}
.col-sm-6{
	width:375px;
	float:left;
}
.col-sm-3{
	width:187px;
	float:left;
}
.col-sm-1{
	width:62px;
	float:left;
}
.col-sm-2{
	width:124px;
	float:left;
}
.col-sm-4{
	width:248px;
	float:left;
}
.col-sm-8{
	width:496px;
	float:left;
}
/* div{
	border: 1px solid black;
} */
</style>
</html>