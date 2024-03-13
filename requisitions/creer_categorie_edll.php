<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1'); 
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();
}

if ($_POST[editProduct] == "Sauvegarder"){
	//On récupère les valeurs des différents champs puis on créé le tuple.
	$cat_nom_en =  mysqli_real_escape_string($con, $_POST[cat_nom_en]);
	$cat_nom_fr =  mysqli_real_escape_string($con, $_POST[cat_nom_fr]);

	$queryCreerCategorie= "INSERT INTO categories (cat_nom_en, cat_nom_fr, cat_edll_ou_hbc,  cat_status)	VALUES ('$cat_nom_en','$cat_nom_fr','edll',1)";
	$resultCreerCategorie=mysqli_query($con,$queryCreerCategorie)		or die  ('I cannot select items because:  ' . $queryCreerCategorie . mysqli_error($con));
	echo '<br><br><br>Catégorie créée.<br>';
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

    <title>Créer une catégorie EDLL</title>
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
  <form name="creer_categorie_edll" method="post" action="creer_categorie_edll.php">
   <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">

 <br>
 <b>Créer une catégorie de produit EDLL</b>

	
<table  border="0" width="600"  class="TextSize">
<tr><td>&nbsp;</td></tr>

<tr>
<th>Nom FR:</th>
 <td><textarea name="cat_nom_fr" cols="45" rows="1" class="formText" id="cat_nom_fr"></textarea></td>
</tr>

<tr><td>&nbsp;</td></tr>


<tr>
 <th>Nom EN:</th>
 <td><textarea name="cat_nom_en" cols="45" rows="1" class="formText" id="cat_nom_en"></textarea></td>
</tr>
 
<tr><td>&nbsp;</td></tr>
	
<tr>
<td colspan="2" align="center"><input type="submit" class="formText" name="editProduct" id="editProduct" value="Sauvegarder" /></td>
</tr>

    </div> <!-- /container -->

<a href="gestion_categories_edll.php">Retour à la liste des Catégories EDLL</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>