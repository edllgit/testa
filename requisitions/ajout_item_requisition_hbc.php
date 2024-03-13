<?php
/*
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
ini_set('display_errors', '1'); 
require_once("../sec_connect_requisitions.inc.php");
?>
<!DOCTYPE html>
<html lang="en">
<?php session_start();
if ($_POST[req_id]<>''){
	$ID_Requisition = $_POST[req_id];
}else{	
	$ID_Requisition = $_REQUEST[req_id];
}
if ($_SESSION[idMobile] == ""){
	header("Location: index.php");/* rediriger à l'index si l'usager n'est pas authentifié  *
	exit();
}

//var_dump($_POST);
echo '<br><br><br><br>';
$AfficherProduitsQuiCorrespondent = 'non';

if ($_POST[Filtrer] == "Afficher les produits"){
	$AfficherProduitsQuiCorrespondent = 'oui';
	//On récupère les valeurs des différents champs puis on met à jour le tuple.
	$categorie		= $_POST[categorie];
	$fournisseur 	= $_POST[fournisseur];
	$req_id			= $_POST[req_id];
}

if ($_POST[Ajouter] == "Ajouter"){
	$prod_req_quantite   = $_POST[prod_req_quantite];
	$prod_id   			 = $_POST[prod_id];
	$categorie			 = $_POST[categorie];
	$fournisseur 		 = $_POST[fournisseur];
	$req_id				 = $_POST[req_id];
	$pro_req_reponse_question_supplementaire   = $_POST[prod_reponse_supplementaire];
	$prod_req_reponse_question_choix_multiple = $_POST[prod_req_reponse_question_choix_multiple];
	
	foreach ($prod_id as $key => $value) 
	{
		if ($prod_req_quantite[$key]>0){
			//Aller chercher le prix du produit sélectionné
			$queryPrixProduit  = "SELECT * FROM produits WHERE prod_id=$value";
			$resultPrixProduit = mysqli_query($con,$queryPrixProduit)		or die  ('I cannot remove items because: ' . mysqli_error($con));
			$DataPrixProduit   = mysqli_fetch_array($resultPrixProduit,MYSQLI_ASSOC);	
			//echo '<br><br><b>'.$DataPrixProduit[prod_description_fr]. '</b>   (ID #'.  $value . ')  Qtee:'.$prod_req_quantite[$key] . ': Ajouté au panier avec succès.'  ;
			echo '<br><br><b>'.$DataPrixProduit[prod_description_fr]. '</b>  Quantité:<b>'.$prod_req_quantite[$key] . '</b>: Ajouté au panier avec succès<br>.'  ;
			$queryInsert="INSERT INTO produits_requisitions  (prod_req_id, prod_id, req_id, prod_req_quantite, prod_req_prix_individuel,prod_req_reponse_question_supplementaire,prod_req_statut, prod_req_reponse_question_choix_multiple)
			VALUES ('',$value,$req_id,$prod_req_quantite[$key],'$DataPrixProduit[prod_prix_unitaire]','$pro_req_reponse_question_supplementaire[$key]','panier',   '$prod_req_reponse_question_choix_multiple[0]')";
			//echo '<br>'. $queryInsert;
			$resultInsert = mysqli_query($con,$queryInsert)		or die  ('I cannot insert items because: ' . mysqli_error($con));
		}//END IF
	}//END FOREACH

}//END IF 

?>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Ajout d'item dans la réquisition HBC #<?php echo $ID_Requisition; ?></title>
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
  <form name="ajout_item_requisition_hbc" method="post" action="ajout_item_requisition_hbc.php">
   

  <?php  include("inc/menu.inc.php"); ?>
	  
<div class="container">
<b>Ajout d'item dans la réquisition HBC #<?php echo $ID_Requisition; ?></b>   <a href="modifier_requisition_hbc.php?req_id=<?php echo $ID_Requisition; ?>">Retour au détail de la réquisition #<?php echo $ID_Requisition; ?></a>
<table width="550" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	

<tr>
<td align="center"><b>Client</b></td>
<td align="center"><b>Numéro de réquisition</b></td>
<td align="center"><b>Date</b></td>
<td align="center"><b>Statut</b></td>
</tr>

<?php   
$ID_Requisition = $_REQUEST[req_id]; 
//Afficher les produits créés 
$QueryInfoRequisition = "SELECT * FROM requisitions WHERE req_id= $ID_Requisition";
$resultInfoRequi=mysqli_query($con,$QueryInfoRequisition)		or die  ('I cannot select items because:   '.$QueryInfoRequisition . mysqli_error($con));
$ordersnum=mysqli_num_rows($rptResult);
$DataInfoRequi=mysqli_fetch_array($resultInfoRequi,MYSQLI_ASSOC);

$TOTAL = 0;///Initilaise le compteur

echo "<tr>
<td align=\"center\">$DataInfoRequi[user_id]</td>
<td align=\"center\">#$ID_Requisition</td>
<td align=\"center\">$DataInfoRequi[req_date_traitement]</td>
<td align=\"center\">$DataInfoRequi[req_status]</td>
</tr></table>";
?>
<table width="1400" cellpadding="2" border="1"  cellspacing="0" class="TextSize">	

<?php
while($listItem=mysqli_fetch_array($rptResult,MYSQLI_ASSOC)){
$SousTotal=$listItem[prod_req_quantite] * $listItem[prod_req_prix_individuel];
$SousTotal=money_format('%.2n',$SousTotal);
	
$queryFournisseur="SELECT fourn_nom_fr FROM fournisseurs WHERE fourn_id= $listItem[fournisseur] AND fourn_edll_ou_hbc='hbc' AND fourn_status=1";
$resultFournisseur =mysqli_query($con,$queryFournisseur)		or die  ('I cannot select items because:   '.$queryFournisseur . mysqli_error($con));
$DataFournisseur = mysqli_fetch_array($resultFournisseur,MYSQLI_ASSOC);
$prod_req_id = $listItem[prod_req_id];

	$TOTAL += $SousTotal;
	
}//END WHILE

?>
<br><br>
Filtre possibles:
<label>Fournisseur:</label>

<select name="fournisseur" class="formField2" id="fournisseur">                    
<?php
  		$QueryFournisseur="SELECT * FROM fournisseurs WHERE fourn_edll_ou_hbc='hbc' AND fourn_status=1 ORDER by fourn_nom_fr"; /* select all openings *
		$ResultFournisseur=mysqli_query($con,$QueryFournisseur)			or die ("Could not select items");
		echo "<option value=\"\">Tous les fournisseurs</option>";
		 while ($DataFournisseur=mysqli_fetch_array($ResultFournisseur,MYSQLI_ASSOC)){
			echo "<option value=\"$DataFournisseur[fourn_id]\"";
			if ($DataFournisseur[fourn_id]==$fournisseur){
				echo " selected";
			}
			echo " >";
			$name=stripslashes($DataFournisseur[fourn_nom_fr]);
			echo "$name</option>";
		 }//END WHILE?>
 </select>
 


 
&nbsp;&nbsp;&nbsp;
<label>Catégories:</label>
<select name="categorie" class="formField2" id="categorie">                    
<?php

  		$queryCategories="SELECT * FROM categories WHERE cat_edll_ou_hbc='hbc' and cat_status=1 ORDER by cat_nom_fr"; /* select all openings *
		$resultCategories=mysqli_query($con,$queryCategories)			or die ("Could not select items");
		echo "<option value=\"\">Toutes les catégories</option>";
		 while ($DataCategories=mysqli_fetch_array($resultCategories,MYSQLI_ASSOC)){
			echo "<option value=\"$DataCategories[cat_id]\"";
			if ($DataCategories[cat_id]==$categorie){
				echo " selected";
			}
			echo " >";
			$name=stripslashes($DataCategories[cat_nom_fr]);
			echo "$name</option>";
		 }
			?>
 </select>
 &nbsp;&nbsp;
<input type="submit" class="formText" name="Filtrer" id="Filtrer" value="Afficher les produits" />
<input name="req_id" type="hidden" id="req_id" value="<?php echo "$ID_Requisition"; ?>" />


<?php 
//Afficher les produits qui correspondent aux filtres appliqués

switch ($fournisseur){
	case '': $FiltreFournisseur = " "; break;
	default:  $FiltreFournisseur = " AND fournisseur = $fournisseur";	
}//END SWITCH



switch ($categorie){
	case '': $FiltreCategorie = " ";		break;
	default:  $FiltreCategorie = " AND categorie = $categorie";	
}//END SWITCH


$queryProduitsCorrespondants	= "SELECT * FROM produits WHERE prod_edll_ou_hbc='hbc' $FiltreCategorie $FiltreFournisseur AND prod_status=1 ORDER BY produits.categorie,produits.prod_description_fr";
//echo '<br>'. $queryProduitsCorrespondants . '<br>';
$resultProduitsCorrespondants	= mysqli_query($con,$queryProduitsCorrespondants)	or die ("Could not select items");
echo '
<tr>
	<td align="center"><b>Fournisseur</b></td>
	<td align="center"><b>Catégorie</b></td>
	<td align="center"><b>Produit</b></td>
	<td align="center"><b>Prix unitaire</b></td>
	<td align="center"><b>Qtée</b></td>
	<td align="center"><b>Question supplémentaire</b></td>
	<td align="center"><b>Réponse</b></td>
	<td align="center"><b>Quest. choix réponse</b></td>
	<td align="center"><b>Votre réponse</b></td>
</tr>';



while ($DataProduitsCorrespondants 	= mysqli_fetch_array($resultProduitsCorrespondants,MYSQLI_ASSOC)){
	
$Select="
<select name=\"prod_req_quantite[]\" class=\"formField2\" id=\"prod_req_quantite[]\">                    
	<option value=\"0\">0</option>
	<option value=\"1\">1</option>
	<option value=\"2\">2</option>
	<option value=\"3\">3</option>
	<option value=\"4\">4</option>
	<option value=\"5\">5</option>
	<option value=\"6\">6</option>
	<option value=\"7\">7</option>
	<option value=\"8\">8</option>
	<option value=\"9\">9</option>
	<option value=\"10\">10</option>
</select><br>";	

 echo "<input name=\"prod_id[]\" type=\"hidden\" id=\"prod_id[]\" value=\"$DataProduitsCorrespondants[prod_id]\"/>";

	$queryCategorie = "SELECT cat_nom_fr FROM categories WHERE cat_id= $DataProduitsCorrespondants[categorie]";
	$resultCategorie =  mysqli_query($con,$queryCategorie)	or die ("Could not select items");
	$DataCategorie=mysqli_fetch_array($resultCategorie,MYSQLI_ASSOC);
	
	$queryFourn = "SELECT fourn_nom_fr FROM fournisseurs WHERE fourn_id = $DataProduitsCorrespondants[fournisseur]";
	$resultFourn =  mysqli_query($con,$queryFourn)	or die ("Could not select items");
	$DataFourn=mysqli_fetch_array($resultFourn,MYSQLI_ASSOC);
	
	
		echo '<td align="center">'.$DataFourn[fourn_nom_fr].'</td>';
		echo '<td align="center">'.$DataCategorie[cat_nom_fr].'</td>';
		echo '<td align="center">'.$DataProduitsCorrespondants[prod_description_fr].'</td>';
		echo '<td align="center">'.$DataProduitsCorrespondants[prod_prix_unitaire].'$</td>';
		echo '<td align="center">'.$Select.'</td>';
		echo '<td align="center">'.$DataProduitsCorrespondants[prod_question_supplementaire].'</td>';
	
		if ($DataProduitsCorrespondants[prod_question_supplementaire]<>''){
			echo  '<td><input name="prod_reponse_supplementaire[]" type="text" class="formText" id="prod_reponse_supplementaire[]" value="" size="15" /></td>';
		}else{
			echo  '<td><input name="prod_reponse_supplementaire[]" type="hidden" class="formText" id="prod_reponse_supplementaire[]" value="" /></td>';
		}
		
		echo '<td align="center">'.$DataProduitsCorrespondants[prod_question_choix_reponse].'</td>';
		
		if ($DataProduitsCorrespondants[prod_question_choix_reponse]<>''){//Si la question à choix de réponse n'est pas vide
			//Afficher le select
			$QueryChoixReponse="SELECT prod_choix_de_reponses FROM produits WHERE prod_id='$DataProduitsCorrespondants[prod_id]'"; /* select all openings *
			//echo '!!query:'. $QueryChoixReponse;
			echo '<td>';
		?>
				
		<select name="prod_req_reponse_question_choix_multiple[]" class="formField2" id="prod_req_reponse_question_choix_multiple[]">   
       <option></option>		
		<?php
				
				$ResultChoixReponse	=	mysqli_query($con,$QueryChoixReponse)			or die ("Could not select items");
				$DataChoixReponse	=	mysqli_fetch_array($ResultChoixReponse,MYSQLI_ASSOC);// Un seul résultat
				$ChoixDeReponse 	= 	$DataChoixReponse[prod_choix_de_reponses];
				//Splitter les choix avec la slash
				$tableauChoixReponse = explode('/',$ChoixDeReponse);
				//echo '<br><br>tableau choix reponse:'.  var_dump($tableauChoixReponse);
				foreach($tableauChoixReponse as $value){
					echo "<option value=\"{$value}\"";
					//if ($DataChoixReponse[fourn_id]==$fournisseur){
					//	echo " selected";
					//}
					echo " >";
					echo "{$value}</option>";
				}//END FOREACH
				 ?>
		 </select>	
		<?php
		echo '</td>';		
		}else{
			echo  '<td><input name="prod_question_choix_reponse[]" type="hidden" class="formText" id="prod_question_choix_reponse[]" value="" /></td>';
		}

		echo '</tr>';
		
		echo '<tr><td colspan="9">&nbsp;<td></tr>';
}//END WHILE

echo '<tr><td align="center" colspan="9"><input type="submit" class="formText" name="Ajouter" id="Ajouter" value="Ajouter" /></td></tr></form>';

?>

    </div> <!-- /container -->
<br><br>
	
  <?php  include("inc/footer.inc.php"); ?>
  </body>
</html>
*/