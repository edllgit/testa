<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");//Fichier de DataBase:EDLL
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);	

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


/*
 A quelle heure devrait être généré ce rapport ? 
8h chaque matin, et il analysera les commandes de la veille

Partie 1- Ce rapport doit contenir 
-Les commandes shippés durant la veille:OK
-Qui appartiennent aux entrepots:OK
-Qui sont des commandes demandées 'Edge and Mount':OK
-Excluant les commandes fabriqués par Swiss:OK
*/


$time_start = microtime(true);
$nbrResultat= 0;
$ladate  	= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier     	= date("Y-m-d", $ladate);

/*
$hier='2020-02-23';
*/

//Début Partie 1
$Condition1 = " LAB in (66,67,59)";								//Lab appartient à Edll: Entrepot QC, Warehouse CA ou SAFETY
$Condition2 = " order_date_shipped='$hier'";				//Date d'expédition = hier
//$Condition3 = " extra_product_orders.job_type='Edge and Mount'";//Uniquement les Edge and Mount
$Condition3 = " ' '";//temporairement désactivé
$Condition4 = " extra_product_orders.category='Edging'";
$Condition5 = " prescript_lab<>10";//QUi ne sont pas fabriqués par Swisscoat
//
$rptQueryPart1    = "SELECT  orders.user_id, orders.order_num,  orders.order_date_shipped, orders.prescript_lab,  orders.tray_num,
extra_product_orders.job_type, extra_product_orders.category   FROM orders, extra_product_orders
WHERE $Condition1  
AND   $Condition2
AND   $Condition3
AND   $Condition4
AND   $Condition5
AND   orders.order_num = extra_product_orders.order_num
ORDER BY user_id";

$Result_QueryPart1    = mysqli_query($con,$rptQueryPart1)	or die  ('I cannot select items because: ' . mysqli_error($con));

echo $rptQueryPart1;


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


		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
		<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"4\"><h3>EDLL ORDERS</h3></td></tr>";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\"><b>User ID</b></td>
				<td align=\"center\"><b>Order Num</b></td>
				<td align=\"center\"><b>Date Shipped</b></td>
				<td align=\"center\"><b>Tray Num</b></td>
				</tr>";
				
		$ordersnum=0;//Compteur de job	
		while ($listItemPart1=mysqli_fetch_array($Result_QueryPart1,MYSQLI_ASSOC)){
			
		
		
		$queryStatusHistory 	= "SELECT COUNT(status_history_id) as NbrResultat FROM status_history WHERE order_num = $listItemPart1[order_num] AND order_status='interlab qc'";
		echo '<br><br>'. $queryStatusHistory;
		$Result_StatusHistory   = mysqli_query($con,$queryStatusHistory)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$DataStatusHistory 		= mysqli_fetch_array($Result_StatusHistory,MYSQLI_ASSOC);
		
		echo 'Nombre de resultat:'.$DataStatusHistory[NbrResultat];

		if ($DataStatusHistory[NbrResultat]>0){	
			$ordersnum = $ordersnum+1;
			$count++;
			if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
			$message.="<tr bgcolor=\"$bgcolor\">";
			$message.="<td align=\"center\">$listItemPart1[user_id]</td>";
			$message.="<td align=\"center\">$listItemPart1[order_num]</td>";
			$message.="<td align=\"center\">$listItemPart1[order_date_shipped]</td>";
			$message.="<td align=\"center\">$listItemPart1[tray_num]</td>";
			$message.="</tr>";
		}//End IF there are no result	
			
			
			
		}//END WHILE
		
		if ($ordersnum>0){
			$message.="<tr bgcolor=\"CCCCCC\"><td align=\"center\" colspan=\"9\"><b>Number of EDLL Orders done by Quebec: $ordersnum</b></td></tr></table>";
		}

//FIN DE LA PARTIE 1 [EDLL]


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	


	
echo "<br>".var_dump($send_to_address);	
echo '<br>'. $message;	
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Jobs Done by Quebec Shipped yesterday ($hier) [Edge and Mount]";
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
	
				// Générer le contenu HTML du rapport


				// Créez un nom de fichier unique avec un horodatage
				$date = new DateTime();
				$timestamp = $date->format('Y-m-d_H-i-s');

				$nomFichier = 'r_edge_and_mount_quebec_'. $timestamp;
			
				// Enregistrez le contenu HTML dans un fichier
/* 				 */$cheminFichierHtml = 'C:/All_Rapports_EDLL/LABO/' . $nomFichier . '.html';
				file_put_contents($cheminFichierHtml, $message);

			
				echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo 'Reussi';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		echo 'Echec';
		//log_email("REPORT: Send EDLL Late Jobs (no status changed since 4 days)",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
	
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
?>