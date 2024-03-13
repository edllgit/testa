<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1');
require_once(__DIR__.'/../../constants/url.constant.php');
include("../../connexion_hbc.inc.php");
include('../../phpmailer_email_functions.inc.php');
require_once('../../class.ses.php');
$time_start = microtime(true);     

$isExporting = false;

// IDs des raisons de reprise a inclure dans le rapport:
$reasonsIds = "2,3,4,54,31,69,46,53,52,63,57,16,40,39,77,78,79,80,81,82,83,84,86,87,88,92,93,94,97";

//POUR ADMINISTRATEURS SEULEMENT CAR CONTIENT LES $$$
$aWeekAgo      = mktime(0,0,0,date("m"),date("d")-6,date("Y"));
$dateaweekago  = date("Y-m-d",$aWeekAgo );
$aujourdhui    = date("Y-m-d");//Aujourd'hui, journee  ou le rapport est execute Ex 19 janvier contiendra du 13 au 19 janvier 2015

//RECOMMENTER CES DATES
$date1_AnneeEnCours  	= $_REQUEST[date1];
$date2_AnneeEnCours    	= $_REQUEST[date2];

//echo '<br>'.$date1_AnneeEnCours . ' ' . $date2_AnneeEnCours;

$date3_AnneeEnCours  	= $_REQUEST[date3];
$date4_AnneeEnCours    	= $_REQUEST[date4];

$AnneeDate1EnCours = substr($date1_AnneeEnCours,0,4);
$AnneeDate2EnCours = substr($date2_AnneeEnCours,0,4);
$ResteDate1EnCours = substr($date1_AnneeEnCours,4,6);
$ResteDate2EnCours = substr($date2_AnneeEnCours,4,6);

$AnneeDate1AnDernier = $AnneeDate1EnCours -1;
$AnneeDate2AnDernier = $AnneeDate2EnCours -1;

$date1_AnneeDernier  	= $AnneeDate1AnDernier .$ResteDate1EnCours; 
$date2_AnneeDernier    	= $AnneeDate2AnDernier .$ResteDate2EnCours; 
    
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

$subject = "Rapport de reprise comparatif HBO Période: $date1_AnneeEnCours-$date2_AnneeEnCours";

$count    = 0;
$message  = "";
$message  = "<html>";
// Checks if the user wants to export this data
if(isset($_POST['export_to']) && $_POST['export_to'] == 'xls'){
  // Download page as xls file (Excel supports html tables)
  $isExporting = true;
    header("Content-type: application/vnd.ms-excel");
  header("Content-Disposition: attachment; filename=".$subject.".xls");
}else{
  $message .= "
  <head>
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
      <td colspan=\"1\" align=\"center\"></td>
      <td align=\"center\" bgcolor=\"#00ff83\" colspan=\"4\"><b>Dates sélectionnées: [$date1_AnneeEnCours - $date2_AnneeEnCours]</b></td>
      <td align=\"center\" bgcolor=\"#99c7f7\" colspan=\"4\"><b>Mêmes dates, l'<b>année précédente</b>: [$date1_AnneeDernier - $date2_AnneeDernier]</b></td>";
    
if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
$message.="<td align=\"center\" bgcolor=\"#83d0c9\" colspan=\"4\"><b>Comparaison: [$date3_AnneeEnCours - $date4_AnneeEnCours]</b></td>";
    
}	
    
$message.="</tr></thead>
<thead>
  <tr>
    <td align=\"center\"> </th>
    <td  bgcolor=\"#00ff83\" align=\"center\"><b>Nbr Reprises*</b></td>	
    <td  bgcolor=\"#00ff83\" align=\"center\"><b>Valeur Reprises*</b></td>
    <td  bgcolor=\"#00ff83\" align=\"center\"><b>Nbr Vente</b></td>
    <td  bgcolor=\"#00ff83\" align=\"center\"><b>% Reprise</b></td>
    <td  bgcolor=\"#99c7f7\" align=\"center\"><b>Nbr reprise*</b></td>
    <td  bgcolor=\"#99c7f7\" align=\"center\"><b>Valeur Reprises*</b></td>
    <td  bgcolor=\"#99c7f7\" align=\"center\"><b>Nbr Vente</b></td>
    <td  bgcolor=\"#99c7f7\" align=\"center\"><b>% Reprise</b></td>";


if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
$message.="
    <td  bgcolor=\"#83d0c9\" align=\"center\"><b>Nbr Reprise*</b></td>
    <td  bgcolor=\"#83d0c9\" align=\"center\"><b>Valeur Reprise*</b></td>
    <td  bgcolor=\"#83d0c9\" align=\"center\"><b>Nbr Vente</b></td>
    <td  bgcolor=\"#83d0c9\" align=\"center\"><b>% Reprise</b></td>";
}

$message.="</tr></thead>";

//Initialisations des compteurs
$totalFirstTimeOrder        = 0;
$MontanttotalFirstTimeOrder = 0;
$totalRedos                 = 0;
$MontanttotalRedos          = 0;
//Période sélectionnée
$CompteurReprisesGlobal 	= 0;	
$CompteurValeurReprisesGlobal 	= 0;
$CompteurVentesGlobal 	= 0;	
//An Dernier
$CompteurReprisesGlobal_An_Dernier 			= 0;	
$CompteurValeurReprisesGlobalAn_Dernier 	= 0;
$CompteurVentesGlobalAn_Dernier 			= 0;

//Période de comparaison
$CompteurReprisesGlobal_Comparaison 		= 0;	
$CompteurValeurReprisesGlobal_Comparaison 	= 0;
$CompteurVentesGlobalComparaison 			= 0;

    
    
//FOR pour parcourir les Succursales
for ($i = 1; $i <= 11; $i++) {
   // echo '<br>'. $i;	
        
//Nouvelle partie
switch($i){
    case  1: $Userid =  " orders.user_id IN ('88403')";      	$Compagnie = '88403-Bloor';			 	break;
    case  2: $Userid =  " orders.user_id IN ('88408')";       	$Compagnie = '88408-Oshawa';		 	break;
    case  3: $Userid =  " orders.user_id IN ('88409')"; 		$Compagnie = '88409-Eglinton';		 	break;
    case  3: $Userid =  " orders.user_id IN ('88414')"; 	 	$Compagnie = '88414-Yorkdale';		 	break; // TODO - Figure out why 3 is there twice (unreachable)
    case  4: $Userid =  " orders.user_id IN ('88416')";     	$Compagnie = '88416-Vancouver';		 	break;
    case  5: $Userid =  " orders.user_id IN ('88431')";     	$Compagnie = '88431-Calgary';		 	break;
    case  6: $Userid =  " orders.user_id IN ('88433')";			$Compagnie = '88433-Polo Park';			break;	
    case  7: $Userid =  " orders.user_id IN ('88435')";         $Compagnie = '88435-West Edmonton';		break;  
    case  8: $Userid =  " orders.user_id IN ('88438')";    		$Compagnie = '88438-Metrotown';		 	break;
    case  9: $Userid =  " orders.user_id IN ('88439')";			$Compagnie = '88439-Langley';		 	break;
    case 10: $Userid =  " orders.user_id IN ('88440')";         $Compagnie = '88440-Rideau'; 			break;
    case 11: $Userid =  " orders.user_id IN ('88444')";     	$Compagnie = '88444-Mayfair';		 	break; 	
}//End Switch

    //PARTIE ANNÉE EN COURS
    $QueryRepriseAnneeEnCours ="SELECT COUNT(order_num)  as NbrRepriseAnneeEnCours, sum(order_total) as ValeurReprisesAnneeEnCours
    FROM ORDERS 	WHERE redo_order_num IS NOT NULL
    AND $Userid
    AND redo_reason_id IN ($reasonsIds)
    AND order_date_processed BETWEEN '$date1_AnneeEnCours' AND '$date2_AnneeEnCours'";	
    //echo '<br>QueryRepriseAnneeEnCours<br>' .     $QueryRepriseAnneeEnCours . '<br>';
    $ResultRepriseAnneeEnCours  	= mysqli_query($con,$QueryRepriseAnneeEnCours)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
    $DataNbrRepriseAnneeEnCours     = mysqli_fetch_array($ResultRepriseAnneeEnCours,MYSQLI_ASSOC);
    
    $QueryVenteAnneeEnCours ="SELECT COUNT(order_num)  as NbrVenteAnneeEnCours
    FROM ORDERS 	WHERE redo_order_num IS NULL 	AND $Userid
    AND order_date_processed BETWEEN '$date1_AnneeEnCours' AND '$date2_AnneeEnCours'";	
    //echo '<br>QueryRepriseAnneeEnCours<br>' .     $QueryVenteAnneeEnCours . '<br>';
    $ResultVenteAnneeEnCours  	 = mysqli_query($con,$QueryVenteAnneeEnCours)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
    $DataNbrVenteAnneeEnCours    = mysqli_fetch_array($ResultVenteAnneeEnCours,MYSQLI_ASSOC);
    $PourcentageRepriseAnneeEnCours =  ($DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours]/$DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours])*100;
    $PourcentageRepriseAnneeEnCours=money_format('%.2n',$PourcentageRepriseAnneeEnCours);
    $ValeurReprisesAnneeEnCours=money_format('%.2n',$DataNbrRepriseAnneeEnCours[ValeurReprisesAnneeEnCours]);
    
    
    //PARTIE AN DERNIER
    $QueryRepriseAnDernier ="SELECT COUNT(order_num)  as NbrRepriseAnDernier, sum(order_total) as ValeurReprisesAnDernier
    FROM ORDERS
    WHERE redo_order_num IS NOT NULL 	AND $Userid
    AND redo_reason_id IN ($reasonsIds)
    AND order_date_processed BETWEEN '$date1_AnneeDernier' AND '$date2_AnneeDernier'";	
    //echo '<br>QueryRepriseAnDernier<br>' .     $QueryRepriseAnDernier . '<br>';
    $ResultRepriseAnDernier  	= mysqli_query($con,$QueryRepriseAnDernier)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
    $DataNbrRepriseAnDernier     = mysqli_fetch_array($ResultRepriseAnDernier,MYSQLI_ASSOC);
    
    $QueryVenteAnDernier ="SELECT COUNT(order_num)  as NbrVenteAnDernier
    FROM ORDERS 	WHERE redo_order_num IS NULL 	AND $Userid
    AND order_date_processed BETWEEN '$date1_AnneeDernier' AND '$date2_AnneeDernier'";	
    //echo '<br>QueryVenteAnDernier<br>' .     $QueryVenteAnDernier . '<br>';
    $ResultVenteAnDernier  	 	 = mysqli_query($con,$QueryVenteAnDernier)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
    $DataNbrVenteAnDernier    	 = mysqli_fetch_array($ResultVenteAnDernier,MYSQLI_ASSOC);
    $PourcentageRepriseAnDernier =  ($DataNbrRepriseAnDernier[NbrRepriseAnDernier]/$DataNbrVenteAnDernier[NbrVenteAnDernier])*100;
    $PourcentageRepriseAnDernier = money_format('%.2n',$PourcentageRepriseAnDernier);
    $ValeurReprisesAnDernier	 = money_format('%.2n',$DataNbrRepriseAnDernier[ValeurReprisesAnDernier]);

    
    
    //PARTIE COMPARAISON, SI DES DATES ONT ÉTÉ ENTRÉES
    if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
        //Partie Comparaison avec [SI renseignée]
        $QueryRepriseComparaison ="SELECT COUNT(order_num) as NbrRepriseComparaison, sum(order_total) as ValeurRepriseComparaison
        FROM ORDERS 		WHERE redo_order_num IS NOT NULL 	AND $Userid
        AND redo_reason_id IN ($reasonsIds)
        AND order_date_processed BETWEEN '$date3_AnneeEnCours' AND '$date4_AnneeEnCours'";	
        //echo '<br>QueryRepriseComparaison<br>' .     $QueryRepriseComparaison . '<br>';
        $ResultRepriseComparaison  	= mysqli_query($con,$QueryRepriseComparaison)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
        $DataNbrRepriseComparaison     = mysqli_fetch_array($ResultRepriseComparaison,MYSQLI_ASSOC);
        
        $QueryVenteComparaison ="SELECT COUNT(order_num)  as NbrVenteComparaison
        FROM ORDERS 	WHERE redo_order_num IS NULL 	AND $Userid
        AND order_date_processed BETWEEN '$date3_AnneeEnCours' AND '$date4_AnneeEnCours'";	
        //echo '<br>QueryVenteComparaison<br>' .     $QueryVenteComparaison . '<br>';
        $ResultVenteComparaison  	 = mysqli_query($con,$QueryVenteComparaison)		or die  ('I cannot select items 1 because: ' . mysqli_error($con));
        $DataNbrVenteComparaison    = mysqli_fetch_array($ResultVenteComparaison,MYSQLI_ASSOC);
        $PourcentageRepriseComparaison =  ($DataNbrRepriseComparaison[NbrRepriseComparaison]/$DataNbrVenteComparaison[NbrVenteComparaison])*100;
        $PourcentageRepriseComparaison=money_format('%.2n',$PourcentageRepriseComparaison);
        $ValeurReprisesComparaison	 = money_format('%(#10n', $DataNbrRepriseComparaison[ValeurRepriseComparaison]);
    }//END IF
    


    //Période sélectionnée
    $CompteurReprisesGlobal 		+= $DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours];
    $CompteurValeurReprisesGlobal 	+= $ValeurReprisesAnneeEnCours;
    $CompteurVentesGlobal 			+= $DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours];
    
    //An Dernier
    $CompteurReprisesGlobal_An_Dernier 		+= $DataNbrRepriseAnDernier[NbrRepriseAnDernier];
    $CompteurValeurReprisesGlobalAn_Dernier += $ValeurReprisesAnDernier;
    $CompteurVentesGlobalAn_Dernier 		+= $DataNbrVenteAnDernier[NbrVenteAnDernier];
    
    
    //Date de comparaison
    $CompteurReprisesGlobal_Comparaison  		+= $DataNbrRepriseComparaison[NbrRepriseComparaison];
    $CompteurValeurReprisesGlobal_Comparaison 	+= $ValeurReprisesComparaison;
    $CompteurVentesGlobalComparaison 			+= $DataNbrVenteComparaison[NbrVenteComparaison];



    $message.="
    <tr>
        <td bgcolor=\"#c0c2ce\" align=\"right\"><b>$Compagnie</th>
        <td bgcolor=\"#00ff83\" align=\"center\">$DataNbrRepriseAnneeEnCours[NbrRepriseAnneeEnCours]</td>
        <td bgcolor=\"#00ff83\" align=\"center\">$ValeurReprisesAnneeEnCours$</td>
        <td bgcolor=\"#00ff83\" align=\"center\">$DataNbrVenteAnneeEnCours[NbrVenteAnneeEnCours]</td>
        <td bgcolor=\"#00ff83\" align=\"center\">$PourcentageRepriseAnneeEnCours%</td>
        <td bgcolor=\"#99c7f7\" align=\"center\">$DataNbrRepriseAnDernier[NbrRepriseAnDernier]</td>
        <td bgcolor=\"#99c7f7\" align=\"center\">$ValeurReprisesAnDernier$</td>
        <td bgcolor=\"#99c7f7\" align=\"center\">$DataNbrVenteAnDernier[NbrVenteAnDernier]</td>
        <td bgcolor=\"#99c7f7\" align=\"center\">$PourcentageRepriseAnDernier%</td>";
        
    if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
    $message.="
        <td bgcolor=\"#83d0c9\" align=\"center\">$DataNbrRepriseComparaison[NbrRepriseComparaison]</td>
        <td bgcolor=\"#83d0c9\" align=\"center\">$ValeurReprisesComparaison$</td>
        <td bgcolor=\"#83d0c9\" align=\"center\">$DataNbrVenteComparaison[NbrVenteComparaison]</td>
        <td bgcolor=\"#83d0c9\" align=\"center\">$PourcentageRepriseComparaison%</td>";
}	
        
        
        
        
    $message.="</tr>";


}//END FOR



//TOTAUX
$message.="
    <tr>
        <td bgcolor=\"#c0c2ce\" align=\"right\"><b>TOTAUX</b></th>
        <td bgcolor=\"#00ff83\" align=\"center\"><b>$CompteurReprisesGlobal</b></td>
        <td bgcolor=\"#00ff83\" align=\"center\"><b>$CompteurValeurReprisesGlobal$</b></td>
        <td bgcolor=\"#00ff83\" align=\"center\"><b>$CompteurVentesGlobal</b></td>
        <td bgcolor=\"#00ff83\" align=\"center\"><b>-</b></td>
        <td bgcolor=\"#99c7f7\" align=\"center\"><b>$CompteurReprisesGlobal_An_Dernier</b></td>
        <td bgcolor=\"#99c7f7\" align=\"center\"><b>$CompteurValeurReprisesGlobalAn_Dernier$</b></td>
        <td bgcolor=\"#99c7f7\" align=\"center\"><b>$CompteurVentesGlobalAn_Dernier</b></td>
        <td bgcolor=\"#99c7f7\" align=\"center\"><b>-</b></td>";
    
    if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
        $message.="
        <td bgcolor=\"#83d0c9\" align=\"center\"><b>$CompteurReprisesGlobal_Comparaison</b></td>
        <td bgcolor=\"#83d0c9\" align=\"center\"><b>$CompteurValeurReprisesGlobal_Comparaison$</b></td>
        <td bgcolor=\"#83d0c9\" align=\"center\"><b>$CompteurVentesGlobalComparaison</b></td>
        <td bgcolor=\"#83d0c9\" align=\"center\"><b>-</b></td>";
    }//END IF





if ($date3_AnneeEnCours<>'' && $date4_AnneeEnCours<>''){
$message.="
    <tr>
        <td colspan=\"13\" bgcolor=\"#cccccc\" align=\"left\">";
}else{
    $message.="
    <tr>
        <td colspan=\"9\" bgcolor=\"#cccccc\" align=\"left\">";
}//END IF	
$message.="<b>*N.B.</b>The redo reasons that are includes in this report are:<br><br>";

$QueryRaisonsReprise = "SELECT * FROM redo_reasons WHERE redo_reason_id IN ($reasonsIds) ORDER BY redo_reason_en";
$ResultRaisonsReprise = mysqli_query($con,$QueryRaisonsReprise) or die ('I cannot select reasons items because: ' . mysqli_error($con));

while ($DataRaisonsReprise = mysqli_fetch_array($ResultRaisonsReprise,MYSQLI_ASSOC)){ 
  $message.=$DataRaisonsReprise['redo_reason_en']."<br />";
}

$message.="</td></tr></table><br><br>";

// Not sending an email if we are exporting
if(!$isExporting){
  $to_address = array('rapports@direct-lens.com');	

  $curTime      = date("m-d-Y");	
  $from_address = 'donotreply@entrepotdelalunette.com';

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
}

echo $message;
         
if(!$isExporting){ ?>
   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
<?php } ?>
  </body>
</html>
*/