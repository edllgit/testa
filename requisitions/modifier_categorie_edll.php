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

if ($_POST[editCategory] == "Sauvegarder"){
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$cat_id			= $_POST[cat_id];
	$cat_nom_en 	=  mysqli_real_escape_string($con, $_POST[cat_nom_en]);
	$cat_nom_fr 	=  mysqli_real_escape_string($con, $_POST[cat_nom_fr]);
	$cat_status 	=  mysqli_real_escape_string($con, $_POST[cat_status]);
	
	$queryMAJCategorie= "UPDATE categories
	SET 
	cat_nom_en 	= '$cat_nom_en',
	cat_nom_fr 	= '$cat_nom_fr',
	cat_status  =  '$cat_status'
	WHERE 		cat_id = $cat_id";
	//echo '<br><br><br>Query'.$queryMAJCategorie. '<br>';
	$resultMajCategorie=mysqli_query($con,$queryMAJCategorie)		or die  ('I cannot select items because: ' . mysqli_error($con));
	echo '<br><br><br>Catégorie mise à jour avec succès.<br>';
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

    <title>Modifier un produit EDLL</title>
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
  <form name="modification_categorie_edll" method="post" action="modifier_categorie_edll.php">
   

  <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">

 		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM categories WHERE cat_id = $_REQUEST[cat_id]  AND cat_edll_ou_hbc='edll' ORDER BY cat_nom_fr DESC";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);

?>

<input name="cat_id" type="hidden" id="cat_id" value="<?php echo "$listItem[cat_id]"; ?>" />

<b>Modification d'une catégorie EDLL</b>

<table  border="0" width="600"  class="TextSize">
<tr><td>&nbsp;</td></tr>

<tr>
<th># Categorie:</th> 
<td><?php echo $listItem[cat_id]; ?></td>
</tr>
<tr><td>&nbsp;</td></tr>

<tr>
<th>Nom FR:</th>
 <td><textarea name="cat_nom_fr" cols="45" rows="1" class="formText" id="cat_nom_fr"><?php echo "$listItem[cat_nom_fr]"; ?></textarea></td>

<tr><td>&nbsp;</td></tr>

<tr>
 <th>Nom EN:</th>
 <td><textarea name="cat_nom_en" cols="45" rows="1" class="formText" id="cat_nom_en"><?php echo "$listItem[cat_nom_en]"; ?></textarea></td>
 
 <tr><td>&nbsp;</td></tr>
 
 
 <tr>
 <th>Statut:</th>
 <td>
<select name="cat_status">
	<option  <?php if ($listItem[cat_status] == '1') echo 'selected'; ?> value="1">Actif</option>
	<option  <?php if ($listItem[cat_status] == '0') echo 'selected'; ?> value="0">Inactif</option>
</select></td>

<tr><td>&nbsp;</td></tr>


<tr>
<td align="center" colspan="2"><input type="submit" class="formText" name="editCategory" id="editCategory" value="Sauvegarder" /></td>
</tr>

             
    </div> <!-- /container -->

<a href="gestion_categories_edll.php">Retour à la liste des catégories EDLL</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>