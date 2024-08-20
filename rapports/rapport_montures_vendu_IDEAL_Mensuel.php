<?php
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
require __DIR__ . '/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$time_start = microtime(true);


// Calculer le premier jour du mois précédent
$firstDayOfPreviousMonth = date("Y-m-01", strtotime("first day of previous month"));

// Calculer le dernier jour du mois précédent
$lastDayOfPreviousMonth = date("Y-m-t", strtotime("last day of previous month"));

// Stocker les dates dans les variables respectives
$ladate = $firstDayOfPreviousMonth;
$ladatef = $lastDayOfPreviousMonth;

// Affichage pour vérification
echo "Premier jour du mois précédent : $ladate\n";
echo "Dernier jour du mois précédent : $ladatef\n";



$rptQuery = "SELECT orders.*, ifc_ca_exclusive.price FROM orders, extra_product_orders , ifc_ca_exclusive
WHERE ifc_ca_exclusive.primary_key = orders.order_product_id  
AND orders.order_num = extra_product_orders.order_num 
AND extra_product_orders.category='Frame' 
AND order_from in('ifcclubca') 
AND order_date_processed BETWEEN '$ladate' AND '$ladatef'  
AND order_product_type = 'exclusive' 
ORDER BY supplier, temple_model_num DESC";

$rptResult = mysqli_query($con, $rptQuery) or die('I cannot select items because: ' . mysqli_error($con));
$ordersnum = mysqli_num_rows($rptResult);

$count = 0;
$message = "";

$message = "<html>";
$message .= "<head><style type='text/css'>
<!--
.TextSize {
    font-size: 8pt;
    font-family: Arial, Helvetica, sans-serif;
}
-->
</style></head>";

$message .= "<body><table width=\"850\" cellpadding=\"2\" cellspacing=\"0\" class=\"TextSize\">";
$message .= "
<tr>
    <td colspan=\"11\">Ce rapport contient toutes les montures Focus vendus par nos magasins EDLL avec le code V1</td>
</tr>
<tr bgcolor=\"CCCCCC\">
    <th align=\"center\">Date</th>
      
    <th align=\"center\">Order #</th>
    <th align=\"center\">Optipro #</th>
    <th align=\"center\">Collection</th>
    <th align=\"center\">Model</th>
    <th align=\"center\">Color</th>
    <th align=\"center\">A Bridge</th>
    <th align=\"center\">Code Source Monture</th>
    <th align=\"center\">Patient</th>
    <th align=\"center\">Lenses</th>
</tr>";
//   <th align=\"center\">Account</th>       
$totalPrice = 0;
$totalOrderTotal = 0;

while ($listItem = mysqli_fetch_array($rptResult, MYSQLI_ASSOC)) {

    $queryFrame = "SELECT 
        epo.order_num, 
        epo.frame_type, 
        epo.supplier, 
        epo.model, 
        epo.temple_model_num, 
        epo.color,
        epo.ep_frame_a,
        epo.ep_frame_dbl 
    FROM 
        extra_product_orders epo
    JOIN 
        orders o ON epo.order_num = o.order_num
    WHERE  
        o.code_source_monture LIKE '%v1%' 
        AND o.code_source_monture <> 'S'
        AND epo.category IN ('Frame', 'Edging') 
        AND epo.supplier = 'FOCUS'  -- Utilisation stricte de l'égalité pour 'FOCUS'
        AND epo.order_num = '$listItem[order_num]'
        AND o.order_date_processed BETWEEN '$ladate' AND '$ladatef'";

    $resultFrame = mysqli_query($con, $queryFrame) or die('I cannot select items because: ' . mysqli_error($con));

    // Vérifiez si la requête renvoie des résultats
    if (mysqli_num_rows($resultFrame) > 0) {
        $DataFrame = mysqli_fetch_array($resultFrame, MYSQLI_ASSOC);

        // Affichez les données récupérées pour débogage
        echo "<pre>";
        print_r($DataFrame);
        echo "</pre>";

        $count++;
        $bgcolor = ($count % 2) == 0 ? "#E5E5E5" : "#FFFFFF";

        $message .= "<tr bgcolor=\"$bgcolor\">";
        $message .= "<td align=\"center\">$listItem[order_date_processed]</td>";
       // $message .= "<td align=\"center\">$entrepot</td>";

        if ($listItem['redo_order_num'] != '') {
            $message .= "<td align=\"center\">$listItem[order_num]R</td>";
        } else {
            $message .= "<td align=\"center\">$listItem[order_num]</td>";
        }

        $message .= "<td align=\"center\">$listItem[order_num_optipro]</td>";
        $message .= "<td align=\"center\">$DataFrame[supplier]</td>";
        $message .= "<td align=\"center\">$DataFrame[temple_model_num]</td>";
        $message .= "<td align=\"center\">$DataFrame[color]</td>";
        $message .= "<td align=\"center\">$DataFrame[ep_frame_a]-$DataFrame[ep_frame_dbl]</td>";
        $message .= "<td align=\"center\">$listItem[code_source_monture]</td>";
        $message .= "<td align=\"center\">$listItem[order_patient_first] $listItem[order_patient_last]</td>";
        $message .= "<td align=\"center\">$listItem[order_product_name]</td>";
        $message .= "</tr>";
    } else {
        // Si aucune donnée n'est trouvée pour la requête imbriquée, affichez un message d'erreur
        echo "<p>Aucune donnée trouvée pour l'ordre numéro: $listItem[order_num]</p>";
    }
}

//$message .= "<tr bgcolor=\"CCCCCC\"><td colspan=\"9\">Number of Orders: $ordersnum</td></tr>";
$message .= "</table></body></html>";

//$send_to_address = array('fdjibrilla@entrepotdelalunette.com');
$send_to_address = array('fdjibrilla@entrepotdelalunette.com','rapports@direct-lens.com','monture@entrepotdelalunette.com','approvisionnement@entrepotdelalunette.com','ebaillargeon@entrepotdelalunette.com','ronda@i-dealoptics.com','mweingarden@i-dealoptics.com');
$curTime = date("m-d-Y");
$to_address = $send_to_address;
$from_address = 'donotreply@entrepotdelalunette.com';
$subject = "Rapport Global EDLL des Montures Focus vendues entre le $ladate - $ladatef .";
$response = office365_mail($to_address, $from_address, $subject, null, $message);

$compteur = 0;
foreach ($to_address as $key => $value) {
    if ($compteur == 0) {
        $EmailEnvoyerA = $value;
    } else {
        $EmailEnvoyerA = $EmailEnvoyerA . ',' . $value;
    }
    $compteur += 1;
}

$date = new DateTime();
$timestamp = $date->format('Y-m-d_H-i-s');
$nomFichier = 'r_montures_vendu_Focus' . $timestamp;
$cheminFichierHtml = 'C:/All_Rapports_EDLL/MONTURE/' . $nomFichier . '.html';
file_put_contents($cheminFichierHtml, $message);

echo 'Rapport sauvegardé au format HTML : ' . $cheminFichierHtml . '<br>';
?>
