<?php
//Afficher toutes les erreurs/avertissements
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once("../../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
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
	header("Location: panier.php?req_id=$REQ_ID");
	exit();
}

	$datetime = date("Y-m-d H:i:s");
	$BloquerSysteme='non';
	$jourdumois = date("d");
	
	//$jourdumois = '15';
	switch($jourdumois){
		case '01':	$BloquerSysteme="oui";	break;	
		case '15':	$BloquerSysteme="oui";	break;		
	}
//echo '<br>BloquerSysteme:'. $BloquerSysteme. ".  <br>Jour du mois:$jourdumois";

?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Réquisition Groupe Vision Optique</title>
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
  <form name="panier" method="post" action="panier.php">

  <?php  include("inc/menu.inc.php");
  $ID_Requisition = $_REQUEST[req_id]; 
  $PathAjouterItem="ajout_item_requisition.php?req_id=".$_REQUEST[req_id];
  
  //Afficher les produits créés 
$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $ID_Requisition";

$rptQuery="SELECT * FROM produits_requisitions, produits
			WHERE  produits_requisitions.req_id = $ID_Requisition
			AND produits.prod_id=produits_requisitions.prod_id
			ORDER BY fournisseur";
//echo $rptQuery . '<br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because:   '.$rptQuery . mysqli_error($con));
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot select items because:   '.$QueryInfoRequisition . mysqli_error($con));
$NbrResultat=mysqli_num_rows($rptResult);
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);
?>
	  
<div class="container">

<div align="center"><img  width="400" src="img/entete.jpg"></div>

<div align="center"><img width="450" src="img/procedes.jpg" style="border:5px double black;"></div>


<br> 

<b>Votre panier</b> 


<?php if ($BloquerSysteme<>'oui'){ ?>
	<h3><a href="<?php echo $PathAjouterItem?>">Ajouter un Item</a></h3>  
<?php }else{ ?>
  <h3>Système indisponible le 1er et le 15 de chaque mois. </h3>  
<?php }?>


<?php if ($DataInfoRequi[req_status]=='panier'){ ?>
 
<?php if ($NbrResultat==0){ ?>
Votre panier est présentement vide. 
<?php }?>

<?php }?>

<?php   
$TOTAL = 0;///Initilaise le compteur

if ($NbrResultat>0){
?>
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
$MontantTotalFournisseur1 = 0;
$MontantTotalFournisseur2 = 0;
$MontantTotalFournisseur3 = 0;
$MontantTotalFournisseur4 = 0;
$MontantTotalFournisseur5 = 0;
$MontantTotalFournisseur6 = 0;
$MontantTotalFournisseur7 = 0;

while($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
$SousTotal=$listItem[prod_req_quantite] * $listItem[prod_req_prix_individuel];
$SousTotal=money_format('%.2n',$SousTotal);
	
$queryFournisseur="SELECT fourn_nom_fr,fourn_montant_achat_min FROM fournisseurs WHERE fourn_id= $listItem[fournisseur]";
//echo '<br>queryF:'. $queryFournisseur;
$resultFournisseur   = mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because:   '.$queryFournisseur . mysqli_error($con));
$DataFournisseur 	 = mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$prod_req_id 		 = $listItem[prod_req_id];
$MontantAchatMinimum = $DataFournisseur[fourn_montant_achat_min];

switch($listItem[fournisseur]){
	case 1:$MontantTotalFournisseur1+=$SousTotal; $MontantMinimumFournisseur1 = $MontantAchatMinimum; 	$NomFournisseur1 = $DataFournisseur[fourn_nom_fr];	break;
	case 2:$MontantTotalFournisseur2+=$SousTotal; $MontantMinimumFournisseur2 = $MontantAchatMinimum;	$NomFournisseur2 = $DataFournisseur[fourn_nom_fr];	break;	
	case 3:$MontantTotalFournisseur3+=$SousTotal; $MontantMinimumFournisseur3 = $MontantAchatMinimum;	$NomFournisseur3 = $DataFournisseur[fourn_nom_fr];	break;
	case 4:$MontantTotalFournisseur4+=$SousTotal; $MontantMinimumFournisseur4 = $MontantAchatMinimum;	$NomFournisseur4 = $DataFournisseur[fourn_nom_fr];	break;	
	case 5:$MontantTotalFournisseur5+=$SousTotal; $MontantMinimumFournisseur5 = $MontantAchatMinimum;	$NomFournisseur5 = $DataFournisseur[fourn_nom_fr];	break;	
	case 6:$MontantTotalFournisseur6+=$SousTotal; $MontantMinimumFournisseur6 = $MontantAchatMinimum;	$NomFournisseur6 = $DataFournisseur[fourn_nom_fr];	break;	
	case 7:$MontantTotalFournisseur7+=$SousTotal; $MontantMinimumFournisseur7 = $MontantAchatMinimum;	$NomFournisseur7 = $DataFournisseur[fourn_nom_fr];	break;	
}//End Switch



	echo "
	<tr>
	<td align=\"center\">$DataFournisseur[fourn_nom_fr]</td>";
	
	if ($BloquerSysteme=='oui'){
		$Desactiver = " disabled "; 
	}else{
		$Desactiver = " "; 
	}//END IF
	
	
	 if ($DataInfoRequi[req_status]=='panier'){ 
		echo "<td align=\"center\"><input type=\"checkbox\" $Desactiver id=\"id_a_effacer[]\" value=\"$prod_req_id\" name=\"id_a_effacer[]\" ></td>";
	 } 
	
	
	
	$QueryNbrItemInclus  = "SELECT  prod_nombre_item_inclus FROM produits WHERE prod_id= (SELECT prod_id FROM  produits_requisitions WHERE prod_req_id=$prod_req_id)";
	//echo '<br><br>'.$QueryNbrItemInclus;
	$ResultNbrItemInclus = mysqli_query($con,$QueryNbrItemInclus)		or die  ('I cannot remove items because: ' . mysqli_error($con));
	$DataNbrItemInclus   = mysqli_fetch_array($ResultNbrItemInclus,MYSQLI_ASSOC);
	
	echo "
	<td align=\"center\">$listItem[prod_description_fr]</td>
	
	<td align=\"center\">$listItem[prod_question_supplementaire]";
	
	
	
	if (($DataInfoRequi[req_status]=='panier') && ($BloquerSysteme<>'oui')){ 
		//On laisse modifier les réponses puisque la réquisition n'a pas encore été traitée
		echo "<a href=\"modifier_qtee.php?prod_req_id=$prod_req_id\">&nbsp;&nbsp;<b>$listItem[prod_req_reponse_question_supplementaire]</b></a></td>";
		echo "<td align=\"center\">$listItem[prod_question_choix_reponse]&nbsp;&nbsp;<b><a href=\"modifier_qtee.php?prod_req_id=$prod_req_id\">$listItem[prod_req_reponse_question_choix_multiple]</a></b></td>";
		echo "<td align=\"center\">$DataNbrItemInclus[prod_nombre_item_inclus]</td>";
		echo "<td align=\"center\"><a href=\"modifier_qtee.php?prod_req_id=$prod_req_id\">$listItem[prod_req_quantite]</a></td>";
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

if ($MontantTotalFournisseur1>0){
	if ($MontantTotalFournisseur1 < $MontantMinimumFournisseur1){
		$SommeManquante = $MontantMinimumFournisseur1-$MontantTotalFournisseur1;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur1 . '$  pour ' . $NomFournisseur1.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur1 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

if ($MontantTotalFournisseur2>0){
	if ($MontantTotalFournisseur2 < $MontantMinimumFournisseur2){
		$SommeManquante = $MontantMinimumFournisseur2-$MontantTotalFournisseur2;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur2 . '$  pour ' . $NomFournisseur2.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur2 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

if ($MontantTotalFournisseur3>0){
	if ($MontantTotalFournisseur3 < $MontantMinimumFournisseur3){
		$SommeManquante = $MontantMinimumFournisseur3-$MontantTotalFournisseur3;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur3 . '$  pour ' . $NomFournisseur3.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur3 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

if ($MontantTotalFournisseur4>0){
	if ($MontantTotalFournisseur4 < $MontantMinimumFournisseur4){
		$SommeManquante = $MontantMinimumFournisseur4-$MontantTotalFournisseur4;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur4 . '$  pour ' . $NomFournisseur4.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur4 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}

if ($MontantTotalFournisseur5>0){
	if ($MontantTotalFournisseur5 < $MontantMinimumFournisseur5){
		$SommeManquante = $MontantMinimumFournisseur5-$MontantTotalFournisseur5;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur5 . '$  pour ' . $NomFournisseur5.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur5 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}


if ($MontantTotalFournisseur6>0){
	if ($MontantTotalFournisseur6 < $MontantMinimumFournisseur6){
		$SommeManquante = $MontantMinimumFournisseur6-$MontantTotalFournisseur6;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur6 . '$  pour ' . $NomFournisseur6.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur6 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}


if ($MontantTotalFournisseur7>0){
	if ($MontantTotalFournisseur7 < $MontantMinimumFournisseur7){
		$SommeManquante = $MontantMinimumFournisseur7-$MontantTotalFournisseur7;
		$SommeManquante=money_format('%.2n',$SommeManquante);
		echo '<p><b>Le montant minimum de ' . $MontantMinimumFournisseur7 . '$  pour ' . $NomFournisseur7.' n\'est pas atteint.
			Total actuel: '.$MontantTotalFournisseur7 .'$ </b><u>Vous devez donc ajouter des produits pour une valeur d\'au moins  <b>'. $SommeManquante .'$</b></u></p>';		
	}//END IF	
}


	
$Fournisseur[$listItem[fournisseur]]+= $SousTotal;

	echo "
	<tr>";
	
	if ($BloquerSysteme=='oui'){
		$Desactiver = " disabled "; 
	}else{
		$Desactiver = " "; 
	}//END IF
	
	if ($DataInfoRequi[req_status]=='panier')
	echo "<td> </td><td align=\"center\"><input $Desactiver type=\"submit\" class=\"formText\" name=\"SupprimerItem\" id=\"SupprimerItem\" value=\"Supprimer\" /></td>";
	echo "<td align=\"right\" colspan=\"8\"><b>TOTAL: $TOTAL$</b></td>
	<tr>";
}//END IF
?>

<input name="req_id" type="hidden" id="req_id" value="<?php echo "$ID_Requisition"; ?>" />

</div> <!-- /container -->
<br><br>
	

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>