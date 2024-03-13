<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[updateStatus]=="fill order(s)")
	update_order_status();

if($_POST[rpt_search]=="search orders"){
	if($_POST[order_type]=="stock")
		$order_type="(order_product_type='stock' or order_product_type='stock_tray')";//search stock orders
	else
		$order_type="order_product_type='" . $_POST[order_type] . "'";//search prescription or all orders
		
$rptQuery="SELECT buying_groups.bg_name, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_patient_first, orders.order_patient_last, orders.order_total, orders.order_date_processed, orders.order_date_shipped, accounts.company, orders.order_status, est_ship_date.est_ship_date, payments.pmt_amount, payments.pmt_marker, payments.pmt_date from orders

LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 

LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 

LEFT JOIN (est_ship_date) ON (orders.order_num=est_ship_date.order_num)
	
WHERE orders.lab='$lab_pkey' AND orders.order_status='$_POST[order_status]'";



	//$rptQuery="SELECT * from orders, accounts, buying_groups WHERE orders.user_id=accounts.user_id AND accounts.buying_group=buying_groups.primary_key AND orders.lab='$lab_pkey' AND orders.order_status='$_POST[order_status]'";
	$heading="$_POST[order_status] - $_POST[order_type] Orders";
	
	if($_POST[order_type]!="all")
		$rptQuery.=" AND " . $order_type;
	
	if($_POST[acct_num]!=""){//if entered acct number
		$rptQuery.=" AND accounts.account_num='" . $_POST[acct_num] . "'";
		$query="select account_num, company from accounts where account_num='$_POST[acct_num]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" from account $acctData[company] with account number $_POST[acct_num]";
	}
	elseif($_POST[acctName]==""){//if select ALL accounts
		if($_POST[buying_group]!="all"){//AND a buying group, including NONE (primary key = 1), was selected
			$rptQuery.=" AND accounts.buying_group='" . $_POST[buying_group] . "'";
			$query="select primary_key, bg_name from buying_groups where primary_key='$_POST[buying_group]'";
			$result=mysql_query($query)
				or die ("Could not find bg list");
			$bgData=mysql_fetch_array($result);
			$heading.=" from buying group $bgData[bg_name]";
		}
	}else{
		$rptQuery.=" AND orders.user_id='" . $_POST[acctName] . "'";// ONE account was selected
		$query="select user_id, company from accounts where user_id='$_POST[acctName]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" from account $acctData[company]";
	}	
	if (($_POST[date_from] != "All" && $_POST[date_to] != "All")&&($_POST[order_status]=="processing")){//select Open orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_item_date between '$date_from' and '$date_to'";
	}
	if (($_POST[date_from] != "All" && $_POST[date_to] != "All")&&($_POST[order_status]=="filled")){//select Filled orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";
	}

	$rptQuery.=" group by order_num desc order by buying_group";
	$heading.=$dateInfo;
	$heading=ucwords($heading);
}
//	$rptQuery="SELECT * from orders WHERE order_status='$_POST[order_status]' and order_product_type='$_POST[order_type]' and lab='$lab_pkey' group by order_num desc";

$stmtForm="<form  method=\"post\" name=\"stmt_form\" id=\"stmt_form\" action=\"printStmt.php\" target=\"_blank\"><input name=\"accountStmt\" type=\"hidden\" value=\"$_POST[acctName]\"><input name=\"printStmt\" type=\"submit\" value=\"Print Statement\" class=\"formField\"></form>";//Print Statement button
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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>

</head>

<body onLoad="window.print(); window.close();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
<!--  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
-->  		<td awidth="75%">
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}
			
			
if ($usercount != 0){


echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
if((($_POST[acctName]!="")||($_POST[acct_num]!=""))&&(($_POST[order_status]=="filled")&&($_POST[order_type]=="all"))){
	echo "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$stmtForm</td>";//show Print Statement button if one acct is selected, order status is Past and order type is ALL
}
elseif($_GET[prnStmt]=="yes"){
	echo "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$stmtForm</td>";//show Print Statement button if page is returned from Statement screen
}
elseif($_POST[order_status]=="processing"){
	echo "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$exportForm</td>";//show Export Report button if order status is Open
}
elseif($_GET[exportData]=="yes"){
	echo "<td colspan=\"8\"><font color=\"white\">$heading</font></td><td colspan=\"2\">$exportForm</td>";//show Export Report button if returning from Export Screen
}else{
	echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td>";
}
  echo "</tr>";
  if($_SESSION["order_status"]=="processing")
              echo "<form action=\"report.php\" method=\"post\" name=\"statusForm\">";
			  echo "<tr>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Est Ship Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Company Account</td>
                <td align=\"center\">Order Status</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Order Total</td>";
                if($_SESSION["order_status"]=="processing")
					echo "<td align=\"center\">Fill Order</td>";
				else
					echo "<td align=\"center\">&nbsp;</td>";
              echo "</tr>";
$bgTotal=0;			  
while ($listItem=mysql_fetch_array($rptResult)){
		if(!isset($currentBG))
			$currentBG=$listItem[bg_name];
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
			
			
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
			$orderTotal=money_format('%.2n',$listItem[order_total]);
			if($listItem[pmt_amount]==0){
				$pmt_status="Open";
				$pmt_amount="";
			}else{
				$pmt_status="Paid";
				$pmt_amount=money_format('%.2n',$listItem[pmt_amount]);
			}
		if($currentBG!=$listItem[bg_name]){
			$bgTotal=money_format('%.2n',$bgTotal);
			echo "<tr bgcolor=\"#555555\"><td colspan=\"8\"><font color=\"white\">Total for $currentBG</font></td><td align=\"center\"><font color=\"white\">\$$bgTotal</font></td><td align=\"center\"><font color=\"white\">&nbsp;</font></td></tr>";
			$bgTotal=0;
			$bgTotal=bcadd($bgTotal, $orderTotal, 2);
			$bgTotal=bcsub($bgTotal, $pmt_amount, 2);
            echo "<tr>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Est Ship Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Company Account</td>
                <td align=\"center\">Order Status</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Order Total</td>";
                if($_SESSION["order_status"]=="processing")
					echo "<td align=\"center\">Fill Order</td>";
				else
					echo "<td align=\"center\">&nbsp;</td>";
              echo "</tr>";
			echo  "<tr><td align=\"center\"><a href=\"display_order.php?order_num=$listItem[order_num]$type_string&po_num=$listItem[po_num]$type_string\">$listItem[order_num]</a></td>
                <td align=\"center\">$order_date</td>";
				 echo "<td align=\"center\">$listItem[est_ship_date]</td>";
				if($ship_date!=0)
                	echo "<td align=\"center\">$ship_date</td>";
				else
                	echo "<td align=\"center\">&nbsp;</td>";
               echo "<td align=\"center\">$listItem[company]</td>
                <td align=\"center\">$listItem[order_status]</td>
				<td align=\"center\">$listItem[order_patient_first]&nbsp;&nbsp;$listItem[order_patient_last]</td>
                <td align=\"center\">\$$orderTotal</td>";
              
            
				if($_SESSION["order_status"]=="processing" && false){
					$checkname="fill_" . $listItem[order_num];
					echo "<td><div align=\"center\"><input name=\"$checkname\" type=\"checkbox\" value=\"$listItem[order_num]\" class=\"formField\"></div></td>";
				}else{
					echo "<td>&nbsp;</td>";
				}
              echo "</tr>";
			$currentBG=$listItem[bg_name];
		}else{
			$bgTotal=bcadd($bgTotal, $orderTotal, 2);
			$bgTotal=bcsub($bgTotal, $pmt_amount, 2);
			echo  "<tr><td align=\"center\"><a href=\"display_order.php?order_num=$listItem[order_num]$type_string&po_num=$listItem[po_num]$type_string\">$listItem[order_num]</a></td>
                <td align=\"center\">$order_date</td>";
				echo "<td align=\"center\">$listItem[est_ship_date]</td>";
				if($ship_date!=0)
                	echo "<td align=\"center\">$ship_date</td>";
				else
                	echo "<td align=\"center\">&nbsp;</td>";
               echo "<td align=\"center\">$listItem[company]</td>
                <td align=\"center\">$listItem[order_status]</td>
                <td align=\"center\">$listItem[order_patient_first]&nbsp;&nbsp;$listItem[order_patient_last]</td>
                <td align=\"center\">\$$orderTotal</td>";
              
            
				if($_SESSION["order_status"]=="processing" && false){
					$checkname="fill_" . $listItem[order_num];
					echo "<td><div align=\"center\"><input name=\"$checkname\" type=\"checkbox\" value=\"$listItem[order_num]\" class=\"formField\"></div></td>";
				}else{
					echo "<td>&nbsp;</td>";
				}
              echo "</tr>";
			}
		}//END WHILE
			$bgTotal=money_format('%.2n',$bgTotal);
			echo "<tr bgcolor=\"#555555\"><td colspan=\"8\"><font color=\"white\">Total for $currentBG</font></td><td align=\"center\"><font color=\"white\">\$$bgTotal</font></td><td align=\"center\"><font color=\"white\">&nbsp;</font></td></tr>";
			
		if($_SESSION["order_status"]=="processing"){
			echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"8\">&nbsp;</td><td colspan=\"2\"><div align=\"right\"></div></td></tr></form>";
		}
		echo "</table>";

}
else if ($heading==""){
}
else {
echo "<div class=\"formField\">No Orders Found</div>";}//END USERCOUNT CONDITIONAL
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
