<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);	
$today      = date("Y-m-d");
//Ce rapport serait généré la 1ère journée de chaque moi et concerne les crédit émis durant le mois précédent. 
//Exemple: si exécuté le 1er février: le range de date Sera: '2020-01-01' AND '2020-01-31' 

$MoisenCours=date('F');
echo 'Mois en cours:'. $MoisenCours;

//On soustrait 1 mois pour trouver le mois précédent
switch ($MoisenCours){
	case 'January':		$MoisConcerner="December";	break;
	case 'February':	$MoisConcerner="January";	break;
	case 'March':		$MoisConcerner="February";	break;
	case 'April':		$MoisConcerner="March";		break;
	case 'May':			$MoisConcerner="April";		break;
	case 'June':		$MoisConcerner="May";		break;
	case 'July':		$MoisConcerner="June";		break;
	case 'August':		$MoisConcerner="July";		break;
	case 'September':	$MoisConcerner="August";	break;
	case 'October':		$MoisConcerner="September";	break;
	case 'November':	$MoisConcerner="October";	break;
	case 'December':	$MoisConcerner="November";	break;
}//End Switch

$GrandTotalCrediter = 0;//Initialiser le compteur
//Aller chercher l'année en cours pour déterminer l'Année à utiliser dans le rapport
$AnneeEnCours = date("Y");
/*
if ($AnneeEnCours==2020){
	$year = 2020;
}else if (($AnneeEnCours==2021)&& ($MoisConcerner=="January")){
	$year = 2020;	
}else if(($AnneeEnCours==2021)&&($MoisConcerner<>"January")){
	$year = 2021;
}else if(($AnneeEnCours==2022)&&($MoisConcerner<>"January")){
	$year = 2022;
}else if(($AnneeEnCours==2023)&&($MoisConcerner<>"January")){
	$year = 2023;
}else if(($AnneeEnCours==2024)&&($MoisConcerner<>"January")){
	$year = 2024;
}*/
if ($MoisConcerner=="December"){
	$year = $AnneeEnCours-1;
	
}



switch($MoisConcerner){
		case 'January':    	$date1 = $year. "-01-01"; $date2 = $year . "-01-31";    break;
		case 'February':    $date1 = $year. "-02-01"; $date2 = $year . "-02-29";    break;
		case 'March':       $date1 = $year. "-03-01"; $date2 = $year . "-03-31";    break;
		case 'April':     	$date1 = $year. "-04-01"; $date2 = $year . "-04-30";    break;
		case 'May':       	$date1 = $year. "-05-01"; $date2 = $year . "-05-31";    break;
		case 'June':      	$date1 = $year. "-06-01"; $date2 = $year . "-06-30";    break;
		case 'July':   		$date1 = $year. "-07-01"; $date2 = $year . "-07-31";    break;
		case 'August':      $date1 = $year. "-08-01"; $date2 = $year . "-08-31";    break;
		case 'September': 	$date1 = $year. "-09-01"; $date2 = $year . "-09-30";    break;
		case 'October':   	$date1 = $year. "-10-01"; $date2 = $year . "-10-31";    break;
		case 'November':  	$date1 = $year. "-11-01"; $date2 = $year . "-11-30";    break;
		case 'December':  	$date1 = $year. "-12-01"; $date2 = $year . "-12-31";    break;	
		default: exit();
}//End Switch


/*
*mcred_acct_user_id EX: 88440       		Libellé: Store
*mcred_order_num 	EX: 17749       		Libellé: Order #
mcred_memo_num 	    EX: M17749A     		Libellé: Credit #
mcred_abs_amount    EX: 3.51        		Libellé: Amount
mcred_date 	        EX: 2019-11-19  		Libellé: Date 
mcred_detail        EX: 'Abandon 50%. Osa'  Libellé: Detail
*/


//A RECOMMENTER
//$date1 = '2019-01-01';
//$date2 = '2019-12-31';

$rptQuery   = "SELECT * FROM memo_credits WHERE mcred_date BETWEEN '$date1' AND '$date2'  ORDER BY mcred_acct_user_id";
echo $rptQuery;	
$result    = mysqli_query($con,$rptQuery)	or die  ('I cannot select items because 1a: <br><br>'.$rptQuery .'<br>' . mysqli_error($con));
$ordersnum = mysqli_num_rows($result);
	
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
	</style></head>
	<body>
		<table width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
			<tr bgcolor=\"CCCCCC\">
				<td align=\"center\">Store</td>
				<td align=\"center\">Order #</td>
				<td align=\"center\">Credit #</td>
				<td align=\"center\">Amount</td>
				<td align=\"center\">Date</td>
				<td align=\"center\">Reason</td>
				<td align=\"center\">Detail</td>
			</tr>";
		$MagasinEnCours  ="";	
		$TotalMagasinEnCours= 0;	
	//Associative Array	
		while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
			
			$queryCreditReason 	= "SELECT mc_description FROM memo_codes WHERE mc_lab= 66 AND memo_code= '$listItem[mcred_memo_code]'";
			//echo '<br><br>'. $queryCreditReason;
			$resultCreditReason	= mysqli_query($con,$queryCreditReason) or die ( "Query $queryCreditReason  failed: " . mysqli_error($con));	
			$DataCreditReason   = mysqli_fetch_array($resultCreditReason,MYSQLI_ASSOC);

			
			if ($MagasinEnCours==''){
				//On initialise cette variable avec le premier magasin traité
				$MagasinEnCours = $listItem[mcred_acct_user_id];
				$TotalMagasinEnCours = $TotalMagasinEnCours+$listItem[mcred_abs_amount];
				//echo '<br><br>Magasin en cours:'. $MagasinEnCours . '    TotalMagasinEnCours:'. $TotalMagasinEnCours .'$';
			}elseif($MagasinEnCours<>$listItem[mcred_acct_user_id]){
				//Afficher le total pour le magasin précédent, 
				$message.='<tr><th colspan="3">TOTAL FOR STORE '.$MagasinEnCours.'</th><th>'.$TotalMagasinEnCours.'$</th></tr>';
				$GrandTotalCrediter = $GrandTotalCrediter+$TotalMagasinEnCours;
				$message.='<tr><td>&#8239;</td></tr>';
				//echo 'Total pour le magasin' .$MagasinEnCours.':' .$TotalMagasinEnCours;
				//Sauvegarder le nouveau magasin Comme étant  celui en cours
				$MagasinEnCours = $listItem[mcred_acct_user_id];
				//Ré-initialiser le compteur
				$TotalMagasinEnCours =0; 
				//Cummuler la 1ere commande du nouveau magasin dans le total
				$TotalMagasinEnCours = $TotalMagasinEnCours+$listItem[mcred_abs_amount];	
			}else{
				$TotalMagasinEnCours = $TotalMagasinEnCours+$listItem[mcred_abs_amount];	
				//echo '<br><br>Magasin en cours:'. $MagasinEnCours . '    TotalMagasinEnCours:'. $TotalMagasinEnCours . '$';		
			}//END IF
			
		

		$count++;
		 if (($count%2)==0)
			$bgcolor="#E5E5E5";
		 else 
			$bgcolor="#FFFFFF";


			$message.="
			<tr bgcolor=\"$bgcolor\">
				<td align=\"center\">$listItem[mcred_acct_user_id]</td>
				<td align=\"center\">$listItem[mcred_order_num]</td>
				<td align=\"center\">$listItem[mcred_memo_num]</td>
				<td align=\"center\">$listItem[mcred_abs_amount]$</td>
				<td align=\"center\">$listItem[mcred_date]</td>
				<td align=\"center\">$DataCreditReason[mc_description]</td>
				<td align=\"center\">$listItem[mcred_detail]</td>
			</tr>";
		}//END WHILE	
			//Display the result of the last store:
			$message.='<tr><th colspan="3">TOTAL FOR STORE '.$MagasinEnCours.'</th><th>'.$TotalMagasinEnCours.'$</th></tr>';
			$GrandTotalCrediter = $GrandTotalCrediter+$TotalMagasinEnCours;
		
		$message.="<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr></table>";
		
}else{
	$message.="<div class=\"TextSize\">No Orders</div>";}//END ORDERSNUM CONDITIONAL
	
	$message.="<p>Total for all stores: $GrandTotalCrediter$</p>";

//SEND EMAIL
echo $message;
$send_to_address = array('rapports@direct-lens.com');
//$send_to_address = array('rapports@direct-lens.com');	
echo "<br>".$send_to_address;
$curTime      =  date("m-d-Y");	
$to_address   =  $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "EDLL Monthly Credit Report:$MoisConcerner  $year  ";
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
		echo 'Reussi';
    }else{
		echo 'Echec';
	}	
		
$time_end 		 = microtime(true);
$time     		 = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page)
					VALUES('Rapport commandes Processing 2.0', '$time','$today','$timeplus3heures','rapport_commandes_confirmed.php')"; 					
$cronResult 	 = mysqli_query($CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>