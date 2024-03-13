<?php
//Afficher toutes les erreurs/avertissements
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Fichiers nécessaires  à inclure pour  cette page
include("../export_functions.inc.php");
include("../sec_connect.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut = date("Y-m-d", $tomorrow);

//Les dates de début et de fin du csv qu'on doit générer
$datedebut  =  "2023-01-01";
$datefin    =  "2023-01-31";

//CREATE EXPORT FILE//
$today=date("Y-m-d");
//$filename="PrecisionOrderData-".$today.".csv";
$filename="Acomba-Monthly-Report-HBC_".$today.".csv";
$fp=fopen($filename, "w");

//1- Facture ship durant le mois
//2- Factures Payé carte de crédit durant le mois
//3- Credits émis durant le mois

	
// 1- Factures des commandes shippés durant le mois
$orderQuery="SELECT DISTINCT orders.order_num
			 FROM orders 
			 WHERE order_date_shipped BETWEEN '$datedebut' AND '$datefin'  AND order_total > 0
			 AND user_id NOT IN ('redo_hbc')";

echo '<br><br><br>' .$orderQuery;
$orderResult = mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));


while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	echo '<br>Order num :' . $orderData[order_num];
	$outputstring=export_monthly_orders_acomba_hbc($orderData[order_num]);
	fwrite($fp,$outputstring);
}//End While




/*

// 2- Crédits émis durant le mois
$creditQuery="SELECT distinct  memo_credits.mcred_memo_num 
FROM memo_credits 
WHERE  mcred_date between '$datedebut' AND '$datefin'    
ORDER BY mcred_date";
//echo '<br>' .$creditQuery;
$ResultCredit=mysql_query($creditQuery)	or die  ('I cannot select 3 items because: ' . mysql_error());

	while ($CreditData=mysql_fetch_array($ResultCredit)){
	echo '<br><br>memo num:' . $CreditData[mcred_memo_num];
	$outputstring=export_monthly_credits_acomba($CreditData[mcred_memo_num]);
	fwrite($fp,$outputstring);
}*/

  
//Fermer le fichier
fclose($fp);
?>