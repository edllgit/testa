<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
require_once(__DIR__.'/../../../constants/ftp.constant.php');
include("../../../sec_connectEDLL.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start  = microtime(true);

//echo '<br>'.var_dump(__DIR__.'/../../../constants/ftp.constant.php');
//CREATE EXPORT FILE//
$today=date("Y-m-d");
//$filename="PrecisionOrderData-".$today.".csv";
$filename="PrecisionOrderData-".$today.".csv";
$fp=fopen($filename, "w");


$orderQuery="SELECT * from orders 	WHERE order_status='processing' AND  order_product_type='exclusive' AND prescript_lab = 10    ORDER by order_num";


//REMETTRE EN COMMENTAIRE
/*$orderQuery="SELECT * from orders 	WHERE  order_num IN (
1584936)";
*/


echo $orderQuery;


$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysql_error($con));
$itemcount=mysqli_num_rows($orderResult);
echo '<br>Item count:'. $itemcount;

$headerstring=get_header_string_swiss_2014();

fwrite($fp,$headerstring);
	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	echo '<br>'.  	$orderData[order_num];
	$outputstring=export_order_swiss_2014($orderData[order_num]);
	fwrite($fp,$outputstring);
}

fclose($fp);
?>
  
<?php           
$ftp_server = constant("SWISSCOAT_FTP");
$ftp_user = constant("FTP_USER_RCO");

$ftp_pass = constant("FTP_PASSWORD_RCO");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}

//Enable PASV ( Note: must be done after ftp_login() 
ftp_pasv($conn_id, true);


ftp_chdir($conn_id,"FROM_DL");
$file        = "PrecisionOrderData-".$today.".csv";
$remote_file = "PrecisionOrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

ftp_close($conn_id);  

if (is_file($file)) /* delete uploaded file */
	unlink($file);
?>

<?php
//Move the file to Swisscoat ftp                  
$ftp_server = constant("SWISSCOAT_FTP");
$ftp_user = constant("FTP_USER_RCO");

$ftp_pass = constant("FTP_PASSWORD_RCO");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}

ftp_chdir($conn_id,"FROM_DL");
$today=date("Y-m-d");
$file        = "PrecisionOrderData-".$today.".csv";
$remote_file = "PrecisionOrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

ftp_close($conn_id);  

//if (is_file($file)) /* delete uploaded file */
//	unlink($file);

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time  			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Exportation Swiss 2.0', '$time','$today','$heure_execution','script_exportation_SWISS.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery)		or die ( "Query failed: " . mysqli_error($con));	
?>
