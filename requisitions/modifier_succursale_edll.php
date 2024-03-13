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

if ($_POST[editSuccursale] == "Sauvegarder"){
	echo 'Passe dans sauvegarder';
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$succ_id		 = mysqli_real_escape_string($con, $_POST[succ_id]); 
	$succ_nom	 	 = mysqli_real_escape_string($con, $_POST[succ_nom]); 
	$succ_courriel 	 = mysqli_real_escape_string($con, $_POST[succ_courriel]);
	$succ_adresse 	 = mysqli_real_escape_string($con, $_POST[succ_adresse]);
	$succ_status	 = mysqli_real_escape_string($con, $_POST[succ_status]);
	
	$queryMAJSuccursale= "UPDATE succursales
	SET 
	succ_nom 	= '$succ_nom',
	succ_courriel 	= '$succ_courriel',
	succ_adresse  =  '$succ_adresse',
	succ_status = '$succ_status'
	WHERE 		succ_id = $succ_id";
	$resultMAJSuccursale=mysqli_query($con,$queryMAJSuccursale)		or die  ('I cannot select items because: ' . mysqli_error($con));
	echo '<br><br><br>Succursale mise à jour avec succès.<br>';
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

    <title>Modifier une succursale EDLL</title>
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
  <form name="modification_succursale_edll" method="post" action="modifier_succursale_edll.php">
   

  <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">

 		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM succursales WHERE succ_id = $_REQUEST[succ_id]  AND succ_edll_ou_hbc='edll' ORDER BY succ_nom DESC";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
?>

<input name="succ_id" type="hidden" id="succ_id" value="<?php echo "$listItem[succ_id]"; ?>" />
<br>
<b>Modification d'une succursale EDLL</b>

<table  border="0" width="600"  class="TextSize">
<tr><td>&nbsp;</td></tr>


<tr><th># Succursale:</th> 
<td><?php echo $listItem[succ_id]; ?></td>

<tr><td>&nbsp;</td></tr>

<tr>
<th>Identifiant:</th>
<td><?php echo $listItem[user_id]; ?></td>
</tr>


<tr><td>&nbsp;</td></tr>

<tr>
<th>Nom:</th>
<td><textarea name="succ_nom" cols="45" rows="1" class="formText" id="succ_nom"><?php echo "$listItem[succ_nom]"; ?></textarea></td>
</tr>
 
 
 <tr><td>&nbsp;</td></tr>
 
 <tr>
 <th>Courriel:</th>
 <td><textarea name="succ_courriel" cols="45" rows="1" class="formText" id="succ_courriel"><?php echo "$listItem[succ_courriel]"; ?></textarea></td>
 
<tr><td>&nbsp;</td></tr>

<tr>
<th>Adresse:</th>
 <td><textarea name="succ_adresse" cols="45" rows="3" class="formText" id="succ_adresse"><?php echo "$listItem[succ_adresse]"; ?></textarea></td>
 
<tr><td>&nbsp;</td></tr>

<tr><th>Statut:</th>
<td>
<select name="succ_status">
	<option  <?php if ($listItem[succ_status] == '1') echo 'selected'; ?> value="1">Actif</option>
	<option  <?php if ($listItem[succ_status] == '0') echo 'selected'; ?> value="0">Inactif</option>
</select></td>
		
<tr><td>&nbsp;</td></tr>
		
<tr>
<td colspan="2" align="center" ><input type="submit" class="formText" name="editSuccursale" id="editSuccursale" value="Sauvegarder" /></td>
		
             
    </div> <!-- /container -->

<a href="gestion_succursales_edll.php">Retour à la liste des succursales EDLL</a><br>
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>