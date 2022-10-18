<!DOCTYPE html>
<html lang="en">
<head>
<title>Generate Invoice</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="main.css">
<link rel="stylesheet"
	href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
	<h3 align="center">Generate Invoice</h3>
	<div class="container">
		<div class="top" style="padding: 5px">
			<button type="button" class="add-button" style="float: right;"
				onclick="addItem();">Add New Item</button>
		</div>

		<form method="POST" action="generatedompdf.php" target="_blank">
			<table class="accounts">
				<tr>
					<th style="width: 10px !important">Name</th>
					<th style="width: 10px">Quantity</th>
					<th style="width: 10px">Unit Price(in $)</th>
					<th style="width: 10px">Tax</th>
					<th style="width: 10px">Total</th>
					<th style="width: 10px">Subtotal without tax</th>
					<th style="width: 10px">Subtotal with tax</th>
					<th style="width: 10px">Discount value</th>
					<th style="width: 10px">Discount amount</th>
					<th style="width: 10px">Total</th>
					<th style="width: 10px"><i class='fa fa-trash' aria-hidden='true'></i></th>
				</tr>
				<tbody id="tbody">
					<tr class= "noresult">
						<td colspan="11" style="text-align: center">No Item Added</td>
					</tr>
				</tbody>
			</table>
			<br>
			<div class="finalTotal">
				<table class="Total" style="width: 50%; float: right;">
					<tr>
						<th style="width: 10px !important">SubTotal</th>
						<th style="width: 10px">Discount</th>
						<th style="width: 10px">GrandTotal</th>
					</tr>
					<tbody id="tbody">
						<td class="grandSubTotal"></td>
						<td class="grandDiscount"></td>
						<td class="grandTotal"></td>
						<input type="hidden" id="invoiceSubTotal" name="invoiceSubTotal"
							value="">
						<input type="hidden" id="invoicDiscount" name="invoicDiscount"
							value="">
						<input type="hidden" id="invoicTotal" name="invoicTotal" value="">
						<input type="hidden" id="rowCount" name="rowCount" value="">

					</tbody>
				</table>
				<br> <br> <br> <br><br> <br> <input style="float: right; width: 10%;"
					class="print-button" type="submit" name="addInvoice"
					value="Print Invoice">
			</div>
		</form>
	</div>
</body>
</html>




<script>
    var items = 0;
    $(".finalTotal").hide();
    function addItem() {
    		$('.noresult').remove();
            items++;
 
        var html = "<tr>";           
            html += "<td  style='width: 10%!important'><input type='text' name='itemName[]' required></td>";
            html += "<td style='width: 10%'><input type='number' name='itemQuantity[]' required ></td>";
            html += "<td style='width: 10%'><input type='number' name='itemUnitPrice[]' onChange='calcTotal(this.parentElement.parentElement)' required></td>";
            html += "<td style='width: 10%'><select name='itemtax[]' id='itemtax' onChange='calcTotal(this.parentElement.parentElement)'><option value='0'>0%</option><option value='1'>1%</option><option value='5'>5%</option><option value='10'>10%</option></select></td>"      
          
            html += "<td style='width: 10%'><input type='text' name='itemTotal[]' readonly ></td>";
            html += "<td style='width: 10%'><input type='text' name='subTotalWithOutTax[]'class= 'subTotalWithOutTax' readonly ></td>";
            html += "<td style='width: 10%'><input type='number'class = 'subTotalWithTax' name='subTotalWithTax[]' onChange='enableDicount(this.parentElement.parentElement)' readonly ></td>";
            html += "<td style='width: 10%'><select name='discountValue[]' id='discountValue' ><option value='0'>percentage(%)</option><option value='1'>Amount($)</option></select></td>";
            html += "<td style='width: 10%'><input type='text' class= 'discountAmount' name='discountAmount[]'onChange='calcDiscount(this.parentElement.parentElement)' ></td>";
            html += "<td style='width: 10%'><input type='text' class = 'grandTotalval' name='grandTotal[]' readonly   ></td>";
            html += "<td style='width: 10%'><button type='button' onclick='deleteRow(this);'><i class='fa fa-trash' aria-hidden='true'></i></button></td>"
        html += "</tr>";
 
        var row = document.getElementById("tbody").insertRow();
        row.innerHTML = html;
    }
 
	function deleteRow(button) {
    	items--
    	button.parentElement.parentElement.remove();
	}


 function calcTotal(thisRow){
        var itemQuantity = thisRow.getElementsByTagName('input')[1].value;
        var itemUnitPrice = thisRow.getElementsByTagName('input')[2].value;
        var itemtax = thisRow.getElementsByTagName('select')[0].value;
        var Total = itemQuantity * itemUnitPrice;
        thisRow.getElementsByTagName('input')[3].value = Total;
        var subTotalWithTax = ((Total * itemtax) / 100) + Total;
   		thisRow.getElementsByTagName('input')[4].value = Total;
        thisRow.getElementsByTagName('input')[5].value = subTotalWithTax;
    }
    
    function calcDiscount(thisRow){      
        var operator = thisRow.getElementsByTagName('select')[1].value;
        var discountAmount = thisRow.getElementsByTagName('input')[6].value;
        var itemTaxAmount = thisRow.getElementsByTagName('input')[5].value;
        if(operator == 0){
      	  var Discount = itemTaxAmount - ((itemTaxAmount * discountAmount) / 100);
      	  thisRow.getElementsByTagName('input')[7].value = Discount;             
        }else{   
      	  var Discount = itemTaxAmount - discountAmount;
      	  thisRow.getElementsByTagName('input')[7].value = Discount;    
        }
		calcAverage();
    }
    
    function calcAverage(){   
    	$(".finalTotal").show();  
        var grandDiscount = 0;
        var grandSubTotal = 0;
        var grandTax = 0;
        var grandTotal  = 0;
        var rowCount = 0;
        
       $(".discountAmount").each(function () {
        	 rowCount++;
       		var $tblrow = $(this);
       		 var discount = parseFloat($(this).val());
			grandDiscount += isNaN(discount) ? 0 : discount;
			$('.grandDiscount').html(grandDiscount.toFixed(2));
			$('#invoicDiscount').val(grandDiscount.toFixed(2));
			$('#rowCount').val(rowCount);

        });      

        
         $(".grandTotalval").each(function () {
       		var $tblrow = $(this);
       		 var discount = parseFloat($(this).val());
			grandTotal += isNaN(discount) ? 0 : discount;
			$('.grandTotal').html(grandTotal.toFixed(2));
			$('#invoicTotal').val(grandTotal.toFixed(2));

        });       

        
        $(".subTotalWithOutTax").each(function () {
                var $tblrow = $(this);
                var discount = parseFloat($(this).val());
                grandSubTotal += isNaN(discount) ? 0 : discount;
                $('.grandSubTotal').html(grandSubTotal.toFixed(2));
                $('#invoiceSubTotal').val(grandSubTotal.toFixed(2));
                
            });
 

    }
    

</script>


