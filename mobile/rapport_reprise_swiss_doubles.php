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

$datedebut   = $_POST[datea];
$datefin     = $_POST[dateb];

$subject      = "Swiss Report Redos Between $datedebut and $datefin (Redos done within 30 days)";

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
AND redo_order_num IS NOT NULL AND prescript_lab = 10
AND order_date_processed BETWEEN '$datedebut' AND '$datefin'
AND redo_reasons.redo_reason_id <> '' AND redo_reasons.redo_reason_id <> 0
AND redo_order_num NOT IN (
1470543,
1471062,
1471142,
1471424,
1471451,
1471480,
1471517,
1471630,
1471891,
1471955,
1472017,
1472280,
1472368,
1472489,
1472566,
1472696,
1472832,
1473018,
1473039,
1473265,
1473557,
1473673,
1474350,
1474452,
1474562,
1475065,
1475284,
1475629,
1475913,
1475929,
1476259,
1476539,
1476641,
1476667,
1477079,
1477445,
1477625,
1477626,
1477633,
1477691,
1477839,
1477987,
1478194,
1478557,
1478767,
1478811,
1479497,
1479575,
1479626,
1479666,
1479757,
1479828,
1479907,
1479984,
1480236,
1480398,
1480403,
1480542,
1480576,
1480579,
1480580,
1480877,
1480880,
1480895,
1480899,
1480979,
1480993,
1481098,
1481217,
1481351,
1481518,
1481977,
1481987,
1481988,
1481997,
1482042,
1482043,
1482200,
1482688,
1482774,
1482820,
1482821,
1482886,
1483705,
1483735,
1483783,
1483799,
1483912,
1484611,
1484629,
1484663,
1485181,
1485542,
1485544,
1485551,
1485633,
1485637,
1485652,
1485741,
1485884,
1485885,
1485887,
1485888,
1485921,
1486308,
1486313,
1486314,
1486316,
1486317,
1486334,
1486387,
1486390,
1486417,
1486465,
1486467,
1486587,
1486593,
1486594,
1486595,
1486599,
1486600,
1486601,
1486610,
1486611,
1486651,
1486653,
1486654,
1486655,
1486662,
1486663,
1486744,
1486749,
1486762,
1487173,
1487271,
1487272,
1487273,
1487274,
1487276,
1487277,
1487278,
1487279,
1487280,
1487301,
1487420,
1487421,
1487431,
1487447,
1487472,
1487494,
1487497,
1487500,
1487501,
1487503,
1487505,
1487634,
1487648,
1487665,
1487666,
1487684,
1487704,
1487749,
1487883,
1488053,
1488057,
1488103,
1488122,
1488162,
1488216,
1488246,
1488273,
1488288,
1488335,
1488449,
1488464,
1488502,
1488507,
1488508,
1488529,
1488556,
1488560,
1488607,
1488996,
1489019,
1489075,
1489145,
1489181,
1489202,
1489255,
1489414,
1489418,
1489421,
1489424,
1489426,
1489544,
1489587,
1489602,
1489615,
1489748,
1489952,
1490034,
1490045,
1490090,
1490108,
1490109,
1490110,
1490112,
1490237,
1490378,
1490459,
1490547,
1490706,
1490730,
1490929,
1490975,
1490999,
1491096,
1491102)

ORDER BY order_num";	

//100% des commandes fabriqués 2 fois par Swiss seraient créditable dans ce rapport

//On élimine les cas ou:

//1-La commande originale n'a pas été fabriqué par Swiss
//2-Il y a eu plus de 30 jours entre la commande originale et la reprise
//3-Ne contient pas les reprises pour lesquels nous avons déja présenté  une  demande de crédit, car ils sont la responsabilité de Swisscoat

/*Comment nous assurer de ne pas utiliser les reprises qui ont déja été crédité par Swisscoat ?
Idée la plus rapide: générer le rapport déja en place pour la même période et noter les reprises qui en font partie,
les ajouter dans un NOT IN lorsqu'on roule ce 2ieme rapport. Demande une intervention manuelle mais semble l'option la plus rapide.
*/

if($Debug == 'yes'){
  echo '<br>Query: <br>'. $rptQuery . '<br>';
}

$rptResult = mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

if ($ordersnum == 0 && !$isExporting){
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
  <tr>
    <th align=\"center\"><b>User ID</b></th>
    <th align=\"center\"><b>Redo #</b></th>
    <th align=\"center\"><b>Redo Date</b></th>
    <th align=\"center\"><b>Original #</b></th>
    <th align=\"center\"><b>Original Date</b></th>
    <th align=\"center\"><b>Days between Original and Redo</b></th>
    <th align=\"center\"><b>Product</b></th>
    <th align=\"center\"><b>Redo Reason</b></th>
    <th align=\"center\"><b>Cost US</b></th>
  </tr>
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
  
  //Vérifier si la commande originale à bien été fabriqué chez Swiss
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
  
  $CommandeAdmissible = 'oui';

  if ($PrescriptLab <> 10){//Si l'original est faite chez Swiss, on l'ajoute au rapport
    echo '<br><br>Commande originale pas faite par Swiss :'. $listItem[order_num];
    $CommandeAdmissible = 'non';
  }
    
  if ($interval > 30){
    echo '<br><br>Commande + de 30 jours:'. $listItem[order_num]. ' '. $interval;
    $CommandeAdmissible = 'non';
  }
  
  if ($CommandeAdmissible == 'oui'){
    $Compteur = $Compteur+1;
    $CompteurCost = $CompteurCost+ $listItem[cost];	
    $message.="
    <tr>
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
      $message.="</tr>";
    }
  }//End IF
}//END WHILE

$CompteurCost=money_format('%.2n',$CompteurCost);

$message.="<tr><td colspan=\"8\">Number of Orders: $Compteur</td>
<td align=\"center\">$CompteurCost$</td></tr></table>";	

if ($ordersnum!=0 && !$isExporting){
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
    //log_email("REPORT: Redos of the day Swiss",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
    echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
  }else{
    //log_email("REPORT: Redos of the day Swiss",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
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