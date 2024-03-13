<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

include("../../sec_connectEDLL.inc.php");
include('../../phpmailer_email_functions.inc.php');

//require 'vendor/autoload.php'; // Assurez-vous d'avoir inclus la bibliothèque PhpSpreadsheet
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

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



//Dates hard codés **A RECOMMENTER**
/*
$date1 = '2020-02-14';
$date2 = '2020-02-20';
*/

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
AND lab IN (66,67,59) AND order_status NOT IN ('cancelled','on hold')
GROUP BY order_num
ORDER BY orders.user_id, orders.redo_reason_id";

//echo '<br>Query: <br>'. $rptQuery . '<br>';
	
$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because 1a: ' . mysqli_error($con));
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
						<td  align=\"center\"><b>Compte</b></td>
						
						<td  align=\"center\"><b>Numéro de la reprise</b></td>
						<td  align=\"center\"><b>Date reprise</b></td>
						<td  align=\"center\"><b>Fabriquant reprise</b></td>
						
						<td  align=\"center\"><b>Commande original</b></td>
						<td  align=\"center\"><b>Date original</b></td>
						<td  align=\"center\"><b>Fabriquant Original</b></td>
						
						<td  align=\"center\"><b>Raison reprise</b></td>
						<td  align=\"center\"><b>Nombre commandes</b></td>
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
					   <td align=\"center\">Total de reprises:</td>
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
				  $message.="<td  align=\"center\"><b>Compte</b></td>
						
						<td  align=\"center\"><b>Numéro de la reprise</b></td>
						<td  align=\"center\"><b>Date reprise</b></td>
						<td  align=\"center\"><b>Fabriquant reprise</b></td>
						
						<td  align=\"center\"><b>Commande original</b></td>
						<td  align=\"center\"><b>Date original</b></td>
						<td  align=\"center\"><b>Fabriquant Original</b></td>
						
						<td  align=\"center\"><b>Raison reprise</b></td>						
						<td  align=\"center\"><b>Nombre commandes</b></td>";
	}
		
	$queryOriginal 	    = "SELECT order_date_processed, prescript_lab FROM orders WHERE order_num = $listItem[redo_order_num]";
	$resultOriginal 	= mysqli_query($con,$queryOriginal)		or die  ('I cannot select items because 2b: ' . mysqli_error($con));
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
			case 'scanned shape to swiss': 	$list_order_status = "Scanned shape to Swiss"; 	break;
			case "on hold":					$$list_order_status= "On Hold";			        break;
			case 'waiting for frame store':		$list_order_status = "Waiting for Frame Store";		break;
			case 'waiting for frame ho/supplier':		$list_order_status = "Waiting for Frame Head Office/Supplier";		break;
			default:                        $list_order_status = "UNKNOWN";             	break;
		}
		
		switch($listItemOriginal[prescript_lab]){
			case 21:    $LabOriginal = 'Trois-Rivieres';  			 break;
			case 25:    $LabOriginal = 'Direct-Lens Exclusive #2';   break;
			case 10:    $LabOriginal = 'Direct-Lens Exclusive #1';   break;
			case 69:    $LabOriginal = 'Essilor #1 Lab';  			 break;
			case 72:    $LabOriginal = 'Optique Quebec';  			 break;
			case  3:    $LabOriginal = 'Directlab St.Catharines';    break;
			default:  	$LabOriginal = 'ERREUR';
		}
		
		switch($listItem[user_id]){
			case 'entrepotifc' : 	case 'entrepotsafe' :   $User_ID_IN = "('entrepotifc','entrepotsafe')";      	$Succursale = 'Trois-Rivieres';   				break;
			case 'entrepotdr' :   	case 'safedr': 		    $User_ID_IN = "('entrepotdr','safedr')";  		     	$Succursale = 'Drummondville';    				break;
			case 'warehousehal': 	case 'warehousehalsafe' :$User_ID_IN = "('warehousehal','warehousehalsafe')";	$Succursale = 'Halifax';   	   					break;
			case 'laval' :        	case 'lavalsafe' :      $User_ID_IN = "('laval','lavalsafe')";  		     	$Succursale = 'Laval';   		   				break;
			case 'terrebonne' :   	case 'terrebonnesafe' : $User_ID_IN = "('terrebonne','terrebonnesafe')";     	$Succursale = 'Terrebonne';       				break;
			case 'sherbrooke' :   	case 'sherbrookesafe' : $User_ID_IN = "('sherbrooke','sherbrookesafe')";    	$Succursale = 'Sherbrooke';       				break;
			case 'chicoutimi' :   	case 'chicoutimisafe' : $User_ID_IN = "('chicoutimi','chicoutimisafe')";     	$Succursale = 'Chicoutimi';      				break;
			case 'levis' :        	case 'levissafe' : 		$User_ID_IN = "('levis','levissafe')"; 		         	$Succursale = 'Lévis';   		   				break;
			case 'granby' :       	case 'granbysafe' : 	$User_ID_IN = "('granby','granbysafe')"; 		     	$Succursale = 'Granby';   		   				break;
			case 'longueuil' :    	case 'longueuilsafe' :  $User_ID_IN = "('longueuil','longueuilsafe')";       	$Succursale = 'Longueuil';        				break;
			case 'gatineau' :     	case 'gatineausafe' :   $User_ID_IN = "('gatineau','gatineausafe')";         	$Succursale = 'Gatineau';         				break;
			case '88666' :     							    $User_ID_IN = "('88666')";         						$Succursale = 'Griffe lunetier #88666';         break;
			//case 'montreal' :     	case 'montrealsafe' :   $User_ID_IN = "('montreal','montrealsafe')";         	$Succursale = 'Montreal ZT1';         			break;
			case 'entrepotquebec' : case 'quebecsafe' :     $User_ID_IN = "('entrepotquebec','quebecsafe')";        $Succursale = 'Quebec';         				break;
			case 'garantieatoutcasser' :       			    $User_ID_IN = "('aaa')";							 	$Succursale = 'Garantieatoutcasser';            break;
			case 'redoifc' :       							$User_ID_IN = "('aaa')";							 	$Succursale = 'Compte de reprise Interne IFC';  break;
			case 'redosafety' :       						$User_ID_IN = "('aaa')";						 	 	$Succursale = 'Compte de reprise Interne SAFE'; break;
			case 'St.Catharines' :       					$User_ID_IN = "('aaa')";							 	$Succursale = 'Compte de reprise Interne STC';  break;
			case 'redoqc':									$User_ID_IN = "('redoqc')";							 	$Succursale = 'Compte de reprise Interne QC'; 	break;
			case 'redo_supplier_stc':						$User_ID_IN = "('redo_supplier_stc')";					$Succursale = 'Compte de reprise Interne Fournisseur STC'; 	break;
			case 'redo_supplier_quebec':					$User_ID_IN = "('redo_supplier_quebec')";				$Succursale = 'Compte de reprise Interne Fournisseur QC'; 	break;
			default:  	$Succursale = 'ERREUR';
		}
		
		
		 if ($User_ID_IN <> ""){
			 $rptOriginal  = "SELECT count(order_num) as NbrOriginales FROM orders WHERE redo_order_num IS NULL AND user_id IN $User_ID_IN
			AND order_date_processed BETWEEN '$date1' AND '$date2' ";
			//echo '<br>'. $rptOriginal;
			$ResultCommandesOriginales 	= mysqli_query($con,$rptOriginal)		or die  ('I cannot select items because 3c: ' . mysqli_error($con));
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
					   <td align=\"center\">Total de reprises:</td>
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
					   <td align=\"center\">Grand Total de reprises:</td>
					   <td align=\"center\">$GrandTotalReprises</td>
				  </tr>";
				  
				  
				
	
	
	
	$message     .="</table>";
	$to_address = array('rapports@direct-lens.com');
	
	ob_start();

	//$to_address = array('rapports@direct-lens.com');//ENLEVER A LA FIN MON EMAIL HARD CODÉ
	$curTime	  = date("m-d-Y");	
	$from_address = 'donotreply@entrepotdelalunette.com';
	$subject	  = "Rapport de reprises EDLL: $date1- $date2";
	
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

			// Générer le contenu HTML du rapport
	$contenuHtml = ob_get_clean();

	// Créez un nom de fichier unique avec un horodatage
	$date = new DateTime();
	$timestamp = $date->format('Y-m-d_H-i-s');
	$nomFichier = 'r_reprise_semaine_edll'. $timestamp;

	// Enregistrez le contenu HTML dans un fichier
	$cheminFichierHtml = 'C:/All_Rapports_EDLL/general/REPRISE/' . $nomFichier . '.html';
	file_put_contents($cheminFichierHtml, $contenuHtml);

	echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
		
		
	if($response){ 
		log_email("REPORT: Orders of the day - Crystal",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
	}else{
		log_email("REPORT: Orders of the day - Crystal",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
		echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
	}	

}//End if query gives results

echo $message;

function log_email($subject,$send_to_address,$additional, $user_agent){
	$curTime = date("Y-m-d");	
	//Log the email sent
	$queryMail = "INSERT INTO email_log (subject, send_to_address, date, additional, user_agent) VALUES ('$subject','$send_to_address','$curTime','$additional', '$user_agent')";
	$ResultMail  = mysqli_query($con,$queryMail)		or die  ('I cannot Send email because 4d: ' . mysqli_error($con));	
}?>
   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
  </body>
</html>