<?php
require_once(__DIR__.'/../../constants/mysql.constant.php');

//mysql_select_db($con,$database_hbc);
mysqli_query($con,"SET CHARACTER SET UTF8");
$query_languages = "SELECT * FROM languages";
$languages = mysqli_query($con,$query_languages) or die(mysqli_error($con));
$row_languages = mysqli_fetch_array($languages,MYSQLI_ASSOC);
$totalRows_languages = mysqli_num_rows($languages);

//Default language	
$mylang = "lang_english";
	
if(!isset($_COOKIE["mylang"])){
//assign eng
$mylang = "lang_english";
} else {
$mylang = $_COOKIE["mylang"];
}

global $con;
$mysql_host     = constant("MYSQL_HOST");
$mysql_user     = constant("MYSQL_USER");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_DIRECT_LENS");

$con =mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_db);
// Check connection
if (mysqli_connect_errno())  {
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}



mysql_select_db($con,$database_hbc);
$query_languagetext = "SELECT * FROM ".$mylang;
$languagetext = mysql_query($query_languagetext) or die(mysql_error());
$row_languagetext = mysql_fetch_assoc($languagetext);
$totalRows_languagetext = mysql_num_rows($languagetext);
do { 
	$mystr = "$".$row_languagetext['progkey']."=\"".$row_languagetext['languagetext']."\";";
	eval($mystr);
	//echo $mystr."<br>";
	} while ($row_languagetext = mysql_fetch_assoc($languagetext)); 

?>