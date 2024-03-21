<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true); 
   

   
/*
Type de verres: Progressifs ET SV
Provenance des commandes: Ifc.ca seulement, AUCUN SAFE
*/

$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2     = date("Y-m-d");



//A REMETTRE EN COMMENTAIRE
/*
$date1 = "2019-01-01";
$date2 = "2019-12-31";
*/

//IFC.CA SEULEMENT: Progressif ET SV
echo '<br>Du: '. $date1 .'&nbsp;&nbsp;Au '. $date2.'<br><br>';


$count   = 0;
$message = "";
$message = "<html>";
$message.= "<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";


$message.= "<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";
$message.= "<tr>
                <th align=\"center\" bgcolor=\"#D8D8D8\">&nbsp;</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">HC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">AR Backside</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">AR+ETC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">iBlu</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Xlr</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">StressFree</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">HD AR</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Low Reflexion</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Night Vision</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Total</th>
			</tr>";
	
	
    
	
//1 -Partie Trois-Rivieres
$user_id     = "('entrepotifc')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_Tr = $total;



//1 -Partie Pourcentage de  Trois-Rivieres
$user_id     = "('entrepotifc')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_Tr) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_Tr)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_Tr)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_Tr)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_Tr)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_Tr)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_Tr)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_Tr)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_Tr)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 1		
			

	
//2-Partie Drummondville
$user_id     = "('entrepotdr')";
$Nom_de_l_entrepot = 'Entrepot de Drummondville';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_Dr = $total;



//2-Partie Pourcentage de  Drummondville
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_Dr) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_Dr)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_Dr)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_Dr)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_Dr)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_Dr)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_Dr)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_Dr)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_Dr)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 2

	
	
//3-Partie Laval
$user_id     = "('laval')";
$Nom_de_l_entrepot = 'Entrepot de Laval';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_LV = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LV) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_LV)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_LV)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LV)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LV)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LV)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LV)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_LV)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_LV)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 3

			
			
		


		
//4-Partie Terrebonne
$user_id     = "('terrebonne')";
$Nom_de_l_entrepot = 'Entrepot de Terrebonne';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";

$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_TB = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_TB) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_TB)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_TB)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_TB)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_TB)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_TB)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_TB)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_TB)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";

$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_TB)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie 4			
			
			
			
//5-Partie Sherbrooke
$user_id     = "('sherbrooke')";
$Nom_de_l_entrepot = 'Entrepot de Sherbrooke';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_SH = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_SH) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_SH)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_SH)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_SH)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_SH)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_SH)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_SH)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_SH)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_SH)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie Sherbrooke	
	
	

	
		
//Partie Halifax
$user_id     = "('warehousehal')";
$Nom_de_l_entrepot = 'Entrepot d\'Halifax';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_HA = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_HA) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_HA)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_HA)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_HA)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_HA)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_HA)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_HA)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_HA)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_HA)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie Halifax
	
	
	
	
//7-Partie Chicoutimi
$user_id     = "('chicoutimi')";
$Nom_de_l_entrepot = 'Entrepot de Chicoutimi';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_CH = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_CH) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_CH)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_CH)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_CH)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_CH)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_CH)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_CH)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_CH)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_CH)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie 7 chicoutimi
	
	

//7-Partie Lévis
$user_id     = "('levis')";
$Nom_de_l_entrepot = 'Entrepot de Levis';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_LE = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LE) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_LE)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_LE)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LE)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LE)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LE)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LE)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_LE)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_LE)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 7 Lévis	
	
	
	
	
	
	
//7-Partie Longueuil
$user_id     = "('longueuil')";
$Nom_de_l_entrepot = 'Entrepot de Longueuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_LO = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LO) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_LO)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_LO)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LO)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LO)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LO)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LO)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_LO)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_LO)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie  Longueuil	
	
	
	
	
	
	
//9-Partie Granby
$user_id     = "('granby')";
$Nom_de_l_entrepot = 'Entrepot de Granby';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_GR = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_GR) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_GR)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_GR)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_GR)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_GR)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_GR)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_GR)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_GR)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_GR)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie Granby
	
	
	
	
//10-Partie Québec
$user_id     = "('entrepotquebec')";
$Nom_de_l_entrepot = 'Entrepot de Qu&eacute;bec';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_QC = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_QC) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_QC)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_QC)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_QC)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_QC)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_QC)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_QC)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_QC)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_QC)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie Québec	
	
	
/*	
//Partie Montreal ZT1  (A)
$user_id     = "('montreal')";
$Nom_de_l_entrepot = 'Entrepot de Montreal ZT1';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_MTL = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_MTL) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_MTL)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_MTL)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_MTL)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_MTL)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_MTL)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_MTL)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_MTL)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_MTL)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie Montreal ZT1	
	
*/	

	
	
//Partie Gatineau(A)
$user_id     = "('gatineau')";
$Nom_de_l_entrepot = 'Entrepot de Gatineau';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_GAT = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_GAT) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_GAT)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_GAT)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_GAT)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_GAT)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_GAT)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_GAT)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_GAT)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_GAT)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
	//Fin partie Gatineau
	

	
//Partie St-Jérôme (A)
$user_id     = "('stjerome')";
$Nom_de_l_entrepot = 'Entrepot de la lunette St-Jérôme';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_STJ = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJ) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_STJ)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_STJ)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJ)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJ)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJ)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJ)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_STJ)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_STJ)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br><br><br>";
	//Fin partie St-Jérôme
	
	
	
	
	
	
	
	
	

//Partie Edmundston (A)
$user_id     = "('edmundston')";
$Nom_de_l_entrepot = 'Entrepot de la lunette Edmundston';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_EDM = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_EDM) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_EDM)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_EDM)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_EDM)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_EDM)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_EDM)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_EDM)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_EDM)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_EDM)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br>";
//Fin partie Edmundston



//Partie Moncton (A)
$user_id     = "('moncton')";
$Nom_de_l_entrepot = 'Entrepot de la lunette Moncton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_MON = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_MON) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_MON)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_MON)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_MON)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_MON)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_MON)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_MON)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_MON)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_MON)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br>";
//Fin partie Moncton




//Partie Fredericton (A)
$user_id     = "('fredericton')";
$Nom_de_l_entrepot = 'Entrepot de la lunette Fredericton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_FREDERICTON = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_FREDERICTON) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_FREDERICTON)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_FREDERICTON)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_FREDERICTON)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_FREDERICTON)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_FREDERICTON)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_FREDERICTON)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_FREDERICTON)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_FREDERICTON)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br>";
//Fin partie Fredericton




//Partie St-John (A)
$user_id     = "('stjohn')";
$Nom_de_l_entrepot = 'Entrepot de la lunette St-John';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_STJOHN = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJOHN) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_STJOHN)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_STJOHN)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJOHN)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJOHN)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJOHN)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJOHN)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_STJOHN)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_STJOHN)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br>";
//Fin partie St-John



//Partie Griffe (A)
$user_id     = "('88666')";
$Nom_de_l_entrepot = 'Griffe Lunetier';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_GRIFFE = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_GRIFFE) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_GRIFFE)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_GRIFFE)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_GRIFFE)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_GRIFFE)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_GRIFFE)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_GRIFFE)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_GRIFFE)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_GRIFFE)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr><br>";
//Fin partie GRIFFE


//Partie Vaudreuil
$user_id     = "('vaudreuil')";
$Nom_de_l_entrepot = 'Entrepot de la lunette Vaudreuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_EDM = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_EDM) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_EDM)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_EDM)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_EDM)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_EDM)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_EDM)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_EDM)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_EDM)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_EDM)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie Vaudreuil






//Partie Sorel
$user_id     = "('sorel')";
$Nom_de_l_entrepot = 'Entrepot de la lunette Sorel';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$NB_AR_Backside    = $DataAR_Backside[AR_Backside];
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$NB_LowReflexion    = $DataLR[Low_Reflexion];	

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$NB_NightVision    = $DataNV[Night_Vision];	

$total = $NB_HC +  $NB_AR_Backside + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR +$NB_LowReflexion + $NB_NightVision;
$total_SOR = $total;



//2-Partie Pourcentage 
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_EDM) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_Backside  = "SELECT count(order_num) as AR_Backside FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('AR Backside','Super AR Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_Backside = mysqli_query($con,$queryAR_Backside) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_Backside   = mysqli_fetch_array($resultAR_Backside,MYSQLI_ASSOC);
$Pourcentage_AR_Backside    = ($DataAR_Backside[AR_Backside]/$total_EDM)*100;
$Pourcentage_AR_Backside=money_format('%.2n',$Pourcentage_AR_Backside);
	
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','MultiClear AR','Super AR')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_EDM)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_EDM)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','Xlr Backside','MaxiiVue')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_EDM)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree','StressFree 32')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_EDM)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR','HD AR Backside')
AND order_status NOT IN ('cancelled')";
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_EDM)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);


$queryLowReflexion  = "SELECT count(order_num) as Low_Reflexion FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Low Reflexion')
AND order_status NOT IN ('cancelled')";
$resultLR = mysqli_query($con,$queryLowReflexion) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataLR   = mysqli_fetch_array($resultLR,MYSQLI_ASSOC);
$Pourcentage_LR = ($DataLR[Low_Reflexion]/$total_EDM)*100;					
$Pourcentage_LR = money_format('%.2n',$Pourcentage_LR);

$queryNightVision  = "SELECT count(order_num) as Night_Vision FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Night Vision')
AND order_status NOT IN ('cancelled')";
$resultNV = mysqli_query($con,$queryNightVision) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataNV   = mysqli_fetch_array($resultNV,MYSQLI_ASSOC);
$Pourcentage_NV = ($DataNV[Night_Vision]/$total_EDM)*100;					
$Pourcentage_NV = money_format('%.2n',$Pourcentage_NV);

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\"><b>$Nom_de_l_entrepot<b></td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
				<td align=\"center\"><b>$NB_AR_Backside</b> ($Pourcentage_AR_Backside%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_iBlu</b> ($Pourcentage_iBlu%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$NB_HD_AR</b> ($Pourcentage_HD_AR%)</td>
				<td align=\"center\"><b>$NB_LowReflexion</b> ($Pourcentage_LR%)</td>
				<td align=\"center\"><b>$NB_NightVision</b> ($Pourcentage_NV%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr></table><br><br><br>";
//Fin partie Sorel




			
//Nouveau  tableau des ventes Armour420		2018-08-08

$message.=  "<table width=\"450\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">
			<tr>
				<td bgcolor=\"#D8D8D8\" align=\"center\" colspan=\"9\">Ventes 420 (Exclus les reprises)</td>
			</tr>";


//Trois-Rivieres
$Nom_de_l_entrepot = "Entrepot de Trois-Rivières";
$user_id    = "('entrepotifc')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";
			
//Drummondville
$Nom_de_l_entrepot = "Entrepot de Drummondville";
$user_id    = "('entrepotdr')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";
			
			
//Laval
$Nom_de_l_entrepot = "Entrepot de Laval";
$user_id    = "('laval')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
			
	
//Terrebonne
$Nom_de_l_entrepot = "Entrepot de Terrebonne";
$user_id    = "('terrebonne')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";				
			
	
//Sherbrooke
$Nom_de_l_entrepot = "Entrepot de Sherbrooke";
$user_id    = "('sherbrooke')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
			
			
//Halifax
$Nom_de_l_entrepot = "Entrepot d'Halifax";
$user_id    = "('warehousehal')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
						
			
//Chicoutimi
$Nom_de_l_entrepot = "Entrepot de Chicoutimi";
$user_id    = "('chicoutimi')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
								
			
//Lévis
$Nom_de_l_entrepot = "Entrepot de Lévis";
$user_id    = "('levis')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		


		
//Longueuil
$Nom_de_l_entrepot = "Entrepot de Longueuil";
$user_id    = "('longueuil')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			
	

//Granby
$Nom_de_l_entrepot = "Entrepot de Granby";
$user_id    = "('granby')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			
			
//Québec
$Nom_de_l_entrepot = "Entrepot de Québec";
$user_id    = "('entrepotquebec')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
/*			
//Montreal  ZT1
$Nom_de_l_entrepot = "Entrepot de Montréal ZT1";
$user_id    = "('montreal')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
*/			
			
//Gatineau
$Nom_de_l_entrepot = "Entrepot de Gatineau";
$user_id    = "('gatineau')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
			


//St-Jérôme
$Nom_de_l_entrepot = "Entrepot de St-Jérôme";
$user_id    = "('stjerome')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
			
			
//Edmundston
$Nom_de_l_entrepot = "Entrepot de Edmundston";
$user_id    = "('edmundston')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
			
			
//Vaudreuil
$Nom_de_l_entrepot = "Entrepot de Vaudreuil";
$user_id    = "('vaudreuil')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";					
			
	

//Sorel
$Nom_de_l_entrepot = "Entrepot de Sorel";
$user_id    = "('sorel')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	


//Moncton
$Nom_de_l_entrepot = "Entrepot de Moncton";
$user_id    = "('moncton')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";				
			
			
//Fredericton
$Nom_de_l_entrepot = "Entrepot de Fredericton";
$user_id    = "('fredericton')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	



//St-John
$Nom_de_l_entrepot = "Entrepot de St-John";
$user_id    = "('stjohn')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";
			



//Griffe
$Nom_de_l_entrepot = "Griffe Lunetier";
$user_id    = "('88666')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	


$message.="</table> <p>N.B. Ce rapport inclus toutes les ventes (Sv, progressifs, etc)<p>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Traitements vendus EDLL Ifc.ca entre $date1 et $date2";
$response     = office365_mail($to_address, $from_address, $subject, null, $message);

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
	
		// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_vente_coating_vendus_progressif_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo '<br>REUSSI';
    }else{
		echo '<br>ECHEC';	
	}	
		

echo $subject.'<br>';
echo $message;

$time_end 		 = microtime(true);
$time 	  		 = $time_end - $time_start;
$today 	  		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   			 = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   			 = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips			 = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
			VALUES('Rapport Coatings vendus verres progressifs Edll 2.0', '$time','$today','$timeplus3heures','rapport_vente_coating_vendus_progressif.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
?>