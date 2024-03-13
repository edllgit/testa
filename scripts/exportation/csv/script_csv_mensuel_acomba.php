<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
include("../../../sec_connect.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start = microtime(true);
$tomorrow   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut  = date("Y-m-d", $tomorrow);

$datedebut  =  "2018-04-01";
$datefin    =  "2018-04-30";

//CREATE EXPORT FILE//
$today=date("Y-m-d");
//$filename="PrecisionOrderData-".$today.".csv";
$filename="Acomba-Monthly-Report-".$today.".csv";
$fp=fopen($filename, "w");

//1- Facture ship durant le mois
//2- Factures Payé carte de crédit durant le mois
//3- Credits émis durant le mois



$HouseAccountQuery="SELECT  user_id FROM accounts WHERE house_account = 1 ";
//echo '<br>'. $HouseAccountQuery;
$HouseAccountResult=mysqli_query($con,$HouseAccountQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$House_Accounts = '(';
$compteur = 0;
while ($HouseAccountData=mysqli_fetch_array($HouseAccountResult,MYSQLI_ASSOC)){
	if ($compteur > 0)
	$House_Accounts .= ' , ';
	$House_Accounts .= '\'' . $HouseAccountData[user_id] .'\'' ;
	$compteur   = $compteur+1;
}
$House_Accounts .= ')';

	
// 1- Factures des commandes shippés durant le mois
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id NOT IN $House_Accounts
AND orders.user_id NOT IN ('entrepotsafe','safedr','lavalsafe','warehousehalsafe','terrebonnesafe',
'sherbrookesafe','chicoutimisafe','granbysafe','stemariesafe','longueuilsafe','levissafe') AND  lab NOT IN (47,50,66,67,62)";
	echo '<br><br><br>' .$orderQuery;
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	
	echo '<br>Order num :' . $orderData[order_num];
	
	
	
	$queryVerifPaiement  = "SELECT count(order_num) as nbrMatch, cclast4, pmt_date from payments WHERE cclast4 <> '' AND order_num = ". $orderData[order_num];
	echo '<br>'. $queryVerifPaiement  ;
	$ResultVerifPaiement = mysqli_query($con,$queryVerifPaiement)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));
	$DataVerifPaiement   = mysqli_fetch_array($ResultVerifPaiement,MYSQLI_ASSOC);
	if($DataVerifPaiement[nbrMatch] == 0)//Aucun tuple trouvé dans table paiements donc on ajoute au fichier
	{
		echo '<br>Nbrmatch = 0'; 
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}elseif(($DataVerifPaiement[nbrMatch] == 1) && ($DataVerifPaiement[pmt_date] >=$datedebut)  && ($DataVerifPaiement[pmt_date] <= $datefin))//Aucun tuple trouvé dans table paiements
	{
		echo '<br>Nbrmatch = 1'; 
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}
	
	
}



// 3- Crédits émis durant le mois
$creditQuery="SELECT distinct  memo_credits.mcred_memo_num FROM memo_credits WHERE  mcred_date between '$datedebut' AND '$datefin' AND mcred_acct_user_id NOT IN $House_Accounts AND mcred_acct_user_id NOT IN ('St.Catharines','entrepotifc','redoifc','entrepotframes',
'entrepotdrframes','entrepotdr','laval','entrepotlavalframe','warehousestc','warehousestcframes','warehousehal','warehousehalframes',
'terrebonne','entrepotterrebonneframe','quebec','entrepotquebecframe','sherbrooke','entrepotsherbrookeframe','chicoutimi',
'entrepotchicoutimiframes','entrepotsafe','safedr','warehousestcsafe','lavalsafe','warehousehalsafe','terrebonnesafe','quebecsafe',
'sherbrookesafe','chicoutimisafe','levis','levissafe','BSG','GARAGEMP','longueuil','granby','stemarie','stemariesafe','longueuilsafe','granbysafe')  order by mcred_date";
//echo '<br>' .$creditQuery;
$ResultCredit=mysqli_query($con,$creditQuery)	or die  ('I cannot select 3 items because: ' . mysqli_error($con));

	while ($CreditData=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
	echo '<br><br>memo num:' . $CreditData[mcred_memo_num];
	$outputstring=export_monthly_credits_acomba($CreditData[mcred_memo_num]);
	fwrite($fp,$outputstring);
}

  
//Fermer le fichier
fclose($fp);
?>