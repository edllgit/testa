<?php

/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$tomorrow   = mktime(0,0,0,date("m"),date("d"),date("Y"));
$datedebut  = date("Y-m-d", $tomorrow);
$datedebut  =  "2018-05-01";
$datefin    =  "2018-05-31";

//////////// Partie #1 Commandes sans order from////////////
$rptQuery="SELECT orders.*, labs.lab_name FROM orders, labs WHERE orders.lab = labs.primary_key AND order_from = '' AND order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 order by order_status";
echo $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

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
		$message.="<body><h2>Factures sans Order From: </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Order From</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
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

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0


echo $message;

//SEND EMAIL #1 Commandes sans Order From
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #1: Commandes sans Order From  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	








//////////////////////////// Partie #2  Commandes qui ne sont PAS export� a Acomba/////////////////////////

//Commande shipp�s durant le mois export�	
$rptQuery="SELECT orders.*, labs.lab_name FROM orders, labs WHERE orders.lab = labs.primary_key AND transfered_acomba_dln_customer = 'no' AND order_date_shipped between '$datedebut' AND '$datefin'  AND order_total > 0 order by order_status";
echo $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	
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

		$message.="<body><h2>Factures du $datedebut au $datefin Non transferes a Acomba : </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Main Lab</td>
                <td align=\"center\">Order Date</td>
                <td align=\"center\">Date Shipped</td>
                <td align=\"center\">Order Status</td>
				<td align=\"center\">Order From</td>
				<td align=\"center\">House Account</td>
			    <td align=\"center\">Transfer to Acomba</td>
			    <td align=\"center\">Date transfer</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				
				switch($listItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";				break;
						case 'order imported':			$order_status = "Order Imported";			break;
						case 'job started':				$order_status = "Surfacing";				break;
						case 'in coating':				$order_status = "In Coating";				break;
						case 'in mounting':				$order_status = "In Mounting";				break;
						case 'in edging':				$order_status = "In Edging";				break;
						case 'order completed':			$order_status = "Order Completed";			break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':	$order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$order_status = "Waiting for Frame KNR";	break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";		break;
						case 're-do':					$order_status = "Redo";						break;
						case 'in transit':				$order_status = "In Transit";				break;
						case 'filled':					$order_status = "Shipped";					break;
						case 'cancelled':				$order_status = "Cancelled";				break;
						case 'verifying':				$order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$order_status = "Scanned shape to Swiss"; 	break;
						default:						$order_status = "UNKNOWN";
		}

			
			
			$QueryHouseAccount  = "SELECT house_account from accounts WHERE user_id = '" . $listItem[user_id]. "'";
			echo 'query: '. $QueryHouseAccount ;
			$ResultHouseAccount = mysqli_query($con,$QueryHouseAccount)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataHouseAccount   = mysqli_fetch_array($ResultHouseAccount,MYSQLI_ASSOC);
			if ($DataHouseAccount[house_account]==1)
			$HouseAccount 	   =  "Oui";
			 
			echo '<br><br>Order Num :'. $listItem[order_num];
			
			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[lab_name]</td><td align=\"center\">$order_date</td>";
				if($ship_date!=0)
                	$message.="<td align=\"center\">$ship_date</td>";
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
				 
				 
				 if ($order_status <> 'Cancelled')
				 $message.= "<td align=\"center\"><b>$HouseAccount</b></td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 
				 
				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
            	 $message.="</tr>";
		}//END WHILE
	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table></body></html>";

echo $message;

//SEND EMAIL #2
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #2 (commande non transferes):  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	






//////////// Partie #4 Commande avec une date de transfert avant la date shipp� (v�rifier si pay� par carte de cr�dit en ligne)///////////
$rptQuery="SELECT user_id, order_num, order_date_processed, order_date_shipped, date_transfer_acomba_dln_customer
FROM `orders`
WHERE date_transfer_acomba_dln_customer NOT IN ('2012-01-01 00:00:00', '0000-00-00 00:00:00','2013-01-01 00:00:00') AND date_transfer_acomba_dln_customer < order_date_shipped";

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

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
		$message.="<body><h2>Commandes avec une date de transfert acomba avant la date shipped:</h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order Date</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Order From</td>
			<td align=\"center\">Order Status</td>
			<td align=\"center\">Transfered to acomba</td>
			<td align=\"center\">Date transfer</td>
			<td align=\"center\">Order Total</td>
			<td align=\"center\">Paiement Carte Credit</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	
		switch($listItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";				break;
						case 'order imported':			$order_status = "Order Imported";			break;
						case 'job started':				$order_status = "Surfacing";				break;
						case 'in coating':				$order_status = "In Coating";				break;
						case 'in mounting':				$order_status = "In Mounting";				break;
						case 'in edging':				$order_status = "In Edging";				break;
						case 'order completed':			$order_status = "Order Completed";			break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':	$order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for frame knr':	$order_status = "Waiting for Frame KNR";	break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";		break;
						case 're-do':					$order_status = "Redo";						break;
						case 'in transit':				$order_status = "In Transit";				break;
						case 'filled':					$order_status = "Shipped";					break;
						case 'cancelled':				$order_status = "Cancelled";				break;
						case 'verifying':				$order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$order_status = "Scanned shape to Swiss"; 	break;
						default:						$order_status = "UNKNOWN";
		}
		
	
	$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
				
	
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>";
					if($listItem[order_date_shipped]!='0000-00-00')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  

					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					  if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$order_status</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 				 
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					  if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[order_total]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					
					
					$queryPayment="SELECT  count(*) as NbrResult from payments WHERE order_num = $listItem[order_num]";
					$ResultPayment=mysqli_query($con,$queryPayment)		or die  ('I cannot select items because: ' . mysqli_error($con));
					$DataPayment=mysqli_fetch_array($ResultPayment,MYSQLI_ASSOC);
					
					if ($DataPayment[NbrResult] > 0)
					
					 $message.= "<td align=\"center\">Oui</td>";
					 else
					 $message.= "<td align=\"center\"><b>NON</b></td>";
				 
					 $message.="</tr>";
	
	}//End While

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0

echo $message;

//SEND EMAIL #4
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #4: Commande avec une date de transfert avant la date shipped ($datedebut - $datefin) ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	
	

















//VALIDATION DES MEMO CREDITS


//////////// Partie #5 Memo credits avec date transf�r� acomba 2012-01-01///////////
$rptQuery="SELECT  memo_credits.* FROM memo_credits WHERE  mcred_date between '$datedebut' AND '$datefin'  and date_transfer_acomba_dln_customer like '%2012-01-01%' order by mcred_date";

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

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
		$message.="<body><h2>Credits transferes '2012-01-01': </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Memo credit #</td>
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order Date</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Order From</td>
			<td align=\"center\">Order Total</td>
			</tr>";
		
	while ($DataOrderFrom=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
	
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[mcred_memo_num]</td>
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$order_date</td>";
					if($listItem[order_date_shipped]!='0000-00-00')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  
					
					 
					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 
					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\"><b>$HouseAccount</b></td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					  if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[order_total]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 $message.="</tr>";
	
	}//End While

}else{
$message.="<tr> <td align=\"center\">Aucun credit</td></tr>";
}//End if nbrResult > 0

echo $message;

//SEND EMAIL #5
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #5: Credits flagges: 2012-01-01  ($datedebut - $datefin) ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	

	
	
	
	
	

//////////////////////////// Partie #6  cr�dits qui ne sont PAS export� a Acomba/////////////////////////

//cr�dits �mis durant le mois export�	
$rptQuery="SELECT  memo_credits.* FROM memo_credits WHERE  mcred_date between '$datedebut' AND '$datefin'  AND transfered_acomba_dln_customer <> 'yes' order by mcred_date";
echo $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	
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

		$message.="<body><h2>Credits du $datedebut au $datefin Non transferes a Acomba : </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
		    	<td align=\"center\">Memo credit #</td>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">House Account</td>
			    <td align=\"center\">Transfer to Acomba</td>
			    <td align=\"center\">Date transfer</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				

			$HouseAccount 	   =  "Non";
			$QueryHouseAccount  = "SELECT house_account from accounts WHERE user_id = '" . $listItem[mcred_acct_user_id]. "'";
			echo 'query: '. $QueryHouseAccount ;
			$ResultHouseAccount = mysqli_query($con,$QueryHouseAccount)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataHouseAccount   = mysqli_fetch_array($ResultHouseAccount,MYSQLI_ASSOC);
			if ($DataHouseAccount[house_account]==1)
			$HouseAccount 	   =  "Oui";
			

			echo '<br><br>Order Num :'. $listItem[order_num];
			
			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[mcred_memo_num]</td>
			<td align=\"center\">$listItem[mcred_order_num]</td>
			<td align=\"center\">$listItem[mcred_date]</td>";
				
			
				 
				 
				 if ($order_status <> 'Cancelled')
				 $message.= "<td align=\"center\"><b>$HouseAccount</b></td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
            	 $message.="</tr>";
		}//END WHILE
	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table></body></html>";


//SEND EMAIL #6
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #6 (credits  non transferes):  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	





//////////////////////////// Partie #7  cr�dits qui ONT �T�S transf�r�s a  Acomba/////////////////////////

//cr�dits �mis durant le mois export�	
$rptQuery="SELECT  memo_credits.* FROM memo_credits WHERE  mcred_date between '$datedebut' AND '$datefin'  AND transfered_acomba_dln_customer = 'yes' order by mcred_date";
echo $rptQuery;
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	
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

		$message.="<body><h2>Credits du $datedebut au $datefin transferes a Acomba : </h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
		    	<td align=\"center\">Memo credit #</td>
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">House Account</td>
			    <td align=\"center\">Transfer to Acomba</td>
			    <td align=\"center\">Date transfer</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				

			$HouseAccount 	   =  "NON";
			$QueryHouseAccount  = "SELECT house_account from accounts WHERE user_id = '" . $listItem[mcred_acct_user_id]. "'";
			echo 'query: '. $QueryHouseAccount ;
			$ResultHouseAccount = mysqli_query($con,$QueryHouseAccount)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataHouseAccount   = mysqli_fetch_array($ResultHouseAccount,MYSQLI_ASSOC);
			if ($DataHouseAccount[house_account]==1)
			$HouseAccount 	   =  "Oui";
			
			
			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[mcred_memo_num]</td>
			<td align=\"center\">$listItem[mcred_order_num]</td>
			<td align=\"center\">$listItem[mcred_date]</td>";
				
				if ($order_status == 'Cancelled'){
					$message.= '<b>'. $order_status. '</b>';
				}else{
					$message.=  $order_status ;
				}
				
				 
				 $message.= "</td>";
				 
				 
				 if ($order_status <> 'Cancelled')
				 $message.= "<td align=\"center\"><b>$HouseAccount</b></td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
				 
				 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
				 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
				 else
				 $message.= "<td align=\"center\">N/A</td>";
            	 $message.="</tr>";
		}//END WHILE
	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table></body></html>";


echo $message;

//SEND EMAIL #7
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #7 (credits  transferes):  $datedebut - $datefin ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	





//////////// Partie #8 Commande avec date de transfert: 2012-01-01 ///////////
$rptQuery="SELECT user_id, order_num, order_date_processed, order_date_shipped, date_transfer_acomba
FROM `orders` WHERE date_transfer_acomba_dln_customer  IN ('2012-01-01 00:00:00') and order_date_shipped between '$datedebut' AND '$datefin'";

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

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
		$message.="<body><h2>Commandes avec une date de transfert acomba avant la date shipped:</h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order Date</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Order From</td>
			<td align=\"center\">Order Status</td>
			<td align=\"center\">Transfered to acomba</td>
			<td align=\"center\">Date transfer</td>
			<td align=\"center\">Order Total</td>
			<td align=\"center\">Paiement Carte Credit</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	
		switch($listItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";				break;
						case 'order imported':			$order_status = "Order Imported";			break;
						case 'job started':				$order_status = "Surfacing";				break;
						case 'in coating':				$order_status = "In Coating";				break;
						case 'in mounting':				$order_status = "In Mounting";				break;
						case 'in edging':				$order_status = "In Edging";				break;
						case 'order completed':			$order_status = "Order Completed";			break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$order_status = "Waiting for Frame Swiss";		break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";		break;
						case 're-do':					$order_status = "Redo";						break;
						case 'in transit':				$order_status = "In Transit";				break;
						case 'filled':					$order_status = "Shipped";					break;
						case 'cancelled':				$order_status = "Cancelled";				break;
						case 'verifying':				$order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$order_status = "Scanned shape to Swiss"; 		break;
						default:						$order_status = "UNKNOWN";
		}
	
	$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					

				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>";
					if($ship_date!=0)
						$message.="<td align=\"center\">$ship_date</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  

					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					  if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$order_status</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 				 
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[transfered_to_acomba]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[date_transfer_acomba]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					  if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[order_total]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					
					
				$queryPayment  = "SELECT  count(*) as NbrResult from payments WHERE order_num = $listItem[order_num]";
				$ResultPayment = mysqli_query($con,$queryPayment)		or die  ('I cannot select items because: ' . mysqli_error($con));
				$DataPayment   = mysqli_fetch_array($ResultPayment,MYSQLI_ASSOC);
					
					if ($DataPayment[NbrResult] > 0)
					
					 $message.= "<td align=\"center\">Oui</td>";
					 else
					 $message.= "<td align=\"center\"><b>NON</b></td>";
					
					 $message.="</tr>";
	
	}//End While

}else{
	$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0

echo $message;

//SEND EMAIL #8
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #8: Commande avec une date de transfert 2012-01-01 ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	






//////////// Partie #9 Commande avec une date shipped avant la date de commande ILLOGIQUE ///////////
$rptQuery="SELECT user_id, order_num, order_date_processed, order_date_shipped, date_transfer_acomba_dln_customer
FROM `orders` WHERE order_date_shipped NOT IN ('0000-00-00') AND order_date_shipped < order_date_processed and order_date_shipped between '$datedebut' AND '$datefin'";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

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
		$message.="<body><h2>Commandes avec une date de transfert shipped inferieur a la date processed:</h2><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	
		if ($ordersnum > 0)
		{
			$message.="<tr bgcolor=\"CCCCCC\">
			<td align=\"center\">Order Number</td>
			<td align=\"center\">Main Lab</td>
			<td align=\"center\">Order Date</td>
			<td align=\"center\">Date Shipped</td>
			<td align=\"center\">Order From</td>
			<td align=\"center\">Order Status</td>
			<td align=\"center\">Transfered to acomba</td>
			<td align=\"center\">Date transfer</td>
			<td align=\"center\">Order Total</td>
			<td align=\"center\">Paiement Carte Credit</td>
			</tr>";
		
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
	
		switch($listItem["order_status"]){
						case 'processing':				$order_status = "Confirmed";				break;
						case 'order imported':			$order_status = "Order Imported";			break;
						case 'job started':				$order_status = "Surfacing";				break;
						case 'in coating':				$order_status = "In Coating";				break;
						case 'in mounting':				$order_status = "In Mounting";				break;
						case 'in edging':				$order_status = "In Edging";				break;
						case 'order completed':			$order_status = "Order Completed";			break;
						case 'delay issue 0':			$order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':		$order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for lens':		$order_status = "Waiting for Lens";			break;
						case 'waiting for shape':		$order_status = "Waiting for Shape";		break;
						case 're-do':					$order_status = "Redo";						break;
						case 'in transit':				$order_status = "In Transit";				break;
						case 'filled':					$order_status = "Shipped";					break;
						case 'cancelled':				$order_status = "Cancelled";				break;
						case 'verifying':				$order_status = "Verifying";				break;
						case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
						default:						$order_status = "UNKNOWN";
		}
	
	$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
					
	
				echo '<br><br>Order Num :'. $listItem[order_num];
				
				$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
				<td align=\"center\">$listItem[lab_name]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>";
					if($listItem[order_date_shipped]!='0000-00-00')
						$message.="<td align=\"center\">$listItem[order_date_shipped]</td>";
					else
						$message.="<td align=\"center\">&nbsp;</td>";
				  

					 if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$listItem[order_from]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					  if ($order_status <> 'Cancelled')
					 $message.= "<td align=\"center\">$order_status</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 				 
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[transfered_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					 
					 if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[date_transfer_acomba_dln_customer]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					  if (($order_status <> 'Cancelled') || ($HouseAccount ==  "Non"))
					 $message.= "<td align=\"center\">$listItem[order_total]</td>";
					 else
					 $message.= "<td align=\"center\">N/A</td>";
					
					
				$queryPayment="SELECT  count(*) as NbrResult from payments WHERE order_num = $listItem[order_num]";
				$ResultPayment=mysqli_query($con,$queryPayment)		or die  ('I cannot select items because: ' . mysqli_error($con));
				$DataPayment=mysqli_fetch_array($ResultPayment,MYSQLI_ASSOC);
					
					if ($DataPayment[NbrResult] > 0)
					
					 $message.= "<td align=\"center\">Oui</td>";
					 else
					 $message.= "<td align=\"center\"><b>NON</b></td>";
					
					
					
					 $message.="</tr>";
	
	}//End While

}else{
$message.="<tr> <td align=\"center\">Aucune commande</td></tr>";
}//End if nbrResult > 0

echo $message;

//SEND EMAIL #9
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;		
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport de validation Acomba #9: Commande avec une date  shipped avant la date de commande ILLOGIQUE! ";
$response=office365_mail($to_address, $from_address, $subject, null, $message);	



$time_end	= microtime(true);
$time 		= $time_end - $time_start;
$today 		= date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   		= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   		= $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips		= $ip  . ' ' .$ip2 ;
$CronQuery  = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) 
VALUES('Rapport validation acomba 2.0', '$time','$today','$timeplus3heures','rapport_validation_acomba.php','$ips')"; 					
$cronResult = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));
*/
?>