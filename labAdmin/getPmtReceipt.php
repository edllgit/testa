<?php
$today=date("m/d/Y");
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
	
echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td></tr>";
echo "<tr><td colspan=\"10\"><b>Statement Payment Date $today</b></td></tr>";
if($pmt_by=="check"){
	echo "<tr><td colspan=\"10\"><b>Statement paid by $pmt_by $_SESSION[CHECK_NO]</b></td></tr>";
}else{
	echo "<tr><td colspan=\"10\"><b>Statement paid by credit card ending in $_SESSION[CCLAST4]</b></td></tr>";
}

if($prev_bal!=0){
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Previous Open Invoices</b></td>
	<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
	if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous unpaid orders
		$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
		$ordersBalData=$_SESSION["ORDERSBALDATA"];
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
		echo "<tr>
		<td align=\"left\" nowrap colspan=\"2\"><b>Previous Balance:</b></td>
		<td align=\"center\"><b>\$$prev_bal</b></td><td align=\"center\">&nbsp;</td></tr>";
	}
}
if($pay_all=="yes"){//total amt to pay (ALL orders) comes from statement form
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
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Current Open Orders Selected for Payment</b></td>
	<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
	echo "<tr>";
	echo "<td align=\"center\">Order Number</td>
<td align=\"center\">Disc Total</td>
<td align=\"center\">Pmt Amt</td>
<td align=\"center\">Final Pmt</td>";
	echo "</tr>";
	$postvars=$_SESSION["POSTVARS"];
	foreach($postvars as $x => $y){//X is fill_order num, y is order amt
		$x_test=explode("_", $x);
		if(($x_test[0]=="fill")&&($y > 0)){
			echo "<tr>";
			$paid_cbox=$postvars["paid_".$x_test[1]];
			$discAmt=$postvars["disc_".$x_test[1]];
			$pmtAmt=number_format($y, 2, '.', '');
			echo "<td align=\"center\">$x_test[1]</td>";//display order num
			echo "<td align=\"center\">\$$discAmt</td>";//display disc amt
			echo "<td align=\"center\">\$$pmtAmt</td>";//display pmt amt
			echo "<td align=\"center\">$paid_cbox</td>";//display paid in full status
			echo "</tr>";
		}	
	}
}
echo "<tr>
<td align=\"left\" nowrap colspan=\"2\"><b>Total Amount of Orders Paid</b></td>
<td align=\"center\"><b>\$$_SESSION[totalCharge]</b></td><td align=\"center\">&nbsp;</td></tr>";
echo "<tr>
<td align=\"left\" nowrap colspan=\"2\"><b>Total Memo Credits Applied</b></td>
<td align=\"center\"><b>\$$_SESSION[MEMOCREDTOTAL]</b></td><td align=\"center\">&nbsp;</td></tr>";
echo "<tr><td align=\"left\" nowrap colspan=\"2\"><b>Monthly Statement Credit</b></td><td align=\"center\"><b>\$-$_SESSION[STMTCREDIT]</b><td align=\"center\">&nbsp;</td></tr>";//print the credit
echo "<tr><td colspan=\"2\" class=\"Subheader\"><b>Previous Balance Applied</b></td><td class=\"Subheader\"><div align=\"center\"><b>\$$_SESSION[PREV_BAL]</b></div></td><td class=\"Subheader\"><div align=\"center\">&nbsp;</div></td></tr>";//print the previous balance
echo "<tr>
<td align=\"left\" nowrap colspan=\"2\"><b>Total Amount Paid</b></td>
<td align=\"center\"><b>\$$_SESSION[grandTotal]</b></td><td align=\"center\">&nbsp;</td></tr>";
echo "</table>";

if($pmt_by!="check"){
	sendDLEmail();
}
?>
