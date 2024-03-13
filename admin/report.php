<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

session_start();



if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

if ($_SESSION["access_admin_id"] == "29"){//Acces ID de IFC SHIPPING, access utilisé par IFC pour faire le shipping
//Redirection vers ifc_shipping
header('Location: /admin/shipping_ifc.php');
exit();
}


if ($_SESSION["access_admin_id"] == "35"){//Acces ID de SCT Employees, access utilisé par employés de Saint-Catharines 
//Redirection vers fast_print
header('Location: /admin/fast_print.php');
exit();
}

if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

if($_POST[rpt_search]=="search orders"){
	if($_POST[lab_name]!="all"){
		$query="SELECT primary_key, lab_name FROM labs WHERE primary_key = '$_POST[lab_name]' LIMIT 1";
		$result=mysqli_query($con,$query) or die ("Could not find lab list");
		$labCount=mysqli_num_rows($result);
		if($labCount != 0){
			$labData=mysqli_fetch_assoc($result,MYSQLI_ASSOC);
			$heading=$labData["lab_name"]." - ";
		}
	}
	elseif($_POST[buying_group]!="all"){
		$query="SELECT primary_key, bg_name from buying_groups WHERE primary_key = '$_POST[buying_group]' LIMIT 1";
		$result=mysqli_query($con,$query) or die ("Could not find bg list");
		$bgCount=mysqli_num_rows($result);
		if($bgCount != 0){
			$bgData=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$heading=$bgData["bg_name"]." - ";
		}
	}
			
	if($_POST[order_type]=="stock")
		$order_type="(order_product_type='stock' or order_product_type='stock_tray')";//search stock orders
	else
		$order_type="order_product_type='" . $_POST[order_type] . "'";//search prescription or all orders
		
	$rptQuery="SELECT buying_groups.bg_name, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.lab, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date, labs.primary_key as lab_key, labs.lab_name from orders
	
	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	
	LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
	
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	
	LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
	
	WHERE orders.order_num != '0'";

	switch($_POST["order_status"]){
		case "processing":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Confirmed";
		break;
		case "confirmed":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Confirmed";
		break;
		case "order imported":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Order Imported";
		break;
		case "job started":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Surfacing";
		break;
		case "in coating":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Coating";
		break;
		case "in mounting":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Mounting";
		break;
		case "in edging":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Edging";
		break;
		case "order completed":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Order Completed";
		break;
		case "delay issue 0":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 0";
		break;
		case "delay issue 1":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 1";
		break;
		case "delay issue 2":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 2";
		break;
		case "delay issue 3":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 3";
		break;
		case "delay issue 4":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 4";
		break;
		case "delay issue 5":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 5";
		break;
		case "delay issue 6":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Delay Issue 6";
		break;
		case "waiting for frame":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Frame";
		break;
		case "waiting for shape":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Shape";
		break;
		case "re-do":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Redo";
		break;
		case "in transit":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "In Transit";
		break;
		case "filled":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Shipped";
		break;
		case "cancelled":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Cancelled";
		break;
		case "information in hand":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Info in Hand";
		break;
		case "on hold":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "On Hold";
		break;
		case "open":
			$rptQuery.=" AND (orders.order_status!='filled' AND orders.order_status!='cancelled' AND orders.order_status!='basket')";
			$order_status_heading = "Open";
		break;
			case "waiting for lens":
			$rptQuery.=" AND orders.order_status='$_POST[order_status]'";
			$order_status_heading = "Waiting for Lens";
		break;
		case "all":
			$rptQuery.=" AND orders.order_status!='basket'";
			$order_status_heading = "Open";
		break;
	}
	$heading.="$order_status_heading - $_POST[order_type] Orders";
	
	if($_POST[order_type]!="all")
		$rptQuery.=" AND " . $order_type;
	
	if (($_POST[date_from] != "All" && $_POST[date_to] != "All")&&($_POST[order_status]=="filled")){//select Filled orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";
	}
	elseif (($_POST[date_from] != "All" && $_POST[date_to] != "All")){
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_item_date between '$date_from' and '$date_to'";
	}
	if($_POST[lab_name]!="all")
			$rptQuery.=" AND orders.lab='$_POST[lab_name]'";
	elseif($_POST[buying_group]!="all")
			$rptQuery.=" AND accounts.buying_group='$_POST[buying_group]'";

	if($_POST["order_num"]!=""){//search for order number only and ignore all other form settings
		$rptQuery="SELECT buying_groups.bg_name, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.lab, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date, labs.primary_key as lab_key, labs.lab_name from orders
		
		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
		
		LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
		
		LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
		
		LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
	
		WHERE orders.order_num = '$_POST[order_num]'";
	
	}

	$rptQuery.=" group by order_num desc ORDER BY lab_name";
	$heading.=$dateInfo;
	$heading=ucwords($heading);
}

//echo '<br><br>'. $rptQuery . '<br><br>';


$exportForm="<form  method=\"post\" name=\"export_form\" id=\"export_form\" action=\"export_file.php\" target=\"_blank\"><input name=\"exportData\" type=\"submit\" value=\"Export Data\" class=\"formField\"></form>";//Export Report button
if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
if($_POST[order_status]!="")
	$_SESSION["order_status"]=$_POST[order_status];
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>



</head>
<body onload="doOnLoad();" onLoad="goto_date.order_num.focus();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="report.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Order Reports</font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td nowrap bgcolor="#DDDDDD" ><div align="right">
						Order Number
					</div></td>
					<td align="left" nowrap="nowrap"><input name="order_num" type="text" id="order_num" size="10" class="formField"></td>
					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td width="25%" nowrap ><div align="right">
						Select Order Status
					</div></td>
					<td width="15%" align="left" nowrap="nowrap"><select name="order_status" id="order_status" class="formField">
					  <option value="all">All</option>
					  <option value="cancelled">Cancelled</option>
					  <option value="processing">Confirmed</option>
					  <option value="delay issue 0">Delay Issue 0</option>
					  <option value="delay issue 1">Delay Issue 1</option>
					  <option value="delay issue 2">Delay Issue 2</option>
					  <option value="delay issue 3">Delay Issue 3</option>
					  <option value="delay issue 4">Delay Issue 4</option>
					  <option value="delay issue 5">Delay Issue 5</option>
					  <option value="delay issue 6">Delay Issue 6</option>
					  <option value="on hold">On Hold</option>
					  <option value="information in hand">Info in Hand</option>
					  <option value="in coating">In Coating</option>
					  <option value="in mounting">In Mounting</option>
                      <option value="in edging">In Edging</option>
					  <option value="job started">Surfacing</option>
					  <option value="in transit">In Transit</option>
					  <option value="open">Open</option>
					  <option value="order completed">Order Completed</option>
					  <option value="order imported">Order Imported</option>
					  <option value="re-do">Redo</option>
					  <option value="filled">Shipped</option>
					  <option value="waiting for frame">Waiting for Frame</option>
					  <option value="waiting for lens">Waiting for Lens</option>
					  <option value="waiting for shape">Waiting for Shape</option>
			      </select></td>
					<td width="15%" nowrap="nowrap"><div align="right">
						Select Order Type
					</div></td>
					<td width="40%" align="left" nowrap ><input name="order_type" type="radio" value="stock">
						Stock
						&nbsp;&nbsp;&nbsp;
							<input name="order_type" type="radio" value="exclusive">
						Prescription
						&nbsp;&nbsp;&nbsp;
							<input name="order_type" type="radio" value="all" checked>
						All&nbsp;&nbsp;&nbsp;</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						Date From
					</div></td>
					<td><input name="date_from" type="text" class="formField" id="date_from" value="All" size="11">
					</td>
					<td><div align="center">
						Through
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="All" size="11">
					</td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
						Select Lab
					</div></td>
					<td align="left" nowrap ><select name="lab_name" class="formField">
						<option value="all" selected>All</option>
						<?php
	$query="SELECT primary_key, lab_name FROM labs order by lab_name";
	$result=mysqli_query($con,$query) or die ("Could not find lab list");
	while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		print "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
?>
					</select></td>
					<td align="left" nowrap ><div align="right">
						Select Buying Group
					</div></td>
					<td align="left" nowrap ><select name="buying_group" class="formField">
						<option value="all" selected>All</option>
						<?php
	$query="SELECT primary_key, bg_name FROM buying_groups order by bg_name";
	$result=mysqli_query($con,$query) or die ("Could not find bg list");
	while ($bgList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		print "<option value=\"$bgList[primary_key]\">$bgList[bg_name]</option>";
}
?>
					</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$usercount=mysqli_num_rows($rptResult);
				$rptQuery="";}
			
			
if ($usercount != 0){


print "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
//if((($_POST[acctName]!="")||($_POST[acct_num]!=""))&&(($_POST[order_status]=="filled")&&($_POST[order_type]=="all"))){
//	print "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$stmtForm</td>";//show Print Statement button if one acct is selected, order status is Past and order type is ALL
//}
//elseif($_GET[prnStmt]=="yes"){
//	print "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$stmtForm</td>";//show Print Statement button if page is returned from Statement screen
//}
if($_POST[rpt_search]=="search orders"){
	print "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$exportForm</td>";//show Export Report button if order status is Open
}
elseif($_GET[exportData]=="yes"){
	print "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$exportForm</td>";//show Export Report button if returning from Export Screen
}else{
	print "<td colspan=\"10\"><font color=\"white\">$heading</font></td>";
}
  print "</tr>";
//  if($_SESSION["order_status"]=="processing")
//              print "<form action=\"report.php\" method=\"post\" name=\"statusForm\">";
			  print "<tr>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Company Account</td>
                <td align=\"center\">Buying Group</td>
                <td align=\"center\">Order Status</td>
                <td align=\"center\">Order Total</td>
                <td align=\"center\">Payment Status</td>
                <td align=\"center\">Payment Total</td>";
//                if($_SESSION["order_status"]=="processing")
//					print "<td align=\"center\">Fill Order</td>";
//				else
					echo "<td align=\"center\">&nbsp;</td>";
              echo "</tr>";
$labTotal=0;			  
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		if(!isset($currentLab))
			$currentLab=$listItem[lab_name];
			$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=$listItem[order_date_processed];
			$new_result=mysqli_query($con,"SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=$listItem[order_date_shipped];
			//$orderQuery="SELECT order_quantity, order_product_discount, order_over_range_fee from orders WHERE order_num='$listItem[order_num]'";
			//$orderResult=mysql_query($orderQuery)
			//	or die  ('I cannot select items because: ' . mysql_error());
			//$orderTotal=0;			  
			//while ($orderTally=mysql_fetch_array($orderResult)){
			//	$orderSubTally=bcmul($orderTally[order_quantity], $orderTally[order_product_discount], 2);
			//	$orderSubTally=bcadd($orderSubTally, $orderTally[order_over_range_fee], 2);
			//	$orderTotal=bcadd($orderTotal, $orderSubTally, 2);
			//}
			//$orderTotal=money_format('%.2n',$orderTotal);
//			$orderTotal=money_format('%.2n',$listItem[order_total]);
			$orderTotal=$listItem[order_total];
			if($listItem[pmt_amount]==0){
				$pmt_status="Open";
				$pmt_amount="";
			}else{
				$pmt_status="Paid";
//				$pmt_amount=money_format('%.2n',$listItem[pmt_amount]);
				$pmt_amount=$listItem[pmt_amount];
			}
			
				switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";					break;
						case 'order imported':			$list_order_status = "Order Imported";				break;
						case 'job started':				$list_order_status = "In Production";				break;
						case 'in coating':				$list_order_status = "In Coating";					break;
						case 'in mounting':				$list_order_status = "In Mounting";					break;
						case 'in edging':				$list_order_status = "In Edging";					break;
						case 'order completed':			$list_order_status = "Order Completed";				break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";				break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";				break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";				break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";				break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";				break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";				break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";				break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";			break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";			break;
						case 'information in hand':		$list_order_status = "Information in Hand";			break;
						case 'on hold':					$list_order_status = "On hold";						break;
						case 're-do':					$list_order_status = "Re-do";						break;
						case 'in transit':				$list_order_status = "In Transit";					break;
						case 'in edging swiss':			$list_order_status = "In Edging Swiss";				break;
						case 'filled':					$list_order_status = "Shipped";						break;
						case 'cancelled':				$list_order_status = "Cancelled";					break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier': $list_order_status = "Waiting for Frame Head Office/Supplier";	break;
				}
		
		if($currentLab!=$listItem[lab_name]){
//			$labTotal=money_format('%.2n',$labTotal);
			print "<tr bgcolor=\"#555555\"><td colspan=\"8\"><font color=\"white\">Total for $currentLab</font></td><td align=\"center\"><font color=\"white\">\$$labTotal</font></td><td align=\"center\"><font color=\"white\">&nbsp;</font></td></tr>";
			$labTotal=0;
			$labTotal=bcadd($labTotal, $orderTotal, 2);
			$labTotal=bcsub($labTotal, $pmt_amount, 2);
            print "<tr>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Company Account</td>
                <td align=\"center\">Buying Group</td>
                <td align=\"center\">Order Status</td>
                <td align=\"center\">Order Total</td>
                <td align=\"center\">Payment Status</td>
                <td align=\"center\">Payment Total</td>";
//                if($_SESSION["order_status"]=="processing")
//					print "<td align=\"center\">Fill Order</td>";
//				else
					print "<td align=\"center\">&nbsp;</td>";
              print "</tr>";
			print  "<tr><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$order_date</td>";
				if($ship_date!=0)
                	print "<td align=\"center\">$ship_date</td>";
				else
                	print "<td align=\"center\">&nbsp;</td>";
		
               print "<td align=\"center\">$listItem[company]</td>
                <td align=\"center\">$listItem[bg_name]</td>
                <td align=\"center\">$list_order_status</td>
                <td align=\"center\">\$$orderTotal</td>
                <td align=\"center\">$pmt_status</td>
                <td align=\"center\">";
				if($pmt_amount!=0)
					print "\$$pmt_amount</td>";
				else
					print "&nbsp;</td>";
//				if($_SESSION["order_status"]=="processing"){
//					$checkname="fill_" . $listItem[order_num];
//					print "<td><div align=\"center\"><input name=\"$checkname\" type=\"checkbox\" value=\"$listItem[order_num]\" class=\"formField\"></div></td>";
//				}else{
					print "<td>&nbsp;</td>";
//				}
              print "</tr>";
			$currentLab=$listItem[lab_name];
		}else{
			$labTotal=bcadd($labTotal, $orderTotal, 2);
			$labTotal=bcsub($labTotal, $pmt_amount, 2);
			print  "<tr><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$order_date</td>";
				if($ship_date!=0)
                	print "<td align=\"center\">$ship_date</td>";
				else
                	print "<td align=\"center\">&nbsp;</td>";
               print "<td align=\"center\">$listItem[company]</td>
                <td align=\"center\">$listItem[bg_name]</td>
                <td align=\"center\">$list_order_status</td>
                <td align=\"center\">\$$orderTotal</td>
                <td align=\"center\">$pmt_status</td>
                <td align=\"center\">";
				if($pmt_amount!=0)
					print "\$$pmt_amount</td>";
				else
					print "&nbsp;</td>";
//				if($_SESSION["order_status"]=="processing"){
//					$checkname="fill_" . $listItem[order_num];
//					print "<td><div align=\"center\"><input name=\"$checkname\" type=\"checkbox\" value=\"$listItem[order_num]\" class=\"formField\"></div></td>";
//				}else{
					print "<td>&nbsp;</td>";
//				}
              print "</tr>";
			}
		}//END WHILE
//			$labTotal=money_format('%.2n',$labTotal);
			print "<tr bgcolor=\"#555555\"><td colspan=\"8\"><font color=\"white\">Total for $currentLab</font></td><td align=\"center\"><font color=\"white\">\$$labTotal</font></td><td align=\"center\"><font color=\"white\">&nbsp;</font></td></tr>";
			
//		if($_SESSION["order_status"]=="processing"){
//			print "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\">&nbsp;</td><td colspan=\"2\"><div align=\"right\"><input name=\"updateStatus\" type=\"submit\" value=\"fill order(s)\" class=\"formField\"><input name=\"resetStatus\" type=\"reset\" value=\"reset\" class=\"formField\"></div></td></tr></form>";
//		}
		print "</table>";

}
else if ($heading==""){
}else{
print "<div class=\"formField\">No Orders Found</div>";
}//END USERCOUNT CONDITIONAL
?></td>
	  </tr>
</table>
  <p>&nbsp;</p>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
