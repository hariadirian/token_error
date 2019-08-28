@extends('beautymail::templates.ark')

@section('content')

    @include('beautymail::templates.ark.heading', [
    'heading' => 'Pemberitahuan pemesanan reservasi tiket TMII',
    'level' => 'h2',
  ])

    @include('beautymail::templates.ark.contentStart')
        @if(!isset($onlyvalidation))
            <h4 class="secondary" style="text-align:left;">Hai {{$nama}} Terimakasih Anda telah melakukan pemesanan tiket reservasi Taman Mini Indonesia Indah</h4>
            <p>Berikut data yang dikirimkan atas nama pemesan tiket TMII</p>
        @else
            <h4 class="secondary" style="text-align:left;">Hai {{$nama}} terimakasih telah bersedia kami hubungi</h4>
            <p>Berikut data valid yang dikirimkan atas nama rombongan pemesan tiket TMII</p>
        @endif
        <div style="width:50%;float:left"><b>Nama Lengkap</b></div>
        <div style="width:50%;float:left">: {{ $nama }}</div>

        <div style="width:50%;float:left"><b>No. KTP</b></div>
        <div style="width:50%;float:left">: {{ $nik }}</div>

        <div style="width:50%;float:left"><b>Email</b></div>
        <div style="width:50%;float:left">: {{ $email }}</div>

        <div style="width:50%;float:left"><b>Nomor HP</b></div>
        <div style="width:50%;float:left">: {{ $no_hp }}</div>
        
        <div style="padding-top:60px"><br><br>Pada tanggal {{ date('Y-m-d') }}, Memesan tiket via online untuk:<br><br></div>
        @if(!isset($onlyvalidation))
            <div style="width:40%;float:left"><b>No Order (dibuat oleh sistem)</b></div>
            <div style="width:60%;float:left">: {{ $no_order }}</div>
            @endif

        <div style="width:40%;float:left"><b>Tiket</b></div>
        <div style="width:60%;float:left">: {{ $product }}</div>

        <div style="width:40%;float:left"><b>Jumlah</b></div>
        <div style="width:60%;float:left">: {{ $kuota }}</div>

        <div style="width:40%;float:left"><b>Reservasi pada tanggal</b></div>
        <div style="width:60%;float:left">: {{ $tanggal_tiket }}</div>

        <div style="width:40%;float:left"><b>Nama bank pengirim</b></div>
        <div style="width:60%;float:left">: {{ $nama_bank }}</div>

        <div style="width:40%;float:left"><b>Nama pengirim transfer bank</b></div>
        <div style="width:60%;float:left">: {{ $nama_pentransfer }}</div>

        <?php if($category_bpartner == '' or (isset($onlyvalidation))){ ?>
            <div style="padding-top:140px">Untuk menyelesaikan transaksi, silakan lakukan transfer dana sebelum tanggal {{ date('d F Y, H:i', strtotime(date('Y-m-d H:i') . "+1 days")) }} WIB.</div>
            <div style="font-size:14px;line-height:28px;text-align:center;padding-top:30px">{{ $results->payment_method }}</div>
            <div style="font-size:14px;line-height:28px;text-align:center"><b>Transfer ke:</b></div>
            <div style="font-size:14px;line-height:28px;text-align:center"><b>{{ $results->bank_name }}</b></div>
            <div style="font-size:14px;line-height:28px;text-align:center"><strong>a/n</strong> PT. <span class="il">PURI</span></div>
            <div style="text-align:center"><span style="display:inline-block;vertical-align:middle;text-align:center;width:300px;padding:10px;background-color:#f7f7f7;border:1px solid #e4e4e4;border-radius:2px;color:#606060"><span style="font-size:20px;font-weight:bold;font-family:Consolas,monospace"><a style="text-decoration:none;border:none;color:#000000">532312214690</a></span></span></div>
            <table style="border-collapse:collapse;color:#4f4f4f;font-size:15px" width="100%" cellspacing="0" cellpadding="0" border="0">
                <tbody>
                    <tr>
                        <td style="padding:10px 0;vertical-align:top;line-height:1.6em;text-align:right" width="50%">Total Pembayaran</td>
                        <td style="padding:10px 0;vertical-align:top;line-height:1.6em" width="40">:</td>
                        <td style="font-weight:bold;padding:10px 0;vertical-align:top;line-height:1.6em" width="340">: {{ $total_price }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="font-size:14px;line-height:28px;text-align:center"><br />Dengan rincian sebagai berikut:<br /> </div>
            <div style="text-align:center"><br />
                <div style="display:inline-block;vertical-align:middle;text-align:center;width:300px;padding:10px;background-color:#f7f7f7;border:1px solid #e4e4e4;border-radius:2px;color:#606060">
                    <div style="font-weight:bold;font-family:Consolas,monospace">
                        <div style="width:50%;float:left;text-align:right">
                            <b>Harga per-tiket :</b>
                        </div>
                        <div style="width:50%;float:left">
                            <a style="text-decoration:none;border:none;color:#000000;text-align:right">
                                {{ $harga }}
                            </a>
                        </div>
                    </div>
                    <div style="font-weight:bold;font-family:Consolas,monospace">
                        <div style="width:50%;float:left;text-align:right">
                            <b>Jumlah :</b>
                        </div>
                        <div style="width:50%;float:left">
                            <a style="text-decoration:none;border:none;color:#000000;text-align:right">
                                {{ $kuota }}
                            </a>
                        </div>
                    </div>
                    <div style="font-weight:bold;font-family:Consolas,monospace">
                        <div style="width:50%;float:left;text-align:right">
                            <b>Total semua :</b>
                        </div>
                        <div style="width:50%;float:left">
                            <a style="text-decoration:none;border:none;color:#000000;text-align:right">
                                {{ number_format($results->total_price,2,',','.') }}
                            </a>
                        </div>
                    </div>
                    <div style="font-weight:bold;font-family:Consolas,monospace">
                        <div style="width:50%;float:left;text-align:right">
                            <b>Diskon :</b>
                        </div>
                        <div style="width:50%;float:left">
                            <a style="text-decoration:none;border:none;color:#000000;text-align:right">
                                {{ '0%' }}
                            </a>
                        </div>
                    </div>
                    <div style="font-weight:bold;font-family:Consolas,monospace">
                        <div style="width:50%;float:left;text-align:right">
                            <b>Total bayar :</b>
                        </div>
                        <div style="width:50%;float:left">
                            <a style="text-decoration:none;border:none;color:#000000;text-align:right">
                             {{ number_format($results->total_price,2,',','.') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php }elseif($category_bpartner == 'MARKETING-PERORANGAN'){ ?>

            <div style="padding-top:110px;text-align:center"><br><br><i>Untuk selanjutnya mohon kesediaannya untuk menunggu dari tim kami menghubungi anda untuk proses transfer dana dan informasi berikutnya. Terima kasih.</i></div>

        <?php } ?>
    @include('beautymail::templates.ark.contentEnd')

    <!-- @include('beautymail::templates.ark.heading', [
    'heading' => 'Selanjutnya Gunakan ID Rekam Medis Anda Untuk Melakukan Booking/Pemesanan Jadwal Dokter Secara Online. Ketika Pada Hari Pemeriksaan, Anda Diharuskan Datang Ke Bagian Pelayanan Klinik Mata Utama Tangerang Selatan Dan Menunjukan Identitas KTP Fisik Anda Untuk Aktivasi Akun Rekam Medis Anda.',
    'level' => 'h2'
  ]) -->

    @include('beautymail::templates.ark.contentStart')

        <br>
        <h4 class="secondary"><strong>Pertanyaan dan Informasi Hubungi 021-82131241 atau email customers@tmii.com</strong></h4>
    @include('beautymail::templates.ark.contentEnd')

@stop