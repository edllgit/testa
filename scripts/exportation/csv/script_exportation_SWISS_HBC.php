<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
require_once(__DIR__.'/../../../constants/ftp.constant.php');
//Connexion BD HBC
include("../../../connexion_hbc.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start  = microtime(true);

//CREATE EXPORT FILE//
$today=date("Y-m-d");
$filename="GGRetailStoreData-".$today.".csv";
$fp=fopen($filename, "w");

$orderQuery="SELECT * from orders 	WHERE order_status='processing' AND  order_product_type='exclusive' AND prescript_lab = 10    ORDER by order_num";


//REMETTRE EN COMMENTAIRE
/*
$orderQuery="SELECT * from orders 	WHERE  order_num IN (
37498
)";*/

echo $orderQuery;


$orderResult=mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: 1 ' . mysql_error($con));
$itemcount=mysqli_num_rows($orderResult);
echo '<br>Item count:'. $itemcount;

$headerstring=get_header_string_swiss_2014();

fwrite($fp,$headerstring);
	while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	echo '<br>'.  	$orderData[order_num];
	$outputstring=export_order_swiss_HBC($orderData[order_num]);
	fwrite($fp,$outputstring);
}

fclose($fp);

?>
  
<?php    
/*
//Credentials pour Direct-Lens, Lensnetclub, IFc.ca, Lensnetclub, tout avec Swiss Sauf Glasses Gallery(HBC)     
$ftp_server = constant("SWISSCOAT_FTP");
$ftp_user = constant("FTP_USER_RCO");

$ftp_pass = constant("FTP_PASSWORD_RCO");
*/

 //Credentials pour Glasses Gallery seulement
$ftp_server = constant("SWISSCOAT_FTP");
$ftp_user = constant("FTP_USER_0D013");

$ftp_pass = constant("FTP_PASSWORD_0D013");


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


ftp_chdir($conn_id,"FROM_GG");
$file        = "GGRetailStoreData-".$today.".csv";
$remote_file = "GGRetailStoreData-".$today.".csv";

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
//Move the file to Swisscoat ftp  (GG credentials)               
$ftp_server = constant("SWISSCOAT_FTP");
$ftp_user = constant("FTP_USER_0D013");

$ftp_pass = constant("FTP_PASSWORD_0D013");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}

ftp_chdir($conn_id,"FROM_GG");
$today=date("Y-m-d");
$file        = "GGRetailStoreData-".$today.".csv";
$remote_file = "GGRetailStoreData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

ftp_close($conn_id);  


?>
