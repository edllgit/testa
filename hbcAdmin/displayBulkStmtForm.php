<?php
$query="select * from accounts, buying_groups WHERE accounts.buying_group=buying_groups.primary_key and user_id='$_POST[accountStmt]'"; //get acct data
$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
$acctData=mysql_fetch_array($result);
$logo_file=$_SESSION["labAdminData"]["logo_file"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Place Order</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="../logos/<?php echo "$logo_file"; ?>" /><td align="right"><img src="../logos/direct-lens_logo.gif" width="200" height="60" /></td></tr></table>
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Name on Account :</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$acctData[title] $acctData[first_name] $acctData[last_name]";?></strong></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">Company:</td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$acctData[company]";?></strong> </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">Buying Group: </td>
                <td class="formCellNosides"><strong><?php echo "$acctData[bg_name]";?></strong></td>
              </tr>
            </table>
			<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead">BILLING ADDRESS </td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Address 1:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$acctData[bill_address1]";?></strong></td>
              </tr>  <tr >
                <td width="130" align="right" class="formCellNosides">Address 2:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$acctData[bill_address2]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">City:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$acctData[bill_city]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">State:</td>
                <td width="520" class="formCellNosides"><strong><?php echo  "$acctData[bill_state]";?></strong> </td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">Postal Code:  </td>
                <td class="formCellNosides"><strong><?php echo "$acctData[bill_zip]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">Country:</td>
                <td class="formCellNosides"><strong><?php echo "$acctData[bill_country]";?></strong></td>
              </tr>
            </table>
			<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
<tr bgcolor="#000099"><td colspan="8" class="tableHead"><?php echo "$heading"; ?></td>
</tr>
              <tr>
                <td class="formCellNosides">Order Number</td>
                <td class="formCellNosides">Order Date</td>
                <td class="formCellNosides">Date Shipped</td>
                <td class="formCellNosides">Purchase Order</td>
                <td class="formCellNosides">Status</td>
                <td class="formCellNosides"><div align="right">Order Total</div></td>
                <td class="formCellNosides">Payment Status</td>
                <td class="formCellNosides"><div align="right">Payment Total</div></td>
              </tr>
<?php
$acctTotal=0;			  
while ($listItem=mysql_fetch_array($rptResult)){
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
			//$orderQuery="SELECT order_quantity, order_product_discount, order_over_range_fee, order_shipping_cost from orders WHERE order_num='$listItem[order_num]'";
			//$orderResult=mysql_query($orderQuery)
			//	or die  ('I cannot select items because: ' . mysql_error().$orderQuery);
			//$orderTotal=0;			  
			//while ($orderTally=mysql_fetch_array($orderResult)){
			//	$orderSubTally=bcmul($orderTally[order_quantity], $orderTally[order_product_discount], 2);
			//	$orderSubTally=bcadd($orderSubTally, $orderTally[order_over_range_fee], 2);
			//	$orderTotal=bcadd($orderTotal, $orderSubTally, 2);
			//	$shipping=$orderTally[order_shipping_cost];
			//}
			//$orderTotal=bcadd($orderTotal, $shipping, 2);/* include shipping on customer statement */
			//$orderTotal=money_format('%.2n',$orderTotal);
			$orderTotal=money_format('%.2n',$listItem[order_total]);
			if($listItem[pmt_amount]==0){
				$pmt_status="Open";
				$pmt_amount="";
			}else{
				$pmt_status="Paid";
				$pmt_amount=money_format('%.2n',$listItem[pmt_amount]);
			}

			$acctTotal=bcadd($acctTotal, $orderTotal, 2);
			$acctTotal=bcsub($acctTotal, $pmt_amount, 2);
			echo  "<tr><td class=\"formCellNosides\">$listItem[order_num]</td>
                <td class=\"formCellNosides\">$order_date</td>
                <td class=\"formCellNosides\">$ship_date</td>
                <td class=\"formCellNosides\">$listItem[po_num]</td>
                <td class=\"formCellNosides\">$listItem[order_status]</td>
                <td class=\"formCellNosides\"><div align=\"right\">\$$orderTotal</div></td>
                <td class=\"formCellNosides\">$pmt_status</td>
                <td class=\"formCellNosides\">";
				if($pmt_amount!=0)
					echo "<div align=\"right\">\$$pmt_amount</div></td>";
              echo "</tr>";
			 $company=strtoupper($listItem[company]);
		}//END WHILE
			$acctTotal=money_format('%.2n',$acctTotal);
			echo "<tr><td colspan=\"7\" class=\"Subheader\">TOTAL FOR $company</td><td class=\"Subheader\"><div align=\"right\">\$$acctTotal</div></td></tr>";
?>
	</table>
</body>
</html>
