<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

include "../connexion_hbc.inc.php";
session_start();
//On doit sauvegarder le nom de la shape uploadé  dans  le champ myupload de la table orders;

$order_num     					    = $_REQUEST[ordernum];


$key     					        = $_REQUEST[key];
$Nom_Trace 				            = substr($key,7,strlen($key)-7);
$_SESSION['RedoData']['myupload']   = $Nom_Trace;
$_SESSION['NomTraceReprise']   		= $Nom_Trace;

echo '<br>Order_num:'        .  $order_num;
echo '<br>Key:'        .  $key;
echo '<br>Longeur Key:'.  strlen($key);
echo '<br>Myupload:'. $_SESSION['RedoData']['myupload'];

if ((strlen($order_num)==5) && (strlen($Nom_Trace)>0)){
	//Mettre a jour dans la Base de donnée
	$queryMajNomTrace = "UPDATE orders 
	SET shape_name_bk = '$Nom_Trace',
			 myupload = '$Nom_Trace' 
	WHERE  order_num = $order_num";
	//echo '<br><br><br>'.$queryMajNomTrace;
	$resultMajTrace=mysqli_query($con,$queryMajNomTrace)	or die  ('I cannot select items because 1: ' . $queryMajNomTrace  . mysqli_error($con));
}


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Shape Uploaded Sucessfully</title>
</head>

<body onLoad="javascript:window.close()">  
 
</body>
</html>