<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/labAdmin'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include("lab_confirmation_func.inc.php");
include("fax_lab_confirm_func.inc.php");
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
include("../sales/salesmath.php");

$original_order_num = mysql_real_escape_string($_REQUEST[original_order_num]);
$queryInfoOriginale = "SELECT order_num, primary_key FROM orders WHERE order_num = $original_order_num";
$resultOriginale    = mysql_query($queryInfoOriginale)		or die ("Erreur durant la requete, svp contater le service informatique");
$DataOriginale      = mysql_fetch_array($resultOriginale);
	
echo '<br>'     . $queryInfoOriginale;
echo '<br>Pkey:'. $DataOriginale[primary_key];
echo '<br># Num'. $DataOriginale[order_num];

//Rediriger vers la prochaine page du processus de reprise (re-doV3.php)
header("Location:re-doV3.php?pkey=$DataOriginale[primary_key]&order_num=$DataOriginale[order_num]");
exit();

//Formulaire pour amasser les informations nécessaires (clé primaire et numéro de commande de l'originale) afin de  démarrer la création du Squelette de la reprise



//Rendu ICI !
/*
<form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="re-do.php" style="margin: 0px; padding: 0px;">
               
 
				<input name="pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" />
				<input name="order_num" type="hidden" value="<?php echo $listItem[order_num]?>" />


</form>
	*/
?>