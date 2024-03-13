<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$time_start = microtime(true);
$nbrResultat = 0;
$today=date("Y-m-d");
$rptQuery="SELECT  orders.prescript_lab, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts
	WHERE  orders.user_id not in ('eyelationnet','eyelationcan') AND accounts.user_id = orders.user_id  and orders.order_status IN ('order imported',			
'job started',	'interlab',					
'in coating',							
'in mounting','in edging','central lab marking',														
'delay issue 1',
'delay issue 2',
'delay issue 3',
'delay issue 4',
'delay issue 5',
'delay issue 6'	,	
'waiting for frame',	
'waitinf for frame swiss',
'waiting for shape'	,		
're-do','verifying',		
'in transit','order completed','on hold','')  AND orders.lab <> 26  AND orders.lab <> 37 order by order_date_processed";
	

	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: <br><br>' . mysql_error());

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
                <td align=\"center\"><b>Order num</b></td>
				<td align=\"center\"><b>Patient Ref</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				<td align=\"center\"><b>Order date</b></td>
                <td align=\"center\"><b>Status</b></td>
				<td align=\"center\"><b>Depuis</b></td>
                <td align=\"center\"><b>Product</b></td>
				<td align=\"center\"><b>Internal note</b></td>
                <td align=\"center\"><b>Main Lab</b></td>
				<td align=\"center\"><b>Prod. Lab</b></td>
				</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
	$queryStatusUpdate="SELECT  max(status_history_id) as max_id  from status_history WHERE order_num = ".  $listItem[order_num]	;
	$resultStatusUpdate=mysql_query($queryStatusUpdate)		or die  ('I cannot select items because: <br><br>' . mysql_error());
	$DataStatusHistory=mysql_fetch_array($resultStatusUpdate);		

	
	if ($DataStatusHistory['max_id'] <>"")
	{
	$queryStatus = "SELECT update_time from status_history WHERE status_history_id =  " . $DataStatusHistory['max_id'];
	$resultStatus=mysql_query($queryStatus)		or die  ('I cannot select items because: <br><br>' . mysql_error());
	$DataStatus=mysql_fetch_array($resultStatus);	

	//echo $queryStatus . '<br><br>';
	}
	
	$today = date("Y-m-d");
	//$tomorrow = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
	$tomorrow = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
	$datecomplete = date("Y/m/d", $tomorrow);
	
	
	$queryDateDiff = "SELECT DATEDIFF('$today','$DataStatus[update_time]') as ladate"; 
	//echo  '<br>'.$queryDateDiff . '<br>';
	$resultDateDiff=mysql_query($queryDateDiff)		or die  ('I cannot select items because: <br><br>' . mysql_error());
	$DataDateDiff=mysql_fetch_array($resultDateDiff);	
	
	$DataDateDiff[ladate] = abs( $DataDateDiff[ladate]);
	//echo '<br> Resultat '. $listItem[order_num] . '  ' .  $DataDateDiff[ladate] . '<br>';	
	//echo 'if datediff > 2: '  . $DataDateDiff[ladate] . '<br><br>' ;
	if ($DataDateDiff[ladate] > 2) {
	
		
		$nbrResultat  = $nbrResultat +1;
		
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  		break;
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
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':		    $list_order_status =  "Out for clip";		    break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'basket':					$list_order_status = "Basket";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;

						case 'in mounting hko':			$list_order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$list_order_status = "In Edging HKO";			break;
						case 'in edging swiss':			$list_order_status = "In Edging Swiss";			break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}
		
	}


	$queryLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["main_lab"];
	$ResultLab=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
	$DataLab=mysql_fetch_array($ResultLab);
	$main_lab = $DataLab[lab_name];

	$queryPLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
	$ResultPLab=mysql_query($queryPLab)		or die  ('I cannot select items because: ' . mysql_error());
	$DataPLab=mysql_fetch_array($ResultPLab);
	$prescript_lab = $DataPLab[lab_name];
		
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>";
			$message.="<td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>";
			$message.="<td align=\"center\">$listItem[tray_num]</td>";
			$message.="<td align=\"center\">$listItem[order_date_processed]</td>";
			
		
            $message.="
                <td align=\"center\">$listItem[order_status]</td>
                <td align=\"center\">$DataStatus[update_time]</td>
                <td align=\"center\">$listItem[order_product_name]</td>
				<td align=\"center\">$listItem[internal_note]</td>
                <td align=\"center\">$main_lab</td>
				<td align=\"center\">$prescript_lab</td>";
				
				
              $message.="</tr>";
		}//END WHILE
		
}//END IF DATEDIFF > 3 (means 4 days or more without status update)		
			
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\"><b>Number of Orders to check because their status has not been updated recently: $nbrResultat</b></td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	
//$send_to_address = array('rapports@direct-lens.com');		
echo "<br>".$send_to_address;	
echo '<br>'. $message;	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Liste des commandes qui ne bougent pas depuis un moment :".$curTime;
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
		log_email("REPORT: Send Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Send Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) VALUES('Send late jobs report by email', '$time','$today','$timeplus3heures','cron_send_late_jobs.php','$ips') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());		
			
mysql_free_result($resultStatusUpdate);	
mysql_free_result($resultStatus);
mysql_free_result($resultDateDiff);		
mysql_free_result($rptResult);


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>