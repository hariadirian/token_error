<table border="0" width="600" cellspacing="0" cellpadding="0" align="center">
<tbody>
<tr>
<td align="center" bgcolor="#70bbd9"><img class="CToWUd" src="https://ci6.googleusercontent.com/proxy/KcmHEpyS3tQ_H3kH1B5V7DHFaomm5-uryzBMRKNTGp3VsuJV0XVOM_dxRGItUuUrcrRazUvvkvwWbVx3pYW6E8kN95omDVgXKMrUKQ=s0-d-e1-ft#https://ticket.tamanmini.com/assets/images/mailbanner.png" alt="tamanmini" /></td>
</tr>
<tr>
<td bgcolor="#ffffff">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td>Hi, {{ $customerData->first_name . $customerData->last_name }}</td>
</tr>
<tr>
  @foreach ($carts as $cart)
    <td>Terima kasih, anda telah melakukan pemesanan tiket Taman Mini Indonesia Indah,
      informasi pemesanan terlampir dokumen(pdf) di dalam email ini. <br /><br />Transaksi {{ $cart->cd_et_cart_product_hd }}<br /><br />
      Lakukan pembayaran sesuai dengan metode yang anda pilih, maksimal 12 jam dari waktu pemesanan Anda.
      <br /><br />No Virtual Account :&nbsp;<strong>8920810741225125</strong><br /><br />Tata cara pembayaran melalui ATM :
      <p class="m_-6636240043973637773m_-8400326935450896464p1">
        <span class="m_-6636240043973637773m_-8400326935450896464cufonh16"><strong>I. Pembayaran melalui ATM bersama / Alto</strong></span>
      </p>
    </td>
  @endforeach
</tr>
<ul>
<li>Gunakan mesin ATM APA SAJA yang berlogo ATM BERSAMA / Alto</li>
<li>Pada menu utama, pilih Transaksi Lainnya</li>
<li>Pilih Transfer</li>
<li>Pilih Antar Bank Online</li>
<li>Masukkan nomor 008 dan Nomor Virtual account anda</li>
<li>Masukkan jumlah tagihan yang akan anda bayar secara lengkap (Pembayaran dengan jumlah tidak sesuai akan otomatis ditolak)</li>
<li>Kosongkan No. Referensi, lalu tekan Benar</li>
<li>Pada halaman konfirmasi transfer akan muncul jumlah yang dibayarkan, nomor rekening, &amp; nama Merchant. Jika informasi telah sesuai tekan Benar</li>
</ul>
<p class="m_-6636240043973637773m_-8400326935450896464p1"><span class="m_-6636240043973637773m_-8400326935450896464cufonh16"><strong>II. Pembayaran melalui ATM Mandiri</strong></span></p>
<ul>
<li>Masukan kartu ATM Mandiri dan PIN anda</li>
<li>Pada ATM Mandiri, pilih menu Bayar/Beli &gt; Lainnya &gt; Lainnya</li>
<li>Pilih Multi Payment</li>
<li>Masukan kode perusahaan lalu tekan Benar (89208)</li>
<li>Masukan 16 Digit Nomor Virtual Account</li>
<li>Pada layar konfirmasi, pastikan tagihan anda sesuai</li>
<li>Jika sesuai tekan 1, lanjutkan menekan Ya</li>
<li>Transaksi selesai</li>
</ul>
<p class="m_-6636240043973637773m_-8400326935450896464p1"><span class="m_-6636240043973637773m_-8400326935450896464cufonh16"><strong>III. Pembayaran melalui Internet Banking Mandiri</strong></span></p>
<ul>
<li>Masuk ke situs&nbsp;<a class="m_-6636240043973637773m_-8400326935450896464moz-txt-link-freetext" href="https://ib.bankmandiri.co.id/" target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://ib.bankmandiri.co.id&amp;source=gmail&amp;ust=1563505678897000&amp;usg=AFQjCNHAobEH8ZepxRnjBcIYtGIxIxRL7Q">https://ib.bankmandiri.co.id</a></li>
<li>Lakukan log in dengan akun Mandiri Internet Banking Anda</li>
<li>Klik menu Bayar &gt; Multi Payment</li>
<li>Kolom penyedia jasa pilih Espay</li>
<li>Masukan 16 Digit Nomor Virtual Account pada kolom Kode Bayar</li>
<li>Pada layar konfirmasi, pastikan tagihan anda sesuai</li>
<li>Jika sesuai ceklist kotak tagihan dan klik Lanjutkan</li>
<li>Transaksi selesai</li>
</ul>
<p class="m_-6636240043973637773m_-8400326935450896464p1"><span class="m_-6636240043973637773m_-8400326935450896464cufonh16"><strong>IV. Pembayaran melalui Mobile Banking Mandiri</strong></span></p>
<ul>
<li>Buka aplikasi Mandiri Mobile Banking</li>
<li>Lakukan log in dengan akun Mandiri Mobile Banking</li>
<li>Klik menu Bayar &gt; Lainnya</li>
<li>Kolom penyedia jasa pilih Espay</li>
<li>Masukan 16 Digit Nomor Virtual Account</li>
<li>Pada layar konfirmasi, pastikan tagihan anda sesuai</li>
<li>Jika sesuai masukan OTP dan Pin SMS Banking, lalu klik OK</li>
<li>Transaksi selesai</li>
</ul>
<br /><br />Note : Anda akan dikenakan biaya Rp 4.400 dengan menggunakan metode pembayaran melalui ATM.<br /><br />Terimakasih,<br />Tamanmini Indonesia Indah.</td>
</tr>
<tr>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr>
<td>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tbody>
<tr>
<td style="text-align: center;">Jalan Taman Mini Indonesia Indah (TMII)&nbsp;<br />Cipayung, Jakarta Timur, Indonesia Telpon: 021-229-844-22</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
