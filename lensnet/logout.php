<?php /*?>session_start();
session_destroy();
header("Location:index.php");<?php */?>
<?php
//On vide ce qui appartient au client Lensnet
session_start();
$_SESSION["sessionUserData"] = "";
$_SESSION["sessionUser_Id"]  = "";
$_SESSION['PrescrData']      = "";
header("Location:login.php");
?>