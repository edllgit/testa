<?php
//TODO OPTIONEL: AJOUTER DANS CE RAPPORT L'OPTION DE GÉNÉRER POUR TOUS LES MAGASINS D'UN CLIQUE, SANS DEVOIR REFAIRE LA SELECTION POUR CHAQUE SUCCURSALE

error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
include("../sec_connect.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');

$isExporting = false;

$date1 = $_REQUEST[date1];
$date2 = $_REQUEST[date2];

$subject= "Rapport de reprise Griffé Lunetier Trois-Rivieres  $date1 - $date2";

// Checks if the user wants to export this data
if(isset($_POST['export_to']) && $_POST['export_to'] == 'xls'){
  // Download page as xls file (Excel supports html tables)
  $isExporting = true;
    header("Content-type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=".$subject.".xls");
}

if(!$isExporting){
  echo '<br><strong>Données utilisés pour générer ce rapport:</strong>';
  echo '<br>Subject:'. $subject;
}


$rptQuery="SELECT  eye, order_num, order_num_optipro, user_id, order_num,	order_date_processed, order_patient_first, order_patient_last, order_product_name, order_date_shipped, order_status, redo_reason_fr  FROM orders, redo_reasons
WHERE  user_id='88666' AND
orders.redo_reason_id = redo_reasons.redo_reason_id
AND orders.order_date_processed BETWEEN '$date1' and '$date2'
AND order_status <> 'cancelled'
AND redo_order_num IS NOT null";
        
//echo '<br><br>'. $rptQuery.'<br><br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

//echo 'Order num:'. $ordersnum;
//Préparer le courriel
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
</style></head>";

$message.="<body>
<table border=\"1\" width=\"850\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">
  <tr bgcolor=\"CCCCCC\">
    <td align=\"center\">Oeil</td>
    <td align=\"center\">Date de Commande</td>
    <td align=\"center\">Numéro de Commande</td>
    <td align=\"center\">Numéro de Commande Optipro</td>
    <td align=\"center\">Client</td>
    <td align=\"center\">Produit</td>
    <td align=\"center\">Raison de reprise</td>
  </tr>";
$GrandTotal = 0;	

while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
  $count++;
  if (($count%2)==0)
    $bgcolor="#E5E5E5";
  else 
    $bgcolor="#FFFFFF";

  $OrderTotalFormatter= number_format($listItem[order_total],2,',',' ');
  $message.="
    <tr bgcolor=\"$bgcolor\">
      <td align=\"center\">$listItem[eye]</td>
      <td align=\"center\">$listItem[order_date_processed]</td>
      <td align=\"center\">$listItem[order_num]</td>
      <td align=\"center\">$listItem[order_num_optipro]</td>
      <td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>
      <td align=\"center\">$listItem[order_product_name]</td>
      <td align=\"center\">$listItem[redo_reason_fr]</td>
    </tr>";
}

$message.="<tr><td colspan=\"5\">&nbsp;</td><td align=\"right\"><b>Nombre de reprises:</b></td><td align=\"center\"><b>$ordersnum</b></td></tr></table>";
            
//echo '<br><br>'. $message.'<br><br>';
//exit();
if ($ordersnum!=0 && !$isExporting){
  //SEND EMAIL TESTS
  $send_to_address = array('rapports@direct-lens.com');	


  //TODO CE RAPPORT SERA PRET, utiliser ces courriels plutot:
  $send_to_address = array('rapports@direct-lens.com');	

  //echo "<br>".$send_to_address;
  $curTime= date("m-d-Y");	
  $to_address=$send_to_address;
  $from_address='donotreply@entrepotdelalunette.com';
  $response=office365_mail($to_address, $from_address, $subject, null, $message);

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
    echo '<h3>Résultat:Le rapport généré ne seras pas affiché dans cette page, il a plutot été envoyé par courriel  avec succès aux adresses suivantes:'. $EmailEnvoyerA.'</h3><br><br>';
    //log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"SENT",$_SERVER['HTTP_USER_AGENT']);
  }else{
    echo '<h3>Résultat:Erreur durant l\'envoie du courriel, svp aviser Charles</h3>';
    //log_email("REPORT: Lentilles en fabrication $month pour $store",$EmailEnvoyerA,"FAILED",$_SERVER['HTTP_USER_AGENT']);
  }
}

if($isExporting){
  echo $message;
}

?>