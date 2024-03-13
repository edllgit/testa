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

    <title>Gestion des produits HBC</title>
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
     
<table width="850" cellpadding="2" border="1"  cellspacing="0" class="TextSize">
<tr>
	<td colspan="7" align="center"><b>GESTION DES CATÉGORIES DE PRODUITS HBC</b></td>
</tr>

<tr><td colspan="7" align="center">&nbsp;</td></tr>

<tr>
	<td colspan="7" align="center"><a href="creer_categorie_hbc.php">Créer une Catégorie</a></td>
</tr>

<tr><td colspan="7" align="center">&nbsp;</td></tr>

<tr>
	<td align="center" colspan="7"><b>Catégories Créées</b></th>
</tr>


<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Description FR</b></td>
	<td align="center"><b>Description EN</b></td>
	<td align="center"><b>Statut</b></td>	
</tr>
		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM categories  WHERE cat_edll_ou_hbc='hbc' ORDER BY cat_id";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);


while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
if ($listItem[cat_status]=='1')
$Prod_status = 'Actif';
else
$Prod_status = 'Inactif';

	
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_categorie_hbc.php?cat_id=$listItem[cat_id]\">$listItem[cat_id]</a></td> 
			<td align=\"center\">$listItem[cat_nom_fr]</td> 
			<td align=\"center\">$listItem[cat_nom_en]</td>
			<td align=\"center\">$Prod_status</td>

			</tr>";
}//END WHILE	 
 ?>    
          
 
 
             
    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/