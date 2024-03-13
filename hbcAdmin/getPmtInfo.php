<?php 
if(isset($_POST[pay_all])){
	$pay_all=$_POST[pay_all];
	$_SESSION["pay_all"]=$_POST[pay_all];
}else{
	$pay_all=$_SESSION["pay_all"];
}
if($_POST[pmt_by]){
	$pmt_by=$_POST[pmt_by];
	$_SESSION["pmt_by"]=$_POST[pmt_by];
}else{
	$pmt_by=$_SESSION["pmt_by"];
}
if(!isset($prev_bal))
	$prev_bal=$_SESSION["PREV_BAL"];

if(!isset($date_from))
	$date_from=$_SESSION["DATE_FROM"];

if(!isset($date_to))
	$date_to=$_SESSION["DATE_TO"];

if(!isset($heading))
	$heading=$_SESSION["heading"];
	
if(!isset($stmt_credit))
	$stmt_credit = $_SESSION["STMTCREDIT"];
	
if(!isset($memoCredTotal))
	$memoCredTotal=$_SESSION["MEMOCREDTOTAL"];
if(!isset($prev_bal))
	$prev_bal=$_SESSION["PREV_BAL"];
if(!isset($grandTotal))
	$grandTotal=$_SESSION["grandTotal"];


$pay_allTotal=$_SESSION["pay_allTotal"];//subtract actual payment from this amt to show balance
	
echo "<form action=\"pmt_form1.php\" method=\"post\" name=\"orderForm\">";
echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td></tr>";

if($prev_bal!=0){
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Previous Open Invoices</b></td>
	<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
	if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous unpaid orders
		$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
		$ordersBalData=$_SESSION["ORDERSBALDATA"];
		if($count_prev_orders){
			echo "<tr>";
			echo "<td align=\"center\">Order Number</td>
		<td align=\"center\">Balance Due</td>
		<td align=\"center\">Pmt Amt</td>
		<td align=\"center\">Final Pmt</td>";
			echo "</tr>";
			for ($i = 1; $i <= $count_prev_orders; $i++){//get order data
				echo "<tr>";
				echo "<td align=\"center\">".$ordersBalData[$i]["order_num"]."</td>
			<td align=\"center\">\$".$ordersBalData[$i]["balance"]."</td>
			<td align=\"center\">\$".$ordersBalData[$i]["balance"]."</td>
			<td align=\"center\">y</td>";
				echo "</tr>";
			}
		}
		echo "<tr>
		<td align=\"left\" nowrap colspan=\"2\"><b>Previous Balance:</b></td>
		<td align=\"center\"><b>\$$prev_bal</b></td><td align=\"center\">&nbsp;</td></tr>";
	}
}
if($_POST[pay_all]=="yes"){//total amt to pay (ALL orders) comes from statement form
	$count_current_orders=count($_SESSION["order_numbers"]);
	if($count_current_orders > 0){
		$order_numbers=$_SESSION["order_numbers"];
		$order_amts=$_SESSION["order_amts"];
		echo "<tr>
		<td align=\"left\" nowrap colspan=\"2\"><b>Current Open Invoices</b></td>
		<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
		echo "<tr>";
		echo "<td align=\"center\">Order Number</td>
	<td align=\"center\">Balance Due</td>
	<td align=\"center\">Pmt Amt</td>
	<td align=\"center\">Final Pmt</td>";
		echo "</tr>";
		for ($i = 1; $i <= $count_current_orders; $i++){//get order data
			$order_paid[$i] = "y";
			echo "<tr>";
			echo "<td align=\"center\">".$order_numbers[$i]."</td>
		<td align=\"center\">\$".$order_amts[$i]."</td>
		<td align=\"center\">\$".$order_amts[$i]."</td>
		<td align=\"center\">".$order_paid[$i]."</td>";
			echo "</tr>";
		}//END FOR
		$_SESSION["order_paid"] = $order_paid;
	}
}else{//only some orders are checked in the statement
	unset($checktest);
	$i=1;
	$_SESSION["POSTVARS"]=$_POST;
	foreach($_POST as $x => $y){//X is fill_order num, y is order amt
		$x_test=explode("_", $x);
		if(($x_test[0]=="fill")&&($y > 0)&&(!$checktest)){
			$checktest=1;
			$_SESSION["order_numbers"]=array();//overwrite the orders session vars that were set in payStmtForm.php for Pay All Orders
			$_SESSION["order_totals"]=array();//current order balances
			$_SESSION["order_amts"]=array();//current payments to apply
			$_SESSION["order_paid"]=array();//y or blank
			$_SESSION["orderCount"]=0;//number of orders
			$_SESSION["totalCharge"]=0;//total amount of all orders
			$_SESSION["grandTotal"]=0;//total amount of all orders, credits and prev bal to be paid
			echo "<tr>
			<td align=\"left\" nowrap colspan=\"2\"><b>Current Open Orders Selected for Payment</b></td>
			<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
			echo "<tr>";
			echo "<td align=\"center\">Order Number</td>
		<td align=\"center\">Disc Total</td>
		<td align=\"center\">Pmt Amt</td>
		<td align=\"center\">Final Pmt</td>";
			echo "</tr>";
		}
		if(($x_test[0]=="fill")&&($y > 0)&&($checktest)){
			$paid_cbox=$_POST["paid_".$x_test[1]];
			$discAmt=$_POST["disc_".$x_test[1]];
			$pmtAmt=number_format($y, 2, '.', '');
			echo "<tr>";
			echo "<td align=\"center\">$x_test[1]</td>";//display order num
			echo "<td align=\"center\">\$$discAmt</td>";//display disc amt
			echo "<td align=\"center\">\$$pmtAmt</td>";//display pmt amt
			echo "<td align=\"center\">$paid_cbox</td>";//display paid in full status
			$_SESSION["order_paid"][$i] = $paid_cbox;
			$_SESSION["order_numbers"][$i] = $x_test[1];
			$_SESSION["order_totals"][$i] = $discAmt;
			$_SESSION["order_amts"][$i] = $y;
			$_SESSION["orderCount"]++;
			$_SESSION["totalCharge"]=bcadd($_SESSION["totalCharge"], $y, 2);
			echo "</tr>";
			$i++;
		}	
	}//END foreach
	$_SESSION["grandTotal"]=$_SESSION["totalCharge"];
	$_SESSION["grandTotal"]=bcsub($_SESSION["grandTotal"], $_SESSION["STMTCREDIT"], 2);
	$_SESSION["grandTotal"]=bcadd($_SESSION["grandTotal"], $_SESSION["MEMOCREDTOTAL"], 2);
	$_SESSION["grandTotal"]=bcadd($_SESSION["grandTotal"], $_SESSION["PREV_BAL"], 2);
}

if($pay_allTotal > 0){
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Total Amount of Current Balance</b></td>
	<td align=\"center\"><b>\$$pay_allTotal</b></td><td align=\"center\">&nbsp;</td></tr>";
}
$pay_allTotal=bcsub($pay_allTotal, $_SESSION["grandTotal"], 2);
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Balance Remaining After Payment</b></td>
	<td align=\"center\"><b>\$$pay_allTotal</b></td><td align=\"center\">&nbsp;</td></tr>";
	
if($_SESSION["totalCharge"] > 0){
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Total Amount of Current Open Orders Selected</b></td>
	<td align=\"center\"><b>\$$_SESSION[totalCharge]</b></td><td align=\"center\">&nbsp;</td></tr>";
}
echo "<tr>
<td align=\"left\" nowrap colspan=\"2\"><b>Total Memo Credits</b></td>
<td align=\"center\"><b>\$$_SESSION[MEMOCREDTOTAL]</b></td><td align=\"center\">&nbsp;</td></tr>";
echo "<tr><td align=\"left\" nowrap colspan=\"2\"><b>Monthly Statement Credit</b></td><td align=\"center\"><b>\$-$_SESSION[STMTCREDIT]</b><td align=\"center\">&nbsp;</td></tr>";//print the credit
echo "<tr>
<td align=\"left\" nowrap colspan=\"2\"><b>Total Amount to be Paid</b></td>
<td align=\"center\"><b>\$$_SESSION[grandTotal]</b></td><td align=\"center\">&nbsp;</td></tr>";
echo "<input type=\"hidden\" name=\"acctDiscTotal\" value=\"$_SESSION[grandTotal]\"><input name=\"date_from\" type=\"hidden\" value=\"$_POST[date_from]\" /><input name=\"date_to\" type=\"hidden\" value=\"$_POST[date_to]\" /><input type=\"hidden\" name=\"pmt_by\" value=\"$_SESSION[pmt_by]\" /><input name=\"acctName\" type=\"hidden\" value=\"$_POST[acctName]\" />";

  if($pmt_by=="ccard") 
	include("ccardInfo.php");
else
	include("checkInfo.php");
echo "<tr><td colspan=\"4\" align=\"center\"><input name=\"Cancel\" type=\"reset\" class=\"formField\" value=\"Cancel\">
	&nbsp;
	<input name=\"submitPmt\" type=\"submit\" class=\"formField\" value=\"Submit\">
</td></tr></table>
</form>";
?>
