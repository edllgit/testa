<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];

//Date du rapport
$ilya6jours  	= mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$date1 = date("Y/m/d", $ilya6jours);

$ajd  			= mktime(0,0,0,date("m"),date("d"),date("Y"));
$date2     = date("Y/m/d", $ajd);


//DATES HARD CODÉS MANUELLE

$date1        = "2021-06-01";
$date2        = "2021-06-30";





/*
Partie 1: Aller parmis les comptes HBC créés chercher toutes les ventes de Gen-Y fait durant la semaine.

*/





/*
Partie 2: Aller chercher parmis les comptes HBC créés toutes les ventes des montures  Gen-Y qui font partie de la liste de Roberto et fait durant la semaine.

*/

include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>PÉRIODE: '. $date1. '-'. $date2.'<br><br>';


//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau =3;	//Nombre de TD dans le tableau
$WidthTableau = 750;		//Pixels

//HBC.CA PRODUCTION PART

//Prepare email 
$message="<html>
<head><style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>
<body>";			

$message.= "
<table width='$WidthTableau' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
	<tr>
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC Incentive Report between $date1-$date2 DM: Johnny Chow</h3></th>
	</tr>";


$user_id	  = '88403';
$requete88403 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88403;
$resultRequete = mysqli_query($con, $requete88403) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88403 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88403 	  = $NbrResultat88403;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88403 .'<br><br><br><br>'; 





$user_id	  = '88408';
$requete88408 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88408;
$resultRequete = mysqli_query($con, $requete88408) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88408 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88408 	  = $NbrResultat88408;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88408 .'<br><br><br><br>'; 



$user_id	  = '88409';
$requete88409 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88409) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88409 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88409 	  = $NbrResultat88409;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88409 .'<br><br><br><br>'; 



$user_id	  = '88411';
$requete88411 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88411;
$resultRequete = mysqli_query($con, $requete88411) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88411 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88411 	  = $NbrResultat88411;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88411 .'<br><br><br><br>'; 



$user_id	  = '88414';
$requete88414 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88414;
$resultRequete = mysqli_query($con, $requete88414) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88414 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88414 	  = $NbrResultat88414;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88414 .'<br><br><br><br>'; 



$user_id	  = '88416';
$requete88416 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88416;
$resultRequete = mysqli_query($con, $requete88416) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88416 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88416 	  = $NbrResultat88416;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88416 .'<br><br><br><br>'; 



$user_id	  = '88431';
$requete88431 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88431;
$resultRequete = mysqli_query($con, $requete88431) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88431 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88431 	  = $NbrResultat88431;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88431 .'<br><br><br><br>'; 



$user_id	  = '88433';
$requete88433 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88433;
$resultRequete = mysqli_query($con, $requete88433) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88433 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88433 	  = $NbrResultat88433;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88433 .'<br><br><br><br>'; 


$user_id	  = '88434';
$requete88434 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88434;
$resultRequete = mysqli_query($con, $requete88434) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88434 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88434 	  = $NbrResultat88434;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88434 .'<br><br><br><br>'; 



$user_id	  = '88435';
$requete88435 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88435;
$resultRequete = mysqli_query($con, $requete88435) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88435 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88435 	  = $NbrResultat88435;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88435 .'<br><br><br><br>'; 



$user_id	  = '88438';
$requete88438 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88438;
$resultRequete = mysqli_query($con, $requete88438) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88438 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88438 	  = $NbrResultat88438;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88438 .'<br><br><br><br>'; 



$user_id	  = '88439';
$requete88439 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88439;
$resultRequete = mysqli_query($con, $requete88439) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88439 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88439 	  = $NbrResultat88439;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88439 .'<br><br><br><br>'; 




$user_id	  = '88440';
$requete88440 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88440;
$resultRequete = mysqli_query($con, $requete88440) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88440 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88440 	  = $NbrResultat88440;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88440 .'<br><br><br><br>'; 




$user_id	  = '88444';
$requete88444 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
echo '<br>query:'. $requete88444;
$resultRequete = mysqli_query($con, $requete88444) or die  ('I cannot select items because #2g: '. $requete . mysqli_error($con));
$NbrResultat88444 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88444 	  = $NbrResultat88444;
echo '<br>'.$user_id. ':'.$Bonus_GenY_88444 .'<br><br><br><br>'; 






exit();


//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';




$Report_Email	= array('dbeaulieu@direct-lens.com');//A COMMENTER	

//$Report_Email	= array('dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//A COMMENTER	

//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','gbruneau@entrepotdelalunette.com','dbeaulieu@direct-lens.com');//LIVE	
$to_address		= $Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";



?>