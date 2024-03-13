<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$tomorrow   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut  = date("Y-m-d", $tomorrow);
$datefin    = $datedebut ;

//A RECOMMENTER 
/*
$datedebut  =  "2018-03-22";
$datefin    =  "2018-03-22";
*/

//////////// Partie #1 Commandes sans order from qui ne sont pas cancell�s////////////
echo '<br>Partie 1<br>';
$rptQuery="SELECT orders.*, labs.lab_name FROM orders, labs WHERE orders.lab = labs.primary_key AND order_from not in ('safety','eye-recommend','lensnetclub','directlens','ifcclub','aitlensclub','ifcclubca','ifcclubus') AND order_date_shipped between '$datedebut' AND '$datefin' AND order_status <> 'cancelled' AND order_total > 0 order by order_status";
echo '<br><br>' . $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$cumulOrderNum = "(";
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
		$message.="<body><h2>Factures sans Order From <b>AVANT</b> modification: </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order date</td>
			<td align=\"center\">Order From</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
	
	if ($count == 1){
		$cumulOrderNum = $cumulOrderNum . $listItem[order_num];
	}
	else{
		$cumulOrderNum = $cumulOrderNum . ' , '. $listItem[order_num];
	}
	
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
	
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>";
					if($listItem[order_date_shipped]!='0000-00-00')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  
					
					 $message.="<td align=\"center\">"; 
					if ($order_status == 'Cancelled'){
						$message.= '<b>'. $order_status. '</b>';
					}else{
						$message.=  $order_status ;
					}
					
					 
					 $message.= "</td>";
					 
					 if ($order_status <> 'Cancelled')
						$message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
						$message.= "<td align=\"center\">N/A</td>";

					 $message.="</tr>";
	
	}//End While
	$cumulOrderNum .=  ")";

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0
echo '<br><br>Cumul order num: ' . $cumulOrderNum;
//SEND EMAIL #1 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Acomba order from fix #1: Commandes sans Order From AVANT modification  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	




//Partie 2.update des order from selon les main labs
echo '<br>Partie 2<br>';
echo ' <br>Contenu de CumulOrder:'. var_dump($cumulOrderNum);
$rptQuery="SELECT orders.*, labs.lab_name FROM orders, labs WHERE orders.lab = labs.primary_key AND order_from not in ('safety','eye-recommend','lensnetclub','directlens','ifcclub','aitlensclub','ifcclubca','ifcclubus') AND order_date_shipped between '$datedebut' AND '$datefin' AND order_status <> 'cancelled' AND order_total > 0 order by order_status";
echo $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

	
		if ($ordersnum > 0)
		{
				
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	$count++;	
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
			
			switch($listItem[lab]){
				case "36":			$order_from = "directlens";  break;//Directlab atlantic
				case "47":			$order_from = "aitlensclub"; break;//AIT
								
				case "3":	
				$queryUserID  = "SELECT product_line FROM accounts WHERE user_id = '$listItem[user_id]'";
				echo   '<br>queryUserID: ' . $queryUserID;	
				$ResultUserID = mysqli_query($con,$queryUserID) or die  ('I cannot update order  because: ' . mysqli_error($con));
				$DataUserID   = mysqli_fetch_array($ResultUserID,MYSQLI_ASSOC);
				$Product_Line = $DataUserID[product_line];
				if ($Product_Line =='directlens')
					$order_from = "directlens";  
				elseif  ($Product_Line =='eye-recommend')
					$order_from = "eye-recommend";  
				break;//Directlab Saint Catharines
				
				case "43":			$order_from = "directlens";  break;//DirectLab Pacific
				case "21":			$order_from = "directlens";  break;//DirectLab Trois-rivieres
				default:    		$order_from = "inconnu"; 	 break;//Source de la commande inconnue ne pas faire de mise a jour
			}
			
			echo '<br><br> Order Num: ' . $listItem[order_num];
			echo '<br>Order from a appliquer: '.$order_from;
			
			
			if (($order_from <> "inconnu") && ($listItem[order_num] <> ''))
			{
				$queryUpdate = "UPDATE orders SET order_from = '$order_from' WHERE order_num = $listItem[order_num]";
				echo   '<br>queryupdate: ' . $queryUpdate;
				$rptResultUpdate=mysqli_query($con,$queryUpdate)		or die  ('I cannot update order  because: ' . mysqli_error($con));
			}
				
				
	}//End While


}//End if nbrResult > 0










//////////// Partie #3 Commandes dont le order from a �t� mis a jour par le script ////////////
echo '<br>Partie 3<br>';
echo ' <br>Contenu de CumulOrder:'. var_dump($cumulOrderNum);
$rptQuery="SELECT orders.*, labs.lab_name FROM orders, labs WHERE orders.lab = labs.primary_key AND order_num in $cumulOrderNum order by order_status";
echo '<br> Partie 3: '. $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$cumulOrderNum = "(";
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
		$message.="<body><h2>Factures sans Order From <b>APRES</b> modification: </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order date</td>
			<td align=\"center\">Order From</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
	
	if ($count == 1){
		$cumulOrderNum = $cumulOrderNum . $listItem[order_num];
	}
	else{
		$cumulOrderNum = $cumulOrderNum . ' , '. $listItem[order_num];
	}
	
	
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
						
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>";
					if($listItem[order_date_shipped]!='')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  
					
					 $message.="<td align=\"center\">"; 
					if ($order_status == 'Cancelled'){
						$message.= '<b>'. $order_status. '</b>';
					}else{
						$message.=  $order_status ;
					}
					
					 
					 $message.= "</td>";
					 
					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 

					 $message.="</tr>";
	
	}//End While
	$cumulOrderNum .=  ")";

}else{
	$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0

echo '<br><br>Cumul order num: ' . $cumulOrderNum;
//SEND EMAIL #3 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;	

//Logger l'ex�cution du script
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Script acomba order from fix', '$time','$today','$heure_execution','script_acomba_order_from_fix.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>