<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');

require_once(__DIR__.'/../../../constants/ftp.constant.php');

//Connexion Database HBC
include("../../../connexion_hbc.inc.php");

include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start  = microtime(true);

//CREATE EXPORT FILE//
$today	  = date("Y-m-d");
$filename = "GKB_HBC_OrderData-".$today.".csv";//Respecte le courriel envoyé à GKB le 28 Janvier 2020
$fp       = fopen($filename, "w");


$orderQuery="SELECT * FROM orders 	WHERE order_status  IN ('processing')  AND order_product_type='exclusive' AND prescript_lab =  4    ORDER by order_num";

//A RECOMMENTER APRES MON TEST
//$orderQuery="SELECT * FROM orders 	WHERE order_num in (33757,34777,34810,34816,34830)    ORDER by order_num";

//TODO  faire un test d'exportation avec quelques commandes
echo $orderQuery;

echo '<br><br>';
$orderResult=mysqli_query($con, $orderQuery)	or die  ('I cannot select items because: ' . mysqli_error());
$itemcount=mysqli_num_rows($orderResult);
$headerstring=get_header_string_HKO();


fwrite($fp,$headerstring);
	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	$outputstring=export_order_gkb_hbc($orderData[order_num]);
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
ftp_chdir($conn_id,"Order HBC");


echo "<br>Dossier actuel: ".$directory."<br>";

$file        = "GKB_HBC_OrderData-".$today.".csv";
$remote_file = "GKB_HBC_OrderData-".$today.".csv";

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
