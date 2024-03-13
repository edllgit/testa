<?php
session_start();
include("../includes/pw_functions.inc.php");
include("../Connections/sec_connect.inc.php");

	$user_test = stripslashes($_POST[user_id]);
	$password_test = stripslashes($_POST[password]);
	$_SESSION["sessionBGData"]=login_to_bg($user_test, $password_test);

$_SESSION["BG_pkey"]=$_SESSION["sessionBGData"]["primary_key"];
//$_SESSION["sessionUser_Id"]=$_SESSION["sessionBGData"]["username"];
header("Location: BGAccount.php");
?>
