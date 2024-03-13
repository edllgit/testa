<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié */
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

    <title>Gestion des Réquisitions EDLL</title>
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
<td colspan="8" align="center"><b>GESTION DES RÉQUISITIONS EDLL</b></td>
</tr>
<tr><td colspan="8" align="center">&nbsp;</td></tr>



<tr>
	<td align="center" colspan="8"><b>Réquisitions non complétés</b></th>             
</tr>

<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Date</b></td>
	<td align="center"><b>Client</b></td>		
	<td align="center"><b>Statut</b></td>
	<td align="center"><b>Valeur Total</b></td>
</tr>
		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM requisitions WHERE req_edll_ou_hbc='edll' AND req_status='panier' ORDER BY req_id";
//echo $rptQuery . '<br><br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);


while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
if ($listItem[prod_status]=='1')
$Prod_status = 'Actif';
else
$Prod_status = 'Inactif';

$calculerTotalRequisition = "SELECT * FROM produits_requisitions WHERE req_id=$listItem[req_id]";
$resultTotalRequisition   = mysqli_query($con,$calculerTotalRequisition)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$TotalRequisition = 0;
while ($DataTotalRequisition=mysqli_fetch_array($resultTotalRequisition,MYSQLI_ASSOC)){
	$PrixProduit =  $DataTotalRequisition[prod_req_quantite] * $DataTotalRequisition[prod_req_prix_individuel];
	$TotalRequisition = $TotalRequisition + $PrixProduit;
	
}//END WHILE

	$TotalRequisition=money_format('%.2n',$TotalRequisition);
	
	if ($TotalRequisition>0){
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_requisition_edll.php?req_id=$listItem[req_id]\">$listItem[req_id]</a></td> 
			<td align=\"center\">$listItem[req_date_creation]</td>
			<td align=\"center\">$listItem[user_id]</td> 
			<td align=\"center\">$listItem[req_status]</td>
			<td align=\"center\">$TotalRequisition$</td>
			</tr>";
	}//END IF
}//END WHILE	 

echo '</table>';
?>

<br><br><br>
<table width="850" cellpadding="2" border="1"  cellspacing="0" class="TextSize">
<tr>
	<td align="center" colspan="8"><b>Réquisitions complétées</b></th>             
</tr>

<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Date de création</b></td>
	<td align="center"><b>Date d'envoie au fournisseur</b></td>
	<td align="center"><b>Client</b></td>		
	<td align="center"><b>Statut</b></td>
	<td align="center"><b>Valeur Total</b></td>
</tr>

<?php
//Afficher les produits créés 
$rptQuery="SELECT * FROM requisitions WHERE req_edll_ou_hbc='edll' AND req_status<>'panier' ORDER BY req_id";
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);

while ($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
	
if ($listItem[prod_status]=='1')
$Prod_status = 'Actif';
else
$Prod_status = 'Inactif';

$calculerTotalRequisition = "SELECT * FROM produits_requisitions WHERE req_id=$listItem[req_id]";
$resultTotalRequisition   = mysqli_query($con,$calculerTotalRequisition)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
$TotalRequisition = 0;
while ($DataTotalRequisition=mysqli_fetch_array($resultTotalRequisition,MYSQLI_ASSOC)){
	$PrixProduit =  $DataTotalRequisition[prod_req_quantite] * $DataTotalRequisition[prod_req_prix_individuel];
	$TotalRequisition = $TotalRequisition + $PrixProduit;
	
}//END WHILE

	$TotalRequisition=money_format('%.2n',$TotalRequisition);
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_requisition_edll.php?req_id=$listItem[req_id]\">$listItem[req_id]</a></td> 
			<td align=\"center\">$listItem[req_date_creation]</td>
			<td align=\"center\">$listItem[req_date_envoie_fournisseur]</td>
			<td align=\"center\">$listItem[user_id]</td> 
			<td align=\"center\">$listItem[req_status]</td>
			<td align=\"center\">$TotalRequisition$</td>
			</tr>";
}//END WHILE	
 ?>    
          
 
            
    </div> <!-- /container -->


  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>