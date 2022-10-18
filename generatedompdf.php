<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';
require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

$dompdf = new Dompdf();
ob_start();

$html = '
<html>
 <body>
<style>
.clearfix:after {
  content: "";
  display: table;
  clear: both;
}

a {
  color: #5D6975;
  text-decoration: underline;
}

body {
  position: relative;
  width: 21cm;  
  height: 29.7cm; 
  margin: 0 auto; 
  color: #000000;
  background: #FFFFFF; 
  font-family: Arial, sans-serif; 
  font-size: 12px; 
  font-family: Arial;
}

header {
  padding: 10px 0;
  margin-bottom: 30px;
}

#logo {
  text-align: center;
  margin-bottom: 10px;
}

#logo img {
  width: 90px;
}

h1 {
  border-top: 1px solid  #5D6975;
  border-bottom: 1px solid  #5D6975;
  color: #5D6975;
  font-size: 2.4em;
  line-height: 1.4em;
  font-weight: normal;
  text-align: center;
  margin: 0 0 20px 0;
  background: url(dimension.png);
}

#project {
  float: left;
}

#project span {
  color: #5D6975;
  text-align: right;
  width: 52px;
  margin-right: 10px;
  display: inline-block;
  font-size: 0.8em;
}

#company {
  float: right;
  text-align: right;
}

#project div,
#company div {
  white-space: nowrap;        
}

table {
  width: 100%;
  border-collapse: collapse;
  border-spacing: 1;
  margin-bottom: 20px;
 border: 1px solid black;
}

table tr:nth-child(2n-1) td {
  background: #F5F5F5;
}

table th,
table td {
  text-align: center;
}

table th {
  padding: 2px 20px;
  color: #000000;
  border-bottom: 1px solid #C1CED9;
  white-space: nowrap;        
  font-weight: normal;
}

table .service,
table .desc {
  text-align: left;
}

table td {
  padding: 20px;
  text-align: center;
}




table td.grand {
  border-top: 1px solid #5D6975;;
}

#notices .notice {
  color: #5D6975;
  font-size: 1.2em;
}

footer {
  color: #5D6975;
  width: 100%;
  height: 30px;
  position: absolute;
  bottom: 0;
  border-top: 1px solid #C1CED9;
  padding: 8px 0;
  text-align: center;
}

</style>
<header class="clearfix"  style="width: 90%">

      <h1>INVOICE </h1>
      <div id="company" class="clearfix">
        <div>Company Name</div>
        <div>455 Foggy Heights,<br /> AZ 85004, US</div>
        <div>(602) 519-0450</div>
      
      </div>
      <div id="project">
        <div><span>NAME</span> Website development</div>
        <div><span>CLIENT</span> Fingent Technologies</div>
        <div><span>ADDRESS</span> </div>
        <div><span>DATE</span> August 18, 2022</div>
      </div>
    </header>
    <main>
      <table style="width: 90%;height: 10px;  border=2;">
        <thead>
          <tr>
           <th style="width: 10px"><span >Name</span></th>
					<th class="service" style="width: 5px"><span >Qty</span></th>
					<th style="width: 10px"><span >Price</span></th>
					<th style="width: 10px"><span >Tax</span></th>
					<th style="width: 10px"><span >Subtotal w/o tax</span></th>
					<th style="width: 10px"><span >Subtotal with tax</span></th>
					<th style="width: 10px"><span >Discount</span></th>
					<th style="width: 10px"><span >Total</span></th>

          </tr>
        </thead>
        <tbody>';
for ($item = 0; $item < $_POST['rowCount']; $item ++) {
    $html .= ' <tr>
                        <td class="service" style="width: 5px"><span >' . $_POST['itemName'][$item] . '</span></td>
                        <td style="width: 10px"><span >' . $_POST['itemQuantity'][$item] . '</span></td>
                        <td style="width: 10px"><span >$' . $_POST['itemUnitPrice'][$item] . '</span></td>
                        <td style="width: 10px"><span >' . $_POST['itemtax'][$item] . '%</span></td>
                        <td style="width: 10px"><span >$' . $_POST['subTotalWithOutTax'][$item] . '</span></td>
                        <td style="width: 10px"><span >$' . $_POST['subTotalWithTax'][$item] . '</span></td>
                        <td style="width: 10px"><span >$' . $_POST['discountAmount'][$item] . '</span></td>
                        <td style="width: 10px"><span >$' . $_POST['grandTotal'][$item] . '</span></td>
	
					</tr>';
}
$html .= '
                    <tr>
                                <td colspan="7" style="float: right;">SubTotal</td>
                                <td class="total">$' . $_POST['invoiceSubTotal'] . '</td>
                        </tr>
                              <tr>
                               <td colspan="7">Discount</td>
                                <td class="total">$' . $_POST['invoicDiscount'] . '</td>
                              </tr>
                              <tr>
                                <td colspan="7" class="grand total">GRAND TOTAL</td>
                                <td class="grand total">$' . $_POST['invoicTotal'] . '</td>
                      </tr>
        </tbody>
      </table>
     
    </main>
    <footer>
     Created by Amrutha Lal M 
    </footer>

	</body>
</html>
';

$dompdf->load_html($html);
$dompdf->render();

$dompdf->stream('my.pdf', array(
    'Attachment' => 0
));
