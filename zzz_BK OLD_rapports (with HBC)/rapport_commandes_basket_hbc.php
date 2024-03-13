<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
ini_set('display_errors', '1');
include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);     


echo 'test';

for ($i = 1; $i <= 14 ; $i++) {
    echo '<br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('88403')";      $Partie = '#88403-Bloor St.';	       
	$send_to_address = array('rapports@direct-lens.com');break;
		
	case  2: $Userid =  " orders.user_id IN ('88408')";      $Partie = '#88408-Oshawa';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  3: $Userid =  " orders.user_id IN ('88409')";      $Partie = '#88409-Eglinton';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  4: $Userid =  " orders.user_id IN ('88414')";      $Partie = '#88414-Yorkdale';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  5: $Userid =  " orders.user_id IN ('88416')";      $Partie = '#88416-Vancouver DTN';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  6: $Userid =  " orders.user_id IN ('88431')";      $Partie = '#88431-Calgary DTN';	       
	$send_to_address = array('rapports@direct-lens.com');break;
		
	case  7: $Userid =  " orders.user_id IN ('88433')";      $Partie = '#88433-Polo Park';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  8: $Userid =  " orders.user_id IN ('88434')";      $Partie = '#88434-Market Mall';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  9: $Userid =  " orders.user_id IN ('88435')";      $Partie = '#88435-West Edmonton';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  10: $Userid =  " orders.user_id IN ('88438')";      $Partie = '#88438-Metrotown';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  11: $Userid =  " orders.user_id IN ('88439')";      $Partie = '#88439-Langley';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  12: $Userid =  " orders.user_id IN ('88440')";      $Partie = '#88440-Rideau';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  13: $Userid =  " orders.user_id IN ('88444')";      $Partie = '#88444-Mayfair';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  14: $Userid =  " orders.user_id IN ('88666')";      $Partie = '#88666-GR';	       
	$send_to_address = array('rapports@direct-lens.com');break;	
}//End Switch
	
	$time_start = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, 
	orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, 
	orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  
	WHERE $Userid AND accounts.user_id = orders.user_id  AND orders.order_status IN ('basket')  
	ORDER BY order_status asc, order_date_processed";
	
	echo '<br>'. $rptQuery .'<br>';
	
	
		
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
			$message.="<tr bgcolor=\"CCCCCC\">
					<td colspan=\"8\" align=\"center\">You have orders that are currently in your basket waiting to be validated. <br><b>Thanks to validate them as soon  as possible to start production and therefore avoid additional delays.</b></td>
					</tr>
					
					<tr bgcolor=\"CCCCCC\">
					<td align=\"center\"><b>Order # Optipro</b></td>
					<td align=\"center\"><b>Store</b></td>
					<td align=\"center\"><b>Patient</b></td>
					<td align=\"center\"><b>Product</b></td>
					</tr>";
					
	while ($listItem = mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
	
	
			
		switch($listItem["user_id"]){
			/*
			case 'garantieatoutcasser' :       			   	$Succursale = 'Garantieatoutcasser';            break;
			case 'redoifc' :       							$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       						$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       					$Succursale = 'Compte de reprise Interne Stc';  break;
			*
			case '88403':   $Succursale = '#88403-Bloor St';		break;
			case '88408':   $Succursale = '#88408-Oshawa';			break;
			case '88409':   $Succursale = '#88409-Eglinton';		break;
			case '88411':   $Succursale = '#88411-Sherway';			break;
			case '88414':   $Succursale = '#88414-Yorkdale';		break;
			case '88416':   $Succursale = '#88416-Vancouver DTN';	break;
			case '88431':   $Succursale = '#88431-Calgary DTN';		break;
			case '88433':   $Succursale = '#88433-Polo Park';		break;
			case '88434':   $Succursale = '#88434-Market Mall';		break;
			case '88435':   $Succursale = '#88435-West Edmonton';	break;
			case '88438':   $Succursale = '#88438-Metrotown';		break;
			case '88439':   $Succursale = '#88439-Langley';			break;
			case '88440':   $Succursale = '#88440-Rideau';			break;
			case '88444':   $Succursale = '#88444-Mayfair';			break;
			case '88666':   $Succursale = '#88666-GR';				break;
			default:  		$Succursale = 'ERREUR';
		}
	
			$nbrResultat =  $nbrResultat+1;
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td align=\"center\">$listItem[order_num_optipro]</td>
						   <td align=\"center\">$Succursale</td>
						   <td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   </td>
					   </tr>";
	}//END WHILE
				
	$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\"><b>Number of orders in your basket: $nbrResultat</b></td></tr></table>";
	
	//SEND EMAIL
	//$send_to_address = array('rapports@direct-lens.com');	//le temps des tests	
	
	echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie: Orders in your basket";
	echo '<br>'.$message;
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
		echo 'reussi';
		}else{
			echo 'echec';
		}	
						
			
}//End For
*/
?>