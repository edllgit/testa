<?php
function calc_prev_balance()
{
	$lab_pkey=$_SESSION["lab_pkey"];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));

	$prevBalQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status from orders

					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id='$_POST[acctName]' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
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
	$lab_pkey=$_SESSION["lab_pkey"];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));
	
		$memoCreditTotal=0;
	
$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$_POST[acctName]' AND date_mc_applied='0000-00-00' AND mcred_date <'$date_from' ";//get memo credits for this date range
				$memo_result=mysql_query($memo_query)		or die  ('I cannot select memo credits because: ' . mysql_error());
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
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id='$_POST[acctName]' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0' group by order_num desc";
	
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
	$lab_pkey=$_SESSION["lab_pkey"];
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
	$lab_pkey=$_SESSION["lab_pkey"];
	$date_from=date("Y-m-d", strtotime($_POST["date_from"]));
	
	$memoCreditTotal=0;
	
$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$acct_user_id' AND date_mc_applied='0000-00-00' AND mcred_date <'$date_from' ";//get memo credits for this date range
				$memo_result=mysql_query($memo_query)		or die  ('I cannot select memo credits because: ' . mysql_error());
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
					
					WHERE orders.lab='$lab_pkey' AND orders.user_id='$acct_user_id' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_total > '0'
		OR
		orders.lab='$lab_pkey' AND orders.user_id='$acct_user_id' AND orders.order_date_shipped < '$date_from' AND orders.order_date_shipped > '0000-00-00' AND orders.order_shipping_cost  > '0'
					 group by order_num desc";
	
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

function make_labadmin_pmt()
{
	require_once("../Connections/sec_connect.inc.php");
	$result=mysql_query("SELECT curdate()");/* get today's date */
		$today=mysql_result($result,0,0);
	$pmt_type="check";
	$_SESSION["CHECK_NO"]=$_POST["check_no"];
		
	if($_POST["cc_no"]){//if this is a credit card payment
//		require_once "../../usaepay.php";
		$pmt_type="credit card";
		$amount=$_SESSION["grandTotal"];
		$cclast4=substr($_POST["cc_no"], -4, 4);
		$query="SELECT * from accounts WHERE user_id = '$_SESSION[user_id]'";//find the account data
		$result=mysql_query($query)
			or die  ('I cannot select items because: ' . mysql_error());
		$acctData=mysql_fetch_array($result);
//		if($acctData["currency"] == "US"){//Paypal
//			include ("../processors/pfpro_settings.inc.php");
//			include "../processors/pfpro_functions.inc.php";
//			$transData=process_card();
//			if((is_array($transData))&&($transData["approved"])){
//				$transData["transAuthCode"]=$transData["AUTHCODE"];
//				$transData["transResultCode"]=$transData["RESULT"];
//				$transData["transRespReasonCode"]="";
//				$transData["transApprovalCode"]="Paypal";
//				$transData["transTransID"]=$transData["PNREF"];
//			}else{
//				$pmtMessage = "There was a problem with the credit card payment. Please try again.";
//				return($pmtMessage);
//				exit();
//			}
//		}else{//Global
			include ("../processors/globalpay_settings.inc.php");
			include "../processors/globalpay_functions.inc.php";
			$transData=showCreditCardSaleResponse();
			if((is_array($transData))&&($transData["approved"])){
				$transData["transAuthCode"]=$transData["AuthCode"];
				$transData["transResultCode"]=$transData["Result"];
				$transData["transRespReasonCode"]="";
				$transData["transApprovalCode"]="Global";
				$transData["transTransID"]=$transData["PNRef"];
			}else{
				$pmtMessage = "There was a problem with the credit card payment. Please try again.";
				return($pmtMessage);
				exit();
			}
//		}
	}		

	if(($pmt_type=="check")||(is_array($transData))&&($transData["approved"])){
		if($_SESSION["order_numbers"]){//current statement orders that are being paid
			$order_numbers=$_SESSION["order_numbers"];
			$order_amts=$_SESSION["order_amts"];
			$order_totals=$_SESSION["order_totals"];
			$orderCount=$_SESSION["orderCount"];
			$orderPaid=$_SESSION["order_paid"];
			for ($i = 1; $i <= $orderCount; $i++){//get order data
				$order_num=$order_numbers[$i];
				$totalCost=$order_amts[$i];
				$order_paid_in_full=$orderPaid[$i];
				$pmtQuery="SELECT * from payments WHERE order_num = '$order_num'";//find this order if it's been paid
				$pmtResult=mysql_query($pmtQuery)
					or die  ('I cannot select payments because: ' . mysql_error());
				$orderTest=mysql_num_rows($pmtResult);
				$order_balance=bcsub($order_totals[$i], $order_amts[$i], 2);//figure the ending balance
				if($order_balance < .01){
					$order_balance = 0;
					$order_paid_in_full="y";
				}
				if($orderTest==0){//if customer never tried to pay this order before
					$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]')";
				}else{ //if customer tried to pay this order before and failed OR only a partial payment was made
					$pmtData=mysql_fetch_assoc($pmtResult);//retrieve previous payment data
					$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$transData[transResultCode]', transAuthCode='$transData[transAuthCode]', transApprovalCode='$transData[transApprovalCode]', transTransID='$transData[transTransID]' WHERE order_num='$order_num'";
				}
				$result=mysql_query($query)
					or die ('Could not add or update current payments because: ' . mysql_error());
			}
		}
		if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous unpaid orders
			reset($_SESSION["ORDERSBALDATA"]);
			$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
			$ordersBalData=$_SESSION["ORDERSBALDATA"];
			for ($i = 1; $i <= $count_prev_orders; $i++){//get order data
				if($ordersBalData[$i]["balance"] > 0){
					$order_num=$ordersBalData[$i]["order_num"];
					$totalCost=$ordersBalData[$i]["balance"];
					$order_paid_in_full="y";
					$pmtQuery="SELECT * from payments WHERE order_num = '$order_num'";//find this order if it's been paid
					$pmtResult=mysql_query($pmtQuery)
						or die  ('I cannot select items because: ' . mysql_error());
					$orderTest=mysql_num_rows($pmtResult);
					$order_balance=0;//previous orders must be paid in full
					if($orderTest==0){//if customer never tried to pay this order before
						$query="insert into payments (user_id, order_num, pmt_date, pmt_type, pmt_amount, order_paid_in_full, check_num, order_balance, cctype, cclast4, transResultCode, transAuthCode, transApprovalCode, transTransID) values ('$_SESSION[user_id]', '$order_num', '$today', '$pmt_type', '$totalCost', '$order_paid_in_full', '$_POST[check_no]', '$order_balance', '$_POST[cc_type]', '$cclast4', '$transData[transResultCode]', '$transData[transAuthCode]', '$transData[transApprovalCode]', '$transData[transTransID]')";
					}else{ //if customer tried to pay this order before and failed
						$pmtData=mysql_fetch_assoc($pmtResult);//retrieve previous payment data
						$query="UPDATE payments SET pmt_marker='', pmt_date='$today', pmt_type='$pmt_type', pmt_amount='$totalCost', prev_pmt_amt1='$pmtData[pmt_amount]', prev_pmt_amt2='$pmtData[prev_pmt_amt1]', order_paid_in_full='$order_paid_in_full', check_num='$_POST[check_no]', order_balance='$order_balance', cctype='$_POST[cc_type]', cclast4='$cclast4', transResultCode='$transData[transResultCode]', transAuthCode='$transData[transAuthCode]', transApprovalCode='$transData[transApprovalCode]', transTransID='$transData[transTransID]' WHERE order_num='$order_num'";
					}
					$result=mysql_query($query)
						or die ('Could not update previous balance payments because: ' . mysql_error());
				}
			}
		}
		if($_SESSION["MEMOCREDLIST"]){//there are memo credits that should be marked as used
			reset($_SESSION["MEMOCREDLIST"]);
			$count_memo_creds=count($_SESSION["MEMOCREDLIST"]);
			$memoCredList=$_SESSION["MEMOCREDLIST"];
			for ($i = 1; $i <= $count_memo_creds; $i++){//update memo cred data
				$query="UPDATE memo_credits SET date_mc_applied='$today' WHERE mcred_primary_key='$memoCredList[$i]'";
				$result=mysql_query($query)
					or die ('Could not update memo credit because: ' . mysql_error());
			}
		}
		
		if($_SESSION["STMTCREDLIST"]){//there are statement credits that should be marked as used
			reset($_SESSION["STMTCREDLIST"]);
			$count_stmt_creds=count($_SESSION["STMTCREDLIST"]);
			$stmtCredList=$_SESSION["STMTCREDLIST"];
			for ($i = 1; $i <= $count_stmt_creds; $i++){//update statement cred data
				$query="UPDATE statement_credits SET date_sc_applied='$today' WHERE primary_key_cr='$stmtCredList[$i]'";
				$result=mysql_query($query)
					or die ('Could not update statement credit because: ' . mysql_error());
			}
		}
	$pmtMessage = "Payment has been successfully submitted.";
	return($pmtMessage);
	}
}

function sendDLEmail(){/* sends the emails */
	$message="LabAdmin has posted an online credit card payment to Direct Lens customer account $_SESSION[COMPANY] in the amount of $" . $_SESSION["grandTotal"] . "\r\n";
	$headers = "From: payments@direct-lens.com\r\n";
	$headers .=	"Content-Type: 	text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("rco.daniel@gmail.com", "Direct-Lens labAdmin credit card payment", "$message", "$headers");
	return true;
}
?>
