<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');


$time_start   = microtime(true);	
$totalCharles = 0;
/*
$date_of_week =date("2020-01-01");
$date_of_week2=date("2020-12-31");
*/
//IFC CLUB.CA 
$totalCharles = 0;
echo '<b>IFC.ca</b><br>';

//Daily report
$heading="IFC.ca: Sales Report Between $date_of_week and $date_of_week2";		

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
1.1 Trois-Rivières
1.2  Drummondville
1.3  Granby
1.4  Lévis
1.5  Chicoutimi
1.6  Laval
1.7  Terrebonne
1.8  Sherbrooke
1.9  Longueuil
1.11 Québec
1.12 Halifax
*/





//1.5 Chicoutimi
$Company = "Chicoutimi";
$user_id = " user_id IN ('chicoutimi')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con, $rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 	    = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('entrepotdr')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('granby')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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




//1.12 Halifax
$Company = "Halifax";
$user_id = " user_id IN ('warehousehal')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.6 Laval
$Company = "Laval";
$user_id = " user_id IN ('laval')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2'  AND redo_order_num IS NOT NULL";
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





//1.4 Lévis
$Company = "L&eacute;vis";
$user_id = " user_id IN ('levis')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.9 Longueuil
$Company = "Longueuil";
$user_id = " user_id IN ('longueuil')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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




//1.11 Québec
$Company = "Qu&eacute;bec";
$user_id = " user_id IN ('entrepotquebec')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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








//1.8 Sherbrooke
$Company = "Sherbrooke";
$user_id = " user_id IN ('sherbrooke')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.7 Terrebonne
$Company = "Terrebonne";
$user_id = " user_id IN ('terrebonne')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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






//1.1 Trois-Rivieres
$Company = "Trois-Rivieres";
$user_id = " user_id IN ('entrepotifc')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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


	
$TotalCommandes = $Nbr_TR + $Nbr_DR + $Nbr_GR + $Nbr_LE + $Nbr_CH + $Nbr_LV + $Nbr_TE + $Nbr_SH + $Nbr_LO + $Nbr_SMB + $Nbr_QC +$Nbr_HA; 
$totalCharles   = $Amount_TR + $Amount_DR+ $Amount_GR+ $Amount_LE+ $Amount_CH+ $Amount_LV+ $Amount_TE+ $Amount_SH+ $Amount_LO+ $Amount_SMB +  $Amount_QC+ $Amount_HA;
$totalCharles 	= money_format('%.2n',$totalCharles);  		

	$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"3\">$TotalCommandes orders = $totalCharles $</td></tr>
	</table></body></html>";
	
	echo $message;
	
	$subject ="Daily Sales report: Ifc.ca Production:" . $date_of_week .'-'. $date_of_week2;

	$Report_Email	= array('dbeaulieu@direct-lens.com');//A SUPPRIMER
	
echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);



//////////////////////////////////PARTIE SAFE
//SAFE PART

$totalCharles = 0;
echo '<b>IFC.ca</b><br>';

//Daily report
$heading="SAFE: Sales Report Between $date_of_week and $date_of_week2";		

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
		$message.= "</tr>";
		
		$message.= "<td width='200' align=\"center\">Company</td>";
		$message.= "<td align=\"center\">Orders</td>";
		$message.= "<td align=\"center\">Redos</td>";
		$message.= "<td align=\"center\">Amount</td>";
		$message.= "<td align=\"center\">Avg</td></tr>";
	

/*
1.1 Trois-Rivières
1.2  Drummondville
1.3  Granby
1.4  Lévis
1.5  Chicoutimi
1.6  Laval
1.7  Terrebonne
1.8  Sherbrooke
1.9  Longueuil
1.11 Québec
1.12 Halifax
*/





//1.5 Chicoutimi
$Company = "Chicoutimi";
$user_id = " user_id IN ('chicoutimisafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con, $rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 	    = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('safedr')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('granbysafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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




//1.12 Halifax
$Company = "Halifax";
$user_id = " user_id IN ('warehousehalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.6 Laval
$Company = "Laval";
$user_id = " user_id IN ('lavalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2'  AND redo_order_num IS NOT NULL";
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





//1.4 Lévis
$Company = "L&eacute;vis";
$user_id = " user_id IN ('levissafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.9 Longueuil
$Company = "Longueuil";
$user_id = " user_id IN ('longueuilsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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




//1.11 Québec
$Company = "Qu&eacute;bec";
$user_id = " user_id IN ('quebecsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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








//1.8 Sherbrooke
$Company = "Sherbrooke";
$user_id = " user_id IN ('sherbrookesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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





//1.7 Terrebonne
$Company = "Terrebonne";
$user_id = " user_id IN ('terrebonnesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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






//1.1 Trois-Rivieres
$Company = "Trois-Rivieres";
$user_id = " user_id IN ('entrepotsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed  BETWEEN '$date_of_week' AND '$date_of_week2' AND redo_order_num IS NOT NULL";
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


	
$TotalCommandes = $Nbr_TR + $Nbr_DR + $Nbr_GR + $Nbr_LE + $Nbr_CH + $Nbr_LV + $Nbr_TE + $Nbr_SH + $Nbr_LO + $Nbr_SMB + $Nbr_QC +$Nbr_HA; 
$totalCharles   = $Amount_TR + $Amount_DR+ $Amount_GR+ $Amount_LE+ $Amount_CH+ $Amount_LV+ $Amount_TE+ $Amount_SH+ $Amount_LO+ $Amount_SMB +  $Amount_QC+ $Amount_HA;
$totalCharles 	= money_format('%.2n',$totalCharles);  		

	$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"3\">$TotalCommandes orders = $totalCharles $</td></tr>
	</table></body></html>";
	
	echo $message;
	
	$subject ="Daily Sales report: SAFE Between " . $date_of_week .' and '. $date_of_week2;
	
	
	$Report_Email	= array('dbeaulieu@direct-lens.com');//A SUPPRIMER

	

	
echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';
$response=office365_mail($to_address, $from_address, $subject, null, $message);

	
?>