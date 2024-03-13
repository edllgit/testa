<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../../Connections/sec_connect.inc.php");
?>
 <input type="hidden" name="id" value="<?php print $_REQUEST[id]; ?>">
<?php


$queryInsert = "DELETE FROM ACCESS_ADMIN   WHERE id =" .$_REQUEST['id']  ;
$QueryResult=mysql_query($queryInsert)	or die ("Error: Could not delete access". '<br>'.$queryInsert . ' <br><br>'.  mysql_error());
echo $queryInsert . '<br>Access  Deleted.';
header("Location: listaccess.php");/* go to admin home page */
exit();

?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {font-size: 7px}
-->
</style>
</head>
<body>
</body>
</html>
