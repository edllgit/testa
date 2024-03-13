<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

$orderNum = $_POST[print_credit_num];
 
if ($orderNum == ''){
	echo '<br>Erreur: Ce numéro de commande n\'est pas reconnu: ' . $orderNum;
	echo '<br>Error: This order number is not recognized: ' . $orderNum;
	exit(); 
}

if (strlen($orderNum) <> 7){
	echo '<br>Erreur: Ce numéro de commande n\'est pas reconnu: ' . $orderNum;
	echo '<br>Error: This order number is not recognized: ' . $orderNum;
	exit(); 
}

if (!is_numeric($orderNum)) {
	echo '<br>Erreur: Ce numéro de commande n\'est pas reconnu: ' . $orderNum;
	echo '<br>Error: This order number is not recognized: ' . $orderNum;
	exit(); 
}

//Rendu la le numero de commande est numérique, d'une longeur de 7 caractères

	$queryOrder  = "SELECT * FROM memo_credits WHERE mcred_order_num ='$orderNum '";
	$resultOrder = mysqli_query($con,$queryOrder)		or die  ('I cannot select items because 1: ' . mysqli_error($con));
	$NbrResult   = mysqli_num_rows($resultOrder);


	if ($NbrResult == 0){
		//Aucun résultat
		echo "<p>No credit have been found with this order number: $orderNum</p>";
	}//End IF
	
	
	//Un seul credit a été émis sur ce numero de commande
	if ($NbrResult == 1){
		$DataCredit=mysqli_fetch_array($resultOrder,MYSQLI_ASSOC);
		//Un seul résultat, on imprime le crédit	?>
		<script type="text/javascript">
		window.open( "../admin/print_credit.php?memo_num=<?php echo $DataCredit[mcred_memo_num] ?>" )
		</script>	
		<?php echo "<script>window.close();</script>";//Ferme la fenêtre puisque le crédit a été ouvert dans un nouvel onglet	?>	<p  ></p>
		<?php
	}//End IF on a 1 seul Résultat
	
	
	if ($NbrResult > 1){//Plusieurs résultats, il faut afficher les liens pour imprimer les différents crédits ?>
		<div align="center" draggable="true"   style="border-style: dashed; width: 680px; align-content:center;"><br> <?php echo $NbrResult;?> credits have been found for this order number, select the credit you want to print by clicking on it<br><br>
		<?php
		//echo '<p  style="border-style: dashed;" style="width: 600px;" align="center">'.$NbrResult . ' credits have been found for this order number, select the credit you want to print by clicking on it<br><br>';
		while ($DataOrder=mysqli_fetch_array($resultOrder,MYSQLI_ASSOC)){
		//Afficher les liens vers les différents crédits
		echo '<a target="_blank" href="'.constant('DIRECT_LENS_URL').'/admin/print_credit.php?memo_num='. $DataOrder[mcred_memo_num].'">'. $DataOrder[mcred_memo_num] .'</a><br><br>';
		}//End While
		echo '</p>';
		
	}//End If more than 1 credit found
	?>