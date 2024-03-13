<?php
//GKB : partie Reseau DirectLab: ne contient PAS de EDLL 
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');

$time_start = microtime(true);
$lab_pkey   = 73;//K and R
$today	    = date("Y-m-d");

//$today      = date("2019-11-11");

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



	$rptQuery  = "SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, labs.lab_name, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped,           	        orders.order_patient_first,orders.order_product_name, orders.order_patient_last, orders.tray_num, accounts.company,     orders.order_status FROM orders
    LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
    LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 
    WHERE prescript_lab='$lab_pkey' 
    AND orders.order_date_processed='$today' 
    AND orders.order_status NOT IN ('cancelled','basket')
    GROUP BY order_num";	


	
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
	

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
		<th>Main Lab</th>
		<th>Order Date</th>
		<th>Product</th>
		<th>Patient</th>
		<th>Tray num</th>
		<th>Order Status</th>
	</thead>";
					
	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
				
		$count++;
		if (($count%2)==0)
			$bgcolor="#E5E5E5";
		else 
			$bgcolor="#FFFFFF";
		
		switch($listItem["order_status"]){
			case 'processing':				$list_order_status = "Confirmed";				break;
			case 'order imported':			$list_order_status = "Order Imported";			break;
			case 'job started':				$list_order_status = "Surfacing";				break;
			case 'in coating':				$list_order_status = "In Coating";				break;
			case 'in mounting':				$list_order_status = "In Mounting";				break;
			case 'in edging':				$list_order_status = "In Edging";				break;
			case 'order completed':			$list_order_status = "Order Completed";			break;
			case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
			case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
			case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
			case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
			case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
			case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
			case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
			case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
			case 'waiting for frame swiss':	$list_order_status = "Waiting for Frame Swiss";	break;
			case 'waiting for frame knr':	$list_order_status = "Waiting for Frame KNR";	break;
			case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
			case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
			case 're-do':					$list_order_status = "Redo";					break;
			case 'in transit':				$list_order_status = "In Transit";				break;
			case 'filled':					$list_order_status = "Shipped";					break;
			case 'interlab':				$list_order_status = "Interlab";				break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
			case "on hold":					$list_order_status = "On Hold";			        break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default:                        $list_order_status = "UNKNOWN";             	break;
		}
		
		
		$message.="
		<tr>
			<td>$listItem[order_num]</td>
			<td>$listItem[lab_name]</td>
			<td>$listItem[order_date_processed]</td>
			<td>$listItem[order_product_name]</td>
			<td>$listItem[order_patient_first] $listItem[order_patient_last]</td>
			<td>$listItem[tray_num]</td>
			<td>$list_order_status</td>";
		$message.="</tr>";
	}//END WHILE
	
	$message.="<tr><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";	
	//$to_address = array('rapports@direct-lens.com');
	//$to_address = array('rapports@direct-lens.com');
	$to_address = array('rapports@direct-lens.com');
	

	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject      = "Orders of the day - KnR: $today ";
	
	
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
			echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
		}else{
			echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
		}	

echo $message;

?>