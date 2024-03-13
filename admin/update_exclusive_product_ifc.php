<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("prod_functions.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if ($_GET[pkey] != ""){
	$pkey = $_GET[pkey];
	$heading="IFC.CA ADMIN PRODUCT FORM";
}
if ($_POST[createProduct] == "Create Product"){
	$pkey = create_ifcca_exclusive_product();
	$heading="IFC.CA EXCLUSIVE PRODUCT FORM&#8212;<font color=\"#FF0000\">PRODUCT CREATED iD=$pkey</font>";
}

if ($_POST[editProduct] == "Update Product"){
	$pkey = $_POST[pkey];
	edit_ifcca_exclusive_product($pkey);
	$heading="IFC.CA EXCLUSIVE PRODUCT FORM&#8212;<font color=\"#FF0000\">PRODUCT EDITED</font>";
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
  		<td width="75%"><?php
if ($_POST[deleteProduct] != "Delete Product"){
	include("exclusive_prodForm_ifc.php");
}else{
print "<table width=\"100%\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">
    <tr bgcolor=\"#000000\"> 
      <td><div align=\"center\"><b><font color=\"#FFFFFF\" size=\"2\" face=\"Helvetica, sans-serif, Arial\">$heading</font></b></div></td>
    </tr>";
	print "<tr bgcolor=\"#FFFFFF\">
    	<td nowrap align=\"center\"><p><font size=\"2\" face=\"Arial, Helvetica, sans-serif\">Product has been deleted. Select a different category from menu at left. </font></p></td></tr></table>";
}
?></td>
    </tr>
</table>
  
</body>
</html>
