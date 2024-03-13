<?php
include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
include("../Connections/sec_connect.inc.php");

$lab_pkey=30;//Conant Optical ID
$today=date("Y-m-d");

$query="SELECT notification_email,lab_email,primary_key,lab_name FROM labs WHERE primary_key='$lab_pkey'";
$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());

$labsItem=mysql_fetch_array($result);//GET LAB INFO
mysql_free_result($result);			
$rptQuery="SELECT company, order_num, tray_num, lab_name, prescript_lab, order_product_name, order_date_Shipped, order_patient_first, order_patient_last
FROM orders , accounts, labs
WHERE accounts.user_id = orders.user_id
AND labs.primary_key = accounts.main_lab
AND order_Date_shipped = '$today'
AND lab <>37
GROUP BY order_num
ORDER BY accounts.company, prescript_lab";
	

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
		$message.="<tr bgcolor=\"CCCCCC\">
                <td align=\"center\">Company</td>
                <td align=\"center\">Order Num</td>
                <td align=\"center\">Tray</td>
                <td align=\"center\">Main lab</td>
				<td align=\"center\">Patient</td>
                <td align=\"center\">Product</td>
				</tr>";
		 	 		 	 		
		while ($listItem=mysql_fetch_array($rptResult)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";

			$message.="<tr bgcolor=\"$bgcolor\"><td align=\"center\">$listItem[company]</td>
                <td align=\"center\">$listItem[order_num]</td><td align=\"center\">$listItem[tray_num]</td>";
               $message.="
                <td align=\"center\">$listItem[lab_name]</td>
			    <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
                <td align=\"center\">$listItem[order_product_name]</td>";
              $message.="</tr>";
		}//END WHILE
		mysql_free_result($rptResult);	
		$message.="</table>";
echo $message;

	
		

}	


//rediriger vers la page php qui fera fermer la fenetre
header("Location:close_page.php");
	
?>