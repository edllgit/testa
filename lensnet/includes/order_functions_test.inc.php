<?php
	function calc_current_balance($user_id) {
		$result=mysql_query("SELECT curdate()");/* get today's date */
			$today=mysql_result($result,0,0);
		$acctTotal=0;			  
		$acctDiscTotal=0;
		$i=1;
		$totalCharge="0.00";//total current amount owed for unpaid shipped orders
		$grandTotal="0.00";//total amount owed including statement & memo credits to check against credit limit
		$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, orders.order_num as order_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date, payments.order_paid_in_full, payments.order_balance from orders
	
					LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
					
					LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
					
					WHERE orders.user_id='$user_id' AND orders.order_total > '0'";
		$rptResult=mysql_query($rptQuery)
			or die  ('I cannot select orders because: ' . mysql_error().$rptQuery);
		$rptCount = mysql_num_rows($rptResult);
		if($rptCount) {
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
				//early pmt discount
				if($discountdate_15 >= $today){
					$discountamt=bcmul('.02', $listItem[order_total], 2);
				}
				//early pmt discount
				elseif($discountdate_10 >= $today){
					$discountamt=bcmul('.01', $listItem[order_total], 2);
				}else{
					$discountamt=0;
				}
				$discounted_total_cost=bcsub($listItem[order_total], $discountamt, 2);
				$discounted_total_cost=bcadd($discounted_total_cost, $listItem[order_shipping_cost], 2);//add non-discounted shipping cost
				$orderTotal=bcadd($listItem[order_total], $listItem[order_shipping_cost], 2);//add shipping cost
				if($listItem[pmt_amount]==0){//this order has NOT been paid
					$pmt_status="Open";
					$pmt_amount="";
					$totalCharge=bcadd($totalCharge, $discounted_total_cost, 2);
				}else{
					if($listItem["order_paid_in_full"] !="y"){//there's only a partial pmt
						$order_subtotal=$listItem["order_balance"];//get the order balance
						if($order_subtotal > 0){
							$totalCharge=bcadd($totalCharge, $order_subtotal, 2);
						}//END IF
					}//END IF
				}//END IF ELSE
			}//END WHILE
		}//END IF
		$grandTotal=bcadd($grandTotal, $totalCharge, 2);//add unpaid shipped invoices
		
		//memo credits
		$memoQuery="SELECT * from memo_credits WHERE date_mc_applied = '0000-00-00' AND mcred_acct_user_id = '$user_id'";
		$memoResult=mysql_query($memoQuery)
			or die  ('I cannot select memo credits because: ' . mysql_error().$memoQuery);
		$memoCount=mysql_num_rows($memoResult);
		if($memoCount > 0){
			$memoCredTotal=0;
			while ($memoData=mysql_fetch_array($memoResult)){
				if($memoData[date_mc_applied]==0){//if not already applied to this statement previously
					if($memoData[mcred_cred_type]=="credit"){
						$memoCredTotal=bcsub($memoCredTotal, $memoData[mcred_abs_amount], 2);
					}else{
						$memoCredTotal=bcadd($memoCredTotal, $memoData[mcred_abs_amount], 2);
					}//END IF ELSE
				}//END IF 
			}//END WHILE
			$grandTotal=bcadd($grandTotal, $memoCredTotal, 2);//add memo credits
		}//END IF
		
		//statement credits
		$query="SELECT * from statement_credits WHERE acct_user_id='$user_id' AND date_sc_applied = '0000-00-00'";
		$result=mysql_query($query)
			or die  ('I cannot select statement credits because: ' . mysql_error().$rptQuery);
		$credit_count=mysql_num_rows($result);
		if($credit_count > 0){
			while($credit_acct=mysql_fetch_array($result)){
				$grandTotal=bcsub($grandTotal, $credit_amt, 2);//subtract end of month credit
			}//END WHILE
		}//END IF
		return ($grandTotal);
	}

function getNewMasterOrderID(){
	$query="select * from order_master_id WHERE primary_key='1'";
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
		
	$listItem=mysql_fetch_array($result);
	$master_order_id=$listItem[last_master_order_id]+1;
	
	$query="UPDATE order_master_id SET last_master_order_id='$master_order_id' WHERE primary_key='1'";
	$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());

	return $master_order_id;
}
?>