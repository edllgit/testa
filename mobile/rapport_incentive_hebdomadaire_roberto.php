<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

$date1       = date("Y-m-d");
$date2       = date("Y-m-d");

$date1   	= $_POST[date1];
$date2     	= $_POST[date2];

//Date du rapport
$ilya6jours  	= mktime(0,0,0,date("m"),date("d")-8,date("Y"));
$date1 = date("Y/m/d", $ilya6jours);

$ajd  			= mktime(0,0,0,date("m"),date("d")-2,date("Y"));
$date2     = date("Y/m/d", $ajd);


//DATES HARD CODÉS MANUELLE
/*
$date1        = "2020-06-28";
$date2        = "2020-07-28";
*/


include('../connexion_hbc.inc.php');
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

echo '<br>date1: '. $date1;
echo '<br>date2: '. $date2;
//$GrandTotalpourTouslesHBC = 0;
$LargeurColspanTableau =3;	//Nombre de TD dans le tableau
$WidthTableau = 800;		//Pixels

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
	<th width=\"200\">Store</th>
	<th width=\"200\">Maxiwide sold (Pair)</th>
	<th width=\"200\">Precision Advance sold (Pair)</th>
	<th width=\"200\">ABC Warranty sold</th>
</tr>";
	 


//#1er:Début magasin #88403
$store				= "88403";
$StoreDescription	= "#88403-BLOOR STREET";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88403 			= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88403 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88403 			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88403</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88403</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88403 </td>
</tr>";
//Fin 88403




//Magasin #88408
$store				= "88408";
$StoreDescription	= "#88408-OSHAWA";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88408				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88408 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88408			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88408</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88408</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88408</td>
</tr>";
//Fin 88408




//Magasin #88409
$store				= "88409";
$StoreDescription	= "#88409-EGLINTON";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88409				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88409 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88409			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88409</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88409</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88409</td>
</tr>";
//Fin 88409


//Magasin #88411
$store				= "88411";
$StoreDescription	= "#88411-SHERWAY";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88411				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88411 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88411			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88411</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88411</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88411</td>
</tr>";
//Fin 88411


//Magasin #88414
$store				= "88414";
$StoreDescription	= "#88414-YORKDALE";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88414				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88414 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88414			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88414</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88414</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88414</td>
</tr>";
//Fin 88414


//Magasin #88416
$store				= "88416";
$StoreDescription	= "#88416-VANCOUVER";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88416				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88416 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88416			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88416</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88416</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88416</td>
</tr>";
//Fin 88416



//Magasin #88431
$store				= "88431";
$StoreDescription	= "#88431-CALGARY DTN";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88431				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88431 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88431			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88431</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88431</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88431</td>
</tr>";
//Fin 88431




//Magasin #88433
$store				= "88433";
$StoreDescription	= "#88433-POLO PARK";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88433				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88433 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88433			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88433</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88433</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88433</td>
</tr>";
//Fin 88433





//Magasin #88434
$store				= "88434";
$StoreDescription	= "#88434_MARKET MALL";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88434				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88434 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88434			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88434</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88434</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88434</td>
</tr>";
//Fin 88434


//Magasin #88435
$store				= "88435";
$StoreDescription	= "#88435-WEST EDMONTON";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88435				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88435 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88435			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88435</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88435</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88435</td>
</tr>";
//Fin 88435



//Magasin #88438
$store				= "88438";
$StoreDescription	= "#88438-METROTOWN";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88438				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88438 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88438			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88438</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88438</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88438</td>
</tr>";
//Fin 88438



//Magasin #88439
$store				= "88439";
$StoreDescription	= "#88439-LANGLEY";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88439				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88439 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88439			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88439</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88439</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88439</td>
</tr>";
//Fin 88439




//Magasin #88440
$store				= "88440";
$StoreDescription	= "#88440-RIDEAU";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88440				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88440 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88440			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88440</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88440</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88440</td>
</tr>";
//Fin 88440





//Magasin #88444
$store				= "88444";
$StoreDescription	= "#88444-MAYFAIR";
$queryMaxiWide      = "SELECT count(order_num) as NbrMaxiWide FROM orders WHERE 
						order_product_name like '%Maxiwide%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultMaxiWide     = mysqli_query($con, $queryMaxiWide) or die  ('I cannot select items because #2g: '. $queryMaxiWide . mysqli_error($con));
$DataMaxiWide      	= mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);

$queryPrecisionAdvance  = "SELECT count(order_num) as NbrPrecisionAdvance FROM orders WHERE 
						order_product_name like '%Advance%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultPrecisionAdvance     = mysqli_query($con, $queryPrecisionAdvance) or die  ('I cannot select items because #2g: '. $queryPrecisionAdvance . mysqli_error($con));
$DataPrecisionAdvance     	= mysqli_fetch_array($resultPrecisionAdvance,MYSQLI_ASSOC);

$queryABCWarranty	  	= "SELECT count(order_num) as NbrABCWarranty FROM orders WHERE 
						warranty like '%Extended Warranty%' AND redo_order_num IS NULL
						AND user_id = '$store' AND order_date_processed BETWEEN '$date1' AND '$date2'";
$resultABCWarranty    		= mysqli_query($con, $queryABCWarranty) or die  ('I cannot select items because #2g: '. $queryABCWarranty . mysqli_error($con));
$DataABCWarranty     		= mysqli_fetch_array($resultABCWarranty,MYSQLI_ASSOC);
	
$MaxiWide_88444				= $DataMaxiWide[NbrMaxiWide];
$PrecisionAdvance_88444 	= $DataPrecisionAdvance[NbrPrecisionAdvance];
$ABCWarranty_88444			= $DataABCWarranty[NbrABCWarranty];
//A)Insérer dans le string du email 'Global'
$message.= "<tr\">
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$StoreDescription</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$MaxiWide_88444</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$PrecisionAdvance_88444</td>
	<td width=\"200\" align=\"center\" bgcolor=\"#EFE9E7\">$ABCWarranty_88444</td>
</tr>";
//Fin 88444




echo '<br><br>'.$message;



//Envoie du rapport COMPLET DE TOUS LES MAGASINS par courriel à Daniel, Karine, Amina et moi.

$subject ="Data for Global HBC Incentive Report [$date1-$date2]";
$Report_Email	= array('dbeaulieu@direct-lens.com');//A COMMENTER	
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
