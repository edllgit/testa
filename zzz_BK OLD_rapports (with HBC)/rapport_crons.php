<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
//ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);
$hier       = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
$hier       = date("Y/m/d", $hier);		
$rptQuery   = "SELECT * from cron_duration WHERE cron_date ='$hier' and cron_duration > 20";
echo $rptQuery;

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

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error());
$ordersnum = mysqli_num_rows($rptResult);
		
	$count   = 0;
	$message = "";
	$message = "<html>
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
	</head>
	<body>
	<table class=\"table\">
		 <thead>
			<th>Script</th>
			<th>Date</th>
			<th>Heure</th>
			<th>Dur&eacute;e</th>
			<th>Php page</th>
		</thead>";
	
				
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
	
		$message.="<tr>
			<td>$listItem[cron_script_name]</td>
			<td>$listItem[cron_date]</td>
			<td>$listItem[cron_time]</td>";
						
		if ($listItem[cron_duration] < 1){
			$message.= "<td align=\"center\">< 1</td>";
		}elseif (($listItem[cron_duration] > 1) && ($listItem[cron_duration] < 5)){
			$message.= "<td align=\"center\">[ 1 - 5]</td>";
		}else{
			$message.= "<td align=\"center\">$listItem[cron_duration]</td>";
		}
	
		$message.="<td align=\"center\">$listItem[php_page]</td>
		</tr>";
	}//END WHILE
	$message.="</table>";

echo $message;	


//SEND EMAIL
$to_address = array('rapports@direct-lens.com');	
$curTime      = date("m-d-Y");	
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "Crons of the day :". $hier;

if ($SendEmail == 'yes'){
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
}

if($SendAdmin == 'yes'){
	$to_address = array('rapports@direct-lens.com');
	$response=office365_mail($to_address, $from_address, $subject, null, $message);	
}

if($response){ 
		echo 'Envoie du rapport par courriel:reussi';
    }else{
		echo 'Envoie du rapport par courriel:Echec';
	}	
		
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Rapport liste des tâches crons 2.0', '$time','$today','$timeplus3heures','rapport_crons.php')"; 					
$cronResult 	 = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));		

?>