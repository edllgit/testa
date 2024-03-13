<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");

//FRAMES SALES REPORT OF IFC.ca AND SAFE
$date2      = date("Y-m-d");	
//$aujourdhui="2014-04-07";//Hard coder une date
$tomorrow	= mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$date1      = date("Y-m-d", $tomorrow);	

//$date1 = "2014-07-18";//Hard coder une date	
//$date2 = "2014-07-24";//Hard coder une date	

//1- Les jobs D'iFC.ca exclusive qui en théorie ont utilisé un frame par commande
/*$rptQuery="SELECT orders.*, ifc_ca_exclusive.price FROM orders, extra_product_orders , ifc_ca_exclusive
WHERE ifc_ca_exclusive.primary_key = orders.order_product_id AND
orders.order_num = extra_product_orders.order_num AND extra_product_orders.category='Frame' 
AND order_from in('ifcclubca') AND order_date_processed between '$date1' and '$date2' AND order_product_type = 'exclusive' 
 AND supplier not like '%BUGETTI%'  AND supplier not like '%RENDEZVOUS%'  AND supplier not like '%ISEE%'
ORDER BY supplier,temple_model_num DESC";*/

$rptQuery="SELECT orders.*, ifc_ca_exclusive.price FROM orders, extra_product_orders , ifc_ca_exclusive
WHERE ifc_ca_exclusive.primary_key = orders.order_product_id AND
orders.order_num = extra_product_orders.order_num AND extra_product_orders.category='Frame' 
AND order_from in('ifcclubca') AND order_date_processed between '$date1' and '$date2' AND order_product_type = 'exclusive' 
AND supplier NOT IN ('BUGETTI','MONTANA','MONTANA +','SUNOPTIC AK','SUNOPTIC CP','SUNOPTIC K','SUNOPTIC MASSIMO',
'Basic','ArmouRx','Wrap-Rx','Classic','Metro','PREMIUM')
ORDER BY supplier,temple_model_num DESC";
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
		$message.="<tr><td colspan=\"11\">Ce rapport ne contient pas les montures qui sont recommandés automatiquement c'est-à-dire les: 
		BUGETTI,MONTANA,MONTANA +,SUNOPTIC AK,SUNOPTIC CP,SUNOPTIC K,SUNOPTIC MASSIMO,Basic,ArmouRx,Wrap-Rx,Classic,Metro,PREMIUM</td></tr><tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Supplier</td>
				<td align=\"center\">Temple Model Num</td>
                <td align=\"center\">Model</td>
				<td align=\"center\">Color</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Product Price</td>
				<td align=\"center\">Order Total</td>
				<td align=\"center\">Website</td>
				</tr>";
		$totalPrice      = 0;
		$totalOrderTotal = 0;	
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging')";
		$resultFrame = mysql_query($queryFrame)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrame   = mysql_fetch_array($resultFrame);
			
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
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'central lab marking':		$list_order_status = "Central Lab Marking";		break;
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
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'in mounting hko':			$list_order_status = "In Mounting HKO";			break;
						case 'waiting for frame hko':	$list_order_status = "Waiting for Frame HKO";	break;
						case 'in edging hko':			$list_order_status = "In Edging HKO";			break;
						case 'in edging swiss':			$list_order_status = "In Edging Swiss";			break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shape to Swiss";	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						
		}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);

			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[order_num]</td>
               				<td align=\"center\">$order_date</td>";
             $message.="    <td align=\"center\">$DataFrame[supplier]</td>
			 				<td align=\"center\">$DataFrame[model]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$listItem[order_product_name]</td>
							<td align=\"center\">$listItem[price]</td>
							<td align=\"center\">$listItem[order_total]</td>
							<td align=\"center\">$listItem[order_from]</td>";
              $message.="</tr>";
			  
	    $totalPrice      =  $totalPrice      + $listItem[price];
		$totalOrderTotal =  $totalOrderTotal + $listItem[order_total];	
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"7\">Number of Orders: $ordersnum</td><td align=\"center\">$$totalPrice</td><td align=\"center\">$$totalOrderTotal</td><td>&nbsp;</td></tr>
		</table>";


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Frames Sales IFC.ca Between $date1 and $date2";
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

}	
//Fin partie 1 (IFC.CA)








//2- PARTIE SAFETY
$rptQuery="SELECT orders.*, safety_exclusive.price FROM orders, extra_product_orders , safety_exclusive
WHERE safety_exclusive.primary_key = orders.order_product_id AND
orders.order_num = extra_product_orders.order_num AND extra_product_orders.category='Edging' 
AND order_from in('safety') AND order_date_processed between  '$date1' and '$date2'  AND order_product_type = 'exclusive'  
ORDER BY supplier,temple_model_num DESC";
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
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Supplier</td>
                <td align=\"center\">Model</td>
				<td align=\"center\">Temple Model Num</td>
				<td align=\"center\">Color</td>
				<td align=\"center\">Product</td>
				<td align=\"center\">Product Price</td>
				<td align=\"center\">Order Total</td>
				<td align=\"center\">Website</td>
				</tr>";
		$totalPrice      = 0;
		$totalOrderTotal = 0;		
		
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging')";
		$resultFrame = mysql_query($queryFrame)		or die  ('I cannot select items because: ' . mysql_error());
		$DataFrame   = mysql_fetch_array($resultFrame);
			
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
						case 'profilo':					$list_order_status = "Profilo";			  	    break;
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
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'out for clip':			$list_order_status = "Out for clip";			break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss':	$list_order_status = "Scanned shapes to Swiss";	break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
		}

			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);

			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[order_num]</td>
               				<td align=\"center\">$order_date</td>";
             $message.="    <td align=\"center\">$DataFrame[supplier]</td>
			 				<td align=\"center\">$DataFrame[model]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[color]</td>
							<td align=\"center\">$listItem[order_product_name]</td>
							<td align=\"center\">$listItem[price]</td>
							<td align=\"center\">$listItem[order_total]</td>
							<td align=\"center\">$listItem[order_from]</td>";
              $message.="</tr>";
	    $totalPrice      =  $totalPrice      + $listItem[price];
		$totalOrderTotal =  $totalOrderTotal + $listItem[order_total];	
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"7\">Number of Orders: $ordersnum</td><td align=\"center\">$$totalPrice</td><td align=\"center\">$$totalOrderTotal</td><td>&nbsp;</td></tr>
		</table>";


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Frames Sales :SAFE Between $date1 and $date2";
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

}	
//Fin partie 2 (SAFE)



function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>