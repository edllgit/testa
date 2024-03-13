<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);	

//Ce rapport EXCLUS les commandes de Québec.

$time_start = microtime(true);
$nbrResultat = 0;
$today       = date("Y-m-d");
$rptQuery    = "SELECT  orders.prescript_lab, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts
	WHERE orders.user_id not in ('quebec','quebecsafe')  AND  lab in (66,67,59) AND accounts.user_id = orders.user_id  and orders.order_status IN ('order imported',			
'job started',	'interlab',	'in coating', 'in mounting',	'in edging','delay issue 1','delay issue 2','delay issue 3','delay issue 4',
'delay issue 5','delay issue 6'	,	'waiting for frame swiss', 'waiting for frame knr','waiting for frame','waiting for shape'	,'re-do','verifying','in transit','order completed','on hold','scanned shape to swiss','')  ORDER BY order_status asc, order_date_processed";

	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 7: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResult);
	
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
				<td align=\"center\"><b>Entrepot</b></td>
				<td align=\"center\"><b>Patient Ref</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				<td align=\"center\"><b>Order date</b></td>
				<td align=\"center\"><b>Frame sent</b></td>
                <td align=\"center\"><b>Status</b></td>
				<td align=\"center\"><b>Since</b></td>
                <td align=\"center\"><b>Product</b></td>
				<td align=\"center\"><b>Internal note</b></td>
                <td align=\"center\"><b>Redo Reason</b></td>
				<td align=\"center\"><b>Prod. Lab</b></td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		
	if ($listItem[frame_sent_hko] <> '0000-00-00 00:00:00'){
		$FrameSentOn = $listItem[frame_sent_hko];
	}	
	if ($listItem[frame_sent_swiss] <> '0000-00-00 00:00:00'){
		$FrameSentOn = $listItem[frame_sent_swiss];
	}	
	
	if (($listItem[frame_sent_swiss] == '0000-00-00 00:00:00') && ($listItem[frame_sent_hko] == '0000-00-00 00:00:00')){
		$FrameSentOn = '';
	}
		
		
			
	$queryStatusUpdate="SELECT  max(status_history_id) as max_id  from status_history WHERE order_num = ".  $listItem[order_num]	;
	$resultStatusUpdate=mysqli_query($con,$queryStatusUpdate)		or die  ('I cannot select items because 2: <br><br>' . mysqli_error($con));
	$DataStatusHistory=mysqli_fetch_array($resultStatusUpdate,MYSQLI_ASSOC);		

	
	if ($DataStatusHistory['max_id'] <>"")
	{
	$queryStatus = "SELECT update_time from status_history WHERE status_history_id =  " . $DataStatusHistory['max_id'];
	$resultStatus=mysqli_query($con,$queryStatus)		or die  ('I cannot select items because 2: <br><br>' . mysqli_error($con));
	$DataStatus=mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);	

	//echo $queryStatus . '<br><br>';
	}
	
	$today = date("Y-m-d");
	//$tomorrow = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
	$tomorrow = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
	$datecomplete = date("Y/m/d", $tomorrow);
	
	
	$queryDateDiff = "SELECT DATEDIFF('$today','$DataStatus[update_time]') as ladate"; 
	//echo  '<br>'.$queryDateDiff . '<br>';
	$resultDateDiff=mysqli_query($con,$queryDateDiff)		or die  ('I cannot select items because 3: <br><br>' . mysqli_error($con));
	$DataDateDiff=mysqli_fetch_array($resultDateDiff,MYSQLI_ASSOC);	
	
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
						case 'job started':				$list_order_status = "Surfacing";				break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";					break;
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
						case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'basket':					$list_order_status = "Basket";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						case 'interlab tr': 			$list_order_status = "Interlab Trois-Rivieres";	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default: 						$list_order_status = "UNKNOWN";
		}

    if ($listItem["main_lab"] <> '')
	{
		$queryLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["main_lab"];
		$ResultLab=mysqli_query($con,$queryLab)		or die  ('I cannot select items because 4: '. $queryLab . mysqli_error($con));
		$DataLab=mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);
		$main_lab = $DataLab[lab_name];
	}
	
	$queryPLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
	$ResultPLab=mysqli_query($con,$queryPLab)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
	$DataPLab=mysqli_fetch_array($ResultPLab,MYSQLI_ASSOC);
	$prescript_lab = $DataPLab[lab_name];
		
		
			
			
			$queryCompany  = "SELECT company FROM accounts WHERE user_id = (SELECT user_id FROM orders WHERE order_num = $listItem[order_num])";
			$resultCompany = mysqli_query($con,$queryCompany)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
			$DataCompany   = mysqli_fetch_array($resultCompany,MYSQLI_ASSOC);
		
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>";
			$message.="<td align=\"center\">$DataCompany[company]</td><td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>";
			$message.="<td align=\"center\">$listItem[tray_num]</td>";
			$message.="<td align=\"center\">$listItem[order_date_processed]</td>";
			$message.="<td align=\"center\">$FrameSentOn</td>";
			
			
			
			
			$queryRedoReason  = "SELECT * FROM redo_reasons  WHERE  redo_reason_id  = (SELECT  redo_reason_id  FROM ORDERS WHERE order_num =  $listItem[order_num])";
		    $resultRedoReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because 5: ' . mysqli_error($con));
			$DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
			
			if ($DataRedoReason[redo_reason_id] <> 0)
			$RedoReason = $DataRedoReason[redo_reason_en];
			else
			$RedoReason = "";
			
            $message.="
                <td align=\"center\">$listItem[order_status]</td>
                <td align=\"center\">$DataStatus[update_time]</td>
                <td align=\"center\">$listItem[order_product_name]</td>
				<td align=\"center\">$listItem[internal_note]</td>
                <td align=\"center\">$RedoReason</td>
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
$subject= "EDLL Late orders (No Quebec)";
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
		echo 'Reussi';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		echo 'Echec';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Send late jobs report by email 2.0', '$time','$today','$timeplus3heures','cron_send_late_jobs.php','$ips') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
			

*/
?>