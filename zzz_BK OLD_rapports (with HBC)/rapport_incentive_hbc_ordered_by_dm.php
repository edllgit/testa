<?php 
/*
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);	
$totalCharles = 0;
/*
$date1        = date("Y-m-d");
$date2        = date("Y-m-d");
*

$date1        = "2019-06-01";
$date2        = "2019-06-30";

//HBC.CA PRODUCTION PART
$totalCharles = 0;
///Initialisation des variables
$Total_BY_DM_Johnny_Chow 		= 0;
$Total_BY_DM_Sukh_Maghera 		= 0;
$Total_BY_DM_Elaine_Macalolooy 	= 0;




//Daily report
	
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
		
		
		
		//DÉBUT PARTIE JOHNNY CHOW
		$heading="HBC Incentive Report between $date1-$date2  DM: <b>Johnny Chow</b>";	
		$message.= "<table width='1150' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr align=\"center\" bgcolor=\"#000000\">";
		$message.= "<td colspan=\"8\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		$message.= "<tr>
					<th width='250' align=\"center\">Store</th>";
		$message.= "<th align=\"center\">Single Vision with AR Orders (3$/job)</th>";
		$message.= "<th align=\"center\">HD IOT/Precision Advance with AR (5$/job)</th>";
		$message.= "<th align=\"center\">Maxiwide with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Ifree with Maxivue(8$/job)</th>";
		$message.= "<th align=\"center\">iAction with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Total Incentive</th>";
		$message.= "<th align=\"center\">Percentage</th>
		</tr>";
	


//1.1 Début #88403-Bloor Street
$Company = "#88403-Bloor Street";
$user_id = " user_id IN ('88403')";

//1.1.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.1.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.1.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.1.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.1.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.1.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.1.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;		
//FIN 88403-Bloor St.


	
	
	
	

	
	
//1.3 Début #88408-Oshawa
$Company = "#88408-Oshawa";
$user_id = " user_id IN ('88408')";

//1.3.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.3.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.3.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.3.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.3.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.3.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.3.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;
//FIN 88408-Oshawa	



//1.4 Début #88409-Eglinton
$Company = "#88409-Eglinton";
$user_id = " user_id IN ('88409')";

//1.4.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.4.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.4.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.4.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.4.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.4.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.4.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;
//FIN 88409-Eglinton	


//1.5 Début #88411-Sherway
$Company = "#88411-Sherway";
$user_id = " user_id IN ('88411')";

//1.5.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.5.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.5.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.5.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.5.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.5.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.5.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;	
//FIN 88411-Sherway



//1.6 Début #88414-Yorkdale
$Company = "#88414-Yorkdale";
$user_id = " user_id IN ('88414')";

//1.6.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.6.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.6.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.6.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.6.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.6.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.6.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;
//FIN 88414-Yorkdale


	






//1.17 Début #88440-Rideau
$Company = "#88440-Rideau";
$user_id = " user_id IN ('88440')";

//1.17.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.17.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.17.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.17.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.17.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.17.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.17.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Johnny_Chow += $Total_Incentive;
//FIN 88440-Rideau





$message.= "<tr><th colspan=\"7\" align=\"right\">TOTAL for Johnny's stores:</th><th>$ $Total_BY_DM_Johnny_Chow</th></tr>";
$message.="</table><br><br>";
//FIN DE LA PARTIE DE JOHNNY CHOW




//DÉBUT PARTIE SUKH MAGHERA
		$heading="HBC Incentive Report between $date1-$date2  DM: <b>Sukh Maghera</b>";	
		$message.= "<table width='1150' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr align=\"center\" bgcolor=\"#000000\">";
		$message.= "<td colspan=\"8\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		$message.= "<tr>
					<th width='250' align=\"center\">Store</th>";
		$message.= "<th align=\"center\">Single Vision with AR Orders (3$/job)</th>";
		$message.= "<th align=\"center\">HD IOT/Precision Advance with AR (5$/job)</th>";
		$message.= "<th align=\"center\">Maxiwide with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Ifree with Maxivue(8$/job)</th>";
		$message.= "<th align=\"center\">iAction with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Total Incentive</th>";
		$message.= "<th align=\"center\">Percentage</th>
		</tr>";





//1.7 Début #88416-Vancouver DTN
$Company = "#88416-Vancouver DTN";
$user_id = " user_id IN ('88416')";

//1.7.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.7.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.7.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.7.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.7.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.7.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.7.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88416-VANCOUVER DTN







//1.10 Début #88431-Calgary DTN
$Company = "#88431-Calgary DTN";
$user_id = " user_id IN ('88431')";

//1.10.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.10.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.10.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.10.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.10.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.10.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.10.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88431-Calgary DTN



	
//1.12 Début #88433-Polo Park
$Company = "#88433-Polo Park";
$user_id = " user_id IN ('88433')";

//1.12.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.12.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.12.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.12.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.12.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.12.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.12.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88433-Polo Park


//1.13 Début #88434-Market Mall
$Company = "#88434-Market Mall";
$user_id = " user_id IN ('88434')";

//1.13.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.13.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.13.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.13.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.13.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.13.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.13.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td></tr>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88434-Market Mall	


//1.14 Début #88438-Metrotown
$Company = "#88438-Metrotown";
$user_id = " user_id IN ('88438')";

//1.15.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.15.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.15.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.15.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.15.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.15.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.15.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88438-Metrotown
	
	
	
//1.16 Début #88439-Langley
$Company = "#88439-Langley";
$user_id = " user_id IN ('88439')";

//1.16.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.16.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.16.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.16.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.16.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.16.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.16.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88439-Langley
	
	

	

	

//1.20 Début #88444-Mayfair
$Company = "#88444-Mayfair";
$user_id = " user_id IN ('88444')";

//1.20.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.20.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.20.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.20.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.20.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.20.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.20.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Sukh_Maghera += $Total_Incentive ;
//FIN 88444-Mayfair


$message.= "<tr><th colspan=\"7\" align=\"right\">TOTAL for Sukh's stores:</th><th>$ $Total_BY_DM_Sukh_Maghera</th></tr>";
$message.="</table><br><br>";
//FIN DE LA PARTIE DM SUKH MAGHERA





//DÉBUT PARTIE ELAINE MACALOLOOY 
		$heading="HBC Incentive Report between $date1-$date2  DM: <b>Elaine Macalolooy</b>";	
		$message.= "<table width='1150' border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr align=\"center\" bgcolor=\"#000000\">";
		$message.= "<td colspan=\"8\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		$message.= "<tr>
					<th width='250' align=\"center\">Store</th>";
		$message.= "<th align=\"center\">Single Vision with AR Orders (3$/job)</th>";
		$message.= "<th align=\"center\">HD IOT/Precision Advance with AR (5$/job)</th>";
		$message.= "<th align=\"center\">Maxiwide with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Ifree with Maxivue(8$/job)</th>";
		$message.= "<th align=\"center\">iAction with Maxivue (8$/job)</th>";
		$message.= "<th align=\"center\">Total Incentive</th>";
		$message.= "<th align=\"center\">Percentage</th>
		</tr>";




//1.8 Début #88429-Saskatoon
$Company = "#88429-Saskatoon";
$user_id = " user_id IN ('88429')";

//1.8.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.8.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.8.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.8.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.8.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.8.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.8.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Elaine_Macalolooy += $Total_Incentive;
//FIN 88429-Saskatoon











	
	
	
	
	
	

	
	
//1.14 Début #88435-West Edmonton
$Company = "#88435-West Edmonton";
$user_id = " user_id IN ('88435')";

//1.14.1- Les SV avec AR
$querySVwithAR = "SELECT count(order_num) as NbrSVwithAR, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%vision%'
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name  ";
//echo '<br>'. $querySVwithAR;
$resultSVwithAR = mysqli_query($con, $querySVwithAR)	or die  ('I cannot select items because: '. $querySVwithAR . mysqli_error($con));
$DataSVwithAR 	= mysqli_fetch_array($resultSVwithAR,MYSQLI_ASSOC);
$SvwithAR = $DataSVwithAR[NbrSVwithAR];

//1.14.2- HD IOT / Precision Advance (Exclusion: PAS DE HC)
$queryHDIOT_PrecisionAdvance =  "SELECT count(order_num) as NbrHDIOT_PrecisionAdvance, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND (order_product_name LIKE '%HD IOT%' OR order_product_name like '%Precision advance%')
AND order_product_coating NOT IN ('Hard Coat')
ORDER BY order_product_name ";
//echo '<br>'. $queryHDIOT_PrecisionAdvance;
$ResultHDIOT_PrecisionAdvance = mysqli_query($con,$queryHDIOT_PrecisionAdvance)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHDIOT_PrecisionAdvance   = mysqli_fetch_array($ResultHDIOT_PrecisionAdvance,MYSQLI_ASSOC);
$HDIOT_PrecisionAdvance       = $DataHDIOT_PrecisionAdvance[NbrHDIOT_PrecisionAdvance];

//1.14.3- Les Maxiwides  avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryMaxiwide  =  "SELECT count(order_num) as NbrMaxiwide, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_coating like '%MaxiVue2%'
AND order_product_name LIKE '%maxiwide%'
ORDER BY order_product_name ";
//echo '<br>'. $queryMaxiwide;
$ResultMaxiwide = mysqli_query($con,$queryMaxiwide)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiwide   = mysqli_fetch_array($ResultMaxiwide,MYSQLI_ASSOC);
$MaxiWide 		= $DataMaxiwide[NbrMaxiwide];

//1.14.4- Les iFree avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiFree =  "SELECT count(order_num) as NbrIfree, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%ifree%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiFree;
$ResultIfree = mysqli_query($con,$queryiFree)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($ResultIfree,MYSQLI_ASSOC);
$iFree 		 = $DataIfree[NbrIfree];

//1.14.5-Les iAction avec Maxivue et Maxivue Backside seulement. (Le 'like' est utilisé afin d'inclure également les 'Maxivue2 Backside')
$queryiAction  =  "SELECT count(order_num) as nbrIaction, user_id, order_product_name, order_status, order_date_processed, order_date_Shipped FROM orders
WHERE redo_order_num IS NULL
AND $user_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('on hold', 'cancelled')
AND order_product_name LIKE '%i-Action%'
AND order_product_coating like '%MaxiVue2%'
ORDER BY order_product_name ";
//echo '<br>'. $queryiAction;
$resultiAction = mysqli_query($con,$queryiAction)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$iAction 	   = $DataiAction[nbrIaction];

//1.14.6-Calcul des montants accordés en bonus
$Montant_HDIOT_PrecisionAdvance = $HDIOT_PrecisionAdvance *5;
$Montant_SvwithAR = $SvwithAR * 3;
$Montant_Maxiwide = $MaxiWide * 8;
$Montant_ifree    = $iFree    * 8;
$Montant_iAction  = $iAction  * 8;
$Total_Incentive = $Montant_HDIOT_PrecisionAdvance + $Montant_SvwithAR + $Montant_Maxiwide  + $Montant_ifree + $Montant_iAction;

//1.14.7-Afficher les résultats
$message.= "<tr><td align=\"center\">".$Company."</td>";
$message.= "<td align=\"center\">". $SvwithAR . " x 3$ = "."$Montant_SvwithAR $</td>";
$message.= "<td align=\"center\">".$HDIOT_PrecisionAdvance . " x 5$ =   " . $Montant_HDIOT_PrecisionAdvance."$</td>";
$message.= "<td align=\"center\">".$MaxiWide." x 8$ = $Montant_Maxiwide$</td>";
$message.= "<td align=\"center\">".$iFree." x 8$ = $Montant_ifree$</td>";
$message.= "<td align=\"center\">".$iAction ." x 8$ = $Montant_iAction$</td>";	
$message.= "<td align=\"center\">$Total_Incentive$</td>";
$message.= "<td align=\"center\">    </td>";
$Total_BY_DM_Elaine_Macalolooy += $Total_Incentive;
//FIN 88435-West Edmonton
$message.= "<tr><th colspan=\"7\" align=\"right\">TOTAL for Elaine's stores:</th><th>$ $Total_BY_DM_Elaine_Macalolooy</th></tr>";
$message.="</table><br>";
//Fin Elaine Macalolooy











	
	
//echo $message;
//exit();	
	

/*$TotalCommandes = $Nbr_88403  + $Nbr_88408 + $Nbr_88409 + $Nbr_88411 + $Nbr_88413 + $Nbr_88414 + $Nbr_88416 + $Nbr_88429  + $Nbr_88431 
				 + $Nbr_88433 + $Nbr_88434 + $Nbr_88435 + $Nbr_88438 + $Nbr_88439 + $Nbr_88440   + $Nbr_88444 ; 
$totalCharles   = $Amount_88403  + $Amount_88408 + $Amount_88409 + $Amount_88411 + $Amount_88413 + $Amount_88414 + $Amount_88416 + $Amount_88429  + $Amount_88431 
				 + $Amount_88433 + $Amount_88434 + $Amount_88435 + $Amount_88438 + $Amount_88439 + $Amount_88440  + $Amount_88444 ; 
$totalCharles 	= money_format('%.2n',$totalCharles);  		
*
	//$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"3\">$TotalCommandes orders = $totalCharles $</td></tr>	</table></body></html>";
	$message.= "</body></html>";
	
	echo $message;
	
	$subject ="HBC Incentive Report between $date1-$date2";
	//$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','riazzolino@opticalvisiongroup.com');
	$Report_Email	= array('dbeaulieu@direct-lens.com');//A SUPPRIMER
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
	$totalCharles = 0;
	
*/
	
?>
