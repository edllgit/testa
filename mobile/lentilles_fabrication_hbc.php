<?php
//TODO OPTIONEL: AJOUTER DANS CE RAPPORT L'OPTION DE GÉNÉRER POUR TOUS LES MAGASINS D'UN CLIQUE, SANS DEVOIR REFAIRE LA SELECTION POUR CHAQUE SUCCURSALE

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
//ini_set('display_errors', '1');
ini_set('max_execution_time', 0);
include("../connexion_hbc.inc.php");
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

	for ($i = 1; $i <= 16 ; $i++) {
		echo '<br> Magasin: '. $i;
		switch($i){
			case  1: $Userid =  " orders.user_id IN ('88666')";    	$Partie = 'Griffé Lunetier TR';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  2: $Userid =  " orders.user_id IN ('88403')";    $Partie = 'HBC #88403-Bloor';	       
			$send_to_address = array('rapports@direct-lens.com');break;//Eric recoit ce rapport (pour les 21 HBC pour le moment)
			
			case  3: $Userid =  " orders.user_id IN ('88408')";    	$Partie = 'HBC #88408-Oshawa';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  4: $Userid =  " orders.user_id IN ('88409')";    	$Partie = 'HBC #88409-Eglinton';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  5: $Userid =  " orders.user_id IN ('88411')";    	$Partie = 'HBC #88411-Sherway';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  6: $Userid =  " orders.user_id IN ('88414')";    	$Partie = 'HBC #88414-Yorkdale';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  7: $Userid =  " orders.user_id IN ('88416')";    	$Partie = 'HBC #88416-Vancouver DTN';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  8: $Userid =  " orders.user_id IN ('88430')";   	$Partie = 'HBC #88430-St.Vital';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  9: $Userid =  " orders.user_id IN ('88431')";   	$Partie = 'HBC #88431-Calgary DTN';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  10: $Userid =  " orders.user_id IN ('88433')";   	$Partie = 'HBC #88433-Polo Park';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  11: $Userid =  " orders.user_id IN ('88434')";   	$Partie = 'HBC #88434-Market Mall';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  12: $Userid =  " orders.user_id IN ('88435')";   	$Partie = 'HBC #88435-West Edmonton';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  13: $Userid =  " orders.user_id IN ('88438')";   	$Partie = 'HBC #88438-Metrotown';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  14: $Userid =  " orders.user_id IN ('88439')";   	$Partie = 'HBC #88439-Langley';	       
			$send_to_address = array('rapports@direct-lens.com');break;
			
			case  15: $Userid =  " orders.user_id IN ('88440')";   	$Partie = 'HBC #88440-Rideau';	       
			$send_to_address = array('rapports@direct-lens.com');break;
		
			case  16: $Userid =  " orders.user_id IN ('88444')";   	$Partie = 'HBC #88444-Mayfair';	       
			$send_to_address = array('rapports@direct-lens.com');break;
						
			
		}//End Switch

		
		
		echo '<br>USER ID: '. $Userid;	
		$subject= "Lentilles en fabrication HBC $month $year pour $Partie";
		echo '<br>Subject:'. $subject;
		echo '<br>Dates: '. $date1 . ' - ' . $date2;
				
		$rptQuery="SELECT  user_id, order_num ,	order_date_processed ,	order_date_shipped ,	order_status, 	order_total, order_num_optipro from orders
		WHERE  $Userid
		AND orders.order_date_processed BETWEEN '$date1' and '$date2'
		AND order_status <> 'cancelled'
		AND (order_date_shipped > '$date2' OR order_date_shipped = '0000-00-00' OR order_date_shipped = '0001-01-01')";
		
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
			
			/*$message.="<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">Compte</td>
					<td align=\"center\"># Commande</td>
					<td align=\"center\"># Commande Optipro</td>
					<td align=\"center\">Order Date</td>
					<td align=\"center\">Shipping Date</td>
					<td align=\"center\">Status</td>
					<td align=\"center\">Order Total</td>
					<td align=\"center\">Monture</td>
					</tr>";*/
					
					$message.="<tr bgcolor=\"CCCCCC\">
					<td align=\"center\">Compte</td>
					<td align=\"center\"># Commande</td>
					<td align=\"center\"># Commande Optipro</td>
					<td align=\"center\">Order Date</td>
					<td align=\"center\">Shipping Date</td>
					<td align=\"center\">Status</td>
					<td align=\"center\">Order Total</td>
					</tr>";
					
			$GrandTotal = 0;	
			
			while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
			$GrandTotal = $GrandTotal + $listItem["order_total"];

			$rptQueryFrame="SELECT * FROM extra_product_orders
			WHERE  order_num= $listItem[order_num]
			AND category='Frame'";
			$resultFrame = mysqli_query($con,$rptQueryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
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
							case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";		break;
							case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
							case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
							case 're-do':					$list_order_status = "Redo";					break;
							case 'in transit':				$list_order_status = "In Transit";				break;
							case 'interlab qc':				$list_order_status = "Interlab QC";				break;
							case 'filled':					$list_order_status = "Shipped";					break;
							case 'cancelled':				$list_order_status = "Cancelled";				break;
							case 'verifying':				$list_order_status = "Verifying";				break;
							case "on hold":					$list_order_status= "On Hold";			        break;
							default:                        $list_order_status = "";             	        break;
			}

			$OrderTotalFormatter= number_format($listItem[order_total],2,',',' ');
			
				/*$message.="
				<tr bgcolor=\"$bgcolor\">
					<td align=\"center\">$listItem[user_id]</td>
					<td align=\"center\">$listItem[order_num]</td>
					 <td align=\"center\">$listItem[order_num_optipro]</td>
					<td align=\"center\">$listItem[order_date_processed]</td>
					<td align=\"center\">$listItem[order_date_shipped]</td>
					<td align=\"center\">$list_order_status</td>
					<td align=\"center\">$OrderTotalFormatter$</td>
					<td align=\"center\">$DataFrame[supplier] $DataFrame[temple_model_num] $DataFrame[color]</td>
				</tr>";*/
				
				$OrderTotalFormatter= number_format($listItem[order_total],2,',',' ');
				$message.="
				<tr bgcolor=\"$bgcolor\">
					<td align=\"center\">$listItem[user_id]</td>
					<td align=\"center\">$listItem[order_num]</td>
					 <td align=\"center\">$listItem[order_num_optipro]</td>
					<td align=\"center\">$listItem[order_date_processed]</td>
					<td align=\"center\">$listItem[order_date_shipped]</td>
					<td align=\"center\">$list_order_status</td>
					<td align=\"center\">$OrderTotalFormatter$</td>
				</tr>";
					
					
			}//END WHILE
			$GrandTotal= number_format($GrandTotal,2,',',' ');
			
			$message.="<tr><td colspan=\"5\">&nbsp;</td><td align=\"right\"><b>Grand Total:</b></td><td align=\"right\"><b>$GrandTotal$</b></td></tr></table>";
			//echo '<br><br>'. $message.'<br><br>';
			
	//SEND EMAIL TESTS A RECOMMENTER !
	//$send_to_address = array('rapports@direct-lens.com');	
	//TODO CE RAPPORT SERA PRET, utiliser ces courriels plutot:
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