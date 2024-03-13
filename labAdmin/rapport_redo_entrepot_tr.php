<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");


$today=date("Y-m-d");

$rptQuery="SELECT user_id, order_num, prescript_lab, order_date_processed,   order_patient_first ,	order_patient_last ,	patient_ref_num , order_product_name, order_status, special_instructions 	,internal_note  FROM orders
WHERE user_id in ('entrepotifc') AND order_status not in ('cancelled','filled')  AND order_status='re-do'
OR
user_id = 'redoifc'AND order_status NOT IN ('cancelled', 'filled') 
AND order_status='re-do'
GROUP BY order_num 
ORDER BY order_date_processed"; 
	
	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because 1: ' . mysql_error());

	$ordersnum=mysql_num_rows($rptResult);
	
	if ($ordersnum!=0){
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

		$message.="<body><div  width=\"900px\" align=\"left\"><p align=\"center\"><b>Les commandes de l'entrepot au status RE-DO<b></p></div>";
						
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$queryLab  = "SELECT lab_name FROM labs where primary_key = $listItem[prescript_lab]";
		$resultLab = mysql_query($queryLab)		or die  ('I cannot select items because 2: ' . mysql_error());
		$DataLab   = mysql_fetch_array($resultLab);
		
		$count++;
		switch($listItem["order_status"]){
						case 'processing':				$list_order_status = "Confirmed";				break;
						case 'order imported':			$list_order_status = "Order Imported";			break;
						case 'job started':				$list_order_status = "Surfacing";				break;
						case 'in coating':				$list_order_status = "In Coating";				break;
						case 'profilo':					$list_order_status = "Profilo";					break;
						case 'in mounting':				$list_order_status = "In Mounting";				break;
						case 'in edging':				$list_order_status = "In Edging";				break;
						case 'order completed':			$list_order_status = "Order Completed";			break;
						case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
						case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
						case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
						case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
						case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
						case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
						case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
						case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
						case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";	break;
						case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
						case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
						case 're-do':					$list_order_status = "Redo";					break;
						case 'in transit':				$list_order_status = "In Transit";				break;
						case 'filled':					$list_order_status = "Shipped";					break;
						case 'cancelled':				$list_order_status = "Cancelled";				break;
						case 'verifying':				$list_order_status = "Verifying";				break;
						case "on hold":					$list_order_status = "On Hold";			        break;
						case 'waiting for frame store':	$list_order_status = "Waiting for Frame Store";	break;
						case 'waiting for frame ho/supplier':$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
						default:                        $list_order_status = "";             	        break;
		}

			
 
 

if ($listItem[user_id]=='entrepotifc'){
$message.="<table width=\"900\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";		
		$message.="
		<tr bgcolor=\"CCCCCC\">
				<th align=\"center\">Compte</th>
                <th align=\"center\">Order #</th>
                <th align=\"center\">Prescript Lab</th>
                <th align=\"center\">Order Date</th>
                <th align=\"center\">Status</th>
				<th align=\"center\">Patient</th>
				<th align=\"center\">Product</th>
				<th align=\"center\">Redo Order #</th>
				</tr>
				<tr bgcolor=\"FFFFFF\">
				  <td align=\"center\">$listItem[user_id]</td>
				  <td align=\"center\">$listItem[order_num]</td>
                  <td align=\"center\">$DataLab[lab_name]</td>
				  <td align=\"center\">$listItem[order_date_processed]</td>
				  <td align=\"center\">$list_order_status</td>
				  <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last] $listItem[patient_ref_num]</td>
                  <td align=\"center\">$listItem[order_product_name]</td>
				  <td align=\"center\">$listItem[redo_order_num]&nbsp;</td>";
    $message.="</tr>";
	$compteurDeRedo = 0;
	
	//Premier Redo
	$queryRedo  = "SELECT * FROM orders WHERE redo_order_num = $listItem[order_num]"; 
	$resultRedo = mysql_query($queryRedo)		or die  ('I cannot select items because 3: ' . mysql_error());
	$NbrResult  = mysql_num_rows($resultRedo);
	if ($NbrResult <> 0){


		while ($DataRedo = mysql_fetch_array($resultRedo))
		{
		$compteurDeRedo +=1;
		
		switch($compteurDeRedo){
					case 1: $bgcolor = "#55C827"; break;
					case 2: $bgcolor = "#DDD12E"; break;
					case 3: $bgcolor = "#D92F32"; break;
					case 4: $bgcolor = "#D92F32"; break; 
		}
		
		$queryLab         = "SELECT lab_name FROM labs where primary_key = $DataRedo[prescript_lab]";
		$resultLab 	      = mysql_query($queryLab)		or die  ('I cannot select items because 4: ' . mysql_error());
		$DataLaboratory   = mysql_fetch_array($resultLab);
		
		$message.="<tr bgcolor=\"$bgcolor\">
				  <td align=\"center\">$DataRedo[user_id]</td>
				  <td align=\"center\">$DataRedo[order_num]</td>
                  <td align=\"center\">$DataLaboratory[lab_name]</td>
				  <td align=\"center\">$DataRedo[order_date_processed]</td>
				  <td align=\"center\">$DataRedo[order_status]</td>
				  <td align=\"center\">$DataRedo[order_patient_first] $DataRedo[order_patient_last] $DataRedo[patient_ref_num]</td>
                  <td align=\"center\">$DataRedo[order_product_name]</td>
				  <td align=\"center\">$DataRedo[redo_order_num]&nbsp;</td>
				</tr>";
				$OrderNum_PremierRedo = $DataRedo[order_num];
		}//End While
	}//End IF
	
	
	
	
	//DEUXIEME Redo
	$queryRedo2  = "SELECT * FROM orders WHERE redo_order_num = $OrderNum_PremierRedo";
	$resultRedo2 = mysql_query($queryRedo2)		or die  ('I cannot select items because 5: ' . mysql_error());
	$NbrResult2  = mysql_num_rows($resultRedo2);
	if ($NbrResult2 <> 0){


		while ($DataRedo2 = mysql_fetch_array($resultRedo2))
		{
		$compteurDeRedo +=1;
		
		switch($compteurDeRedo){
					case 1: $bgcolor = "#55C827"; break;
					case 2: $bgcolor = "#DDD12E"; break;
					case 3: $bgcolor = "#D92F32"; break;
					case 4: $bgcolor = "#D92F32"; break; 
		}
		
		$queryLab  = "SELECT lab_name FROM labs where primary_key = $DataRedo2[prescript_lab]";
		//echo '<br>'. $queryLab;
		$resultLab = mysql_query($queryLab)		or die  ('I cannot select items because 6: ' . mysql_error());
		$DataLaboratory   = mysql_fetch_array($resultLab);
		
		
		$message.="<tr bgcolor=\"$bgcolor\">
				  <td align=\"center\">$DataRedo2[user_id]</td>
				  <td align=\"center\">$DataRedo2[order_num]</td>
                  <td align=\"center\">$DataLaboratory[lab_name]</td>
				  <td align=\"center\">$DataRedo2[order_date_processed]</td>
				  <td align=\"center\">$DataRedo2[order_status]</td>
				  <td align=\"center\">$DataRedo2[order_patient_first] $DataRedo2[order_patient_last] $DataRedo2[patient_ref_num]</td>
                  <td align=\"center\">$DataRedo2[order_product_name]</td>
				  <td align=\"center\">$DataRedo2[redo_order_num]&nbsp;</td>
				</tr>";
				$OrderNum_DeuxiemeRedo = $DataRedo2[order_num];
		}//End While
	}//End IF
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if ($OrderNum_DeuxiemeRedo <> '')
	{
		//TROISIEME Redo
		$queryRedo3  = "SELECT * FROM orders WHERE redo_order_num = $OrderNum_DeuxiemeRedo";
		echo '<br>'. $queryRedo3;
		$resultRedo3 = mysql_query($queryRedo3)		or die  ('I cannot select items because 7: ' . mysql_error());
		$NbrResult3  = mysql_num_rows($resultRedo3);
		if ($NbrResult3 <> 0){
	
	
			while ($DataRedo3 = mysql_fetch_array($resultRedo3))
			{
			$compteurDeRedo +=1;
			
			switch($compteurDeRedo){
						case 1: $bgcolor = "#55C827"; break;
						case 2: $bgcolor = "#DDD12E"; break;
						case 3: $bgcolor = "#D92F32"; break;
						case 4: $bgcolor = "#D92F32"; break; 
			}
			
			$queryLab  = "SELECT lab_name FROM labs where primary_key = $DataRedo3[prescript_lab]";
			$resultLab = mysql_query($queryLab)		or die  ('I cannot select items because 8: ' . mysql_error());
			$DataLaboratory   = mysql_fetch_array($resultLab);
			
			
			$message.="<tr bgcolor=\"$bgcolor\">
					  <td align=\"center\">$DataRedo3[user_id]</td>
					  <td align=\"center\">$DataRedo3[order_num]</td>
					  <td align=\"center\">$DataLaboratory[lab_name]</td>
					  <td align=\"center\">$DataRedo3[order_date_processed]</td>
					  <td align=\"center\">$DataRedo3[order_status]</td>
					  <td align=\"center\">$DataRedo3[order_patient_first] $DataRedo3[order_patient_last] $DataRedo3[patient_ref_num]</td>
					  <td align=\"center\">$DataRedo3[order_product_name]</td>
					  <td align=\"center\">$DataRedo3[redo_order_num]&nbsp;</td>
					</tr>";
			}//End While
		}//End IF
	}//End IF if ($OrderNum_DeuxiemeRedo <> '')
	
	$message.="</table><br><br>";
}//End if compte  = entrepotifc

		}//END WHILE







$queryRedoSpecialInstruction = "SELECT * FROM orders WHERE  user_id='entrepotifc' AND order_status not in ('cancelled','filled') 
AND (special_instructions LIKE '%redo%' OR  internal_note  LIKE '%redo%')";
$resultSpecialInst  = mysql_query($queryRedoSpecialInstruction)		or die  ('I cannot select items because 8: ' . mysql_error());
$NombreResultat     = mysql_num_rows($resultSpecialInst);
$compteur 			= 0;
if ($NombreResultat > 0){
	$message.= "<table width=\"900\" cellpadding=\"2\"  cellspacing=\"0\" border=\"1\" class=\"TextSize\">";
	$message.= "<tr><td align=\"center\" colspan=\"8\"><b>Autre(s) Commande(s) qui ont le mot 'REDO' dans l'instruction speciale ou la note interne</b></td></tr><tr bgcolor=\"CCCCCC\">
				<th align=\"center\">Compte</th>
                <th align=\"center\">Order #</th>
                <th align=\"center\">Prescript Lab</th>
                <th align=\"center\">Order Date</th>
                <th align=\"center\">Status</th>
				<th align=\"center\">Product</th>
				<th align=\"center\">Special Inst.</th>
				<th align=\"center\">Note Interne</th>
				</tr>";
	
	while ($DataSpecialInst=mysql_fetch_array($resultSpecialInst)){
	$compteur +=1;
	
	if (($compteur%2)==0)
   	$bgcolor="#E5E5E5";
	else 
	$bgcolor="#FFFFFF";

			$queryLab  = "SELECT lab_name FROM labs where primary_key = $DataSpecialInst[prescript_lab]";
			$resultLab = mysql_query($queryLab)		or die  ('I cannot select items because 8: ' . mysql_error());
			$DataLaboratory   = mysql_fetch_array($resultLab);
	
				$message.= "<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$DataSpecialInst[user_id]</td>
				<td align=\"center\">$DataSpecialInst[order_num]</td>
				<td align=\"center\">$DataLaboratory[lab_name]</td>
				<td align=\"center\">$DataSpecialInst[order_date_processed]</td>
				<td align=\"center\">$DataSpecialInst[order_status]</td>
				<td align=\"center\">$DataSpecialInst[order_product_name]</td>
				<td align=\"center\">$DataSpecialInst[special_instructions]&nbsp;</td>
				<td align=\"center\">$DataSpecialInst[internal_note]&nbsp;</td>
				</tr>";
	}		
	$message.="</table><br><br>";	
}

echo '<br>'. $message. '<br>';

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport des Redos de l'entrepot TR: ".$curTime;
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
	
	
	if($response){ 
		log_email("REPORT: Redo Report Entrepot TR",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Redo Report Entrepot TR",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	

}	


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
$ips				   = $ip  . ' ' .$ip2 ;
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) VALUES('Redo Report Entrepot', '$time','$today','$timeplus3heures','rapport_Redo_entrepot_tr.php','$ips') "  ; 					
$cronResult=mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());		


//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
?>
