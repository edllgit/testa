<?php
//session_start();
//if ($_SESSION[labAdminData][username]==""){
//	echo  "You are not logged in. Click <a href='index.htm'>here</a> to login.";
//	exit();
//}
//session_destroy();
//header("Location:/labAdmin");
?>


<?php
session_start();
//if ($_SESSION[labAdminData][username]==""){
//	echo  "You are not logged in. Click <a href='index.htm'>here</a> to login.";
//	exit();
//}
$_SESSION["labAdminData"] = "";
header("Location:/hbcAdmin");
?>