<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start  = microtime(true);
$nbrResultat = 0;
$today	     = date("Y-m-d");

if ($_REQUEST['email'] == 'no'){
	$SendEmail = 'no';
}elseif($_REQUEST['email'] == 'admin'){
	$SendEmail = 'no';
	$SendAdmin = 'yes';
}else{
	$SendEmail = 'yes';
}
if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}

$rptQuery    = "SELECT  orders.patient_ref_num,orders.prescript_lab, orders.order_num, accounts.main_lab, orders.order_product_name, accounts.company, orders.order_date_processed, orders.order_item_date,  orders.user_id, orders.po_num, orders.order_total, orders.re_add, orders.le_add, orders.le_sphere, orders.re_sphere, orders.order_date_processed, orders.order_date_shipped, orders.order_patient_first, orders.order_patient_last, orders.tray_num,orders.order_status 
FROM orders, accounts
WHERE  accounts.user_id = orders.user_id  and orders.order_status IN ('waiting for frame,'waiting for frame swiss','waiting for frame KNR')  order by order_date_processed";
	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';
	
$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);
$count	   = 0;
$message   = "";		
$message   = "
<html>
<head>
	<meta charset=\"utf-8\">
	<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
	<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
	<!-- Bootstrap core CSS -->
	<link href=\"http://www.direct-lens.com/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
	<!-- Custom styles for this template -->
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
	<script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
	<![endif]-->
</head>";

$message.="<body><table class=\"table\">";
$message.="
<thead>
	<th><b>Order num</b></th>
	<th>Patient Ref</b></th>
	<th>Order date</b></th>
	<th><b>Lab that produces</b></th>
	<th><b>Tray Num</b></th>
	<th><b>Depuis</b></th>
	<th><b>Main Lab</b></th>
	<th><b>Product</b></th>
</thead>";
				
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	$queryStatusUpdate="SELECT  max(status_history_id) as max_id  from status_history WHERE order_num = ".  $listItem[order_num]	;
	$resultStatusUpdate=mysqli_query($con,$queryStatusUpdate)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
	$DataStatusHistory=mysqli_fetch_array($resultStatusUpdate,MYSQLI_ASSOC);		
		
	if ($DataStatusHistory['max_id'] <>"")
	{
		$queryStatus  = "SELECT update_time from status_history WHERE status_history_id =  " . $DataStatusHistory['max_id'];
		$resultStatus = mysqli_query($con,$queryStatus)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
		$DataStatus   = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
	}
		
	$today	      = date("Y-m-d");
	$tomorrow     = mktime(0,0,0,date("m"),date("d")+4,date("Y"));
	$datecomplete = date("Y/m/d", $tomorrow);
		
	$queryDateDiff  = "SELECT DATEDIFF('$today','$DataStatus[update_time]') as ladate"; 
	$resultDateDiff = mysqli_query($con,$queryDateDiff)		or die  ('I cannot select items because: <br><br>' . mysqli_error($con));
	$DataDateDiff	= mysqli_fetch_array($resultDateDiff,MYSQLI_ASSOC);				
	$DataDateDiff[ladate] = abs( $DataDateDiff[ladate]);
	$nbrResultat    = $nbrResultat +1;
			
				
	$count++;
	if (($count%2)==0)
		$bgcolor="#E5E5E5";
	else 
		$bgcolor="#FFFFFF";
	
	$queryLab  = "SELECT lab_name from labs WHERE primary_key = " . $listItem["main_lab"];
	$ResultLab = mysqli_query($con,$queryLab)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataLab   = mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);	
	$queryPrescriptLab  = "SELECT lab_name from labs WHERE primary_key = " . $listItem["prescript_lab"];
	$resultPrescriptLab = mysqli_query($con,$queryPrescriptLab)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataPrescriptLab   = mysqli_fetch_array($resultPrescriptLab,MYSQLI_ASSOC);
			
	$message.="
	<tr>
		<td >$listItem[order_num]</td>
		<td align=\"center\">$listItem[order_patient_first]  $listItem[order_patient_last]</td>
		<td align=\"center\">$listItem[order_date_processed]</td>
		<td align=\"center\">$DataPrescriptLab[lab_name]</td>
		<td align=\"center\">$listItem[tray_num]</td>
		<td align=\"center\">$DataStatus[update_time]</td>
		<td align=\"center\">$DataLab[lab_name]</td>
		<td align=\"center\">$listItem[order_product_name]</td>
	</tr>";
}//END WHILE

			
$message	 .="<tr><td colspan=\"9\"><b>Number of Orders to check because they are waiting for lens: $nbrResultat</b></td></tr></table>";
$to_address = array('rapports@direct-lens.com','monture@entrepotdelalunette.com');
//$to_address = array('rapports@direct-lens.com');
$curTime	  = date("m-d-Y");	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject	  = 'Liste des commandes waiting for Frame:'. $curTime;
if ($SendEmail == 'yes'){
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

if($SendAdmin == 'yes'){
	$to_address = array('rapports@direct-lens.com');
	$response   = office365_mail($to_address, $from_address, $subject, null, $message);	
}
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
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
    }else{
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

echo $message;

$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport commandes en attente de monture 2.0', '$time','$today','$timeplus3heures','rapport_commandes_waiting_for_frame.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con)); 

?>