<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$today      = date("Y-m-d");	
$rptQuery   = "SELECT * FROM `login_attempt` Where datetime like '%$today%' AND  ip NOT IN ('66.131.20.33', '24.226.154.9','70.28.46.177','74.142.157.66','142.166.45.131','50.101.53.65')";
 
 //VOT 74.142.157.66
 //Drummond 24.226.154.9
 //Rive sud 66.131.20.33
 //Atlantic 142.166.45.131
 //SCT 50.101.53.65 	
 
echo $rptQuery;
$rptResult = mysqli_query($con,$rptQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
	
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
		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">ID</td>
                <td align=\"center\">Username</td>
                <td align=\"center\">Password</td>
                <td align=\"center\">Date time</td>
				<td align=\"center\">Ip</td>
                <td align=\"center\">Level</td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[id]</td>
                <td align=\"center\">$listItem[username]</td>
				<td align=\"center\">$listItem[password]</td>
				<td align=\"center\">$listItem[datetime]</td>
				<td align=\"center\">$listItem[ip]</td>
				 <td align=\"center\">$listItem[level]</td>";
              $message.="</tr>";
		}//END WHILE
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of attempt: $ordersnum</td></tr></table>";

}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime         = date("m-d-Y");	
$to_address      = $send_to_address;
$from_address    = 'donotreply@entrepotdelalunette.com';
$subject         = "Login attempt of the day: ". $curTime;
$response        = office365_mail($to_address, $from_address, $subject, null, $message);
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
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	


$time_end  = microtime(true);
$time      = $time_end - $time_start;
$today     = date("Y-m-d");// current date
echo "Execution time:  $time seconds\n";
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
			VALUES('Rapport tentatives de connexion de la journée 2.0', '$time','$today','$timeplus3heures','rapport_tentative_connexion.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		
?>