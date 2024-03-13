<?php 
require_once("../Connections/sec_connect.inc.php");

echo 'balance dr:  ' . calc_prev_acct_balance_with_memo($user_id);
function calc_prev_acct_balance_with_memo($acct_user_id)
{

$acct_user_id = 'eyelationnet';
$lab_pkey = 47;
$date_from = '2013-08-31';
$date_to   = '2013-09-04';

	//$lab_pkey=$_SESSION["lab_pkey"];
	//$date_from=date("Y-m-d", strtotime($_POST["date_from"]));
	
$memoCreditTotal=0;
$memo_query="SELECT * from memo_credits WHERE mcred_acct_user_id='$acct_user_id' AND date_mc_applied='0000-00-00' AND mcred_date <'$date_from' ";//get memo credits for this date range
echo '<br> Memo query:'. $memo_query .  '<br><br>';
			
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
					
					echo '<br><br>$prevBalQuery: ' .$prevBalQuery . '<br><br>';

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
			echo "<br> Order: ". $listItem["order_num"]. ' : balance = balance + '. $order_total. '<br><br>';
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
					   echo '2balance = balance + '. $pmtItem["order_balance"]. '<br><br>';
					}
					elseif($pmtItem["order_balance"] == 0){//customer tried to pay before, but was unsuccessful
						$i++;
						$order_total=bcadd($listItem["order_total"], $listItem["order_shipping_cost"], 2);//add shipping cost
						$ordersBalData[$i]["order_num"]=$listItem["order_num"];//add open invoice and balance to ordersBalData array
						$ordersBalData[$i]["balance"]=$order_total;
						$ordersBalData[$i]["order_total"]=$order_total;
						$ordersBalData[$i]["company"]=$listItem["company"];
						$prev_bal=bcadd($prev_bal, $order_total, 2);//add open invoice amt to prev bal
						echo '3balance = balance + ' . $listItem["order_num"] . ' :' . $order_total. '<br><br>';
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
echo 'balance: + memocredittotal: ' . $memoCreditTotal . '<br><br>';
	return ($prev_bal);
}

?>