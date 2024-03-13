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

if ($_POST[editConfig] == "Sauvegarder"){
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$config_id					 		= mysqli_real_escape_string($con, $_POST[config_id]);
	$config_courriel_personne_contact   = mysqli_real_escape_string($con, $_POST[config_courriel_personne_contact]);
	$config_nom_personne_contact   		= mysqli_real_escape_string($con, $_POST[config_nom_personne_contact]);
	$config_courriel_simulation  		= mysqli_real_escape_string($con, $_POST[config_courriel_simulation]);
	$config_courriel_copie_commande  	= mysqli_real_escape_string($con, $_POST[config_courriel_copie_commande]);
	$config_courriel_copie_commande2  	= mysqli_real_escape_string($con, $_POST[config_courriel_copie_commande2]);

	$queryConfig= "UPDATE configurations
	SET 
	config_courriel_personne_contact = '$config_courriel_personne_contact',
	config_nom_personne_contact = '$config_nom_personne_contact',
	config_courriel_simulation = '$config_courriel_simulation',
	config_courriel_copie_commande = '$config_courriel_copie_commande',
	config_courriel_copie_commande2 = '$config_courriel_copie_commande2'
	WHERE 		config_id = $config_id";
	//echo '<br><br><br>Query'.$queryConfig. '<br>';
	$resultConfig=mysqli_query($con,$queryConfig)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
	echo '<br><br><br>Configurations mise à jour avec succès.<br>';
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

    <title>Configuration des paramètres EDLL</title>
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
  <form name="config_edll" method="post" action="config_edll.php">
   

  <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">


<b>Configuration des paramètres EDLL</b>
 		
 <?php   
//Afficher le fournisseur sélectionné
$rptQuery="SELECT * FROM configurations WHERE config_edll_ou_hbc='edll'";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because b2: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);
?>


<table  border="0" width="750"  class="TextSize">
<tr><td>&nbsp;</td></tr>


<input name="config_id" type="hidden" id="config_id" value="<?php echo "$listItem[config_id]"; ?>" />

<tr><td>&nbsp;</td></tr>
<tr><th colspan="2"><u>CONTACT</u></th></tr>
<tr> 
<tr><th colspan="2">(Ces informations seront incluse dans les simulations et les commandes envoyées au fournisseurs)</th>
</tr>

<tr><td>&nbsp;</td></tr>

<tr> 
 <th>Nom de la personne contact:</th>
 <td><input name="config_nom_personne_contact" type="text" class="formText" id="config_nom_personne_contact" value="<?php echo "$listItem[config_nom_personne_contact]"; ?>" size="45" /></td>
</tr>	

<tr><td>&nbsp;</td></tr>
 
<tr> 
 <th>Courriel de la personne contact:</th>
 <td><input name="config_courriel_personne_contact" type="text" class="formText" id="config_courriel_personne_contact" value="<?php echo "$listItem[config_courriel_personne_contact]"; ?>" size="45" /></td>
</tr>	
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><th><u>SIMULATION</u></th></tr>

<tr>
 <th>Courriel ou seront envoyées les courriel de simulation:</th>
 <td><input name="config_courriel_simulation" type="text" class="formText" id="config_courriel_simulation" value="<?php echo "$listItem[config_courriel_simulation]"; ?>" size="45" /></td>
</tr>	

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><th><u>COMMANDES RÉELLES</u></th></tr>

<tr>
 <th>Envoyer une copie de toutes les commandes à cette adresse:</th>
 <td><input name="config_courriel_copie_commande" type="text" class="formText" id="config_courriel_copie_commande" value="<?php echo "$listItem[config_courriel_copie_commande]"; ?>" size="45" /></td>
</tr>	

<tr><td>&nbsp;</td></tr>
<tr>
 <th>Envoyer une copie de toutes les commandes à cette adresse:</th>
 <td><input name="config_courriel_copie_commande2" type="text" class="formText" id="config_courriel_copie_commande2" value="<?php echo "$listItem[config_courriel_copie_commande2]"; ?>" size="45" /></td>
</tr>
	

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr><th><u>DÉSACTIVATION AUTOMATIQUE</u></th></tr>
<tr><td colspan="2">Le système sera automatiquement <b>désactivé</b> pour les sucursales <b>les 1er et 15 de chaque mois</b>.<br>
Ce message s'affichera alors dans la page de commande:</td></tr>	
<tr><td><img border="2" src="succursales/img/indisponible.jpg"></td></tr>
<tr><td colspan="2">Les succursales pourront uniquement consulter leur historique de commandes durant ces journées </td></tr>	
<tr><td>&nbsp;</td></tr>

		<tr>
		<td align="center" colspan="2"><input type="submit" class="formText" name="editConfig" id="editConfig" value="Sauvegarder" /></td>
		</tr>

             
    </div> <!-- /container -->

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>