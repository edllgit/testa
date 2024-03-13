<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");


$date1 = "2017-10-01";
$date2 = "2017-12-31";


$message="<html>
		<head>
			<style type='text/css'>
			<!--
	
			.TextSize {
				font-size: 8pt;
				font-family: Arial, Helvetica, sans-serif;
			}
			-->
			</style>
		 </head>";
		 
$Total_Vente_TR  	= 0;
$Total_Vente_SH  	= 0;
$Total_Vente_CH  	= 0;
$Total_Vente_TB  	= 0;
$Total_Vente_LV  	= 0;
$Total_Vente_HA  	= 0;
$Total_Vente_DR  	= 0;
$Total_Vente_LE  	= 0;
$Total_Vente_LO  	= 0;
$Total_Vente_SMB 	= 0;
$Total_Vente_GR  	= 0;	
$TOTAL_VENTE_GLOBAL = 0;

//SECTION VENTE
		 
//PARTIE SWISS PREMIERE COLONNE
$TotalVenteSwiss = 0;

$Query_TR_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_TR_SWISS FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_TR_SWISS_no_Redo 	= mysql_query($Query_TR_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_SWISS_no_redo      = mysql_fetch_array($result_TR_SWISS_no_Redo);
$Vente_TR_SWISS 			= $Data_TR_SWISS_no_redo[NbVente_TR_SWISS];
	
		 
$Query_SH_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_SH_SWISS FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_SH_SWISS_no_Redo 	= mysql_query($Query_SH_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_SWISS_no_redo      = mysql_fetch_array($result_SH_SWISS_no_Redo);
$Vente_SH_SWISS 			= $Data_SH_SWISS_no_redo[NbVente_SH_SWISS];

$Query_GR_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_GR_SWISS FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_GR_SWISS_no_Redo 	= mysql_query($Query_GR_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_SWISS_no_redo      = mysql_fetch_array($result_GR_SWISS_no_Redo);
$Vente_GR_SWISS 			= $Data_GR_SWISS_no_redo[NbVente_GR_SWISS];
	
	
$Query_CH_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_CH_SWISS FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_CH_SWISS_no_Redo 	= mysql_query($Query_CH_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_SWISS_no_redo      = mysql_fetch_array($result_CH_SWISS_no_Redo);
$Vente_CH_SWISS 			= $Data_CH_SWISS_no_redo[NbVente_CH_SWISS];	


$Query_TB_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_TB_SWISS FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_TB_SWISS_no_Redo 	= mysql_query($Query_TB_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_SWISS_no_redo      = mysql_fetch_array($result_TB_SWISS_no_Redo);
$Vente_TB_SWISS 			= $Data_TB_SWISS_no_redo[NbVente_TB_SWISS];		 
		 
		 
$Query_LV_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_LV_SWISS FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_LV_SWISS_no_Redo 	= mysql_query($Query_LV_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_SWISS_no_redo      = mysql_fetch_array($result_LV_SWISS_no_Redo);
$Vente_LV_SWISS 			= $Data_LV_SWISS_no_redo[NbVente_LV_SWISS];		 
	
	
$Query_HA_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_HA_SWISS FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_HA_SWISS_no_Redo 	= mysql_query($Query_HA_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_SWISS_no_redo      = mysql_fetch_array($result_HA_SWISS_no_Redo);
$Vente_HA_SWISS 			= $Data_HA_SWISS_no_redo[NbVente_HA_SWISS];			 		 
	
$Query_DR_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_DR_SWISS FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_DR_SWISS_no_Redo 	= mysql_query($Query_DR_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_SWISS_no_redo      = mysql_fetch_array($result_DR_SWISS_no_Redo);
$Vente_DR_SWISS 			= $Data_DR_SWISS_no_redo[NbVente_DR_SWISS];	

$Query_LE_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_LE_SWISS FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_LE_SWISS_no_Redo 	= mysql_query($Query_LE_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_SWISS_no_redo      = mysql_fetch_array($result_LE_SWISS_no_Redo);
$Vente_LE_SWISS 			= $Data_LE_SWISS_no_redo[NbVente_LE_SWISS];	


$Query_LO_SWISS_no_redo 	= "SELECT count(order_num) as NbVente_LO_SWISS FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NULL";		 
$result_LO_SWISS_no_Redo 	= mysql_query($Query_LO_SWISS_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_SWISS_no_redo      = mysql_fetch_array($result_LO_SWISS_no_Redo);
$Vente_LO_SWISS 			= $Data_LO_SWISS_no_redo[NbVente_LO_SWISS];	




$TotalVenteSwiss = $Vente_SMB_SWISS +$Vente_LO_SWISS + $Vente_LE_SWISS + $Vente_DR_SWISS + $Vente_HA_SWISS +$Vente_LV_SWISS + $Vente_TB_SWISS  +  $Vente_CH_SWISS+ $Vente_SH_SWISS + $Vente_TR_SWISS + $Vente_GR_SWISS ;
$Total_Vente_TR  = $Total_Vente_TR  + $Vente_TR_SWISS;
$Total_Vente_SH  = $Total_Vente_SH  + $Vente_SH_SWISS;
$Total_Vente_CH  = $Total_Vente_CH  + $Vente_CH_SWISS;
$Total_Vente_TB  = $Total_Vente_TB  + $Vente_TB_SWISS;
$Total_Vente_LV  = $Total_Vente_LV  + $Vente_LV_SWISS;
$Total_Vente_HA  = $Total_Vente_HA  + $Vente_HA_SWISS;
$Total_Vente_DR  = $Total_Vente_DR  + $Vente_DR_SWISS;
$Total_Vente_LE  = $Total_Vente_LE  + $Vente_LE_SWISS;
$Total_Vente_LO  = $Total_Vente_LO  + $Vente_LO_SWISS;
$Total_Vente_SMB = $Total_Vente_SMB + $Vente_SMB_SWISS;
$Total_Vente_GR  = $Total_Vente_GR  + $Vente_GR_SWISS;
//Fin partie Swiss = Colonne #1



//PARTIE Dlab 2ieme COLONNE
$TotalVenteDlab = 0;

$Query_TR_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_TR_DLAB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_TR_DLAB_no_Redo    = mysql_query($Query_TR_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_DLAB_no_redo      = mysql_fetch_array($result_TR_DLAB_no_Redo);
$Vente_TR_DLAB 			   = $Data_TR_DLAB_no_redo[NbVente_TR_DLAB];
	 
$Query_SH_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_SH_DLAB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS NULL";		 
$result_SH_DLAB_no_Redo = mysql_query($Query_SH_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_DLAB_no_redo   = mysql_fetch_array($result_SH_DLAB_no_Redo);
$Vente_SH_DLAB 			= $Data_SH_DLAB_no_redo[NbVente_SH_DLAB];
	
	
$Query_GR_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_GR_DLAB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS NULL";		 
$result_GR_DLAB_no_Redo = mysql_query($Query_GR_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_DLAB_no_redo   = mysql_fetch_array($result_GR_DLAB_no_Redo);
$Vente_GR_DLAB 			= $Data_GR_DLAB_no_redo[NbVente_GR_DLAB];	
	
	
$Query_CH_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_CH_DLAB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS NULL";		 
$result_CH_DLAB_no_Redo = mysql_query($Query_CH_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_DLAB_no_redo   = mysql_fetch_array($result_CH_DLAB_no_Redo);
$Vente_CH_DLAB 			= $Data_CH_DLAB_no_redo[NbVente_CH_DLAB];	


$Query_TB_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_TB_DLAB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_TB_DLAB_no_Redo = mysql_query($Query_TB_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_DLAB_no_redo   = mysql_fetch_array($result_TB_DLAB_no_Redo);
$Vente_TB_DLAB 			= $Data_TB_DLAB_no_redo[NbVente_TB_DLAB];		 
		 
		 
$Query_LV_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_LV_DLAB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_LV_DLAB_no_Redo = mysql_query($Query_LV_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_DLAB_no_redo   = mysql_fetch_array($result_LV_DLAB_no_Redo);
$Vente_LV_DLAB 			= $Data_LV_DLAB_no_redo[NbVente_LV_DLAB];		 
	
	
$Query_HA_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_HA_DLAB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_HA_DLAB_no_Redo = mysql_query($Query_HA_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_DLAB_no_redo   = mysql_fetch_array($result_HA_DLAB_no_Redo);
$Vente_HA_DLAB 			= $Data_HA_DLAB_no_redo[NbVente_HA_DLAB];			 		 
	
$Query_DR_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_DR_DLAB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_DR_DLAB_no_Redo = mysql_query($Query_DR_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_DLAB_no_redo   = mysql_fetch_array($result_DR_DLAB_no_Redo);
$Vente_DR_DLAB 			= $Data_DR_DLAB_no_redo[NbVente_DR_DLAB];	

$Query_LE_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_LE_DLAB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_LE_DLAB_no_Redo = mysql_query($Query_LE_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_DLAB_no_redo   = mysql_fetch_array($result_LE_DLAB_no_Redo);
$Vente_LE_DLAB 			= $Data_LE_DLAB_no_redo[NbVente_LE_DLAB];	


$Query_LO_DLAB_no_redo 	= "SELECT count(order_num) as NbVente_LO_DLAB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NULL";		 
$result_LO_DLAB_no_Redo = mysql_query($Query_LO_DLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_DLAB_no_redo   = mysql_fetch_array($result_LO_DLAB_no_Redo);
$Vente_LO_DLAB 			= $Data_LO_DLAB_no_redo[NbVente_LO_DLAB];	




$TotalVenteDLAB =$Vente_SMB_DLAB +$Vente_LO_DLAB + $Vente_LE_DLAB + $Vente_DR_DLAB + $Vente_HA_DLAB +$Vente_LV_DLAB + $Vente_TB_DLAB  +  $Vente_CH_DLAB+ $Vente_SH_DLAB + $Vente_TR_DLAB + $Vente_GR_DLAB ;
$Total_Vente_TR  = $Total_Vente_TR  + $Vente_TR_DLAB;
$Total_Vente_SH  = $Total_Vente_SH  + $Vente_SH_DLAB;
$Total_Vente_CH  = $Total_Vente_CH  + $Vente_CH_DLAB;
$Total_Vente_TB  = $Total_Vente_TB  + $Vente_TB_DLAB;
$Total_Vente_LV  = $Total_Vente_LV  + $Vente_LV_DLAB;
$Total_Vente_HA  = $Total_Vente_HA  + $Vente_HA_DLAB;
$Total_Vente_DR  = $Total_Vente_DR  + $Vente_DR_DLAB;
$Total_Vente_LE  = $Total_Vente_LE  + $Vente_LE_DLAB;
$Total_Vente_LO  = $Total_Vente_LO  + $Vente_LO_DLAB;
$Total_Vente_SMB = $Total_Vente_SMB + $Vente_SMB_DLAB;
$Total_Vente_GR  = $Total_Vente_GR  + $Vente_GR_DLAB;
//Fin partie Dlab = Colonne #2


//PARTIE Central Lab  COLONNE #3
$TotalVenteCentralLab = 0;

$Query_TR_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_TR_CENTRALLAB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_TR_CENTRALLAB_no_Redo    = mysql_query($Query_TR_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_CENTRALLAB_no_redo      = mysql_fetch_array($result_TR_CENTRALLAB_no_Redo);
$Vente_TR_CENTRALLAB 			 = $Data_TR_CENTRALLAB_no_redo[NbVente_TR_CENTRALLAB];
	
		 
$Query_SH_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_SH_CENTRALLAB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25 
AND redo_order_num IS NULL";		 
$result_SH_CENTRALLAB_no_Redo = mysql_query($Query_SH_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_CENTRALLAB_no_redo   = mysql_fetch_array($result_SH_CENTRALLAB_no_Redo);
$Vente_SH_CENTRALLAB 		  = $Data_SH_CENTRALLAB_no_redo[NbVente_SH_CENTRALLAB];
	
$Query_GR_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_GR_CENTRALLAB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25 
AND redo_order_num IS NULL";		 
$result_GR_CENTRALLAB_no_Redo = mysql_query($Query_GR_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_CENTRALLAB_no_redo   = mysql_fetch_array($result_GR_CENTRALLAB_no_Redo);
$Vente_GR_CENTRALLAB 		  = $Data_GR_CENTRALLAB_no_redo[NbVente_GR_CENTRALLAB];	
	
	
$Query_CH_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_CH_CENTRALLAB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25 
AND redo_order_num IS NULL";		 
$result_CH_CENTRALLAB_no_Redo = mysql_query($Query_CH_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_CENTRALLAB_no_redo   = mysql_fetch_array($result_CH_CENTRALLAB_no_Redo);
$Vente_CH_CENTRALLAB 		  = $Data_CH_CENTRALLAB_no_redo[NbVente_CH_CENTRALLAB];	


$Query_TB_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_TB_CENTRALLAB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_TB_CENTRALLAB_no_Redo = mysql_query($Query_TB_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_CENTRALLAB_no_redo   = mysql_fetch_array($result_TB_CENTRALLAB_no_Redo);
$Vente_TB_CENTRALLAB 		  = $Data_TB_CENTRALLAB_no_redo[NbVente_TB_CENTRALLAB];		 
		 
		 
$Query_LV_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_LV_CENTRALLAB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_LV_CENTRALLAB_no_Redo = mysql_query($Query_LV_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_CENTRALLAB_no_redo   = mysql_fetch_array($result_LV_CENTRALLAB_no_Redo);
$Vente_LV_CENTRALLAB 		  = $Data_LV_CENTRALLAB_no_redo[NbVente_LV_CENTRALLAB];		 
	
	
$Query_HA_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_HA_CENTRALLAB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_HA_CENTRALLAB_no_Redo = mysql_query($Query_HA_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_CENTRALLAB_no_redo   = mysql_fetch_array($result_HA_CENTRALLAB_no_Redo);
$Vente_HA_CENTRALLAB 			= $Data_HA_CENTRALLAB_no_redo[NbVente_HA_CENTRALLAB];			 		 
	
$Query_DR_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_DR_CENTRALLAB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_DR_CENTRALLAB_no_Redo = mysql_query($Query_DR_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_CENTRALLAB_no_redo   = mysql_fetch_array($result_DR_CENTRALLAB_no_Redo);
$Vente_DR_CENTRALLAB 		  = $Data_DR_CENTRALLAB_no_redo[NbVente_DR_CENTRALLAB];	

$Query_LE_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_LE_CENTRALLAB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_LE_CENTRALLAB_no_Redo = mysql_query($Query_LE_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_CENTRALLAB_no_redo   = mysql_fetch_array($result_LE_CENTRALLAB_no_Redo);
$Vente_LE_CENTRALLAB 		  = $Data_LE_CENTRALLAB_no_redo[NbVente_LE_CENTRALLAB];	


$Query_LO_CENTRALLAB_no_redo 	= "SELECT count(order_num) as NbVente_LO_CENTRALLAB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NULL";		 
$result_LO_CENTRALLAB_no_Redo = mysql_query($Query_LO_CENTRALLAB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_CENTRALLAB_no_redo   = mysql_fetch_array($result_LO_CENTRALLAB_no_Redo);
$Vente_LO_CENTRALLAB 		  = $Data_LO_CENTRALLAB_no_redo[NbVente_LO_CENTRALLAB];	



$TotalVenteCENTRALLAB =$Vente_SMB_CENTRALLAB +$Vente_LO_CENTRALLAB + $Vente_LE_CENTRALLAB + $Vente_DR_CENTRALLAB + $Vente_HA_CENTRALLAB +$Vente_LV_CENTRALLAB + $Vente_TB_CENTRALLAB + $Vente_CH_CENTRALLAB+ $Vente_SH_CENTRALLAB + $Vente_TR_CENTRALLAB + $Vente_GR_CENTRALLAB ;
$Total_Vente_TR  = $Total_Vente_TR  + $Vente_TR_CENTRALLAB;
$Total_Vente_SH  = $Total_Vente_SH  + $Vente_SH_CENTRALLAB;
$Total_Vente_CH  = $Total_Vente_CH  + $Vente_CH_CENTRALLAB;
$Total_Vente_TB  = $Total_Vente_TB  + $Vente_TB_CENTRALLAB;
$Total_Vente_LV  = $Total_Vente_LV  + $Vente_LV_CENTRALLAB;
$Total_Vente_HA  = $Total_Vente_HA  + $Vente_HA_CENTRALLAB; 
$Total_Vente_DR  = $Total_Vente_DR  + $Vente_DR_CENTRALLAB; 
$Total_Vente_LE  = $Total_Vente_LE  + $Vente_LE_CENTRALLAB; 
$Total_Vente_LO  = $Total_Vente_LO  + $Vente_LO_CENTRALLAB; 
$Total_Vente_SMB = $Total_Vente_SMB + $Vente_SMB_CENTRALLAB; 
$Total_Vente_GR  = $Total_Vente_GR  + $Vente_GR_CENTRALLAB;  
//Fin partie Central Lab = Colonne #3




//PARTIE GKB COLONNE 4
$TotalVenteGKB = 0;

$Query_TR_GKB_no_redo 	= "SELECT count(order_num) as NbVente_TR_GKB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_TR_GKB_no_Redo    = mysql_query($Query_TR_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_GKB_no_redo      = mysql_fetch_array($result_TR_GKB_no_Redo);
$Vente_TR_GKB 			  = $Data_TR_GKB_no_redo[NbVente_TR_GKB];
	
		 
$Query_SH_GKB_no_redo 	= "SELECT count(order_num) as NbVente_SH_GKB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_SH_GKB_no_Redo = mysql_query($Query_SH_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_GKB_no_redo   = mysql_fetch_array($result_SH_GKB_no_Redo);
$Vente_SH_GKB 		   = $Data_SH_GKB_no_redo[NbVente_SH_GKB];
	
$Query_GR_GKB_no_redo 	= "SELECT count(order_num) as NbVente_GR_GKB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_GR_GKB_no_Redo = mysql_query($Query_GR_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_GKB_no_redo   = mysql_fetch_array($result_GR_GKB_no_Redo);
$Vente_GR_GKB 		   = $Data_GR_GKB_no_redo[NbVente_GR_GKB];
	
	
$Query_CH_GKB_no_redo 	= "SELECT count(order_num) as NbVente_CH_GKB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_CH_GKB_no_Redo = mysql_query($Query_CH_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_GKB_no_redo   = mysql_fetch_array($result_CH_GKB_no_Redo);
$Vente_CH_GKB 		   = $Data_CH_GKB_no_redo[NbVente_CH_GKB];	


$Query_TB_GKB_no_redo 	= "SELECT count(order_num) as NbVente_TB_GKB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_TB_GKB_no_Redo = mysql_query($Query_TB_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_GKB_no_redo   = mysql_fetch_array($result_TB_GKB_no_Redo);
$Vente_TB_GKB 		   = $Data_TB_GKB_no_redo[NbVente_TB_GKB];		 
		 
		 
$Query_LV_GKB_no_redo 	= "SELECT count(order_num) as NbVente_LV_GKB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_LV_GKB_no_Redo = mysql_query($Query_LV_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_GKB_no_redo   = mysql_fetch_array($result_LV_GKB_no_Redo);
$Vente_LV_GKB 		   = $Data_LV_GKB_no_redo[NbVente_LV_GKB];		 
	
	
$Query_HA_GKB_no_redo 	= "SELECT count(order_num) as NbVente_HA_GKB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_HA_GKB_no_Redo = mysql_query($Query_HA_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_GKB_no_redo   = mysql_fetch_array($result_HA_GKB_no_Redo);
$Vente_HA_GKB 		   = $Data_HA_GKB_no_redo[NbVente_HA_GKB];			 		 
	
$Query_DR_GKB_no_redo 	= "SELECT count(order_num) as NbVente_DR_GKB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_DR_GKB_no_Redo = mysql_query($Query_DR_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_GKB_no_redo   = mysql_fetch_array($result_DR_GKB_no_Redo);
$Vente_DR_GKB 		   = $Data_DR_GKB_no_redo[NbVente_DR_GKB];	

$Query_LE_GKB_no_redo 	= "SELECT count(order_num) as NbVente_LE_GKB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_LE_GKB_no_Redo = mysql_query($Query_LE_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_GKB_no_redo   = mysql_fetch_array($result_LE_GKB_no_Redo);
$Vente_LE_GKB 		   = $Data_LE_GKB_no_redo[NbVente_LE_GKB];	


$Query_LO_GKB_no_redo 	= "SELECT count(order_num) as NbVente_LO_GKB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NULL";		 
$result_LO_GKB_no_Redo = mysql_query($Query_LO_GKB_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_GKB_no_redo   = mysql_fetch_array($result_LO_GKB_no_Redo);
$Vente_LO_GKB 		   = $Data_LO_GKB_no_redo[NbVente_LO_GKB];	




$TotalVenteGKB =$Vente_SMB_GKB +$Vente_LO_GKB + $Vente_LE_GKB + $Vente_DR_GKB + $Vente_HA_GKB +$Vente_LV_GKB + $Vente_TB_GKB  +  $Vente_CH_GKB+ $Vente_SH_GKB + $Vente_TR_GKB + $Vente_GR_GKB ;
$Total_Vente_TR  = $Total_Vente_TR  + $Vente_TR_GKB;
$Total_Vente_SH  = $Total_Vente_SH  + $Vente_SH_GKB;
$Total_Vente_CH  = $Total_Vente_CH  + $Vente_CH_GKB;
$Total_Vente_TB  = $Total_Vente_TB  + $Vente_TB_GKB;
$Total_Vente_LV  = $Total_Vente_LV  + $Vente_LV_GKB;
$Total_Vente_HA  = $Total_Vente_HA  + $Vente_HA_GKB;
$Total_Vente_DR  = $Total_Vente_DR  + $Vente_DR_GKB;
$Total_Vente_LE  = $Total_Vente_LE  + $Vente_LE_GKB;
$Total_Vente_LO  = $Total_Vente_LO  + $Vente_LO_GKB;
$Total_Vente_SMB = $Total_Vente_SMB + $Vente_SMB_GKB;
$Total_Vente_GR  = $Total_Vente_GR  + $Vente_GR_GKB;
//Fin partie Essilor Lab = Colonne #4




//PARTIE AUTRES COLONNE 5
$TotalVenteAUTRES = 0;

$Query_TR_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_TR_AUTRES FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_TR_AUTRES_no_Redo    = mysql_query($Query_TR_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_AUTRES_no_redo      = mysql_fetch_array($result_TR_AUTRES_no_Redo);
$Vente_TR_AUTRES 			  = $Data_TR_AUTRES_no_redo[NbVente_TR_AUTRES];
	
		 
$Query_SH_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_SH_AUTRES FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_SH_AUTRES_no_Redo = mysql_query($Query_SH_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_AUTRES_no_redo   = mysql_fetch_array($result_SH_AUTRES_no_Redo);
$Vente_SH_AUTRES 		   = $Data_SH_AUTRES_no_redo[NbVente_SH_AUTRES];
	
	
$Query_GR_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_GR_AUTRES FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_GR_AUTRES_no_Redo = mysql_query($Query_GR_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_AUTRES_no_redo   = mysql_fetch_array($result_GR_AUTRES_no_Redo);
$Vente_GR_AUTRES 		  = $Data_GR_AUTRES_no_redo[NbVente_GR_AUTRES];
		
	
$Query_CH_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_CH_AUTRES FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_CH_AUTRES_no_Redo = mysql_query($Query_CH_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_AUTRES_no_redo   = mysql_fetch_array($result_CH_AUTRES_no_Redo);
$Vente_CH_AUTRES 		   = $Data_CH_AUTRES_no_redo[NbVente_CH_AUTRES];	


$Query_TB_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_TB_AUTRES FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_TB_AUTRES_no_Redo = mysql_query($Query_TB_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_AUTRES_no_redo   = mysql_fetch_array($result_TB_AUTRES_no_Redo);
$Vente_TB_AUTRES 		   = $Data_TB_AUTRES_no_redo[NbVente_TB_AUTRES];		 
		 
		 
$Query_LV_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_LV_AUTRES FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_LV_AUTRES_no_Redo = mysql_query($Query_LV_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_AUTRES_no_redo   = mysql_fetch_array($result_LV_AUTRES_no_Redo);
$Vente_LV_AUTRES 		   = $Data_LV_AUTRES_no_redo[NbVente_LV_AUTRES];		 
	
	
$Query_HA_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_HA_AUTRES FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_HA_AUTRES_no_Redo = mysql_query($Query_HA_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_AUTRES_no_redo   = mysql_fetch_array($result_HA_AUTRES_no_Redo);
$Vente_HA_AUTRES 		   = $Data_HA_AUTRES_no_redo[NbVente_HA_AUTRES];			 		 
	
$Query_DR_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_DR_AUTRES FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_DR_AUTRES_no_Redo = mysql_query($Query_DR_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_AUTRES_no_redo   = mysql_fetch_array($result_DR_AUTRES_no_Redo);
$Vente_DR_AUTRES 		   = $Data_DR_AUTRES_no_redo[NbVente_DR_AUTRES];	

$Query_LE_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_LE_AUTRES FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_LE_AUTRES_no_Redo = mysql_query($Query_LE_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_AUTRES_no_redo   = mysql_fetch_array($result_LE_AUTRES_no_Redo);
$Vente_LE_AUTRES 		   = $Data_LE_AUTRES_no_redo[NbVente_LE_AUTRES];	


$Query_LO_AUTRES_no_redo 	= "SELECT count(order_num) as NbVente_LO_AUTRES FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NULL";		 
$result_LO_AUTRES_no_Redo = mysql_query($Query_LO_AUTRES_no_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_AUTRES_no_redo   = mysql_fetch_array($result_LO_AUTRES_no_Redo);
$Vente_LO_AUTRES 		   = $Data_LO_AUTRES_no_redo[NbVente_LO_AUTRES];	




$TotalVenteAUTRES =$Vente_SMB_AUTRES +$Vente_LO_AUTRES + $Vente_LE_AUTRES + $Vente_DR_AUTRES + $Vente_HA_AUTRES +$Vente_LV_AUTRES + $Vente_TB_AUTRES  +  $Vente_CH_AUTRES+ $Vente_SH_AUTRES + $Vente_TR_AUTRES + $Vente_GR_AUTRES ;
$Total_Vente_TR  = $Total_Vente_TR  + $Vente_TR_AUTRES;
$Total_Vente_SH  = $Total_Vente_SH  + $Vente_SH_AUTRES;
$Total_Vente_CH  = $Total_Vente_CH  + $Vente_CH_AUTRES;
$Total_Vente_TB  = $Total_Vente_TB  + $Vente_TB_AUTRES;
$Total_Vente_LV  = $Total_Vente_LV  + $Vente_LV_AUTRES;
$Total_Vente_HA  = $Total_Vente_HA  + $Vente_HA_AUTRES;
$Total_Vente_DR  = $Total_Vente_DR  + $Vente_DR_AUTRES;
$Total_Vente_LE  = $Total_Vente_LE  + $Vente_LE_AUTRES;
$Total_Vente_LO  = $Total_Vente_LO  + $Vente_LO_AUTRES;
$Total_Vente_SMB = $Total_Vente_SMB + $Vente_SMB_AUTRES;
$Total_Vente_GR  = $Total_Vente_GR  + $Vente_GR_AUTRES;
//Fin partie AUTRES = Colonne #5

$TOTAL_VENTE_GLOBAL = $Total_Vente_TR + $Total_Vente_SH + $Total_Vente_CH + $Total_Vente_TB + $Total_Vente_LV + $Total_Vente_HA + $Total_Vente_DR + $Total_Vente_LE + $Total_Vente_LO + $Total_Vente_SMB + $Total_Vente_GR;


//SECTION 2: REPRISES

//REPRISES SWISS PREMIERE COLONNE
$TotalRepriseSwiss = 0;

$Query_TR_SWISS_redo 	= "SELECT count(order_num) as NbReprise_TR_SWISS FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS  NOT NULL";		 
$result_TR_SWISS_Redo 	= mysql_query($Query_TR_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_SWISS_redo     = mysql_fetch_array($result_TR_SWISS_Redo);
$Reprise_TR_SWISS 		= $Data_TR_SWISS_redo[NbReprise_TR_SWISS];
	
		 
$Query_SH_SWISS_redo 	= "SELECT count(order_num) as NbReprise_SH_SWISS FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_SH_SWISS_Redo 	= mysql_query($Query_SH_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_SWISS_redo     = mysql_fetch_array($result_SH_SWISS_Redo);
$Reprise_SH_SWISS 		= $Data_SH_SWISS_redo[NbReprise_SH_SWISS];

$Query_GR_SWISS_redo 	= "SELECT count(order_num) as NbReprise_GR_SWISS FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_GR_SWISS_Redo 	= mysql_query($Query_GR_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_SWISS_redo     = mysql_fetch_array($result_GR_SWISS_Redo);
$Reprise_GR_SWISS 		= $Data_GR_SWISS_redo[NbReprise_GR_SWISS];
	
	
$Query_CH_SWISS_redo 	= "SELECT count(order_num) as NbReprise_CH_SWISS FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_CH_SWISS_Redo 	= mysql_query($Query_CH_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_SWISS_redo     = mysql_fetch_array($result_CH_SWISS_Redo);
$Reprise_CH_SWISS 		= $Data_CH_SWISS_redo[NbReprise_CH_SWISS];	


$Query_TB_SWISS_redo 	= "SELECT count(order_num) as NbVente_TB_SWISS FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_TB_SWISS_Redo 	= mysql_query($Query_TB_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_SWISS_redo     = mysql_fetch_array($result_TB_SWISS_Redo);
$Reprise_TB_SWISS 		= $Data_TB_SWISS_redo[NbVente_TB_SWISS];		 
		 
		 
$Query_LV_SWISS_redo 	= "SELECT count(order_num) as NbReprise_LV_SWISS FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_LV_SWISS_Redo 	= mysql_query($Query_LV_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_SWISS_redo     = mysql_fetch_array($result_LV_SWISS_Redo);
$Reprise_LV_SWISS 		= $Data_LV_SWISS_redo[NbReprise_LV_SWISS];		 
	
	
$Query_HA_SWISS_redo 	= "SELECT count(order_num) as NbReprise_HA_SWISS FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_HA_SWISS_Redo 	= mysql_query($Query_HA_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_SWISS_redo     = mysql_fetch_array($result_HA_SWISS_Redo);
$Reprise_HA_SWISS 		= $Data_HA_SWISS_redo[NbReprise_HA_SWISS];			 		 
	
$Query_DR_SWISS_redo 	= "SELECT count(order_num) as NbReprise_DR_SWISS FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_DR_SWISS_Redo 	= mysql_query($Query_DR_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_SWISS_redo     = mysql_fetch_array($result_DR_SWISS_Redo);
$Reprise_DR_SWISS 		= $Data_DR_SWISS_redo[NbReprise_DR_SWISS];	

$Query_LE_SWISS_redo 	= "SELECT count(order_num) as NbReprise_LE_SWISS FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS NOT NULL";		 
$result_LE_SWISS_Redo 	= mysql_query($Query_LE_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_SWISS_redo     = mysql_fetch_array($result_LE_SWISS_Redo);
$Reprise_LE_SWISS 		= $Data_LE_SWISS_redo[NbReprise_LE_SWISS];	


$Query_LO_SWISS_redo 	= "SELECT count(order_num) as NbReprise_LO_SWISS FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 10 
AND redo_order_num IS  NOT NULL";		 
$result_LO_SWISS_Redo 	= mysql_query($Query_LO_SWISS_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_SWISS_redo     = mysql_fetch_array($result_LO_SWISS_Redo);
$Reprise_LO_SWISS 		= $Data_LO_SWISS_redo[NbReprise_LO_SWISS];	




$TotalRepriseSwiss = $Reprise_SMB_SWISS +$Reprise_LO_SWISS + $Reprise_LE_SWISS + $Reprise_DR_SWISS + $Reprise_HA_SWISS +$Reprise_LV_SWISS + $Reprise_TB_SWISS  +  $Reprise_CH_SWISS+ $Reprise_SH_SWISS + $Reprise_TR_SWISS + $Reprise_GR_SWISS ;
$Total_Reprise_TR  = $Total_Reprise_TR  + $Reprise_TR_SWISS;
$Total_Reprise_SH  = $Total_Reprise_SH  + $Reprise_SH_SWISS;
$Total_Reprise_CH  = $Total_Reprise_CH  + $Reprise_CH_SWISS;
$Total_Reprise_TB  = $Total_Reprise_TB  + $Reprise_TB_SWISS;
$Total_Reprise_LV  = $Total_Reprise_LV  + $Reprise_LV_SWISS;
$Total_Reprise_HA  = $Total_Reprise_HA  + $Reprise_HA_SWISS;
$Total_Reprise_DR  = $Total_Reprise_DR  + $Reprise_DR_SWISS;
$Total_Reprise_LE  = $Total_Reprise_LE  + $Reprise_LE_SWISS;
$Total_Reprise_LO  = $Total_Reprise_LO  + $Reprise_LO_SWISS;
$Total_Reprise_SMB = $Total_Reprise_SMB + $Reprise_SMB_SWISS;
$Total_Reprise_GR  = $Total_Reprise_GR  + $Reprise_GR_SWISS;
//Fin Reprises partie Swiss = Colonne #1


//REPRISES DLAB 2ieme COLONNE
$TotalRepriseDlab = 0;

$Query_TR_DLAB_redo 	= "SELECT count(order_num) as NbReprise_TR_DLAB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS  NOT NULL";		 
$result_TR_DLAB_Redo 	= mysql_query($Query_TR_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_DLAB_redo      = mysql_fetch_array($result_TR_DLAB_Redo);
$Reprise_TR_DLAB 		= $Data_TR_DLAB_redo[NbReprise_TR_DLAB];
	
		 
$Query_SH_DLAB_redo 	= "SELECT count(order_num) as NbReprise_SH_DLAB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_SH_DLAB_Redo 	= mysql_query($Query_SH_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_DLAB_redo      = mysql_fetch_array($result_SH_DLAB_Redo);
$Reprise_SH_DLAB 		= $Data_SH_DLAB_redo[NbReprise_SH_DLAB];

$Query_GR_DLAB_redo 	= "SELECT count(order_num) as NbReprise_GR_DLAB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS NOT NULL";		 
$result_GR_DLAB_Redo 	= mysql_query($Query_GR_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_DLAB_redo      = mysql_fetch_array($result_GR_DLAB_Redo);
$Reprise_GR_DLAB 		= $Data_GR_DLAB_redo[NbReprise_GR_DLAB];
	
	
$Query_CH_DLAB_redo 	= "SELECT count(order_num) as NbReprise_CH_DLAB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_CH_DLAB_Redo 	= mysql_query($Query_CH_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_DLAB_redo      = mysql_fetch_array($result_CH_DLAB_Redo);
$Reprise_CH_DLAB 		= $Data_CH_DLAB_redo[NbReprise_CH_DLAB];	


$Query_TB_DLAB_redo 	= "SELECT count(order_num) as NbVente_TB_DLAB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_TB_DLAB_Redo 	= mysql_query($Query_TB_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_DLAB_redo      = mysql_fetch_array($result_TB_DLAB_Redo);
$Reprise_TB_DLAB 		= $Data_TB_DLAB_redo[NbVente_TB_DLAB];		 
		 
		 
$Query_LV_DLAB_redo 	= "SELECT count(order_num) as NbReprise_LV_DLAB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS NOT NULL";		 
$result_LV_DLAB_Redo 	= mysql_query($Query_LV_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_DLAB_redo      = mysql_fetch_array($result_LV_DLAB_Redo);
$Reprise_LV_DLAB 		= $Data_LV_DLAB_redo[NbReprise_LV_DLAB];		 
	
	
$Query_HA_DLAB_redo 	= "SELECT count(order_num) as NbReprise_HA_DLAB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_HA_DLAB_Redo 	= mysql_query($Query_HA_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_DLAB_redo      = mysql_fetch_array($result_HA_DLAB_Redo);
$Reprise_HA_DLAB 		= $Data_HA_DLAB_redo[NbReprise_HA_DLAB];			 		 
	
$Query_DR_DLAB_redo 	= "SELECT count(order_num) as NbReprise_DR_DLAB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_DR_DLAB_Redo 	= mysql_query($Query_DR_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_DLAB_redo      = mysql_fetch_array($result_DR_DLAB_Redo);
$Reprise_DR_DLAB 		= $Data_DR_DLAB_redo[NbReprise_DR_DLAB];	

$Query_LE_DLAB_redo 	= "SELECT count(order_num) as NbReprise_LE_DLAB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3
AND redo_order_num IS NOT NULL";		 
$result_LE_DLAB_Redo 	= mysql_query($Query_LE_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_DLAB_redo      = mysql_fetch_array($result_LE_DLAB_Redo);
$Reprise_LE_DLAB 		= $Data_LE_DLAB_redo[NbReprise_LE_DLAB];	


$Query_LO_DLAB_redo 	= "SELECT count(order_num) as NbReprise_LO_DLAB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 3 
AND redo_order_num IS  NOT NULL";		 
$result_LO_DLAB_Redo 	= mysql_query($Query_LO_DLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_DLAB_redo      = mysql_fetch_array($result_LO_DLAB_Redo);
$Reprise_LO_DLAB 		= $Data_LO_DLAB_redo[NbReprise_LO_DLAB];	



$TotalRepriseDLAB = $Reprise_SMB_DLAB +$Reprise_LO_DLAB + $Reprise_LE_DLAB + $Reprise_DR_DLAB + $Reprise_HA_DLAB +$Reprise_LV_DLAB + $Reprise_TB_DLAB  +  $Reprise_CH_DLAB+ $Reprise_SH_DLAB + $Reprise_TR_DLAB + $Reprise_GR_DLAB ;
$Total_Reprise_TR  = $Total_Reprise_TR  + $Reprise_TR_DLAB;
$Total_Reprise_SH  = $Total_Reprise_SH  + $Reprise_SH_DLAB;
$Total_Reprise_CH  = $Total_Reprise_CH  + $Reprise_CH_DLAB;
$Total_Reprise_TB  = $Total_Reprise_TB  + $Reprise_TB_DLAB;
$Total_Reprise_LV  = $Total_Reprise_LV  + $Reprise_LV_DLAB;
$Total_Reprise_HA  = $Total_Reprise_HA  + $Reprise_HA_DLAB;
$Total_Reprise_DR  = $Total_Reprise_DR  + $Reprise_DR_DLAB;
$Total_Reprise_LE  = $Total_Reprise_LE  + $Reprise_LE_DLAB;
$Total_Reprise_LO  = $Total_Reprise_LO  + $Reprise_LO_DLAB;
$Total_Reprise_SMB = $Total_Reprise_SMB + $Reprise_SMB_DLAB;
$Total_Reprise_GR  = $Total_Reprise_GR  + $Reprise_GR_DLAB;
//Fin Reprises partie Dlab = Colonne #2



//REPRISES Central Lab  3ieme COLONNE
$TotalRepriseCENTRALLAB = 0;

$Query_TR_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_TR_CENTRALLAB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS  NOT NULL";		 
$result_TR_CENTRALLAB_Redo 	= mysql_query($Query_TR_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_CENTRALLAB_redo    = mysql_fetch_array($result_TR_CENTRALLAB_Redo);
$Reprise_TR_CENTRALLAB 		= $Data_TR_CENTRALLAB_redo[NbReprise_TR_CENTRALLAB];
	
		 
$Query_SH_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_SH_CENTRALLAB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_SH_CENTRALLAB_Redo 	= mysql_query($Query_SH_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_CENTRALLAB_redo    = mysql_fetch_array($result_SH_CENTRALLAB_Redo);
$Reprise_SH_CENTRALLAB 		= $Data_SH_CENTRALLAB_redo[NbReprise_SH_CENTRALLAB];

$Query_GR_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_GR_CENTRALLAB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25 
AND redo_order_num IS NOT NULL";		 
$result_GR_CENTRALLAB_Redo 	= mysql_query($Query_GR_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_CENTRALLAB_redo    = mysql_fetch_array($result_GR_CENTRALLAB_Redo);
$Reprise_GR_CENTRALLAB 		= $Data_GR_CENTRALLAB_redo[NbReprise_GR_CENTRALLAB];
	
	
$Query_CH_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_CH_CENTRALLAB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_CH_CENTRALLAB_Redo 	= mysql_query($Query_CH_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_CENTRALLAB_redo    = mysql_fetch_array($result_CH_CENTRALLAB_Redo);
$Reprise_CH_CENTRALLAB 		= $Data_CH_CENTRALLAB_redo[NbReprise_CH_CENTRALLAB];	


$Query_TB_CENTRALLAB_redo 	= "SELECT count(order_num) as NbVente_TB_CENTRALLAB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_TB_CENTRALLAB_Redo 	= mysql_query($Query_TB_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_CENTRALLAB_redo    = mysql_fetch_array($result_TB_CENTRALLAB_Redo);
$Reprise_TB_CENTRALLAB 		= $Data_TB_CENTRALLAB_redo[NbVente_TB_CENTRALLAB];		 
		 
		 
$Query_LV_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_LV_CENTRALLAB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_LV_CENTRALLAB_Redo 	= mysql_query($Query_LV_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_CENTRALLAB_redo    = mysql_fetch_array($result_LV_CENTRALLAB_Redo);
$Reprise_LV_CENTRALLAB 		= $Data_LV_CENTRALLAB_redo[NbReprise_LV_CENTRALLAB];		 
	
	
$Query_HA_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_HA_CENTRALLAB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_HA_CENTRALLAB_Redo 	= mysql_query($Query_HA_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_CENTRALLAB_redo    = mysql_fetch_array($result_HA_CENTRALLAB_Redo);
$Reprise_HA_CENTRALLAB 		= $Data_HA_CENTRALLAB_redo[NbReprise_HA_CENTRALLAB];			 		 
	
$Query_DR_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_DR_CENTRALLAB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_DR_CENTRALLAB_Redo 	= mysql_query($Query_DR_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_CENTRALLAB_redo    = mysql_fetch_array($result_DR_CENTRALLAB_Redo);
$Reprise_DR_CENTRALLAB 		= $Data_DR_CENTRALLAB_redo[NbReprise_DR_CENTRALLAB];	

$Query_LE_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_LE_CENTRALLAB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS NOT NULL";		 
$result_LE_CENTRALLAB_Redo 	= mysql_query($Query_LE_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_CENTRALLAB_redo    = mysql_fetch_array($result_LE_CENTRALLAB_Redo);
$Reprise_LE_CENTRALLAB 		= $Data_LE_CENTRALLAB_redo[NbReprise_LE_CENTRALLAB];	


$Query_LO_CENTRALLAB_redo 	= "SELECT count(order_num) as NbReprise_LO_CENTRALLAB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 25
AND redo_order_num IS  NOT NULL";		 
$result_LO_CENTRALLAB_Redo 	= mysql_query($Query_LO_CENTRALLAB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_CENTRALLAB_redo    = mysql_fetch_array($result_LO_CENTRALLAB_Redo);
$Reprise_LO_CENTRALLAB 		= $Data_LO_CENTRALLAB_redo[NbReprise_LO_CENTRALLAB];	



$TotalRepriseCENTRALLAB = $Reprise_SMB_CENTRALLAB +$Reprise_LO_CENTRALLAB + $Reprise_LE_CENTRALLAB + $Reprise_DR_CENTRALLAB + $Reprise_HA_CENTRALLAB +$Reprise_LV_CENTRALLAB + $Reprise_TB_CENTRALLAB  +  $Reprise_CH_CENTRALLAB+ $Reprise_SH_CENTRALLAB + $Reprise_TR_CENTRALLAB + $Reprise_GR_CENTRALLAB ;
$Total_Reprise_TR  = $Total_Reprise_TR  + $Reprise_TR_CENTRALLAB;
$Total_Reprise_SH  = $Total_Reprise_SH  + $Reprise_SH_CENTRALLAB;
$Total_Reprise_CH  = $Total_Reprise_CH  + $Reprise_CH_CENTRALLAB;
$Total_Reprise_TB  = $Total_Reprise_TB  + $Reprise_TB_CENTRALLAB;
$Total_Reprise_LV  = $Total_Reprise_LV  + $Reprise_LV_CENTRALLAB;
$Total_Reprise_HA  = $Total_Reprise_HA  + $Reprise_HA_CENTRALLAB;
$Total_Reprise_DR  = $Total_Reprise_DR  + $Reprise_DR_CENTRALLAB;
$Total_Reprise_LE  = $Total_Reprise_LE  + $Reprise_LE_CENTRALLAB;
$Total_Reprise_LO  = $Total_Reprise_LO  + $Reprise_LO_CENTRALLAB;
$Total_Reprise_SMB = $Total_Reprise_SMB + $Reprise_SMB_CENTRALLAB;
$Total_Reprise_GR  = $Total_Reprise_GR  + $Reprise_GR_CENTRALLAB;
//Fin Reprises partie Central Lab = Colonne #3



//REPRISES GKB 4ieme COLONNE
$TotalRepriseGKB = 0;

$Query_TR_GKB_redo 	= "SELECT count(order_num) as NbReprise_TR_GKB FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS  NOT NULL";		 
$result_TR_GKB_Redo 	= mysql_query($Query_TR_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_GKB_redo    = mysql_fetch_array($result_TR_GKB_Redo);
$Reprise_TR_GKB 		= $Data_TR_GKB_redo[NbReprise_TR_GKB];
	
		 
$Query_SH_GKB_redo 	= "SELECT count(order_num) as NbReprise_SH_GKB FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_SH_GKB_Redo 	= mysql_query($Query_SH_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_GKB_redo    = mysql_fetch_array($result_SH_GKB_Redo);
$Reprise_SH_GKB 		= $Data_SH_GKB_redo[NbReprise_SH_GKB];

$Query_GR_GKB_redo 	= "SELECT count(order_num) as NbReprise_GR_GKB FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69 
AND redo_order_num IS NOT NULL";		 
$result_GR_GKB_Redo 	= mysql_query($Query_GR_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_GKB_redo    = mysql_fetch_array($result_GR_GKB_Redo);
$Reprise_GR_GKB 		= $Data_GR_GKB_redo[NbReprise_GR_GKB];
	
	
$Query_CH_GKB_redo 	= "SELECT count(order_num) as NbReprise_CH_GKB FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_CH_GKB_Redo 	= mysql_query($Query_CH_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_GKB_redo    = mysql_fetch_array($result_CH_GKB_Redo);
$Reprise_CH_GKB 		= $Data_CH_GKB_redo[NbReprise_CH_GKB];	


$Query_TB_GKB_redo 	= "SELECT count(order_num) as NbVente_TB_GKB FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_TB_GKB_Redo 	= mysql_query($Query_TB_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_GKB_redo    = mysql_fetch_array($result_TB_GKB_Redo);
$Reprise_TB_GKB 		= $Data_TB_GKB_redo[NbVente_TB_GKB];		 
		 
		 
$Query_LV_GKB_redo 	= "SELECT count(order_num) as NbReprise_LV_GKB FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_LV_GKB_Redo 	= mysql_query($Query_LV_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_GKB_redo    = mysql_fetch_array($result_LV_GKB_Redo);
$Reprise_LV_GKB 		= $Data_LV_GKB_redo[NbReprise_LV_GKB];		 
	
	
$Query_HA_GKB_redo 	= "SELECT count(order_num) as NbReprise_HA_GKB FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_HA_GKB_Redo 	= mysql_query($Query_HA_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_GKB_redo    = mysql_fetch_array($result_HA_GKB_Redo);
$Reprise_HA_GKB 		= $Data_HA_GKB_redo[NbReprise_HA_GKB];			 		 
	
$Query_DR_GKB_redo 	= "SELECT count(order_num) as NbReprise_DR_GKB FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_DR_GKB_Redo 	= mysql_query($Query_DR_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_GKB_redo    = mysql_fetch_array($result_DR_GKB_Redo);
$Reprise_DR_GKB 		= $Data_DR_GKB_redo[NbReprise_DR_GKB];	

$Query_LE_GKB_redo 	= "SELECT count(order_num) as NbReprise_LE_GKB FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS NOT NULL";		 
$result_LE_GKB_Redo 	= mysql_query($Query_LE_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_GKB_redo    = mysql_fetch_array($result_LE_GKB_Redo);
$Reprise_LE_GKB 		= $Data_LE_GKB_redo[NbReprise_LE_GKB];	


$Query_LO_GKB_redo 	= "SELECT count(order_num) as NbReprise_LO_GKB FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab = 69
AND redo_order_num IS  NOT NULL";		 
$result_LO_GKB_Redo 	= mysql_query($Query_LO_GKB_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_GKB_redo    = mysql_fetch_array($result_LO_GKB_Redo);
$Reprise_LO_GKB 		= $Data_LO_GKB_redo[NbReprise_LO_GKB];	



$TotalRepriseGKB = $Reprise_SMB_GKB +$Reprise_LO_GKB + $Reprise_LE_GKB + $Reprise_DR_GKB + $Reprise_HA_GKB +$Reprise_LV_GKB + $Reprise_TB_GKB  +  $Reprise_CH_GKB+ $Reprise_SH_GKB + $Reprise_TR_GKB + $Reprise_GR_GKB ;
$Total_Reprise_TR  = $Total_Reprise_TR  + $Reprise_TR_GKB;
$Total_Reprise_SH  = $Total_Reprise_SH  + $Reprise_SH_GKB;
$Total_Reprise_CH  = $Total_Reprise_CH  + $Reprise_CH_GKB;
$Total_Reprise_TB  = $Total_Reprise_TB  + $Reprise_TB_GKB;
$Total_Reprise_LV  = $Total_Reprise_LV  + $Reprise_LV_GKB;
$Total_Reprise_HA  = $Total_Reprise_HA  + $Reprise_HA_GKB;
$Total_Reprise_DR  = $Total_Reprise_DR  + $Reprise_DR_GKB;
$Total_Reprise_LE  = $Total_Reprise_LE  + $Reprise_LE_GKB;
$Total_Reprise_LO  = $Total_Reprise_LO  + $Reprise_LO_GKB;
$Total_Reprise_SMB = $Total_Reprise_SMB + $Reprise_SMB_GKB;
$Total_Reprise_GR  = $Total_Reprise_GR  + $Reprise_GR_GKB;
//Fin Reprises partie GKB = Colonne #4



//REPRISES AUTRES 5ieme COLONNE
$TotalRepriseAUTRES = 0;

$Query_TR_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_TR_AUTRES FROM orders 
WHERE user_id IN ('entrepotifc','entrepotsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS  NOT NULL";		 
$result_TR_AUTRES_Redo 	= mysql_query($Query_TR_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TR_AUTRES_redo    = mysql_fetch_array($result_TR_AUTRES_Redo);
$Reprise_TR_AUTRES 		= $Data_TR_AUTRES_redo[NbReprise_TR_AUTRES];
	
		 
$Query_SH_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_SH_AUTRES FROM orders 
WHERE user_id IN ('sherbrooke','sherbrookesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_SH_AUTRES_Redo 	= mysql_query($Query_SH_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_SH_AUTRES_redo    = mysql_fetch_array($result_SH_AUTRES_Redo);
$Reprise_SH_AUTRES 		= $Data_SH_AUTRES_redo[NbReprise_SH_AUTRES];

$Query_GR_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_GR_AUTRES FROM orders 
WHERE user_id IN ('granby','granbysafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_GR_AUTRES_Redo 	= mysql_query($Query_GR_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_GR_AUTRES_redo    = mysql_fetch_array($result_GR_AUTRES_Redo);
$Reprise_GR_AUTRES 		= $Data_GR_AUTRES_redo[NbReprise_GR_AUTRES];
	
	
$Query_CH_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_CH_AUTRES FROM orders 
WHERE user_id IN ('chicoutimi','chicoutimisafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_CH_AUTRES_Redo 	= mysql_query($Query_CH_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_CH_AUTRES_redo    = mysql_fetch_array($result_CH_AUTRES_Redo);
$Reprise_CH_AUTRES 		= $Data_CH_AUTRES_redo[NbReprise_CH_AUTRES];	


$Query_TB_AUTRES_redo 	= "SELECT count(order_num) as NbVente_TB_AUTRES FROM orders 
WHERE user_id IN ('terrebonne','terrebonnesafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_TB_AUTRES_Redo 	= mysql_query($Query_TB_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_TB_AUTRES_redo    = mysql_fetch_array($result_TB_AUTRES_Redo);
$Reprise_TB_AUTRES 		= $Data_TB_AUTRES_redo[NbVente_TB_AUTRES];		 
		 
		 
$Query_LV_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_LV_AUTRES FROM orders 
WHERE user_id IN ('laval','lavalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_LV_AUTRES_Redo 	= mysql_query($Query_LV_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LV_AUTRES_redo    = mysql_fetch_array($result_LV_AUTRES_Redo);
$Reprise_LV_AUTRES 		= $Data_LV_AUTRES_redo[NbReprise_LV_AUTRES];		 
	
	
$Query_HA_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_HA_AUTRES FROM orders 
WHERE user_id IN ('warehousehal','warehousehalsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_HA_AUTRES_Redo 	= mysql_query($Query_HA_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_HA_AUTRES_redo    = mysql_fetch_array($result_HA_AUTRES_Redo);
$Reprise_HA_AUTRES 		= $Data_HA_AUTRES_redo[NbReprise_HA_AUTRES];			 		 
	
$Query_DR_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_DR_AUTRES FROM orders 
WHERE user_id IN ('entrepotdr','safedr') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_DR_AUTRES_Redo 	= mysql_query($Query_DR_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_DR_AUTRES_redo    = mysql_fetch_array($result_DR_AUTRES_Redo);
$Reprise_DR_AUTRES 		= $Data_DR_AUTRES_redo[NbReprise_DR_AUTRES];	

$Query_LE_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_LE_AUTRES FROM orders 
WHERE user_id IN ('levis','levissafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS NOT NULL";		 
$result_LE_AUTRES_Redo 	= mysql_query($Query_LE_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LE_AUTRES_redo    = mysql_fetch_array($result_LE_AUTRES_Redo);
$Reprise_LE_AUTRES 		= $Data_LE_AUTRES_redo[NbReprise_LE_AUTRES];	


$Query_LO_AUTRES_redo 	= "SELECT count(order_num) as NbReprise_LO_AUTRES FROM orders 
WHERE user_id IN ('longueuil','longueuilsafe') 
AND order_date_processed BETWEEN '$date1' AND '$date2' 
AND prescript_lab NOT IN (10,3,25,69)
AND redo_order_num IS  NOT NULL";		 
$result_LO_AUTRES_Redo 	= mysql_query($Query_LO_AUTRES_redo)		or die  ('I cannot select items because 1: ' . mysql_error());
$Data_LO_AUTRES_redo    = mysql_fetch_array($result_LO_AUTRES_Redo);
$Reprise_LO_AUTRES 		= $Data_LO_AUTRES_redo[NbReprise_LO_AUTRES];	




$TotalRepriseAUTRES = $Reprise_SMB_AUTRES +$Reprise_LO_AUTRES + $Reprise_LE_AUTRES + $Reprise_DR_AUTRES + $Reprise_HA_AUTRES +$Reprise_LV_AUTRES + $Reprise_TB_AUTRES  +  $Reprise_CH_AUTRES+ $Reprise_SH_AUTRES + $Reprise_TR_AUTRES + $Reprise_GR_AUTRES ;
$Total_Reprise_TR  = $Total_Reprise_TR  + $Reprise_TR_AUTRES;
$Total_Reprise_SH  = $Total_Reprise_SH  + $Reprise_SH_AUTRES;
$Total_Reprise_CH  = $Total_Reprise_CH  + $Reprise_CH_AUTRES;
$Total_Reprise_TB  = $Total_Reprise_TB  + $Reprise_TB_AUTRES;
$Total_Reprise_LV  = $Total_Reprise_LV  + $Reprise_LV_AUTRES;
$Total_Reprise_HA  = $Total_Reprise_HA  + $Reprise_HA_AUTRES;
$Total_Reprise_DR  = $Total_Reprise_DR  + $Reprise_DR_AUTRES;
$Total_Reprise_LE  = $Total_Reprise_LE  + $Reprise_LE_AUTRES;
$Total_Reprise_LO  = $Total_Reprise_LO  + $Reprise_LO_AUTRES;
$Total_Reprise_SMB = $Total_Reprise_SMB + $Reprise_SMB_AUTRES;
$Total_Reprise_GR  = $Total_Reprise_GR  + $Reprise_GR_AUTRES;
//Fin Reprises partie AUTRES = Colonne #5

$GrandTotalReprise = $Total_Reprise_TR + $Total_Reprise_SH + $Total_Reprise_CH + $Total_Reprise_TB + $Total_Reprise_LV + $Total_Reprise_HA + $Total_Reprise_DR +$Total_Reprise_LE + $Total_Reprise_LO + $Total_Reprise_SMB + $Total_Reprise_GR;

//Calcul des poucentage de reprise
$PourcentageReprise_TR = ($Total_Reprise_TR/$Total_Vente_TR)*100;
$PourcentageReprise_TR = money_format('%.2n',$PourcentageReprise_TR);

$PourcentageReprise_SH = ($Total_Reprise_SH/$Total_Vente_SH)*100;
$PourcentageReprise_SH = money_format('%.2n',$PourcentageReprise_SH);

$PourcentageReprise_CH = ($Total_Reprise_CH/$Total_Vente_CH)*100;
$PourcentageReprise_CH = money_format('%.2n',$PourcentageReprise_CH);

$PourcentageReprise_TB = ($Total_Reprise_TB/$Total_Vente_TB)*100;
$PourcentageReprise_TB = money_format('%.2n',$PourcentageReprise_TB);

$PourcentageReprise_LV = ($Total_Reprise_LV/$Total_Vente_LV)*100;
$PourcentageReprise_LV = money_format('%.2n',$PourcentageReprise_LV);

$PourcentageReprise_HA = ($Total_Reprise_HA/$Total_Vente_HA)*100;
$PourcentageReprise_HA = money_format('%.2n',$PourcentageReprise_HA);

$PourcentageReprise_DR = ($Total_Reprise_DR/$Total_Vente_DR)*100;
$PourcentageReprise_DR = money_format('%.2n',$PourcentageReprise_DR);

$PourcentageReprise_LE = ($Total_Reprise_LE/$Total_Vente_LE)*100;
$PourcentageReprise_LE = money_format('%.2n',$PourcentageReprise_LE);

$PourcentageReprise_LO = ($Total_Reprise_LO/$Total_Vente_LO)*100;
$PourcentageReprise_LO = money_format('%.2n',$PourcentageReprise_LO);

$PourcentageReprise_SMB = ($Total_Reprise_SMB/$Total_Vente_SMB)*100;
$PourcentageReprise_SMB = money_format('%.2n',$PourcentageReprise_SMB);

$PourcentageReprise_GR = ($Total_Reprise_GR/$Total_Vente_GR)*100;
$PourcentageReprise_GR = money_format('%.2n',$PourcentageReprise_GR);

$MoyennePourcentageReprise = ($PourcentageReprise_TR + $PourcentageReprise_SH + $PourcentageReprise_CH+ $PourcentageReprise_TB+$PourcentageReprise_LV  +$PourcentageReprise_HA +$PourcentageReprise_DR + $PourcentageReprise_LE  +  $PourcentageReprise_LO + $PourcentageReprise_SMB + $PourcentageReprise_GR)/11;
$MoyennePourcentageReprise = money_format('%.2n',$MoyennePourcentageReprise);
//Affichage des résultats
$message.="
<table width=\"1000\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">

<tr>
	<th colspan=\"14\" align=\"center\" bgcolor=\"#F4E3E3\">Période: $date1 - $date2 </strong></th>
</tr>

<tr>
	<th align=\"center\">&nbsp;</td>
	<th colspan=\"6\" align=\"center\" bgcolor=\"#C4F3BD\">Ventes</strong></th>
	<th colspan=\"7\" align=\"center\" bgcolor=\"#FBFF6B\">Reprises</strong></th>
</tr>

<tr>
	<th colspan=\"1\" align=\"center\">&nbsp;</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Swiss</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Dlab</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Central Lab</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Essilor #1</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Autres</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">Nombre de vente</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Swiss</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Dlab</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Central Lab</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Essilor #1</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Autres</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">Nombre de reprise</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">% Reprise global</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Trois-Rivières</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TR_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_TR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TR_CENTRALLAB</th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_TR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_TR%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Sherbrooke</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SH_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SH_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SH_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SH_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SH_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_SH</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SH_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SH_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SH_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SH_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SH_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_SH</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_SH%</strong></th>
</tr>	


<tr>
	<th colspan=\"1\" align=\"center\">Chicoutimi</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_CH_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_CH_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_CH_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_CH_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_CH_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_CH</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_CH_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_CH_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_CH_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_CH_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_CH_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_CH</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_CH%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Terrebonne</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TB_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TB_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TB_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TB_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_TB_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_TB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TB_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TB_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TB_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TB_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_TB_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_TB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_TB%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Laval</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LV_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LV_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LV_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LV_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LV_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_LV</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LV_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LV_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LV_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LV_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LV_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_LV</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_LV%</strong></th>
</tr>	
		
<tr>
	<th colspan=\"1\" align=\"center\">Halifax</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_HA_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_HA_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_HA_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_HA_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_HA_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_HA</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_HA_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_HA_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_HA_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_HA_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_HA_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_HA</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_HA%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Drummondville</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_DR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_DR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_DR_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_DR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_DR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_DR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_DR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_DR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_DR_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_DR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_DR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_DR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_DR%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Lévis</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LE_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LE_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LE_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LE_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LE_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_LE</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LE_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LE_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LE_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LE_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LE_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_LE</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_LE%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Longueuil</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LO_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LO_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LO_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LO_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_LO_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_LO</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LO_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LO_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LO_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LO_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_LO_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_LO</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_LO%</strong></th>
</tr>	

<tr>
	<th colspan=\"1\" align=\"center\">Sainte-Marie</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SMB_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SMB_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SMB_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SMB_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_SMB_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_SMB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SMB_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SMB_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SMB_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SMB_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_SMB_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_SMB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_SMB%</strong></th>
</tr>	


<tr>
	<th colspan=\"1\" align=\"center\">Granby</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_GR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_GR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_GR_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_GR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Vente_GR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#C4F3BD\">$Total_Vente_GR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_GR_SWISS</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_GR_DLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_GR_CENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_GR_GKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Reprise_GR_AUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$Total_Reprise_GR</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$PourcentageReprise_GR%</strong></th>
</tr>	
						
<tr>
	<th colspan=\"1\" align=\"center\">Total</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TotalVenteSwiss</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TotalVenteDLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TotalVenteCENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TotalVenteGKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TotalVenteAUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#8AE4B0\">$TOTAL_VENTE_GLOBAL</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$TotalRepriseSwiss</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$TotalRepriseDLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$TotalRepriseCENTRALLAB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$TotalRepriseGKB</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FBFF6B\">$TotalRepriseAUTRES</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$GrandTotalReprise</strong></th>
	<th colspan=\"1\" align=\"center\"  bgcolor=\"#FFF962\">$MoyennePourcentageReprise%</strong></th>
</tr>	
								  
 </table>";	
	


//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport Annuel Jules $date1 - $date2";
$response=office365_mail($to_address, $from_address, $subject, null, $message);
//Log email
	$compteur = 0;
	foreach($to_address as $key => $value)
	{
		if ($compteur == 0 )
		 	$EmailEnvoyerA = $value;
		else
			$EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
		$compteur += 1;	
	}


echo $message;

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}


?>