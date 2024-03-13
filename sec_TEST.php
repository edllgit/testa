<?php
require_once(__DIR__.'/constants/mysql.constant.php');
	
global $con;
$con =mysqli_connect(constant('MYSQL_HOST_TEST'), constant("MYSQL_USER"), constant("MYSQL_PASSWORD"), constant("MYSQL_DB_DIRECT_LENS"));
// Check connection
if (mysqli_connect_errno()){
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

$usa_key = "m4a1x0vCi0eV77mpiAzfd53JSCyOD9Yt";

if (!function_exists('money_format')){
	function money_format($myspec, $myamount){
	$myreturn = $lbl_moneysym.number_format($myamount, 2, '.', '');
	// 1234.57
	// '%.2n',$acctTotal
	return $myreturn;
	}
}

?>