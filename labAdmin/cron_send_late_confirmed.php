<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$today=date("Y-m-d");
			
$rptQuery="SELECT  distinct orders.prescript_lab, orders.order_num as order_num, orders.order_product_name, orders.po_num, labs.lab_name, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status from orders, labs
	WHERE user_id not in ('eyelationnet','eyelationcan') AND  orders.lab = labs.primary_key and orders.order_status='processing' and order_date_processed > '2011-01-01' and order_date_processed != '" . $today . "' order by order_date_processed";
	echo '<br>'. $rptQuery . '<br>';
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
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Order Date</td>
                <td align=\"center\">Product</td>
                <td align=\"center\">Patient</td>
				<td align=\"center\">Lab that produce</td>
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
						case 'processing':				$list_order_status = "Confirmed";			break;
						case 'order imported':			$list_order_status = "Order Imported";		break;
						case 'job started':				$list_order_status = "Surfacing";			break;
						case 'in coating':				$list_order_status = "In Coating";			break;
						case 'profilo':					$list_order_status = "Profilo";			  	break;
						case 'in mounting':				$list_order_status = "In Mounting";			break;
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
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";	break;
		}



			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);


			$message.="<tr bgcolor=\"$bgcolor\">";
			
			
			$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
			$datedujour = date("Y-m-d", $tomorrow);
			
		  $queryProduceLab = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"] ;
		  $rptProduceLab=mysql_query($queryProduceLab)		or die  ('I cannot select items because: <br><br>' . mysql_error());
		  $DataProduceLab=mysql_fetch_array($rptProduceLab);	
			
	 $now = time();
     $your_date = strtotime($listItem[order_date_processed]);
     $datediff = $now - $your_date;
     $nbrJoursDifference =  floor($datediff/(60*60*24));
			
if ($nbrJoursDifference >1){
$message.="<td align=\"center\"><h3>$listItem[order_num]</h3></td><td align=\"center\">$listItem[lab_name]</td>
<td align=\"center\"><h3>$order_date</h3></td>";
$message.="<td align=\"center\">$listItem[order_product_name]</td><td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td> 
<td align=\"center\">$DataProduceLab[lab_name]</td>
<td align=\"center\">$listItem[tray_num]</td>
<td align=\"center\">$list_order_status</td>";

}else{
$message.="<td align=\"center\">$listItem[order_num]</td><td align=\"center\">$listItem[lab_name]</td>
<td align=\"center\">$order_date</td>";
$message.="<td align=\"center\">$listItem[order_product_name]</td><td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
<td align=\"center\">$DataProduceLab[lab_name]</td>
<td align=\"center\">$listItem[tray_num]</td>
<td align=\"center\">$list_order_status</td>";
}
			
	
        
				
           
              
			
			 			  

              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

		//SEND EMAIL
		
		if ($labsItem[notification_email]==""){
			
			$lab_email=$labsItem[lab_email];
		}
		else{
			$lab_email=$labsItem[notification_email];
		}

		$send_to_address = array('rapports@direct-lens.com');
			
		//$send_to_address = array('rapports@direct-lens.com');

echo "<br>".$send_to_address;
		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject='Liste des commandes Confirmed :'.$curTime;
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
		log_email("REPORT: Send Confirmed Jobs",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Send Confirmed Jobs",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
echo '<br>'.  $message;


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>