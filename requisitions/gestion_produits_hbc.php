<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié *
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

    <title>Gestion des Produits HBC</title>
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
  
      <?php  include("inc/menu.inc.php"); ?>
	  
	  
<div class="container">

  <p>&nbsp;</p>  <p>&nbsp;</p> <p>&nbsp;</p> 
  <?php $LargeurColonne=11; ?>   
	 

<table width="1300" cellpadding="2" border="1"  cellspacing="0" class="TextSize">
<tr>
<td colspan="<?php echo $LargeurColonne ?>" align="center"><b>GESTION DES PRODUITS HBC</b></td>
</tr>
<tr><td colspan="<?php echo $LargeurColonne ?>" align="center">&nbsp;</td></tr>

<tr><td colspan="<?php echo $LargeurColonne ?>" align="center"><a href="creer_produit_hbc.php">Créer un produit</a></td></tr>

<tr><td colspan="<?php echo $LargeurColonne ?>" align="center">&nbsp;</td></tr>

<tr>
	<td align="center" colspan="<?php echo $LargeurColonne ?>"><b>Produits créés</b></th>             
</tr>

<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Description</b></td>
	<td align="center"><b>Code fournisseur</b></td>	
	<td align="center"><b>Nombre d'item inclus</b></td>		
	<td align="center"><b>Prix unitaire</b></td>
	<td align="center"><b>Catégorie</b></td>
	<td align="center"><b>Fournisseur</b></td>
	<td align="center"><b>Statut</b></td>
	<td align="center"><b>Date de création</b></td>
	<td align="center"><b>Question supplémentaire</b></td>
	<td align="center"><b>Question à choix réponse</b></td>
</tr>
		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM produits WHERE prod_edll_ou_hbc='hbc' ORDER BY prod_description_fr";
//echo $rptQuery . '<br><br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);


while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
if ($listItem[prod_status]=='1')
$Prod_status = 'Actif';
else
$Prod_status = 'Inactif';

$queryCategorie = "SELECT * FROM categories WHERE cat_edll_ou_hbc='hbc' AND cat_id = $listItem[categorie]";
$resultCategorie= mysqli_query($con,$queryCategorie)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataCategorie	= mysqli_fetch_array($resultCategorie,MYSQLI_ASSOC);
$cat_nom_fr 	= $DataCategorie[cat_nom_fr];

$queryFournisseur 	= "SELECT * FROM fournisseurs WHERE fourn_edll_ou_hbc='hbc' AND fourn_id = $listItem[fournisseur]";
$resultFournisseur	= mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because: ' . mysqli_error($con));
$DataFournisseur	= mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$fourn_nom_fr 		= $DataFournisseur[fourn_nom_fr];

	
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_produit_hbc.php?prod_id=$listItem[prod_id]\">$listItem[prod_id]</a></td> 
			<td align=\"center\">$listItem[prod_description_fr]</td> 
			<td align=\"center\">$listItem[prod_code]</td>
			<td align=\"center\">$listItem[prod_nombre_item_inclus]</td>
			<td align=\"center\">$listItem[prod_prix_unitaire]$</td>
			<td align=\"center\">$cat_nom_fr</td>
			<td align=\"center\">$fourn_nom_fr</td>
			<td align=\"center\">$Prod_status</td>
			<td align=\"center\">$listItem[prod_date_creation]</td>
			<td align=\"center\">$listItem[prod_question_supplementaire]</td>
			<td align=\"center\">$listItem[prod_question_choix_reponse]</td>
			</tr>";
}//END WHILE	 
 ?>    
          

    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/