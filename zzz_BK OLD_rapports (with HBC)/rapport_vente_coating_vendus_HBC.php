<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connect.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true); 
    
/*
Type de verres: Progressifs ET SV
Provenance des commandes: HBC et Griffé
*

$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2     = date("Y-m-d");

/*
$date1 = "2018-10-01";
$date2 = "2018-11-30";
*

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


$message.= "<body><table width=\"700\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">
<tr><th colspan=\"6\">HBC Coatings Sold Between $date1 et $date2 (Redos are not included)</th></tr>";
$message.= "<tr>
                <th align=\"center\" bgcolor=\"#D8D8D8\">&nbsp;</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">HC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">AR+ETC</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Xlr</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">StressFree</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Total</th>
			</tr>";
	
	

	
//1- #88403-Bloor.St
$user_id     = "('88403')";
$Nom_de_l_entrepot = '#88403-Bloor St';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88403 = $total;
//Calcul Pourcentage
$user_id     = "('88403')";
$Nom_de_l_entrepot = '#88403-Bloor St';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88403) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88403)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88403)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88403)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 1-#88403-Bloor	
			

	

	
//3-#88408-Oshawa
$user_id     = "('88408')";
$Nom_de_l_entrepot = '#88408-Oshawa';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88408 = $total;

//3-#88408-Oshawa
$user_id     = "('88408')";
$Nom_de_l_entrepot = '#88408-Oshawa';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88408) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88408)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88408)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88408)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 3-#88408-Oshawa





//4-#88409-Eglinton
$user_id     = "('88409')";
$Nom_de_l_entrepot = '#88409-Eglinton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88409 = $total;



//4-#88409-Eglinton
$user_id     = "('88409')";
$Nom_de_l_entrepot = '#88409-Eglinton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88409) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88409)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88409)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88409)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	



$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b>  ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 4-#88409-Eglinton



//5-#88411-Sherway
$user_id     = "('88411')";
$Nom_de_l_entrepot = '#88411-Sherway';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88411 = $total;


//5-#88411-Sherway
$user_id     = "('88411')";
$Nom_de_l_entrepot = '#88411-Sherway';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88411) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88411)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88411)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88411)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 5-#88411-Sherway




//6-#88414-Yorkdale
$user_id     = "('88414')";
$Nom_de_l_entrepot = '#88414-Yorkdale';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88414 = $total;


	
//6-#88414-Yorkdale
$user_id     = "('88414')";
$Nom_de_l_entrepot = '#88414-Yorkdale';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88414) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88414)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88414)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88414)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			


$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 6-#88414-Yorkdale





//7-#88416-Vancouver DTN
$user_id     = "('88416')";
$Nom_de_l_entrepot = '#88416-Vancouver DTN';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88416 = $total;


//7-#88416-Vancouver DTN
$user_id     = "('88416')";
$Nom_de_l_entrepot = '#88416-Vancouver DTN';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88416) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88416)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88416)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88416)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			


$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 7-#88416-Vancouver DTN



	






//10-#88431-Calgary DTN
$user_id     = "('88431')";
$Nom_de_l_entrepot = '#88431-Calgary DTN';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88431 = $total;

//10-#88431-Calgary DTN
$user_id     = "('88431')";
$Nom_de_l_entrepot = '#88431-Calgary DTN';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88431) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88431)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88431)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88431)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			

$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 10-#88431-Calgary DTN



	
	
	
	
	
//12-##88433-Polo Park
$user_id     = "('88433')";
$Nom_de_l_entrepot = '#88433-Polo Park';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88433 = $total;


//12-#88433-Polo Park
$user_id     = "('88433')";
$Nom_de_l_entrepot = '#88433-Polo Park';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88433) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88433)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88433)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88433)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b>  ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b>  ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 12-#88433-Polo Park




//13-#88434-Market Mall
$user_id     = "('88434')";
$Nom_de_l_entrepot = '#88434-Market Mall';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88434 = $total;

//13-#88434-Market Mall	
$user_id     = "('88434')";
$Nom_de_l_entrepot = '#88434-Market Mall';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88434) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88434)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88434)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88434)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 13-#88434-Market Mall




//14-#88435-West Edmonton
$user_id     = "('88435')";
$Nom_de_l_entrepot = '#88435-West Edmonton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88435 = $total;


//14-#88435-West Edmonton
$user_id     = "('88435')";
$Nom_de_l_entrepot = '#88435-West Edmonton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88435) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88435)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88435)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88435)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 14-#88435-West Edmonton






//15-#88438-Metrotown
$user_id     = "('88438')";
$Nom_de_l_entrepot = '#88438-Metrotown';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88438 = $total;


//15-#88438-Metrotown
$user_id     = "('88438')";
$Nom_de_l_entrepot = '#88438-Metrotown';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88438) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88438)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88438)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88438)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 15-#88438-Metrotown




//16-#88439-Langley
$user_id     = "('88439')";
$Nom_de_l_entrepot = '#88439-Langley';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88439 = $total;


//16-#88439-Langley
$user_id     = "('88439')";
$Nom_de_l_entrepot = '#88439-Langley';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88439) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88439)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88439)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88439)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			

$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 16-#88439-Langley

	
	
	
//17-#88440-Rideau
$user_id     = "('88440')";
$Nom_de_l_entrepot = '#88440-Rideau';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88440 = $total;

	
//17-#88440-Rideau
$user_id     = "('88440')";
$Nom_de_l_entrepot = '#88440-Rideau';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88440) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88440)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88440)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88440)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 17-#88440-Rideau





	
	
	
//20-88444-Mayfair
$user_id     = "('88444')";
$Nom_de_l_entrepot = '#88444-Mayfair';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','MaxiVue2 Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	

$total = $NB_HC  + $NB_AR_ETC  + $NB_Xlr  + $NB_StressFree;
$total_88444 = $total;

//20-#88444-Mayfair
$user_id     = "('88444')";
$Nom_de_l_entrepot = '#88444-Mayfair';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_88444) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);
	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','SPC','SPC Backside')
AND order_status NOT IN ('cancelled')";
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_88444)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
		
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr','MaxiVue2','Maxivue2 Backside','Xlr Backside')
AND order_status NOT IN ('cancelled')";
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_88444)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_88444)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	
			


$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\"><b>$NB_HC</b> ($Pourcentage_HC%)</td>
			    <td align=\"center\"><b>$NB_AR_ETC</b> ($Pourcentage_AR_ETC%)</td>
                <td align=\"center\"><b>$NB_Xlr</b> ($Pourcentage_Xlr%)</td>
				<td align=\"center\"><b>$NB_StressFree</b> ($Pourcentage_StressFree%)</td>
				<td align=\"center\"><b>$total</b></td>
			</tr>";
//Fin partie 20-#88444-Mayfair		



	
	
		
//Nouveau  tableau des ventes Armour420		

$message.=  "<br><br><table width=\"365\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">
			<tr>
				<th bgcolor=\"#D8D8D8\" align=\"center\" colspan=\"9\">Armour 420 Sales (Redos are not included)</th>
			</tr>";

$SommeCommandesArmour420 = 0;
//#88403-Bloor St
$Nom_de_l_entrepot = "#88403-Bloor St";
$user_id    = "('88403')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";
			

			
			
//#88408-Oshawa
$Nom_de_l_entrepot = "#88408-Oshawa";
$user_id    = "('88408')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
			
						
//#88409-Eglinton
$Nom_de_l_entrepot = "#88409-Eglinton";
$user_id    = "('88409')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
			
		
//#88411-Sherway
$Nom_de_l_entrepot = "#88411-Sherway";
$user_id    = "('88411')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		

			
//#88414-Yorkdale
$Nom_de_l_entrepot = "#88414-Yorkdale";
$user_id    = "('88414')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
	
		
					
//#88416-Vancouver DTN
$Nom_de_l_entrepot = "#88416-Vancouver DTN";
$user_id    = "('88416')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
		
			
//#88429-Saskatoon
$Nom_de_l_entrepot = "#88429-Saskatoon";
$user_id    = "('88429')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
		

			
			
//#88431-Calgary DTN
$Nom_de_l_entrepot = "#88431-Calgary DTN";
$user_id    = "('88431')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			

	
//#88433-Polo Park
$Nom_de_l_entrepot = "#88433-Polo Park";
$user_id    = "('88433')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			
			
				
//#88434-Market Mall
$Nom_de_l_entrepot = "#88434-Market Mall";
$user_id    = "('88434')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			
		
		
//#88435-West Edmonton
$Nom_de_l_entrepot = "#88435-West Edmonton";
$user_id    = "('88435')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";			
		
		
//#88438-Metrotown
$Nom_de_l_entrepot = "#88438-Metrotown";
$user_id    = "('88438')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
		
//#88439-Langley
$Nom_de_l_entrepot = "#88439-Langley";
$user_id    = "('88439')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";		
		

		
//#88440-Rideau
$Nom_de_l_entrepot = "#88440-Rideau";
$user_id    = "('88440')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
	

			
			
	
			
			
						
//#88444-Mayfair
$Nom_de_l_entrepot = "#88444-Mayfair";
$user_id    = "('88444')";
$queryA420  = "SELECT count(order_num) as A420 FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Armour 420%'
AND order_status NOT IN ('cancelled')";
$resultA420  = mysqli_query($con,$queryA420) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataA420    = mysqli_fetch_array($resultA420,MYSQLI_ASSOC);
$NB_A420     = $DataA420[A420];	
$SommeCommandesArmour420  = $SommeCommandesArmour420  + $NB_A420;
$message.=  "<tr bgcolor=\"#C6C4C4\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_A420</td>
			</tr>";	
			
			
	
//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "HBC Coatings Sold Between $date1 et $date2";
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
	
	if($response){ 
		echo '<br>REUSSI!<br>';
    }else{
		echo '<br>ECHEC!<br>';	
	}	
		

echo $subject.'<br>';
echo $message;

*/

?>
