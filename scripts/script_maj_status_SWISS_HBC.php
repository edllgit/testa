<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR); 
//ini_set('display_errors', '1');
?>
<style type="text/css">
<!--
.TextSize {
	font-size: 11pt;
	font-family: Arial, Helvetica, sans-serif;
}
-->
</style>
<div class="TextSize">
<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
//ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');  
$time_start = microtime(true); 

//Credentials de Glasses Gallery                   
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
ftp_pasv($conn_id,true);//IMPORTANT mode passif nécessaire pour le ftp direct-lens sur serveur lunarpages TODO: EST_CE NÉCESSAIRE POUR SERVEUR Windows VM ?? A VALIDER!
ftp_chdir($conn_id,"TO_GG");
$directory=ftp_pwd($conn_id);

echo "<br>".$directory."<br>";

$contents=ftp_nlist($conn_id, ".");

$max=0;
$newest_file="";

echo' <br>Debut recherche du fichier le plus recent..';

foreach ($contents as $value) {//FIND NEWEST FILE
	
	$time=ftp_mdtm($conn_id,$value);
		//if ((strpos($value,"status")!==false)&&(strpos($value,".csv[2018")!==false)){
		if ((strpos($value,"status_0D013_[202")!==false)&&(strpos($value,".csv")!==false)){
		if ($time>$max){
			$max=$time;
			$newest_file=$value;
		}
	}
 	//echo "$value $time<br />\n";
}
echo "<br>Plus Recent:".$newest_file;

$time_end = microtime(true);
$time =  $time_end  - $time_start;
echo "Execution time recherche du csv:  $time seconds\n";


$local_file  = 'Fichier_Temporaire_MAJ_Status_SWISS_HBC.csv';
$server_file = $newest_file;

// try to download $server_file and save to $local_file
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo "<br>Successfully written $server_file to $local_file\n";
} else {
    echo "There was a problem\n";
}

// close the connection

$orderArray=array();
ftp_close($conn_id);  

$row = 1;
$handle = fopen("Fichier_Temporaire_MAJ_Status_SWISS_HBC.csv", "r");
$count=0;

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {//COLLECT DATA INTO ARRAY FIRST
	$count++;
	$order_num=$orderArray[$count][1]=$data[1];
	$code_update=$orderArray[$count][2]=$data[3];
	
	echo '<br>ORDER NUM:'.   $order_num;
	echo '<br>CODE UPDATE:'. $code_update;
	
		switch($code_update){
				case "300":				$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
				case "205":				$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
				case "302":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "210":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "306":				$file_order_status= "in coating";			$file_order_status_display="In Coating";			break;
				case "211":				$file_order_status= "in coating";			$file_order_status_display="In Coating";			break;
				case "325":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "307":				$file_order_status= "order completed";		$file_order_status_display="Order Completed";		break;
				case "228":				$file_order_status= "order completed";		$file_order_status_display="Order Completed";		break;
				case "235":				$file_order_status= "in transit";			$file_order_status_display="In Transit";			break;
				case "308":				$file_order_status= "in mounting";			$file_order_status_display="In Mounting";			break;
				case "200":				$file_order_status= "in mounting";			$file_order_status_display="In Mounting";			break;
				case "330":				$file_order_status= "in transit";			$file_order_status_display="In Transit";			break;
				case "500":				$file_order_status= "delay issue 0";		$file_order_status_display="Delay Issue 0";			break;
				case "501":				$file_order_status= "delay issue 1";		$file_order_status_display="Delay Issue 1";			break;
				case "502":				$file_order_status= "delay issue 2";		$file_order_status_display="Delay Issue 2";			break;
				case "503":				$file_order_status= "delay issue 3";		$file_order_status_display="Delay Issue 3";			break;
				case "504":				$file_order_status= "delay issue 4";		$file_order_status_display="Delay Issue 4";			break;
				case "505":				$file_order_status= "delay issue 5";		$file_order_status_display="Delay Issue 5";			break;
				case "506":				$file_order_status= "delay issue 6";		$file_order_status_display="Delay Issue 6";			break;
				case "508":				$file_order_status= "waiting for frame swiss";	$file_order_status_display="Waiting for Frame Swiss";		break;
				case "601":				$file_order_status= "waiting for frame swiss";	$file_order_status_display="Waiting for Frame Swiss";		break;
				case "509":				$file_order_status= "waiting for shape";	$file_order_status_display="Waiting for Shape";		break;
				default:				$file_order_status= "";				    	$file_order_status_display="NO CODE RECOGNIZED";	break;
			}
	
		$query="SELECT primary_key,order_date_processed,order_status,order_num,order_product_id FROM orders WHERE order_num='$order_num'";
		echo $query .'<br>';
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: dhs76 . query:' . $query . ' ' . mysqli_error($con));
		$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	
	
		if ($listItem[order_num]==$order_num){
			
			switch($listItem[order_status]){
				case "cancelled":					$order_status_display= "Cancelled";				break;
				case "processing":					$order_status_display= "Confirmed";				break;
				case "order imported":				$order_status_display= "Order Imported";		break;
				case "job started":					$order_status_display= "In Production";			break;
				case "in coating":					$order_status_display= "In Coating";			break;
				case "in mounting":					$order_status_display= "In Mounting";			break;
				case "order completed":				$order_status_display= "Order Completed";		break;
				case "delay issue 0":				$order_status_display= "Delay Issue 0";			break;
				case "delay issue 1":				$order_status_display= "Delay Issue 1";			break;
				case "delay issue 2":				$order_status_display= "Delay Issue 2";			break;
				case "delay issue 3":				$order_status_display= "Delay Issue 3";			break;
				case "delay issue 4":				$order_status_display= "Delay Issue 4";			break;
				case "delay issue 5":				$order_status_display= "Delay Issue 5";			break;
				case "delay issue 6":				$order_status_display= "Delay Issue 6";			break;	
				case "waiting for frame":			$order_status_display= "Waiting for Frame";		break;
				case "waiting for frame swiss":		$order_status_display= "Waiting for Frame Swiss";	break;
				case "waiting for lens":			$order_status_display= "Waiting for Lens";		break;
				case "waiting for shape":			$order_status_display= "Waiting for Shape";		break;
				case "information in hand":			$order_status_display= "Information in Hand";	break;
				case "on hold":						$order_status_display= "On Hold";				break;
				case "re-do":						$order_status_display= "Re-do";					break;
				case "in transit":					$order_status_display= "In Transit";			break;
				case "filled":						$order_status_display= "Shipped";				break;
				default:							$order_status_display= "NO STATUS";				break;
			}
			
						
			if (($file_order_status!="")&&($listItem[order_status]!="filled")&&($listItem[order_status]!="cancelled")){
			$files_changed++;
				
			
			$queryStatus = "SELECT COUNT(status_history_id) AS NBR FROM STATUS_HISTORY WHERE order_num = $order_num  and order_status = '". $file_order_status. "'";
			echo $queryStatus .'<br>';
			$resultStatus=mysqli_query($con,$queryStatus)		or die ('Could not update because: ' . mysqli_error($con));
			$DataStatus=mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
			$ExisteDeja = $DataStatus['NBR'];

		
		
			//	echo '<br>'. $queryStatus. '<br>';
			//	echo  ' Existe deja (0 = non, 1=oui):' . $ExisteDeja. '<br>';
		
				if ($ExisteDeja == 0) {
				

					$statusQuery="UPDATE orders SET order_status='$file_order_status' WHERE order_num=$order_num AND order_status!='basket'"; //UPDATE THE STATUS OF THE ORDER
					echo $statusQuery. '<br>';
					$statusResult=mysqli_query($con,$statusQuery)			or die ( "Query failed: " . mysqli_error($con));//TODO
				
					//Code ajouté par Charles 2010-07-23
					$todayDate = date("Y-m-d g:i a");// current date
					$currentTime = time($todayDate); //Change date into time
					$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
					$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
					$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$order_num', '$datecomplete','update script S 2.0')";
					echo $queryStatusHistory .'<br>';
					$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
					//Fin code ajouté par Charles
					
					}else {
						echo "<tr><td align=\"right\">No Update :</td><td><b>".$listItem[order_status]. ' ' .	$file_order_status_display . "</b></td></tr>";
					}
			
				}
			
					
			}//END IF DIFFERENT
	
}//END WHILE
echo '<br><br>Decompte:'.  $count;
fclose($handle);



$time_end = microtime(true);
$time =  $time_end  - $time_start;
echo "Execution time:  $time seconds\n";
/*
$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Update status Swiss', '$time','$today','$timeplus3heures','cron_update_satus.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
*/
?>
</div>