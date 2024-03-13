<?php
require_once(__DIR__.'/../constants/ftp.constant.php');
                     
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

//$today=date("Y-m-d");
$today = "2012-02-06";
$file="PrecisionOrderData2-".$today.".csv";

$remote_file ="PrecisionOrderData2-".$today.".csv";

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