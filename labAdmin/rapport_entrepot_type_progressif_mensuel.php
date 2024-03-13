<?php
include("../Connections/sec_connect.inc.php");
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');

$date1 = date("Y-m-d");
$date2 = date("Y-m-d");
$date1 = "2015-12-01";
$date2 = "2015-12-07";

$rptQueryTR  = "SELECT * FROM orders  
WHERE order_date_processed BETWEEN '$date1' and '$date2'
AND user_id='entrepotifc' and order_status NOT IN ('cancelled')";
$rptResult   = mysql_query($rptQueryTR)		or die  ('I cannot select items because: ' . mysql_error());
$ordersnum   = mysql_num_rows($rptResult);

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

$message.= "<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
$message.= "<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Entrepot Trois-Rivières</td>
                <td align=\"center\">Entrepot Drummondville</td>
                <td align=\"center\" bgcolor=\"#D8D8D8\">Entrepot Laval</td>
				<td align=\"center\">Entrepot Terrebonne</td>
				<td align=\"center\" bgcolor=\"#D8D8D8\">Entrepot Sherbrooke</td>
                <td align=\"center\">Entrepot Halifax</td>
				</tr>";
	
echo $message;	
		
while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


	    $queryFrame  = "SELECT * FROM extra_product_orders WHERE category= 'Frame' AND order_num = $listItem[order_num]";
		$resultFrame = mysql_query($queryFrame)	or die  ('I cannot select items because: ' . mysql_error());
		$ModeleTrouver = mysql_num_rows($resultFrame);
		$DataFrame   = mysql_fetch_array($resultFrame);
		$Collection  = $DataFrame[supplier];
		$FrameModel  = $DataFrame[temple_model_num];
		
		$queryCompany  =  "SELECT company FROM accounts WHERE user_id = '$listItem[user_id]'";	
		$resultCompany =  mysql_query($queryCompany)	or die  ('I cannot select items because: ' . mysql_error());
		$DataCompany   =  mysql_fetch_array($resultCompany);
		$Company       =  $DataCompany[company];

	$QuerySwissEdgingBarcode = "SELECT swiss_edging_barcode FROM swiss_edging_barcodes WHERE order_num = $listItem[order_num]";
	$resultEdgingBarcodee    =  mysql_query($QuerySwissEdgingBarcode)	or die  ('I cannot select items because: ' . mysql_error());
	$DataEdgingBarcode  	 = mysql_fetch_array($resultEdgingBarcodee);
	$SwissEdgingBarcode      = $DataEdgingBarcode[swiss_edging_barcode];

			$message.="<tr bgcolor=\"$bgcolor\">
			    <td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[tray_num]</td>
				<td align=\"center\">$Collection</td>
			    <td align=\"center\">$FrameModel</td>
                <td align=\"center\">$Company</td>
                <td align=\"center\">$listItem[order_product_name]</td>
				<td align=\"center\">$SwissEdgingBarcode</td>";
              $message.="</tr>";
}//END WHILE
mysql_free_result($rptResult);
$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');	

echo "<br>".$send_to_address;
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
	
	if($response){ 
		log_email("REPORT: Types de progressifs vendus EDLL entre $date1 et $date2",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Types de progressifs vendus EDLL entre $date1 et $date2",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	
		

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

echo $message;
?>