<?php
ini_set('MAX_EXECUTION_TIME', -1);
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because 6: ' . mysql_error());	
}


for ($i = 13; $i <= 13; $i++) {
    echo '<br><br><br><br>'. $i;
	
switch($i){
	case  1: $Userid =  " orders.user_id IN ('entrepotifc','entrepotsafe')";      $Partie = 'Trois-Rivieres';	       
	$send_to_address = array('rapports@direct-lens.com');break;
	
	case  2: $Userid =  " orders.user_id IN ('entrepotdr','safedr')";        	  $Partie = 'Drummondville';		   
	$send_to_address = array('rapports@direct-lens.com'); break;
	
	case  3: $Userid =  " orders.user_id IN ('warehousehal','warehousehalsafe')"; $Partie = 'Halifax'; 				  
	 $send_to_address = array('rapports@direct-lens.com');          break;
	 
	case  4: $Userid =  " orders.user_id IN ('laval','lavalsafe')"; 			  $Partie = 'Laval';				   
	$send_to_address = array('rapports@direct-lens.com');        break;
	
	case  5: $Userid =  " orders.user_id IN ('gfd')";         $Partie = '-';  
	$send_to_address = array('rapports@direct-lens.com');   break;
	
	case  6: $Userid =  " orders.user_id IN ('terrebonne','terrebonnesafe')"; 	  $Partie = 'Terrebonne'; 			   
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  7: $Userid =  " orders.user_id IN ('sherbrooke','sherbrookesafe')";     $Partie = 'Sherbrooke'; 			  
	 $send_to_address = array('rapports@direct-lens.com');   break;
	 
	case  8: $Userid =  " orders.user_id IN ('chicoutimi','chicoutimisafe')";     $Partie = 'Chicoutimi';		       
	$send_to_address = array('rapports@direct-lens.com');    break;
	
	case  9: $Userid =  " orders.user_id IN ('levis','levissafe')"; 			  $Partie = 'Lévis';      			   
	$send_to_address = array('rapports@direct-lens.com');         break;
	
	case 10: $Userid =  " orders.user_id IN ('longueuil','longueuilsafe')";       $Partie = 'Longueuil';  			   
	$send_to_address = array('rapports@direct-lens.com');     break;
	
	case 11: $Userid =  " orders.user_id IN ('granby','granbysafe')";             $Partie = 'Granby';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
		
	case 12: $Userid =  " orders.user_id IN ('entrepotquebec','quebecsafe')";             $Partie = 'Québec';  				   
	$send_to_address = array('rapports@direct-lens.com');        break;
		
	//case 13: $Userid =  " orders.user_id IN ('montreal','montrealsafe')";             $Partie = 'Montreal ZT1';  				   
	//$send_to_address = array('rapports@direct-lens.com');        break;
}//End Switch

	
	$time_start  = microtime(true);
	$nbrResultat = 0;
	$today       = date("Y-m-d H:i:s");
	$rptQuery    = "SELECT * FROM orders WHERE $Userid AND redo_order_num IS NOT NULL
	AND order_date_shipped BETWEEN '2018-01-01' AND '2018-12-31'";
	echo '<br>'. $rptQuery;
	
	
		
		$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because 7: <br><br>' . mysql_error());
		$ordersnum=mysql_num_rows($rptResult);
		
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
	
			$message.="<body><table width=\"850\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
			$message.="
					<tr bgcolor=\"CCCCCC\">
						<td align=\"center\"><b># Reprise</b></td>
						<td align=\"center\"><b># Originale</b></td>
						<td align=\"center\"><b># Optipro Originale</b></td>
						<td align=\"center\"><b>Raison de reprise</b></td>
						<td align=\"center\"><b>Produit Original</b></td>
						<td align=\"center\"><b>Produit Reprise</b></td>
						<td align=\"center\"><b>Fournisseur Original</b></td>
						<td align=\"center\"><b>Opticien (Original)</b></td>
					</tr>";
					
	while ($listItem=mysql_fetch_array($rptResult)){
			
			$count++;
			 if (($count%2)==0)
					$bgcolor="#E5E5E5";
				else 
					$bgcolor="#FFFFFF";
	
			$queryOriginal   = "SELECT * from ORDERS WHERE order_status<>'cancelled' AND order_num = $listItem[redo_order_num]";
			$ResultOriginal  = mysql_query($queryOriginal)		or die  ('I cannot Send email because 6: ' . mysql_error());	
			$DataOriginal    = mysql_fetch_array($ResultOriginal);
		
			$queryRedoReason   = "SELECT * from redo_reasons WHERE redo_reason_id = $listItem[redo_reason_id]";
			$ResultRedoReason  = mysql_query($queryRedoReason)		or die  ('I cannot Send email because 6: ' . mysql_error());	
			$DataRedoReason    = mysql_fetch_array($ResultRedoReason);
		
		
		switch($DataOriginal[prescript_lab]){
			case 10: $Fournisseur='Swisscoat'; 		break;
			case 25: $Fournisseur='Central Lab'; 	break;
			case 3:  $Fournisseur='STC'; 			break;	
			case 69: $Fournisseur='Essilor Lab'; 	break;	
			case 72: $Fournisseur='QC'; 			break;
			case 70: $Fournisseur='Plastic Plus';	break;
			case 60: $Fournisseur='CSC';		 	break;
			case 68: $Fournisseur='QUEST';		 	break;
			default:  $Fournisseur='INCONNU';		break;	
		}
			
			$message.="<tr bgcolor=\"$bgcolor\">
						   <td height=\"150\" align=\"center\">$listItem[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num]</td>
						   <td height=\"150\" align=\"center\">$DataOriginal[order_num_optipro]</td>
						   <td align=\"center\">$DataRedoReason[redo_reason_fr]</td>
						   <td align=\"center\">$DataOriginal[order_product_name]</td>
						   <td align=\"center\">$listItem[order_product_name]</td>
						   <td align=\"center\">$Fournisseur</td>
						   <td align=\"center\">$DataOriginal[opticien]</td>
					   </tr>";
	}//END WHILE
	echo $message;
	//exit();
	
	$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\"><b>Nombre de commande(s): $count</b></td></tr></table>";
	
	//SEND EMAIL
	$send_to_address = array('rapports@direct-lens.com');			
	echo "<br>".$send_to_address;	
	echo '<br>'. $message;	
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject= "$Partie: Vos commandes en 'Hold'";
	echo '<br>'.$message;
	//exit();
	if ($nbrResultat > 0)
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<br>'; 
	var_dump($send_to_address);
	echo '<br>'; 
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
			log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		}else{
			log_email("REPORT: EDLL : Waiting for frame",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		}	
		
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	//echo "Execution time:  $time seconds\n";
	$today = date("Y-m-d");// current date
	$timeplus3heures = date("H:i:s");
	$ip   				   = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$ip2   				   = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$ips				   = $ip  . ' ' .$ip2 ;
	$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page, ip) VALUES('Send late jobs report by email', '$time','$today','$timeplus3heures','cron_send_late_jobs.php','$ips')"; 					
	$cronResult = mysql_query($CronQuery)			or die ( "Query failed: " . mysql_error());		
				
			
}//End For
?>