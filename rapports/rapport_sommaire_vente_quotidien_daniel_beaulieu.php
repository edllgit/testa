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

$time_start   = microtime(true);
$datefrom 	  = date("Y-m-d");
$dateto   	  = $datefrom;
$datefromdlab = date("Ymd");
$datetodlab   = $datefromdlab;




//Pour date Hard Codé A RECOMMENCER ! 
/*
$datefrom 	  = "2018-04-11";
$dateto   	  = "2018-04-11";
$datefromdlab = "20180411";
$datetodlab	  = "20180411";
*/



echo 'date from '  . $datefrom	 . '<br><br>';
echo 'date to '  . $dateto	 . '<br><br>';

echo 'datefromdlab '  . $datefromdlab	 . '<br><br>';
echo 'date to dlab '  . $datetodlab	 . '<br><br>';

//**************************************//////////////////////////////////////////////////////////

$time_start = microtime(true);

$Redo_Account = "('votredo','atlanticredo','redoqc','redoatl','lensnetpacific')";


$GrandTotal 		 = 	0;
$GrandTotalRedo		 =	0;
$GrandTotalEscompte  =	0;	
$GrandTotalCredit    =	0;	
$GrandTotalNet    	 =	0;	

//Variables pour totaux USa et Canada
$GrandTotalNetUSA   	=	0;	
$GrandTotalNetCA    	=	0;	
$GrandTotalNetLensnet   =	0;	

		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head><style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style></head>";
		

		$message.="<body><table   border=\"1\" width=\"500\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Total net</td>
				<td align=\"center\">Re-dos</td>
				<td align=\"center\">Escompte</td>
				<td align=\"center\">Cr&eacute;dit</td>
				<td align=\"center\">Ventes</td>
				</tr>";
				



//DEBUT CANADA
//1- Directlab Atlantic
//2- IFC.ca
//3- Directlab Italia REMPLACÉ PAR SAFE
//4- Directlab Pacific
//5- Directlab Saint-Catharines DLENS
//6- Directlab Saint-Catharines MANUF
//7- Directlab Trois-Rivieres
//DEBUT LENSNET
//8- Lensnet Atlantique

	
	
//Debut 	
	$Nomdulab = "Directlab Atlantic";
	$lelab   = 36;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000')
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 6: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}

			
$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 7: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);

	echo $rptQuery;
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 8: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);

	echo $rptQueryStock;
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	 $rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 9: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND mcred_acct_user_id not in $Redo_Account 
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 10: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
				$totalCredit = 0;
			
			$totalRedo = $listItemRedo[total]; 
			
			if ($totalRedo == Null)
				$totalRedo = 0;
		
			$GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
              <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			
			$GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 //Fin #1 Directlab Atlantic

	
	
	
	
	
//Debut 	
	$Nomdulab = "IFC.ca Production";
						
	$User_Id_IFC_CA = '(';
	$queryIfcCa="SELECT user_id from accounts WHERE main_lab not in (66,67) AND product_line = 'ifcclubca' and approved='approved'";	
	$resultIfcCa=mysqli_query($con,$queryIfcCa)		or die  ('I cannot select items because ifc.ca: ' . mysqli_error($con));		
	$compteur = 0;
	while($DataIfcCa=mysqli_fetch_array($resultIfcCa,MYSQLI_ASSOC))
	{
		if ($compteur == 0)	
		$User_Id_IFC_CA.=   "'". $DataIfcCa[user_id] . "'";
		else
		$User_Id_IFC_CA.= ",'" . $DataIfcCa[user_id] . "'";
		$compteur +=1;
	}
	$User_Id_IFC_CA .= ')';	
				echo '<br><br> IFC.CA : ' .$User_Id_IFC_CA ;
				
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND order_product_type <> 'frame_stock_tray'  AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is    NULL 	    AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>'. $rptEscompte . '<br><br>';
	
	
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 104: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	$nbrResult = mysqli_num_rows($rptResultEscompte);
	if ($nbrResult > 0)
	{
		while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
		{
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
				$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
				echo 'ajout escompte % '. '<br>';
			}
		
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
				echo 'ajout  escompte $'. '<br>';
				$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
			}
			
			if ($listItemEscompte['extra_product_price'] <0){
				//substring pour aller cherche le montant en enlever le signe negatif (-)
				$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
				echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
				$totalEscompte = $totalEscompte  + $extra_prod_price ;
			}
			
		}
	}

			
    $rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND orders.order_product_type = 'exclusive' AND order_product_type <> 'frame_stock_tray'  AND orders.redo_order_num is    NULL AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQuery: '. $rptQuery;
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 105: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND  orders.order_product_type <> 'exclusive' AND order_product_type <> 'frame_stock_tray'  AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryStock: '. $rptQueryStock;
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 106: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND orders.order_date_processed between '$datefrom' and '$dateto' AND order_product_type <> 'frame_stock_tray'    AND redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryRedo:'. $rptQueryRedo;
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 107: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND   mcred_acct_user_id not in $Redo_Account 
  AND  memo_credits.mcred_acct_user_id in $User_Id_IFC_CA
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>QueryCredit:'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 108: ' . mysqli_error($con));
   $totalCredit = 0;
	echo  $QueryCredit;
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End WHILE
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
		$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
		    $GrandTotalNet = $GrandTotalNet + $totalnet;
			$GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  Ifc.ca Production













//Debut 	
	$Nomdulab = "IFC.ca Frames";	
				
	$User_Id_IFC_CA = '(';
	$queryIfcCa="SELECT user_id from accounts WHERE product_line = 'ifcclubca' and approved='approved'";	
	$resultIfcCa=mysqli_query($con,$queryIfcCa)		or die  ('I cannot select items because ifc.ca: ' . mysqli_error($con));		
	$compteur = 0;
	while($DataIfcCa=mysqli_fetch_array($resultIfcCa,MYSQLI_ASSOC))
	{
		if ($compteur == 0)	
		$User_Id_IFC_CA.=   "'". $DataIfcCa[user_id] . "'";
		else
		$User_Id_IFC_CA.= ",'" . $DataIfcCa[user_id] . "'";
		$compteur +=1;
	}
	$User_Id_IFC_CA .= ')';	
	echo '<br><br> IFC.CA : ' .$User_Id_IFC_CA ;
				
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND order_product_type = 'frame_stock_tray'  AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is    NULL 	    AND (orders.order_status!='cancelled' AND orders.order_status!='basket') group by order_num";
	echo '<br><br>'. $rptEscompte . '<br><br>';
	
	
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 104: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	$nbrResult = mysqli_num_rows($rptResultEscompte);
	if ($nbrResult > 0)
	{
		while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
		{
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
				$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
				echo 'ajout escompte % '. '<br>';
			}
		
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
				echo 'ajout  escompte $'. '<br>';
				$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
			}
			
			if ($listItemEscompte['extra_product_price'] <0){
				//substring pour aller cherche le montant en enlever le signe negatif (-)
				$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
				echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
				$totalEscompte = $totalEscompte  + $extra_prod_price ;
			}
			
		}//End While
	}//End IF

			
    $rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND orders.order_product_type = 'exclusive' AND order_product_type = 'frame_stock_tray'  AND orders.redo_order_num is    NULL AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQuery: '. $rptQuery;
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 105: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND  orders.order_product_type <> 'exclusive' AND order_product_type = 'frame_stock_tray'  AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryStock: '. $rptQueryStock;
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 106: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND orders.order_date_processed between '$datefrom' and '$dateto' AND order_product_type = 'frame_stock_tray'    AND redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryRedo:'. $rptQueryRedo;
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 107: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND   mcred_acct_user_id not in $Redo_Account 
  AND  memo_credits.mcred_acct_user_id in $User_Id_IFC_CA
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>QueryCredit:'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 108: ' . mysqli_error($con));
   $totalCredit = 0;
	echo  $QueryCredit;
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
		$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
		    $GrandTotalNet = $GrandTotalNet + $totalnet;
			$GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  Ifc.ca Frames
	
	
	
				
//Debut SAFE
	$Nomdulab = "SAFE"; //EXCLURE LES SAFE VENDU AUX EDLL
	$lelab   = 59;
	echo '<br>SAFESAFE';	
	$CompteSafeEDLL = "('redosafety','GARAGEMP','entrepotsafe','safedr','lavalsafe','warehousehalsafe','terrebonnesafe','sherbrookesafe',
'chicoutimisafe','quebecsafe','longueuilsafe','stemariesafe','levissafe','gatineausafe','stjeromesafe','edmundstonsafe','vaudreuilsafe','sorelsafe','monctonsafe', 'frederictonsafe','stjohnsafe')";
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab AND order_from='safety'  AND user_id NOT IN $CompteSafeEDLL 
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 23: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

	
			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND order_from='safety' AND user_id NOT IN $CompteSafeEDLL  
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 24: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND user_id NOT IN $CompteSafeEDLL 
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 25: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	 $rptQueryRedo="Select   SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> '' AND user_id NOT IN $CompteSafeEDLL 
	AND (orders.order_status!='cancelled' AND order_from='safety' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 26: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND mcred_acct_user_id not in $Redo_Account  AND mcred_acct_user_id NOT IN $CompteSafeEDLL 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 27: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
		if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	}//End While


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			$GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                 <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  DL SAFE #3
	
		
	
				
//Debut 	
	$Nomdulab = "Directlab Pacific";
	$lelab   = 43;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 28: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 29: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 30: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];

	 $rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 31: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code
   AND mcred_acct_user_id not in $Redo_Account   
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 32: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	

			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin #4 DLAB Pacific			  
		

	
	
	

	
				
//Debut 	
$User_Id_EYE_RECOMMEND = '(';
	$queryEyeRecommend="SELECT user_id from accounts WHERE product_line = 'eye-recommend' and approved='approved'";	
	$resultEyeRecommend=mysqli_query($con,$queryEyeRecommend)		or die  ('I cannot select items because ifc.ca: ' . mysqli_error($con));		
	$compteur = 0;
	while($DataEyeRecommend=mysqli_fetch_array($resultEyeRecommend,MYSQLI_ASSOC))
	{
		if ($compteur == 0)	
		$User_Id_EYE_RECOMMEND.=   "'". $DataEyeRecommend[user_id] . "'";
		else
		$User_Id_EYE_RECOMMEND.= ",'" . $DataEyeRecommend[user_id] . "'";
		$compteur +=1;
	}
	$User_Id_EYE_RECOMMEND .= ')';	
	//echo '<br><br> Eye Recommend : ' .$User_Id_EYE_RECOMMEND . '<br><br>' ;
				
	$Nomdulab = "Directlab St-Catharines <b>Dlens</b>";
	$lelab   = 3;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto' AND order_from <> 'ifcclubca'  AND orders.redo_order_num is NULL AND orders.lab = $lelab AND user_id not in $User_Id_EYE_RECOMMEND	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 38: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		

		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

			
$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND order_from <> 'ifcclubca' AND user_id not in $User_Id_EYE_RECOMMEND	
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 39: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders  
	WHERE  order_from <> 'ifcclubca' AND orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND user_id not in $User_Id_EYE_RECOMMEND	
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 40: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	 $rptQueryRedo="SELECT  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND order_from <> 'ifcclubca' AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> '' AND user_id not in $User_Id_EYE_RECOMMEND	
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 41: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND mcred_acct_user_id not in $Redo_Account  AND mcred_acct_user_id not in $User_Id_EYE_RECOMMEND	
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 42: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
	if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
	
	if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $totalVenteSct =  $total;
			 $totalSctNet =  $totalnet;
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin 
			  
			
	
		
				
				
				  
			 	
	//Debut 	
	$Nomdulab = "Directlab St-Catharines <b>Manuf</b>";
	$lelab   = 3;	

	$rptQuery="Select  SUM(order_total) as total from dlab_orders 
	WHERE dlab_orders.order_date_processed between '$datefromdlab' and '$datetodlab'   and directlab ='sct'
	 AND (dlab_orders.order_status!='cancelled' AND dlab_orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 43: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);

	echo $rptQuery;
	//echo '<br>' . $rptQuery. ' ttl:' . $listItem[total];
	
	$rptQueryOrders="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  and prescript_lab = $lelab 
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$resultOrder=mysqli_query($con,$rptQueryOrders)		or die  ('I cannot select items because 44: ' . mysqli_error($con));
	$DataOrders=mysqli_fetch_array($resultOrder,MYSQLI_ASSOC);
	echo $rptQueryOrders;
	

	
			$total = $listItem[total];// + $DataOrders[total];
			if ($total == Null)
			$total = 0;
			
			
			$totalnet = $total;
		    $totalnet=money_format('%.2n',$totalnet);
			$total=money_format('%.2n',$total);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
               <td align=\"center\">".$totalnet."$</td>
			  <td align=\"center\">-</td>
			  <td align=\"center\">-</td>
			  <td align=\"center\">-</td>
			 <td align=\"center\">".$total."$</td>";
			  $message.="</tr>";
			  $totalVenteSct =  $totalVenteSct + $total;
			  $totalSctNet  =   $totalSctNet   + $totalnet;
			  $GrandTotal = $GrandTotal + $total;  
			  $GrandTotalNet = $GrandTotalNet + $totalnet;
			  $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 //Fin




	//Debut 	
	$Nomdulab = "Directlab St-Catharines <b>TOTAL</b>";
	$lelab   = 3;
	$total=0;
	$total=money_format('%.2n',$totalSctNet);
	$totalVente=money_format('%.2n',$totalVenteSct);	
	
	
	
	$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
               <td align=\"center\">".$total."$<b>*</b></td>
			  <td align=\"center\">-</td>
			  <td align=\"center\">-</td>
			  <td align=\"center\">-</td>
			 <td align=\"center\">".$totalVente."$</td>";
			  $message.="</tr>";
			 //Fin #6 SCT manuf et Dlens
	
	
	
	
	
	
	
					
//Debut 	
	$Nomdulab = "Directlab Trois-Rivi&egrave;res";
	$lelab   = 21;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND order_from <> 'ifcclubca'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 45: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
			

	 //Query des ventes		
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket') AND order_from <> 'ifcclubca'";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 46: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	echo '<br><br>';


	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND order_from <> 'ifcclubca'
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 47: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total] ;

	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''  AND order_from <> 'ifcclubca'
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND mcred_acct_user_id not in $Redo_Account  
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 48: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
	if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
	
	if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While

	
			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;		
		$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin Trois-rivieres #7	





	
		  
 //Début Lens Net 
							 	
	$Nomdulab = "Lensnet Club Atlantic";
	$lelab   = 33;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account  AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 65: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($con,$rptResultEscompte))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 66: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 67: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 68: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND   mcred_acct_user_id not in $Redo_Account 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br> :  '.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 69: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
	if ($DataCredit['mcred_cred_type'] =="credit"){
		$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
		echo 'ajout Credit  '. '<br>';
		}
	
	if ($DataCredit['mcred_cred_type'] == "debit"){
		echo 'ajout  Débit '. '<br>';
		$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While

	
			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
		$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 $GrandTotalNetLensnet = $GrandTotalNetLensnet +  $totalnet;
//Fin 
	
		
		

	
	
			
					
							
//Debut 	
	$Nomdulab = "Lensnet Club ON";
	$lelab   = 29;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 80: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	


			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 81: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 82: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	 $rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 83: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code
    AND mcred_acct_user_id not in $Redo_Account    
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 84: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
	if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
	
	if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);

			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
               <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 $GrandTotalNetLensnet = $GrandTotalNetLensnet +  $totalnet;
//Fin
			  
			 
			
		
			
			 
			 
										
//Debut 	
	$Nomdulab = "Lensnet Club Pacific";
	$lelab   = 44;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 85: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 86: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE  orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 87: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select  distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND  mcred_acct_user_id not in $Redo_Account 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 88: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			$GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 $GrandTotalNetLensnet = $GrandTotalNetLensnet +  $totalnet;
//Fin 


	echo '<br>GrandTotalnet-2: '.  $GrandTotalNet	 . '<br>';  



					
//Debut 	
	$Nomdulab = "Lensnet Club QC";
	$lelab   = 28;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 89: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

	$rptQuery="SELECT  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 90: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 91: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;

	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];

	
	$rptQueryRedo="SELECT  SUM(order_total) as total from orders 
	WHERE  orders.user_id not in $Redo_Account AND   orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 92: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code  
   AND   mcred_acct_user_id not in $Redo_Account 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 93: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
	if ($DataCredit['mcred_cred_type'] =="credit"){
			$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
			echo 'ajout Credit  '. '<br>';
		}
	
	if ($DataCredit['mcred_cred_type'] == "debit"){
			echo 'ajout  Débit '. '<br>';
			$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                 <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
		     $GrandTotalNet = $GrandTotalNet + $totalnet;
		     $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
		     $GrandTotalNetLensnet = $GrandTotalNetLensnet +  $totalnet;
//Fin  lensnet QC #13
		  	
		echo '<br>GrandTotalnet-1: '.  $GrandTotalNet	 . '<br>';  
		
			  
	

	
//Debut 	
	$Nomdulab = "Lensnet Club West";
	$lelab   = 34;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 99: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 100: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 101: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 102: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
    AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND   mcred_acct_user_id not in $Redo_Account 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
    echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 103: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
		$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
		     $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
			 $GrandTotalNetLensnet = $GrandTotalNetLensnet +  $totalnet;
//Fin  Lensnet West #15
		
//FIN LENSNET CLUB

//TOTAUX LNC
$message.="<tr bgcolor=\"$bgcolor\">
		   <td align=\"center\">TOTAL LENSNET ALONE</td>
           <td align=\"center\"><b>".$GrandTotalNetLensnet."</b>$</td>
		   </tr>";
		   
		   
		   
		echo '<br>GrandTotalnet1: '.  $GrandTotalNet	 . '<br>';  
			  
		   
		   
									
//Debut  EYE RECOMMEND 	
	$Nomdulab = "PRESTIGE";
	$lelab   = 3;//Clients e-r SONT DANS stc
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   ORDERS.USER_ID IN $User_Id_EYE_RECOMMEND AND  orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 99: ' . $rptEscompte . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

			
	$rptQuery="SELECT  SUM( order_total) as total from orders 
	WHERE   ORDERS.USER_ID IN $User_Id_EYE_RECOMMEND AND  orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 100: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE   orders.user_id in $User_Id_EYE_RECOMMEND AND  orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 101: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	 $rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id in $User_Id_EYE_RECOMMEND AND  orders.order_date_processed between '$datefrom' and '$dateto' AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 102: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
    AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND   mcred_acct_user_id in $User_Id_EYE_RECOMMEND
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 103: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
		$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  Lensnet West #15
		
//FIN Eye-Recommend   
		   
		   
	
		echo '<br>GrandTotalnet2: '.  $GrandTotalNet	 . '<br>';  
			  	   
		   
		   
		   
//TOTAUX CANADA
$message.="<tr bgcolor=\"$bgcolor\">
		   <td align=\"center\">TOTAL CANADA</td>
           <td align=\"center\"><b>".$GrandTotalNetCA."</b>$</td>
		   </tr>";

	
//FIN CANADA
	
	
	
	

		//echo '<br>GrandTotalnet3: '.  $GrandTotalNet	 . '<br>';  
			  

	
//DEBUT USA
//#1 Aitlensclub
//#2 Directlab USA
//#3 Directlab Eagle 
//#4 Illinois
//#5 Vot Dlens
//#6 VOT Manuf

	
	
		
								
//Debut 	
	$Nomdulab = "Directlab USA";
	$lelab   = 41;
			
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 49: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC))
	{
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 50: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="SELECT  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 51: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];

	
	$rptQueryRedo="SELECT  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 52: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code 
   AND mcred_acct_user_id not in $Redo_Account  
   AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
   AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 53: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
               <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			  $GrandTotalNetUSA = $GrandTotalNetUSA  + $totalnet;
//Fin Lensnet USA 	 
			 
			 
		
						
//Debut 	
	$Nomdulab = "Directlab Eagle";
	$lelab   = 50;
			
	$rptEscompte="SELECT order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 33: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While
	

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 34: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="SELECT  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 35: ' . mysql_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 36: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
   AND memo_codes.memo_code = memo_credits.mcred_memo_code
   AND mcred_acct_user_id not in $Redo_Account   
   AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
   AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 37: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	

	
			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
		 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                 <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			  $GrandTotalNetUSA = $GrandTotalNetUSA  + $totalnet;
//Fin  
				 

	
	
							
//Debut 	
	$Nomdulab = "Directlab Illinois";
	$lelab   = 46;
			
	$rptEscompte="Select DISTINCT  order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 54: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 55: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 56: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];

	
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 57: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;
	
	
	
   $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
    AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND mcred_acct_user_id not in $Redo_Account 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
	
	
    echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 58: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			 $GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
               <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalEscompte."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			  $GrandTotalNetUSA = $GrandTotalNetUSA  + $totalnet;
//Fin 			  

	 
		
	
			
	
		
							
		
			 
			 $message.="<tr bgcolor=\"$bgcolor\">
		   <td align=\"center\">TOTAL USA</td>
           <td align=\"center\"><b>".$GrandTotalNetUSA ."</b>$</td>
		   </tr>";
//FIN USA
			 
			 
			 
	 
		 





$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\"><b>Totaux:</b></td>";
                
			  $message.="<td align=\"center\"><b>".$GrandTotalNet."$</b></td>";
			  $message.="<td align=\"center\"><b>".$GrandTotalRedo."$</b></td>";
			  $message.="<td align=\"center\"><b>".$GrandTotalEscompte."$</b></td>";
			  $message.="<td align=\"center\"><b>".$GrandTotalCredit."$</b></td>
			  <td align=\"center\"><b>".$GrandTotal."$</b></td>";
			
              $message.="</tr>";

  $message.="<tr  bgcolor=\"$bgcolor\"><td  colspan=\"6\" align=\"center\">* Les cr&eacute;dits, re-dos et escompte manuf de VOT et Sct ne sont pas soustraits de ce total</td>";
			
              $message.="</tr></table><br>";
			  
			  
			
			
			
			
			
			
			
			  
//PARTIE EDLL Ifc.ca
	$message.="<table  border=\"1\" width=\"500\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td colspan=\"6\" align=\"center\">Partie Entrepot de la lunette</td>
				</tr>";	
				
$message.="<tr>
<th align=\"center\">Main Lab</th>
<th align=\"center\">Total net</th>
<th align=\"center\">Re-dos EDLL</th>
<th align=\"center\">Re-dos sans frais</th>
<th align=\"center\">Cr&eacute;dit</th>
<th align=\"center\">Ventes</th>
</tr>";	  


	
	$Nomdulab = "Entrepots de la lunette";
						
	$User_Id_IFC_CA = '(';
	$queryIfcCa="SELECT user_id from accounts WHERE main_lab IN (66,67) AND product_line = 'ifcclubca' and approved='approved'";	
	$resultIfcCa=mysqli_query($con,$queryIfcCa)		or die  ('I cannot select items because ifc.ca: ' . mysqli_error($con));		
	$compteur = 0;
	
	while($DataIfcCa=mysqli_fetch_array($resultIfcCa,MYSQLI_ASSOC)){
		if ($compteur == 0)	
		$User_Id_IFC_CA.=   "'". $DataIfcCa[user_id] . "'";
		else
		$User_Id_IFC_CA.= ",'" . $DataIfcCa[user_id] . "'";
		$compteur +=1;
	}
	
	$User_Id_IFC_CA .= ')';	
	echo '<br><br> IFC.CA : ' .$User_Id_IFC_CA ;
				
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND order_product_type <> 'frame_stock_tray'  AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is    NULL 	    AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>'. $rptEscompte . '<br><br>';
	
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 104: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	$nbrResult = mysqli_num_rows($rptResultEscompte);
	if ($nbrResult > 0){
		while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
			
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
				$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
				echo 'ajout escompte % '. '<br>';
			}
		
			if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
				echo 'ajout  escompte $'. '<br>';
				$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
			}
			
			if ($listItemEscompte['extra_product_price'] <0){
				//substring pour aller cherche le montant en enlever le signe negatif (-)
				$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
				echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
				$totalEscompte = $totalEscompte  + $extra_prod_price ;
			}
			
		}//End While
	}//End IF

			
    $rptQuery="Select  SUM( order_total) as total from orders 
	WHERE  orders.user_id in $User_Id_IFC_CA AND orders.order_product_type = 'exclusive' AND order_product_type <> 'frame_stock_tray'   AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br>CETTE REQUETE CI<br>rptQuery: '. $rptQuery;
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 105: ' . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	echo '<br>ici2: Total'.  $listItem[total];
	
	
	$rptQueryStock="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND  orders.order_product_type <> 'exclusive' AND order_product_type <> 'frame_stock_tray'  AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'  AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br>ICI Stock<br>rptQueryStock: '. $rptQueryStock;
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 106: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	echo '<br>ici3: Total'.  $listItemStock[total];
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select  SUM(order_total) as total from orders 
	WHERE   orders.user_id not in $Redo_Account AND  orders.user_id in $User_Id_IFC_CA AND orders.order_date_processed between '$datefrom' and '$dateto' AND order_product_type <> 'frame_stock_tray' AND  orders.user_id NOT IN ('redoifc','St.Catharines')   AND redo_order_num <> ''
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryRedo:'. $rptQueryRedo;
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 107: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo $rptQueryRedo;



	$rptQueryRedoInterne="Select  SUM(order_total) as TotalRedoSansFrais from orders 
	WHERE   orders.user_id  in ('redoifc','St.Catharines') AND orders.order_date_processed between '$datefrom' and '$dateto' AND order_product_type <> 'frame_stock_tray' 
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	echo '<br><br>rptQueryRedo:'. $rptQueryRedoInterne;
	$ResultRedoInterne=mysqli_query($con,$rptQueryRedoInterne)		or die  ('I cannot select items because 107: ' . mysqli_error($con));
	$DataredoInterne=mysqli_fetch_array($ResultRedoInterne,MYSQLI_ASSOC);
	echo '<br>Query redo interne: '. $rptQueryRedoInterne . '<br>';


	
	
	
    $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
    AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND   mcred_acct_user_id not in $Redo_Account 
    AND  memo_credits.mcred_acct_user_id in $User_Id_IFC_CA
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   
   echo '<br><br>QueryCredit:'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 108: ' . $QueryCredit. mysqli_error($con));
   $totalCredit = 0;
	echo  $QueryCredit;
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While
	


			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			$GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$TotalRedoInterne = money_format('%.2n',$DataredoInterne[TotalRedoSansFrais]);
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
		$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
             <td align=\"center\"><b>".$totalnet."</b>$</td>
			 <td align=\"center\">".$totalRedo."$</td>
			 <td align=\"center\">".$TotalRedoInterne."$</td>
			 <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
		    $GrandTotalNet = $GrandTotalNet + $totalnet;
			$GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  Ifc.ca  partie EDLL










//Debut SAFE
	$Nomdulab = "SAFE"; //EXCLURE LES SAFE VENDU AUX EDLL
	$lelab   = 59;	
	$CompteSafeEDLL = "('redosafety','GARAGEMP','entrepotsafe','safedr','lavalsafe','warehousehalsafe','terrebonnesafe','sherbrookesafe',
'chicoutimisafe','stemariesafe','longueuilsafe','granbysafe','quebecsafe','gatineausafe','stjeromesafe','edmundstonsafe','vaudreuilsafe','sorelsafe','monctonsafe', 'frederictonsafe','stjohnsafe')";
	$rptEscompte="Select order_num, global_dsc, order_total, additional_dsc, discount_type, extra_product_price from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'  AND orders.redo_order_num is NULL AND orders.lab = $lelab AND order_from='safety'  AND user_id IN $CompteSafeEDLL 
	AND orders.order_total NOT IN ('-1000','-5000') AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultEscompte=mysqli_query($con,$rptEscompte)		or die  ('I cannot select items because 23: ' . mysqli_error($con));
	$totalEscompte = 0;
	echo $rptEscompte;
	
	while($listItemEscompte=mysqli_fetch_array($rptResultEscompte,MYSQLI_ASSOC)){
		
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '%')){
			$totalEscompte = $totalEscompte  + (($listItemEscompte['additional_dsc']/100) * $listItemEscompte['order_total']);
			echo 'ajout escompte % '. '<br>';
		}
	
		if (($listItemEscompte['additional_dsc'] >0) && ($listItemEscompte['discount_type'] == '$')){
			echo 'ajout  escompte $'. '<br>';
			$totalEscompte = $totalEscompte  + $listItemEscompte['additional_dsc'] ;
		}
		
		if ($listItemEscompte['extra_product_price'] <0){
			//substring pour aller cherche le montant en enlever le signe negatif (-)
			$extra_prod_price = substr($listItemEscompte['extra_product_price'],1,strlen($listItemEscompte['extra_product_price'])-1);
			echo $listItemEscompte['order_num'] . ' ajout extra product a ' . $extra_prod_price  .  '<br>';
			$totalEscompte = $totalEscompte  + $extra_prod_price ;
		}
		
	}//End While

	
			
	$rptQuery="Select  SUM( order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type = 'exclusive'  AND orders.order_date_processed between '$datefrom' and '$dateto'   
	AND orders.lab = $lelab AND order_from='safety' AND user_id  IN $CompteSafeEDLL  
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResult=mysqli_query($con, $rptQuery)		or die  ('I cannot select items because 24: '  .$rptQuery . mysqli_error($con));
	$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
	echo $rptQuery;
	
	$rptQueryStock="Select  SUM( distinct order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_product_type <> 'exclusive' AND orders.redo_order_num is NULL AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab AND user_id  IN $CompteSafeEDLL 
	AND (orders.order_status!='cancelled' AND orders.order_status!='basket')";
	$rptResultStock=mysqli_query($con,$rptQueryStock)		or die  ('I cannot select items because 25: ' . mysqli_error($con));
	$listItemStock=mysqli_fetch_array($rptResultStock,MYSQLI_ASSOC);
	echo $rptQueryStock;
	
	
	$TotalStocketExclusive = $listItem[total] + $listItemStock[total];
	
	$rptQueryRedo="Select   SUM(order_total) as total from orders 
	WHERE orders.user_id not in $Redo_Account AND orders.order_date_processed between '$datefrom' and '$dateto'   AND orders.lab = $lelab and redo_order_num <> '' AND user_id  IN $CompteSafeEDLL 
	AND (orders.order_status!='cancelled' AND order_from='safety' AND orders.order_status!='basket')";
	$rptResultRedo=mysqli_query($con,$rptQueryRedo)		or die  ('I cannot select items because 26: ' . mysqli_error($con));
	$listItemRedo=mysqli_fetch_array($rptResultRedo,MYSQLI_ASSOC);
	echo  $rptQueryRedo;
	
	
    $QueryCredit="Select distinct memo_credits.* from memo_credits, memo_codes, orders where mcred_date  between '$datefrom' and '$dateto'  
    AND memo_codes.memo_code = memo_credits.mcred_memo_code  
    AND mcred_acct_user_id not in $Redo_Account  AND mcred_acct_user_id  IN $CompteSafeEDLL 
    AND memo_codes.mc_lab = $lelab AND orders.order_num = memo_credits.mcred_order_num
	AND memo_codes.mc_lab = orders.lab";
   echo '<br><br>'.  $QueryCredit . '<br><br>';
   $rptqueryCredit=mysqli_query($con,$QueryCredit)		or die  ('I cannot select items because 27: ' . mysqli_error($con));
   $totalCredit = 0;
	
	while ($DataCredit=mysqli_fetch_array($rptqueryCredit,MYSQLI_ASSOC)){
	
		if ($DataCredit['mcred_cred_type'] =="credit"){
				$totalCredit = $totalCredit  + $DataCredit['mcred_abs_amount'] ;
				echo 'ajout Credit  '. '<br>';
		}
		
		if ($DataCredit['mcred_cred_type'] == "debit"){
				echo 'ajout  Débit '. '<br>';
				$totalCredit = $totalCredit - $DataCredit['mcred_abs_amount'] ;
		}
	
	}//End While

	  
			if ($totalCredit == Null)
			$totalCredit = 0;
			$totalRedo = $listItemRedo[total]; 
			if ($totalRedo == Null)
			$totalRedo = 0;
			$GrandTotalRedo = $GrandTotalRedo + $totalRedo;
			$total = $TotalStocketExclusive;
			if ($total == Null)
			$total = 0;
			
			$total=money_format('%.2n',$total);
			$totalRedo=money_format('%.2n',$totalRedo);
			$totalEscompte=money_format('%.2n',$totalEscompte);
			$totalCredit=money_format('%.2n',$totalCredit);
			$totalnet = $total - $totalRedo  - $totalCredit;
			$totalnet=money_format('%.2n',$totalnet);
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$Nomdulab</td>
                 <td align=\"center\"><b>".$totalnet."</b>$</td>
			  <td align=\"center\">".$totalRedo."$</td>
			  <td align=\"center\">".$totalCredit."$</td>
			 <td align=\"center\">".$total."$</td>";
              $message.="</tr>";
			 $GrandTotal = $GrandTotal + $total;  
			 $GrandTotalEscompte = $GrandTotalEscompte + $totalEscompte;
			 $GrandTotalCredit = $GrandTotalCredit + $totalCredit;
			 $GrandTotalNet = $GrandTotalNet + $totalnet;
			 $GrandTotalNetCA = $GrandTotalNetCA + $totalnet;
//Fin  DL SAFE #3

		
			  

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Summary Sales Report: $datefrom";
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
	
	
		// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_sommaire_vente_quotidien_Daniel_Beau_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	

echo $message;
		
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
VALUES('Rapport Quotidien Sommaire Daniel Beaulieu', '$time','$today','$timeplus3heures','rapport_sommaire_vente_quotidien_daniel_beaulieu.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
	
?>