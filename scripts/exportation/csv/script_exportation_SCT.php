<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
require_once(__DIR__.'/../../../constants/ftp.constant.php');
include("../../../sec_connect.inc.php");
include('../../../phpmailer_email_functions.inc.php');
require_once('../../../class.ses.php');
include("../../../export_functions.inc.php");
$time_start = microtime(true);

if ($_REQUEST[debug]=='yes'){
	$Debug = 'yes';
}

/*TODO:
1- Modifier de mysql vers mysqli: FAIT
2- Copier le fichier généré sur le ftp de DL dans le dossier SCT/FROM DIRECT_LENS:
3- Renommer fichier exporté   $filename = "SCT_OrderData-".$today.".csv";:
*/
?>

<html>
<head>
	<meta charset="utf-8\">
    <me//ta http-equiv="X-UA-Compatible" content="IE=edge">
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
$today    = date("Y-m-d");
//$today  = "2012-06-20";
//$filename = "../SCT/FROM DIRECT_LENS/SCT_OrderData-".$today.".csv";
$filename = "SCT_OrderData-".$today.".csv";
$fp       = fopen($filename, "c");
$orderQuery="SELECT distinct orders.* from orders
WHERE  orders.prescript_lab in (3,70) 
AND orders.order_status='processing' 
GROUP BY order_num ORDER BY orders.order_num";



$orderQuery="SELECT * FROM orders WHERE order_num in (
1478818,
1478819
) 
GROUP BY order_num ORDER BY orders.order_num";


if($Debug == 'yes')
echo '<br>Query: <br>'. $orderQuery . '<br>';

$orderResult = mysqli_query($con,$orderQuery)	or die  ('I cannot select items because 1.0: ' . mysqli_error($con));
$itemcount	 = mysqli_num_rows($orderResult);
echo '<table class="table">';
while ($orderData = mysqli_fetch_array($orderResult,MYSQLI_ASSOC)){
	echo '<tr><td>Order num</td><td>'. $orderData[order_num] . '</td></tr>';
	$outputstring = export_order_DLAB($orderData[order_num]);
	fwrite($fp,$outputstring);
}
		
fclose($fp);


//Connexion au FTP direct-lens pour copier le fichier dans le dossier SCT/FROM DIRECT_LENS
$ftp_server = constant("FTP_WINDOWS_VM"); //Updated: 2016-11-02 8 AM
$ftp_user   = constant("FTP_USER_SCT");

$ftp_pass   = constant("FTP_PASSWORD_SCT");
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
ftp_chdir($conn_id,"FROM DIRECT_LENS");
echo "<br>Dossier actuel: ".$directory."<br>";
$file        = "SCT_OrderData-".$today.".csv";
$remote_file = "SCT_OrderData-".$today.".csv";

// upload a file
if (ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {
 echo "successfully uploaded $file\n";
} else {
 echo "There was a problem while uploading $file\n";
}

ftp_close($conn_id);  
echo '<br>passe 3.6<br>';
if (is_file($file)) /* delete uploaded file */
	unlink($file);
	

echo '<div class="alert alert-success" role="alert"><strong> File ' . $filename . ' has been sucessfully written</strong></div>';


//Logger l'exécution du script
$time_end 		 = microtime(true);
$time	  		 = $time_end - $time_start;
$today    		 = date("Y-m-d");// current date
$heure_execution = date("H:i:s");
$CronQuery		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Exportation Saint-Catharines  (commandes vraiment faites a 100% par le lab) 2.0', '$time','$today','$heure_execution','script_exportation_STC.php')"; 					
$cronResult		 = mysqli_query($con,$CronQuery)	or die ( "Query failed: " . mysqli_error($con));	
?>
 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="..fichierquejenaipas../ie10-viewport-bug-workaround.js"></script>
  </body>
</html>