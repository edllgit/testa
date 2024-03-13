<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
require_once(__DIR__.'/../../../constants/ftp.constant.php');
include("../../../sec_connectEDLL.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start = microtime(true);

if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}
?>
<html>
<head>
	<meta charset="utf-8\">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport\" content=\"width=device-width, initial-scale=1">
	<!-- Bootstrap core CSS -->
    <link href="http://www.direct-lens.com/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<?php
//CREATE EXPORT FILE//
$today      = date("Y-m-d");
//$filename   = "../hko/FROM DIRECT-LENS/HKO_OrderData-".$today.".csv";
$filename   = "KNR_OrderData-".$today.".csv";
			
$fp         = fopen($filename, "c");
$orderQuery = "SELECT * FROM orders
LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
WHERE order_status='processing' AND order_product_type='exclusive' AND orders.prescript_lab = 73 
ORDER BY order_num";

/*
//A REMETTRE EN COMMENTAIRE
$orderQuery = "SELECT * FROM orders
LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
WHERE order_num in (
1492557,
1492558,
1492559,
1492560
)
ORDER BY order_num";
*/

/*
$orderQuery = "SELECT * FROM orders
LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
WHERE  order_num in (
1478476
)
ORDER BY order_num";
*/


if($Debug == 'yes')
echo '<br>Query: <br>'. $orderQuery . '<br>';

$orderResult  = mysqli_query($con,$orderQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$itemcount    = mysqli_num_rows($orderResult);
$headerstring = get_header_string_KNR();
fwrite($fp,$headerstring);

while ($orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	$outputstring=export_order_KNR($orderData[order_num]);
	fwrite($fp,$outputstring);
}
fclose($fp);

 
//Credentials pour Nouveau FTP sur instance AWS [Windows VM]
$ftp_server = constant("FTP_WINDOWS_VM"); 
$ftp_user   = constant("FTP_USER_KANDR");//Créé le 25 Septembre 20109

$ftp_pass   = constant("FTP_PASSWORD_KANDR");

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
ftp_chdir($conn_id,"ftp_root/Echange avec Fournisseurs/K and R/FROM DIRECT-LENS/Order Jobs");
$file        = "KNR_OrderData-".$today.".csv";
$remote_file = "KNR_OrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}


ftp_close($conn_id);  
if (is_file($file)) /* delete uploaded file */
	unlink($file);
	
 //Logger l'exécution du script
$time_end        = microtime(true);
$time            = $time_end - $time_start;
$today           = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery       = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Exportation KNR 2.0', '$time','$today','$heure_execution','script_exportation_KNR.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	

?>
