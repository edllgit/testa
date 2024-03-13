<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");
$lab_id = 50; //Eagle
$today=date("Y-m-d");
$rptQuery="SELECT ifc_frames_french.*, product_inventory_ifc.product_inventory_id, product_inventory_ifc.min_inventory, product_inventory_ifc.inventory, product_inventory_ifc.last_updated, product_inventory_ifc.product_id
		FROM ifc_frames_french 
		LEFT JOIN product_inventory_ifc ON (product_inventory_ifc.product_id=ifc_frames_french.ifc_frames_id && product_inventory_ifc.lab_id='$lab_id' ) 
		ORDER BY code";
echo $rptQuery . '<br>';

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

		$message.="<body><table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.=" <tr>
    	<th>UPS</th>
    	<th>PRODUCT</th>
        <th>TYPE</th>
        <th>COLOR</th>
        <th>COLLECTION</th>
        <th>Qty REMAINING</th>
        <th>LAST UPDATE</th>
	</tr>";
				
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


			$message.="<tr bgcolor=\"$bgcolor\">
			<td align=\"center\">$listItem[upc]</td>
            <td align=\"center\">$listItem[code]</td>
			<td align=\"center\">$listItem[type]</td>
			<td align=\"center\">$listItem[color]</td>
			<td align=\"center\">$listItem[collection]</td>";
			
			if ($listItem[inventory] == null) {
			$message.= "<td align=\"center\">-</td>";
			}else{
			$message.= "<td align=\"center\">$listItem[inventory]</td>";
			}
		 $message.=	"<td align=\"center\">$listItem[last_updated]</td>";
				
             
              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);
		$message.="</table>";

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="Directlab Eagle Rapport d'inventaire des montures";	
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
		log_email("REPORT: Frames inventory Eagle",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    }else{
		log_email("REPORT: Frames inventory Eagle",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
	}	

}		


function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysql_query($queryMail)		or die  ('I cannot Send email because: ' . mysql_error());	
}


?>