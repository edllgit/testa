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
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$prod_id			 = $_POST[prod_id];
	$prod_description_en 			= mysqli_real_escape_string($con, $_POST[prod_description_en]);
	$prod_description_fr 			= mysqli_real_escape_string($con, $_POST[prod_description_fr]);
	$prod_code 			 			= mysqli_real_escape_string($con, $_POST[prod_code]);
	$prod_question_supplementaire 	= mysqli_real_escape_string($con, $_POST[prod_question_supplementaire]);
	$prod_question_choix_reponse 	= mysqli_real_escape_string($con, $_POST[prod_question_choix_reponse]);
	$prod_choix_de_reponses 		= mysqli_real_escape_string($con, $_POST[prod_choix_de_reponses]);
	$prod_prix_unitaire  			= mysqli_real_escape_string($con, $_POST[prod_prix_unitaire]);
	$prod_status 		 			= mysqli_real_escape_string($con, $_POST[prod_status]);
	$fournisseur 		 			= mysqli_real_escape_string($con, $_POST[fournisseur]);
	$categorie 			 			= mysqli_real_escape_string($con, $_POST[categorie]);
	$prod_nombre_item_inclus 	   = mysqli_real_escape_string($con, $_POST[prod_nombre_item_inclus]);
	
	$queryMAJProduit= "UPDATE produits
	SET 
	prod_description_en 		= '$prod_description_en',
	prod_description_fr 		= '$prod_description_fr',
	prod_code 				    = '$prod_code',
	prod_question_supplementaire= '$prod_question_supplementaire',
	prod_question_choix_reponse= '$prod_question_choix_reponse',
	prod_choix_de_reponses 	= '$prod_choix_de_reponses', 
	prod_prix_unitaire 		= '$prod_prix_unitaire',
	prod_status 			= '$prod_status',
	fournisseur 			= '$fournisseur',
	categorie 				= '$categorie',
	prod_nombre_item_inclus = '$prod_nombre_item_inclus'
	WHERE 				prod_id = $prod_id	";
	//echo '<br><br><br>query MAJ: '. $queryMAJProduit;
	$resultMajProduit=mysqli_query($con,$queryMAJProduit)		or die  ('I cannot select items because: ' . mysqli_error($con));
	echo '<br><br><br>Produit mis à jour avec succès.<br>';
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
  <form name="modification_produit_edll" method="post" action="modifier_produit_edll.php">
     <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">

 		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM produits WHERE prod_id = $_REQUEST[prod_id] ORDER BY prod_description_fr DESC";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);

?>

<b>Modification d'un produit EDLL</b>
<table  border="0" width="600"  class="TextSize">
<input name="prod_id" type="hidden" id="prod_id" value="<?php echo "$listItem[prod_id]"; ?>" />
<tr><td>&nbsp;</td></tr>

<tr>
	<th># Produit:</th>
	<td>#<?php echo $listItem[prod_id]; ?></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<th>Statut:</th>
<td>
<select name="prod_status">
	<option  <?php if ($listItem[prod_status] == '1') echo 'selected'; ?> value="1">Actif</option>
	<option  <?php if ($listItem[prod_status] == '0') echo 'selected'; ?> value="0">Inactif</option>
</select>
</td>
</tr>

<tr><td>&nbsp;</td></tr>


<tr>
<th>Description français:</th>
<td><input name="prod_description_fr" type="text" class="formText" id="prod_description_fr" value="<?php echo "$listItem[prod_description_fr]"; ?>" size="60" /></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
 <th>Description anglais:</th>
 <td><input name="prod_description_en" type="text" class="formText" id="prod_description_en" value="<?php echo "$listItem[prod_description_en]"; ?>" size="60" /></td> 
</tr>


<tr><td>&nbsp;</td></tr>

<tr>
  <th>Code produit:</th>
 <td><input name="prod_code" type="text" class="formText" id="prod_code" value="<?php echo "$listItem[prod_code]"; ?>" size="10" /></td>
</tr>	

<tr><td>&nbsp;</td></tr>

 <tr>
 <th>Nombre d'item inclus:</th>
 <td><input name="prod_nombre_item_inclus" type="text" class="formText" id="prod_nombre_item_inclus" value="<?php echo "$listItem[prod_nombre_item_inclus]"; ?>" size="15" /> </td>
 </tr>
 
<tr><td>&nbsp;</td></tr>

<tr>
 <th>Prix unitaire:</th>
 <td><input name="prod_prix_unitaire" type="text" class="formText" id="prod_prix_unitaire" value="<?php echo "$listItem[prod_prix_unitaire]"; ?>" size="6" /></td>
</tr>


<tr><td>&nbsp;</td></tr>

<tr>	   	 
 <th>Catégorie:</th>  
<td>
 <select name="categorie" class="formText" id="categorie">
  <?php
$queryCat="SELECT * from categories WHERE cat_edll_ou_hbc='edll'"; /* select all openings */
$resultCat=mysqli_query($con,$queryCat)or die ("Could not select items");

 while ($listItemCat=mysqli_fetch_array($resultCat,MYSQLI_ASSOC)){
 echo "<option value=\"$listItemCat[cat_id]\""; 
 if ($listItemCat[cat_id]=="$listItem[categorie]") 
 echo "selected=\"selected\"";
 echo ">";
 echo $listItemCat[cat_nom_fr] . "</option>";}?>
        </select><br></td>
</tr>
	
<tr><td>&nbsp;</td></tr>
	
<tr>		
<th>Fournisseur:</th>  
<td><select name="fournisseur" class="formText" id="fournisseur">
  <?php
$queryFourn="SELECT * from fournisseurs WHERE fourn_edll_ou_hbc='edll' "; /* select all openings */
$resultFourn=mysqli_query($con,$queryFourn)or die ("Could not select items");

 while ($listItemFourn=mysqli_fetch_array($resultFourn,MYSQLI_ASSOC)){
  
  echo "<option value=\"$listItemFourn[fourn_id]\"";
  
 if ($listItemFourn[fourn_id]=="$listItem[fournisseur]") 
 echo "selected=\"selected\"";
 echo ">";
 echo $listItemFourn[fourn_nom_fr] . "</option>";}?>
        </select>
</td>
</tr>
	
<tr><td>&nbsp;</td></tr>


 <tr>
 <th>Question supplémentaire:</th>
 <td><input name="prod_question_supplementaire" type="text" class="formText" id="prod_question_supplementaire" value="<?php echo "$listItem[prod_question_supplementaire]"; ?>" size="50" /></td>
 </tr>
 
<tr><td>&nbsp;</td></tr>

 <tr>
  <th>Question à choix de réponse:</th>
 <td><input name="prod_question_choix_reponse" type="text" class="formText" id="prod_question_choix_reponse" value="<?php echo "$listItem[prod_question_choix_reponse]"; ?>" size="50" /></td>
 </tr>
 
 <tr><td>&nbsp;</td></tr>
 
  <tr>
  <th>Choix de réponse (séparés par une barre oblique):</th>
 <td><input name="prod_choix_de_reponses" type="text" class="formText" id="prod_choix_de_reponses" value="<?php echo "$listItem[prod_choix_de_reponses]"; ?>" size="50" /></td>
 </tr>
	
<tr><td>&nbsp;</td></tr>

<tr>
	<td colspan="2" align="center"><input type="submit" class="formText" name="editProduct" id="editProduct" value="Sauvegarder" /></td>
</tr>
		
             
    </div> <!-- /container -->

<a href="gestion_produits_edll.php">Retour à la liste des produits EDLL</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>