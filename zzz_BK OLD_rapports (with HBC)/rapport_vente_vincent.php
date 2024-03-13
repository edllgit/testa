<?php 

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);	
$totalCharles = 0;
$labQuery  	  = "SELECT primary_key, lab_name, reports_email from labs WHERE primary_key NOT IN (8,10,11,12,15,19,23,24,25,26,30,35)";
$labResult    = mysqli_query($con,$labQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$labcount     = mysqli_num_rows($labResult);	


$date_of_week        = date("Y-m-d");


//A Re-Commenter apres les tests IMPORTANT !
//TODO A RECOMMENTER

$date1=date("2019-01-01");
$date2=date("2019-09-30");

//IFC CLUB.CA PRODUCTION PART
$totalCharles = 0;
echo '<b>IFC.ca</b><br>';

//Daily report
$heading="IFC.ca: Sales Report between $date1 and $date2";		

//Prepare email 
$message="<html>";
$message.="<head><style type='text/css'>
<!--
.TextSize {
	font-size: 10pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";
		$message.="<body>";
		$message.= "<table width='600' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
		$message.= "<td colspan=\"6\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		$message.= "<td width='200' align=\"center\">Company</td>";
		$message.= "<td align=\"center\">Orders</td>";
		$message.= "<td align=\"center\">Redos</td>";
		$message.= "<td align=\"center\">Amount</td>";
		$message.= "<td align=\"center\">Avg</td></tr>";
	

/*
1.1 Chicoutimi OK
1.2 Drummondville OK
1.3 Granby OK
1.4 Halifax OK
1.5  Laval
1.6  Lévis
1.7  Terrebonne
1.8  Sherbrooke
1.9  Longueuil
1.10 Trois-Rivières
1.11 Québec
1.12 Montréal ZT1
1.13 Gatineau
1.14 St-Jérôme
*/



//1.1 Chicoutimi
$Company = "Chicoutimi";
$user_id = " user_id IN ('chicoutimi','chicoutimisafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con, $rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 	    = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_CH     = $DataCommandes[NbrOrders];
$Amount_CH  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";



//1.2 Drummondville
$Company = "Drummondville";
$user_id = " user_id IN ('entrepotdr','safedr')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_DR  = $DataCommandes[NbrOrders];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";





//1.3 Granby
$Company = "Granby";
$user_id = " user_id IN ('granby','granbysafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_GR  = $DataCommandes[NbrOrders];
$Amount_DR  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";




//1.4 Halifax
$Company = "Halifax";
$user_id = " user_id IN ('warehousehal','warehousehalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo'<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_HA  	= $DataCommandes[NbrOrders];
$Amount_HA  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";





//1.5 Laval
$Company = "Laval";
$user_id = " user_id IN ('laval','lavalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_LV  = $DataCommandes[NbrOrders];
$Amount_LV  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";





//1.6 Lévis
$Company = "L&eacute;vis";
$user_id = " user_id IN ('levis','levissafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_LE  = $DataCommandes[NbrOrders];
$Amount_LE  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";



//1.7 Terrebonne
$Company = "Terrebonne";
$user_id = " user_id IN ('terrebonne','terrebonnesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_TE  = $DataCommandes[NbrOrders];
$Amount_TE  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";




//1.8 Sherbrooke
$Company = "Sherbrooke";
$user_id = " user_id IN ('sherbrooke','sherbrookesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_SH  = $DataCommandes[NbrOrders];
$Amount_SH  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";




//1.9 Longueuil
$Company = "Longueuil";
$user_id = " user_id IN ('longueuil','longueuilsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos);
$Nbr_LO  = $DataCommandes[NbrOrders];
$Amount_LO  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";



//1.10 Trois-Rivieres
$Company = "Trois-Rivieres";
$user_id = " user_id IN ('entrepotifc','entrepotsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_TR 	= $DataCommandes[NbrOrders];
$Amount_TR  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	

//1.11 Québec
$Company = "Qu&eacute;bec";
$user_id = " user_id IN ('entrepotquebec','quebecsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_QC  = $DataCommandes[NbrOrders];
$Amount_QC  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";






	
	
//1.12 Montreal ZT1
$Company = "Montreal ZT1";
$user_id = " user_id IN ('montreal','montrealsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_MTL 	= $DataCommandes[NbrOrders];
$Amount_MTL = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	
	
	
	
//1.13 Gatineau
$Company = "Gatineau";
$user_id = " user_id IN ('gatineau','gatineausafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_GAT 	= $DataCommandes[NbrOrders];
$Amount_GAT = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";





//1.14 ST-JÉROME
$Company = "St-Jérôme";
$user_id = " user_id IN ('stjerome','stjeromesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_STJ 	= $DataCommandes[NbrOrders];
$Amount_STJ = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";



	
$TotalCommandes = $Nbr_TR + $Nbr_DR + $Nbr_GR + $Nbr_LE + $Nbr_CH + $Nbr_LV + $Nbr_TE + $Nbr_SH + $Nbr_LO + $Nbr_SMB + $Nbr_QC +$Nbr_HA +$Nbr_MTL + $Nbr_GAT + $Nbr_STJ ; 
$totalCharles   = $Amount_TR + $Amount_DR+ $Amount_GR+ $Amount_LE+ $Amount_CH+ $Amount_LV+ $Amount_TE+ $Amount_SH+ $Amount_LO+ $Amount_SMB +  $Amount_QC+ $Amount_HA+ $Amount_MTL + $Amount_GAT + $Amount_STJ;
$totalCharles 	= money_format('%.2n',$totalCharles);  		

	$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"3\">$TotalCommandes orders = $totalCharles $</td></tr>
	</table></body></html>";
	$subject ="Daily Sales report: Ifc.ca Production between $date1 and $date2";
	//$Report_Email	= array('dbeaulieu@direct-lens.com');
    $Report_Email	= array('dbeaulieu@direct-lens.com');//TODO A RECOMMENTER

echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';

$response=office365_mail($to_address, $from_address, $subject, null, $message);

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";
	$totalCharles = 0;
	

	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport de vente Quotidien (Daily HTML) 2.0', '$time','$today','$timeplus3heures','rapport_vente_quotidien.php')"; 					
$cronResult 	 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	

?>
