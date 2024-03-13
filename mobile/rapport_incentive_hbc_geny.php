<?php /*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
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
/*
$date1        = "2021-06-01";
$date2        = "2021-07-01";
*/

include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>PÉRIODE: '. $date1. '-'. $date2.'<br><br>';

//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau =4;	//Nombre de TD dans le tableau
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
		<th  colspan=\"$LargeurColspanTableau\"><h3>HBC GEN-Y Incentive Report between $date1-$date2</h3></th>
	</tr>
	<tr>
		<th>Store</th>
		<th>Bonus for Gen-Y</th>
		<th>Bonus for Specific Models</th>
		<th>Total Bonus</th>
	</tr>";
	


$user_id	  = '88403';
$requete88403 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y'  AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
$resultRequete 		= mysqli_query($con, $requete88403) or die  ('I cannot select items because #2g1: '. $requete . mysqli_error($con));
$NbrResultat88403 	= mysqli_num_rows($resultRequete);
$Bonus_GenY_88403 	= $NbrResultat88403;
$BonusModeleMonture_88403 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88403 	= $Bonus_GenY_88403  + $BonusModeleMonture_88403;
echo '<tr><td>'.$user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88403 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88403. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88403. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88403$</td>
<td align=\"center\">$BonusModeleMonture_88403$</td>
<td align=\"center\">$BonusTotal88403$</td>
</tr>";



$user_id	  = '88408';
$requete88408 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND extra_product_orders.category='Frame' AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88408;
$resultRequete = mysqli_query($con, $requete88408) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88408 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88408 	= $NbrResultat88408;
$BonusModeleMonture_88408 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88408 	= $Bonus_GenY_88408  + $BonusModeleMonture_88408;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88408 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88408. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88408. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88408$</td>
<td align=\"center\">$BonusModeleMonture_88408$</td>
<td align=\"center\">$BonusTotal88408$</td>
</tr>";



$user_id	  = '88409';
$requete88409 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88409) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88409 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88409 	= $NbrResultat88409;
$BonusModeleMonture_88409 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88409 	= $Bonus_GenY_88409  + $BonusModeleMonture_88409;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88409 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88409. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88409. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88409$</td>
<td align=\"center\">$BonusModeleMonture_88409$</td>
<td align=\"center\">$BonusTotal88409$</td>
</tr>";



$user_id	  = '88411';
$requete88411 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88411) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88411 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88411 	= $NbrResultat88411;
$BonusModeleMonture_88411 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88411 	= $Bonus_GenY_88411  + $BonusModeleMonture_88411;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88411 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88411. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88411. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88411$</td>
<td align=\"center\">$BonusModeleMonture_88411$</td>
<td align=\"center\">$BonusTotal88411$</td>
</tr>";


$user_id	  = '88414';
$requete88414 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88414) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88414 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88414 	= $NbrResultat88414;
$BonusModeleMonture_88414 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88414 	= $Bonus_GenY_88414  + $BonusModeleMonture_88414;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88414 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88414. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88414. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88414$</td>
<td align=\"center\">$BonusModeleMonture_88414$</td>
<td align=\"center\">$BonusTotal88414$</td>
</tr>";




$user_id	  = '88416';
$requete88416 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88416) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88416 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88416 	= $NbrResultat88416;
$BonusModeleMonture_88416 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88416 	= $Bonus_GenY_88416  + $BonusModeleMonture_88416;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88416 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88416. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88416. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88416$</td>
<td align=\"center\">$BonusModeleMonture_88416$</td>
<td align=\"center\">$BonusTotal88416$</td>
</tr>";



$user_id	  = '88431';
$requete88431 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88431) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88431 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88431 	= $NbrResultat88431;
$BonusModeleMonture_88431 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88431 	= $Bonus_GenY_88431  + $BonusModeleMonture_88431;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88431 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88431. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88431. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88431$</td>
<td align=\"center\">$BonusModeleMonture_88431$</td>
<td align=\"center\">$BonusTotal88431$</td>
</tr>";


$user_id	  = '88433';
$requete88433 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88433) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88433 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88433 	= $NbrResultat88433;
$BonusModeleMonture_88433 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88433 	= $Bonus_GenY_88433  + $BonusModeleMonture_88433;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88433 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88433. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88433. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88433$</td>
<td align=\"center\">$BonusModeleMonture_88433$</td>
<td align=\"center\">$BonusTotal88433$</td>
</tr>";





$user_id	  = '88434';
$requete88434 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88434) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88434 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88434 	= $NbrResultat88434;
$BonusModeleMonture_88434 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88434 	= $Bonus_GenY_88434  + $BonusModeleMonture_88434;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88434 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88434. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88434. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88434$</td>
<td align=\"center\">$BonusModeleMonture_88434$</td>
<td align=\"center\">$BonusTotal88434$</td>
</tr>";



$user_id	  = '88435';
$requete88435 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88435) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88435 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88435 	= $NbrResultat88435;
$BonusModeleMonture_88435 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88435 	= $Bonus_GenY_88435  + $BonusModeleMonture_88435;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88435 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88435. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88435. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88435$</td>
<td align=\"center\">$BonusModeleMonture_88435$</td>
<td align=\"center\">$BonusTotal88435$</td>
</tr>";



$user_id	  = '88438';
$requete88438 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88438) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88438 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88438 	= $NbrResultat88438;
$BonusModeleMonture_88438 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88438 	= $Bonus_GenY_88438  + $BonusModeleMonture_88438;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88438 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88438. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88438. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88438$</td>
<td align=\"center\">$BonusModeleMonture_88438$</td>
<td align=\"center\">$BonusTotal88438$</td>
</tr>";


$user_id	  = '88439';
$requete88439 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88439) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88439 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88439 	= $NbrResultat88439;
$BonusModeleMonture_88439 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88439 	= $Bonus_GenY_88439  + $BonusModeleMonture_88439;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88439 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88439. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88439. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88439$</td>
<td align=\"center\">$BonusModeleMonture_88439$</td>
<td align=\"center\">$BonusTotal88439$</td>
</tr>";



$user_id	  = '88440';
$requete88440 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88440) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88440 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88440 	= $NbrResultat88440;
$BonusModeleMonture_88440 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88440 	= $Bonus_GenY_88440  + $BonusModeleMonture_88440;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88440 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88440. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88440. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88440$</td>
<td align=\"center\">$BonusModeleMonture_88440$</td>
<td align=\"center\">$BonusTotal88440$</td>
</tr>";







$user_id	  = '88444';
$requete88444 = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$user_id' AND redo_order_num IS NULL  AND order_date_processed BETWEEN '$date1' and '$date2'
AND extra_product_orders.supplier='GEN-Y' AND extra_product_orders.category='Frame'  AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')";
//echo '<br>query:'. $requete88409;
$resultRequete = mysqli_query($con, $requete88444) or die  ('I cannot select items because #2g2: '. $requete . mysqli_error($con));
$NbrResultat88444 =  mysqli_num_rows($resultRequete);
$Bonus_GenY_88444 	= $NbrResultat88444;
$BonusModeleMonture_88444 = Verifier_Bonus($user_id,$date1,$date2);//$Userid_Magasin,$date1,$date2);
$BonusTotal88444 	= $Bonus_GenY_88444  + $BonusModeleMonture_88444;
echo $user_id. '<br>Bonus Gen-Y:'.$Bonus_GenY_88444 .'$';
echo '<br>Bonus Modele Monture: '.$BonusModeleMonture_88444. '$'; 
echo '<br>Bonus Total: '.$BonusTotal88444. '$<br><br><br>'; 
$message.= "<tr>
<td align=\"center\">$user_id</td>
<td align=\"center\">$Bonus_GenY_88444$</td>
<td align=\"center\">$BonusModeleMonture_88444$</td>
<td align=\"center\">$BonusTotal88444$</td>
</tr>";




//Paramètres communs
$from_address='donotreply@entrepotdelalunette.com';
//$Report_Email	= array('dbeaulieu@direct-lens.com');//A COMMENTER	
$Report_Email	=array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','ebaillargeon@entrepotdelalunette.com');

$to_address		= $Report_Email;
$subject ="HBC Incentive Report  (Gen-Y) $date1-$date2";
$from_address='donotreply@entrepotdelalunette.com';
echo 'Envoie du rapport en cours..<br>';
$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";


exit();
?>




<?php
function Verifier_Bonus($Userid_Magasin,$date1,$date2){
/*
	Fonction avec 3 paramètres:
	1-User id du magasin Ex:88440
	2-Date de début
	3-Date de fin
	*/
	include('../connexion_hbc.inc.php');
	$BonusMagasinCourrant = 0;//On initialise le compteur
	
	$QueryVentesGenY  = "SELECT * FROM orders, extra_product_orders WHERE orders.user_id='$Userid_Magasin' AND redo_order_num IS NULL 
					AND orders.order_num = extra_product_orders.order_num AND orders.order_status NOT IN ('cancelled','on hold')
					AND extra_product_orders.category='Frame'
					AND order_date_processed BETWEEN '$date1' and '$date2' AND extra_product_orders.supplier='GEN-Y' ";
	//echo '<br>QUERY: <br>'. $QueryVentesGenY;
	$resultVenteGenY = mysqli_query($con, $QueryVentesGenY) or die  ('I cannot select items because #2gFF: <br>'. $QueryVentesGenY . '<br>' . mysqli_error($con));
	
	while ($DataVenteGenY = mysqli_fetch_array($resultVenteGenY,MYSQLI_ASSOC)){
		
		$queryFrame  = "SELECT order_num, frame_type, supplier, model, temple_model_num, ep_frame_a,ep_frame_dbl,ep_frame_b, color FROM extra_product_orders 
		WHERE order_num = $DataVenteGenY[order_num] AND category in ('Frame','Edging','Edging_Frame')";
		$resultFrame = mysqli_query($con,$queryFrame)		or die  ('I cannot select items because: ' . mysqli_error($con));
		//echo '<br><br>$queryFrame:' . $queryFrame. '<br>';
		$DataFrame   = mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);	
	
	
		$AjouterAuRapport='non';//Initialisation de la variable
		$CouleurMontureEnMajuscule = strtoupper($DataFrame[color]);
			
			switch ($DataFrame[temple_model_num]){
				//List from roberto, Ticket #3806
			    case '1634': if(($CouleurMontureEnMajuscule =='1') 	 || ($CouleurMontureEnMajuscule=='2')) 	 {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;} break;
				case '1644': if(($CouleurMontureEnMajuscule =='2') 	 || ($CouleurMontureEnMajuscule=='3')) 	 {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;} break;
				case '1701': if(($CouleurMontureEnMajuscule =='3')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1714': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1724': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;
				case '1737': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1738': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1744': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break; 
				case '1751': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1810': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1812': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1813': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1818': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1819': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1820': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1827': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1828': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1906': if(($CouleurMontureEnMajuscule =='2')   || ($CouleurMontureEnMajuscule=='3'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1907': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				case '1914': if(($CouleurMontureEnMajuscule =='1')   || ($CouleurMontureEnMajuscule=='2'))   {$AjouterAuRapport='oui';$BonusMagasinCourrant+=1;}	break;  
				//CAS PARTICULIERS CAR LE NOM DE LA COULEUR > 12 CARACTÈRES (Car présentement, je reçois uniquement les 12 premiers caractères)
			}//END SWITCH
			
		//	echo '<br><br>passe apres switch<br><br>';
			

	}//END WHILE
	//echo 'Bonus  magasin: '. $BonusMagasinCourrant .'$';	
return $BonusMagasinCourrant;
}//END FUNCTION


echo '<br><br>'.$message;
?>