<?php
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);     

//Ce rapport serait généré la 1ère journée de chaque moi et concerne les crédit émis durant le mois précédent. 
//Exemple: si exécuté le 1er février: le range de date Sera: '2020-01-01' AND '2020-01-31' 

for ($i = 1; $i <= 14; $i++) {
    echo '<br>'. $i;
	
switch($i){
	
	
	case  1: $Userid =  " memo_credits.mcred_acct_user_id IN ('88403')";      
	$Partie = '88403-Bloor';	       
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	
	case  2: $Userid =  " memo_credits.mcred_acct_user_id IN ('88408')";        	  
	$Partie = '88408-Oshawa';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;

	case  3: $Userid =  " memo_credits.mcred_acct_user_id IN ('88409')";        	  
	$Partie = '88409-Eglinton';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;

	
	case  4: $Userid =  " memo_credits.mcred_acct_user_id IN ('88414')";        	  
	$Partie = '88414-Yorkdale';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  5: $Userid =  " memo_credits.mcred_acct_user_id IN ('88416')";        	  
	$Partie = '88416-Vancouver DTN';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  6: $Userid =  " memo_credits.mcred_acct_user_id IN ('88431')";        	  
	$Partie = '88431-Calgary DTN';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	 
	case  7: $Userid =  " memo_credits.mcred_acct_user_id IN ('88433')";        	  
	$Partie = '88433-Polo Park';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  8: $Userid =  " memo_credits.mcred_acct_user_id IN ('88434')";        	  
	$Partie = '88434-Market Mall';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  9: $Userid =  " memo_credits.mcred_acct_user_id IN ('88435')";        	  
	$Partie = '88435-West Edmonton';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;

	case  10: $Userid =  " memo_credits.mcred_acct_user_id IN ('88438')";        	  
	$Partie = '88438-Metrotown';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  11: $Userid =  " memo_credits.mcred_acct_user_id IN ('88439')";        	  
	$Partie = '88439-Langley';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	case  12: $Userid =  " memo_credits.mcred_acct_user_id IN ('88440')";        	  
	$Partie = '88440-Rideau';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	

	case  13: $Userid =  " memo_credits.mcred_acct_user_id IN ('88444')";        	  
	$Partie = '88444-Mayfair';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	
	
	case  14: $Userid =  " memo_credits.mcred_acct_user_id IN ('88666')";        	  
	$Partie = 'Griffé Trois-Rivieres';		   
	$send_to_address = array('rapports@direct-lens.com');
	//$send_to_address = array('rapports@direct-lens.com');
	break;
	

}//End Switch

		
$MoisenCours=date('F');
echo 'Mois en cours:'. $MoisenCours.'<br><br>';

//On soustrait 1 mois pour trouver le mois précédent
switch ($MoisenCours){
	case 'January':		$MoisConcerner="Décembre";	break;
	case 'February':	$MoisConcerner="Janvier";	break;
	case 'March':		$MoisConcerner="Février";	break;
	case 'April':		$MoisConcerner="Mars";		break;
	case 'May':			$MoisConcerner="Avril";		break;
	case 'June':		$MoisConcerner="Mai";		break;
	case 'July':		$MoisConcerner="Juin";		break;
	case 'August':		$MoisConcerner="Juillet";	break;
	case 'September':	$MoisConcerner="Août";		break;
	case 'October':		$MoisConcerner="Septembre";	break;
	case 'November':	$MoisConcerner="Octobre";	break;
	case 'December':	$MoisConcerner="Novembre";	break;
}//End Switch

$GrandTotalCrediter = 0;//Initialiser le compteur
//Aller chercher l'année en cours pour déterminer l'Année à utiliser dans le rapport
$AnneeEnCours = date("Y");
if ($AnneeEnCours==2022){
	$year = 2022;
}
/*
}else if (($AnneeEnCours==2021)&& ($MoisConcerner=="Janvier")){
	$year = 2020;	
}else if(($AnneeEnCours==2021)&&($MoisConcerner<>"Janvier")){
	$year = 2021;
}*


switch($MoisConcerner){
		case 'Janvier':    	$date1 = $year. "-01-01"; $date2 = $year . "-01-31";    break;
		case 'Février':    	$date1 = $year. "-02-01"; $date2 = $year . "-02-29";    break;
		case 'Mars':       	$date1 = $year. "-03-01"; $date2 = $year . "-03-31";    break;
		case 'Avril':     	$date1 = $year. "-04-01"; $date2 = $year . "-04-30";    break;
		case 'Mai':       	$date1 = $year. "-05-01"; $date2 = $year . "-05-31";    break;
		case 'Juin':      	$date1 = $year. "-06-01"; $date2 = $year . "-06-30";    break;
		case 'Juillet':   	$date1 = $year. "-07-01"; $date2 = $year . "-07-31";    break;
		case 'Août':      	$date1 = $year. "-08-01"; $date2 = $year . "-08-31";    break;
		case 'Septembre': 	$date1 = $year. "-09-01"; $date2 = $year . "-09-30";    break;
		case 'Octobre':   	$date1 = $year. "-10-01"; $date2 = $year . "-10-31";    break;
		case 'Novembre':  	$date1 = $year. "-11-01"; $date2 = $year . "-11-30";    break;
		case 'Décembre':  	$date1 = $year. "-12-01"; $date2 = $year . "-12-31";    break;	
		default: exit();
}//End Switch
	
		
		
$rptQuery   = "SELECT * FROM memo_credits WHERE mcred_date BETWEEN '$date1' AND '$date2' AND $Userid  ORDER BY mcred_acct_user_id";
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
				<td align=\"center\">Détail</td>
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
				$message.='<tr><th colspan="3">TOTAL POUR LE COMPTE '.$MagasinEnCours.'</th><th>'.$TotalMagasinEnCours.'$</th></tr>';
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
	$message ="<div class=\"TextSize\">No credit have been created during this month</div>";}//END ORDERSNUM CONDITIONAL
	
	//$message.="<p>Total for all stores: $GrandTotalCrediter$</p>";

//SEND EMAIL
echo $message;
echo "<br>".$send_to_address;
$curTime      =  date("m-d-Y");	
$to_address   =  $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject      = "HBC Credits for $Partie $MoisConcerner  $year  ";
$response     = office365_mail($to_address, $from_address, $subject, null, $message);

}//END FOR 

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
		
	*/	
?>