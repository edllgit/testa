<?php
require_once(__DIR__.'/../../constants/mysql.constant.php');

# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$mysql_user     = constant("MYSQL_USER");
$mysql_host     = constant("MYSQL_HOST");
$mysql_password = constant("MYSQL_PASSWORD");
$mysql_db		= constant("MYSQL_DB_HBC");
$dbh			= mysql_connect ("$mysql_host", "$mysql_user", "$mysql_password") or die ('I cannot connect to the server because: ' . mysql_error());
$db				= mysql_select_db($mysql_db, $dbh)		or die  ('I cannot connect to the database because: ' . mysql_error());
$usa_key 		= "m4a1x0vCi0eV77mpiAzfd53JSCyOD9Yt";


if (!function_exists('money_format')){
	function money_format($myspec, $myamount){
	$myreturn = $lbl_moneysym.number_format($myamount, 2, '.', '');
	return $myreturn;
	}
}


?>