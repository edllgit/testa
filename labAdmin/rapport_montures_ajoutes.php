<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");

$yesterday	    = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$ilya7jours     = date("Y-m-d", $yesterday);
$today			= date("Y-m-d");	

$rptQuery="SELECT * FROM ifc_frames_french WHERE date_created BETWEEN '$ilya7jours' AND '$today'  ORDER BY collection";
echo $rptQuery;
$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
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

		$message.="<body><table width=\"950\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Collection</td>
                <td align=\"center\">Model</td>
				<td align=\"center\">Available on Ifc.ca</td>
				<td align=\"center\">Price Ifc.ca</td>
				<td align=\"center\">Discounted Price Ifc.ca</td>
				<td align=\"center\">Available for Entrepots</td>
				<td align=\"center\">Price Entrepot</td>
                <td align=\"center\">Available Milano6769.ca</td>
				<td align=\"center\">Active</td>
				<td align=\"center\">Date Created</td>
				<td align=\"center\">Frame A</td>
				<td align=\"center\">Frame B</td>
				<td align=\"center\">Frame ED</td>
				<td align=\"center\">Frame DBL</td>
				</tr>";
		$totalPrice      = 0;
		$totalOrderTotal = 0;	
		while ($listItem=mysql_fetch_array($rptResult)){
				
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$message.="	<tr bgcolor=\"$bgcolor\">
					   		<td align=\"center\">$listItem[collection]</td>
               				<td align=\"center\">$listItem[model]</td>";
							
             $message.="    <td align=\"center\">$listItem[display_on_ifcca]</td>
							<td align=\"center\">$listItem[stock_price]</td>
							<td align=\"center\">$listItem[stock_price_with_discount]</td>
			 				<td align=\"center\">$listItem[display_entrepot]</td>
			 				<td align=\"center\">$listItem[stock_price_entrepot]</td>
							<td align=\"center\">$listItem[display_milano6769Canada]</td>
							<td align=\"center\">$listItem[active]</td>
							<td align=\"center\">$listItem[date_created]</td>
							<td align=\"center\">$listItem[frame_a]</td>
							<td align=\"center\">$listItem[frame_b]</td>
							<td align=\"center\">$listItem[frame_ed]</td>
							<td align=\"center\">$listItem[frame_dbl]</td>";
              $message.="</tr>";
			  
	  
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="</table>";


//SEND EMAIL
//$send_to_address = array('rapports@direct-lens.com');
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Rapport des montures ajoutés (Entre $ilya7jours et $today)";
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

}	



function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}

//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");

?>