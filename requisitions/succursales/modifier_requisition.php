<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1'); 
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

    <title>Réquisition EDLL #<?php echo $ID_Requisition; ?></title>
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
  <form name="modification_requisition_edll" method="post" action="modifier_requisition_edll.php">
   
  <?php  include("inc/menu.inc.php");
  $ID_Requisition = $_REQUEST[req_id]; 
  $PathAjouterItem="ajout_item_requisition_edll.php?req_id=".$_REQUEST[req_id];
  
  //Afficher les produits créés 
$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $ID_Requisition";

$rptQuery="SELECT * FROM produits_requisitions, produits
			WHERE  produits_requisitions.req_id = $ID_Requisition
			AND produits.prod_id=produits_requisitions.prod_id
			ORDER BY fournisseur";
//echo $rptQuery . '<br>';
$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because:   '.$rptQuery . mysqli_error($con));
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot select items because:   '.$QueryInfoRequisition . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);
 ?>
	  
<div class="container">
<b>Réquisition #<?php echo $ID_Requisition; ?></b>    
<table width="800" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<tr>
<td align="center"><b>Client</b></td>
<td align="center"><b>Numéro de réquisition</b></td>
<td align="center"><b>Date de création</b></td>
<td align="center"><b>Date envoie au fournisseur</b></td>
<td align="center"><b>Statut</b></td>
</tr>

<?php   
$TOTAL = 0;///Initilaise le compteur

echo "<tr>
<td align=\"center\">$DataInfoRequi[user_id]</td>
<td align=\"center\">#$ID_Requisition</td>
<td align=\"center\">$DataInfoRequi[req_date_creation]</td>
<td align=\"center\">$DataInfoRequi[req_date_envoie_fournisseur]</td>
<td align=\"center\">$DataInfoRequi[req_status]</td>
</tr></table>";
?>
<br><br>
<table width="1350" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<tr>
<td align="center"><b>Fournisseur</b></td>
<?php if ($DataInfoRequi[req_status]=='panier'){ ?>
<td align="center"><b>Sélectionner</b></td>
<?php } ?>
<td align="center"><b>Produit</b></td>
<td align="center"><b>Question supplémentaire</b></td>

<td align="center"><b>Question choix multiple</b></td>
<td align="center"><b>Qtée</b></td>
<td align="center"><b>Prix ind.</b></td>
<td align="center"><b>Sous-total</b></td>
</tr>
<?php
while($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
$SousTotal=$listItem[prod_req_quantite] * $listItem[prod_req_prix_individuel];
$SousTotal=money_format('%.2n',$SousTotal);
	
$queryFournisseur="SELECT fourn_nom_fr FROM fournisseurs WHERE fourn_id= $listItem[fournisseur]";
$resultFournisseur =mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because:   '.$queryFournisseur . mysqli_error($con));
$DataFournisseur = mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$prod_req_id = $listItem[prod_req_id];


	echo "
	<tr>
	<td align=\"center\">$DataFournisseur[fourn_nom_fr]</td>";
	
	 if ($DataInfoRequi[req_status]=='panier'){ 
		echo "<td align=\"center\"><input type=\"checkbox\" id=\"id_a_effacer[]\" value=\"$prod_req_id\" name=\"id_a_effacer[]\" ></td>";
	 } 
	
	echo "
	<td align=\"center\">$listItem[prod_description_fr]</td>
	<td align=\"center\">$listItem[prod_question_supplementaire]";
	
	if ($DataInfoRequi[req_status]=='panier'){ 
		//On laisse modifier les réponses puisque la réquisition n'a pas encore été traitée
		echo "<a href=\"modifier_qtee_edll.php?prod_req_id=$prod_req_id\">&nbsp;&nbsp;<b>$listItem[prod_req_reponse_question_supplementaire]</b></a></td>";
		echo "<td align=\"center\">$listItem[prod_question_choix_reponse]&nbsp;&nbsp;<b><a href=\"modifier_qtee_edll.php?prod_req_id=$prod_req_id\">$listItem[prod_req_reponse_question_choix_multiple]</a></b></td>";
		echo "<td align=\"center\"><a href=\"modifier_qtee_edll.php?prod_req_id=$prod_req_id\">$listItem[prod_req_quantite]</a></td>";
	}else{
		//La réquisition a déja été traité, on ne laisse donc pas l'option de la modifier
		echo "<b>$listItem[prod_req_reponse_question_supplementaire]</b></td>";
		echo "<td align=\"center\">$listItem[prod_question_choix_reponse]&nbsp;&nbsp;<b>$listItem[prod_req_reponse_question_choix_multiple]</b></td>";
		echo "<td align=\"center\">$listItem[prod_req_quantite]</td>";
	}
	
	echo "<td align=\"center\">$listItem[prod_req_prix_individuel]$</td>
	<td align=\"center\">$SousTotal$</td>
	</tr>";
	$TOTAL += $SousTotal;
	$TOTAL=money_format('%.2n',$TOTAL);
}//END WHILE
echo "
<tr>";
if ($DataInfoRequi[req_status]=='panier')
echo "<td> </td><td align=\"center\"><input type=\"submit\" class=\"formText\" name=\"SupprimerItem\" id=\"SupprimerItem\" value=\"Supprimer\" /></td>";
echo "<td align=\"right\" colspan=\"8\"><b>TOTAL: $TOTAL$</b></td>
<tr></table>";
?>
	
<input name="req_id" type="hidden" id="req_id" value="<?php echo "$ID_Requisition"; ?>" />

</div> <!-- /container -->
<br><br>
	

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>