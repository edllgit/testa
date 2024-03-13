<?php include('../Connections/directlens.php'); ?>
<?php
	print "<br>";
	print "Saved Language to database..";
$mylangtable = $_POST["mylangtable"];
mysql_select_db($database_directlens, $directlens);
$query_languages = "SELECT * FROM ".$mylangtable;
$languages = mysql_query($query_languages, $directlens) or die(mysql_error());
$row_languages = mysql_fetch_assoc($languages);
$totalRows_languages = mysql_num_rows($languages);

do {
	$mysqlstr = "UPDATE ".$mylangtable." set ";
	$mypost = $row_languages["progkey"];
	$mysqlstr .= "languagetext = \"".$_POST[$mypost];
	$mysqlstr .= "\" WHERE progkey = \"".$row_languages['progkey']."\"";
	mysql_query("SET CHARACTER SET UTF8"); 
	$result = mysql_query($mysqlstr) or die(mysql_error());
	} while ($row_languages = mysql_fetch_assoc($languages)); 
	header("Location:step2.php");
?>