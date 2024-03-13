<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$tomorrow = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$todaysdate = date("Y/m/d", $tomorrow);	
$tomorrowmoins30 = mktime(0,0,0,date("m"),date("d")-30,date("Y"));
$ilyaunmois = date("Y/m/d", $tomorrowmoins30);		
	
//Parmis les clients de Saint catharines(3) et de lens net ontario (29)
//qui a acheté pour plus de 2000$ dans le dernier mois.
	
$rptQuery  = "SELECT distinct link_account FROM accounts WHERE main_lab in (3,29) and approved='approved' order by company";	
$rptResult = mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error() . '<br><br>'. $rptQuery);
$ordersnum = mysql_num_rows($rptResult);
	
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

		$message.="<body><table width=\"950\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="
		<tr bgcolor=\"CCCCCC\">
	<th width=\"60\">Company</th>
	<th width=\"110\">Purchases in last 30 days</th>
	</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
			
			$queryLinkAccount  =  "SELECT user_id FROM accounts WHERE link_account = ". $listItem[link_account];
			$resultLinkAccount = mysql_query($queryLinkAccount) or die  ('I cannot select items because: ' . mysql_error() . '<br><br>'. $queryLinkAccount);
			$compteur = 0;
			$UserID ='(';
			
			while ($DataLinkAccount=mysql_fetch_array($resultLinkAccount)){
				$compteur = $compteur +1;
				if ($compteur == 1)
				$UserID .='\'$DataLinkAccount[user_id]\'';
				
				if ($compteur > 1)
				$UserID .=',\'$DataLinkAccount[user_id]\'';
			}//End While
			$UserID .=')';
			echo '<br><br>The user id:'. $UserID;
			$queryTotalPurchases = "SELECT SUM(order_total) as total_purchases_last_30_days FROM ORDERS WHERE USER_ID IN 			 AND order_date_processed BETWEEN '$ilyaunmois' AND '$todaysdate'";
			echo '<br><br>' . $queryTotalPurchases;
			
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";



			$message.="<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$DataLab[lab_name]</td>";
               $message.="
                <td align=\"center\">$listItem[order_patient_last] $listItem[order_patient_first]</td>";
              $message.="</tr>";
		}//END WHILE
		$message.="</table>";

}
//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
echo '<br>'. $message;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "Rapport 2000$ achat dans 30 derniers jours " . $datecomplete;
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
		log_email("Rapport 2000$ achat dans 30 derniers jours",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: sucess';
    }else{
		log_email("Rapport 2000$ achat dans 30 derniers jours",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo 'Logged: failed';
	}	
	
	
	
	function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

?>