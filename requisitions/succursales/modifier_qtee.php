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

$prod_req_id = $_REQUEST[prod_req_id];
$queryReqID = "SELECT req_id FROM produits_requisitions WHERE prod_req_id= $prod_req_id";
$resultReqID = mysqli_query($con,$queryReqID)		or die  ('I cannot select items because a1:   '.$queryReqID . mysqli_error($con));
$DataReqID=mysqli_fetch_array($resultReqID,MYSQLI_ASSOC);

if ($_POST[MettreAJourItem] == "Mettre a jour"){
	echo '<br><br><br>Passe mise a jour';
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$req_id									  = mysqli_real_escape_string($con, $_POST[req_id]);
	$prod_req_id							  = mysqli_real_escape_string($con, $_POST[prod_req_id]); 
	$prod_req_quantite      				  = mysqli_real_escape_string($con, $_POST[prod_req_quantite]);
	$prod_req_reponse_question_supplementaire = mysqli_real_escape_string($con, $_POST[prod_req_reponse_question_supplementaire]); 
	$prod_req_reponse_question_choix_multiple = mysqli_real_escape_string($con, $_POST[prod_req_reponse_question_choix_multiple]);
	
	$queryMAJQtee = "UPDATE produits_requisitions
	SET prod_req_quantite = $prod_req_quantite,
	prod_req_reponse_question_supplementaire = '$prod_req_reponse_question_supplementaire',
	prod_req_reponse_question_choix_multiple = '$prod_req_reponse_question_choix_multiple'
	WHERE prod_req_id= $prod_req_id	";
	//echo '<br>Query<br>' . $queryMAJQtee;
	
	$resultMajQtee=mysqli_query($con,$queryMAJQtee)		or die  ('I cannot select items because: ' . mysqli_error($con));
	//echo '<br><br><br>Réquisition mise à jour avec succès.<br>';
	//Rediriger vers le détail de la requisition après la MAJ
	$PathRedirection = "panier.php?req_id=". $req_id;
	header("Location:".$PathRedirection);/* rediriger à l'index si l'usager n'est pas authentifié */
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

    <title>Modifier la quantité d'un item</title>
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
  <form name="modifier_qtee" method="post" action="modifier_qtee.php">
   

  <?php  include("inc/menu.inc.php");  ?>
	  
<div class="container">

<b>Modifier la quantité d'un item</b>   <a href="modifier_requisition.php?req_id=<?php echo "$DataReqID[req_id]"; ?>">Retour à la réquisition</a>
<table width="550" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	

<tr>
<td align="center"><b>Client</b></td>
<td align="center"><b>Numéro de réquisition</b></td>
<td align="center"><b>Date</b></td>
<td align="center"><b>Statut</b></td>
</tr>

<?php   

//Afficher les produits créés 
$rptQuery="SELECT * FROM produits_requisitions, produits
			WHERE  prod_req_id = $prod_req_id 
			AND produits.prod_id=produits_requisitions.prod_id";

$rptResult=mysqli_query($con,$rptQuery)		or die  ('I cannot select items because a1:   '.$rptQuery . mysqli_error($con));
$listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC);

$TOTAL = 0;///Initilaise le compteur

$ID_Requisition = $listItem[req_id]; 

$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $listItem[req_id] ";
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot  select items because b2:   '.$QueryInfoRequisition . mysqli_error($con));
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);

echo "<tr>
<td align=\"center\">$DataInfoRequi[user_id]</td>
<td align=\"center\">#$ID_Requisition</td>
<td align=\"center\">$DataInfoRequi[req_date_traitement]</td>
<td align=\"center\">$DataInfoRequi[req_status]</td>
</tr></table><br><br>";
?>
<table width="1300" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	
<tr>
<td align="center"><b>Fournisseur</b></td>
<td align="center"><b>Produit</b></td>
<td align="center"><b>Qtée</b></td>
<td align="center"><b>Prix ind.</b></td>
<td align="center"><b>Question supplémentaire</b></td>
<td align="center"><b>Réponse</b></td>
<td align="center"><b>Question choix de réponse</b></td>
<td align="center"><b>Réponse</b></td>
</tr>

	 
<?php
	
$queryFournisseur="SELECT fourn_nom_fr FROM fournisseurs WHERE fourn_id= $listItem[fournisseur]";
//echo '<br>'.$queryFournisseur;
$resultFournisseur =mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because:   '.$queryFournisseur . mysqli_error($con));
$DataFournisseur = mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$prod_req_id = $listItem[prod_req_id];

$Select ="
<select name=\"prod_req_quantite\" class=\"formField2\" id=\"prod_req_quantite\">                    

<option value=\"1\"";
if ($listItem[prod_req_quantite]==1) $Select .= 'selected'; 
$Select .=">1</option>

<option value=\"2\"";
if ($listItem[prod_req_quantite]==2) $Select .= 'selected'; 
$Select .=">2</option>

<option value=\"3\"";
if ($listItem[prod_req_quantite]==3) $Select .= 'selected'; 
$Select .=">3</option>

<option value=\"4\"";
if ($listItem[prod_req_quantite]==4) $Select .= 'selected'; 
$Select .=">4</option>

<option value=\"5\"";
if ($listItem[prod_req_quantite]==5) $Select .= 'selected'; 
$Select .=">5</option>

<option value=\"6\"";
if ($listItem[prod_req_quantite]==6) $Select .= 'selected'; 
$Select .=">6</option>

<option value=\"7\"";
if ($listItem[prod_req_quantite]==7) $Select .= 'selected'; 
$Select .=">7</option>

<option value=\"8\"";
if ($listItem[prod_req_quantite]==8) $Select .= 'selected'; 
$Select .=">8</option>

<option value=\"9\"";
if ($listItem[prod_req_quantite]==9) $Select .= 'selected'; 
$Select .=">9</option>

<option value=\"10\"";
if ($listItem[prod_req_quantite]==10) $Select .= 'selected'; 
$Select .=">10</option>

</select>";

	echo "
	<tr>
	<td align=\"center\">$DataFournisseur[fourn_nom_fr]</td>
	<td align=\"center\">$listItem[prod_description_fr]</td>
	<td align=\"center\">$Select</td>
	<td align=\"center\">$listItem[prod_req_prix_individuel]$</td>
	<td align=\"center\">$listItem[prod_question_supplementaire]</td>";
	
	if ($listItem[prod_question_supplementaire]<>''){
		echo "<td align=\"center\"><input type=\"text\"id=\"prod_req_reponse_question_supplementaire\"  name=\"prod_req_reponse_question_supplementaire\" 
		value=\"$listItem[prod_req_reponse_question_supplementaire]\"></td>";
	}else{
		echo "<td align=\"center\"><input type=\"hidden\"id=\"prod_req_reponse_question_supplementaire\"  name=\"prod_req_reponse_question_supplementaire\"
		value=\"$listItem[prod_req_reponse_question_supplementaire]\"></td>";
	}
	
	echo "<td align=\"center\">$listItem[prod_question_choix_reponse]</td>";
	//echo "<td align=\"center\">";
	//Aller chercher le product ID 
		
	$queryProduit  = "SELECT prod_id, prod_req_reponse_question_choix_multiple  FROM produits_requisitions WHERE prod_req_id = $prod_req_id ";
	//echo '<br><br>QUERY: '. $queryProduit;
	$ResultProduit = mysqli_query($con,$queryProduit)		or die ("Cannot not select items b8" . $queryProduit);
	$DataProduit   = mysqli_fetch_array($ResultProduit,MYSQLI_ASSOC);// Un seul résultat
	$QueryChoixReponse="SELECT prod_choix_de_reponses FROM produits WHERE prod_id='$DataProduit[prod_id]'";
	//echo '<br>QueryChoixReponse:<br>' . $QueryChoixReponse;
	
	$ResultChoixReponse	=	mysqli_query($con,$QueryChoixReponse)			or die ("Could not select items");
	$DataChoixReponse	=	mysqli_fetch_array($ResultChoixReponse,MYSQLI_ASSOC);// Un seul résultat
	$ChoixDeReponse 	= 	$DataChoixReponse[prod_choix_de_reponses];
	//Splitter les choix avec la slash
	$tableauChoixReponse = explode('/',$ChoixDeReponse);
	$SelectReponse ="<select name=\"prod_req_reponse_question_choix_multiple\" class=\"formField2\" id=\"prod_req_reponse_question_choix_multiple\">";
	foreach($tableauChoixReponse as $value){
					$SelectReponse.= "<option value=\"{$value}\"";
					$valeurTemp=$value;
					if ($DataProduit[prod_req_reponse_question_choix_multiple]==$valeurTemp){
					//	echo '<br>if '. $DataProduit[prod_req_reponse_question_choix_multiple].'=='. $valeurTemp;
						$SelectReponse.= " selected";
					}
					$SelectReponse.= " >";
					$SelectReponse.= "{$value}</option>";
				}//END FOREACH
	
	//Insérer les options
	$SelectReponse .="</select>";
	

	if ($listItem[prod_question_choix_reponse]<>''){
		echo "<td align=\"center\">$SelectReponse</td>";
	}
	
	
	echo "</tr>";
	


echo "
<tr>
<td align=\"center\" colspan=\"8\"><input type=\"submit\" class=\"formText\" name=\"MettreAJourItem\" id=\"MettreAJourItem\" value=\"Mettre a jour\" /></td>
<tr>";

?>

<input name="prod_req_id" type="hidden" id="prod_req_id" value="<?php echo "$prod_req_id"; ?>" />
<input name="req_id" type="hidden" id="req_id" value="<?php echo "$DataReqID[req_id]"; ?>" />

    </div> <!-- /container -->
<br><br>
	

  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>