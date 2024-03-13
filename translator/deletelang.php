<?php include('../Connections/directlens.php'); ?>
<?php
$mylangtable = $_POST["mydellang"];
mysql_select_db($database_directlens, $directlens);
$query_languages1 = "DROP TABLE lang_".$mylangtable;
$languages1 = mysql_query($query_languages1, $directlens) or die(mysql_error());
$query_languages2 = "delete from languages where mysql_table = 'lang_".$mylangtable."'";
$languages2 = mysql_query($query_languages2, $directlens) or die(mysql_error());
//echo $query_languages;
header("Location:step1.php");
?>