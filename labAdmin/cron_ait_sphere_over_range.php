<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$hier = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$hier = date("Y/m/d", $hier);	
//THIS REPORT IS TO SEE THE OVER RANGE ORDERED BY JAMES NEVINS ACCT (CUTTING EDGE OPTICAL) ONLY

$rptQuery= "SELECT orders.* ,A.account_num as account_num from orders, accounts A 
WHERE  
orders.user_id = A.user_id AND orders.re_sphere >=  6  AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'   
OR 
orders.user_id = A.user_id AND orders.le_sphere >=  6  AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'   
OR
orders.user_id = A.user_id AND orders.re_sphere <= -6  AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'     
OR 
orders.user_id = A.user_id AND orders.le_sphere <= -6  AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'   
OR
orders.user_id = A.user_id AND orders.re_cyl >  2     AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'   
OR 
orders.user_id = A.user_id AND orders.le_cyl >  2     AND orders.order_date_processed = '$hier'  AND orders.order_date_shipped = '0000-00-00' AND  orders.user_id='jamesnns' AND order_status <> 'cancelled'   
ORDER BY orders.order_date_processed";

	
echo $rptQuery;
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
		$message.="
		<tr bgcolor=\"CCCCCC\">
	<th align=\"center\" width=\"75\">Order date</th>
	<th width=\"60\">Order Number</th>
	<th width=\"60\">Main Lab</th>
	<th width=\"100\">Patient</th>
	<th width=\"120\">Ref Patient</th>
	<th width=\"380\">Product</th>
	<th width=\"110\">Status</th>
	<th width=\"90\">Left Sphere</th>
	<th width=\"90\">Right Sphere</th>
	<th width=\"90\">Left Cylinder</th>
	<th width=\"90\">Right Cylinder</th>
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
						case 'job started':				$list_order_status = "Surfacing";		    	break;
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
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'information in hand':		$list_order_status = "Info in Hand";			break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}


			
switch($listItem['order_status']) {
	case 'processing':			$lestatus= "Confirmed";				break;
	case 'order imported':		$lestatus=  "Order Imported";		break;
	case 'job started':			$lestatus=  "Surfacing";			break;
	case 'in coating':			$lestatus=  "In Coating";			break;
	case 'profilo':				$lestatus = "Profilo";			    break;
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
	case 'out for clip':		$lestatus=  "Out for clip";			break;
	case 'filled':				$lestatus=  "Shipped";				break;
	case 'cancelled':			$lestatus=  "Cancelled";			break;
	case 're-do':				$lestatus=  "Redo";					break;
	case 'waiting for frame':	$lestatus=  "Waiting for Frame";	break;
	case 'information in hand':	$lestatus=  "Infoin hand";			break;
	case 'waiting for lens':	$lestatus=  "Waiting for lens";		break;
	case 'waiting for frame store':	$lestatus=  "Waiting for Frame Store";	break;
	case 'waiting for frame ho/supplier':	$lestatus=  "Waiting for Frame Head Office/Supplier";	break;
}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);

$queryLab = "SELECT lab_name from labs WHERE primary_key = ". $listItem[lab];
$LabResult=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
$DataLab=mysql_fetch_array($LabResult);


			$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$order_date</td>
                <td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$DataLab[lab_name]</td>";
               $message.="
                <td align=\"center\">$listItem[order_patient_last] $listItem[order_patient_first]</td>
				<td align=\"center\">$listItem[patient_ref_num]</td>
				<td align=\"left\">".    $listItem['order_product_name'] . "</td>
				<td align=\"center\">".  $lestatus. "</td>
				<td align=\"center\">".  $listItem['le_sphere']. "</td>
				<td align=\"center\">" . $listItem['re_sphere'] . "</td>
				<td align=\"center\">".  $listItem['le_cyl']. "</td>
				<td align=\"center\">" . $listItem['re_cyl'] . "</td>";
  
              $message.="</tr>";
		}//END WHILE
		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
echo '<br>'. $message;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "AIT Spheres/Cylinders Over Range :". $hier;
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
		log_email("REPORT: Sphere over 2",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Sphere over 2",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
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