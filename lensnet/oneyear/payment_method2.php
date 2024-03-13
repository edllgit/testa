<?php 
session_start();

if ($_POST[payment_method]=='credit_card'){
	header("Location:credit_card.php");
	exit();
}else{
	header("Location:check.php");
	exit();
}
?>
