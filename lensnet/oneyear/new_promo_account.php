<?php
session_start();
include("promo_functions.inc.php");

if ($_POST[requestTest]=="yes"){
	$success=create_account_promo_one_year();
}
if($success==true)
	header("Location:login_promo_new_acct.php");
else
	echo 'Une erreur est survenue, svp veuillez contacter notre équipe de support. An error occured Please contact our support team';
	exit();
?>
