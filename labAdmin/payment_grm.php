<?php /*?><?php 
include_once("../Connections/sec_connect.inc.php");
$User_ID_GRM = 'grm64362';
 
$QueryOrders="SELECT * FROM orders
WHERE user_id = '$User_ID_GRM'
AND order_date_shipped < '2012-07-31' 
AND order_date_shipped <> '0000-00-00'
AND order_num not in (SELECT order_num from payments where user_id='$User_ID_GRM') Limit 0,300";
echo '<br>'. $QueryOrders . '<br>';
$ResultOrders=mysql_query($QueryOrders)	or die  ('I cannot select items because: ' . mysql_error());
$orderCount=mysql_num_rows($ResultOrders);	

if($orderCount != 0){	
	while($OrderData=mysql_fetch_assoc($ResultOrders)){
		
$query_rs = "INSERT INTO `direct54_dirlens`.`payments` (`primary_key`, `pmt_marker`, `user_id`, `order_num`, `pmt_date`, `pmt_type`, `check_num`, `order_balance`, `pmt_amount`, `prev_pmt_amt1`, `prev_pmt_amt2`, `order_paid_in_full`, `cctype`, `cclast4`, `transAuthCode`, `transResultCode`, `transRespReasonCode`, `transApprovalCode`, `transTransID`) 
VALUES (NULL, '', '$User_ID_GRM', '$OrderData[order_num]', '2012-09-05', 'check', '123456', '0', '$OrderData[order_total]', '', '', 'y', '', '', '', '', '', '', '');
"; 
echo '<br>'. $query_rs . '<br>';
$rs = mysql_query($query_rs) or die(mysql_error());

    }//End while  
	
}//Enf IF $orderCount != 0)
            ?><?php */?>