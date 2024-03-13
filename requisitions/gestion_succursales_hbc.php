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

    <title>Gestion des succursales HBC</title>
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

  <p>&nbsp;</p> <p>&nbsp;</p> 
  
<table width="1200" cellpadding="2" border="1"  cellspacing="0" class="TextSize">
<tr>
	<td colspan="7" align="center"><b>GESTION DES SUCCURSALES HBC</b></td>
</tr>

<tr><td colspan="7" align="center">&nbsp;</td></tr>

<tr>
	<td align="center" colspan="7"><b>Succursales Créées</b></th>
</tr>

<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Nom</b></td>
	<td align="center"><b>Courriel</b></td>
	<td align="center"><b>Statut</b></td>	
	<td align="center"><b>Adresse</b></td>	
</tr>
		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM succursales  WHERE succ_edll_ou_hbc='hbc' ORDER BY succ_nom";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);


while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
if ($listItem[succ_status]=='1')
$Succ_status = 'Actif';
else
$Succ_status = 'Inactif';

	
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_succursale_hbc.php?succ_id=$listItem[succ_id]\">$listItem[succ_id]</a></td> 
			<td align=\"center\">$listItem[succ_nom]</td> 
			<td align=\"center\">$listItem[succ_courriel]</td>
			<td align=\"center\">$Succ_status</td>
			<td align=\"right\">$listItem[succ_adresse]</td>
			</tr>";
}//END WHILE	 
 ?>    
          
 
 
             
    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/