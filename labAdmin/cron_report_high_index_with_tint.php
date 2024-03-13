<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$tomorrow = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $tomorrow);	
	
$rptQuery = "SELECT orders . * , A.account_num AS account_num
FROM orders, accounts A, extra_product_orders epo
WHERE orders.user_id = A.user_id AND epo.order_num = orders.order_num
AND orders.order_status NOT IN ('cancelled', 'filled', 'basket')
AND orders.order_product_index IN ('1.67', '1.60', '1.74', '1.59', '1.70', '1.80', '1.90')
AND order_date_processed = '$datecomplete'
AND epo.category = 'Tint' ORDER BY orders.order_date_processed";	
echo $rptQuery;
$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error());

	$ordersnum=mysql_num_rows($rptResult);
	
	if ($ordersnum!=0){
		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";

		$message.="<body><table width=\"950\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="
		<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"75\">Order date</th>
	<th width=\"60\">Order Number</th>
	<th width=\"60\">Main Lab</th>
	<th width=\"100\">Patient</th>
	<th width=\"120\">Ref Patient</th>
	<th width=\"120\">Tray Num</th>
	<th width=\"430\">Produit</th>
	<th width=\"110\">Status EN</th>
	</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

	

			
switch($listItem['order_status']) {
	case 'processing':			$lestatus=  "Confirmed";			break;
	case 'interlab':			$lestatus=  "Interlab P";			break;
	case 'interlab qc':			$lestatus=  "Interlab QC";			break;
	case 'order imported':		$lestatus=  "Order Imported";		break;
	case 'job started':			$lestatus=  "Surfacing";			break;
	case 'in coating':			$lestatus=  "In Coating";			break;
	case 'profilo':				$lestatus = "Profilo";			  	break;
	case 'in mounting':			$lestatus=  "In Mounting";			break;
	case 'central lab marking':	$lestatus=  "Central Lab Marking";	break;
	case 'in edging':			$lestatus=  "In Edging";			break;
	case 'order completed':		$lestatus=  "Order Completed";		break;
	case 'delay issue 0':		$lestatus=  "Delay Issue 0";		break;
	case 'delay issue 1':		$lestatus=  "Delay Issue 1";		break;
	case 'delay issue 2':		$lestatus=  "Delay Issue 2";		break;
	case 'delay issue 3':		$lestatus=  "Delay Issue 3";		break;
	case 'delay issue 4':		$lestatus=  "Delay Issue 4";		break;
	case 'delay issue 5':		$lestatus=  "Delay Issue 5";		break;
	case 'delay issue 6':		$lestatus=  "Delay Issue 6";		break;
	case 'waiting for frame':	$lestatus=  "Waiting for Frame";	break;
	case 'waiting for frame swiss':	$lestatus=  "Waiting for Frame Swiss";	break;
	case 'in transit':			$lestatus=  "In Transit";			break;
	case 'out for clip':		$lestatus = "Out for clip";			break;
	case 'filled':				$lestatus=  "Shipped";				break;
	case 'cancelled':			$lestatus=  "Cancelled";			break;
	case 're-do':				$lestatus=  "Redo";					break;
	case 'waiting for shape':	$lestatus=  "Waiting for Shape";	break;
	case 'information in hand':	$lestatus=  "Info in hand";		    break;
	case 'waiting for lens':	$lestatus=  "Waiting for lens";		break;
	case 'in mounting hko':			$lestatus = "In Mounting HKO";			break;
	case 'waiting for frame hko':	$lestatus = "Waiting for Frame HKO";	break;
	case 'in edging hko':			$lestatus = "In Edging HKO";			break;
	case 'in edging swiss':			$lestatus = "In Edging Swiss";			break;
	case 'waiting for frame store':	$lestatus=  "Waiting for Frame Store";	break;
	case 'waiting for frame ho/supplier':	$lestatus=  "Waiting for Frame Head Office/Supplier";	break;
						
}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);

$queryLab = "SELECT lab_name from labs WHERE primary_key = ". $listItem[lab];
$LabResult=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
$DataLab=mysql_fetch_array($LabResult);
mysql_free_result($LabResult);

			$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$order_date</td>
                <td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$DataLab[lab_name]</td>";
               $message.="
                <td align=\"center\">$listItem[order_patient_last] $listItem[order_patient_first]</td>
				<td align=\"center\">$listItem[patient_ref_num]</td>
				<td align=\"center\">$listItem[tray_num]</td>
				<td align=\"left\">".  $listItem['order_product_name'] . "</td>
				<td align=\"center\">". $lestatus. "</td>";
              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
echo '<br>'. $message;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Commandes 1.59 et + avec teinte " . $datecomplete;
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	$compteur = 0;
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}
	
	if($response){ 
		log_email("REPORT: High Index with Tint",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: sucess';
    }else{
		log_email("REPORT: High Index with Tint",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: failed';
	}	
	
	
	
	function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}
//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");

?>