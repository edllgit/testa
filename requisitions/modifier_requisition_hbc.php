<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1'); 
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié *
	exit();
}


if ($_POST[SupprimerItem] == "Supprimer"){
 $ID_Requisition = $_POST[id_a_effacer]; 
 
	//On récupère les valeurs à supprimer 
	//Afficher les valeur reçues
	//var_dump($_POST);
	//On efface les tuples demandés
	
	
	foreach ($ID_Requisition as $value) {
		//echo "<br>ID a effacer: ".$value." <br>";
		$queryRemoveItem= "DELETE FROM produits_requisitions 	WHERE 		prod_req_id = $value";
		$resultRemoveItem=mysqli_query($con,$queryRemoveItem)		or die  ('I cannot remove items because: ' . mysqli_error($con));
		//echo '<br>'. $queryRemoveItem.'<br>';
	}
	//echo '<br><br><br>Réquisition mise à jour avec succès.<br>';
	//Rediriger vers le détail de la réquisition
	$REQ_ID = $_REQUEST[req_id];
	header("Location: modifier_requisition_hbc.php?req_id=$REQ_ID");
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

    <title>Réquisition HBC #<?php echo $ID_Requisition; ?></title>
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
  <form name="modification_requisition_hbc" method="post" action="modifier_requisition_hbc.php">
   

  <?php  include("inc/menu.inc.php");
  $ID_Requisition = $_REQUEST[req_id]; 
  $PathAjouterItem="ajout_item_requisition_hbc.php?req_id=".$_REQUEST[req_id];
  
  //Afficher les produits créés 
$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $ID_Requisition";

$rptQuery="SELECT * FROM produits_requisitions, produits
			WHERE  produits_requisitions.req_id = $ID_Requisition
			AND produits.prod_id=produits_requisitions.prod_id
			ORDER BY fournisseur";
//echo $rptQuery . '<br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because:   '.$rptQuery . mysqli_error($con));
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot select items because:   '.$QueryInfoRequisition . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);
  ?>
	  
<div class="container">

<b>Réquisition HBC #<?php echo $ID_Requisition; ?></b>    
<?php if ($DataInfoRequi[req_status]=='panier'){ ?>
<a href="<?php echo $PathAjouterItem?>">Ajouter un Item</a>  
<a href="simulation_courriel_requisition.php?req_id=<?php echo $_REQUEST[req_id]; ?>"><b>Simuler</b> les courriels qui seraient envoyés pour cette réquisition</a>
<?php }?>

<table width="600" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<tr>
<td align="center"><b>Client</b></td>
<td align="center"><b>Numéro de Réquisition</b></td>
<td align="center"><b>Date de création</b></td>
<td align="center"><b>Date d'envoie au fournisseur</b></td>
<td align="center"><b>Statut</b></td>
</tr>

<?php   
$TOTAL = 0;///Initilaise le compteur


if ($DataInfoRequi[req_date_envoie_fournisseur]=='0000-00-00 00:00:00')
$DataInfoRequi[req_date_envoie_fournisseur]='';	

echo "<tr>
<td align=\"center\">$DataInfoRequi[user_id]</td>
<td align=\"center\">#$ID_Requisition</td>
<td align=\"center\">$DataInfoRequi[req_date_creation]</td>
<td align=\"center\">$DataInfoRequi[req_date_envoie_fournisseur]</td>
<td align=\"center\">$DataInfoRequi[req_status]</td>
</tr></table>";
?>
<br><br>
<table width="1350" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<tr>
<td align="center"><b>Fournisseur</b></td>
<?php if ($DataInfoRequi[req_status]=='panier'){ ?>
<td align="center"><b>Sélectionner</b></td>
<?php } ?>
<td align="center"><b>Produit</b></td>
<td align="center"><b>Question supplémentaire</b></td>

<td align="center"><b>Question choix multiple</b></td>

<td align="center"><b>Nombre d'item inclus (par commande)</b></td>
<td align="center"><b>Qtée</b></td>
<td align="center"><b>Prix ind.</b></td>
<td align="center"><b>Sous-total</b></td>
</tr>
<?php

$MontantTotalFournisseur9  = 0;
$MontantTotalFournisseur10 = 0;

while($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
$SousTotal=$listItem[prod_req_quantite] * $listItem[prod_req_prix_individuel];
$SousTotal=money_format('%.2n',$SousTotal);
	
$queryFournisseur="SELECT fourn_nom_fr,fourn_montant_achat_min FROM fournisseurs WHERE fourn_id= $listItem[fournisseur]";
$resultFournisseur =mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because:   '.$queryFournisseur . mysqli_error($con));
$DataFournisseur = mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$prod_req_id = $listItem[prod_req_id];
$MontantAchatMinimum = $DataFournisseur[fourn_montant_achat_min];

switch($listItem[fournisseur]){
	case 9: $MontantTotalFournisseur9+=$SousTotal;  $MontantMinimumFournisseur9  = $MontantAchatMinimum; 	$NomFournisseur9  = $DataFournisseur[fourn_nom_fr];	break;
	case 10:$MontantTotalFournisseur10+=$SousTotal; $MontantMinimumFournisseur10 = $MontantAchatMinimum;	$NomFournisseur10 = $DataFournisseur[fourn_nom_fr];	break;	
}//End Switch


	echo "
	<tr>
	<td align=\"center\">$DataFournisseur[fourn_nom_fr]</td>";
	
	 if ($DataInfoRequi[req_status]=='panier'){ 
		echo "<td align=\"center\"><input type=\"checkbox\" id=\"id_a_effacer[]\" value=\"$prod_req_id\" name=\"id_a_effacer[]\" ></td>";
	 } 
	 
	$QueryNbrItemInclus  = "SELECT  prod_nombre_item_inclus FROM produits WHERE prod_id= (SELECT prod_id FROM  produits_requisitions WHERE prod_req_id=$prod_req_id)";
	$ResultNbrItemInclus = mysqli_query($con,$QueryNbrItemInclus)		or die  ('I cannot remove items because: ' . mysqli_error($con));
	$DataNbrItemInclus   = mysqli_fetch_array($ResultNbrItemInclus,MYSQLI_ASSOC);
	
	echo "
	<td align=\"center\">$listItem[prod_description_fr]</td>
	<td align=\"center\">$listItem[prod_question_supplementaire]";
	
	if ($DataInfoRequi[req_status]=='panier'){ 
		//On laisse modifier les réponses puisque la réquisition n'a pas encore été traitée
		echo "<a href=\"modifier_qtee_hbc.php?prod_req_id=$prod_req_id\">&nbsp;&nbsp;<b>$listItem[prod_req_reponse_question_supplementaire]</b></a></td>";
		echo "<td align=\"center\">$listItem[prod_question_choix_reponse]&nbsp;&nbsp;<b><a href=\"modifier_qtee_hbc.php?prod_req_id=$prod_req_id\">$listItem[prod_req_reponse_question_choix_multiple]</a></b></td>";
		echo "<td align=\"center\">$DataNbrItemInclus[prod_nombre_item_inclus]</td>";
		echo "<td align=\"center\"><a href=\"modifier_qtee_hbc.php?prod_req_id=$prod_req_id\">$listItem[prod_req_quantite]</a></td>";
	}else{
		//La réquisition a déja été traité, on ne laisse donc pas l'option de la modifier
		echo "<b>$listItem[prod_req_reponse_question_supplementaire]</b></td>";
		echo "<td align=\"center\">$listItem[prod_question_choix_reponse]&nbsp;&nbsp;<b>$listItem[prod_req_reponse_question_choix_multiple]</b></td>";
		echo "<td align=\"center\">$DataNbrItemInclus[prod_nombre_item_inclus]</td>";
		echo "<td align=\"center\">$listItem[prod_req_quantite]</td>";
	}
	
	echo "<td align=\"center\">$listItem[prod_req_prix_individuel]$</td>
	<td align=\"center\">$SousTotal$</td>
	</tr>";
	$TOTAL += $SousTotal;
	$TOTAL=money_format('%.2n',$TOTAL);
}//END WHILE


$MontantTotalFournisseur1=money_format('%.2n',$MontantTotalFournisseur1);
$MontantTotalFournisseur2=money_format('%.2n',$MontantTotalFournisseur2);
$MontantTotalFournisseur3=money_format('%.2n',$MontantTotalFournisseur3);
$MontantTotalFournisseur4=money_format('%.2n',$MontantTotalFournisseur4);
$MontantTotalFournisseur5=money_format('%.2n',$MontantTotalFournisseur5);
$MontantTotalFournisseur6=money_format('%.2n',$MontantTotalFournisseur6);
$MontantTotalFournisseur7=money_format('%.2n',$MontantTotalFournisseur7);



if ($MontantTotalFournisseur9>0){
	if ($MontantTotalFournisseur9 < $MontantMinimumFournisseur9){
		$SommeManquante = $MontantMinimumFournisseur9-$MontantTotalFournisseur9;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur9 . '$  pour ' . $NomFournisseur9.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur9 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

if ($MontantTotalFournisseur10>0){
	if ($MontantTotalFournisseur10 < $MontantMinimumFournisseur10){
		$SommeManquante = $MontantMinimumFournisseur10-$MontantTotalFournisseur10;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur10 . '$  pour ' . $NomFournisseur10.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur10 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

echo "
<tr>";
if ($DataInfoRequi[req_status]=='panier')
echo "<td> </td><td align=\"center\"><input type=\"submit\" class=\"formText\" name=\"SupprimerItem\" id=\"SupprimerItem\" value=\"Supprimer\" /></td>";
echo "<td align=\"right\" colspan=\"8\"><b>TOTAL: $TOTAL$</b></td>
<tr></table>";
?>



<?php if ($DataInfoRequi[req_status]<>'panier'){
	//Le status n'Est pas panier, la commande a donc été traitée..on affiche l'historique d'envoie de courriels

$queryHistorique="SELECT * FROM historique_envoie_courriel WHERE req_id= $ID_Requisition";
$resultHistorique =mysqli_query($con,$queryHistorique)		or die  ('I cannot select items because:   '.$queryHistorique . mysqli_error($con));
$TOTAL = 0;///Initilaise le compteur

echo "<br><br><b>Historique d'envoie des courriels</b>
	<table width=\"1350\" cellpadding=\"2\" border=\"1\"  cellspacing=\"0\" class=\"TextSize\">	
	<tr>
	<td width=\"100\" align=\"center\"><b>Date d'envoie</b></td>
	<td align=\"center\"><b>Fournisseur</b></td>
	<td align=\"center\"><b>Courriel</b></td>
	<td align=\"center\"><b>Résultat</b></td>
	<td align=\"center\"><b>Explication</b></td>
	<td width=\"100\" align=\"center\"><b>Message ID</b></td>
	<td align=\"center\"><b>Request ID</b></td>
	</tr>";
	
while ($DataHistorique = mysqli_fetch_array($resultHistorique ,MYSQLI_ASSOC)){

	
	if ($DataHistorique[hist_resultat_envoie]==1){
		$Resultat="Succès";	
	}else{
		$Resultat="Échec";	
	}
	
	echo "<tr>
	<td width=\"100\" align=\"center\">$DataHistorique[hist_date_envoie]</td>
	<td align=\"center\">$DataHistorique[fourn_nom_fr]</td>
	<td align=\"center\">$DataHistorique[fourn_courriel]</td>
	<td align=\"center\"><b>$Resultat</b></td>
	<td align=\"center\">$DataHistorique[hist_explication]</td>
	<td width=\"100\" align=\"center\">$DataHistorique[hist_message_id]</td>
	<td align=\"center\">$DataHistorique[hist_request_id]</td>
	</tr>";
	
}//END WHILE
echo "</table>";

}//END IF

?>	
	
<input name="req_id" type="hidden" id="req_id" value="<?php echo "$ID_Requisition"; ?>" />

</div> <!-- /container -->
<br><br>
	

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/