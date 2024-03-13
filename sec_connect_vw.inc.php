<?php

ini_set("display_errors", 1); 
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~8192);  

require_once(__DIR__.'/constants/mysql.constant.php');

$mysql_user=constant("MYSQL_USER");
$mysql_host=constant("MYSQL_HOST");
$mysql_password=constant("MYSQL_PASSWORD");
$mysql_db=constant("MYSQL_DB_VISION_WONDERS");


//MySql procedural
		
$dbh=mysql_connect ("$mysql_host", "$mysql_user", "$mysql_password") or die ('I cannot connect to the server because: ' . mysql_error());
$db=mysql_select_db($mysql_db, $dbh)
		or die  ('I cannot connect to the database because: ' . mysql_error());
		
//MySqli oo

$mysqli = new mysqli($mysql_host, $mysql_user,$mysql_password, $mysql_db);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

?>
<?php
if (!function_exists('money_format')){
	function money_format_custom($myspec, $myamount){
	$myreturn = $lbl_moneysym.number_format($myamount, 2, '.', '');
	// 1234.57
	// '%.2n',$acctTotal
	return $myreturn;
	}
}
?>