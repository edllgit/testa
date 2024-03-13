<?php

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
?>
 <input type="hidden" name="id" value="<?php echo $_REQUEST[id]; ?>">
<?php


$queryInsert = "DELETE FROM ACCESS   WHERE id =" .$_REQUEST['id']  ;
$QueryResult=mysql_query($queryInsert)	or die ("Error: Could not delete access".   mysql_error());
echo $queryInsert . '<br>Access  Deleted.';
header("Location: listaccess.php");/* go to admin home page */
exit();
	
$query="select * from labs where primary_key = '$pkey'";
$labResult=mysql_query($query)	or die ("Could not find lab");
$labData=mysql_fetch_array($labResult);
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