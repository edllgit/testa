<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);     
$today      = date("Y-m-d");
			
$rptQuery="SELECT * FROM ORDERS 
			WHERE lab in (66,67,59)
			AND order_status = 'processing' 
			AND (re_sphere >4 OR le_sphere >4)
			AND prescript_lab <> 71";
  
//Enregistrer dans l'historique lorsqu'on redirige vers Lab #71
echo '<br>'. $rptQuery;

$message.="$rptQuery <br><br>";
	
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


$queryHistorique = "SELECT * FROM status_history 
WHERE order_num = $listItem[order_num] 
AND  order_status like '%order redirected to En attente de redirection%'";

$message.="$queryHistorique <br>";

echo '<br><br><br><br><br>'. $queryHistorique;
$ResultHistorique     = mysqli_query($con,$queryHistorique)		or die  ('I cannot select items because: ' . mysqli_error($con));
$CountResultatTrouver = mysqli_num_rows($ResultHistorique);
echo 'Nombre de resultat:'. $CountResultatTrouver;
$message.='Nombre de resultat:'. $CountResultatTrouver.'<br><br>';
$compteurJobrediriges = 0;
if ($CountResultatTrouver==0)//La commande n'a jamais  été redirigé vers le lab #71, on le fait.
{
	//1 redirection vers lab #71
	echo '<br>La commande n\'a jamais ete redirige vers le lab #71, on le fait.';
	$message.='<br>La commande n\'a jamais ete redirige vers le lab #71, on le fait.';

	$queryRedirection71 = "UPDATE orders SET  prescript_lab = 71 WHERE order_num =$listItem[order_num]";	
	echo '<br>$queryRedirection71: '. $queryRedirection71;
	$message.='<br>$queryRedirection71: '. $queryRedirection71;
	$resultRedirection71    = mysqli_query($con,$queryRedirection71) or die  ('I cannot select items because: ' . mysqli_error($con));
	
	$compteurJobrediriges  = $compteurJobrediriges +1 ;
	//2 enregistrer dans l'historique
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);				
	$order_status = "Order redirected to En attente de redirection";
	$queryHistorique = "INSERT INTO status_history (order_num, order_status, update_time, update_type) VALUES ($listItem[order_num], '$order_status','$datecomplete','update script sphere +4')";
	echo '<br>'. $queryHistorique;
	$message.='<br>'. $queryHistorique;
	$resultHistorique  = mysqli_query($con,$queryHistorique) or die  ('I cannot select items because: ' . mysqli_error($con));

	
}//End if la commande n'a jamais été redirigé vers Lab #71

		}//END WHILE
		
		//SEND EMAIL
		$send_to_address = array('rapports@direct-lens.com');		
		echo "<br>".$send_to_address;
		$curTime= date("m-d-Y");	
		$to_address=$send_to_address;
		$from_address='donotreply@entrepotdelalunette.com';
		$subject="Script Mirror Coating orders to redirect";
		
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
		echo 'Logged: sucess';
    }else{
		echo 'Logged: failed';
	}	


$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
			VALUES('Redirection Spheres +4 et + 2.0', '$time','$today','$timeplus3heures','script_redirection_sphere_plus_4.php')"; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
			*/
?>