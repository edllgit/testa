<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);

$hier = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier = date("Y/m/d", $hier);		
$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name,orders.redo_order_num, orders.order_num as order_num, orders.po_num, orders.special_instructions, orders.internal_note, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.prescript_lab, orders.order_patient_last, orders.tray_num, accounts.company, orders.order_status, orders.redo_reason_id from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
	WHERE orders.order_date_processed='$hier' and orders.redo_order_num IS NOT NULL
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')
	order by orders.lab, orders.order_status";
	

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
				<td align=\"center\">Redo Order #</td>
                <td align=\"center\">Main Lab</td>
				<td align=\"center\">Raison</td>
				<td align=\"center\">Special Instruction</td>
				<td align=\"center\">Note intene</td>
				<td align=\"center\">Lab qui fabrique</td>
                <td align=\"center\">Order Status</td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";			break;
						case 'order imported':			$list_order_status = "Order Imported";		break;
						case 'job started':				$list_order_status = "Surfacing";			break;
						case 'in coating':				$list_order_status = "In Coating";			break;
						case 'profilo':				    $list_order_status = "Profilo";				break;
						case 'in mounting':				$list_order_status = "In Mounting";			break;
						case 'central lab marking':		$list_order_status = "Central Lab Marking"; break;
						case 'in edging':				$list_order_status = "In Edging";			break;
						case 'order completed':			$list_order_status = "Order Completed";		break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";		break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";		break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";		break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";		break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";		break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";		break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";		break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";	break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";	break;
						case 're-do':					$list_order_status = "Redo";				break;
						case 'in transit':				$list_order_status = "In Transit";			break;
						case 'out for clip':		    $list_order_status =  "Out for clip";		break;
						case 'filled':					$list_order_status = "Shipped";				break;
						case 'cancelled':				$list_order_status = "Cancelled";			break;
						case 'in mounting hko':			$list_order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$list_order_status = "In Edging HKO";			break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
						
		}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);

			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[order_num]</td>
			<td align=\"center\">$listItem[redo_order_num]</td>
             <td align=\"center\">$listItem[lab_name]</td>";
				
				
			$queryLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
			$ResultLab=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
			$DataLab=mysql_fetch_array($ResultLab);	
				
				
	
				
				
			
			$queryReason = "SELECT redo_reason_en FROM redo_reasons WHERE redo_reason_id = $listItem[redo_reason_id]"	;
			$rptReason=mysql_query($queryReason)		or die  ('I cannot select items because: ' . mysql_error());
 			$DataReason=mysql_fetch_array($rptReason);
			
               $message.="
			    <td align=\"center\">$DataReason[redo_reason_en]</td>
                <td align=\"center\">$listItem[special_instructions]</td>
				 <td align=\"center\">$listItem[internal_note]</td>
				<td align=\"center\">". $DataLab[lab_name] . "</td>
                <td align=\"center\">$list_order_status</td>";
              $message.="</tr>";
		}//END WHILE
	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

echo $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Explications des re-dos du :". $hier;
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
		log_email("REPORT: Re-do Reasons",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Re-do Reasons",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	

}
		
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Email Re-dos reasons report', '$time','$today','$timeplus3heures','cron_send_redo_reasons.php') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());			


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
	
?>