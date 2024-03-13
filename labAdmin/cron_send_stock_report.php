<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$lab_pkey=3;//Acculab ID

$tomorrow     = mktime(0,0,0,date("m"),date("d")-3,date("Y"));
$datecomplete = date("Y/m/d", $tomorrow);

			
$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first,orders.order_product_name, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status FROM orders
	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	WHERE prescript_lab='$lab_pkey' 
	AND orders.order_date_processed < '$datecomplete'
	AND orders.order_status NOT IN ('filled','on hold')
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
	GROUP BY order_num";
	
	echo '<br>'. $rptQuery;
	exit();
	
	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
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

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Tray Number</td>
                <td align=\"center\">Order Status</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";				break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':				    $list_order_status = "Profilo";				    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status =  "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case "on hold":					$$list_order_status= "On Hold";			        break;
						case 'in mounting hko':			$list_order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$list_order_status = "In Edging HKO";			break;
						case 'in edging swiss':			$list_order_status = "In Edging Swiss";			break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':	$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "";             	        break;
		}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);

			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[lab_name]</td><td align=\"center\">$order_date</td>";
				
               $message.="
			     <td align=\"center\">$listItem[order_product_name]</td>
                <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[tray_num]</td>
                <td align=\"center\">$list_order_status</td>";
              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	
$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Stock orders - STC";
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
		log_email("REPORT: Stock orders - STC",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Stock orders - STC",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

?>