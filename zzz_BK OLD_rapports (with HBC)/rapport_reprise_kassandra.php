<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


ini_set('MAX_EXECUTION_TIME', -1);
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');



$datedujour  		= mktime(0,0,0,date("m"),date("d"),date("Y"));
$aujourdhui     	= date("Y/m/d", $datedujour);
echo '<br>Date du jour:'. $aujourdhui;

$MoisEnCours 	= date("m", $datedujour);
 
$MoisEnCours=$MoisEnCours-1;//Pour sélectionner le mois qui vient de se terminer
$AnneeEnCours  = date("Y", $datedujour); 
if ($MoisEnCours==0){
	$MoisEnCours=12;	
	//$AnneeEnCours = $AnneeEnCours-1;
}
//$MoisEnCours=4;
switch($MoisEnCours){
		/*case 1:	$JourDebut="01-01";	$JourFin="01-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Janvier 
		case 2: $JourDebut="02-01";	$JourFin="02-29";	$AnneeEnCours = $AnneeEnCours  ;	break; //Février
		case 3: $JourDebut="03-01";	$JourFin="03-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Mars
		case 4: $JourDebut="04-01";	$JourFin="04-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Avril
		case 5: $JourDebut="05-01";	$JourFin="05-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Mai
		case 6: $JourDebut="06-01";	$JourFin="06-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Juin
		case 7: $JourDebut="07-01";	$JourFin="07-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Juillet
		case 8: $JourDebut="08-01";	$JourFin="08-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Août
		case 9: $JourDebut="09-01";	$JourFin="09-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Septembre
		case 10:$JourDebut="10-01";	$JourFin="10-31";	$AnneeEnCours = $AnneeEnCours  ;	break; //Octobre
		case 11:$JourDebut="11-01";	$JourFin="11-30";	$AnneeEnCours = $AnneeEnCours  ;	break; //Novembre
		case 12:$JourDebut="12-01";	$JourFin="12-31";	$AnneeEnCours = $AnneeEnCours-1;	break; //Décembre	*/
		
		
		//Programmation pour matcher avec les projections de semaines par  Jean Lachance
		case 1:	$JourDebut="01-01";	$JourFin="01-30";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 1-4 
		case 2: $JourDebut="01-31";	$JourFin="02-27";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 5-8
		case 3: $JourDebut="02-28";	$JourFin="04-03";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 9-13
		case 4: $JourDebut="04-04";	$JourFin="05-01";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 14-17
		case 5: $JourDebut="05-02";	$JourFin="05-29";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 18-21
		case 6: $JourDebut="05-30";	$JourFin="07-03";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 22-26
		case 7: $JourDebut="07-04";	$JourFin="07-31";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 27-30
		case 8: $JourDebut="08-01";	$JourFin="08-28";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 31-34
		case 9: $JourDebut="08-29";	$JourFin="10-02";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 35-39
		case 10:$JourDebut="10-03";	$JourFin="10-30";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 40-43
		case 11:$JourDebut="10-31";	$JourFin="11-27";	$AnneeEnCours = $AnneeEnCours;		break; //Semaine 44-47
		case 12:$JourDebut="11-28";	$JourFin="12-31";	$AnneeEnCours = $AnneeEnCours-1;	break; //Semaine 48-52
		
}

echo '<br>Mois en cours:'. $MoisEnCours;
echo '<br>JourDebut:'. $JourDebut;
echo '<br>JourFin:'. $JourFin.'<br><br>';




//$tt= $_REQUEST[tt];
$tt=1;
echo ' Value of TT:'.$tt;

for ($i = $tt; $i <= 14; $i++) {
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
		
	case  5: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  6: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Partie = 'Sherbrooke'; 			  
	 $send_to_address = array('rapports@direct-lens.com');   break;
	 
	case  7: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Partie = 'Chicoutimi';		       
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  8: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Partie = 'Lévis';      			   
	$send_to_address = array('rapports@direct-lens.com');         break;
	
	case 9: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Partie = 'Longueuil';  			   
	$send_to_address = array('rapports@direct-lens.com');     break;
	
	case 10: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Partie = 'Granby';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
		
	case 11: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";             $Partie = 'Québec';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case 12: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";             $Partie = 'Montréal';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case 13: $Userid =  " orders.user_id IN ('gatineau','gatineausafe')";             $Partie = 'Gatineau';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case 14: $Userid =  " orders.user_id IN ('stjerome','stjeromesafe')";             $Partie = 'St-Jérôme';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
}//End Switch

	
	$time_start  = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT * FROM orders WHERE $Userid AND redo_order_num IS NOT NULL
	AND order_date_shipped BETWEEN  '$AnneeEnCours-$JourDebut' AND '$AnneeEnCours-$JourFin'";
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
						<td align=\"center\"><b># Reprise</b></td>
						<td align=\"center\"><b># Originale</b></td>
						<td align=\"center\"><b># Optipro (Originale)</b></td>
						<td align=\"center\"><b>Raison de reprise</b></td>
						<td align=\"center\"><b>Produit (Original)</b></td>
						<td align=\"center\"><b>Produit Reprise</b></td>
						<td align=\"center\"><b>Fournisseur (Original)</b></td>
						<td align=\"center\"><b>Opticien (Original)</b></td>
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
			
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td height=\"150\" align=\"center\">$listItem[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num_optipro]</td>
						   <td align=\"center\">$DataRedoReason[redo_reason_fr]</td>
						   <td align=\"center\">$DataOriginal[order_product_name]</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   <td align=\"center\">$Fournisseur</td>
						   <td align=\"center\">$listItem[opticien]</td>
					   </tr>";
	}//END WHILE
	echo $message;
	//exit();
	

	//SEND EMAIL
	$send_to_address = array('rapports@direct-lens.com');	
	//$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".$send_to_address;	
		
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "Rapport de reprises  $AnneeEnCours-$JourDebut au $AnneeEnCours-$JourFin: ". $Partie;

	//exit();
	if ($ordersnum > 0){
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	echo '<br>'; 
	var_dump($send_to_address);
	echo '<br>'; 
	
	if($response){ 
			echo 'Fonctionne';
			//log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			echo 'Erreur..';
			//log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}	
	}
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
		
		
		
	//exit();
			
}//End For
?>