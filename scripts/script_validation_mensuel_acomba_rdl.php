<?php
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//Fichiers nécessaires  à inclure pour  cette page
include("../export_functions.inc.php");
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$time_start = microtime(true);
$tomorrow   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut  = date("Y-m-d", $tomorrow);
//Taper les dates de début et de fin du rapport à générer
$datedebut  =  "2024-05-01";
$datefin    =  "2024-05-31";

//CREATE EXPORT FILE//
$today=date("Y-m-d");
//$filename="PrecisionOrderData-".$today.".csv";
$filename="Acomba-Monthly-Report-".$today.".csv";
$fp=fopen($filename, "w");

//1- Facture ship durant le mois
//2- Factures Payé carte de crédit durant le mois
//3- Credits émis durant le mois

echo' passe<br>';

$HouseAccountQuery="SELECT  user_id FROM accounts WHERE house_account = 1 ";
echo '<br>'. $HouseAccountQuery;
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
echo 'House acct:'. $House_Accounts.'<br>';

// 1- Factures des commandes shippés durant le mois
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id NOT IN $House_Accounts
AND orders.user_id NOT IN ('entrepotsafe','safedr','lavalsafe','terrebonnesafe',
'sherbrookesafe','chicoutimisafe','granbysafe','longueuilsafe','levissafe','quebecsafe','GARAGEMP','BSG',
'eyeviewsafe','villeshannon','redosafety','gatineausafe','stjeromesafe','garantieatoutcasser') AND  lab NOT IN (47,50,66,62)";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE

/*
//2-Factures de MONTREAL
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('montreal','montrealsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE
*/

//2.5-Factures d'Edmundston
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('edmundston','edmundstonsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE



//2.5-Factures de Vaudreuil
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('vaudreuil','vaudreuilsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE
	
	
//2.5-Factures de Moncton
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('moncton','monctonsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE

//2.5-Factures de Fredericton
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('fredericton','frederictonsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE	

//2.5-Factures de ST-John
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('stjohn','stjohnsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE
	
//2.5-Factures de Sorel
$orderQuery="SELECT DISTINCT orders.order_num
FROM orders WHERE order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 
AND orders.user_id  IN ('sorel','sorelsafe')";
	echo '<br><br><br>' .$orderQuery . '<br>';
$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select 2 items because: ' . mysqli_error($con));

	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
		echo '<br>Order num :' . $orderData[order_num];
		$outputstring=export_monthly_orders_acomba($orderData[order_num]);
		fwrite($fp,$outputstring);
	}//END WHILE
//FIN SOREL
	

// 3- Crédits émis durant le mois
$creditQuery="SELECT distinct  memo_credits.mcred_memo_num FROM memo_credits WHERE  mcred_date between '$datedebut' AND '$datefin' AND mcred_acct_user_id NOT IN $House_Accounts AND mcred_acct_user_id NOT IN ('St.Catharines','entrepotifc','redoifc','entrepotframes',
'entrepotdrframes','entrepotdr','laval','entrepotlavalframe','warehousestc','warehousestcframes','warehousehalframes',
'terrebonne','entrepotterrebonneframe','quebec','entrepotquebecframe','sherbrooke','entrepotsherbrookeframe','chicoutimi',
'entrepotchicoutimiframes','entrepotsafe','safedr','lavalsafe','terrebonnesafe','quebecsafe',
'sherbrookesafe','chicoutimisafe','levis','levissafe','BSG','granbysafe','longueuil','granby','longueuilsafe','granbysafe','entrepotquebec','garantieatoutcasser',
'directlabnetwork','brianmilano','milanordl','opticaldepomilano6769','opticalboutique','leonardmann','entrepotmilanotroisri','entrepotmilanosherbrooke','entrepotmilanodrummondville',
'warehousemilanohalifax','laval@entrepotdelalunette.com','terrebonne@entrepotdelalunette.com','entrepotmilanochicoutimi','mainopticalmilano','3for1glassesmilano','imperialmilano6769',
'firstrateopticalsupply','poloniaopticalmilano6769','eyesonrichmondmilano','opticalmarketmilano','nanakmilano','carmenmilano6769','mattieyewearmilano6769',
'towneopticalmilano6769','oandomilano6769','kawarthamilano6769','carlosmilano6769','brianmilano6769','ezvisionmilano6769','eyesonmainmilano6769','villeshannon','eyeviewsafe',
'GARAGEMP','redosafety','gatineausafe','stjeromesafe','gatineau','stjerome')  
  ORDER BY mcred_date";
echo '<br>' .$creditQuery.'<br>';
$ResultCredit=mysqli_query($con,$creditQuery)	or die  ('I cannot select 3 items because: ' . mysqli_error($con));

	while ($CreditData=mysqli_fetch_array($ResultCredit,MYSQLI_ASSOC)){
	echo '<br><br>memo num:' . $CreditData[mcred_memo_num];
	$outputstring=export_monthly_credits_acomba($CreditData[mcred_memo_num]);
	fwrite($fp,$outputstring);
}

  
//Fermer le fichier
fclose($fp);
?>