<?php 
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);	

$date_of_week        = date("Y-m-d");
$date_of_week_france = date("d-m-Y");

		
$datehier   		= mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$date_of_week  		= date("Y-m-d",$datehier );



//A Re-Commenter apres les tests IMPORTANT !
//IMPORTANT
//$date_of_week=date("2019-10-27");


//HBC.CA PRODUCTION PART
$totalCharles = 0;

//Daily report
$heading="HBC Sales Report for $date_of_week";		

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
		$message.= "<table width='600' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
		<tr bgcolor=\"#000000\">";
		$message.= "<td colspan=\"6\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr><tr>";
		$message.= "<td width='200' align=\"center\">Company</td>";
		$message.= "<td align=\"center\">Orders</td>";
		$message.= "<td align=\"center\">Redos</td>";
		$message.= "<td align=\"center\">Amount</td>";
		$message.= "<td align=\"center\">Avg</td>";
		$message.= "<td align=\"center\">Left in Basket</td>
		</tr>";
		
	

/*
1.1  #88403 Bloor Street
1.3  #88408 Oshawa
1.4  #88409 Eglinton
1.5  #88411 Sherway
...
*



//1.5 #88403-Bloor Street
$Company = "#88403 - Bloor Street";
$user_id = " user_id IN ('88403')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
$rptResultCommandes = mysqli_query($con, $rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 	    = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88403     = $DataCommandes[NbrOrders];
$Amount_88403  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";






//1.3 #88408 - Oshawa
$Company = "#88408 - Oshawa";
$user_id = " user_id IN ('88408')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88408  = $DataCommandes[NbrOrders];
$Amount_88408  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";




//1.4 #88409 - Eglinton
$Company = "#88409 - Eglinton";
$user_id = " user_id IN ('88409')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo'<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88409  	= $DataCommandes[NbrOrders];
$Amount_88409  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";





//1.5 #88411 - Sherway
$Company = "#88411 - Sherway";
$user_id = " user_id IN ('88411')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88411  = $DataCommandes[NbrOrders];
$Amount_88411  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";






//1.6 #88413 - Eaton
$Company = "#88413 - Eaton";
$user_id = " user_id IN ('88413')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88413  = $DataCommandes[NbrOrders];
$Amount_88413  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";





//1.7 #88414 - Yorkdale
$Company = "#88414 - Yorkdale";
$user_id = " user_id IN ('88414')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos);
$Nbr_88414  = $DataCommandes[NbrOrders];
$Amount_88414  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";




//1.8 #88416 Vancouver DTN
$Company = "#88416 Vancouver DTN";
$user_id = " user_id IN ('88416')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88416  = $DataCommandes[NbrOrders];
$Amount_88416  = $DataCommandes[ttl_originales];
$Moyenne = $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne = money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";






//1.11  #88431 - Calgary DTN
$Company = "#88431 - Calgary DTN";
$user_id = " user_id IN ('88431')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88431 	= $DataCommandes[NbrOrders];
$Amount_88431  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";

	
	
	
	
//1.13 #88433 - Polo Park
$Company = "#88433 - Polo Park";
$user_id = " user_id IN ('88433')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88433	= $DataCommandes[NbrOrders];
$Amount_88433  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
//1.14 88434 - Market Mall
$Company = "#88434 - Market Mall";
$user_id = " user_id IN ('88434')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88434 	= $DataCommandes[NbrOrders];
$Amount_88434  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
//1.15 #88435 - West Edmonton
$Company = "#88435 - West Edmonton";
$user_id = " user_id IN ('88435')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88435 	= $DataCommandes[NbrOrders];
$Amount_88435  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
	
//1.16 #88438 - Metrotown
$Company = "#88438 - Metrotown";
$user_id = " user_id IN ('88438')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88438 	= $DataCommandes[NbrOrders];
$Amount_88438  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
//1.17 #88439 - Langley
$Company = "#88439 - Langley";
$user_id = " user_id IN ('88439')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88439 	= $DataCommandes[NbrOrders];
$Amount_88439  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
//1.18 #88440 - Rideau
$Company = "#88440 - Rideau";
$user_id = " user_id IN ('88440')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88440 	= $DataCommandes[NbrOrders];
$Amount_88440  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
	
	
//1.21 #88444 - Mayfair
$Company = "#88444 - Mayfair";
$user_id = " user_id IN ('88444')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Commandes laissé dans le Basket
$rptQueryBasket  = "SELECT count(order_num) AS NbrBasket, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND order_num=-1";
$rptResultBasket = mysqli_query($con, $rptQueryBasket)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataBasket 	 = mysqli_fetch_array($rptResultBasket,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed = '$date_of_week' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_88444 	= $DataCommandes[NbrOrders];
$Amount_88444  = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	$message.= "<td align=\"center\">".$DataBasket[NbrBasket]."</td></tr>";
	
	
	
	

$TotalCommandes = $Nbr_88403  + $Nbr_88408 + $Nbr_88409 + $Nbr_88411 + $Nbr_88413 + $Nbr_88414 + $Nbr_88416 + $Nbr_88429 +  $Nbr_88431 
				 + $Nbr_88433 + $Nbr_88434 + $Nbr_88435 + $Nbr_88438 + $Nbr_88439 + $Nbr_88440   + $Nbr_88444 ; 
				 
$totalCharles   = $Amount_88403  + $Amount_88408 + $Amount_88409 + $Amount_88411 + $Amount_88413 + $Amount_88414 + $Amount_88416 + $Amount_88429  + $Amount_88431 
				 + $Amount_88433 + $Amount_88434 + $Amount_88435 + $Amount_88438 + $Amount_88439 + $Amount_88440   + $Amount_88444 ; 
$totalCharles 	= money_format('%.2n',$totalCharles);  		

	$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"4\">$TotalCommandes orders = $totalCharles $</td></tr>
	</table></body></html>";
	
	
	echo $message;
	
	$subject ="Daily Sales report: Hbc stores:" . $date_of_week;
    $Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','dbeaulieu@direct-lens.com','commis@entrepotdelalunette.com','r.iazzolino@direct-lens.com');
	//$Report_Email	= array('dbeaulieu@direct-lens.com','ehandfield@direct-lens.com');//A SUPPRIMER
	//$Report_Email	= array('dbeaulieu@direct-lens.com');//A SUPPRIMER
echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';

$response=office365_mail($to_address, $from_address, $subject, null, $message);
echo '<br>message sent';

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";

	
*/
	
?>
