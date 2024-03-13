<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");

session_start();
$today = date("Y-m-d");// current date

if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

//Traitement ici, retirer de la liste le numéro de commande qui est transmis
$order_num    = $_REQUEST[on];
if ($order_num > 1340000){
	$queryRetirer = "UPDATE orders SET frame_sent_saintcath = 'no' WHERE order_num = ".$order_num;
	$resultRetirer = mysqli_query($con,$queryRetirer) or die  ('<strong>Errors occured during the process:</strong> '. $queryRetirer . mysqli_error($con));
	echo $queryRetirer;
}
//Puis rediriger vers la liste une fois mis à jour
header("Location:rapport_monture_pour_labo.php");
exit();
?>
<html>
<head>
<title>Retirer une monture du rapport</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body>
</body>
</html>