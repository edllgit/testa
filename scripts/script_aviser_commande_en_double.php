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

//CE script devra être exécuté plusieurs fois par jour, si une commande en double est trouvée, un courriel sera envoyé.
 

//A RECOMMENTER 
/*
$datedebut  =  "2018-03-22";
$datefin    =  "2018-03-22";
*/

$EnvoyerCourriel = 'non';//Valeur par défaut

$rptQuery="SELECT order_num, count( order_num ) AS NbrCommande
FROM orders
WHERE order_date_processed BETWEEN '$datefin' AND '$datefin'
GROUP BY order_num
ORDER BY `orders`.`order_num` ASC";

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
		$message.="<body><h2>Factures sans Order From <b>AVANT</b> modification: </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order date</td>
			<td align=\"center\">Order From</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
	
	if ($listItem[NbrCommande]> 1){
	//Signifie qu'au moins une commande est en double, il faut donc réagir..!
	
		
	}
	
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
					
	
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>";
					if($listItem[order_date_shipped]!='0000-00-00')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  
					
					 $message.="<td align=\"center\">"; 
					if ($order_status == 'Cancelled'){
						$message.= '<b>'. $order_status. '</b>';
					}else{
						$message.=  $order_status ;
					}
					
					 
					 $message.= "</td>";
					 
					 if ($order_status <> 'Cancelled')
						$message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
						$message.= "<td align=\"center\">N/A</td>";

					 $message.="</tr>";
	
	}//End While
	$cumulOrderNum .=  ")";

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0
echo '<br><br>Cumul order num: ' . $cumulOrderNum;
//SEND EMAIL #1 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Acomba order from fix #1: Commandes sans Order From AVANT modification  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	




echo '<br><br>Cumul order num: ' . $cumulOrderNum;
//SEND EMAIL #3 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;	

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Script acomba order from fix', '$time','$today','$heure_execution','script_aviser_commande_en_double.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>