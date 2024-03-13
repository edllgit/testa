<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1'); 
require_once("../sec_connect_requisitions.inc.php");
include('../phpmailer_email_functions.inc.php');
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();
}
?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Simulation des courriels créés pour la réquisition #<?php echo $ID_Requisition; ?></title>
    <!-- Bootstrap core CSS -->
    <link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
  <br><br><br><br>
  <form name="modification_requisition_edll" method="post" action="modifier_requisition_edll.php">
 
  <?php  include("inc/menu.inc.php");
  $ID_Requisition = $_REQUEST[req_id]; 
  $PathAjouterItem="ajout_item_requisition_edll.php?req_id=".$_REQUEST[req_id];
  ?>
	  
<div class="container">

<b>Simulation des courriels créés pour la réquisition #<?php echo $ID_Requisition; ?></b>       
<table width="550" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	

<tr>
<td align="center"><b>Client</b></td>
<td align="center"><b>Numéro de Réquisition</b></td>
<td align="center"><b>Date de création</b></td>
<td align="center"><b>Statut</b></td>
</tr>

<?php   

//Afficher les produits créés 
$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $ID_Requisition";
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot select items because:   '.$QueryInfoRequisition . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);

$TOTAL = 0;///Initilaise le compteur

echo "<tr>
<td align=\"center\">$DataInfoRequi[user_id]</td>
<td align=\"center\">#$ID_Requisition</td>
<td align=\"center\">$DataInfoRequi[req_date_creation]</td>
<td align=\"center\">$DataInfoRequi[req_status]</td>
</tr>";
?>
<table width="735" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<?php
//Séparer les tuples de produits_requisitions par distinct fournisseur
$queryDistinctFournisseur  = "SELECT distinct fourn_id, fourn_nom_fr,fourn_nom_en, fourn_courriel, fourn_montant_achat_min FROM fournisseurs, produits_requisitions, produits WHERE produits.prod_id = produits_requisitions.prod_id AND produits.fournisseur = fournisseurs.fourn_id and produits_requisitions.req_id=$ID_Requisition";
$resultDistinctFournisseur = mysqli_query($con,$queryDistinctFournisseur)		or die  ('I cannot select items because:   '.$queryDistinctFournisseur.'<br><br>'. mysqli_error($con));

if ($DataInfoRequi[req_status]<>'panier'){
	echo '<br><p><b>Erreur, cette commande à déja été transféré</b></p>';
	exit();
}


while ($DataDistinctFournisseur =mysqli_fetch_array($resultDistinctFournisseur,MYSQLI_ASSOC)){
	$message='';
	//Traiter les fournisseurs un par un
	$Fourn_ID 				 = $DataDistinctFournisseur[fourn_id];
	$Fourn_Nom_FR 			 = $DataDistinctFournisseur[fourn_nom_fr];
	$Fourn_Nom_EN 			 = $DataDistinctFournisseur[fourn_nom_en];
	$Fourn_Courriel 		 = $DataDistinctFournisseur[fourn_courriel];
	$Fourn_Montant_Achat_Min = $DataDistinctFournisseur[fourn_montant_achat_min];
	
	echo '<br><br><br><br><b>Nom du fournisseur</b>:'. $Fourn_Nom_FR;
	echo '   <b>ID:</b> #'. $Fourn_ID.  '  <b>Courriel:</b>'. $Fourn_Courriel. '  <b>Montant achat minimum:</b>'. $Fourn_Montant_Achat_Min . '$';


	$user_id=$DataInfoRequi[user_id];
	//Aller chercher l'adresse d'expédition du magasin qui a fait cette commande dans la BD direct_lens54, cees adresses pourraient aussi être stocké dans la bd requisitions..
	$queryAdresseExpedition="SELECT * FROM succursales WHERE succ_user_id='$user_id'";
	//echo '<br>'. $queryAdresseExpedition;
	$resultAdresseExpedition=mysqli_query($con,$queryAdresseExpedition)		or die  ('I cannot remove items because: ' . mysqli_error($con));
	$DataAdreseExpedition = mysqli_fetch_array($resultAdresseExpedition,MYSQLI_ASSOC);
	
	
	//Bâtir le contenu du courriel
	$message.="<p>Bonjour $Fourn_Nom_FR, <br><br>Ce courriel contient une <b>simulation</b> de commande provenant de la compagnie:<b> $DataAdreseExpedition[succ_nom]</b>
	<br><br>
	L'adresse d'expédition est: <b>$DataAdreseExpedition[succ_adresse]</b>
	<br><br><br>
	Voici le contenu de la commande:<br> </p>
	<table width=\"1250\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">	
	<tr>
	<td align=\"center\"><b>Fournisseur</b></td>
	<td align=\"center\"><b>Code</b></td>
	<td align=\"center\"><b>Produit</b></td>
	<td align=\"center\"><b>Question supplémentaire</b></td>
	<td align=\"center\"><b>Question choix réponse</b></td>
	<td align=\"center\"><b>Qtée</b></td>
	<td align=\"center\"><b>Prix unitaire</b></td>
	<td align=\"center\"><b>Sous-total</b></td>
	</tr>";
	
$rptQuery="SELECT * FROM produits_requisitions, produits
			WHERE  produits_requisitions.req_id = $ID_Requisition
			AND produits.prod_id=produits_requisitions.prod_id
			AND produits.fournisseur=$Fourn_ID
			ORDER BY fournisseur";
//echo $rptQuery . '<br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because:   '.$rptQuery . mysqli_error($con));

	while($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	$SousTotal=$listItem[prod_req_quantite] * $listItem[prod_req_prix_individuel];
	$SousTotal=money_format('%.2n',$SousTotal);
		
		$message.= "
		<tr>
		<td align=\"center\">$Fourn_Nom_FR</td>
		<td align=\"center\">$listItem[prod_code]</td>
		<td align=\"center\">$listItem[prod_description_fr]</td>
		<td align=\"center\">$listItem[prod_question_supplementaire] $listItem[prod_req_reponse_question_supplementaire]</td>
		<td align=\"center\">$listItem[prod_question_choix_reponse] $listItem[prod_req_reponse_question_choix_multiple]</td>
		<td align=\"center\">$listItem[prod_req_quantite]</td>
		<td align=\"center\">$listItem[prod_req_prix_individuel]$</td>
		<td align=\"center\">$SousTotal$</td>
		</tr>";
		// Code, Produit, Qtee, prix individuel, sous-total, total
	}//END WHILE
	
	$queryConfig  = "SELECT * FROM configurations WHERE config_edll_ou_hbc='$DataInfoRequi[req_edll_ou_hbc]'";
	$resultConfig = mysqli_query($con,$queryConfig)		or die  ('I cannot select items because:   '.$queryConfig . mysqli_error($con));
	$DataConfig   = mysqli_fetch_array($resultConfig,MYSQLI_ASSOC);
	$Destinataire = $DataConfig[config_courriel_simulation];//Courriel configuré pour recevoir les simulations par courriel
	
	$message.= '</table><br><br>'. "<p>Pour toute question ou problématique concernant cette commande, veuillez contacter <b>$DataConfig[config_nom_personne_contact]</b> à l'adresse <a href=\"mailto:$DataConfig[config_courriel_personne_contact]\">$DataConfig[config_courriel_personne_contact]</a></p>";
	
	$send_to_address=$Destinataire;
	$curTime= date("Y-m-d");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject="Simulation de Réquisition #$ID_Requisition  $DataAdreseExpedition[succ_nom] $Fourn_Nom_FR $curTime";
	//NE PAS ENVOYER LE COURRIEL AU FOURNISSEUR, ON SIMULE SEULEMENT + copie envoyé à Charles
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	echo '<br><br><br><b>From:</b>'. $from_address;
	echo '<br><b>To:</b>'. $to_address;
	echo '<br><b>Sujet:</b>'. $subject;
	echo '<br><br>'.$message;
}//END WHILE

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
	
?>
<p>
<p><b>Si cette simulation vous convient, vous pouvez <a href="envoie_courriel_requisition.php?req_id=<?php echo "$ID_Requisition"; ?>"> cliquer ici pour procéder à la validation de cette réquisition</a>, qui enverra tous les courriels vu ci-dessus aux fournisseur(s) concerné(s) et  changera le statut de cette réquisition pour traité.</b></p>
<input name="req_id" type="hidden" id="req_id" value="<?php echo "$ID_Requisition"; ?>" />

    </div> <!-- /container -->
<br><br>
	
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>