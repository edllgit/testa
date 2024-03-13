<?php
session_start();
if($_SESSION["sessionBGData"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("includes/BG_functions.inc.php");
include("Connections/sec_connect.inc.php");
$BG_pkey=$_SESSION["BG_pkey"];
$bg_name=$_SESSION["sessionBGData"]["bg_name"];
$BGdisc=$_SESSION["sessionBGData"]["global_dsc"];
$BGdisc=bcdiv($BGdisc, 100, 2);
$heading=$_SESSION["heading"];
$acctName=$_SESSION["acctName"];
$date_from=$_SESSION["date_from"];
$date_to=$_SESSION["date_to"];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #FFFFFF;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.select1 {width:100px}
.style1 {	color: #FFFFFF;
	font-weight: bold;
}
.style2 {color: #FFFFFF}
-->
</style>

</head>

<body>
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left" nowrap="nowrap"><font size="2" face="Arial, Helvetica, sans-serif"><?php echo $bg_name; ?></font><td align="right"><img src="logos/direct-lens_logo.gif" width="200" height="60" /></td></tr></table>
<?php
$rptQuery=$_SESSION["rptQuery"];
$rptResult=mysql_query($rptQuery)
	or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
$usercount=mysql_num_rows($rptResult);
print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\" bgcolor=\"#FFFFFF\">
            	<tr bgcolor=\"#1F3A71\">
            		<td colspan=\"4\" class=\"tableHead\"><div align=\"center\">$heading</div></td>
           		</tr>";
if ($usercount != 0){//if there are some orders for this BG, build the report
  print "<tr>
  <td align=\"center\"  class=\"formCell\">Company</td>
  <td align=\"center\"  class=\"formCell\">Discount</td>
  <td align=\"center\"  class=\"formCell\">AR Orders</td>
  <td align=\"right\"  class=\"formCell\">Total Orders</td>
  </tr>";
	$acctTotal=0;			  
	$ARTotal=0;			  
	while ($listItem=mysql_fetch_array($rptResult)){
		if(!isset($currentAcct))
			$currentAcct=$listItem[company];
		$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_item_date]','%m-%d-%Y')");
		$formated_date=mysql_result($new_result,0,0);
		$prodName=explode(" ", $listItem[order_product_name]); /* check if an AR product */
		$prodCount=count($prodName);
		$i=$prodCount;
		$i--;
		if($prodName[$i] == "AR"){ /* if AR product, add to AR total */
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
		}
		$acctTotal=bcadd($acctTotal,$listItem[order_total],2);
		if($currentAcct!=$listItem[company]){
			$BGtotal=bcmul($acctTotal, $BGdisc, 2);
			$acctTotal=money_format('%.2n',$acctTotal);
			$ARTotal=money_format('%.2n',$ARTotal);
			print "<tr><td align=\"center\"  class=\"formCell\">$currentAcct</td><td align=\"center\"  class=\"formCell\">\$$BGtotal</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$acctTotal</td></tr>";
			$acctTotal=0;
			$ARTotal=0;
			$acctTotal=bcadd($acctTotal, $listItem[order_total], 2);
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
			$currentAcct=$listItem[company];
		}else{
			$acctTotal=bcadd($acctTotal, $listItem[order_total], 2);
			$ARTotal=bcadd($ARTotal, $listItem[order_total], 2);
		}
	}//END WHILE
	$BGtotal=bcmul($acctTotal, $BGdisc, 2);
	$acctTotal=money_format('%.2n',$acctTotal);
	$ARTotal=money_format('%.2n',$ARTotal);
	print "<tr><td align=\"center\"  class=\"formCell\">$currentAcct</td><td align=\"center\"  class=\"formCell\">\$$BGtotal</td><td align=\"center\"  class=\"formCell\">\$$ARTotal</td><td align=\"right\"  class=\"formCell\">\$$acctTotal</td></tr>";
	print "</table>";

	if($acctName!=""){//select orders for the account selected
		$orderQuery="SELECT * from orders WHERE orders.user_id='$acctName' AND orders.order_status!='basket' AND orders.order_num!='0'";
		if ($date_from != "All" && $date_to != "All"){
//			$date_from=date("Y-m-d",strtotime($_POST["date_from"]));
//			$date_to=date("Y-m-d",strtotime($_POST["date_to"]));
			$orderQuery.=" AND orders.order_item_date between '$date_from' and '$date_to'";
		}
		$orderQuery.=" group by order_num desc";
		$orderResult=mysql_query($orderQuery)
			or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
		$ordercount=mysql_num_rows($orderResult);
		if($ordercount!=0){//if there are orders, display them
			print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
			<tr><td colspan=\"6\" bgcolor=\"#1F3A71\" class=\"tableHead\"><div align=\"center\">
			Orders
		</div></td>
	</tr>
		<tr bgcolor=\"#E7F2FF\">
		<td class=\"formCell\">
			Order No		</td>
		<td class=\"formCell\">
			Order Date		</td>
		<td class=\"formCell\">
			Ship Date		</td>
		<td class=\"formCell\">
			Patient</td>
		<td class=\"formCell\">
			Order Status</td>
		<td class=\"formCell\" align=\"right\">
			Order Total</td>
	</tr>";
	while ($orderData=mysql_fetch_array($orderResult)){
		switch($orderData["order_status"])
					{
						case 'processing':				$order_status_heading = "Confirmed";				break;
						case 'order imported':			$order_status_heading = "Order Imported";			break;
						case 'job started':				$order_status_heading = "Surfacing";			    break;
						case 'in coating':				$order_status_heading = "In Coating";				break;
						case 'in mounting':				$order_status_heading = "In Mounting";				break;
						case 'in edging':				$order_status_heading = "In Edging";				break;
						case 'order completed':			$order_status_heading = "Order Completed";			break;
						case 'delay issue 0':			$order_status_heading = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status_heading = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status_heading = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status_heading = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status_heading = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status_heading = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status_heading = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status_heading = "Waiting for Frame";		break;
						case 'in transit':				$order_status_heading = "In Transit";				break;
						case 'filled':					$order_status_heading = "Shipped";					break;
						case 'canceled':				$order_status_heading = "Cancelled";				break;
					}
		$orderTotal=money_format('%.2n',$orderData["order_total"]);
		$new_result=mysql_query("SELECT DATE_FORMAT('$orderData[order_item_date]','%m-%d-%Y')");
		$formatted_order_date=mysql_result($new_result,0,0);
		$new_result=mysql_query("SELECT DATE_FORMAT('$orderData[order_date_shipped]','%m-%d-%Y')");
		$formatted_ship_date=mysql_result($new_result,0,0);
		$outputstring.=$orderData["order_num"].chr(9).$formatted_order_date.chr(9).$formatted_ship_date.chr(9).$orderData["order_patient_first"] . " " . $orderData["order_patient_last"].chr(9).$order_status_heading.chr(9).$orderTotal.chr(13);
		print "<tr><td class=\"formCell\">$orderData[order_num]</td>
		<td class=\"formCell\">$formatted_order_date</td>
		<td class=\"formCell\">$formatted_ship_date</td>
		<td class=\"formCell\">$orderData[order_patient_first] $orderData[order_patient_last]</td>
		<td class=\"formCell\">$order_status_heading</td>
		<td class=\"formCell\" align=\"right\">\$$orderTotal</td>
	</tr>";
	}

print "</table>";
		}
	}


}else{
	print "<tr><td colspan=\"3\" class=\"formCell\">No Orders Found</td></tr></table>";
}//END USERCOUNT CONDITIONAL
?>
</body>
</html>
