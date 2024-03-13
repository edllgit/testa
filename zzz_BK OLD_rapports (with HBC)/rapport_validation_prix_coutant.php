<?php
/*
//GKB: redos seulement
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$lab_pkey   = 69;//St catherines ID
$today	    = date("Y-m-d");


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

//Parametre recu par REQUEST: plateforme  Option edll/rdl
$Plateforme = 'edll'; 



	$count=0;
	$message="";	
	$message="
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
	
	$message.="
	<body>
	<table class=\"table\">
	<thead>
		<th>Order Number</th>
		<th>Lab that produce</th>
		<th>Order Date</th>
		<th>Product</th>
		<th>Code</th>
		<th>Cost US</th>
	</thead>";
		


$rptQuerySwiss  = "SELECT user_id, order_num, lab, prescript_lab, order_date_processed, order_product_name, order_status, order_patient_first, order_patient_last, tray_num, order_from, order_product_id
FROM `orders`
WHERE order_status = 'in transit'
AND lab <>37 AND prescript_lab=10
ORDER BY PRESCRIPT_LAB, order_date_processed";	
	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuerySwiss . '<br>';

$rptResultSwiss=mysqli_query($con, $rptQuerySwiss)		or die  ('I cannot select items because: ' . mysqli_error($con));

	
					
	while ($listItem=mysqli_fetch_array($rptResultSwiss,MYSQLI_ASSOC)){
				
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";

	
		switch($listItem[prescript_lab]){
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 25: $LabProduce = 'HKO'; 	           break;	
			case 69: $LabProduce = 'GKB';              break;	
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 3:  $LabProduce = 'Saint-Catharines'; break;	
			case 21:  $LabProduce = 'Trois-Rivieres';  break;
			case 54:  $LabProduce = 'Vision-Ease';     break;
			case 58:  $LabProduce = 'US OPTICAL';      break;
			case 68:  $LabProduce = 'QUEST';      	   break;
			case 60:  $LabProduce = 'CSC';      	   break;
			case 70:  $LabProduce = 'Plastic Plus b(Saint-Catharines P)';    break;
			case 71:  $LabProduce = 'Waiting for redirection';    break;
			case 72:  $LabProduce = 'Optique Quebec';  break;
		}



	switch($listItem[order_from]){
		case 'ifcclubca':     $TableToUse="ifc_ca_exclusive";   break;	
		case 'directlens':    $TableToUse="exclusive";  		break;	
		case 'lensnetclub':   $TableToUse="exclusive";  		break;	
		case 'eye-recommend': $TableToUse="exclusive"; 			break;	
		case 'safety': 		  $TableToUse="safety_exclusive";  	break;	
	}
	
	$queryCost  = "SELECT cost,cost_us, product_code FROM  $TableToUse WHERE primary_key =  $listItem[order_product_id]";
	$resultCost   = mysqli_query($con,$queryCost)			or die ( "Query failed: " . mysqli_error($con) );
	$DataCost     = mysqli_fetch_array($resultCost,MYSQLI_ASSOC);
	$cost_cad     = $DataCost[cost];
	$cost_us      = $DataCost[cost_us];
	$Product_Code = $DataCost[product_code];	
				
	
		$message.="
		<tr>
			<td>$listItem[order_num]</td>
			<td>$LabProduce</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_product_name]</td>
			<td>$Product_Code</td>
			<td>$cost_us</td></tr>";
		
	}//Fin Partie Swiss END WHILE


	$message.="<tr><td colspan=\"6\"></td></tr>  
		       <tr><td colspan=\"6\"></td></tr>
		       <tr><td colspan=\"6\"></td></tr>
			   <tr><td colspan=\"6\"></td></tr>  
		       <tr><td colspan=\"6\"></td></tr>
		       <tr><td colspan=\"6\"></td></tr>";




//Partie HKO
$rptQueryHKO  = "SELECT user_id, order_num, lab, prescript_lab, order_date_processed, order_product_name, order_status, order_patient_first, order_patient_last, tray_num, order_from, order_product_id
FROM `orders`
WHERE order_status = 'in transit'
AND lab <>37 AND prescript_lab=25
ORDER BY PRESCRIPT_LAB, order_date_processed";	
	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQueryHKO . '<br>';

$rptResultHKO=mysqli_query($con,$rptQueryHKO)		or die  ('I cannot select items because: ' . mysqli_error($con));

	
					
	while ($listItem=mysqli_fetch_array($rptResultHKO,MYSQLI_ASSOC)){
				
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";

	
		switch($listItem[prescript_lab]){
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 25: $LabProduce = 'HKO'; 	           break;	
			case 69: $LabProduce = 'GKB';              break;	
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 3:  $LabProduce = 'Saint-Catharines'; break;	
			case 21:  $LabProduce = 'Trois-Rivieres';  break;
			case 54:  $LabProduce = 'Vision-Ease';     break;
			case 58:  $LabProduce = 'US OPTICAL';      break;
			case 68:  $LabProduce = 'QUEST';      	   break;
			case 60:  $LabProduce = 'CSC';      	   break;
			case 70:  $LabProduce = 'Plastic Plus b(Saint-Catharines P)';    break;
			case 71:  $LabProduce = 'Waiting for redirection';    break;
			case 72:  $LabProduce = 'Optique Quebec';  break;
		}



	switch($listItem[order_from]){
		case 'ifcclubca':     $TableToUse="ifc_ca_exclusive";   break;	
		case 'directlens':    $TableToUse="exclusive";  		break;	
		case 'lensnetclub':   $TableToUse="exclusive";  		break;	
		case 'eye-recommend': $TableToUse="exclusive"; 			break;	
		case 'safety': 		  $TableToUse="safety_exclusive";  	break;	
	}
	
	$queryCost  = "SELECT cost,cost_us, product_code FROM  $TableToUse WHERE primary_key =  $listItem[order_product_id]";
	//echo '<br>'.$queryCost;
	$resultCost   = mysqli_query($con,$queryCost)			or die ( "Query failed: " . mysql_error() );
	$DataCost     = mysqli_fetch_array($resultCost,MYSQLI_ASSOC);
	$cost_cad     = $DataCost[cost];
	$cost_us      = $DataCost[cost_us];
	$Product_Code = $DataCost[product_code];	
				
		//$new_result = mysqli_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
		//$order_date = mysql_result($new_result,0,0);		
		$message.="
		<tr>
			<td>$listItem[order_num]</td>
			<td>$LabProduce</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_product_name]</td>
			<td>$Product_Code</td>
			<td>$cost_us</td>";
		$message.="</tr>";
	}//Fin Partie HKO END WHILE


	$message.="<tr><td colspan=\"6\"></td></tr>  
		       <tr><td colspan=\"6\"></td></tr>
		       <tr><td colspan=\"6\"></td></tr>
			   <tr><td colspan=\"6\"></td></tr>  
		       <tr><td colspan=\"6\"></td></tr>
		       <tr><td colspan=\"6\"></td></tr>";



//Partie GKB
$rptQueryGKB  = "SELECT user_id, order_num, lab, prescript_lab, order_date_processed, order_product_name, order_status, order_patient_first, order_patient_last, tray_num, order_from, order_product_id
FROM `orders`
WHERE order_status = 'in transit'
AND lab <>37 AND prescript_lab=69
ORDER BY PRESCRIPT_LAB, order_date_processed";	
	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQueryGKB . '<br>';

$rptResultGKB=mysqli_query($con,$rptQueryGKB)		or die  ('I cannot select items because: ' . mysqli_error($con));

	
					
	while ($listItem=mysqli_fetch_array($rptResultGKB,MYSQLI_ASSOC)){
				
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";

	
		switch($listItem[prescript_lab]){
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 25: $LabProduce = 'HKO'; 	           break;	
			case 69: $LabProduce = 'GKB';              break;	
			case 10: $LabProduce = 'Swisscoat';        break;	
			case 3:  $LabProduce = 'Saint-Catharines'; break;	
			case 21:  $LabProduce = 'Trois-Rivieres';  break;
			case 54:  $LabProduce = 'Vision-Ease';     break;
			case 58:  $LabProduce = 'US OPTICAL';      break;
			case 68:  $LabProduce = 'QUEST';      	   break;
			case 60:  $LabProduce = 'CSC';      	   break;
			case 70:  $LabProduce = 'Plastic Plus b(Saint-Catharines P)';    break;
			case 71:  $LabProduce = 'Waiting for redirection';    break;
			case 72:  $LabProduce = 'Optique Quebec';  break;
		}



	switch($listItem[order_from]){
		case 'ifcclubca':     $TableToUse="ifc_ca_exclusive";   break;	
		case 'directlens':    $TableToUse="exclusive";  		break;	
		case 'lensnetclub':   $TableToUse="exclusive";  		break;	
		case 'eye-recommend': $TableToUse="exclusive"; 			break;	
		case 'safety': 		  $TableToUse="safety_exclusive";  	break;	
	}
	
	$queryCost  = "SELECT cost,cost_us, product_code FROM  $TableToUse WHERE primary_key =  $listItem[order_product_id]";
	//echo '<br>'.$queryCost;
	$resultCost   = mysqli_query($con,$queryCost)			or die ( "Query failed: " . mysqli_error($con) );
	$DataCost     = mysqli_fetch_array($resultCost,MYSQLI_ASSOC);
	$cost_cad     = $DataCost[cost];
	$cost_us      = $DataCost[cost_us];
	$Product_Code = $DataCost[product_code];	
				
		//$new_result = mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
		//$order_date = mysql_result($new_result,0,0);		
		$message.="
		<tr>
			<td>$listItem[order_num]</td>
			<td>$LabProduce</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_product_name]</td>
			<td>$Product_Code</td>
			<td>$cost_us</td>";
		$message.="</tr>";
	}//Fin Partie HKO END WHILE


	
	$today    	     = date("Y-m-d");// current date
	$message.="</table>";	
	$to_address = array('rapports@direct-lens.com');
	//$to_address = array('rapports@direct-lens.com');
	//$to_address = array('rapports@direct-lens.com');
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Cost validation report: $today";
	
	echo $message;
	
	//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response = office365_mail($to_address, $from_address, $subject, null, $message);
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
			//log_email("REPORT: Redos of the day GKB",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
			echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
		}else{
			//log_email("REPORT: Redos of the day GKB",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
			echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
		}	

$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today    	     = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
VALUES('Email redirection Redo report GKB 2.0', '$time','$today','$timeplus3heures','cron_send_redirection_redo_report_gkb.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con) );
echo $message;
*/

/*
function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because: ' . mysqli_error($con));	
}*/


?>