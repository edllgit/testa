<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//GKB: redos seulement
ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/url.constant.php');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start   = microtime(true);

$isExporting  = false;

$today	      = date("Y-m-d");
//$today      = date("2016-11-02");

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

$datedebut   = $_POST[date1];
$datefin     = $_POST[date2];

$subject     = "KNR Redos Report between $datedebut and $datefin";

// Checks if the user wants to export this data
if(isset($_POST['export_to']) && $_POST['export_to'] == 'xls'){
    // Download page as xls file (Excel supports html tables)
    $isExporting = true;
    header("Content-type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".$subject.".xls");
}

$rptQuery  = "SELECT user_id, order_num, order_date_processed, redo_order_num, order_product_name, orders.redo_reason_id, cost_us as cost
FROM orders, redo_reasons, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key 
AND orders.redo_reason_id = redo_reasons.redo_reason_id
AND redo_order_num IS NOT NULL AND prescript_lab = 73
AND order_date_processed BETWEEN '$datedebut' AND '$datefin'
AND redo_reasons.redo_reason_id <> '' AND redo_reasons.redo_reason_id <> 0
AND redo_reasons.redo_reason_id NOT IN (37,38,39,46,51,52,57,40,62,43,59,45,65,66,64,31,53,63,73)
ORDER BY order_num";	


//CAS PARTICULIER POUR DANIEL
/*
$rptQuery  = "SELECT user_id, order_num, order_date_processed, redo_order_num, order_product_name, orders.redo_reason_id, cost_us as cost
FROM orders, redo_reasons, ifc_ca_exclusive
WHERE orders.order_product_id = ifc_ca_exclusive.primary_key 
AND orders.redo_reason_id = redo_reasons.redo_reason_id
AND redo_order_num IS NOT NULL AND prescript_lab = 73
AND order_date_processed BETWEEN '$datedebut' AND '$datefin'
AND redo_reasons.redo_reason_id <> '' AND redo_reasons.redo_reason_id <> 0
AND redo_reasons.redo_reason_id  IN (62,53,59,70,37,51,46,53,52,63,57,40,39,38,45)
ORDER BY order_num";	
*/
    
if($Debug == 'yes')
echo '<br>Query: <br>'. $rptQuery . '<br>';

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

if ($ordersnum == 0){
  echo '<div class="alert alert-warning" role="alert"><strong>0 result</strong></div>';
}
    
    
$count=0;
$message="";	
$message.="<html>";

if(!$isExporting){
  $message.="<head>
    <meta charset=\"utf-8\">
    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
    <meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
    <!-- Bootstrap core CSS -->
    <link href=\"".constant('DIRECT_LENS_URL')."/bootstrap/css/bootstrap.min.css\" rel=\"stylesheet\">
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src=\"https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js\"></script>
    <script src=\"https://oss.maxcdn.com/respond/1.4.2/respond.min.js\"></script>
    <![endif]-->
  </head>";
}


$message.="<body>
<table class=\"table\" border=\"1\">
<thead>
  <th align=\"center\"><b>User ID</b></th>
  <th align=\"center\"><b>Redo #</b></th>
  <th align=\"center\"><b>Redo Date</b></th>
  <th align=\"center\"><b>Original #</b></th>
  <th align=\"center\"><b>Original Date</b></th>
  <th align=\"center\"><b>Days between Original and Redo</b></th>
  <th align=\"center\"><b>Product</b></th>
  <th align=\"center\"><b>Redo Reason</b></th>
  <th align=\"center\"><b>Cost US</b></th>
</thead>";

$Compteur = 0;	
$CompteurCost = 0;	
while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
  $count++;
  if (($count%2)==0)
    $bgcolor="#E5E5E5";
  else 
    $bgcolor="#FFFFFF";
  
  $queryRedoReason  = "SELECT redo_reason_en FROM redo_reasons WHERE  redo_reason_id = $listItem[redo_reason_id] ";		
  $ResultRedoReason = mysqli_query($con,$queryRedoReason)		or die  ('I cannot select items because: ' . mysqli_error($con));
  $DataRedoReason   = mysqli_fetch_array($ResultRedoReason,MYSQLI_ASSOC);
  $RedoReasonEN     = $DataRedoReason[redo_reason_en];
  
  //Vérifier si la commande originale à bien été fabriqué chez KNR
  $queryVerificationOriginalSwiss = "SELECT prescript_lab, order_date_processed as DateOriginal FROM orders WHERE order_num =  $listItem[redo_order_num]";
  $ResultValidationOriginalSwiss  = mysqli_query($con,$queryVerificationOriginalSwiss)		or die  ('I cannot select items because: ' . mysqli_error($con));
  $DataValidationOriginalSwiss    = mysqli_fetch_array($ResultValidationOriginalSwiss,MYSQLI_ASSOC);
  $PrescriptLab = $DataValidationOriginalSwiss[prescript_lab];
  $DateOriginal = $DataValidationOriginalSwiss[DateOriginal];
  $DateReprise = $listItem[order_date_processed];
  //Calcul de la date de commande de la reprise - 60 jours (2 mois)
  $dateReprise = $listItem[order_date_processed];

  $date1 = strtotime($DateOriginal);
  $date2 = strtotime($DateReprise);
  $interval = ($date2 -$date1)/86400;
  
  $NombreResult = 0;
  //Valider si la raison de reprise est #42 ou 50 = lab edging error, on doit valider qui a fait le taillage de la commande ORIGINALE
  if ($listItem[redo_reason_id] == 42 || $listItem[redo_reason_id] == 50){
    //Verifier dans l'historique de statuts si une mise a jour 'edging' a été faite par STC, si c'est le cas, la commande est inadmissible pour le rapport redo KNR
    $queryEdging = "SELECT * FROM status_history WHERE order_num = $listItem[redo_order_num] AND order_status  IN ('in edging') AND update_ip2 = '72.38.178.58' ";	
    //Si cette requete ne donne aucun résultat, STC n'a pas fait de mise a jour  donc KNR a fait le taillage, la commane est admissible
    echo '<br>' . $queryEdging;
    $resultEdging  = mysqli_query($con,$queryEdging)		or die  ('I cannot Send email because: ' . mysqli_error($con));
    $NombreResult  = mysqli_num_rows($resultEdging);	
  }
  
  $CommandeAdmissible = 'oui';

  if ($PrescriptLab <> 73){//Si l'original est faite chez KNR, on l'ajoute au rapport
    echo '<br><br>Commande originale pas faite par KNR :'. $listItem[order_num];
    $CommandeAdmissible = 'non';
  }
    
  if ($interval > 60){
    //echo '<br><br>Commande + de 60 jours:'. $listItem[order_num]. ' '. $interval;
    //$CommandeAdmissible = 'non';
  }
          
  if ($NombreResult > 0){
    echo '<br><br>'. $NombreResult.' Commande taillé par Saint-Catharines:'. $listItem[redo_order_num];
    $CommandeAdmissible = 'non';	
  }			
        
        
  if ($CommandeAdmissible == 'oui'){
    $Compteur = $Compteur+1;
    $CompteurCost = $CompteurCost+ $listItem[cost];	
    $message.="<tr>
      <td align=\"center\">$listItem[user_id]</td>
      <td bgcolor=\"#F5EA4D\" align=\"center\">$listItem[order_num]</td>
      <td bgcolor=\"#F5EA4D\" align=\"center\">$DateReprise</td>
      <td bgcolor=\"#EDBD15\" align=\"center\">$listItem[redo_order_num]</td>
      <td bgcolor=\"#EDBD15\" align=\"center\">$DateOriginal</td>
      <td align=\"center\">$interval</td>
      <td align=\"center\">$listItem[order_product_name]</td>
      <td align=\"center\">$RedoReasonEN</td>";
    
    if ($listItem[cost] ==0){
      $message.="<td bgcolor=\"#F10A0E\" align=\"center\">$listItem[cost]</td>";
    }else{
      $message.="<td align=\"center\">$listItem[cost]</td>";
    }
    $message.="</tr>";
  }//End IF
}//END WHILE

$CompteurCost=money_format('%.2n',$CompteurCost);

$message.="<tr><td colspan=\"8\">Number of Orders: $Compteur</td>
<td align=\"center\">$CompteurCost$</td></tr></table>";	

if ($ordersnum!=0){
    $to_address = array('rapports@direct-lens.com');
    $from_address = 'donotreply@entrepotdelalunette.com';
    
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
    //log_email("REPORT: Redos of the day KNR",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
  }else{
    //log_email("REPORT: Redos of the day KNR",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
    echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
  }	
}//End IF query gives results

if(!$isExporting){
  $time_end 		 = microtime(true);
  $time     		 = $time_end - $time_start;
  $today    	     = date("Y-m-d");// current date
  $timeplus3heures = date("H:i:s");
  $CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Email redirection Redo report GKB', '$time','$today','$timeplus3heures','cron_send_redirection_redo_report_gkb.php')";
  $cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con) );
}

echo $message;

?>