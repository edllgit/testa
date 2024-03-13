<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);    
//IMPORTANT
//Ce script Ne redirige plus les jobs avec teinte Demande de Jen mise en plkace le 8/8/2018

$message='Jobs processing avec Mirroir :' . '<br>';

//1: Trouver 100% des jobs qui sont présentement a PROCESSING  et qui ne sont pas redigirés vers TR
$QueryProcessing  = "SELECT distinct(order_num) FROM orders WHERE  orders.order_status='processing'  AND order_product_type<>'frame_stock_tray' AND orders.prescript_lab NOT IN (71,33,34,36,62)
 AND redo_order_num is null AND analyzed_by_mirror_tint_script = ''";
echo '<br>'. $QueryProcessing;
$resultProcessing = mysqli_query($con,$QueryProcessing) or die  ('I cannot select items because: ' . mysqli_error($con));	
//$ResultProcessing = mysql_num_rows($resultProcessing); 
$compteur = 0;
while ($DataProcessing = mysqli_fetch_array($resultProcessing,MYSQLI_ASSOC)){
	
	//2- Vérifier si la commande contient un mirroir ou une teinte
	$queryMirrorTeinte = "SELECT * FROM extra_product_orders WHERE order_num = $DataProcessing[order_num] AND  category IN ('Mirror')";	
	echo '<br>'. $queryMirrorTeinte;
	$resultMirroirTeinte = mysqli_query($con,$queryMirrorTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));		
	$NombreResultatMirroirTeinte = mysqli_num_rows($resultMirroirTeinte); 	
		
	if ($NombreResultatMirroirTeinte > 0){//Au moins un résultat
		//3- Evaluer si la job a déja été redirigé manuellement, si oui, ou la laisse faire
		echo '<br>Cette job contient une teinte ou un mirroir: '. $DataProcessing[order_num];
		$queryRedirectionManuelle  		 = "SELECT * FROM status_history WHERE order_num = $DataProcessing[order_num] AND order_status like '%Order redirected to%'";
		//echo  '<br>'. $queryRedirectionManuelle;
		$resultRedirectionManuelle 		 = mysqli_query($con,$queryRedirectionManuelle) or die  ('I cannot select items because: ' . mysqli_error($con));		
		$NombreResultRedirectionManuelle = mysqli_num_rows($resultRedirectionManuelle); 	
		if ($NombreResultRedirectionManuelle>0){
		echo '-->Cette commande a ete redigire manuellement donc ou l\'oublie<br>';
		//On met a jour dans la  table orders, le champ  analyzed_by_mirror_tint_script à oui.
		$queryUpdateFlag    = "UPDATE orders SET analyzed_by_mirror_tint_script ='oui'
							  WHERE order_num =  $DataProcessing[order_num]";
		echo '<br>'.$queryUpdateFlag .'<br>;';
		$resultatUpdateFlag = mysqli_query($con,$queryUpdateFlag) or die  ('I cannot select items because: ' . mysqli_error($con));		
		
		}else{
		$compteur = $compteur +1;
		$queryUPDATEEPO  = "UPDATE orders SET prescript_lab=71, analyzed_by_mirror_tint_script='oui' WHERE order_num =  " . $DataProcessing[order_num];
		echo '<br>'. $queryUPDATEEPO;
		$message.= "<br> Commande  $OrderNum redirigé vers 'En attente de redirection'. ";
		$resultUpdateEPO = mysqli_query($con,$queryUPDATEEPO) or die  ('I cannot select items because: ' . mysqli_error($con));
		echo '&nbsp;R&eacute;ussi<br><br>';
		$message.= '&nbsp;R&eacute;ussi<br><br>';
		
		$todayDate = date("Y-m-d g:i a");// current date
		$currentTime = time($todayDate); //Change date into time
		$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
		$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);			
		//Enregistrer dans l'historique la redirection
		$order_status = "Order redirected to En attente de redirection";
		$queryHistorique = "INSERT INTO status_history (order_num, order_status, update_time, update_type) VALUES ($DataProcessing[order_num], '$order_status','$datecomplete','update script sphere mirror 2.0')";
		echo '<br>'. $queryHistorique;
		$message.='<br>'. $queryHistorique;
		$resultHistorique  = mysqli_query($con,$queryHistorique) or die  ('I cannot select items because: ' . mysqli_error($con));
		
	
		}//End IF
	}
	
	if ($NombreResultatMirroirTeinte == 0){//Il n'y a aucune teinte et aucun mirroir, on flag la commande afin de ne pas la ré-analyser
			$queryUpdateFlagNoTint    = "UPDATE orders SET analyzed_by_mirror_tint_script ='oui'
										  WHERE order_num =  $DataProcessing[order_num]";
			echo '<br>'.$queryUpdateFlagNoTint .'<br>';
			$resultatUpdateFlagNotint = mysqli_query($con,$queryUpdateFlagNoTint) or die  ('I cannot select items because: ' . mysqli_error($con));		
	}
				
}//End While

echo '<br> flag : '.$queryUpdateFlagNoTint .'<br>';
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	
//$send_to_address = array('rapports@direct-lens.com');	
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Mirror/Tint Edll redirection-->TR";

if ($compteur>0){//Envoyer le email seulement s'il y a des commandes.
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
		echo '<br>Reussi';
    }else{
		echo '<br>Echec';
	}	
}//End If il y a des résultats



echo $message;

$time_end = microtime(true);
$time 	  = $time_end - $time_start;
$today    = date("Y-m-d");// current date

$todayDate   = date("Y-m-d g:i a");// current date
$currentTime = time($todayDate); //Change date into time
$timeAfterOneHour = $currentTime+((60*60)*6);	//Add 3 hours to server time to get actual time
$datecomplete     = date("Y-m-d H:i:s",$timeAfterOneHour);		
$HeureActuelle = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
			VALUES('Script Redirection si Mirroir  Edll 2.0', '$time','$today','$HeureActuelle','script_mirror_teinte_edll.php')";
echo $CronQuery; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
?>