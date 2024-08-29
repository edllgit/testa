<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);	





for ($i = 1; $i <= 22; $i++) {
    echo '<br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Partie = 'Trois-Rivieres';	       
	$send_to_address = array('rapports@direct-lens.com');

	break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Partie = 'Drummondville';		   
	$send_to_address = array('rapports@direct-lens.com'); 

	break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Partie = 'Halifax'; 				  
	 $send_to_address = array('rapports@direct-lens.com');          

	 break;
	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Partie = 'Laval';				   
	$send_to_address = array('rapports@direct-lens.com');        

	break;
	
	/*case  5: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";         $Partie = 'Montreal HBC Zone Tendance 1';  
	$send_to_address = array('rapports@direct-lens.com');    
	//ob_start();
	break;*/
	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com');    

	break;
	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com');    

	break;
	
	case  7: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Partie = 'Sherbrooke'; 			  
	 $send_to_address = array('rapports@direct-lens.com');   

	 break;
	 
	case  8: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Partie = 'Chicoutimi';		       
	$send_to_address = array('rapports@direct-lens.com');    

	break;
	
	case  9: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Partie = 'Lévis';      			   
	$send_to_address = array('rapports@direct-lens.com');         

	break;

	
	case 10: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Partie = 'Longueuil';  			   
	$send_to_address = array('rapports@direct-lens.com');     

	break;

	
	case 11: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Partie = 'Granby';  				   
	$send_to_address = array('rapports@direct-lens.com');        

	break;
		
	case 12: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";             $Partie = 'Québec';  				   
	$send_to_address = array('rapports@direct-lens.com');        

	break;

	
	case 13: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";         $Partie = 'Gatineau';  				   
	$send_to_address = array('rapports@direct-lens.com');        

	break;
	
	case 14: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";         $Partie = 'St-Jérôme';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;
	
	case 15: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";         $Partie = 'Edmundston';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;
	
	case 16: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";         $Partie = 'Vaudreuil';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;

	
	case 17: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";        		 	$Partie = 'Sorel';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;
	
	case 18: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";        		 	$Partie = 'Moncton';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;

	case 19: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";        	$Partie = 'Fredericton';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;

	case 20: $Userid =  " orders.user_id IN ('88666','88666')";        	$Partie = '#88666-GR';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	break;


	case 21: $Userid =  " orders.user_id IN ('stjohn','stjohnsafe')";        	$Partie = 'stjohn';  				   
	$send_to_address = array('rapports@direct-lens.com');      

	case 22: $Userid =  " orders.user_id IN ('dartmouth','dartmouthsafe')";        	$Partie = 'dartmouth';  				   
	$send_to_address = array('rapports@direct-lens.com');   

	break;
}//End Switch

	
	$time_start = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, 
	accounts.main_lab, accounts.company, orders.order_date_processed,orders.redo_order_num, orders.order_item_date,  
	orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, 
	orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last,
	orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts 
	WHERE $Userid  
	AND lab in (66,67,59)
	AND accounts.user_id = orders.user_id  and orders.order_status IN ('on hold')
	AND orders.redo_order_num is not null
	ORDER BY order_status asc, order_date_processed";
	
	// juste pour le test de Fawaz
	/*$rptQuery    =  "SELECT  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, 
	accounts.main_lab, accounts.company, orders.order_date_processed,orders.redo_order_num, orders.order_item_date,  
	orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, 
	orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last,
	orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts 
	WHERE $Userid  
	AND lab in (66,67,59)
	AND accounts.user_id = orders.user_id  and orders.order_status IN ('filled')
	AND orders.redo_order_num is not null
	ORDER BY order_status asc, order_date_processed"; */ 
	
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
			$message.="<tr bgcolor=\"CCCCCC\">
					<td colspan=\"8\" align=\"center\">Voici la liste de vos commandes qui sont en Hold.(Soit des reprises non terminées, soit des commandes que vous nous avez demandé de mettre en attente). Merci de canceller les commandes qui doivent l'&ecirc;tre.</td>
					</tr>
					
					<tr bgcolor=\"CCCCCC\">
					<td align=\"center\"><b># Commande</b></td>
					<td align=\"center\"><b># Optipro</b></td>
					<td align=\"center\"><b>Entrepot</b></td>
					<td align=\"center\"><b>Patient</b></td>
					<td align=\"center\"><b>Date Commande</b></td>
					<td align=\"center\"><b>Produit</b></td>
					<td align=\"center\"><b>Canceller la commande</b></td>
					</tr>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
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
			case 'waiting for frame knr':	$list_order_status = "Attente de monture KNR";	break;		
			case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
			case 're-do':					$list_order_status = "Redo";					break;
			case 'in transit':				$list_order_status = "In Transit";				break;
			case 'filled':					$list_order_status = "Shipped";					break;
			case 'basket':					$list_order_status = "Basket";					break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 		break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default: 						$list_order_status = "UNKNOWN"; 		
		}
			
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
			case 'entrepotquebec' : case 'quebecsafe'    :    $Succursale = 'Québec';        			   	break;
			//case 'montreal':      case 'montrealsafe':      $Succursale = 'MTL ZT1';        			   	break;
			case 'gatineau':      case 'gatineausafe':      $Succursale = 'Gatineau';        			   	break;
			case 'stjerome':      case 'stjeromesafe':      $Succursale = 'St-Jerome';        			   	break;
			case 'edmundston':    case 'edmundston':        $Succursale = 'Edmundston';        			   	break;
			case 'vaudreuil':     case 'vaudreuilsafe':     $Succursale = 'Vaudreuil';        			   	break;
			case 'sorel':     	  case 'sorelsafe':     	$Succursale = 'Sorel';        			   		break;
			case 'moncton':       case 'monctonsafe':     	$Succursale = 'Moncton';        			   	break;
			case 'fredericton':   case 'frederictonsafe':   $Succursale = 'Fredericton';        			break;
			case 'stjohn':   	  case 'stjohnsafe':        $Succursale = 'stjohn';        			        break;
			case 'dartmouth':     case 'dartmouthsafe':     $Succursale = 'dartmouth';        			    break;
			case '88666':       case '88666':     	        $Succursale = '#88666 Griffé';        			break;
			case 'garantieatoutcasser' :       			   	$Succursale = 'Garantieatoutcasser';            break;
			case 'redoifc' :       							$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       						$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       					$Succursale = 'Compte de reprise Interne Stc';  break;
			default:  										$Succursale = 'ERREUR';
		}
	
			$nbrResultat =  $nbrResultat+1;
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td height=\"150\" align=\"center\">$listItem[order_num]</td>
						   <td align=\"center\">$listItem[order_num_optipro]</td>
						   <td align=\"center\">$Succursale</td>
						   <td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>
						   <td align=\"center\">$listItem[order_date_processed]</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   <td align=\"center\"><a href=\"http://www.direct-lens.com/labAdmin/cancellation_edll.php?order_num=$listItem[order_num]&order_num_optipro=$listItem[order_num_optipro]\">Canceller cette commande</a></td>
					   </tr>";
	}//END WHILE
				
	$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\"><b>Nombre de commande(s) en 'Hold': $nbrResultat</b></td></tr></table>";
	
	//SEND EMAIL
	//$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".var_dump($send_to_address);	
	echo '<br>'. $message;	
	//echo "<br>".$to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie: Vos commandes en 'Hold'";
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
			echo 'Reussi';
		}else{
			echo 'Echec';
		}	 
		

	// Générer le contenu HTML du rapport


	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');

	$nomFichier = 'r_commande_en_hold_edll_'.$Partie . $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Wait/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);



	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';

	$time_end = microtime(true);
	$time = $time_end - $time_start;
	//echo "Execution time:  $time seconds\n";
	$today = date("Y-m-d");// current date
	$timeplus3heures 	   = date("H:i:s");
	$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$ips				   = $ip  . ' ' .$ip2 ;
	$CronQuery  = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip)
				   VALUES('Rapport commandes en attente EDLL 2.0', '$time','$today','$timeplus3heures','rapport_commandes_en_hold_edll.php','$ips')"; 					
	$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
						
}//End For
?>