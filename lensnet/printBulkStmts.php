<?php
session_start();
//var_dump($_SESSION);
if ($_SESSION["sessionUser_Id"]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
//include("labadmin/admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../includes/getlang.php"; 

$queryLab = "SELECT main_lab from accounts WHERE user_id =  '" .$_SESSION["sessionUser_Id"] . "'";
$rptLab=mysql_query($queryLab)	or die  ($lbl_error1_txt . mysql_error());
$DataLab=mysql_fetch_assoc($rptLab);
$lab_pkey=$DataLab[main_lab];

$queryLogo = "SELECT logo_file from labs WHERE primary_key = $DataLab[main_lab] ";
$rptLogo=mysql_query($queryLogo)	or die  ($lbl_error1_txt . mysql_error());
$DataLogo=mysql_fetch_assoc($rptLogo);
$logo_file=$DataLogo[logo_file];

$lab_name=$_SESSION["labAdminData"]["lab_name"];

mysql_query("SET CHARACTER SET UTF8");
if($_POST["stmt_search"]=="prepare statements"){
	$rptQuery="SELECT buying_groups.bg_name, buying_groups.contact_first, buying_groups.contact_last, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, accounts.title, accounts.first_name, accounts.email, accounts.phone, accounts.account_rebate, accounts.last_name, accounts.bill_address1, accounts.bill_address2, accounts.bill_city, accounts.bill_state, accounts.bill_zip, accounts.bill_country, accounts.product_line, orders.order_num as order_num, orders.po_num, orders.order_patient_first, orders.order_patient_last, orders.patient_ref_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status, payments.pmt_amount, payments.prev_pmt_amt1, payments.prev_pmt_amt2, payments.pmt_marker, payments.pmt_date, payments.check_num as check_no, order_paid_in_full, payments.pmt_type from orders

LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 

WHERE orders.lab='$lab_pkey' AND orders.order_num != '0'";


	$date_from=date("Y-m-d",strtotime($_POST["date_from"]));
	$date_to=date("Y-m-d",strtotime($_POST["date_to"]));
	
	
	if ($mylang == 'lang_french'){
		$dateInfo = " pour l'intervalle de date: " . $_POST["date_from"] . " - " . $_POST["date_to"];
		}else {
		$dateInfo = " for date range: " . $_POST["date_from"] . " - " . $_POST["date_to"];
		}
	
	
	
	$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";

	if(($_POST["stmt_type"]=="bulk")&&($_POST["stmt_sort"]=="account"))
		$rptQuery.=" group by order_num desc order by company";
	elseif(($_POST["stmt_type"]=="bulk")&&($_POST["stmt_sort"]=="buying group"))
		$rptQuery.=" group by order_num desc order by bg_name, company";
	elseif($_POST["stmt_type"]=="individual")
		$rptQuery.=" AND accounts.user_id = '". $_SESSION["sessionUser_Id"]. "' group by order_num desc";

	//echo $rptQuery;	
	
	
		if ($mylang == 'lang_french'){
		$heading=$dateInfo . " Etat de compte LensnetClub";
		}else {
		$heading=$dateInfo . " LensnetClub Statement";
		}
	
	
	$heading=ucwords($heading);
}
$_SESSION["RPTQUERY"]=$rptQuery;
$_SESSION["heading"]=$heading;



if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ($lbl_error1_txt . mysql_error());
	$orderCount=mysql_num_rows($rptResult);
	$rptQuery="";
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>LensnetClub &mdash; Account Statement</title>
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
<?php
if ($orderCount != 0){
	$acctTotal=0;
	$shippedTotal=0;
	$pmtTotal=0;
	$acctBalance=0;
	$memoCreditTotal=0;
	$memoCreditTotalApplied=0;
	$runningBalance=0;
	$current_header="";
	while ($listItem=mysql_fetch_assoc($rptResult)){
		if($_POST["stmt_sort"]=="account")
			$new_header=$listItem["company"];
		else
			$new_header=$listItem["bg_name"];
		if($current_header != $new_header){//we've encountered the next acct
			if($current_header!=""){//if this isn't the first acct print the previous acct totals

				$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$acct_user_id' AND mcred_date >='$date_from' AND mcred_date <= '$date_to'";//get memo credits for this date range
				$memo_result=mysql_query($memo_query)					or die  ('I cannot select memo credits because: ' . mysql_error());
				$memo_count=mysql_num_rows($memo_result);
				if($memo_count != 0){
					while($memo_credit_acct=mysql_fetch_array($memo_result)){
						$new_result=mysql_query("SELECT DATE_FORMAT('$memo_credit_acct[mcred_date]','%m-%d-%Y')");
						$mcred_date=mysql_result($new_result,0,0);
						$memo_credit_amt=money_format('%.2n',$memo_credit_acct["mcred_abs_amount"]);
						if($memo_credit_acct["date_mc_applied"]==0){//if not already applied to this statement previously
							if($memo_credit_acct["mcred_cred_type"]=="credit"){
								$acctTotal=bcsub($acctTotal, $memo_credit_amt, 2);//subtract memo credit
								$memoCreditTotal=bcsub($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
								$runningBalance = bcsub($runningBalance, $memo_credit_amt, 2);//subtract memo credit from the running balance
								if($runningBalance < 0)
									$sign = "-$";
								else
									$sign = "$";
								$formatBalance=$sign . money_format('%.2n',abs($runningBalance));
								echo "<tr><td class=\"formCellNosides\">$memo_credit_acct[mcred_order_num]</td><td class=\"formCellNosides\">$mcred_date</td><td colspan=\"6\" class=\"formCellNosides\">Memo Credit $memo_credit_acct[mcred_memo_num]</td><td class=\"formCellNosides\" nowrap=\"nowrap\"><div align=\"right\">- \$$memo_credit_amt</div></td><td class=\"formCellNosides\"><div align=\"right\">$formatBalance</div></td></tr>";
							}else{
								$acctTotal=bcadd($acctTotal, $memo_credit_amt, 2);//add memo debit
								$memoCreditTotal=bcadd($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
								$runningBalance = bcadd($runningBalance, $memo_credit_amt, 2);//add memo credit to the running balance
								if($runningBalance < 0)
									$sign = "-$";
								else
									$sign = "$";
								$formatBalance=$sign . money_format('%.2n',abs($runningBalance));
								echo "<tr><td class=\"formCellNosides\">$memo_credit_acct[mcred_order_num]</td><td class=\"formCellNosides\">$mcred_date</td><td colspan=\"6\" class=\"formCellNosides\">Memo Debit $memo_credit_acct[mcred_memo_num]</td><td class=\"formCellNosides\" nowrap=\"nowrap\"><div align=\"right\">\$$memo_credit_amt</div></td><td class=\"formCellNosides\"><div align=\"right\">$formatBalance</div></td></tr>";
							}//END IF memo_credit_acct
						}//END IF date_mc_applied
						
						else{//IF APPLIED
							if($memo_credit_acct["mcred_cred_type"]=="credit"){
								$memoCreditTotalApplied=bcsub($memoCreditTotalApplied, $memo_credit_amt, 2);//subtract memo credit
							}else{
								$memoCreditTotalApplied=bcadd($memoCreditTotalApplied, $memo_credit_amt, 2);//subtract memo credit
							}//END IF memo_credit_acct
						}//END IF NOT APPLIED
						
					}//END WHILE
				}//END IF memo_count
				
				$query="SELECT * from statement_credits WHERE acct_user_id='$acct_user_id' AND stmt_month='$_POST[stmt_month]' AND stmt_year='$_POST[stmt_year]'";//get end of month credit for this acct
				$result=mysql_query($query)
					or die  ('I cannot select credits because: ' . mysql_error());
				$credit_count=mysql_num_rows($result);
				if($credit_count != 0){
					while($credit_acct=mysql_fetch_array($result)){
						if($credit_acct[date_sc_applied]==0){//if not already applied to this statement previously
							$credit_amt=money_format('%.2n',$credit_acct["amount"]);
							echo "<tr><td colspan=\"9\" class=\"formCellNosides\">".strtoupper($credit_acct[credit_option])." STATEMENT CREDIT</td><td class=\"formCellNosides\"><div align=\"right\">- \$$credit_amt</div></td></tr>";//print the previous acct's totals
							$acctTotal=bcsub($acctTotal, $credit_amt, 2);//subtract end of month credit
							$runningBalance=bcsub($runningBalance, $credit_amt, 2);//subtract end of month credit
						}//END IF date_sc_applied
					}//END WHILE
				}//END IF credit_count
				if($runningBalance < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatAcctTotal=$sign . money_format('%.2n',abs($runningBalance));
				if($memoCreditTotal < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatMemoCreditTotal=$sign . money_format('%.2n',abs($memoCreditTotal));		
				if($memoCreditTotalApplied < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatMemoCreditTotalApplied=$sign . money_format('%.2n',abs($memoCreditTotalApplied));
				$shippedTotal=money_format('%.2n',$shippedTotal);
				$pmtTotal=money_format('%.2n',$pmtTotal);
				echo "<tr><td colspan=\"5\" class=\"formCellNosides\">TOTAL SHIPPED FOR PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">\$$shippedTotal</div></td><td class=\"formCellNosides\" colspan=\"3\"><div align=\"right\">&nbsp;</div></td></tr>";//print the previous acct's totals
				echo "<tr><td colspan=\"8\" class=\"formCellNosides\">TOTAL PAYMENTS FOR PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">\$$pmtTotal</div></td><td class=\"formCellNosides\"><div align=\"right\">&nbsp;</div></td></tr>";//print the previous acct's totals
				if($memoCreditTotal !=0)
					echo "<tr><td colspan=\"9\" class=\"formCellNosides\">TOTAL MEMO CREDITS FOR PERIOD (open)</td><td class=\"formCellNosides\"><div align=\"right\">$formatMemoCreditTotal</div></td></tr>";//print the previous acct's totals
				if($memoCreditTotalApplied !=0)
					echo "<tr><td colspan=\"9\" class=\"formCellNosides\">TOTAL MEMO CREDITS FOR PERIOD (applied)</td><td class=\"formCellNosides\"><div align=\"right\">$formatMemoCreditTotalApplied</div></td></tr>";//print the previous acct's totals
				echo "<tr><td colspan=\"6\" class=\"formCellNosides\">BALANCE AT END OF PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">&nbsp;</div></td><td class=\"formCellNosides\" colspan=\"3\"><div align=\"right\">$formatAcctTotal</div></td></tr>";//print the previous acct's totals
				echo "</table>";
				$acctTotal=0;//zero out the counter
				$shippedTotal=0;
				$pmtTotal=0;
				$memoCreditTotal=0;
				$memoCreditTotalApplied=0;
				echo "<div style=\"page-break-after:always\"></div>";
			}//END IF A NEW ACCT AFTER THE 1ST
			$current_header=$new_header;//make the new acct the current acct
			$acct_user_id=$_SESSION["sessionUser_Id"];//set the new account user id
			$acctBalance = calc_prev_acct_balance_with_memo($acct_user_id);//get new accounts previous balance
						
			$runningBalance = $acctBalance;
			//if($_POST["stmt_sort"]=="account")
				include("displayBulkStmtAcct.php");//print the acct header info
			//else
				//include("displayBulkStmtBG.php");//print the BG header info
		}//END IF ANY NEW ACCT
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
			
//			$orderTotal=money_format('%.2n',$listItem["order_total"]);
//			$orderTotal = $listItem["order_shipping_cost"] + $orderTotal;
//			$orderTotal=money_format('%.2n',$orderTotal);
			
			$orderTotal=$listItem["order_total"];
			$orderTotal = $listItem["order_shipping_cost"] + $orderTotal;
			$runningBalance = bcadd($runningBalance, $orderTotal, 2);//add this order total to the running balance
			$pmt_amount = $listItem["pmt_amount"] + $listItem["prev_pmt_amt1"] + $listItem["prev_pmt_amt2"];
			$orderFinalTotal = $orderTotal - $pmt_amount;
			$orderTotal=money_format('%.2n',$orderTotal);
			$pmt_amount=money_format('%.2n',$pmt_amount);
//			if($listItem["pmt_amount"]==0){
			if(($listItem["pmt_amount"] < $listItem["order_total"])&&($listItem["order_paid_in_full"]!="y")){
				$pmt_status="Open";
//				$pmt_amount="";
				$acctTotal=bcadd($acctTotal, $orderFinalTotal, 2);
				$runningBalance = bcsub($runningBalance, $pmt_amount, 2);//subtract the pmt from the running balance
			}
			elseif($listItem["pmt_amount"] > $listItem["order_total"]){
				$pmt_status="Paid";
				$runningBalance = bcsub($runningBalance, $pmt_amount, 2);//subtract the pmt from the running balance
			}
			elseif($listItem["order_paid_in_full"]=="y"){
				$pmt_status="Paid";
				$runningBalance = bcsub($runningBalance, $orderTotal, 2);//subtract the order total from the running balance
			}
			$shippedTotal=bcadd($shippedTotal, $orderTotal, 2);
			$pmtTotal=bcadd($pmtTotal, $pmt_amount, 2);
			$acct_user_id=$listItem["user_id"];
			if($pmt_amount > 0)
				$pmt_type = $listItem["pmt_type"]." ".$listItem["check_no"];
			else
				$pmt_type = "";
			include("displayBulkStmtOrders.php");//print the order row
	}//END WHILE
	$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$acct_user_id' AND mcred_date >='$date_from' AND mcred_date <= '$date_to'";//get memo credits for this date range
	$memo_result=mysql_query($memo_query)
		or die  ('I cannot select memo credits because: ' . mysql_error());
	$memo_count=mysql_num_rows($memo_result);
	if($memo_count != 0){
		while($memo_credit_acct=mysql_fetch_array($memo_result)){
			$new_result=mysql_query("SELECT DATE_FORMAT('$memo_credit_acct[mcred_date]','%m-%d-%Y')");
			$mcred_date=mysql_result($new_result,0,0);
			$memo_credit_amt=money_format('%.2n',$memo_credit_acct["mcred_abs_amount"]);
				if($memo_credit_acct["date_mc_applied"]==0){//if not already applied to this statement previously
					if($memo_credit_acct["mcred_cred_type"]=="credit"){
						$acctTotal=bcsub($acctTotal, $memo_credit_amt, 2);//subtract memo credit
						$memoCreditTotal=bcsub($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
						$runningBalance = bcsub($runningBalance, $memo_credit_amt, 2);//subtract memo credit from the running balance
						if($runningBalance < 0)
							$sign = "-$";
						else
							$sign = "$";
						$formatBalance=$sign . money_format('%.2n',abs($runningBalance));
						echo "<tr><td class=\"formCellNosides\">$memo_credit_acct[mcred_order_num]</td><td class=\"formCellNosides\">$mcred_date</td><td colspan=\"6\" class=\"formCellNosides\">Memo Credit $memo_credit_acct[mcred_memo_num]</td><td class=\"formCellNosides\" nowrap=\"nowrap\"><div align=\"right\">- \$$memo_credit_amt</div></td><td class=\"formCellNosides\"><div align=\"right\">$formatBalance</div></td></tr>";
					}else{
						$acctTotal=bcadd($acctTotal, $memo_credit_amt, 2);//add memo debit
						$memoCreditTotal=bcadd($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
						$runningBalance = bcadd($runningBalance, $memo_credit_amt, 2);//add memo credit to the running balance
						if($runningBalance < 0)
							$sign = "-$";
						else
							$sign = "$";
						$formatBalance=$sign . money_format('%.2n',abs($runningBalance));
						echo "<tr><td class=\"formCellNosides\">$memo_credit_acct[mcred_order_num]</td><td class=\"formCellNosides\">$mcred_date</td><td colspan=\"6\" class=\"formCellNosides\">Memo Debit $memo_credit_acct[mcred_memo_num]</td><td class=\"formCellNosides\" nowrap=\"nowrap\"><div align=\"right\">\$$memo_credit_amt</div></td><td class=\"formCellNosides\"><div align=\"right\">$formatBalance</div></td></tr>";
					}//END IF memo_credit_acct
				}//END IF date_mc_applied
										
			else{//IF APPLIED
					if($memo_credit_acct["mcred_cred_type"]=="credit"){
					$memoCreditTotalApplied=bcsub($memoCreditTotalApplied, $memo_credit_amt, 2);//subtract memo credit
				}else{
					$memoCreditTotalApplied=bcadd($memoCreditTotalApplied, $memo_credit_amt, 2);//subtract memo credit
					}//END IF memo_credit_acct
				}//END IF NOT APPLIED
						
		}//END WHILE
	}//END IF MEMO COUNT
				
	$query="SELECT * from statement_credits WHERE acct_user_id='$acct_user_id' AND stmt_month='$_POST[stmt_month]' AND stmt_year='$_POST[stmt_year]'";//get end of month credit for this acct
	$result=mysql_query($query)
		or die  ('I cannot select credits because: ' . mysql_error());
	$credit_count=mysql_num_rows($result);
	if($credit_count != 0){
		while($credit_acct=mysql_fetch_array($result)){
			if($credit_acct[date_sc_applied]==0){//if not already applied to this statement previously
				$credit_amt=money_format('%.2n',$credit_acct["amount"]);
				echo "<tr><td colspan=\"9\" class=\"formCellNosides\">".strtoupper($credit_acct[credit_option])." STATEMENT CREDIT</td><td class=\"formCellNosides\"><div align=\"right\">- \$$credit_amt</div></td></tr>";//print the previous acct's totals
				$acctTotal=bcsub($acctTotal, $credit_amt, 2);//subtract end of month credit
				$runningBalance=bcsub($runningBalance, $credit_amt, 2);//subtract end of month credit
			}//END IF date_sc_applied
		}//END WHILE
	}
				if($runningBalance < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatAcctTotal=$sign . money_format('%.2n',abs($runningBalance));
				if($memoCreditTotal < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatMemoCreditTotal=$sign . money_format('%.2n',abs($memoCreditTotal));
				if($memoCreditTotalApplied < 0)
					$sign = "-$";
				else
					$sign = "$";
				$formatMemoCreditTotalApplied=$sign . money_format('%.2n',abs($memoCreditTotalApplied));
				$shippedTotal=money_format('%.2n',$shippedTotal);
				$pmtTotal=money_format('%.2n',$pmtTotal);
				echo "<tr><td colspan=\"5\" class=\"formCellNosides\">TOTAL SHIPPED FOR PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">\$$shippedTotal</div></td><td class=\"formCellNosides\" colspan=\"3\"><div align=\"right\">&nbsp;</div></td></tr>";//print this acct's totals
				echo "<tr><td colspan=\"8\" class=\"formCellNosides\">TOTAL PAYMENTS FOR PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">\$$pmtTotal</div></td><td class=\"formCellNosides\"><div align=\"right\">&nbsp;</div></td></tr>";//print this acct's totals
				if($memoCreditTotal !=0)
					echo "<tr><td colspan=\"9\" class=\"formCellNosides\">TOTAL MEMO CREDITS FOR PERIOD (open)</td><td class=\"formCellNosides\"><div align=\"right\">$formatMemoCreditTotal</div></td></tr>";//print the previous acct's totals
				if($memoCreditTotalApplied !=0)
					echo "<tr><td colspan=\"9\" class=\"formCellNosides\">TOTAL MEMO CREDITS FOR PERIOD (applied)</td><td class=\"formCellNosides\"><div align=\"right\">$formatMemoCreditTotalApplied</div></td></tr>";//print the previous acct's totals
				echo "<tr><td colspan=\"6\" class=\"formCellNosides\">BALANCE AT END OF PERIOD</td><td class=\"formCellNosides\"><div align=\"right\">&nbsp;</div></td><td class=\"formCellNosides\" colspan=\"3\"><div align=\"right\">$formatAcctTotal</div></td></tr>";//print the previous acct's totals
	echo "</table>";

}else{
	echo "<div class=\"formField\">".$adm_noorders_txt."</div>";
}//END orderCount CONDITIONAL
?>

<?php  
function calc_prev_balance()
{
	//$lab_pkey=$_SESSION["lab_pkey"];
	$queryLab = "SELECT main_lab from accounts WHERE user_id =  '" .$_SESSION["sessionUser_Id"] . "'";
	$rptLab=mysql_query($queryLab)	or die  ($lbl_error1_txt . mysql_error());
	$DataLab=mysql_fetch_assoc($rptLab);
	$lab_pkey=$DataLab[main_lab];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));

	$prevBalQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status from orders

					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id= '" . $_SESSION["sessionUser_Id"] . "' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
	
	//echo $prevBalQuery;
	$prevBalResult=mysql_query($prevBalQuery);
	$ordercount=mysql_num_rows($prevBalResult);
	if ($ordercount != 0){
		$prev_bal=0;
		$ordersBalData=array();
		$i=0;
		while ($listItem=mysql_fetch_array($prevBalResult)){
			$pmtQuery="SELECT * from payments WHERE order_num = '$listItem[order_num]'";
			$pmtResult=mysql_query($pmtQuery);
			$pmtcount=mysql_num_rows($pmtResult);
			if($pmtcount == 0){//no payments
				$i++;
				$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
				$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
				$ordersBalData[$i]["balance"]=$order_total;
				$ordersBalData[$i]["order_total"]=$order_total;
				$ordersBalData[$i]["company"]=$listItem["company"];
				$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
			}else{//at least one payment or more
				$pmtItem=mysql_fetch_assoc($pmtResult);
				if($pmtItem["order_paid_in_full"] !="y"){//there's has been a payment attempted before since there's an entry in the pmts table
					if($pmtItem["order_balance"] > 0){//there's only a partial pmt
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$pmtItem["order_balance"];
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $pmtItem["order_balance"], 2);//add open invoice amt to prev bal
					}
					elseif($pmtItem["order_balance"] == 0){//customer tried to pay before, but was unsuccessful
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$order_total;
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
					}
				}
			}//END IF/ELSE
		}//END WHILE
		$ordersBalData[$i]["prev_bal"]=$prev_bal;
		return ($ordersBalData);
	}else{
		return false;
	}//END IF
}





function calc_prev_balance_with_memo()
{
	//$lab_pkey=$_SESSION["lab_pkey"];
		$queryLab = "SELECT main_lab from accounts WHERE user_id =  '" .$_SESSION["sessionUser_Id"] . "'";
	$rptLab=mysql_query($queryLab)	or die  ($lbl_error1_txt . mysql_error());
	$DataLab=mysql_fetch_assoc($rptLab);
	$lab_pkey=$DataLab[main_lab];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));
	
		$memoCreditTotal=0;
	
$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id= '". $_SESSION["sessionUser_Id"] . "' AND date_mc_applied='0000-00-00' AND mcred_date <'$date_from' ";//get memo credits for this date range
				$memo_result=mysql_query($memo_query)
					or die  ('I cannot select memo credits because: ' . mysql_error());
				$memo_count=mysql_num_rows($memo_result);
				if($memo_count != 0){
						while($memo_credit_acct=mysql_fetch_array($memo_result)){
						
						$memo_credit_amt=money_format('%.2n',$memo_credit_acct["mcred_abs_amount"]);
						if($memo_credit_acct["date_mc_applied"]==0){//if not already applied to this statement previously
						
							if($memo_credit_acct["mcred_cred_type"]=="credit"){
								$memoCreditTotal=bcsub($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
							
							}else{
								$memoCreditTotal=bcadd($memoCreditTotal, $memo_credit_amt, 2);//addt memo credit
						
							}//END IF memo_credit_acct
						}//END IF date_mc_applied
					}//END WHILE
					
				}//END IF MEMO COUNT

	$prevBalQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status from orders

					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id= '" . $_SESSION["sessionUser_Id"] . "' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
	
	$prevBalResult=mysql_query($prevBalQuery);
	$ordercount=mysql_num_rows($prevBalResult);
	if ($ordercount != 0){
		$prev_bal=0;
		$ordersBalData=array();
		$i=0;
		while ($listItem=mysql_fetch_array($prevBalResult)){
			$pmtQuery="SELECT * from payments WHERE order_num = '$listItem[order_num]'";
			$pmtResult=mysql_query($pmtQuery);
			$pmtcount=mysql_num_rows($pmtResult);
			if($pmtcount == 0){//no payments
				$i++;
				$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
				$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
				$ordersBalData[$i]["balance"]=$order_total;
				$ordersBalData[$i]["order_total"]=$order_total;
				$ordersBalData[$i]["company"]=$listItem["company"];
				$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
			}else{//at least one payment or more
				$pmtItem=mysql_fetch_assoc($pmtResult);
				if($pmtItem["order_paid_in_full"] !="y"){//there's has been a payment attempted before since there's an entry in the pmts table
					if($pmtItem["order_balance"] > 0){//there's only a partial pmt
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$pmtItem["order_balance"];
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $pmtItem["order_balance"], 2);//add open invoice amt to prev bal
					}
					elseif($pmtItem["order_balance"] == 0){//customer tried to pay before, but was unsuccessful
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$order_total;
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
					}
				}
			}//END IF/ELSE
		}//END WHILE
		
		$prev_bal=bcadd($prev_bal, $memoCreditTotal, 2);
	
		$ordersBalData[$i]["prev_bal"]=$prev_bal;
	
		return ($ordersBalData);
	}else{
		return false;
	}//END IF
}

function calc_prev_acct_balance($acct_user_id)
{
	//$lab_pkey=$_SESSION["lab_pkey"];
	$queryLab = "SELECT main_lab from accounts WHERE user_id =  '" .$_SESSION["sessionUser_Id"] . "'";
	$rptLab=mysql_query($queryLab)	or die  ($lbl_error1_txt . mysql_error());
	$DataLab=mysql_fetch_assoc($rptLab);
	$lab_pkey=$DataLab[main_lab];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));

	$prevBalQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status from orders

					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id='$acct_user_id' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
	
	$prevBalResult=mysql_query($prevBalQuery);
	$ordercount=mysql_num_rows($prevBalResult);
	$prev_bal=0;
	if ($ordercount != 0){
		$ordersBalData=array();
		$i=0;
		while ($listItem=mysql_fetch_array($prevBalResult)){
			$pmtQuery="SELECT * from payments WHERE order_num = '$listItem[order_num]'";
			$pmtResult=mysql_query($pmtQuery);
			$pmtcount=mysql_num_rows($pmtResult);
			if($pmtcount == 0){//no payments
				$i++;
				$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
				$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
				$ordersBalData[$i]["balance"]=$order_total;
				$ordersBalData[$i]["order_total"]=$order_total;
				$ordersBalData[$i]["company"]=$listItem["company"];
				$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
			}else{//at least one payment or more
				$pmtItem=mysql_fetch_assoc($pmtResult);
				if($pmtItem["order_paid_in_full"] !="y"){//there's has been a payment attempted before since there's an entry in the pmts table
					if($pmtItem["order_balance"] > 0){//there's only a partial pmt
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$pmtItem["order_balance"];
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $pmtItem["order_balance"], 2);//add open invoice amt to prev bal
					}
					elseif($pmtItem["order_balance"] == 0){//customer tried to pay before, but was unsuccessful
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$order_total;
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
					}
				}
			}//END IF/ELSE
		}//END WHILE
//		$ordersBalData[$i]["prev_bal"]=$prev_bal;
//		return ($ordersBalData);
	}//END IF
	if($prev_bal == 0)
		$prev_bal = "0.00";
	return ($prev_bal);
}

function calc_prev_acct_balance_with_memo($acct_user_id)
{
	//$lab_pkey=$_SESSION["lab_pkey"];
	$queryLab = "SELECT main_lab from accounts WHERE user_id =  '" .$_SESSION["sessionUser_Id"] . "'";
	$rptLab=mysql_query($queryLab)	or die  ($lbl_error1_txt . mysql_error());
	$DataLab=mysql_fetch_assoc($rptLab);
	$lab_pkey=$DataLab[main_lab];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));
	
	$memoCreditTotal=0;
	
$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$acct_user_id' AND date_mc_applied='0000-00-00' AND mcred_date <'$date_from' ";//get memo credits for this date range

			
				$memo_result=mysql_query($memo_query)
					or die  ('I cannot select memo credits because: ' . mysql_error());
				$memo_count=mysql_num_rows($memo_result);
				if($memo_count != 0){
						while($memo_credit_acct=mysql_fetch_array($memo_result)){
						
						$memo_credit_amt=money_format('%.2n',$memo_credit_acct["mcred_abs_amount"]);
						if($memo_credit_acct["date_mc_applied"]==0){//if not already applied to this statement previously
						
							if($memo_credit_acct["mcred_cred_type"]=="credit"){
								$memoCreditTotal=bcsub($memoCreditTotal, $memo_credit_amt, 2);//subtract memo credit
							
							}else{
								$memoCreditTotal=bcadd($memoCreditTotal, $memo_credit_amt, 2);//addt memo credit
						
							}//END IF memo_credit_acct
						}//END IF date_mc_applied
					}//END WHILE
					
				}//END IF MEMO COUNT

	$prevBalQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status from orders

					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id='$acct_user_id' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
	//echo '<br><br>'. $prevBalQuery. '<br><br>';
	$prevBalResult=mysql_query($prevBalQuery);
	$ordercount=mysql_num_rows($prevBalResult);
	$prev_bal=0;
	if ($ordercount != 0){
		$ordersBalData=array();
		$i=0;
		while ($listItem=mysql_fetch_array($prevBalResult)){
			$pmtQuery="SELECT * from payments WHERE order_num = '$listItem[order_num]'";
			$pmtResult=mysql_query($pmtQuery);
			$pmtcount=mysql_num_rows($pmtResult);
			if($pmtcount == 0){//no payments
				$i++;
				$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
				$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
				$ordersBalData[$i]["balance"]=$order_total;
				$ordersBalData[$i]["order_total"]=$order_total;
				$ordersBalData[$i]["company"]=$listItem["company"];
				$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
			}else{//at least one payment or more
				$pmtItem=mysql_fetch_assoc($pmtResult);
				if($pmtItem["order_paid_in_full"] !="y"){//there's has been a payment attempted before since there's an entry in the pmts table
					if($pmtItem["order_balance"] > 0){//there's only a partial pmt
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$pmtItem["order_balance"];
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $pmtItem["order_balance"], 2);//add open invoice amt to prev bal
					}
					elseif($pmtItem["order_balance"] == 0){//customer tried to pay before, but was unsuccessful
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$order_total;
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
					}
				}
			}//END IF/ELSE
		}//END WHILE
//		$ordersBalData[$i]["prev_bal"]=$prev_bal;
//		return ($ordersBalData);
	}//END IF
	if($prev_bal == 0)
		$prev_bal = "0.00";
	
	$prev_bal=bcadd($prev_bal, $memoCreditTotal, 2);

	return ($prev_bal);
}



?>
</body>
</html>