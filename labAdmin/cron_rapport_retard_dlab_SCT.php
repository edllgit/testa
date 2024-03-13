<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$time_start = microtime(true);


$tomorrow = mktime(0,0,0,date("m"),date("d")-5,date("Y"));
$ilya5jours = date("Ymd", $tomorrow);

			
$rptQuery="SELECT * FROM `dlab_orders`
WHERE order_status NOT IN ('filled', 'cancelled')  AND directlab = 'sct'  and invoice_type = 1
AND  order_date_processed <= '" . $ilya5jours . "' order by order_date_processed";
	
	echo '<br>'. $rptQuery . '<br>';
	

	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because:' . mysql_error());

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
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Dlab Order Num</td>
				<td align=\"center\">User</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Dlab Expected Date</td>
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
						case 'job started':				$list_order_status = "Surfacing";			    break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'central lab marking':		$list_order_status = "Central Lab Marking";		break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'waiting for lens':		$list_order_status = "Waiting for lens";		break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Fram Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'interlab':				$list_order_status = "Interlab P";				break;
						case 'interlab qc':				$list_order_status = "Interlab QC";				break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'in mounting hko':			$list_order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$list_order_status = "In Edging HKO";			break;
						case 'in edging swiss':			$list_order_status = "In Edging Swiss";			break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
						
		}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_expected]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);




$QueryUser="Select lab_name from labs where primary_key = (SELECT main_lab FROM `accounts` WHERE user_id = '" . $listItem[user_id]  . "')";
$resultUser=mysql_query($QueryUser)		or die  ('I cannot select items because: <br><br>' . mysql_error());
if (mysql_num_rows($resultUser)> 0){
$DataUser=mysql_fetch_array($resultUser);
mysql_free_result($resultUser);
$User = $DataUser['lab_name'];
}
else{
$User =  $listItem[user_id];
}




			$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[dlab_order_num]</td>
				<td align=\"center\">Directlab St-Catharines</td>
                <td align=\"center\">$order_date</td>";
				
		
		
		$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
		$aujourdhui = date("m-d-Y", $ladate);
	
		if ($ship_date < $aujourdhui){
		$message.=	"<td align=\"center\"><b>$ship_date</b></td>";
		}else{
		$message.=	"<td align=\"center\">$ship_date</td>";
		}
				
		$Produits = trim($listItem[produit], ",");
$Produits = str_replace(',', ' ',$listItem[produit]);
				
        $message.="<td align=\"center\">$Produits</td>";
        $message.="
                <td align=\"center\">$listItem[patient]</td>
                <td align=\"center\">$listItem[tray_num]</td>
                <td align=\"center\">$list_order_status</td>";
        $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Directlabs Late Orders SCT :".$curTime;
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
		log_email("REPORT: Rapport Retard Directlab Saint-Catharines",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Rapport Retard Directlab Saint-Catharines",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
}
		
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Daily report Directlab late orders Saint-Catharines', '$time','$today','$timeplus3heures','cron_rapport_retard_dlab_SCT.php') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error()  );	


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}	
?>