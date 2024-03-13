<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../connexion_hbc.inc.php";
include("prod_functions_hbc.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

if ($_GET[pkey] != ""){
	$pkey = $_GET[pkey];
	$heading="HBC ADMIN PRODUCT FORM";
}

if ($_POST[editProduct] == "Update Product"){
	$pkey = $_POST[pkey];
	edit_hbc_exclusive_product($pkey);
	$heading="HBC EXCLUSIVE PRODUCT FORM  <font color=\"#FF0000\">PRODUCT EDITED</font>";
}
?>
<html>
<head>
<title>Direct-Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<?php
	include("exclusive_prodForm_hbc.php");
?></td>
    </tr>
</table>
  
</body>
</html>
