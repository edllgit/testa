<?php
session_start();
include("includes/pw_functions.inc.php");

if ($_POST[requestTest]=="yes"){
	$success=create_newsletter_member();
}
if($success==true)
	header("Location:RegisterThanks.php");
else
	header("Location:accountProblem.php");
?>
