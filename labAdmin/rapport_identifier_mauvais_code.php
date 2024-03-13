<?php

include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
$today     = date("Y-m-d");
$rptQuery  = "SELECT * FROM ifc_ca_exclusive WHERE prod_status='active' AND collection like '%entrepot%'
AND primary_key>88375  ORDER BY primary_key LIMIT 0,155";//Donnera  plus de 10 000 produits.
echo '<br>'. $rptQuery;
$rptResult = mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
$count     = 0;
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
                <td align=\"center\">Product</td>
				<td align=\"center\">Nbr Commandes</td>
                <td align=\"center\">Nbr Redos</td>
				</tr>";
				
while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

		$queryNombreReprise = "SELECT COUNT(order_num) as NbrReprise, order_product_name FROM orders WHERE  order_from = 'ifcclubca' AND order_product_id =$listItem[primary_key] AND redo_order_num is not null ";
		//echo '<br>'. $queryNombreReprise;
		$resultReprise      = mysql_query($queryNombreReprise)		or die  ('I cannot select items because: ' . mysql_error());
		$DataNombreReprise  = mysql_fetch_array($resultReprise);
	
		$queryNombreCommande = "SELECT COUNT(order_num) as NbrCommande, order_product_name FROM orders WHERE  order_from = 'ifcclubca' AND order_product_id =$listItem[primary_key] ";
		//echo '<br>'. $queryNombreCommande . '<br><br><br>';
		$resultCommande       = mysql_query($queryNombreCommande)		or die  ('I cannot select items because: ' . mysql_error());
		$DataNombreCommande   = mysql_fetch_array($resultCommande);
	
	
		if ($DataNombreCommande[NbrCommande]>0){
		$message.="<tr bgcolor=\"$bgcolor\">
					   <td align=\"center\">$listItem[primary_key]</td>
					   <td align=\"center\">$listItem[product_name]</td>
					   <td align=\"center\">$DataNombreCommande[NbrCommande]</td>
					   <td align=\"center\">$DataNombreReprise[NbrReprise]</td>
				  </tr>";
		}//End IF
	
}//END WHILE
echo '<br><br>'. $message  .'<br><br>';
	   // exit();
		//mysql_free_result($rptResult);
		//$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject= "nouveau rapport Charles";
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
		log_email("REPORT: Redirection report Acculab",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Redirection report Acculab",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}


?>