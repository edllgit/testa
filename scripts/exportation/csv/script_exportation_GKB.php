<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
require_once(__DIR__.'/../../../constants/ftp.constant.php');
include("../../../sec_connectEDLL.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start  = microtime(true);

//CREATE EXPORT FILE//
$today	  = date("Y-m-d");
$filename = "GKB_OrderData-".$today.".csv";
$fp       = fopen($filename, "w");


$orderQuery="SELECT * FROM orders 	WHERE order_status  IN ('processing')  AND order_product_type='exclusive' AND prescript_lab = 69    ORDER by order_num";

//A RECOMMENTER 
/*
$orderQuery="SELECT * FROM orders 	WHERE order_num in (
1565739,
1565671,
1565672,
1565756,
1565758,
1565669,
1565686,
1565688,
1565695,
1565696,
1565742,
1565755)    ORDER by order_num";
*/
echo $orderQuery;

echo '<br><br>';
$orderResult=mysqli_query($con, $orderQuery)	or die  ('I cannot select items because: ' . mysqli_error());
$itemcount=mysqli_num_rows($orderResult);
$headerstring=get_header_string_HKO();


fwrite($fp,$headerstring);
	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	$outputstring=export_order_gkb_2015($orderData[order_num]);
	fwrite($fp,$outputstring);
}

fclose($fp);
?>
  
  
<?php       
$ftp_server = constant("GKB_FTP"); //Updated: 2016-11-02 8 AM
$ftp_user   = constant("FTP_USER_DLN");

$ftp_pass   = constant("FTP_PASSWORD_DLN");

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

ftp_chdir($conn_id,"Directlab");
ftp_chdir($conn_id,"Order");


echo "<br>Dossier actuel: ".$directory."<br>";

$file        = "GKB_OrderData-".$today.".csv";
$remote_file = "GKB_OrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}
//// */

 /*       //new adress pour test FTP  mis en pause apres test

  
//Credentials pour Nouveau FTP sur instance AWS
$ftp_server = constant('FTP_WINDOWS_VM'); //Updated:2019-04-04
$ftp_user   = constant('FTP_USER_HKO');
$ftp_pass   = constant('FTP_PASSWORD_HKO');

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

ftp_chdir($conn_id,"Directlab");
ftp_chdir($conn_id,"Order");

echo "<br>Dossier actuel: ".$directory."<br>";

$file        = "GKB_OrderData-".$today.".csv";
$remote_file = "GKB_OrderData-".$today.".csv";
$file1        = "test_OrderData-".$today.".csv";
$remote_file1 = "test_OrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

if (ftp_put($conn_id, $remote_file1, $file1, FTP_BINARY)) {
 echo "successfully uploaded $file1\n";
} else {
 echo "There was a problem while uploading $file1\n";
}



 */


ftp_close($conn_id);  
echo '<br>passe 3.6<br>';
if (is_file($file)) /* delete uploaded file */
	unlink($file);
	


	
//Logger l'exécution du script	
$time_end 		 = microtime(true);
$time 			 = $time_end - $time_start;
$today 			 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Exportation GKB 2.0', '$time','$today','$heure_execution','script_exportation_GKB.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery)	or die ( "Query failed: " . mysqli_error($con));
?>
