<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>e-ticketing tmii</title>
    <style>
        @font-face {
            font-family: SourceSansPro;
            src: url(SourceSansPro-Regular.ttf);
        }

        .clearfix:after {
            content: "";
            display: table;
            clear: both;
        }

        a {
            color: #0087C3;
            text-decoration: none;
        }

        body {
            position: relative;
            width: 100%;
            height: 29.7cm;
            margin: 0 auto;
            color: #555555;
            background: #FFFFFF;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-family: SourceSansPro;
        }

        header {

            padding: 0px 0;
            margin-bottom: 20px;
            border-bottom: 0px solid #AAAAAA;
        }

        #logo {
            float: left;
            margin-top: 8px;
        }

        #status {
            float: left;
            margin-bottom: 8px;
        }

        #logo img {
            height: 70px;
        }

        #pmt {
            text-align: center;
        }

        #company {
            width: 92%;
            float: right;
            text-align: right;
        }


        #details {
            margin-bottom: 50px;
        }

        #client {
            padding-left: 6px;
            border-left: 6px solid #0087C3;
            float: left;
        }

        #client .to {
            color: #777777;
        }

        h2.name {
            font-size: 1.4em;
            font-weight: normal;
            margin: 0;
        }

        #invoice {
            width: 92%;
            float: right;
            text-align: right;
        }

        #invoice h1 {
            color: #0087C3;
            font-size: 2.4em;
            line-height: 1em;
            font-weight: normal;
            margin: 0 0 10px 0;
        }

        #invoice .date {
            font-size: 1.1em;
            color: #777777;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 0px;
        }

        table th,
        table td {
            padding: 20px;
            background: #EEEEEE;
            text-align: center;
            border-bottom: 1px solid #FFFFFF;
        }

        table th {
            white-space: nowrap;
            font-weight: normal;
        }

        table td {
            text-align: right;
        }



        table td h3 {
            color: #57B223;
            font-size: 1.2em;
            font-weight: normal;
            margin: 0 0 0.2em 0;
        }

        table .inv {
            text-align: center;
            white-space: pre;
            LINE-HEIGHT: 0px
        }

        table .hider2 {
            width: 60%;
            color: #FFFFFF;
            background: #57B223;
            font-size: 1.5em;
        }

        table .hider {
            color: #FFFFFF;
            background: #57B223;
            font-size: 1.5em;
        }

        table .tiket {
            text-align: left;
        }

        table .konten {
            text-align: center;
        }


        table td.unit,
        table td.qty,
        table td.total {
            font-size: 1.2em;
        }

        table tbody tr:last-child td {
            border: none;
        }

        table tfoot td {
            padding: 10px 20px;
            background: #FFFFFF;
            border-bottom: none;
            font-size: 1.2em;
            white-space: nowrap;
            border-top: 1px solid #AAAAAA;
        }

        table tfoot tr:first-child td {
            border-top: none;
        }

        table tfoot tr:last-child td {
            color: #57B223;
            font-size: 1.4em;
            border-top: 1px solid #57B223;

        }

        table tfoot tr td:first-child {
            border: none;
        }
    </style>
</head>

<body>
<h1>INVOICE No. {{ $cart_id }} </h1>
    <table border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td colspan="5" class="inv"><time>Date invoiced : {{ date('d F Y H:m') }}</time></td>
        </tr>
    </table>
    <table>
        <img src="{{ public_path('img/unpaid2.png') }}" height=100 width=100 align="left">
        <img src="{{ public_path('img/tmii-logo.gif') }}" height="100" width="100" align="right">
    </table>




<header class="clearfix"></header>
<div id="details" class="clearfix">
    <div id="client">
        <div class="to">KEPADA: <strong>{{ $first_name . $last_name }}</strong></div>
        <h2 class="name">{{ $email }}</h2>
        <div class="address">-</div>
        <div class="phone"> {{ $mobile_phone }}</div>
    </div>
    <div id="company">
        <h2 class="name">Kantor Unit Pelayanan E-Ticketing TMII</h2>
        <div>Gedung Pengelola Taman Mini Indonesia Indah Lt.1</div>
        <div>Jl. Raya Taman Mini Pintu 1</div>
        <div>Jakarta Timur, Jakarta, Indonesia 13560</div>
        <div>Telpon/Fax: +62 21 - 229-844-22</div>
    </div>
</div>

    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th class="hider">Tiket</th>
            <th class="hider2">Qty</th>
            <th class="hider">Diskon</th>
            <th class="hider">Harga Setelah Diskon</th>
        </tr>
        </thead>
        <tbody>

        </tbody>
        <tfoot>
        <tr>
            <td colspan="3">Sub Total</td>
            <td>Rp. <var>{{ $sub_total }}</var></td>
        </tr>
        <tr>
            <td colspan="3">Biaya Admin</td>
            <td>Rp. <var>{{ $biaya_admin }}</var></td>
        </tr>


        <tr>
            <td colspan="3">Total Amount</td>
            <td>Rp. <var>{{ $total_amount }}</var></td>
        </tr>
        </tfoot>
    </table>




</body>
</html>
