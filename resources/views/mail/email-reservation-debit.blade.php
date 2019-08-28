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
                Terima kasih, anda telah melakukan pemesanan tiket Taman Mini Indonesia Indah, informasi pemesanan terdapat di dalam email ini.
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="title">
                Transaksi # {{ $no_order }}
			</td>
		</tr>
		<tr>
			<td width="100%" height="10"></td>
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
			<td class="title">
                No Virtual Account : <b>8920810741225125</b>
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="title">
                Tata cara pembayaran melalui ATM : 
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="title">
                <b>I. Pembayaran melalui ATM bersama / Alto</b>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
                <ul>
                    <li><span>Gunakan mesin ATM APA SAJA yang berlogo ATM BERSAMA / Alto</span></li>
                    <li><span>Pada menu utama, pilih Transaksi Lainnya</span></li>
                    <li><span>Pilih Transfer</span></li>
                    <li><span>Pilih Antar Bank Online</span></li>
                    <li><span>Masukkan nomor 008 dan Nomor Virtual account anda</span></li>
                    <li><span>Masukkan jumlah tagihan yang akan anda bayar secara lengkap (Pembayaran dengan jumlah tidak sesuai akan otomatis ditolak)</span></li>
                    <li><span>Kosongkan No. Referensi, lalu tekan Benar</span></li>
                    <li><span>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomorrekening, &amp; nama Merchant. Jika informasi telah sesuai tekan Benar</span></li>
                </ul>
			</td>
		</tr>
		<tr>
			<td class="title">
                <b> II. Pembayaran melalui ATM Mandiri</b>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
                <ul>
                    <li><span>Masukan kartu ATM Mandiri dan PIN anda</span></li>
                    <li><span>Pada ATM Mandiri, pilih menu Bayar/Beli &gt; Lainnya &gt; Lainnya</span></li>
                    <li><span>Pilih Multi Payment</span></li>
                    <li><span>Masukan kode perusahaan lalu tekan Benar (89208)</span></li>
                    <li><span>Masukan 16 Digit Nomor Virtual Account</span></li>
                    <li><span>Pada layar konfirmasi, pastikan tagihan anda sesuai</span></li>
                    <li><span>Jika sesuai tekan 1, lanjutkan menekan Ya</span></li>
                    <li><span>Transaksi selesai</span></li>
                </ul>
			</td>
		</tr>
		<tr>
			<td class="title">
                <b>III. Pembayaran melalui Internet Banking Mandiri</b>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
                <ul>
                    <li><span>Masuk ke situs <a class="m_5006138950878765915moz-txt-link-freetext" href="https://ib.bankmandiri.co.id" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://ib.bankmandiri.co.id&amp;source=gmail&amp;ust=1549617129058000&amp;usg=AFQjCNG-_rBI6je7h9p-jX7Hp51Cs0j_EQ">https://ib.bankmandiri.co.id</a></span></li>
                    <li><span>Lakukan log in dengan akun Mandiri Internet Banking Anda</span></li>
                    <li><span>Klik menu Bayar &gt; Multi Payment</span></li>
                    <li><span>Kolom penyedia jasa pilih Espay</span></li>
                    <li><span>Masukan 16 Digit Nomor Virtual Accountpada kolom Kode Bayar</span></li>
                    <li><span>Pada layar konfirmasi, pastikan tagihan anda sesuai</span></li>
                    <li><span>Jika sesuai ceklist kotak tagihan danklik Lanjutkan</span></li>
                    <li><span>Transaksi selesai</span></li>
                </ul>
			</td>
		</tr>
		<tr>
			<td class="title">
                <b>IV. Pembayaran melalui Mobile Banking Mandiri</b>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
                <ul>
                    <li><span>Buka aplikasi Mandiri Mobile Banking</span></li>
                    <li><span>Lakukan log in dengan akun Mandiri Mobile Banking</span></li>
                    <li><span>Klik menu Bayar &gt; Lainnya</span></li>
                    <li><span>Kolom penyedia jasa pilih Espay</span></li>
                    <li><span>Masukan 16 Digit Nomor Virtual Account</span></li>
                    <li><span>Pada layar konfirmasi, pastikantagihan anda sesuai</span></li>
                    <li><span>Jika sesuai masukan OTP dan Pin SMS Banking, lalu klik OK</span></li>
                    <li><span>Transaksi selesai</span></li>
                </ul>
			</td>
		</tr>
		<tr>
			<td class="paragraph">
                Note : Anda akan dikenakan biaya Rp 4.400 dengan menggunakan metode pembayaran melalui ATM.
			</td>
		</tr>
		<tr>
			<td width="100%" height="25"></td>
		</tr>
		<tr>
			<td class="paragraph">
            Terimakasih,<br />
            Tamanmini Indonesia Indah.
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