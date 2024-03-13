<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);  
require_once("../../sec_connect_requisitions.inc.php");
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

    <title>Mes réquisitions</title>
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
<td colspan="8" align="center"><b>Mes réquisitions</b></td>
</tr>
<tr><td colspan="8" align="center">&nbsp;</td></tr>

<tr bgcolor="CCCCCC">
	<td align="center"><b>ID</b></td>
	<td align="center"><b>Date</b></td>
	<td align="center"><b>Client</b></td>		
	<td align="center"><b>Statut</b></td>
	<td align="center"><b>Valeur Total</b></td>
</tr>
		
 <?php   
//Afficher les produits créés 
$rptQuery="SELECT * FROM requisitions WHERE req_edll_ou_hbc='edll' AND user_id='$_SESSION[idMobile]' ORDER BY req_id";
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
	echo 	"<tr>
			<td align=\"center\"><a href=\"modifier_requisition_edll.php?req_id=$listItem[req_id]\">$listItem[req_id]</a></td> 
			<td align=\"center\">$listItem[req_date_traitement]</td>
			<td align=\"center\">$listItem[user_id]</td> 
			<td align=\"center\">$listItem[req_status]</td>
			<td align=\"center\">$TotalRequisition$</td>
			</tr>";
}//END WHILE	 

//S'il y a une réquisition en cours au status Panier, permettre d'y ajouter des items directement de la page d'accueil, sinon créer une nouvelle réquisition automatiquement
$queryRequi  = "SELECT * FROM requisitions WHERE req_edll_ou_hbc='edll' AND user_id='$_SESSION[idMobile]' AND req_status='panier'";
//echo '<br>'.$queryRequi;
$resultRequi =  mysqli_query($con,$queryRequi)		or die  ('I cannot select items because a1: ' . mysqli_error($con));


$NombreResultat = mysqli_num_rows($resultRequi);
//echo '<br>Nombre resultat:'. $NombreResultat;
$DataReq = mysqli_fetch_array($resultRequi,MYSQLI_ASSOC);
if ($NombreResultat>0){
	//On doit rediriger vers le détail de cette réquisition	
	$url = "panier.php?req_id=".$DataReq[req_id];
	echo '<br><br>url:'. $url;
	//exit();
	header("Location: $url");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();	
}else{
	//Aucune réquistion n'est en cours, on doit en créer une nouvelle..
	$curTime= date("Y-m-d");	
	$queryCreerRequisition = "INSERT INTO requisitions (user_id, req_date_creation, req_status,req_edll_ou_hbc)
                                        VALUES ('$_SESSION[idMobile]','$curTime', 'panier', 'edll')";
	//echo 	'<br>'. $queryCreerRequisition;
	//exit();
	$resultCreationRequisition  =   mysqli_query($con,$queryCreerRequisition)		or die  ('I cannot select items because a13: ' . mysqli_error($con));
	//$queryLastInsertID  		= "SELECT LAST_INSERT_ID();";
	//$resultLastInsertID 		=  mysqli_query($con,$queryLastInsertID)		or die  ('I cannot select items because a13: ' . mysqli_error($con)); 
	echo '<br>Last inserted ID:'. 
	$LastInsertID = mysqli_insert_id($con);
	//Rediriger vers le détail de la réquistion qu'on vient de créer
	$url = "ajout_item_requisition.php?req_id=".mysqli_insert_id($con);
	echo '<br><br>url:'. $url;
	header("Location: $url");/* rediriger à l'index si l'usager n'est pas authentifié */
	exit();
}
?>    
          
</div> <!-- /container -->

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>