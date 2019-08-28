@extends('beautymail::templates.minty')

@section('content')
    @include('beautymail::templates.minty.contentStart')
    <style>
    td{
        padding-right:7px;
        padding-left:7px;
    }
    </style>
        <tr>
			<td>
				<img src="https://ticket.tamanmini.com/assets/images/mailbanner.png" alt="Taman Mini Indonesia Indah" />
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="title">
                Hi, {{ $nama }}
			</td>
		</tr>
		<tr>
			<td width="100%" height="10"></td>
		</tr>
		<tr>
			<td class="paragraph">
			Terima kasih telah melakukan pemesanan pada pesanan anda dari no order : {{ $no_order }}, informasi pemesanan terlampir dokumen (.pdf) di dalam email ini.
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="paragraph">
				Lakukan pembayaran sesuai dengan metode yang anda pilih, maksimal 12 jam dari waktu pemesanan Anda.
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="paragraph">
			Tata cara dengan Credit Card (Visa/Mastercard) :
			</td>
		</tr>
		<tr>
			<td class="paragraph">
				<ul>
					<li><span>Masukkan produk yang mau kamu beli ke
						Keranjang Belanja</span></li>
					<li><span>Pilih metode pembayaran Credit Card</span></li>
					<li><span>Kamu akan langsung diarahkan ke
						halaman Credit Card. Masukkan Data Kartu
						Kredit anda dan Lanjutkan</span></li>
					<li><span>Periksa kembali jumlah pembayaran,
						Pilih Rekening dan Masukkan Token, lalu klik
						Confirm.</span></li>
					<li><span>Kamu akan masuk ke halaman transaksi
						sukses. Klik OK untuk kembali ke website
						MerchantKamu akan masuk ke halaman 3D Secure
						Bank. Masukkan kode OTP yang dikirimkan ke
						HP kamu. Lalu Lanjutkan</span></li>
					<li><span>Transaksi Berhasil. Klik Ok untuk
						kembali ke halaman Merchant</span></li>
                </ul>
			</td>
		</tr>
		<tr>
			<td width="100%" height="50"></td>
		</tr>
		<tr>
			<td class="paragraph">
				Note : Anda akan dikenakan biaya Rp 3.800 dengan menggunakan metode pembayaran Credit Card
			</td>
		</tr>
		
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="paragraph">
            Terimakasih,<br />
            <b>E-ticketing Tamanmini Indonesia Indah.</b>
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
	@include('beautymail::templates.minty.contentEnd')

    <!-- @include('beautymail::templates.ark.heading', [
    'heading' => 'Selanjutnya Gunakan ID Rekam Medis Anda Untuk Melakukan Booking/Pemesanan Jadwal Dokter Secara Online. Ketika Pada Hari Pemeriksaan, Anda Diharuskan Datang Ke Bagian Pelayanan Klinik Mata Utama Tangerang Selatan Dan Menunjukan Identitas KTP Fisik Anda Untuk Aktivasi Akun Rekam Medis Anda.',
    'level' => 'h2'
  ]) -->

    @include('beautymail::templates.ark.contentStart')

        <br>
        <h4 class="secondary"><strong>Pertanyaan dan Informasi Hubungi 021-82131241 atau email customers@tmii.com</strong></h4>
    @include('beautymail::templates.ark.contentEnd')

@stop