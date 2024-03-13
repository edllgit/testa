<?php
session_start();
include("promo_functions.inc.php");
//include("Connections/sec_connect.inc.php");

	$user_test = stripslashes($_POST[user_id]);
	$password_test = stripslashes($_POST[password]);
	$_SESSION["sessionUserData"]=login_to_dl($user_test, $password_test);
	$_SESSION["sessionUserData"]["id"]=stripslashes($_SESSION["sessionUserData"]["primary_key"]);
	$_SESSION["sessionUserData"]["company"]=stripslashes($_SESSION["sessionUserData"]["company"]);
	$_SESSION["sessionUserData"]["first_name"]=stripslashes($_SESSION["sessionUserData"]["first_name"]);
	$_SESSION["sessionUserData"]["last_name"]=stripslashes($_SESSION["sessionUserData"]["last_name"]);
	$_SESSION["sessionUserData"]["address1"]=stripslashes($_SESSION["sessionUserData"]["address1"]);
	$_SESSION["sessionUserData"]["address2"]=stripslashes($_SESSION["sessionUserData"]["address2"]);
	$_SESSION["sessionUserData"]["city"]=stripslashes($_SESSION["sessionUserData"]["city"]);
	$_SESSION["sessionUserData"]["zip"]=stripslashes($_SESSION["sessionUserData"]["zip"]);
	$_SESSION["sessionUserData"]["phone"]=stripslashes($_SESSION["sessionUserData"]["phone"]);
	$_SESSION["sessionUserData"]["fax"]=stripslashes($_SESSION["sessionUserData"]["fax"]);
	$_SESSION["sessionUserData"]["email"]=stripslashes($_SESSION["sessionUserData"]["email"]);
	$_SESSION["sessionUserData"]["bg_name"]=stripslashes($_SESSION["sessionUserData"]["bg_name"]);
	$_SESSION["sessionUserData"]["global_dsc"]=stripslashes($_SESSION["sessionUserData"]["global_dsc"]);
		
$_SESSION["sessionUser_Id"]=$_SESSION["sessionUserData"]["user_id"];
$_SESSION["id"]=$_SESSION["sessionUserData"]["id"];

if($_SESSION["sessionUserData"]["account_type"]=="restricted") {
$_SESSION['account_type']='restricted';
}else {
$_SESSION['account_type']='normal';
}

$_SESSION["sessionUserData"]["account_type"]=="normal";
$_SESSION['account_type']='normal'; 

	header("Location:payment_method.php");
	exit();
?>
