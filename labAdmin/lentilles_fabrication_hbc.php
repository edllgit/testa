<?php

include("../Connections/connexion_hbc.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');


$store = $_REQUEST[store];
$month = $_REQUEST[month];
$year  = $_REQUEST[year];

echo '<br>Store: '. $store;
echo '<br>month: '. $month . ' ' . $year;

switch($month){
	case 'janvier':   $date1 = $year. "-01-01"; $date2 = $year . "-01-31";    break;
	case 'fevrier':   $date1 = $year. "-02-01"; $date2 = $year . "-02-29";    break;
	case 'mars':      $date1 = $year. "-03-01"; $date2 = $year . "-03-31";    break;
	case 'avril':     $date1 = $year. "-04-01"; $date2 = $year . "-04-30";    break;
	case 'mai':       $date1 = $year. "-05-01"; $date2 = $year . "-05-31";    break;
	case 'juin':      $date1 = $year. "-06-01"; $date2 = $year . "-06-30";    break;
	case 'juillet':   $date1 = $year. "-07-01"; $date2 = $year . "-07-31";    break;
	case 'aout':      $date1 = $year. "-08-01"; $date2 = $year . "-08-31";    break;
	case 'septembre': $date1 = $year. "-09-01"; $date2 = $year . "-09-30";    break;
	case 'octobre':   $date1 = $year. "-10-01"; $date2 = $year . "-10-31";    break;
	case 'novembre':  $date1 = $year. "-11-01"; $date2 = $year . "-11-30";    break;
	case 'decembre':  $date1 = $year. "-12-01"; $date2 = $year . "-12-31";    break;	
	default: exit();
}


switch($store){
	case 'griffe-tr':   $userID = "('88666')"; 	break;
	/*case 'drummondville':  $userID = "('entrepotdr','safedr')"; 			break;
	case 'granby':        $userID = "('granby','granbysafe')";              break;
	case 'halifax':        $userID = "('warehousehal','warehousehalsafe')"; break;
	case 'laval':   	   $userID = "('laval','lavalsafe')"; 				break;
	case 'levis':   	   $userID = "('levis','levissafe')"; 				break;
	case 'longueuil':      $userID = "('longueuil','longueuilsafe')";   	break;
	case 'sherbrooke':     $userID = "('sherbrooke','sherbrookesafe')"; 	break;
	case 'terrebonne':     $userID = "('terrebonne','terrebonnesafe')"; 	break;
	case 'trois-rivieres': $userID = "('entrepotifc','entrepotsafe')";  	break;
	case 'quebec':   	   $userID = "('entrepotquebec','quebecsafe')";  	break;
	case 'montreal':   	   $userID = "('montreal','montrealsafe')";  	break;*/
}


echo '<br>Dates: '. $date1 . ' - ' . $date2;
			
$rptQuery="SELECT  user_id, orders.order_num ,	order_date_processed ,	order_date_shipped ,	order_status, 	order_total, order_num_optipro, supplier, temple_model_num,	color  from orders, extra_product_orders
	WHERE  orders.order_num = extra_product_orders.order_num
	AND user_id IN $userID
	AND orders.order_date_processed BETWEEN '$date1' and '$date2'
	AND order_status <> 'cancelled'
	AND category='Frame'
	AND (order_date_shipped > '$date2' OR order_date_shipped = '0000-00-00')";
	
	echo '<br>'. $rptQuery;
	
	
	

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
                <td align=\"center\">Compte</td>
                <td align=\"center\"># Commande</td>
				<td align=\"center\"># Commande Optipro</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Shipping Date</td>
				<td align=\"center\">Status</td>
                <td align=\"center\">Order Total</td>
				<td align=\"center\">Monture</td>
				</tr>";
		$GrandTotal = 0;		
		while ($listItem=mysql_fetch_array($rptResult)){
		
		$GrandTotal = $GrandTotal + $listItem["order_total"];

			
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
						case 'profilo':				    $list_order_status = "Profilo";					break;
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
						case 'interlab qc':				$list_order_status = "Interlab QC";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case "on hold":					$list_order_status= "On Hold";			        break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "";             	        break;
		}



			$message.="
			<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[user_id]</td>
                <td align=\"center\">$listItem[order_num]</td>
				 <td align=\"center\">$listItem[order_num_optipro]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_date_shipped]</td>
                <td align=\"center\">$list_order_status</td>
                <td align=\"center\">$listItem[order_total]</td>
				<td align=\"center\">$listItem[supplier] $listItem[temple_model_num] $listItem[color] </td>
			</tr>";
			
			
				
		}//END WHILE
		mysql_free_result($rptResult);
		$message.="<tr><td colspan=\"5\">&nbsp;</td><td align=\"right\"><b>Grand Total:</b></td><td align=\"right\"><b>$GrandTotal$</b></td></tr></table>";

echo '<br><br>'. $message;

		
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Lentilles en fabrication $month pour $store";
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
		log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
}		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}


?>