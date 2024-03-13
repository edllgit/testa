<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../../../sec_connect.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
$time_start = microtime(true);     
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

//Rapport sera exécuté chaque dimanche soir et ira chercher les reprises des derniers 7 jours: si exécuté le 9 avril, ca prendra du 3 au 9 avril
$delais    = 6;
$tomorrow  = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$date1     = date("Y-m-d", $tomorrow);
$date2     = date("Y-m-d");
echo '<br>Du: '. $date1 .'&nbsp;&nbsp;Au '. $date2.'<br><br>';
$Couleur1  = "#ECE76C";//Jaune
$Couleur2  = "#8AE370";//Vert
$Couleur3  = "#CBF0EB";//Bleu

//Dates hard codés
//$date1 = '2018-10-25';
//$date2 = '2019-02-18';

?>
<html>
<head>
<td bgcolor="#CBF0EB">
	<meta charset="utf-8\">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport\" content=\"width=device-width, initial-scale=1">
	<!-- Bootstrap core CSS -->
    <link href="http://www.direct-lens.com/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php
$rptQuery  = "SELECT orders.user_id, company, order_num, redo_order_num, order_date_processed, lab_name, prescript_lab, order_date_processed, redo_reason_en, order_status 
FROM accounts, orders, redo_reasons, labs
WHERE orders.user_id = accounts.user_id 
AND labs.primary_key = orders.prescript_lab
AND orders.redo_reason_id = redo_reasons.redo_reason_id
AND order_date_processed BETWEEN '$date1' AND '$date2'
AND order_status NOT IN ('cancelled','on hold')
AND orders.user_id <> '88666'
AND orders.user_id NOT IN ('redoifc','St.Catharines')
GROUP BY order_num
ORDER BY orders.user_id, orders.redo_reason_id";

echo '<br>Query: <br>'. $rptQuery . '<br>';
	
$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

	
if ($ordersnum!=0){
	$count    = 0;
	$message  = "";
	$message  = "<html>";
	$message .= "<head>
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
	
	$message.="<body>
				<table class=\"table\">
					<thead>
						<td  align=\"center\"><b>Store</b></td>
						
						<td  align=\"center\"><b>Redo Order #</b></td>
						<td  align=\"center\"><b>Redo Date</b></td>
						<td  align=\"center\"><b>Manufacturer Redo</b></td>
						
						<td  align=\"center\"><b>Original Order #</b></td>
						<td  align=\"center\"><b>Date original</b></td>
						<td  align=\"center\"><b>Manufacturer Original</b></td>
						
						<td  align=\"center\"><b>Redo Reason</b></td>
						<td  align=\"center\"><b>Number of orders</b></td>
					</thead>";
	
	$bgcolor = "#FFFFFF";
	$CompagnieActuelle = '';				
	//echo '<br>Avant premier passage, compagnieactuelle:'. $CompagnieActuelle;

	$TotalRepriseSuccActuelle = 0;
	$GrandTotalReprises 	  = 0;

	while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){



	if ($CompagnieActuelle ==''){
	$bgcolor = $Couleur1;
	$CompagnieActuelle =$listItem[company];
	//echo '<br>Compagnieactuelle:'. $CompagnieActuelle;	
	}
	elseif($CompagnieActuelle <> $listItem[company]){
		$CompagnieActuelle = $listItem[company];
		
		
		switch($bgcolor){
			case '#ECE76C': $bgcolor = '#8AE370';  break; 	
			case '#8AE370': $bgcolor = '#CBF0EB';  break; 	
			case '#CBF0EB': $bgcolor = '#ECE76C';  break; 		
		}
		
	
		//echo '<br>Compagnieactuelle:'. $CompagnieActuelle;	
	//Insérer le saut de ligne
		
		$message.="
		
		
			<tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\">Total redos:</td>
					   <td align=\"center\">$TotalRepriseSuccActuelle</td>
				  </tr>
		
		
		<tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
				  </tr><tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
				  </tr>";
				  $TotalRepriseSuccActuelle = 0;
				  $message.="<td  align=\"center\"><b>Store</b></td>
						
						<td  align=\"center\"><b>Redo Order #</b></td>
						<td  align=\"center\"><b>Redo Date</b></td>
						<td  align=\"center\"><b>Manufacturer Redo</b></td>
						
						<td  align=\"center\"><b>Original Order #</b></td>
						<td  align=\"center\"><b>Date original</b></td>
						<td  align=\"center\"><b>Manufacturer Original</b></td>
						
						<td  align=\"center\"><b>Redo Reason</b></td>
						<td  align=\"center\"><b>Number of orders</b></td>";
	}
		
	$queryOriginal 	    = "SELECT order_date_processed, prescript_lab FROM orders WHERE order_num = $listItem[redo_order_num]";
	$resultOriginal 	= mysqli_query($con,$queryOriginal)		or die  ('I cannot select items because: ' . mysqli_error($con));
	$listItemOriginal   = mysqli_fetch_array($resultOriginal,MYSQLI_ASSOC);
		
	$count++;
		

		
		switch($listItem["order_status"]){
			case 'processing':				$list_order_status = "Confirmed";				break;
			case 'order imported':			$list_order_status = "Order Imported";			break;
			case 'job started':				$list_order_status = "Surfacing";				break;
			case 'in coating':				$list_order_status = "In Coating";				break;
			case 'profilo':					$list_order_status = "Profilo";					break;
			case 'in mounting':				$list_order_status = "In Mounting";				break;
			case 'in edging':				$list_order_status = "In Edging";				break;
			case 'in edging hko':			$list_order_status = "In Edging";				break;
			case 'order completed':			$list_order_status = "Order Completed";			break;
			case 'delay issue 0':			$list_order_status = "Delay Issue 0";			break;
			case 'delay issue 1':			$list_order_status = "Delay Issue 1";			break;
			case 'delay issue 2':			$list_order_status = "Delay Issue 2";			break;
			case 'delay issue 3':			$list_order_status = "Delay Issue 3";			break;
			case 'delay issue 4':			$list_order_status = "Delay Issue 4";			break;
			case 'delay issue 5':			$list_order_status = "Delay Issue 5";			break;
			case 'delay issue 6':			$list_order_status = "Delay Issue 6";			break;
			case 'waiting for frame':		$list_order_status = "Waiting for Frame";		break;
			case 'waiting for frame swiss':		$list_order_status = "Waiting for Frame Swiss";		break;
			case 'waiting for shape':		$list_order_status = "Waiting for Shape";		break;
			case 'waiting for lens':		$list_order_status = "Waiting for Lens";		break;
			case 're-do':					$list_order_status = "Redo";					break;
			case 'in transit':				$list_order_status = "In Transit";				break;
			case 'filled':					$list_order_status = "Shipped";					break;
			case 'cancelled':				$list_order_status = "Cancelled";				break;
			case 'verifying':				$list_order_status = "Verifying";				break;
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss";  break;
			case "on hold":					$list_order_status= "On Hold";			        break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default:                        $list_order_status = "UNKNOWN";             	break;
		}
		
		switch($listItemOriginal[prescript_lab]){
			case 21:    $LabOriginal = 'Trois-Rivieres';  			 break;
			case 25:    $LabOriginal = 'Direct-Lens Exclusive #2';   break;
			case 10:    $LabOriginal = 'Direct-Lens Exclusive #1';   break;
			case 69:    $LabOriginal = 'Essilor #1 Lab';  			 break;
			case  3:    $LabOriginal = 'Directlab St.Catharines';    break;
			default:  	$LabOriginal = 'ERREUR';
		}
		
		switch($listItem[user_id]){
			case '88403' :      $User_ID_IN = "('88403')";      $Succursale = '#88403-Bloor';   		break;
			case '88408' :      $User_ID_IN = "('88408')";    	$Succursale = '#88408-Oshawa';   		break;
			case '88409' :      $User_ID_IN = "('88409')";    	$Succursale = '#88409-Eglinton';   		break;
			case '88411' :      $User_ID_IN = "('88411')";    	$Succursale = '#88411-Sherway';   		break;
			case '88414' :      $User_ID_IN = "('88414')";    	$Succursale = '#88414-Yorkdale';   		break;
			case '88416' :      $User_ID_IN = "('88416')";    	$Succursale = '#88416-Vancouver DTN';	break;
			case '88431' :      $User_ID_IN = "('88431')";      $Succursale = '#88431-Calgary DTN';   	break;
			case '88433' :      $User_ID_IN = "('88433')";      $Succursale = '#88433-Polo Park';   	break;
			case '88434' :      $User_ID_IN = "('88434')";      $Succursale = '#88434-Market Mall';   	break;
			case '88435' :      $User_ID_IN = "('88435')";      $Succursale = '#88435-West Edmonton';   break;
			case '88438' :      $User_ID_IN = "('88438')";      $Succursale = '#88438-Metrotown';   	break;
			case '88439' :      $User_ID_IN = "('88439')";      $Succursale = '#88439-Langley';   		break;
			case '88440' :      $User_ID_IN = "('88440')";      $Succursale = '#88440-Rideau';   		break;			
			case '88444' :      $User_ID_IN = "('88444')";      $Succursale = '#88444-Mayfair';   		break;
			case '88666' :      $User_ID_IN = "('88666')";      $Succursale = '#88666-Griffe';   		break;
			default:  	$Succursale = 'Internal Redo Acct';
		}
		

		
		 if ($User_ID_IN <> ""){
			 $rptOriginal  = "SELECT count(order_num) as NbrOriginales FROM orders WHERE redo_order_num IS NULL AND user_id IN $User_ID_IN
			AND order_date_processed BETWEEN '$date1' AND '$date2' ";
			//echo '<br>'. $rptOriginal;
			$ResultCommandesOriginales 	= mysqli_query($con,$rptOriginal)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataOriginales   = mysqli_fetch_array($ResultCommandesOriginales,MYSQLI_ASSOC);
			if ($DataOriginales[NbrOriginales]==0)
			    $DataOriginales[NbrOriginales]='-';
		}
		
		$message.="<tr bgcolor=\"$bgcolor\">
					  <td align=\"center\">$Succursale</td>
					  
					  <td align=\"center\">$listItem[order_num]</td>
					  <td align=\"center\">$listItem[order_date_processed]</td>
					  <td align=\"center\">$listItem[lab_name]</td>
					  
					  <td align=\"center\">$listItem[redo_order_num]</td>
					  <td align=\"center\">$listItemOriginal[order_date_processed]</td>
					  <td align=\"center\"> $LabOriginal</td>
					 
					  <td align=\"center\">$listItem[redo_reason_en]</td>
					  <td align=\"center\">$DataOriginales[NbrOriginales]</td>";
		

				  $message         .=" </tr>";
				  
	$TotalRepriseSuccActuelle = $TotalRepriseSuccActuelle+1;
	$GrandTotalReprises       = $GrandTotalReprises+1;
		
	}//END WHILE
	
	$message.="
		
		
			<tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\">Total redos:</td>
					   <td align=\"center\">$TotalRepriseSuccActuelle</td>
				  </tr>";
				  
				  
			$message.="
			<tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
					   <td align=\"center\">&nbsp;</td>
				  </tr>
			<tr bgcolor=\"#FFFFFF\">
					   <td align=\"center\"><b>All Stores Total redos</b></td>
					   <td align=\"center\">$GrandTotalReprises</td>
				  </tr>";
				  
				  
				
	
	
	
	$message     .="</table>";

	$to_address = array('rapports@direct-lens.com');
	
	//$to_address = array('rapports@direct-lens.com');
	$curTime	  = date("m-d-Y");	
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject	  = "Weekly Redos Report HBC: $date1- $date2";
	
	//SEND EMAIL
	if ($SendEmail == 'yes'){
		$response=office365_mail($to_address, $from_address, $subject, null, $message);
	}
	
	if($SendAdmin == 'yes'){
		$to_address = array('rapports@direct-lens.com');
		$response=office365_mail($to_address, $from_address, $subject, null, $message);	
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

}//End if query gives results

echo $message;
?>
   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
*/