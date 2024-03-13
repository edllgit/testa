<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$tomorrow   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut  = date("Y-m-d", $tomorrow);
$datefin    = $datedebut ;


//Partie #1: EDLL Afficher les commandes avant la MAJ
echo '<br>Partie 1: EDLL<br>';
$rptQuery="SELECT order_num,  order_date_processed, order_status  FROM orders WHERE order_status=''  ORDER BY order_num";
echo '<br><br>' . $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$cumulOrderNum = "(";
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
		$message.="<body><h2>Factures sans status <b>AVANT</b> modification: </h2>
		<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Order date</td>
			<td align=\"center\">Status</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
	
	if ($count == 1){
		$cumulOrderNum = $cumulOrderNum . $listItem[order_num];
	}
	else{
		$cumulOrderNum = $cumulOrderNum . ' , '. $listItem[order_num];
	}
	
	
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
	
				//echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
					<td align=\"center\">$listItem[order_num]</td>
					<td align=\"center\">$listItem[order_date_processed]</td>
					<td align=\"center\">$listItem[order_status]</td>
				</tr>";
	
	}//End While
	$cumulOrderNum .=  ")";

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0

echo '<br><br>Cumul order num: ' . $cumulOrderNum;


//Partie #2: Mise a jour des status
//#2: Remettre le status à jour pour ces commandes
$queryUpdateStatus  = "UPDATE orders set order_status='waiting for frame swiss' WHERE order_status=''";
echo '<br><br>'.$queryUpdateStatus.'<br><br>';
$ResultUpdateStatus = mysqli_query($con,$queryUpdateStatus)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum2=mysqli_num_rows($rptResult);


$message.='<tr><td></td></tr>';//Ajouter saut de ligne
$message.='<tr><td></td></tr>';//Ajouter saut de ligne
$message.='<tr><td></td></tr>';//Ajouter saut de ligne
$message.='<tr><td></td></tr>';//Ajouter saut de ligne


if ($ordersnum > 0)
{
	//Partie #3: Afficher les status après la MAJ
	$queryAfficherStatus  = "SELECT order_num, order_date_processed, order_status FROM orders WHERE order_num IN $cumulOrderNum   ORDER BY order_num";
	echo '<br>'.$queryAfficherStatus .'<br>';

	$ResultAfficherStatus = mysqli_query($con,$queryAfficherStatus)		or die  ('I cannot select items because: ' . mysqli_error($con));
	while ($DataAfficherStatus=mysqli_fetch_array($ResultAfficherStatus,MYSQLI_ASSOC)){
		
				 if (($count%2)==0)
						$bgcolor="#E5E5E5";
					else 
						$bgcolor="#FFFFFF";
						
		
					//echo '<br><br>Order Num :'. $DataAfficherStatus[order_num];
					
					$message.="<tr bgcolor=\"$bgcolor\">
						<td align=\"center\">$DataAfficherStatus[order_num]</td>
						<td align=\"center\">$DataAfficherStatus[order_date_processed]</td>
						<td align=\"center\">$DataAfficherStatus[order_status]</td>
					</tr>";
	}//END WHILE		

}//END IF

/*
//SEND EMAIL Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Order Status Fix #1: EDLL $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	




//SEND EMAIL #3 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;	 */

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Script acomba order from fix', '$time','$today','$heure_execution','script_fix_status_problem_EDLL.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>