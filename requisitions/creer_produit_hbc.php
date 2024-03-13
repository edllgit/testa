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

if ($_POST[editProduct] == "Sauvegarder"){
	//On récupère les valeurs des différents champs puis on créé le tuple.
	$prod_id					  =  $_POST[prod_id];
	$prod_description_en 		  =  mysqli_real_escape_string($con, $_POST[prod_description_en]);
	$prod_description_fr 		  =  mysqli_real_escape_string($con, $_POST[prod_description_fr]);
	$prod_code 					  =  mysqli_real_escape_string($con, $_POST[prod_code]);
	$prod_question_supplementaire =  mysqli_real_escape_string($con, $_POST[prod_question_supplementaire]);
	$prod_question_choix_reponse  =  mysqli_real_escape_string($con, $_POST[prod_question_choix_reponse]);
	$prod_prix_unitaire  		  =  mysqli_real_escape_string($con, $_POST[prod_prix_unitaire]);
	$prod_choix_de_reponses 	  = mysqli_real_escape_string($con, $_POST[prod_choix_de_reponses]);
	$prod_status  		 		  =  mysqli_real_escape_string($con, $_POST[prod_status]);
	$fournisseur 		 		  =  mysqli_real_escape_string($con, $_POST[fournisseur]);
	$categorie 				 	  =  mysqli_real_escape_string($con, $_POST[categorie]);
	$ladate	  		 	 		  =  mktime(0,0,0,date("m"),date("d")-0,date("Y"));
	$prod_date_creation      	  =  date("Y-m-d", $ladate);	
	$prod_nombre_item_inclus 	  = mysqli_real_escape_string($con, $_POST[prod_nombre_item_inclus]);

	$queryCreerProduit= "INSERT INTO produits (prod_description_en,prod_description_fr,prod_prix_unitaire,prod_status,fournisseur,categorie,prod_edll_ou_hbc,prod_date_creation, prod_question_supplementaire,prod_code,prod_question_choix_reponse,prod_choix_de_reponses,prod_nombre_item_inclus)
	VALUES ('$prod_description_en','$prod_description_fr','$prod_prix_unitaire','$prod_status', '$fournisseur','$categorie','hbc','$prod_date_creation','$prod_question_supplementaire',
	'$prod_code','$prod_question_choix_reponse','$prod_choix_de_reponses','$prod_nombre_item_inclus')";

	$resultCreerProduit=mysqli_query($con,$queryCreerProduit)		or die  ('I cannot select items because:  ' . $queryCreerProduit . mysqli_error($con));
	echo '<br><br><br>Produit créé.<br>';
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

    <title>Créer un produit HBC</title>
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
  <form name="creer_produit_hbc" method="post" action="creer_produit_hbc.php">
   <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">
<br>
 <b>Créer un produit HBC</b>

<table  border="0" width="700"  class="TextSize">
<tr><td>&nbsp;</td></tr>

<tr>
<th>Statut:</th>
<td><select name="prod_status">
	<option  value="1">Actif</option>
	<option  value="0">Inactif</option>
</select></td>

<tr><td>&nbsp;</td></tr>

<tr>
<th>Description français:</th>
 <td><textarea name="prod_description_fr" cols="45" rows="1" class="formText" id="prod_description_fr"></textarea></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<th>Description anglais:</th>
 <td><textarea name="prod_description_en" cols="45" rows="1" class="formText" id="prod_description_en"></textarea></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
 <th>Code produit:</th>
 <td><input name="prod_code" type="text" class="formText" id="prod_code" value="" size="6" /></td>
</tr>
	   
 <tr><td>&nbsp;</td></tr>
 
  <tr>
 <th>Nombre d'item inclus:</th>
 <td><input name="prod_nombre_item_inclus" type="text" class="formText" id="prod_nombre_item_inclus" value="" size="15" /> </td>
 </tr>
 
<tr><td>&nbsp;</td></tr>
 
 <tr>
 <th>Prix unitaire:</th>
 <td><input name="prod_prix_unitaire" type="text" class="formText" id="prod_prix_unitaire" value="" size="6" /> </td>
 </tr>
 
<tr><td>&nbsp;</td></tr>


 <tr><th>Catégorie:</th>   
 <td><select name="categorie" class="formText" id="categorie">
  <?php
$queryCat="SELECT * from categories WHERE cat_edll_ou_hbc='hbc' AND cat_status=1 "; /* select all openings *
$resultCat=mysqli_query($con,$queryCat)or die ("Could not select items");

 while ($listItemCat=mysqli_fetch_array($resultCat,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItemCat[cat_id]\"";
  
 echo ">";
 echo $listItemCat[cat_nom_fr] . "</option>";}?>
        </select></td>
		
		
<tr><td>&nbsp;</td></tr>
		
<tr><th>Fournisseur:</th>   
 <td><select name="fournisseur" class="formText" id="fournisseur">
  <?php
$queryFourn="SELECT * from fournisseurs WHERE fourn_edll_ou_hbc='hbc' AND fourn_status=1 "; /* select all openings *
$resultFourn=mysqli_query($con,$queryFourn)or die ("Could not select items");

 while ($listItemFourn=mysqli_fetch_array($resultFourn,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItemFourn[fourn_id]\"";
  
 echo ">";
 echo $listItemFourn[fourn_nom_fr] . "</option>";}?>
        </select></td>
	
	<tr><td>&nbsp;</td></tr>
	
 <tr>
 <th>Question supplémentaire:</th>
 <td><input name="prod_question_supplementaire" type="text" class="formText" id="prod_question_supplementaire" value="" size="60" /></td>
 </tr>
 
 <tr><td>&nbsp;</td></tr>
	
 <tr>
 <th>Question à choix de réponse:</th>
 <td><input name="prod_question_choix_reponse" type="text" class="formText" id="prod_question_choix_reponse" value="" size="60" /></td>
 </tr>
	
<tr><td>&nbsp;</td></tr>   	
 
  <tr>
  <th>Choix de réponse (séparés par une /)<br>Exemple: Directeur/Opticien/Employé</th>
 <td><input name="prod_choix_de_reponses" type="text" class="formText" id="prod_choix_de_reponses" value="" size="50" /></td>
 </tr>
		
		
		<tr>
		<td align="center" colspan="2"><input type="submit" class="formText" name="editProduct" id="editProduct" value="Sauvegarder" /></td>
		 </tr>
		
             
    </div> <!-- /container -->

<a href="gestion_produits_hbc.php">Retour à la liste des produits HBC</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/