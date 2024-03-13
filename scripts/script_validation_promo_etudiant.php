<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$date2      = date("Y-m-d");
$delais     = 6;
$tomorrow   = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1      = date("Y-m-d", $tomorrow);

/*
$date1 = "2018-02-01";
$date2 = "2018-03-14";
*/


$rptQuery="SELECT * FROM orders
	WHERE orders.order_product_name like '%promo etudiant%'
	AND user_id<>'garantieatoutcasser'
	AND order_date_processed BETWEEN '$date1' AND '$date2'
	AND orders.order_status NOT IN ('cancelled','on hold')
	ORDER BY user_id";
	echo '<br>'. $rptQuery.'<br>';
	$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$ordersnum=mysqli_num_rows($rptResult);


if ($ordersnum > 0)
{	
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
                <td align=\"center\">Order Number</td>
                <td align=\"center\">Entrepot</td>
                <td align=\"center\">Order Date</td>
				<td align=\"center\">Product</td>
                <td align=\"center\">Order Status</td>
				</tr>";
	
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";


		$message.="
			<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[order_num]</td>
                <td align=\"center\">$listItem[company]</td>
				<td align=\"center\">$listItem[order_date_processed]</td>
				<td align=\"center\">$listItem[order_product_name]</td>
                <td align=\"center\">$listItem[order_status]</td>
			</tr>";
				
		}//END WHILE
	
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";



echo $message;

//SEND EMAIL
$send_to_address = array('rapports@direct-lens.com');
echo "<br>".$send_to_address;
$curTime= date("m-d-Y");	
$to_address=$send_to_address;
$from_address='donotreply@entrepotdelalunette.com';
$subject="EDLL: Promo Etudiant en dehors de garantieatoutcasser $date1-$date2";
$response=office365_mail($to_address, $from_address, $subject, null, $message);

//Log email
$compteur = 0;
	
	if($response){ 
		echo 'Reussi';
    }else{
		echo 'Echec';
	}
	
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	echo "Execution time:  $time seconds\n";
	$today = date("Y-m-d");// current date
	$timeplus3heures = date("H:i:s");
	$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
				VALUES('Script validation promo etudiant 2.0', '$time','$today','$timeplus3heures','script_validation_promo_etudiant.php')"; 					
	$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));


}//End If There are orders

?>