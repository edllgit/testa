<?php
/*
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*

ini_set('MAX_EXECUTION_TIME', -1);
include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');


$tt= $_REQUEST[tt];

echo ' Value of TT:'.$tt;

for ($i = $tt; $i <= 14; $i++) {
    echo '<br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('88403')";	$Partie = '88403-Bloor';	       
	$send_to_address = array('rapports@direct-lens.com');break;
		
	case  2: $Userid =  " orders.user_id IN ('88408')";	$Partie = '88408-Oshawa';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  3: $Userid =  " orders.user_id IN ('88409')";	$Partie = '88409-Eglinton';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  4: $Userid =  " orders.user_id IN ('88411')";	$Partie = '88411-Sherway';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  5: $Userid =  " orders.user_id IN ('88414')";	$Partie = '88414-Yorkdale';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  6: $Userid =  " orders.user_id IN ('88416')";	$Partie = '88416-Vancouver';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  7: $Userid =  " orders.user_id IN ('88431')";	$Partie = '88431-Calgary DTN';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  8: $Userid =  " orders.user_id IN ('88433')";	$Partie = '88433-Polo Park';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  9: $Userid =  " orders.user_id IN ('88434')";	$Partie = '88434-Market Mall';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  10: $Userid =  " orders.user_id IN ('88435')";	$Partie = '88435-West Edmonton';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  11: $Userid =  " orders.user_id IN ('88438')";	$Partie = '88438-Metrotown';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  12: $Userid =  " orders.user_id IN ('88439')";	$Partie = '88439-Langley';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  13: $Userid =  " orders.user_id IN ('88440')";	$Partie = '88440-Rideau';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  14: $Userid =  " orders.user_id IN ('88444')";	$Partie = '88444-Mayfair';	       
	$send_to_address = array('rapports@direct-lens.com');break;


}//End Switch

	
	$time_start  = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT * FROM orders WHERE $Userid AND redo_order_num IS NOT NULL
	AND order_date_shipped BETWEEN '2020-01-01' AND '2020-12-31'";
	echo '<br>'. $rptQuery;
	
	
		
		$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 7: <br><br>' . mysqli_error($con));
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
	
			$message.="<body><table width=\"850\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="
					<tr bgcolor=\"CCCCCC\">
						<td align=\"center\"><b>Store</b></td>
						<td align=\"center\"><b># Redo</b></td>
						<td align=\"center\"><b># 1ST order</b></td>
						<td align=\"center\"><b># Optipro (1ST order)</b></td>
						<td align=\"center\"><b>Redo Reason</b></td>
						<td align=\"center\"><b>Date Redo</b></td>
						<td align=\"center\"><b>Date (1ST order)</b></td>
						<td align=\"center\"><b>Product (1ST order)</b></td>
						<td align=\"center\"><b>Product Redo</b></td>
						<td align=\"center\"><b>Cost Redo</b></td>
						<td align=\"center\"><b>Supplier (1ST order)</b></td>
						<td align=\"center\"><b>Optician (1ST order)</b></td>
					</tr>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
	
			$queryOriginal   = "SELECT * from ORDERS WHERE order_status<>'cancelled' AND order_num = $listItem[redo_order_num]";
			$ResultOriginal  = mysqli_query($con,$queryOriginal)		or die  ('I cannot Send email because 6: ' . mysqli_error($con));	
			$DataOriginal    = mysqli_fetch_array($ResultOriginal,MYSQLI_ASSOC);
		
			$queryRedoReason   = "SELECT * from redo_reasons WHERE redo_reason_id = $listItem[redo_reason_id]";
			$ResultRedoReason  = mysqli_query($con,$queryRedoReason)		or die  ('I cannot Send email because 6: ' . mysqli_error($con));	
			$DataRedoReason    = mysqli_fetch_array($ResultRedoReason,MYSQLI_ASSOC);
		
		
		switch($DataOriginal[prescript_lab]){
			case 2:  $Fournisseur='HKO'; 			break;	
			case 4:  $Fournisseur='GKB'; 			break;	
			case 10: $Fournisseur='Swisscoat'; 		break;
			case 25: $Fournisseur='Central Lab'; 	break;
			case 3:  $Fournisseur='STC'; 			break;	
			case 69: $Fournisseur='Essilor Lab'; 	break;	
			case 72: $Fournisseur='QC'; 			break;
			case 70: $Fournisseur='Plastic Plus';	break;
			case 60: $Fournisseur='CSC';		 	break;
			case 68: $Fournisseur='QUEST';		 	break;
			case 73: $Fournisseur='KNR';		 	break;
			default:  $Fournisseur='INCONNU';		break;	
		}
		
		//Aller chercher le Cost de la reprise
		$queryCostRedo="SELECT cost_us FROM ifc_ca_exclusive where primary_key= (SELECT order_product_id from orders WHERE order_num= $listItem[order_num])";
		//echo '<br><br>'.$queryCostRedo;
		$ResultCostRedo  = mysqli_query($con,$queryCostRedo)		or die  ('I cannot Send email because 6: ' . mysqli_error($con));	
		$DataCostRedo    = mysqli_fetch_array($ResultCostRedo,MYSQLI_ASSOC);
			
			$message.="<tr bgcolor=\"$bgcolor\">
						<td height=\"150\" align=\"center\">$listItem[user_id]</td>
						   <td height=\"150\" align=\"center\">$listItem[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num_optipro]</td>
						   <td align=\"center\">$DataRedoReason[redo_reason_en]</td>
						   <td align=\"center\">$listItem[order_date_processed]</td>
						   <td align=\"center\">$DataOriginal[order_date_processed]</td>
						   <td align=\"center\">$DataOriginal[order_product_name]</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						  
						  <td align=\"center\">$DataCostRedo[cost_us]</td>
						  
						   <td align=\"center\">$Fournisseur</td>
						   <td align=\"center\">$listItem[opticien]</td>
					   </tr>";
	}//END WHILE
	echo $message;
	//exit();
	

	//SEND EMAIL
	$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".$send_to_address;	
		
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie";

	//exit();
	if ($nbrResultat > 0)
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<br>'; 
	var_dump($send_to_address);
	echo '<br>'; 
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
			echo 'Fonctionne';
			//log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			echo 'Erreur..';
			//log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}	
		
	//exit();
			
}//End For
*/
?>
