<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');
$today      = date("Y-m-d");
$time_start = microtime(true);		
$rptQuery   = "SELECT  * FROM gkb_stock_order WHERE 1 ORDER BY product_name";
$rptResult  = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
$ordersnum  = mysqli_num_rows($rptResult);

echo "<head>
		<meta charset=\"utf-8\">
    	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
   		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style>
		<!-- Bootstrap core CSS -->
		<link href=\"../bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
		<!-- Custom styles for this template -->
		<link href=\"css/signin.css\" rel=\"stylesheet\">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
        <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
        <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
        <![endif]-->
		</head>";
	
//1- Construire le courriel
	if ($ordersnum!=0){
		$count=0;
		$message="";
		
		$message="<html>";
		$message.="<head>
		<meta charset=\"utf-8\">
    	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
   		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
		<style type='text/css'>
		<!--

		.TextSize {
			font-size: 8pt;
			font-family: Arial, Helvetica, sans-serif;
		}
		-->
		</style>
		<!-- Bootstrap core CSS -->
		<link href=\"../../bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
		<!-- Custom styles for this template -->
		<link href=\"css/signin.css\" rel=\"stylesheet\">
		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
        <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
        <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
        <![endif]-->
		</head>";

		$message.="<body><table class=\"table\" width=\"665\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">";
		$message.="<tr align=\"center\" bgcolor=\"CCCCCC\">
                <td align=\"center\"><strong>Product</strong></td>
                <td align=\"center\"><strong>Quantity</strong></td>
				<td align=\"center\"><strong>Sphere</strong></td>
				<td align=\"center\"><strong>Cylinder</strong></td>
				<td align=\"center\"><strong>Tray Num</strong></td>
				</tr>";
				
		while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
			
		$count++;
		 if (($count%2)==0)
   				$bgcolor="#E5E5E5";
			else 
				$bgcolor="#FFFFFF";
				
				if ($listItem[product_name] <> ''){
				$message.="
				<tr bgcolor=\"$bgcolor\">
					<td align=\"center\">$listItem[product_name]</td>
					<td align=\"center\">$listItem[quantity]</td>
					<td align=\"center\">$listItem[sphere]</td>
					<td align=\"center\">$listItem[cylindre]</td>
					<td align=\"center\">$listItem[tray_num]</td>
				</tr>";
				}
		}//END WHILE
		$message.="<tr><td colspan=\"5\">&nbsp;</td></tr><tr><td align=\"center\" colspan=\"5\"><b>Please note that the quantities are by single lens<b></td></tr></table>";

}

//SEND EMAIL


//LIVE CREDENTIALS
$send_to_address = array('rapports@direct-lens.com');




//echo "<br>".$send_to_address;
$curTime      = date("m-d-Y");	
$to_address   = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Stock order: ".$curTime;

//2- Envoyer le courriel

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
		//log_email("REPORT: GKB Stock order",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		
		
	?>

	<div class="alert alert-success"  role="alert">Email sent sucessfully to dbeaulieu@direct-lens.com ,jmotyka@direct-lens.com,lalit.logistic@gkbopticals.net,vijay.agarwal@gkbopticals.net,sameerb@gkbopticals.net,kgawel@direct-lens.com.&nbsp;Keep the email you will receive as a reference.</div>
    
    <?php /*?> <!--//TEST credentials-->
	<div class="alert alert-success"  role="alert">Email sent sucessfully to dbeaulieu@direct-lens.com ,dbeaulieu@direct-lens.com.&nbsp;Keep the email you will receive as a reference.</div><?php */?>
	<?php	
		
		//3- Vider la table gkb_stock_order
		$rptQueryViderTable = "DELETE  FROM gkb_stock_order WHERE 1";
		$ResultViderTable   = mysqli_query($con,$rptQueryViderTable)		or die  ('I cannot empty table gkb_stock_order because: <br><br>' . mysqli_error($con));
		echo '<div align="center" class="alert alert-info" role="alert"><h3><a href="gkb_stock_order.php">Click here to Prepare another stock order</a></h3></div>';
	}//END IF
echo '<br><br>';
echo '<div align="center" class="alert alert-warning" role="alert"><h3>Summary of the order you just transferred to GKB</h3></div>';
echo $message;
		
$time_end = microtime(true);
$time = $time_end - $time_start;
//echo "Execution time:  $time seconds\n";
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Send jobs in Transit', '$time','$today','$timeplus3heures','cron_sent_late_transit.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));		

?>