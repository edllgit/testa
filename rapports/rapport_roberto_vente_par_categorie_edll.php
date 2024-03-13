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

$date1 = "2021-01-01";
$date2 = "2021-10-18";




for ($i = 1; $i <= 16; $i++) {

   // echo '<br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Partie = 'Trois-Rivieres';				break;       
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Partie = 'Drummondville';				break; 	   
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Partie = 'Halifax'; 						break; 	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Partie = 'Laval';						break; 	   
	case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 					break; 	   
	case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Partie = 'Sherbrooke'; 					break; 	  
	case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Partie = 'Chicoutimi';		 			break;      
	case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Partie = 'Lévis';      			   		break;   
	case  9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Partie = 'Longueuil';  			   		break;   
	case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Partie = 'Granby';  				   		break;   
	case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";     $Partie = 'Québec';  				  		break;   
	case 12: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";         $Partie = 'St-Jérôme';  				 	break;     
	case 13: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";         $Partie = 'Gatineau';						break;   

	case 14: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";     $Partie = 'Edmundston';                   break;
	case 15: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";   $Partie = 'Fredericton';  				break;
	case 16: $Userid =  " orders.user_id IN ('88666')";      					  $Partie = '#88666-GR';           			break;

	
}//End Switch

	$time_start = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");

	$rptQueryStock    = "SELECT count(order_num) as NbrOrder_Stock,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND (order_product_name like '%stock%' OR order_product_name like '%plano sv%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryStock .'<br>';
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 7: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptQueryStock);
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	$NbrOrderStock = $listItemStock[NbrOrder_Stock];
	
	$rptQuerySVRX   = "SELECT count(order_num) as NbrOrder_SVRX,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND order_product_name NOT like '%stock%' 
	AND (order_product_name like '%single vision%' OR order_product_name like '%SV RX%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQuerySVRX .'<br>';
	$rptResultSVRX=mysqli_query($con,$rptQuerySVRX)		or die  ('I cannot select items because 7d: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptQuerySVRX);
	$listItemSVRX = mysqli_fetch_array($rptResultSVRX,MYSQLI_ASSOC);
	$NbrOrderSVRX = $listItemSVRX[NbrOrder_SVRX];
	
	
	$rptQueryGood   = "SELECT count(order_num) as NbrOrder_Good,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND order_product_name NOT like '%stock%' AND order_product_name NOT like '%single vision%'
	AND (order_product_name like '%digital progressive IOT%' OR order_product_name like '%optotech%' OR order_product_name like '%internet/mas%' OR order_product_name like '%Precision 1.%' OR order_product_name like '%K-ONE PROG%' OR order_product_name like '%Promotion Progressif%' OR order_product_name like '%Digital Progressive 1.%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryGood .'<br>';
	$rptResultGood=mysqli_query($con,$rptQueryGood)		or die  ('I cannot select items because 7c: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptQueryGood);
	$listItemGood = mysqli_fetch_array($rptResultGood,MYSQLI_ASSOC);
	$NbrOrderGood = $listItemGood[NbrOrder_Good];
	
		
	$rptQueryBetter   = "SELECT count(order_num) as NbrOrder_Better,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND order_product_name NOT like '%stock%' AND order_product_name NOT like '%single vision%'
	AND (order_product_name like '%HD IOT%' OR order_product_name like '%alpha hd%' 
	OR order_product_name like '%ultimate%' OR order_product_name like '%Precision+ 1.%'
	OR order_product_name like '%RDTK%' OR order_product_name like '%Progressive HD%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryBetter .'<br>';
	$rptResultBetter=mysqli_query($con,$rptQueryBetter)		or die  ('I cannot select items because 7a: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptQueryBetter);
	$listItemBetter = mysqli_fetch_array($rptResultBetter,MYSQLI_ASSOC);
	$NbrOrderBetter = $listItemBetter[NbrOrder_Better];
	
	
	$rptQueryMaxiwide   = "SELECT count(order_num) as NbrOrder_Maxiwide,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) 
	AND (order_product_name like '%maxiwide%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryMaxiwide .'<br>';
	$rptResultMaxiwide=mysqli_query($con,$rptQueryMaxiwide)		or die  ('I cannot select items because 7b: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResultMaxiwide);
	$listItemMaxiwide = mysqli_fetch_array($rptResultMaxiwide,MYSQLI_ASSOC);
	$NbrOrderMaxiwide = $listItemMaxiwide[NbrOrder_Maxiwide];
	
	
	$rptQueryBest   = "SELECT count(order_num) as NbrOrder_Best,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) 
	AND (order_product_name like '%4k%' OR order_product_name like '%4d%' OR order_product_name like '%ifree%' OR order_product_name like '%Precision+ 360%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryBest .'<br>';
	$rptResultBest=mysqli_query($con,$rptQueryBest)		or die  ('I cannot select items because 7b: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResultBest);
	$listItemBest = mysqli_fetch_array($rptResultBest,MYSQLI_ASSOC);
	$NbrOrderBest = $listItemBest[NbrOrder_Best];
	
	
	$rptQueryOffice  = "SELECT count(order_num) as NbrOrder_Office,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) AND order_product_name NOT like '%stock%' AND order_product_name NOT like '%single vision%'
	AND (order_product_name like '%office%' OR order_product_name like '%reader%' or order_product_name like '%room%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryOffice .'<br>';
	$rptResultOffice=mysqli_query($con,$rptQueryOffice)		or die  ('I cannot select items because 7y: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptQueryOffice);
	$listItemOffice = mysqli_fetch_array($rptResultOffice,MYSQLI_ASSOC);
	$NbrOrderOffice = $listItemOffice[NbrOrder_Office];
	
	
	$QueryBifocal  = "SELECT count(order_num) as NbrOrder_Bifocal,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59)  
	AND (order_product_name like '%28%' OR order_product_name like '%35%' OR order_product_name like '%22%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $QueryBifocal .'<br>';
	$rptResultBifocal=mysqli_query($con,$QueryBifocal)		or die  ('I cannot select items because 7z: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResultBifocal);
	$listItemBifocal = mysqli_fetch_array($rptResultBifocal,MYSQLI_ASSOC);
	$NbrBifocal 	 = $listItemBifocal[NbrOrder_Bifocal];
	
	
	$rptQueryFatigue   = "SELECT count(order_num) as NbrOrder_Fatigue,  orders.prescript_lab, orders.order_num_optipro, orders.order_num, orders.order_product_name, accounts.main_lab, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.frame_sent_hko, orders.frame_sent_swiss, orders.user_id, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,  orders.order_status, orders.internal_note from orders, accounts  WHERE $Userid   AND lab in (66,67,59) 
	AND (order_product_name like '%fatigue%' OR order_product_name like '%irelax%' OR order_product_name like '%reader%')
	AND order_date_processed BETWEEN '$date1' and '$date2'	AND accounts.user_id = orders.user_id  and orders.order_status NOT IN ('cancelled','on hold')
	AND redo_order_num is null
	ORDER BY order_status asc, order_date_processed";
	//echo '<br>'. $rptQueryBest .'<br>';
	$rptResultFatigue=mysqli_query($con,$rptQueryFatigue)		or die  ('I cannot select items because 7o: <br><br>' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResultFatigue);
	$listItemFatigue = mysqli_fetch_array($rptResultFatigue,MYSQLI_ASSOC);
	$NbrOrderFatigue = $listItemFatigue[NbrOrder_Fatigue];
	
	
	$count=0;

	if ($i==1){
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
					<td align=\"center\"><b>Magasin</b></td>
					<td align=\"center\"><b>Stock</b></td>
					<td align=\"center\"><b>SV RX</b></td>
					<td align=\"center\"><b>GOOD</b></td>
					<td align=\"center\"><b>BETTER</b></td>
					<td align=\"center\"><b>BEST</b></td>
					<td align=\"center\"><b>MAXIWIDE</b></td>
					<td align=\"center\"><b>OFFICE</b></td>
					<td align=\"center\"><b>BIFOCAL</b></td>
					<td align=\"center\"><b>ANTI FATIGUE</b></td>
					</tr>";
					
		}
					
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
				
		switch($listItemStock["user_id"]){
			case 'entrepotifc' :  	case 'entrepotsafe' :     	$Succursale = 'Trois-Rivieres';   				break;
			case 'entrepotdr' :   	case 'safedr': 		     	$Succursale = 'Drummondville';   			 	break;
			case 'warehousehal' : 	case 'warehousehalsafe' :	$Succursale = 'Halifax';   	   					break;
			case 'laval' :        	case 'lavalsafe' :     	 	$Succursale = 'Laval';   		  				break;
			case 'terrebonne' :   	case 'terrebonnesafe' : 	$Succursale = 'Terrebonne';       				break;
			case 'sherbrooke' :   	case 'sherbrookesafe' : 	$Succursale = 'Sherbrooke';       				break;
			case 'chicoutimi' :   	case 'chicoutimisafe' : 	$Succursale = 'Chicoutimi';       				break;
			case 'levis' :        	case 'levissafe' : 			$Succursale = 'Lévis';   		   				break;
			case 'granby' :       	case 'granbysafe' : 		$Succursale = 'Granby';   		   				break;
			case 'longueuil' :    	case 'longueuilsafe' :    	$Succursale = 'Longueuil';        			    break;
			//case 'montreal' :     	case 'montrealsafe' :     	$Succursale = 'Montreal ZT1';        			break;
			case 'stjerome' :     	case 'stjeromesafe' :     	$Succursale = 'St-Jérome ZT';        			break;
			case 'gatineau' :     	case 'gatineausafe' :     	$Succursale = 'Gatineau';        				break;
			case 'edmundston' :   	case 'edmundstonsafe' :   	$Succursale = 'Edmundston';        				break;
			case 'entrepotquebec':  case 'quebecsafe'    :    	$Succursale = 'Québec';        					break;

			case 'fredericton':     case 'frederictonsafe'    : $Succursale = 'Fredericton';        			break;
			case '88666':           case '88666'    :    	    $Succursale = '#88666 Griffe';        			break;

			case 'entrepotquebec':  case 'quebecsafe'    :    	$Succursale = 'Québec';        					break;

			case 'garantieatoutcasser' :       			   		$Succursale = 'Garantieatoutcasser';            break;
			case 'redoifc' :       								$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       							$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       						$Succursale = 'Compte de reprise Interne Stc';  break;
			default:  											$Succursale = 'ERREUR';
		}
	
			$nbrResultat =  $nbrResultat+1;
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td align=\"center\">$Succursale</td>
						   <td align=\"center\">$NbrOrderStock</td>
						   <td align=\"center\">$NbrOrderSVRX</td>
						   <td align=\"center\">$NbrOrderGood</td>
						   <td align=\"center\">$NbrOrderBetter</td>
						   <td align=\"center\">$NbrOrderBest</td>
						   <td align=\"center\">$NbrOrderMaxiwide</td>
						   <td align=\"center\">$NbrOrderOffice</td>
						   <td align=\"center\">$NbrBifocal</td>
						   <td align=\"center\">$NbrOrderFatigue</td>
						   </td>
					   </tr>";
				
}//End For



$message.=" 
<tr><td></td></tr>

  <tr>
	<td colspan=\"10\"><b>GOOD</b> Includes: Digital Progressive IOT, Optotech, Internet/MAS, Precision,K-ONE PROG, Promotion Progressif, Digital Progressive </td>
  </tr>
  
   <tr>
  <td  colspan=\"10\"><b>BETTER</b> Includes: HD IOT, Alpha HD, Ultimate, Precision+, RDTK, Progressive HD </td>
  </tr>
  
   <tr>
  <td  colspan=\"10\"><b>BEST</b> Includes: 4K, Alpha 4D, iFree, Precision+ 360 </td>
  </tr>
  
   <tr>
  <td  colspan=\"10\"><b>OFFICE</b> Includes: Office,  iReader, iRoom </td>
  </tr>
  
   <tr>
  <td  colspan=\"10\"><b>BIFOCAL</b> Includes: FT28, FT35, RD22  </td>
  </tr></table>";

	//SEND EMAIL
	$send_to_address = array('rapports@direct-lens.com');	
	


	//echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "Rapport Roberto Edll par categorie de produits $date1 - $date2";
	//echo '<br>'.$message;
	//exit();
	
	if ($nbrResultat > 0)
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<br>'; 
	//var_dump($send_to_address);
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

	// Générer le contenu HTML du rapport


	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');

	$nomFichier = 'r_roberto_vente_par_catégorie_edll_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);


	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
		
		if($response){ 
		echo 'reussi';
		}else{
			echo 'echec';
		}							
			

?>