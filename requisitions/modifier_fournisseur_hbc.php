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

if ($_POST[editFournisseur] == "Sauvegarder"){
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$fourn_id				 = mysqli_real_escape_string($con, $_POST[fourn_id]);
	$fourn_nom_fr 			 = mysqli_real_escape_string($con, $_POST[fourn_nom_fr]);
	$fourn_nom_en 			 = mysqli_real_escape_string($con, $_POST[fourn_nom_en]);
	$fourn_courriel 		 = mysqli_real_escape_string($con, $_POST[fourn_courriel]);
	$fourn_montant_achat_min = mysqli_real_escape_string($con, $_POST[fourn_montant_achat_min]);
	$fourn_status 			 = mysqli_real_escape_string($con, $_POST[fourn_status]);
	
	$queryMAJfourniseur= "UPDATE fournisseurs
	SET 
	fourn_nom_fr 	= '$fourn_nom_fr',
	fourn_nom_en 	= '$fourn_nom_en',
	fourn_courriel 	= '$fourn_courriel',
	fourn_status 	= '$fourn_status',
	fourn_montant_achat_min = '$fourn_montant_achat_min'
	WHERE 		fourn_id = $fourn_id";
	//echo '<br><br><br>Query'.$queryMAJfourniseur. '<br>';
	$resultMajFournisseur=mysqli_query($con,$queryMAJfourniseur)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
	echo '<br><br><br>Fournisseur mise à jour avec succès.<br>';
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

    <title>Modifier un Fournisseur HBC</title>
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
  <form name="modification_fournisseur_hbc" method="post" action="modifier_fournisseur_hbc.php">
   

  <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">


<b>Modification d'un fournisseur HBC</b>
 		
 <?php   
//Afficher le fournisseur sélectionné
$rptQuery="SELECT * FROM fournisseurs WHERE fourn_id = $_REQUEST[fourn_id]";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because b2: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
?>


<table  border="0" width="600"  class="TextSize">
<tr><td>&nbsp;</td></tr>


<input name="fourn_id" type="hidden" id="fourn_id" value="<?php echo "$listItem[fourn_id]"; ?>" />

<tr>
	<th># Fournisseur:</th> 
	<td><?php echo $listItem[fourn_id]; ?></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
<th>Nom FR:</th>
<td><textarea name="fourn_nom_fr" cols="45" rows="1" class="formText" id="fourn_nom_fr"><?php echo "$listItem[fourn_nom_fr]"; ?></textarea></td>
</tr>

<tr><td>&nbsp;</td></tr>

<tr>
	<th>Nom EN:</th>
	<td><textarea name="fourn_nom_en" cols="45" rows="1" class="formText" id="fourn_nom_en"><?php echo "$listItem[fourn_nom_en]"; ?></textarea></td>
</tr>
 
 <tr><td>&nbsp;</td></tr>
 
<tr> 
 <th>Courriel:</th>
 <td><input name="fourn_courriel" type="text" class="formText" id="fourn_courriel" value="<?php echo "$listItem[fourn_courriel]"; ?>" size="30" /></td>
</tr>	

<tr><td>&nbsp;</td></tr>
	
<tr>
<th>Montant Achat Minimum:</th>
 <td><input name="fourn_montant_achat_min" type="text" class="formText" id="fourn_montant_achat_min" value="<?php echo "$listItem[fourn_montant_achat_min]"; ?>" size="4" />$</td>
</tr>
 
<tr><td>&nbsp;</td></tr>
 
 <tr>
 <th>Statut:</th>
 <td>
<select name="fourn_status">
	<option  <?php if ($listItem[fourn_status] == '1') echo 'selected'; ?> value="1">Actif</option>
	<option  <?php if ($listItem[fourn_status] == '0') echo 'selected'; ?> value="0">Inactif</option>
</select></td>
</tr>
		
		<tr>
		<td><input type="submit" class="formText" name="editFournisseur" id="editFournisseur" value="Sauvegarder" /></td>
		</tr>

             
    </div> <!-- /container -->

<a href="gestion_fournisseurs_hbc.php">Retour à la liste des Fournisseurs HBC</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/