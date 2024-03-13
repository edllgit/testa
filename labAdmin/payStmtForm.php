<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}
if(isset($_POST[pay_all])){
	$pay_all=$_POST[pay_all];
	$_SESSION["pay_all"]=$_POST[pay_all];
}else{
	if(isset($_SESSION["pay_all"])){
		$pay_all=$_SESSION["pay_all"];
	}else{
		$pay_all="yes";
	}
}
if($_POST[pmt_by]){
	$pmt_by=$_POST[pmt_by];
	$_SESSION["pmt_by"]=$_POST[pmt_by];
}else{
	if(isset($_SESSION["pmt_by"])){
		$pmt_by=$_SESSION["pmt_by"];
	}else{
		$pmt_by="check";
	}
}
if(!isset($prev_bal))
	$prev_bal=$_SESSION["PREV_BAL"];

if(!isset($date_from))
	$date_from=$_SESSION["DATE_FROM"];

if(!isset($date_to))
	$date_to=$_SESSION["DATE_TO"];

if(!isset($stmt_credit_month))
	$stmt_credit_month=$_SESSION["STMT_CREDIT_MONTH"];

if(!isset($stmt_credit_year))
	$stmt_credit_year=$_SESSION["STMT_CREDIT_YEAR"];

if(!isset($heading))
	$heading=$_SESSION["heading"];
	
if(!isset($acct_user_id))
	$acct_user_id=$_SESSION["user_id"];

$_SESSION["grandTotal"]=0;//reset the final total, which includes stmt credits, memo credits and previous balance
	
echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td></tr>";
echo "<form action=\"pmt_form1.php\" method=\"post\" name=\"orderForm\">";

if($prev_bal!=0){//previous balance data
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Previous Open Invoices</b></td>
	<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
	if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous unpaid orders
		$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
		$ordersBalData=$_SESSION["ORDERSBALDATA"];
		echo "<tr>";
		echo "<td align=\"center\">Order Number</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
	<td align=\"center\">Balance Due</td>
		<td align=\"center\">&nbsp;</td>
	<td align=\"center\">Pmt Amt</td>
	<td align=\"center\">Final Pmt</td>";
		echo "</tr>";
		for ($i = 1; $i <= $count_prev_orders; $i++){//get order data
			if($ordersBalData[$i]["balance"] > 0){
				$currentAcct=$ordersBalData[$i]["company"];
				echo "<tr>";
				echo "<td align=\"center\">".$ordersBalData[$i]["order_num"]."</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">\$".$ordersBalData[$i]["balance"]."</td>
			<td align=\"center\">&nbsp;</td>
			<td align=\"center\">\$".$ordersBalData[$i]["balance"]."</td>
			<td align=\"center\">y</td>";
				echo "</tr>";
			}
		}
		echo "<tr>
		<td align=\"left\" nowrap colspan=\"2\"><b>Previous Balance:</b></td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\"><b>\$$prev_bal</b></td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		<td align=\"center\">&nbsp;</td>
		</tr>";
	}
	$_SESSION["grandTotal"]=$prev_bal;//add previous balance
}

if (($usercount != 0)||($prev_bal != 0)){//payment form
	echo "<tr><td colspan=\"5\">Pay previous balance plus ALL open orders and apply ALL credits for this statement<input name=\"pay_all\" type=\"radio\" value=\"yes\" class=\"formField\"";
	if($pay_all=="yes") echo " checked=\"checked\"";
	echo " onchange=\"document.orderForm.submit();\">Yes&nbsp;&nbsp;&nbsp;<input name=\"pay_all\" type=\"radio\" value=\"\" class=\"formField\"";
	if($pay_all=="") echo "checked=\"checked\"";
	echo " onchange=\"document.orderForm.submit();\">No</td>";
	echo "<td colspan=\"5\" align=\"right\">Pay by <input name=\"pmt_by\" type=\"radio\" value=\"ccard\" class=\"formField\"";
	if($pmt_by=="ccard") echo " checked=\"checked\"";
	echo ">Credit Card&nbsp;&nbsp;&nbsp;<input name=\"pmt_by\" type=\"radio\" value=\"check\" class=\"formField\"";
	if($pmt_by=="check") echo "checked=\"checked\"";
	echo ">Check</td></tr>";
}
if ($usercount != 0){//current balance data
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"2\"><b>Current Orders</b></td>
	<td align=\"center\">&nbsp;</td><td align=\"center\">&nbsp;</td></tr>";
	echo "<tr>
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Order Date</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Buying Group</td>
			<td align=\"center\">Order Status</td>
			<td align=\"center\">Order Total</td>
			<td align=\"center\">Disc Total</td>
			<td align=\"center\">Payment Status</td>
			<td align=\"center\">Pmt Amt</td>
			<td align=\"center\">Final Pmt</td>";
    echo "</tr>";
	$result=mysql_query("SELECT curdate()");/* get today's date */
		$today=mysql_result($result,0,0);
	$acctTotal=0;			  
	$acctDiscTotal=0;
	$i=1;
	$_SESSION["order_numbers"]=array();//all statement order numbers to be paid
	$_SESSION["order_amts"]=array();//all statement order amounts
	$_SESSION["orderCount"]=0;//total number of statement orders
	$_SESSION["totalCharge"]="0.00";//total current statement charge
	while ($listItem=mysql_fetch_array($rptResult)){
		$currentAcct=$listItem[company];
		$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
		$order_date=mysql_result($new_result,0,0);
		$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
		$ship_date=mysql_result($new_result,0,0);
		$result=mysql_query("SELECT DATE_ADD('$listItem[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date for due date */
		$duedate=mysql_result($result,0,0);
		$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
		$discountdate_15=mysql_result($result,0,0);
		$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
		$discountdate_10=mysql_result($result,0,0);
		if($discountdate_15 >= $today){
			
			$discountamt=bcmul('.00', $listItem[order_total], 2);
			//$discountamt=bcmul('.02', $listItem[order_total], 2);
			$pass_disc=".00";
			$discount = "0%";
		}
		elseif($discountdate_10 >= $today){
			$discountamt=bcmul('.00', $listItem[order_total], 2);
			$pass_disc=".00";
			$discount = "0%";
		}else{
			$discountamt=0;
		}
		$discounted_total_cost=bcsub($listItem[order_total], $discountamt, 2);
		$discounted_total_cost=bcadd($discounted_total_cost, $listItem[order_shipping_cost], 2);//add non-discounted shipping cost
		$orderTotal=bcadd($listItem[order_total], $listItem[order_shipping_cost], 2);//add shipping cost
		if($listItem[pmt_amount]==0){//this order has NOT been paid
			$pmt_status="Open";
			$pmt_amount="";
			$_SESSION["order_numbers"][$i]=$listItem[order_num];
			$_SESSION["order_amts"][$i]=$discounted_total_cost;
			$_SESSION["orderCount"]++;
			$_SESSION["totalCharge"]=bcadd($_SESSION["totalCharge"], $discounted_total_cost, 2);
			$i++;
			$acctDiscTotal=bcadd($acctDiscTotal, $discounted_total_cost, 2);//add to discount total
			$acctTotal=bcadd($acctTotal, $orderTotal, 2);//add to total
		}else{
			if($listItem["order_paid_in_full"] !="y"){//there's only a partial pmt
//				$order_subtotal=bcsub($listItem["order_total"], $listItem["pmt_amount"], 2);//subtract the partial pmt from the order total
				$order_subtotal=$listItem["order_balance"];//get the order balance
				if($order_subtotal > 0){
					$_SESSION["order_numbers"][$i]=$listItem[order_num];
					$_SESSION["order_amts"][$i]=$order_subtotal;
					$discounted_total_cost=$order_subtotal;
					$_SESSION["orderCount"]++;
					$_SESSION["totalCharge"]=bcadd($_SESSION["totalCharge"], $order_subtotal, 2);
					$pmt_status="Open";
					$pmt_amount=money_format('%.2n',$listItem[order_subtotal]);
					$i++;
				}
			}else{
				$pmt_status="Paid";
				$pmt_amount=money_format('%.2n',$listItem[pmt_amount]);
			}
		}
			echo  "<tr><td align=\"center\">$listItem[order_num]</td>
			<td align=\"center\">$order_date</td>";
			if($ship_date!=0)
				echo "<td align=\"center\">$ship_date</td>";
			else
				echo "<td align=\"center\">&nbsp;</td>";
		   echo "<td align=\"center\">$listItem[bg_name]</td>
			<td align=\"center\">$listItem[order_status]</td>
			<td align=\"center\">\$$orderTotal</td>
			<td align=\"center\">\$$discounted_total_cost</td>
			<td align=\"center\">$pmt_status</td>";
			if(($pmt_status=="Open")&&($pay_all=="yes")){
				$checkbox="paid_" . $listItem[order_num];//set disabled checkbox to yes for payment in full with order number
				$checkname="fill_" . $listItem[order_num];//set hidden var for discount payment amount with order number
				echo "<td><div align=\"center\"><input name=\"$checkname\" type=\"hidden\" value=\"$discounted_total_cost\">\$$discounted_total_cost</div></td><td><div align=\"center\"><input name=\"$checkbox\" type=\"checkbox\" value=\"y\" class=\"formField\" checked=\"checked\" disabled=\"disabled\"></div></td>";
			}
			elseif(($pmt_status=="Open")&&($pay_all=="")){
				$checkbox="paid_" . $listItem[order_num];//set checkbox for payment in full with order number
				$checkname="fill_" . $listItem[order_num];//set text field for payment amount with order number
				echo "<td><div align=\"center\"><input name=\"disc_" . $listItem[order_num] . "\" type=\"hidden\" value=\"$discounted_total_cost\" /><input name=\"$checkname\" type=\"text\" class=\"formField\" size=\"8\" value=\"$discounted_total_cost\"></div></td><td><div align=\"center\"><input name=\"$checkbox\" type=\"checkbox\" value=\"y\" class=\"formField\" checked=\"checked\"></div></td>";
			}else{
				echo "<td><div align=\"center\">&nbsp;</div></td>";
			}
		  echo "</tr>";
		}//END WHILE
	echo "<tr>
	<td align=\"left\" nowrap colspan=\"6\"><b>Total Amount of Current Open Orders</b></td>
	<td align=\"center\"><b>\$$_SESSION[totalCharge]</b></td><td align=\"center\">&nbsp;</td></tr>";
	$_SESSION["grandTotal"]=bcadd($_SESSION["grandTotal"], $_SESSION["totalCharge"], 2);//add current invoices
}else{
	echo "<tr><td colspan=\"5\"><div class=\"formField\"><b>No Current Orders Found</b></div></td></tr>";
}//END USERCOUNT CONDITIONAL

$memoQuery="SELECT * from memo_credits WHERE mcred_date between '$date_from' and '$date_to' AND mcred_acct_user_id = '$acct_user_id'";
$memoResult=mysql_query($memoQuery)	or die  ('I cannot select items because: ' . mysql_error());
$memoCount=mysql_num_rows($memoResult);
if($memoCount > 0){
	$memoCredTotal=0;
	$i=1;
	while ($memoData=mysql_fetch_array($memoResult)){
		if($memoData[date_mc_applied]==0){//if not already applied to this statement previously
			$memoCredList[$i]=$memoData[mcred_primary_key];
			$i++;
			if($memoData[mcred_cred_type]=="credit"){
				$memoCredTotal=bcsub($memoCredTotal, $memoData[mcred_abs_amount], 2);
			}else{
				$memoCredTotal=bcadd($memoCredTotal, $memoData[mcred_abs_amount], 2);
			}
		}
	}
	if(is_array($memoCredList))
		$_SESSION["MEMOCREDLIST"]=$memoCredList;
//		$acctTotal=bcadd($acctTotal, $memoCredTotal, 2);//include memo credits
//		$acctDiscTotal=bcadd($acctDiscTotal, $memoCredTotal, 2);//include memo credits
	$_SESSION["grandTotal"]=bcadd($_SESSION["grandTotal"], $memoCredTotal, 2);//add memo credits
}
	if($memoCredTotal)
		$_SESSION["MEMOCREDTOTAL"]=$memoCredTotal;
	else
		$_SESSION["MEMOCREDTOTAL"]="0.00";
//		$acctTotal=bcadd($acctTotal, $prev_bal, 2);//include previous balance
//		$acctDiscTotal=bcadd($acctDiscTotal, $prev_bal, 2);//include previous balance

	//$acctTotal=money_format('%.2n',$acctTotal);
$_SESSION["company"]=$currentAcct;

$query="SELECT * from statement_credits WHERE acct_user_id='$acct_user_id' AND stmt_month='$stmt_credit_month' AND stmt_year='$stmt_credit_year'";//get end of month credit for this acct
$result=mysql_query($query)	or die  ('I cannot select credits because: ' . mysql_error());
$credit_count=mysql_num_rows($result);
if($credit_count != 0){
	$i=1;
	while($credit_acct=mysql_fetch_array($result)){
		if($credit_acct[date_sc_applied]==0){//if not already applied to this statement previously
			$stmtCredList[$i]=$credit_acct[primary_key_cr];
			$i++;
			$credit_amt=number_format($credit_acct[amount], 2, '.', '');
//			echo "<tr><td colspan=\"5\" class=\"Subheader\"><b>$credit_acct[credit_option] Monthly Statement Credit:</b></td><td class=\"Subheader\"><div align=\"center\">&nbsp;</div><td class=\"Subheader\"><div align=\"center\"><b>\$-$credit_amt</b></div></td><td colspan=\"3\" class=\"Subheader\"><div align=\"center\">&nbsp;</div></td></tr>";//print the credit
			$acctTotal=bcsub($acctTotal, $credit_amt, 2);//subtract end of month credit
			$acctDiscTotal=bcsub($acctDiscTotal, $credit_amt, 2);//subtract end of month credit
//			$_SESSION["totalCharge"]=bcsub($_SESSION["totalCharge"], $credit_amt, 2);//subtract end of month credit
			$_SESSION["grandTotal"]=bcsub($_SESSION["grandTotal"], $credit_amt, 2);//subtract end of month credit
		}else{
			$credit_amt="0.00";
		}
	}
	if(is_array($stmtCredList))
		$_SESSION["STMTCREDLIST"]=$stmtCredList;
	$_SESSION["STMTCREDIT"]=$credit_amt;
}else{
	$_SESSION["STMTCREDIT"]="0.00";
}
if(!$_SESSION["pay_allTotal"]){//1st pass thru file, set the total amount owed
	$_SESSION["pay_allTotal"]=bcadd($_SESSION["totalCharge"], $_SESSION["PREV_BAL"], 2);
	$_SESSION["user_id_total"]=$_POST["acctName"];
}
elseif($_SESSION["user_id_total"]!=$_POST["acctName"]){//customer account id changed, set the total amount owed
	$_SESSION["pay_allTotal"]=bcadd($_SESSION["totalCharge"], $_SESSION["PREV_BAL"], 2);
	$_SESSION["user_id_total"]=$_POST["acctName"];
}
//print the memo credit total
echo "<tr><td colspan=\"5\" class=\"Subheader\"><b>Total Memo Credits</b></td><td class=\"Subheader\"><div align=\"center\">&nbsp;</div><td class=\"Subheader\"><div align=\"center\"><b>\$$_SESSION[MEMOCREDTOTAL]</b></div></td><td colspan=\"3\" class=\"Subheader\"><div align=\"center\">&nbsp;</div></td></tr>";
//print the statement credit total
echo "<tr><td colspan=\"5\" class=\"Subheader\"><b>$credit_acct[credit_option] Monthly Statement Credit</b></td><td class=\"Subheader\"><div align=\"center\">&nbsp;</div><td class=\"Subheader\"><div align=\"center\"><b>\$-$_SESSION[STMTCREDIT]</b></div></td><td colspan=\"3\" class=\"Subheader\"><div align=\"center\">&nbsp;</div></td></tr>";
//print the previous balance
echo "<tr><td colspan=\"5\" class=\"Subheader\"><b>Previous Balance</b></td><td class=\"Subheader\"><div align=\"center\">&nbsp;</div></td><td class=\"Subheader\"><div align=\"center\"><b>\$$_SESSION[PREV_BAL]</b></div></td><td colspan=\"3\" class=\"Subheader\"><div align=\"center\">&nbsp;</div></td></tr>";	
//print the grand total
echo "<tr bgcolor=\"#555555\"><td colspan=\"5\"><font color=\"white\">Total Balance for $_SESSION[COMPANY]</font></td><td align=\"center\"><font color=\"white\">&nbsp;</font></td><td align=\"center\"><font color=\"white\">\$$_SESSION[grandTotal]</font></td><td colspan=\"3\">&nbsp;</td></tr>";
		
echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"9\"><div align=\"center\"><input name=\"acctDiscTotal\" type=\"hidden\" value=\"$acctDiscTotal\" /><input name=\"acctName\" type=\"hidden\" value=\"$_POST[acctName]\" /><input name=\"date_from\" type=\"hidden\" value=\"$_POST[date_from]\" /><input name=\"date_to\" type=\"hidden\" value=\"$_POST[date_to]\" />";
if($_SESSION["grandTotal"] > 0){
	echo "<input name=\"payOrders\" type=\"submit\" value=\"pay order(s)\" class=\"formField\">&nbsp;&nbsp;<input name=\"resetPmts\" type=\"reset\" value=\"reset\" class=\"formField\"></div></td></tr>";
}//END IF grandTotal
echo "</form></table>";
?>
