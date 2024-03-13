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

$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2 	   = date("Y-m-d");

echo '<br>Du: '. $date1 .'&nbsp;&nbsp;Au '. $date2.'<br><br>';

//Dates Hard Codés **A REMETTRE EN COMMENTAIRE**
/*
$date1 = "2019-01-01";
$date2 = "2019-12-31";
*/

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
                <th align=\"center\" bgcolor=\"#D8D8D8\">Tout Ind.</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">iAction</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">HD</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Digital IOT</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Digital Optotech</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">iRelax/Exec. Office</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Precision</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Promo Internet</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Maxiwide</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Total</th>
				</tr>";
	
	
//1 -Partie Trois-Rivieres
$user_id     = "('entrepotifc','entrepotsafe')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
	
$queryDigitalIOT   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT  = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT    = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

	
$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	


$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%'  
AND redo_order_num is null
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	


$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%'  
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Precision + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech </td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
			</tr>";
			
			
			
			
			
			
			
			
			
			
			
			
			
	
//2- Partie Drummondville
$user_id     = "('entrepotdr','safedr')";
$Nom_de_l_entrepot = 'Entrepot de Drummondville';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT  = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT    = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	

$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND redo_order_num is null
AND order_status NOT IN ('cancelled')
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%'
AND redo_order_num is null  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	


$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%'  
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech </td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet </td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";		
			
			
			
			
// 3-Partie Laval
$user_id     = "('laval','lavalsafe')";
$Nom_de_l_entrepot = 'Entrepot de Laval';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	

$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%'
AND redo_order_num is null  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	


	

$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;
$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech </td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";		
			
			
		
			
			
			
			
			
// 4-Partie Terrebonne
$user_id     = "('terrebonne','terrebonnesafe')";
$Nom_de_l_entrepot = 'Entrepot de Terrebonne';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	


$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%' 
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	




$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";		
					
			
			
			
			
			
			
			
	
// 5-Partie Sherbrooke
$user_id     = "('sherbrooke','sherbrookesafe')";
$Nom_de_l_entrepot = 'Entrepot de Sherbrooke';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con, $queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	


$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	


$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
				<td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
			
			
// 6-Partie Halifax
$user_id     = "('warehousehal','warehousehalsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Halifax';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	


$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%' 
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%'  
AND redo_order_num is null
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	


$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
			
				
			
			
			
		
// 7-Partie Chicoutimi
$user_id     = "('chicoutimi','chicoutimisafe')";
$Nom_de_l_entrepot = 'Entrepot de Chicoutimi';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%' 
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	


$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Chicoutimi



// 8-Partie Lévis
$user_id     = "('levis','levissafe')";
$Nom_de_l_entrepot = 'Entrepot de Levis';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];		

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%executive%'  
AND redo_order_num is null
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Lévis




// 9-Partie Longueuil
$user_id     = "('longueuil','longueuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Longueuil';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT   = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Longueuil




// 10-Partie Granby
$user_id     = "('granby','granbysafe')";
$Nom_de_l_entrepot = 'Entrepot de Granby';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	




$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	


$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Granby




// 11-Partie Québec
$user_id     = "('entrepotquebec','quebecsafe')";
$Nom_de_l_entrepot = 'Entrepot de Qu&eacute;bec';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	

$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	

$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	




$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%'  
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Québec


/*
// 12-Partie Montreal ZT1
$user_id     = "('montreal','montrealsafe')";
$Nom_de_l_entrepot = 'Entrepot de Montréal ZT1';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Montreal
*/


// 13-Partie Gatineau
$user_id     = "('gatineau','gatineausafe')";
$Nom_de_l_entrepot = 'Entrepot de Gatineau';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Gatineau



// 14-Partie St-Jérôme
$user_id     = "('stjerome','stjeromesafe')";
$Nom_de_l_entrepot = 'Entrepot de St-Jérôme';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie St-Jérôme






//15-Partie Edmundston
$user_id     = "('edmundston','edmundstonsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Edmundston';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Edmundston





//16-Partie Vaudreuil
$user_id     = "('vaudreuil','vaudreuilsafe')";
$Nom_de_l_entrepot = 'Entrepot Vaudreuil';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Vaudreuil




//17-Partie Sorel
$user_id     = "('sorel','sorelsafe')";
$Nom_de_l_entrepot = 'Entrepot Sorel-Tracy';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Sorel



//18-Partie Moncton
$user_id     = "('moncton','monctonsafe')";
$Nom_de_l_entrepot = 'Entrepot Moncton';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Moncton



//19-Partie Fredericton
$user_id     = "('fredericton','frederictonsafe')";
$Nom_de_l_entrepot = 'Entrepot Fredericton';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie Fredericton



//19-Partie GRIFFE
$user_id     = "('88666')";
$Nom_de_l_entrepot = 'GRIFFE lunetier';
$queryIfree  = "SELECT count(order_num) as iFree FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%ifree%' OR  order_product_name like '%4d%'  OR  order_product_name like '%impression%' OR  order_product_name like '%maxiwide%' OR  order_product_name like '%360%')  
AND order_status NOT IN ('cancelled')";
$resultIfree = mysqli_query($con,$queryIfree) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataIfree   = mysqli_fetch_array($resultIfree,MYSQLI_ASSOC);
$NB_iFree    = $DataIfree[iFree];		

$queryiAction  = "SELECT count(order_num) as iAction FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%iAction%' 
AND order_status NOT IN ('cancelled')";
$resultiAction = mysqli_query($con,$queryiAction) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiAction   = mysqli_fetch_array($resultiAction,MYSQLI_ASSOC);
$NB_iAction    = $DataiAction[iAction];	
	
$queryHD  = "SELECT count(order_num) as HD FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%HD%'  AND order_product_name not like '%SV%'  AND order_product_name not like '%single%' AND order_product_name not like '%digital%'
AND order_status NOT IN ('cancelled')";
$resultHD = mysqli_query($con,$queryHD) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataHD   = mysqli_fetch_array($resultHD,MYSQLI_ASSOC);
$NB_HD    = $DataHD[HD];	
	
$queryDigitalIOT  = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%digital progressive IOT%' 
AND order_status NOT IN ('cancelled')";
$resultDigitalIOT = mysqli_query($con,$queryDigitalIOT) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalIOT   = mysqli_fetch_array($resultDigitalIOT,MYSQLI_ASSOC);
$NB_Digital_IOT    = $DataDigitalIOT[Digital];	


$queryDigitalOptotech   = "SELECT count(order_num) as Digital FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND ( order_product_name like '%digital progressive par Optotech%'  OR order_product_name like '%Digital par Optotech%'  )
AND order_status NOT IN ('cancelled')";
$resultDigitalOptotech  = mysqli_query($con,$queryDigitalOptotech) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataDigitalOptotech    = mysqli_fetch_array($resultDigitalOptotech,MYSQLI_ASSOC);
$NB_Digital_Optotech    = $DataDigitalOptotech[Digital];	


$queryPromoInternet  = "SELECT count(order_num) as PromoInternet FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_name like '%Promo Internet%' OR order_product_name like '%Promo duo internet%')
AND order_status NOT IN ('cancelled')";
$resultPromoInternet = mysqli_query($con,$queryPromoInternet) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPromoInternet   = mysqli_fetch_array($resultPromoInternet,MYSQLI_ASSOC);
$NB_PromoInternet    = $DataPromoInternet[PromoInternet];	



$queryiRelax_Exe_Office  = "SELECT count(order_num) as iRelax_Exe_Office FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id AND order_product_name like '%irelax%'  
AND order_status NOT IN ('cancelled')
AND redo_order_num is null
OR
order_date_processed BETWEEN '$date1' and '$date2'
AND redo_order_num is null
AND user_id in $user_id AND order_product_name like '%executive%'  
AND order_status NOT IN ('cancelled')";
$resultiRelax_Exe_Office = mysqli_query($con,$queryiRelax_Exe_Office) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataiRelax_Exe_Office   = mysqli_fetch_array($resultiRelax_Exe_Office,MYSQLI_ASSOC);
$NB_iRelax_Exe_Office    = $DataiRelax_Exe_Office[iRelax_Exe_Office];	



$queryCamber  = "SELECT count(order_num) as Camber FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%Precision%'  
AND order_status NOT IN ('cancelled')";
$resultCamber = mysqli_query($con,$queryCamber) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCamber   = mysqli_fetch_array($resultCamber,MYSQLI_ASSOC);
$NB_Camber    = $DataCamber[Camber];	

$queryMaxiWide  = "SELECT count(order_num) as MaxiWide FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND order_product_name like '%MaxiWide%' 
AND order_status NOT IN ('cancelled')";
$resultMaxiWide = mysqli_query($con,$queryMaxiWide) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataMaxiWide   = mysqli_fetch_array($resultMaxiWide,MYSQLI_ASSOC);
$NB_MaxiWide    = $DataMaxiWide[MaxiWide];

$total = $NB_iFree + $NB_iAction + $NB_HD + $NB_Digital_IOT  + $NB_iRelax_Exe_Office  + $NB_Camber + $NB_PromoInternet +$NB_Digital_Optotech+ $NB_MaxiWide;

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
			    <td align=\"center\">$NB_iFree</td>
                <td align=\"center\">$NB_iAction</td>
				<td align=\"center\">$NB_HD</td>
			    <td align=\"center\">$NB_Digital_IOT</td>
				<td align=\"center\">$NB_Digital_Optotech</td>
                <td align=\"center\">$NB_iRelax_Exe_Office</td>
				<td align=\"center\">$NB_Camber</td>
				<td align=\"center\">$NB_PromoInternet</td>
				<td align=\"center\">$NB_MaxiWide</td>
				<td align=\"center\">$total</td>
				</tr>";			
//Fin partie GRIFFE


$message.="</table><br><br>";





//2ieme tableau pour les Polarisé/Transitions/Teintes
$message.= "<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";
$message.= "<tr>
                <th align=\"center\" bgcolor=\"#D8D8D8\">&nbsp;</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Polaris&eacute;s</th>
                <th align=\"center\" bgcolor=\"#D8D8D8\">Transitions</th>
				<th align=\"center\" bgcolor=\"#D8D8D8\">Teintes</th>
			</tr>";



//2.1 -Partie Trois-Rivieres
$user_id     = "('entrepotifc','entrepotsafe')";
$Nom_de_l_entrepot = 'Entrepot de Trois-Rivieres';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];		

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN TR			


//2.2 Partie Drummondville
$user_id     = "('entrepotdr','safedr')";
$Nom_de_l_entrepot = 'Entrepot de Drummondville';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN DR		



//2.3 Partie Laval
$user_id     = "('laval','lavalsafe')";
$Nom_de_l_entrepot = 'Entrepot de Laval';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Laval	


//2.4 Partie Terrebonne
$user_id     = "('terrebonne','terrebonnesafe')";
$Nom_de_l_entrepot = 'Entrepot de Terrebonne';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Terrebonne


//2.5 Partie Sherbrooke
$user_id     = "('sherbrooke','sherbrookesafe')";
$Nom_de_l_entrepot = 'Entrepot de Sherbrooke';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Sherbrooke


//2.6 Partie Halifax
$user_id     = "('warehousehal','warehousehalsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Halifax';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Halifax


//2.7 Partie Chicoutimi
$user_id     = "('chicoutimi','chicoutimisafe')";
$Nom_de_l_entrepot = 'Entrepot de Chicoutimi';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			


$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Chicoutimi


//2.8 Partie Lévis
$user_id     = "('levis','levissafe')";
$Nom_de_l_entrepot = 'Entrepot de L&eacute;vis';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Lévis


//2.9 Partie Longueuil
$user_id     = "('longueuil','longueuilsafe')";
$Nom_de_l_entrepot = 'Entrepot de Longueuil';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Longueuil


//2.10 Partie Granby
$user_id     = "('granby','granbysafe')";
$Nom_de_l_entrepot = 'Entrepot de Granby';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Granby



//2.11 Partie Québec
$user_id     = "('entrepotquebec','quebecsafe')";
$Nom_de_l_entrepot = 'Entrepot de Qu&eacute;bec';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Granby



/*
//2.12 Partie Montréal ZT1
$user_id     = "('montreal','montrealsafe')";
$Nom_de_l_entrepot = 'Entrepot de Montréal ZT1';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Montréal ZT1
*/





//2.13 Partie Gatineau
$user_id     = "('gatineau','gatineausafe')";
$Nom_de_l_entrepot = 'Entrepot de Gatineau';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Gatineau


//2.14 Partie St-Jérôme
$user_id     = "('stjerome','stjeromesafe')";
$Nom_de_l_entrepot = 'Entrepot de St-Jérôme';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN St-Jérôme




//2.14 Partie Edmundston
$user_id     = "('edmundston','edmundstonsafe')";
$Nom_de_l_entrepot = 'Entrepot d\'Edmundston';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Edmundston





//2.15 Partie Vaudreuil
$user_id     = "('vaudreuil','vaudreuilsafe')";
$Nom_de_l_entrepot = 'Entrepot Vaudreuil';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Vaudreuil




//2.15 Partie Sorel
$user_id     = "('sorel','sorelsafe')";
$Nom_de_l_entrepot = 'Entrepot Sorel-Tracy';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Sorel-Tracy





//2.16 Partie Moncton
$user_id     = "('moncton','monctonsafe')";
$Nom_de_l_entrepot = 'Entrepot moncton';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN Sorel-Tracy



//2.17 Partie Fredericton
$user_id     = "('fredericton','frederictonsafe')";
$Nom_de_l_entrepot = 'Entrepot Fredericton';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";

//FIN fredericton



//2.17 Partie GRIFFE
$user_id     = "('88666')";
$Nom_de_l_entrepot = 'Griffe Lunetier';

$queryTransitions  = "SELECT count(order_num) as NbTransitions FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_photo like '%brown%' OR  order_product_photo like '%grey%')  
AND order_status NOT IN ('cancelled')";
$resultTransitions = mysqli_query($con,$queryTransitions) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTransitions   = mysqli_fetch_array($resultTransitions,MYSQLI_ASSOC);
$NbTransitions     = $DataTransitions[NbTransitions];

$queryPolarized  = "SELECT count(order_num) as NbPolarized FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id in $user_id
AND redo_order_num is null
AND (order_product_polar like '%brown%' OR  order_product_polar like '%grey%' OR  order_product_polar like '%green%')  
AND order_status NOT IN ('cancelled')";
$resultPolarized = mysqli_query($con,$queryPolarized) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataPolarized   = mysqli_fetch_array($resultPolarized,MYSQLI_ASSOC);
$NbPolarized     = $DataPolarized[NbPolarized];			

$queryTeinte  = "SELECT count(orders.order_num) as NbTeintes FROM orders, extra_product_orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND orders.order_num =extra_product_orders.order_num
AND extra_product_orders.category='Tint'
AND user_id in $user_id
AND redo_order_num is null 
AND order_status NOT IN ('cancelled')";
$resultTeinte    = mysqli_query($con,$queryTeinte) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataTeintes     = mysqli_fetch_array($resultTeinte,MYSQLI_ASSOC);
$NbTeintes       = $DataTeintes[NbTeintes];	

$message.="<tr bgcolor=\"$bgcolor\">
 				<td align=\"center\">$Nom_de_l_entrepot</td>
				<td align=\"center\">$NbPolarized</td>
				<td align=\"center\">$NbTransitions</td>
				<td align=\"center\">$NbTeintes</td>
			</tr>";
//FIN griffe




//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');
$send_to_address = array('rapports@direct-lens.com');	

$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Types de progressifs vendus EDLL entre $date1 et $date2";
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
	$nomFichier = 'r_vente_type_progressif_Edll_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo '<br>Reussi';
    }else{
		echo '<br>Echec';
	}	
		

echo $subject.'<br>';
echo $message;


$time_end = microtime(true);
$time 	  = $time_end - $time_start;
$today 	  = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   			 = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   			 = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips			 = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
			VALUES('Rapport vente type de progressifs Edll 2.0', '$time','$today','$timeplus3heures','rapport_vente_type_progressif_edll.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
?>