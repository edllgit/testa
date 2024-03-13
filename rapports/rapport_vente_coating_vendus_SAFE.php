<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();


$time_start = microtime(true);	
/*
Type de verres: SV ET PROGRESSIF A LA DEMANDE DE DANIEL BEAULIEU LE 4 AVRIL 2018  
Provenance des commandes: SAFE
rapport fonctionne, reste à créer la cron job
*/

$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2     = date("Y-m-d");
/*
$date1 = "2018-04-17";
$date2 = "2018-04-23";
*/
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
				<th align=\"center\" bgcolor=\"#D8D8D8\">AR+ETC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">iBlu</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Xlr</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">StressFree</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">HD AR</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">SPF</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Total</th>
			</tr>";
	
		
//1 -Partie Trois-Rivieres
$user_id     = "('entrepotsafe')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
echo '<br>'.$queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
echo '<br>'.$queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
echo '<br>' .$queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
echo '<br>' .$queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];				


$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC + $NB_AR + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_StressFree32  + $NB_StressFreeNoflex + $NB_HD_AR + $NB_SFP ;
$total_Tr = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie 1		
			
	
	
			
//2-Partie Drummondville
$user_id     = "('safedr')";
$Nom_de_l_entrepot = 'Entrepot de Drummondville';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>' .$queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>' .$queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	
$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>' .$queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because 1: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];		

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because 2: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC +  $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR  + $NB_SFP ;
$total_Dr = $total;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 2
			
			
	
	
//3-Partie Laval
$user_id     = "('lavalsafe')";
$Nom_de_l_entrepot = 'Entrepot de Laval';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 3: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>' .$queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	
		

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
echo '<br>' .$queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because 9: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because 8: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_HD_AR  + $NB_SFP ;
$total_LV = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 3			
			
	
//4-Partie Terrebonne
$user_id     = "('terrebonnesafe')";
$Nom_de_l_entrepot = 'Entrepot de Terrebonne';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
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
	
$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because 10: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];						

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because 11: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_HD_AR  + $NB_SFP ;
$total_TE = $total;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 4			
			
//5-Partie Sherbrooke
$user_id     = "('sherbrookesafe')";
$Nom_de_l_entrepot = 'Entrepot de Sherbrooke';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
		

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because 13: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because 12: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC +  $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_HD_AR  + $NB_SFP ;
$total_SB = $total ;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 5	
	
	
//6-Partie Halifax
$user_id     = "('warehousehalsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Halifax';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	
$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because 14: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because 15: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR  + $NB_SFP ;
$total_HA = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 6
	
	
	
	
//7-Partie Chicoutimi
$user_id     = "('chicoutimisafe')";
$Nom_de_l_entrepot = 'Entrepot de Chicoutimi';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 16: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null

AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_HD_AR  + $NB_SFP ;
$total_CHI = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 7 chicoutimi
	
	

//7-Partie Lévis
$user_id     = "('levissafe')";
$Nom_de_l_entrepot = 'Entrepot de Levis';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 17: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		


	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'

AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
	
			

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  + $NB_HD_AR  + $NB_SFP ;
$total_LEVIS = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 7 Lévis	
	
	
	
	
	
	
//7-Partie Longueuil
$user_id     = "('longueuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Longueuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 18: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];	
					

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree  +  $NB_HD_AR  + $NB_SFP ;
$total_LONGUEUIL = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie 7 Longueuil	
	
	
	
	
	
//9-Partie Granby
$user_id     = "('granbysafe')";
$Nom_de_l_entrepot = 'Entrepot de Granby';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 19: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_Granby = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie Granby
	

	
	
//10-Partie Québec
$user_id     = "('quebecsafe')";
$Nom_de_l_entrepot = 'Entrepot de Qu&eacute;bec';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_QC = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie Québec	
	
	

	
/*
//10-Partie Montreal ZT1
$user_id     = "('montrealsafe')";
$Nom_de_l_entrepot = 'Entrepot de Montreal ZT1';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_MTL = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
	//Fin partie Montreal ZT1	
*/
	
	
	
	
	
	
	
//Partie Gatineau 
$user_id     = "('gatineausafe')";
$Nom_de_l_entrepot = 'Entrepot de Gatineau';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_GAT = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie Gatineau





//Partie St-Jérôme 
$user_id     = "('stjeromesafe')";
$Nom_de_l_entrepot = 'Entrepot de St-Jérôme';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_STJ = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie St-Jérôme	
	
	
	
//Partie Edmunston 
$user_id     = "('edmunstonsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Edmunston';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_STJ = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie Edmunston





//Partie Moncton 
$user_id     = "('monctonsafe')";
$Nom_de_l_entrepot = 'Entrepot de Moncton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_MON = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie Moncton



//Partie Fredericton 
$user_id     = "('frederictonsafe')";
$Nom_de_l_entrepot = 'Entrepot de Fredericton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_FREDERICTON = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie Fredericton




//Partie Griffe 
$user_id     = "('88666')";
$Nom_de_l_entrepot = 'Griffe Lunetier';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_GRIFFE = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie GRIFFE




	
//Partie Vaudreuil 
$user_id     = "('vaudreuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Vaudreuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_STJ = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr>";
//Fin partie Vaudreuil





	
//Partie Sorel 
$user_id     = "('sorelsafe')";
$Nom_de_l_entrepot = 'Entrepot de Sorel';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because 20: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$NB_HC    = $DataHC[HC];		



$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$NB_AR_ETC    = $DataAR_ETC[AR_ETC];
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$NB_iBlu    = $DataiBlu[iBlu];
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$NB_Xlr    = $DataXlr[Xlr];	
	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$NB_StressFree    = $DataStressFree[StressFree];				

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$NB_HD_AR    = $DataHD_AR[HD_AR];	

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$NB_SFP    = $DataSFP[HD_AR];	

$total = $NB_HC  + $NB_AR_ETC + $NB_iBlu + $NB_Xlr  + $NB_StressFree + $NB_HD_AR  + $NB_SFP ;
$total_SOR = $total;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_HC</td>
			    <td align=\"center\">$NB_AR_ETC</td>
                <td align=\"center\">$NB_iBlu</td>
                <td align=\"center\">$NB_Xlr</td>
				<td align=\"center\">$NB_StressFree</td>
				<td align=\"center\">$NB_HD_AR</td>
				<td align=\"center\">$NB_SFP</td>
				<td align=\"center\">$total</td>
			</tr></table><br><br>";
//Fin partie Sorel

		
//PARTIE POURCENTAGES
$message.="<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";

$message.= "<tr>
                <th align=\"center\" bgcolor=\"#D8D8D8\">&nbsp;</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">HC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">AR+ETC</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">iBlu</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Xlr</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">StressFree</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">HD AR</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">SFP</th>
			</tr>";
			
//1 -Partie Trois-Rivieres
$user_id     = "('entrepotsafe')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_Tr) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_Tr)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_Tr)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_Tr)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_Tr)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_Tr)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
			
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 1		
			
	
	
		
//2 -Partie Drummondville
$user_id     = "('safedr')";
$Nom_de_l_entrepot = 'Entrepot de Drummondville';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_Dr) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_Dr)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_Dr)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_Dr)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_Dr)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_Tr)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 2		 
	 
	 
//3 -Partie Laval
$user_id     = "('lavalsafe')";
$Nom_de_l_entrepot = 'Entrepot de Laval';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LV) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($son));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LV)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LV)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LV)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LV)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
		

$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_LV)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);

$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>	
			</tr>";
//Fin partie 3 
	  
	
	
	
//4 -Partie Terrebonne
$user_id     = "('terrebonnesafe')";
$Nom_de_l_entrepot = 'Entrepot de Terrebonne';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_TE) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_TE)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_TE)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_TE)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_TE)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_TE)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_TE)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);


$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 4	
	

	
	
//5-Partie Sherbrooke
$user_id     = "('sherbrookesafe')";
$Nom_de_l_entrepot = 'Entrepot de Sherbrooke';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_SB) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_SB)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_SB)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_SB)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_SB)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_SB)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);	
	
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_SB)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
			
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 5
	
	
	
	
	
	
 //6-Partie Halifax
$user_id     = "('warehousehalsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Halifax';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_HA) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_HA)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_HA)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_HA)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_HA)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_HA)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 6



 //7-Partie Chicoutimi
$user_id     = "('chicoutimisafe')";
$Nom_de_l_entrepot = 'Entrepot de Chicoutimi';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysql_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_CHI) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_CHI)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_CHI)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_CHI)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_CHI)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_CHI)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_CHI)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 7 chicoutimi



 //7-Partie Lévis
$user_id     = "('levissafe')";
$Nom_de_l_entrepot = 'Entrepot de Levis';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($con,$resultHC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LEVIS) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_LEVIS)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LEVIS)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LEVIS)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LEVIS)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LEVIS)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_LEVIS)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 7 Lévis



//7-Partie Longueuil
$user_id     = "('longueuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Longueuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_LONGUEUIL) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_LONGUEUIL)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_LONGUEUIL)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_LONGUEUIL)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_LONGUEUIL)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_LONGUEUIL)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_LONGUEUIL)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 7 Longueuil





//7-Partie Granby
$user_id     = "('granbysafe')";
$Nom_de_l_entrepot = 'Entrepot de Granby';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'

AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_Granby) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
$resultAR_ETC = mysqli_query($con,$queryAR_ETC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataAR_ETC   = mysqli_fetch_array($resultAR_ETC,MYSQLI_ASSOC);
$Pourcentage_AR_ETC    = ($DataAR_ETC[AR_ETC]/$total_Granby)*100;
$Pourcentage_AR_ETC=money_format('%.2n',$Pourcentage_AR_ETC);
	
$queryiBlu  = "SELECT count(order_num) as iBlu FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('iBlu','iblue')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_Granby)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_Granby)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_Granby)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	


$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_Granby)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_Granby)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 7 Granby




//10-Partie Québec
$user_id     = "('quebecsafe')";
$Nom_de_l_entrepot = 'Entrepot de Qu&eacute;bec';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_QC) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_QC)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_QC)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_QC)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_QC)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_QC)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie 10 Québec


/*
//Partie MTL ZT1
$user_id     = "('montrealsafe')";
$Nom_de_l_entrepot = 'Entrepot de Montréal ZT1';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_MTL) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_MTL)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_MTL)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_MTL)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_MTL)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_MTL)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Montréal ZT1
*/


//Partie Gatineau
$user_id     = "('gatineausafe')";
$Nom_de_l_entrepot = 'Entrepot de Gatineau';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_GAT) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);

	
$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_GAT)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_GAT)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_GAT)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_GAT)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_GAT)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Gatineau



//Partie St-Jérôme
$user_id     = "('stjeromesafe')";
$Nom_de_l_entrepot = 'Entrepot de St-Jérôme';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJ) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJ)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJ)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJ)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJ)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_STJ)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie St-Jérôme





//Partie Edmunston
$user_id     = "('edmunstonsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Edmunston';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJ) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJ)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJ)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJ)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJ)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_STJ)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Edmunston











//Partie Moncton
$user_id     = "('monctonsafe')";
$Nom_de_l_entrepot = 'Entrepot de Moncton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_MON) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_MON)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_MON)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_MON)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_MON)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_MON)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Moncton




//Partie Fredericton
$user_id     = "('frederictonsafe')";
$Nom_de_l_entrepot = 'Entrepot de Fredericton';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_FREDERICTON) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_FREDERICTON)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_FREDERICTON)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_FREDERICTON)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_FREDERICTON)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_FREDERICTON)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Fredericton



//Partie GRIFFE
$user_id     = "('88666')";
$Nom_de_l_entrepot = 'Griffe Lunetier';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_GRIFFE) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_GRIFFE)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_GRIFFE)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_GRIFFE)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_GRIFFE)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_GRIFFE)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie GRIFFE



//Partie Vaudreuil
$user_id     = "('vaudreuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Vaudreuil';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJ) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJ)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJ)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJ)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJ)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_STJ)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Vaudreuil





//Partie Sorel
$user_id     = "('sorelsafe')";
$Nom_de_l_entrepot = 'Entrepot de Sorel';
$queryHC  = "SELECT count(order_num) as HC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HC','Hard Coat')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHC. '<br>';
$resultHC = mysqli_query($con,$queryHC) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHC   = mysqli_fetch_array($resultHC,MYSQLI_ASSOC);
$Pourcentage_HC    = ($DataHC[HC]/$total_STJ) * 100;		
$Pourcentage_HC=money_format('%.2n',$Pourcentage_HC);


$queryAR_ETC  = "SELECT count(order_num) as AR_ETC FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Dream AR','ITO AR','AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryAR_ETC. '<br>';
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
//echo '<br>'. $queryiBlu. '<br>';
$resultiBlu = mysqli_query($con,$queryiBlu) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiBlu   = mysqli_fetch_array($resultiBlu,MYSQLI_ASSOC);
$Pourcentage_iBlu    = ($DataiBlu[iBlu]/$total_STJ)*100;
$Pourcentage_iBlu=money_format('%.2n',$Pourcentage_iBlu);	
	
	
$queryXlr  = "SELECT count(order_num) as Xlr FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('Xlr')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryXlr. '<br>';
$resultXlr = mysqli_query($con,$queryXlr) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataXlr   = mysqli_fetch_array($resultXlr,MYSQLI_ASSOC);
$Pourcentage_Xlr    = ($DataXlr[Xlr]/$total_STJ)*100;	
$Pourcentage_Xlr=money_format('%.2n',$Pourcentage_Xlr);	

	
$queryStressFree  = "SELECT count(order_num) as StressFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('StressFree')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryStressFree. '<br>';
$resultStressFree = mysqli_query($con,$queryStressFree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataStressFree   = mysqli_fetch_array($resultStressFree,MYSQLI_ASSOC);
$Pourcentage_StressFree    = ($DataStressFree[StressFree]/$total_STJ)*100;	
$Pourcentage_StressFree=money_format('%.2n',$Pourcentage_StressFree);	

$queryHD_AR  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('HD AR')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $queryHD_AR. '<br>';
$resultHD_AR = mysqli_query($con,$queryHD_AR) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD_AR   = mysqli_fetch_array($resultHD_AR,MYSQLI_ASSOC);
$Pourcentage_HD_AR = ($DataHD_AR[HD_AR]/$total_STJ)*100;					
$Pourcentage_HD_AR = money_format('%.2n',$Pourcentage_HD_AR);
	
$querySFP  = "SELECT count(order_num) as HD_AR FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_coating IN ('SFP')
AND order_status NOT IN ('cancelled')";
//echo '<br>'. $querySFP. '<br>';
$resultSFP = mysqli_query($con,$querySFP) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSFP   = mysqli_fetch_array($resultSFP,MYSQLI_ASSOC);
$Pourcentage_SFP = ($DataSFP[HD_AR]/$total_STJ)*100;					
$Pourcentage_SFP = money_format('%.2n',$Pourcentage_SFP);
		
$message.=  "<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$Pourcentage_HC%</td>
			    <td align=\"center\">$Pourcentage_AR_ETC%</td>
                <td align=\"center\">$Pourcentage_iBlu%</td>
                <td align=\"center\">$Pourcentage_Xlr%</td>
				<td align=\"center\">$Pourcentage_StressFree%</td>
				<td align=\"center\">$Pourcentage_HD_AR%</td>
				<td align=\"center\">$Pourcentage_SFP%</td>
			</tr>";
//Fin partie Sorel


$message.="</table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Ventes Verres Sécurité: Traitements vendus EDLL entre $date1 et $date2";
$response     = office365_mail($to_address, $from_address, $subject, null, $message);

//Envoie du email
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
	$nomFichier = 'r_vente_coating_vendus_Safe_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	

echo $subject.'<br>';
echo $message;


//Logs	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport coatings vendus SAFE 2.0', '$time','$today','$timeplus3heures','rapport_vente_coating_vendus_SAFE.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
 
?>