<?php
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

header('Content-Type: text/html; charset=iso-8859-1');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');
$time_start = microtime(true);

$InsererDansBD = true; // Si on rencontre des erreurs, on assigne false a cette variable

$delais = 1; // (Nombre de jours) - Date il y a X jours


$yesterday = mktime(0,0,0,date("m"),date("d")-$delais,date("Y"));
$la_veille = date("Y-m-d", $yesterday);

$today = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datedujour = date("Y-m-d", $today);

$queryRepriseDeLaVeille  = "SELECT * FROM orders WHERE order_date_shipped='$la_veille' AND redo_order_num IS NOT NULL
ORDER BY redo_reason_id";
$ResultRepriseDelaVeille =  mysqli_query($con,$queryRepriseDeLaVeille) or die ('I cannot select items because  1: ' .$queryRepriseDeLaVeille  . mysqli_error($con));

//<tr bgcolor=\"CCCCCC\">

$message="";
$message.="header('Content-Type: text/html; charset=iso-8859-1')";

$message="
<table class=\"table\" border=\"1\" width=\"1700\">
<tr>
    <th align=\"center\"># Commande</th>
    <th align=\"center\"># Original</th>
    <th align=\"center\">Compte</th>
    <th align=\"center\">Raison de Reprise</th>
    <th align=\"center\">Raison de Reprise</th>
    <th align=\"center\">Cr&eacute;dit d&eacute;ja &eacute;mis</th>
    <th align=\"center\">Script doit-il &eacute;mettre un cr&eacute;dit?</th>
    <th align=\"center\">Justification</th>
</tr>";

while ($DataRepriseVeille = mysqli_fetch_array($ResultRepriseDelaVeille,MYSQLI_ASSOC)){
    //Initialisation 
    $Justification="";
    $DoitEmettreCredit = 'Oui';
        
    //Requêtes
    $queryRaisonReprise = "SELECT * FROM redo_reasons WHERE redo_reason_id='$DataRepriseVeille[redo_reason_id]'";
    $resultRaisonReprise = mysqli_query($con,$queryRaisonReprise) or die ('I cannot select items because  1: ' .$queryRaisonReprise  . mysqli_error($con));
    $DataRaisonReprise = mysqli_fetch_array($resultRaisonReprise,MYSQLI_ASSOC);
    
    $queryVerifierCredit = "SELECT sum(mcred_abs_amount) as TotalDejaCrediter FROM memo_credits WHERE mcred_order_num= $DataRepriseVeille[redo_order_num]";
    $resultVerifierCredit = mysqli_query($con,$queryVerifierCredit) or die ('I cannot select items because  1: ' .$queryRaisonReprise  . mysqli_error($con));
    $DataVerifierCredit = mysqli_fetch_array($resultVerifierCredit,MYSQLI_ASSOC);
    
    $RaisonRepriseValidepourCredit = 'Non';
    switch($DataRepriseVeille[redo_reason_id]){
        case '89':$RaisonRepriseValidepourCredit = 'Oui'; break; //FRAME CHANGE
        case '91':$RaisonRepriseValidepourCredit = 'Oui'; break; //BROKEN MANIPULATION
        case '96':$RaisonRepriseValidepourCredit = 'Oui'; break; //ERROR DATA
        case '98':$RaisonRepriseValidepourCredit = 'Oui'; break; //N/A PRODUCT CHANGE (OTHER)
        case '99':$RaisonRepriseValidepourCredit = 'Oui'; break; //SCRATCH (NO WARRANTY)
    }//END SWITCH
    
    if ($RaisonRepriseValidepourCredit<>'Oui'){
        $Justification="La raison de reprise ne fait pas partie des 20%";
        $DoitEmettreCredit='Non';	
    }//END IF
    
    $message.="<tr>";
    $message.="
        <td align=\"center\">$DataRepriseVeille[order_num]</td>
        <td align=\"center\">$DataRepriseVeille[redo_order_num]</td>
        <td align=\"center\">$DataRepriseVeille[user_id]</td>
        <td align=\"center\">$DataRepriseVeille[redo_reason_id]</td>
        <td align=\"center\">$DataRaisonReprise[redo_reason_fr]</td>";
        
    if ($DataVerifierCredit[TotalDejaCrediter]<>''){
        $message.="<td align=\"center\">$DataVerifierCredit[TotalDejaCrediter]$</td>";
    }else{
        $message.="<td align=\"center\"></td>";
    }
    
    if ($DataVerifierCredit[TotalDejaCrediter]<>''){
        $DoitEmettreCredit='Non';
        $Justification = "Un ou des cr&eacute;dits ont d&eacute;ja &eacute;t&eacute; &eacute;mis sur la commande $DataRepriseVeille[redo_order_num].";
    }
    
    //CALCUL DU MONTANT A ÉMETTRE EN CRÉDIT
    if ($DoitEmettreCredit=='Oui'){
        //Calcul du montant du crédit à émettre
        $queryTotalOriginale  = "SELECT order_total, user_id, order_patient_first, order_patient_last FROM orders WHERE order_num=$DataRepriseVeille[redo_order_num]";
        $resultTotalOriginale = mysqli_query($con,$queryTotalOriginale) or die ('I cannot select items because  1: ' .$queryTotalOriginale . mysqli_error($con));
        $DataTotalOriginale   = mysqli_fetch_array($resultTotalOriginale,MYSQLI_ASSOC);
        
        $MontantAEmettre = ($DataRaisonReprise[Pourcentage_a_emettre_comme_credit]/100) * $DataTotalOriginale[order_total]; //Valeur totale de la commande originale	
        $MontantAEmettre = money_format('%.2n',$MontantAEmettre);
    }
    
    // ON ÉMET LE CRÉDIT SUR LA COMMANDE ORIGINALE
    if ($DoitEmettreCredit=='Oui'){
        $mcred_acct_user_id = $DataTotalOriginale[user_id];
        $mcred_order_num 	= $DataRepriseVeille[redo_order_num];
        $patient_first_name	= $DataTotalOriginale[order_patient_first];
        $patient_last_name	= $DataTotalOriginale[order_patient_last];
        $mcred_abs_amount   = $MontantAEmettre;
        $mcred_date 		= $datedujour;
        $RaisonRepriseFR    = str_replace("'",' ',$DataRaisonReprise[redo_reason_fr]);
        $mcred_detail 		= "Cr&eacute;dit g&eacute;n&eacute;r&eacute; automatiquement par le script de G&eacute;n&eacute;ration de Cr&eacute;dit. Raison de ce cr&eacute;dit: $DataRaisonReprise[Pourcentage_a_emettre_comme_credit] pour $RaisonRepriseFR";
        $mcred_memo_num     = 'M'. $DataRepriseVeille[redo_order_num] . 'A';
        //Nous avons le montant ainsi que le numéro de commande sur lequel émettre le crédit. 	
        $queryInsertInto="INSERT INTO memo_credits (mcred_cred_type,mcred_acct_user_id, mcred_order_num,patient_first_name, patient_last_name, mcred_abs_amount,mcred_date,mcred_detail, mcred_memo_num, mcred_memo_code) 
        VALUES ('credit','$mcred_acct_user_id',$mcred_order_num,'$patient_first_name','$patient_last_name','$mcred_abs_amount','$mcred_date','$mcred_detail','$mcred_memo_num','9.99')";
        if($InsererDansBD){
            $resultInsert  =  mysqli_query($con,$queryInsertInto)		or die  ('I cannot select items because  1: ' .$queryInsertInto  . mysqli_error($con));
            $Justification = "<b>Le cr&eacute;dit $mcred_memo_num de $mcred_abs_amount$ vient d'&ecirc;tre  &eacute;mis!</b>";
        }else{
            $Justification = "<b>Le cr&eacute;dit $mcred_memo_num de $mcred_abs_amount$ (non émis)</b>";
        }
    }

    if ($DoitEmettreCredit=='Oui'){
        $message.="
        <td align=\"center\"><b>$DoitEmettreCredit, $DataRaisonReprise[Pourcentage_a_emettre_comme_credit] = $MontantAEmettre$</b></td>
        <td align=\"center\">$Justification</td>";
    }else{
        $message.="
        <td align=\"center\">$DoitEmettreCredit</td>
        <td align=\"center\">$Justification</td>";
    }

    $message.="</tr>";
}
    
echo '<br /><br />'.$message;

//Envoie du rapport par courriel
$to_address = array('rapports@direct-lens.com');
$from_address = 'donotreply@entrepotdelalunette.com';
if($InsererDansBD){
    $subject = "Credit 20% générés automatiquement: $la_veille";
}else{
    $subject = "(Test) Credit 20% générés automatiquement: $la_veille";
}

//SEND EMAIL
if ($SendEmail == 'yes'){
    $response = office365_mail($to_address, $from_address, $subject, null, $message);
}

if($response){ 
    echo '<div class="alert alert-success" role="alert"><strong>Email Sent Sucessfully to:'. $EmailEnvoyerA.'</strong></div>';
}else{
    echo '<div class="alert alert-danger" role="alert"><strong>Error while sending the email to: '. $EmailEnvoyerA.'</strong></div>';
}	

//Logger l'exécution du script
$time_end        = microtime(true);
$time            = $time_end - $time_start;
$heure_execution = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
                    VALUES('Generation automatique de Credits', '$time','$today','$heure_execution','script_generation_credit_automatique.php')";
if($InsererDansBD){
    $cronResult = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));
}

?>