<?php
//session_start();
//if ($_SESSION[adminData][username]==""){
//	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
//	exit();
//}
//session_destroy();
//header("Location:index.htm");
?>

<?php
session_start();
$_SESSION["adminData"] = "";
header("Location:/admin");
?>
