<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);     



for ($i = 1; $i <= 19; $i++) {
    echo '<br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Partie = 'Trois-Rivieres';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Partie = 'Drummondville';		   
	$send_to_address = array('rapports@direct-lens.com'); break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Partie = 'Halifax'; 				  
	 $send_to_address = array('rapports@direct-lens.com');          break;
	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Partie = 'Laval';				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case  5: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";         $Partie = 'Montreal HBC Zone Tendance 1';  
	$send_to_address = array('rapports@direct-lens.com');     break;
	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  7: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Partie = 'Sherbrooke'; 			  
	 $send_to_address = array('rapports@direct-lens.com');   break;
	 
	case  8: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     			$Partie = 'Chicoutimi';		       
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  9: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  			$Partie = 'Lévis';      			   
	$send_to_address = array('rapports@direct-lens.com');         break;
	
	case 10: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       			$Partie = 'Longueuil';  			   
	$send_to_address = array('rapports@direct-lens.com');     break;
	
	case 11: $Userid =  " orders.user_id IN ('granby','granbysafe')";             			$Partie = 'Granby';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
		
	case 12: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";             	$Partie = 'Québec';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;	
	
	case 13: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";             		$Partie = 'St-Jérôme';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;	
	
	case 14: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";             		$Partie = 'Gatineau';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;	
	
	case 15: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";             	$Partie = 'Edmundston';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;	
	
	case 16: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";             	$Partie = 'Vaudreuil';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;	
	
	case 17: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";             			$Partie = 'Sorel';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case 18: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";             		$Partie = 'Moncton';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case 19: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";             $Partie = 'Fredericton';
	$send_to_address = array('rapports@direct-lens.com');        break;
}//End Switch

	
	$time_start = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND accounts.user_id = orders.user_id  and orders.order_status IN ('basket')  ORDER BY order_status asc, order_date_processed";
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
					<td colspan=\"8\" align=\"center\">Vous avez pr&eacute;sentement plusieurs commandes dans votre panier d'achat en attente de validation. <br><b>Merci de les valider le plus rapidement possible afin de démarrer la production  et  donc &eacute;viter des d&eacute;lais suppl&eacute;mentaires pour la fabrication de ces commandes.</b></td>
					</tr>
					
					<tr bgcolor=\"CCCCCC\">
					<td align=\"center\"><b># Optipro</b></td>
					<td align=\"center\"><b>Entrepot</b></td>
					<td align=\"center\"><b>Patient</b></td>
					<td align=\"center\"><b>Produit</b></td>
					</tr>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
	
	
			
		switch($listItem["user_id"]){
			case 'entrepotifc' :  case 'entrepotsafe' :     $Succursale = 'Trois-Rivieres';   				break;
			case 'entrepotdr' :   case 'safedr': 		    $Succursale = 'Drummondville';   			 	break;
			case 'warehousehal' : case 'warehousehalsafe' : $Succursale = 'Halifax';   	   					break;
			case 'laval' :        case 'lavalsafe' :        $Succursale = 'Laval';   		  				break;
			case 'terrebonne' :   case 'terrebonnesafe' :   $Succursale = 'Terrebonne';       				break;
			case 'sherbrooke' :   case 'sherbrookesafe' : 	$Succursale = 'Sherbrooke';       				break;
			case 'chicoutimi' :   case 'chicoutimisafe' :   $Succursale = 'Chicoutimi';       				break;
			case 'levis' :        case 'levissafe' : 		$Succursale = 'Lévis';   		   				break;
			case 'granby' :       case 'granbysafe' : 		$Succursale = 'Granby';   		   				break;
			case 'longueuil' :    case 'longueuilsafe' :    $Succursale = 'Longueuil';        			    break;
			case 'montreal' :     case 'montrealsafe' :     $Succursale = 'Montreal ZT1';        			break;
			case 'stjerome' :     case 'stjeromesafe' :     $Succursale = 'St-Jérome ZT';        			break;
			case 'gatineau' :     case 'gatineausafe' :     $Succursale = 'Gatineau';        				break;
			case 'edmundston' :   case 'edmundstonsafe' :   $Succursale = 'Edmundston';        				break;
			case 'sorel' :   	  case 'sorelsafe' :   		$Succursale = 'Sorel';        					break;
			case 'vaudreuil' :    case 'vaudreuilsafe' :    $Succursale = 'Vaudreuil';        				break;
			case 'moncton' :      case 'monctonsafe' :  	$Succursale = 'Moncton';        				break;
			case 'entrepotquebec' :   case 'quebecsafe'  :  $Succursale = 'Québec';        					break;
			case 'fredericton' :  case 'frederictonsafe'  : $Succursale = 'Fredericton';        			break;
			case 'garantieatoutcasser' :       			   	$Succursale = 'Garantieatoutcasser';            break;
			case 'redoifc' :       							$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       						$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       					$Succursale = 'Compte de reprise Interne Stc';  break;
			default:  										$Succursale = 'ERREUR';
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
				
	$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\"><b>Nombre de commande(s) dans votre Panier: $nbrResultat</b></td></tr></table>";
	
	//SEND EMAIL
	//$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie: Vos commandes dans le panier";
	echo '<br>'.$message;
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
		echo 'reussi';
		}else{
			echo 'echec';
		}	
						
			
}//End For

$time_end = microtime(true);
$time     = $time_end - $time_start;
$today    = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery  = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip)
				VALUES('Rapport commandes Edll dans le  Panier 2.0', '$time','$today','$timeplus3heures','rapport_commandes_edll_basket.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
?>