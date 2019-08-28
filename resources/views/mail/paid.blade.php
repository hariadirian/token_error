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
  
  padding: 10px 0;
  margin-bottom: 20px;
  border-bottom: 1px solid #AAAAAA;
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
  margin: 0  0 10px 0;
}

#invoice .date {
  font-size: 1.1em;
  color: #777777;
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 0;
  margin-bottom: 20px;
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

table td h3{
  color: #57B223;
  font-size: 1.2em;
  font-weight: normal;
  margin: 0 0 0.2em 0;
}

table .hider2 {
  width: 60%;
  color: #FFFFFF;
  background:  #57B223;
  font-size: 1.5em;
}

table .hider {
  color: #FFFFFF;
  background:  #57B223;
  font-size: 1.5em;
}

table .tiket{
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
    <header class="clearfix">
      <div id="logo">
        <img src="img/tmii-logo.gif">
      </div>
      <div id="company">
        <h2 class="name">Kantor Unit Pelayanan E-Ticketing TMII</h2>
        <div>Gedung Pengelola Taman Mini Indonesia Indah Lt.1</div>
        <div>Jl. Raya Taman Mini Pintu 1</div>
        <div>Jakarta Timur, Jakarta, Indonesia 13560</div>
        <div>Telpon/Fax: +62 21 - 229-844-22</div>
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">KEPADA:</div>
          <h2 class="name">Eko</h2>
          <div class="address">-</div>
          <div class="phone">+6281284484102</div>
          <div class="email"><a href="mailto:effriyatnaeko@gmail.com">effriyatnaeko@gmail.com</a></div>
        </div>
        <div id="invoice">
            <div class="date">No Invoice:</div>
          <h1>1902JVCKAYF9</h1>
          <div class="date">Tanggal Invoice: 07/02/2019</div>
          <div class="date">Status :</div>
        </div>
      </div>
      <table border="0"   cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="hider">No</th>
            <th class="hider2">Tiket</th>
            <th class="hider">Qty</th>
            <th class="hider">Sub Total(Rp)</th>
            <th class="hider">Diskon(%)</th>
            <th class="hider">Total(Rp)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="konten">01</td>
            <td class="tiket">DISKON IMLEK ANGKA HOKI TAHUN BABI DI SNOWBAY !!! (WEEKEND) @ 180.000</td>
            <td class="konten">1</td>
            <td class="konten">180.000</td>
            <td class="konten">47</td>
            <td class="konten">95.400</td>
          </tr>
        </tbody>
        <tfoot>
          
          <tr>
            <td colspan="3" rowspan="4"> <img src="paid.png" alt="ini gambar udah bayar ceritanya.."></td>
            <td colspan="2" >Diskon Lain-lain(Rp)</td>
            <td>84.600</td>
          </tr>
          <tr>
            <td colspan="2">Sub Total(Rp)</td>
            <td>95.400</td>
          </tr>
          <tr>
              <td colspan="2">Biaya Transaksi(Rp)</td>
              <td>4.400</td>
            </tr>
          <tr>
            <td colspan="2">Total(Rp)</td>
            <td>99.800</td>
          </tr>
        </tfoot>
      </table>

  </body>
</html>