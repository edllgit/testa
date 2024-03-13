<?php 
/*
session_start();
session_destroy();
header("Location:index.php");
*/
require_once(__DIR__.'/../constants/url.constant.php');

//On vide ce qui appartient au client Direct-lens
session_start();
$_SESSION["sessionUserData"] = "";
$_SESSION["sessionUser_Id"]  = "";
$_SESSION['PrescrData']      = "";
header("Location:".constant('DIRECT_LENS_URL')."/direct-lens/connexion.php");
?>