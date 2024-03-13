<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
//ini_set('MAX_EXECUTION_TIME', -1);
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$time_start = microtime(true);
$today     =  date("Y-m-d");

//Phase 1: Produits HORS STOCK pour  Trois-Rivieres
//EXCLURE PRODUITS SAFETY
$rptQueryTR="SELECT order_num, order_num_optipro, order_product_name, order_product_coating, order_product_index, order_product_id, order_product_price, order_total, order_patient_first, order_patient_last, order_date_processed, user_id FROM orders
WHERE order_date_processed BETWEEN '2019-01-15' AND '2019-03-20'
AND lab IN ( 66,67 )
AND order_status<>'cancelled'
AND order_product_id IN (47858, 44736, 44734, 44732, 47883, 46252, 46556, 47861, 46581, 47886, 47870, 47865, 47853, 51543, 71754, 71751, 75018, 75019, 75020, 75021, 75022, 75023, 75046, 75146, 75274, 85701)
AND user_id IN ('warehousehal')  
ORDER BY user_id, order_date_processed";


//PARTIE SAFE
//TR:   	AND user_id IN ('entrepotsafe')    
//TB:  		AND user_id IN ('terrebonnesafe')  
//SH:   	AND user_id IN ('sherbrookesafe')  
//QC:  		AND user_id IN ('quebecsafe')      
//MTL ZT1:  AND user_id IN ('montrealsafe')	   
//LO:   	AND user_id IN ('longueuilsafe')   
//LE: 		AND user_id IN ('levissafe')       
//Laval:	AND user_id IN ('lavalsafe')       
//HA:		AND user_id IN ('warehousehalsafe') 
//GR:		AND user_id IN ('granbysafe')
//DR:		AND user_id IN ('safedr')
//CH: 		AND user_id IN ('chicoutimisafe')


//PARTIE IFC.CA
//TR:   	AND user_id IN ('entrepotifc')
//TB:  		AND user_id IN ('terrebonne')
//SH:   	AND user_id IN ('sherbrooke')
//QC:  		AND user_id IN ('entrepotquebec')
//MTL ZT1:  AND user_id IN ('montreal')
//LO:   	AND user_id IN ('longueuil')
//LE: 		AND user_id IN ('levis')
//Laval:	AND user_id IN ('laval')
//HA:		AND user_id IN ('warehousehal')
//GR:		AND user_id IN ('granby')
//DR:		AND user_id IN ('entrepotdr')
//CH: 		AND user_id IN ('chicoutimi')


//Il faut passer toutes les commandes effectués dans ces dates pour évaluer
//0- EXCLURE TOUT LE STOCK
//1- Si l'indice est parmis ceux-ci: On facture 4$: 1.53, 1.59, 1.74
//2- Si l'indice est parmis ceux-ci: On facture 8$: 1.60, 1.67
//3- Si le traitement est autre chose que 'Hard Coat/Uncoated', on facture 4$
//Remplir un tableau a mesure qu'on passe les commandes afin d'avoir un résultat global qui fait du sens et qui se comprends

echo $rptQueryTR.'<br><br>';

	
	$rptResult=mysql_query($rptQueryTR)		or die  ('I cannot select items because: ' . mysql_error());
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

		$message.="<body><table width=\"1325\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
					<th align=\"center\"># Commande</th>
					<th align=\"center\"># Optipro</th>
					<th align=\"center\">Date de Commande</th>
					<th align=\"center\">Compte</th>
					<th align=\"center\">Patient</th>
					<th align=\"center\">Produit / Clé</th>
					
					<th align=\"center\">Prix Verres</th>
					<th align=\"center\">Total Facture</th>
					
					<th align=\"center\">Indice</th>
					<th align=\"center\">Coating</th>

					<th align=\"center\">Majoration Totale</th>

				</tr>";
				
		

		$SommeTotalMajoration = 0;
		
		while ($listItem=mysql_fetch_array($rptResult)){
				
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
			
		$order_num 			= $listItem[order_num];	
		$index_v   			= $listItem[order_product_index];
		$coating   			= $listItem[order_product_coating]; 
		$price_can 			= $listItem[price_can];	
		$vendant_edll 		= $listItem[vendant_edll];		
		$MajorationIndice 	= 0;
		$MajorationCoating 	= 0;
		$product_id 		= $listItem[order_product_id];	
			
	
		switch($product_id){	
			case 75146: $MajorationStock  = 0.02 ; break;			
			case 47865: $MajorationStock  = 1.00 ; break;		
			case 71754: $MajorationStock  = 2.00 ; break;
			case 71751: $MajorationStock  = 2.00 ; break;		
			case 75018: $MajorationStock  = 2.02 ; break;
			case 75019: $MajorationStock  = 2.02 ; break;	
			case 44736: $MajorationStock  = 3.00 ; break;	
			case 75020: $MajorationStock  = 4.02 ; break;
			case 46581: $MajorationStock  = 4.02 ; break;	
			case 75021: $MajorationStock  = 4.02 ; break;		
			case 47870: $MajorationStock  = 5.00 ; break;			
			case 85701: $MajorationStock  = 6.02 ; break;
			case 75046: $MajorationStock  = 6.02 ; break;	
			case 47853: $MajorationStock  = 8.02 ; break;
			case 47858: $MajorationStock  = 8.02 ; break;		
			case 47861: $MajorationStock  = 8.40 ; break;
			case 46252: $MajorationStock  = 8.40 ; break;		
			case 44732: $MajorationStock  = 9.00 ; break;	
			case 44734: $MajorationStock  = 11.00; break;	
			case 46556: $MajorationStock  = 11.05; break;	
			case 75274: $MajorationStock  = 12.02; break;	
			case 51543: $MajorationStock  = 13.00; break;	
			case 47886: $MajorationStock  = 14.00; break;	
			case 47883: $MajorationStock  = 18.00; break;	
			case 75022: $MajorationStock  = 38.02; break;	
			case 75023: $MajorationStock  = 38.02; break;
		}	



		   $message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[order_num_optipro]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[user_id]</td>
				<td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
				<td align=\"center\">$listItem[order_product_name] / $listItem[order_product_id]</td>
				
				
				<td align=\"center\">$listItem[order_product_price]</td>
				<td align=\"center\">$listItem[order_total]</td>
				
				<td align=\"center\">$listItem[order_product_index]</td>
				<td align=\"center\">$listItem[order_product_coating]</td>

				
				<td align=\"center\">$MajorationStock</td>";
              $message.="</tr>";	
			
		$SommeTotalMajoration += $MajorationStock;
			
		}//END WHILE
		
		$message.="<tr bgcolor=\"CCCCCC\"><th align=\"center\" colspan=\"12\">Total des majorations à re-facturer: $SommeTotalMajoration$</th></tr></table>";
		

		echo $message;
		exit();
		
		
		

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="MAJ prix EDLL Mars 2019 Rapport de Validation: ". $curTime;
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
		log_email("REPORT: Login Attempt",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Login Attempt",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	


$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Send Login Attempt by email', '$time','$today','$timeplus3heures','cron_send_login_attempt.php') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}




?>