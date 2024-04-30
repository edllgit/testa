<?php 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);	
$totalCharles = 0;
$labQuery  	  = "SELECT primary_key, lab_name, reports_email from labs WHERE primary_key NOT IN (8,10,11,12,15,19,23,24,25,26,30,35)";
$labResult    = mysqli_query($con,$labQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$labcount     = mysqli_num_rows($labResult);	

$date1				 = date("Y-m-d");
$date2        		 = date("Y-m-d");
$date_of_week_france = date("d-m-Y");

//A RECOMMENTER
/*
$date1 = "2021-05-07";
$date2 = "2021-05-07";
*/


$Redo_Account = "('votredo','atlanticredo','redoqc','redoatl','lensnetpacific')";

if($labcount != 0){	
	while($labData=mysqli_fetch_array($labResult,MYSQLI_ASSOC)){//step thru each lab and build order rpt for each lab	
	echo'<br> Dans le while';
	echo '<b>' . $labData['lab_name'] . '</b><br>';
		$lab_pkey	   = $labData[primary_key];
		$reports_email = $labData[reports_email];
		$lab_name	   = $labData[lab_name];
	
		$rptQuery="SELECT accounts.*,  orders.* from orders
		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) ";
		$rptQuery.="WHERE orders.user_id not in $Redo_Account AND orders.lab='$lab_pkey' AND orders.order_total > 0  
		AND orders.order_num != '0' AND orders.order_status!='cancelled' AND redo_order_num IS NULL 
		AND order_from <> 'ifcclubca' AND orders.order_date_processed between '$date1'  and '$date2'";
		$rptQuery.=" group by order_num order by company";
				
		if ($lab_pkey == 37){
			$heading="Relev&eacute; des commandes au $date_of_week_france";
		}else{
			$heading="Sales Report for $date_of_week";
		}
		echo '<br>'. $rptQuery . '<br><br>';
		
		$rptResult=mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$usercount=mysqli_num_rows($rptResult);
		
				
		$rptQuery="";
		$amtTotal=0;			  
		$orderTotal=0;

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
		$message.= "<td colspan=\"4\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		
		if ($lab_pkey == 37){
			$message.= "<tr><td width='100' align=\"center\">N client</td>";
			$message.= "<td width='200' align=\"center\">Nom de magasin</td>";
			$message.= "<td align=\"center\">Commandes</td>";
			$message.= "<td align=\"center\">Montant</td></tr>";
		}else{
			$message.= "<tr><td width='100' align=\"center\">Account No</td>";
			$message.= "<td width='200' align=\"center\">Company</td>";
			$message.= "<td align=\"center\">Orders</td>";
			$message.= "<td align=\"center\">Amount</td></tr>";
		}
		

		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			if(!isset($currentCompany)){
				$currentCompany=$listItem["company"];
				$currentAcct=$listItem["account_num"];
			}

			if($currentCompany!=$listItem["company"]){//if the company changes, print out the totals, zero out the counters and add the first totals of the new company
				$amtTotalDisplay=money_format('%.2n',$amtTotal);
				$message.="<tr><td align=\"center\">".$currentAcct."</td>";
				$message.="<td align=\"center\">".$currentCompany."</td>";
				$message .= "<td align=\"center\">";
				if ($orderTotal != 0){
					$message .= $orderTotal;
					}else{
					$message .= " ";
					}
				$message .= "</td>";
				$message .= "<td align=\"center\">";
				if ($amtTotalDisplay != 0){
					$message .= $amtTotalDisplay;
				}else{
					$message .= " ";
				}
				$message .= "</td>";
				$totalCharles = $totalCharles +$amtTotalDisplay;
				$message.= "</tr>";
				$amtTotal=0;			  
				$orderTotal=0;
				$currentCompany=$listItem["company"];
				$currentAcct=$listItem["account_num"];
				$amtTotal=$listItem["order_total"];			  
				$orderTotal++;
	
			}else{//if it's still the same account, add in the totals
				$amtTotal= bcadd($amtTotal, $listItem["order_total"], 2);			
				$orderTotal++;
			}//END IF NOT CURRENT ACCT
		}//END WHILE

	$amtTotalDisplay=money_format('%.2n',$amtTotal);
	$message.= "<tr><td align=\"center\">".$currentAcct."</td>";
	$message.= "<td align=\"center\">".$currentCompany."</td>";
	$message.= "<td align=\"center\">".$orderTotal."</td>";
	
	
	if ($amtTotalDisplay > 0){
		$totalCharles = $totalCharles +$amtTotalDisplay;
	}
	
	$orderTotal = 0;
	$message .= "<td align=\"center\">".$amtTotalDisplay."</td>";
	$message.= "</tr>";
	$message.= "</table>";
	
	
	
	if ($lab_pkey == 37){
		$message .= "<br><p align=\"center\"><b>Total:</b>  ".  money_format('%.2n',$totalCharles). "&euro; </p>";
	}else{
		$message .= "<br><p align=\"center\"><b>Total:</b>  ".  money_format('%.2n',$totalCharles). "$ </p>";
	}

	$message.="</body></html>";
	
		
if ($lab_pkey == 37){
		$subject ="Relevé des commandes au : ". $labData['lab_name'];
}else{
		$subject ="Daily Sales report: ". $labData['lab_name'];
}



switch($lab_pkey){
	case "50"://Directlab Eagle
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','thahn@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "47"://Ait Lens Club
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','thahn@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;

	case "46"://Directlab Illinois
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','thahn@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "43"://Directlab Pacific
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','rco.daniel@gmail.com','jmotyka@direct-lens.com');
	break;
		
	case "44"://Lensnet Pacific
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','rco.daniel@gmail.com','jmotyka@direct-lens.com');
	break;

	case "42"://Lensnet Club Italia
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com', 'thahn@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "41"://Directlab USA
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;

	case "40"://Directlab Italia
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;

	case "3"://Directlab St. Catharines
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','rco.daniel@gmail.com','kgawel@direct-lens.com', 'thahn@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "21":// Directlab Trois-Rivieres
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','rco.daniel@gmail.com');
	break;
	
	case "28"://Lensnet Club QC
	$Report_Email	= array('dbeaulieu@direct-lens.com','thahn@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "29"://Lensnet Club ON
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com', 'kgawel@direct-lens.com','thahn@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "32"://Lensnet Club USA
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "33"://Lensnet Club Atlantic
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com');
	break;
	
	case "34"://Lensnet Club West
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "32"://Lensnet Club USA
	$Report_Email	= array('dbeaulieu@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	case "36"://Directlab Atlantic
	$Report_Email	= array('dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','thahn@direct-lens.com','rco.daniel@gmail.com','dbeaulieu@direct-lens.com','jmotyka@direct-lens.com');
	break;
	
	default:
	$Report_Email = "rapports@direct-lens.com";
	break;

}
//A SUPPRIMER
//$Report_Email	= array('dbeaulieu@direct-lens.com');

echo '<br>';		

if (($Report_Email <> "") && ($usercount > 0)){	
	var_dump($Report_Email);
	echo '<br><br>';
	$curTime      = date("m-d-Y");	
	$to_address   = $Report_Email;
	$from_address = 'donotreply@entrepotdelalunette.com';
	$response = office365_mail($to_address, $from_address, $subject, null, $message);
	echo 'Resultat: '  . $response;
	echo "<br><br>Success: " . $to_address ;
}else{
	echo  'Reports email' . $Report_Email;
}
		
$currentAcct = "  ";
$currentCompany=" ";
$totalCharles = 0;
} // END WHILE  lab list
	

}//END IF LABCOUNT	




//SAFE PART
$totalCharles = 0;
echo '<b>' . 'SAFE' . '</b><br>';
//Daily report
$rptQuery="SELECT accounts.*,  orders.* from orders		LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) ";
$rptQuery.="WHERE orders.order_from = 'safety' AND orders.order_total > 0  AND orders.order_num != '0' AND orders.order_status!='cancelled' AND redo_order_num IS NULL AND orders.order_date_processed between '$date1'  and '$date2'";
$rptQuery.=" group by order_num order by company";
echo '<br>'. $rptQuery;

$heading	= "SAFE: Sales Report for $date_of_week";		
$rptResult	= mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$usercount	= mysqli_num_rows($rptResult);
		
$rptQuery="";
$amtTotal=0;			  
$orderTotal=0;

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
		$message.= "<td colspan=\"4\"><font color=\"white\">".$heading."</font></td>";
		$message.= "</tr>";
		
		$message.= "<tr><td width='100' align=\"center\">Account No</td>";
		$message.= "<td width='200' align=\"center\">Company</td>";
		$message.= "<td align=\"center\">Orders</td>";
		$message.= "<td align=\"center\">Amount</td></tr>";

		


		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			if(!isset($currentCompany)){
				$currentCompany=$listItem["company"];
				$currentAcct=$listItem["account_num"];
			}

			if($currentCompany!=$listItem["company"]){//if the company changes, print out the totals, zero out the counters and add the first totals of the new company
				$amtTotalDisplay=money_format('%.2n',$amtTotal);
				$message.="<tr><td align=\"center\">".$currentAcct."</td>";
				$message.="<td align=\"center\">".$currentCompany."</td>";
				$message .= "<td align=\"center\">";
				if ($orderTotal != 0){
					$message .= $orderTotal;
					}else{
					$message .= " ";
					}
				$message .= "</td>";
				$message .= "<td align=\"center\">";
				if ($amtTotalDisplay != 0){
					$message .= $amtTotalDisplay;
				}else{
					$message .= " ";
				}
				$message .= "</td>";
				//$message .= "<td align=\"center\">$amtTotalDisplay</td>";
				$totalCharles = $totalCharles +$amtTotalDisplay;
				$message.= "</tr>";
				$amtTotal=0;			  
				$orderTotal=0;
				$currentCompany=$listItem["company"];
				$currentAcct=$listItem["account_num"];
				$amtTotal=$listItem["order_total"];			  
				$orderTotal++;
	
			}else{//if it's still the same account, add in the totals
				$amtTotal= bcadd($amtTotal, $listItem["order_total"], 2);			
				$orderTotal++;
			}//END IF NOT CURRENT ACCT
		}//END WHILE

	$amtTotalDisplay=money_format('%.2n',$amtTotal);
	$message.= "<tr><td align=\"center\">".$currentAcct."</td>";
	$message.= "<td align=\"center\">".$currentCompany."</td>";
	$message.= "<td align=\"center\">".$orderTotal."</td>";
	
	if ($amtTotalDisplay > 0){
		$totalCharles = $totalCharles +$amtTotalDisplay;
	}
	
	$orderTotal = 0;
	$message .= "<td align=\"center\">".$amtTotalDisplay."</td>";
	$message.= "</tr>";
	$message.= "</table>";
	
	$message .= "<br><p align=\"center\"><b>Total:</b>  ".  money_format('%.2n',$totalCharles). "$ </p>";
	$message.="</body></html>";
	$subject ="Daily Sales report: SAFE";
	$Report_Email	= array('thahn@direct-lens.com','dbeaulieu@direct-lens.com','rco.daniel@gmail.com','r.iazzolino@direct-lens.com');
    //$Report_Email	= array('dbeaulieu@direct-lens.com');//A SUPPRIMER
echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';

if ($usercount > 0)
$response=office365_mail($to_address, $from_address, $subject, null, $message);

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";
	$totalCharles = 0;
	
//Fin partie SAFETY

















//IFC CLUB.CA PRODUCTION PART
$totalCharles = 0;
echo '<b>IFC.ca</b><br>';

//Daily report
$heading="IFC.ca: Sales Report for $date_of_week";		

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
$user_id = " user_id IN ('chicoutimi','chicoutimisafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con, $rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 	    = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('entrepotdr','safedr')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_DR  = $DataCommandes[NbrOrders];
$Amount_DR  = $DataCommandes[ttl_originales];
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
$user_id = " user_id IN ('granby','granbysafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_GR  = $DataCommandes[NbrOrders];
$Amount_GR  = $DataCommandes[ttl_originales];
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
$user_id = " user_id IN ('warehousehal','warehousehalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('laval','lavalsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('levis','levissafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('longueuil','longueuilsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('entrepotquebec','quebecsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes = mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('sherbrooke','sherbrookesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('terrebonne','terrebonnesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
$user_id = " user_id IN ('entrepotifc','entrepotsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
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
	
	
	
	
/*	
//1.0 Montreal ZT1
$Company = "Montreal ZT1";
$user_id = " user_id IN ('montreal','montrealsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_MTL 	= $DataCommandes[NbrOrders];
$Amount_MTL = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	
	
*/	
	
//Gatineau
$Company = "Gatineau";
$user_id = " user_id IN ('gatineau','gatineausafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_GAT 	= $DataCommandes[NbrOrders];
$Amount_GAT = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";





//ST-JÉROME
$Company = "St-J&eacute;rome";
$user_id = " user_id IN ('stjerome','stjeromesafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_STJ 	= $DataCommandes[NbrOrders];
$Amount_STJ = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";






//EDMUNDSTON
$Company = "Edmundston";
$user_id = " user_id IN ('edmundston','edmundstonsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_EDM 	= $DataCommandes[NbrOrders];
$Amount_EDM = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";




//VAUDREUIL
$Company = "Vaudreuil";
$user_id = " user_id IN ('vaudreuil','vaudreuilsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_Vau 	= $DataCommandes[NbrOrders];
$Amount_Vau = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";




//Sorel
$Company = "Sorel";
$user_id = " user_id IN ('sorel','sorelsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_So 	= $DataCommandes[NbrOrders];
$Amount_So = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";
	
	
	
	
	


//Moncton
$Company = "Moncton";
$user_id = " user_id IN ('moncton','monctonsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_Mct 	= $DataCommandes[NbrOrders];
$Amount_Mct = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";	



//Fredericton
$Company = "Fredericton";
$user_id = " user_id IN ('fredericton','frederictonsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_Fred 	= $DataCommandes[NbrOrders];
$Amount_Fred = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";	
	
	

//St-John
$Company = "St-John";
$user_id = " user_id IN ('stjohn','stjohnsafe')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
//echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
//echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_STJOHN 	= $DataCommandes[NbrOrders];
$Amount_STJOHN = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";	
	
	




//GRIFFE
//Trouver comment aller chercher le data de Griffé et l'ajouter dans ce rapport avant de l'envoyer..
include('../connexion_hbc.inc.php'); //Connexion DB HBC pour accéder aux ventes de Griffé

$Company = "Griffé Trois-Rivières";
$user_id = " user_id IN ('88666')";
//Commandes Originales
$rptQueryCommandes = "SELECT count(order_num) AS NbrOrders, sum(order_total) AS ttl_originales FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS  NULL";
echo '<br>'. $rptQueryCommandes;
$rptResultCommandes = mysqli_query($con,$rptQueryCommandes)	or die  ('I cannot select items because: '. $rptQueryCommandes . mysqli_error($con));
$DataCommandes 		= mysqli_fetch_array($rptResultCommandes,MYSQLI_ASSOC);
//Reprises
$rptQueryRedos     = "SELECT count(order_num) AS NbrRedos, sum(order_total) AS total_redos FROM orders WHERE $user_id  AND orders.order_date_processed between '$date1'  and '$date2' AND redo_order_num IS NOT NULL";
echo '<br>'. $rptQueryRedos;
$rptResultRedos = mysqli_query($con,$rptQueryRedos)	or die  ('I cannot select items because: ' . mysqli_error($con));
$DataRedos      = mysqli_fetch_array($rptResultRedos,MYSQLI_ASSOC);
$Nbr_GRF_TR 	= $DataCommandes[NbrOrders];
$Amount_GRF_TR = $DataCommandes[ttl_originales];
$Moyenne 	= $DataCommandes[ttl_originales]/$DataCommandes[NbrOrders];
$Moyenne 	= money_format('%.2n',$Moyenne);  		

	$amtTotalDisplay=money_format('%.2n',$amtTotal);

	$message.= "<tr><td align=\"center\">".$Company."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[NbrOrders]."</td>";
	$message.= "<td align=\"center\">".$DataRedos[NbrRedos]."</td>";
	$message.= "<td align=\"center\">".$DataCommandes[ttl_originales]."$</td>";
	$message.= "<td align=\"center\">".$Moyenne."$</td>";


//Fin GRIFFÉ


$TotalCommandes = $Nbr_TR + $Nbr_DR + $Nbr_GR + $Nbr_LE + $Nbr_CH + $Nbr_LV + $Nbr_TE + $Nbr_SH + $Nbr_LO + $Nbr_SMB + $Nbr_QC +$Nbr_HA +$Nbr_MTL + $Nbr_GAT + $Nbr_STJ +$Nbr_GRF_TR + $Nbr_EDM + $Nbr_Vau + $Nbr_So + $Nbr_Mct + $Nbr_Fred+ $Nbr_STJOHN; 
$totalCharles   = $Amount_TR + $Amount_DR+ $Amount_GR+ $Amount_LE+ $Amount_CH+ $Amount_LV+ $Amount_TE+ $Amount_SH+ $Amount_LO+ $Amount_SMB +  $Amount_QC+ $Amount_HA+ $Amount_MTL + $Amount_GAT + $Amount_STJ +$Amount_GRF_TR + $Amount_EDM + $Amount_Vau + $Amount_So + $Amount_Mct + $Amount_Fred + $Amount_STJOHN;
$totalCharles 	= money_format('%.2n',$totalCharles);  		



	$message.= "<tr><td align=\"right\" colspan=\"2\">Total:</td><td colspan=\"3\">$TotalCommandes orders = $totalCharles $</td></tr>
	</table></body></html>";
	$subject ="Daily Sales report: Ifc.ca Production:" . $date1;
	$Report_Email	= array('thahn@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','dbeaulieu@direct-lens.com','r.iazzolino@direct-lens.com');
    //$Report_Email	= array('dbeaulieu@direct-lens.com');//TODO A RECOMMENTER APRÈS MES TESTS

echo '<br><br>';
$curTime= date("m-d-Y");	
$to_address=$Report_Email;
$from_address='donotreply@entrepotdelalunette.com';

$response=office365_mail($to_address, $from_address, $subject, null, $message);

echo 'resultat'  . $response;
echo "<br><br>success: " . $to_address ;
	$currentAcct = "  ";
	$currentCompany=" ";
	$totalCharles = 0;
	
	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_vente_quotidien_'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/Vente/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $message);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
	

	
	
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport de vente Quotidien (Daily HTML) 2.0', '$time','$today','$timeplus3heures','rapport_vente_quotidien.php')"; 					
$cronResult 	 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>