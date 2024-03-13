<?php
//TODO OPTIONEL: AJOUTER DANS CE RAPPORT L'OPTION DE GÉNÉRER POUR TOUS LES MAGASINS D'UN CLIQUE, SANS DEVOIR REFAIRE LA SELECTION POUR CHAQUE SUCCURSALE

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
ini_set('max_execution_time', 0);
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$month = $_REQUEST[month];
$year  = $_REQUEST[year];

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
	

echo '<br><strong>Données utilisés pour générer ce rapport:</strong>';
echo '<br><strong>Mois</strong>: '. $month . ' ' . $year.'<br><br>';

	for ($i =1; $i <= 7 ; $i++) {
		echo '<br> Magasin: '. $i;
		switch($i){
			
			
			case  1: $Userid =  " orders.user_id IN ('edmundston','edmundstonsafe')";    		$Partie = 'Edmundston';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			
			case  2: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')";    	$Partie = 'Halifax';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			
			case  3: $Userid =  " orders.user_id IN ('vaudreuil','vaudreuilsafe')";    			$Partie = 'Vaudreuil';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			
			case  4: $Userid =  " orders.user_id IN ('sorel','sorelsafe')";    					$Partie = 'Sorel';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			case  5: $Userid =  " orders.user_id IN ('moncton','monctonsafe')";    				$Partie = 'Moncton';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			case  6: $Userid =  " orders.user_id IN ('fredericton','frederictonsafe')";    		$Partie = 'Fredericton';	       
			$send_to_address = array('rapports@direct-lens.com');
			break;
			
			case  7: $Userid =  " orders.user_id IN ('88666')";    								$Partie = 'Griffé Trois-Rivieres';	       
			//Fichier de connexion BD HBC (Pour accéder aux ventes de Griffé-TR)
			include('../connexion_hbc.inc.php');
			$send_to_address = array('rapports@direct-lens.com');
			break;
							
		}//End Switch
	
	

		echo '<br>USER ID: '. $Userid;	
		$subject= "Rapport commandes par KNR: $month $year pour $Partie";
		echo '<br>Subject:'. $subject;
		echo '<br>Dates: '. $date1 . ' - ' . $date2;
				
		$rptQuery="
		SELECT user_id, order_num, lab, order_date_shipped, knr_ref_num, order_product_name, order_product_id, order_total
		FROM orders
		WHERE $Userid
		AND prescript_lab =73
		AND order_date_shipped BETWEEN '$date1' and '$date2'";
		
		echo '<br><br>'. $rptQuery;
		$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$ordersnum=mysqli_num_rows($rptResult);
		
		
		//Préparer le courriel
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

			$message.="<body><table border=\"1\" width=\"720\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
			
			$message.="<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">Compte</td>
					<td align=\"center\"># Commande</td>
					<td align=\"center\">Lab</td>
					<td align=\"center\">Date Shipped</td>
					<td align=\"center\">KNR Ref Num</td>
					<td align=\"center\">Product</td>
					<td align=\"center\">Product ID</td>
					<td align=\"center\">Order Total</td>
					</tr>";
					
	
			$GrandTotal = 0;	
			
			while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$GrandTotal = $GrandTotal + $listItem["order_total"];

			
			$rptQueryFrame="SELECT supplier, temple_model_num, color FROM extra_product_orders
			WHERE  order_num= $listItem[order_num]
			AND category='Frame'";
			$resultFrame = mysqli_query($con,$rptQueryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);
				
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";

			$OrderTotalFormatter= number_format($listItem[order_total],2,',',' ');
				
				$message.="
				<tr bgcolor=\"$bgcolor\">
					<td align=\"center\">$listItem[user_id]</td>
					<td align=\"center\">$listItem[order_num]</td>
					<td align=\"center\">$listItem[lab]</td>
					<td align=\"center\">$listItem[order_date_shipped]</td>
					<td align=\"center\">$listItem[knr_ref_num]</td>
					<td align=\"center\">$listItem[order_product_name]</td>
					<td align=\"center\">$listItem[order_product_id]</td>
					<td align=\"center\">$listItem[order_total]$</td>
				</tr>";
	
			}//END WHILE
			$GrandTotal= number_format($GrandTotal,2,',',' ');
			
			$message.="<tr><td colspan=\"6\">&nbsp;</td><td align=\"right\"><b>Grand Total:</b></td><td align=\"right\"><b>$GrandTotal$</b></td></tr></table>";
			//echo '<br><br>'. $message.'<br><br>';
			
	//SEND EMAIL TESTS REMETTRE EN COMMENTAIRE
	//$send_to_address = array('rapports@direct-lens.com');	
	

	//echo "<br>".$send_to_address;
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
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
			echo '<h3>Résultat:Courriel envoyé avec succès aux adresses:'. $EmailEnvoyerA.'</h3><br><br>';
			//log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			echo '<h3>Résultat:Erreur durant l\'envoie du courriel, svp aviser Charles</h3>';
			//log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}	
			
	}//Fin du For

	
	
}//End if there are results

?>