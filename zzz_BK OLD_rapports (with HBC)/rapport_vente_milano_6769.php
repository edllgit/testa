<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);     

$aujourdhui=date("Y-m-d");	
//$aujourdhui="2014-04-07";//Hard coder une date
$tomorrow		  = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$vendredi_dernier = date("Y-m-d", $tomorrow);	

//DATES HARD CODÉS A ENLEVER
/*
$vendredi_dernier = "2018-04-01";	
$aujourdhui       = "2018-06-30";	
*/
//RAPPORT QUI ROULE UNE FOIS PAR SEMAINE (LE JEUDI) et qui inclus toutes les montures MILANO 6769 commandés depuis le vendredi précédent

//1- Les jobs exclusive qui en théorie ont utilisé un frame par commande
$rptQuery="SELECT * FROM orders, extra_product_orders WHERE orders.order_num = extra_product_orders.order_num AND extra_product_orders.category in ('Edging','Frame','Edging_Frame')
 AND order_from = 'ifcclubca' AND order_date_processed between '$vendredi_dernier' and '$aujourdhui' 
 AND order_product_type = 'exclusive' AND supplier like '%MILANO%' GROUP BY orders.order_num ORDER BY  model  DESC";
echo 'requete:'. $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);


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
				<td align=\"center\">Color</td>
	</tr>";
	
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, color FROM extra_product_orders WHERE order_num = $listItem[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
			
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
						case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
						case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
						case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;					
					default:						$list_order_status = "UNKNOWN";
	}

			

			 $message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[order_num]</td>
               				<td align=\"center\">$listItem[order_date_processed]</td>";
             $message.="    <td align=\"center\">$DataFrame[supplier]</td>
							<td align=\"center\">$DataFrame[temple_model_num]</td>
							<td align=\"center\">$DataFrame[color]</td>";
             $message.="</tr>";
		}//END WHILE

		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Milano6769 Frames Sales between $vendredi_dernier and $aujourdhui";
echo $message;
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

	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
			VALUES('Rapport vente Milano 6769 2.0', '$time','$today','$timeplus3heures','rapport_vente_milano_6769.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));

}	


?>