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
ini_set('display_errors', '1');
//Connexion DataBase-->HBC
include("../connexion_hbc.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');   	
$time_start = microtime(true);  

//Credentials pour Nouveau FTP sur instance AWS [Windows VM]
$ftp_server = constant("FTP_WINDOWS_VM"); //Updated:2019-04-04
$ftp_user   = constant("FTP_USER_HKO");

$ftp_pass   = constant("FTP_PASSWORD_HKO");

//set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}
ftp_pasv($conn_id,true);
$directory=ftp_pwd($conn_id);
echo "<br>".$directory."<br>";
//Select the folder TO DIRECT LENS
ftp_chdir($conn_id,"TO_DIRECT_LENS");

//Select the folder HBC
echo "<br>".$directory."<br>";
ftp_chdir($conn_id,"HBC");

$contents=ftp_nlist($conn_id, "");
$max=0;
$newest_file="";



foreach ($contents as $value) {//FIND NEWEST FILE
	
	$time=ftp_mdtm($conn_id,$value);
	if ((strpos($value,"order")!==false)&&(strpos($value,".csv")!==false)){
		if ($time>$max){
			$max=$time;
			$newest_file=$value;
		}
	}
 	echo "$value $time<br />\n";
}

//EN ATTENTE D'AVOIR DES FICHIERS DE MAJ EN PROVENANCE D'HKO.. TOUT D'ABORD ILS DEVRONT RECEVOIR DES COMMANDES.. 


$local_file='TempFile_Fichier_temporaire_maj_status_HKO678915742.csv';
$server_file =$newest_file;


echo "<br>Plus récent: ".$newest_file;

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
$handle = fopen("TempFile_Fichier_temporaire_maj_status_HKO678915742.csv", "r");
$count=0;

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {//COLLECT DATA INTO ARRAY FIRST
	$count++;
	$orderArray[$count][1]=$data[1];
	$orderArray[$count][2]=$data[3];
}

fclose($handle);

$start_time=microtime(true);

//LOOP THROUGH ARRAY
echo "<table width=\"600\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$files_changed=0;
    for ($c=0; $c <=$count; $c++) {
	
		$order_num=$orderArray[$c][1];
		
		
		$file_order_status_display='';
		
			switch($orderArray[$c][2]){
				case "300":				$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
				case "302":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "325":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "306":				$file_order_status= "in coating";			$file_order_status_display="In Coating";			break;
				case "307":				$file_order_status= "order completed";		$file_order_status_display="Order Completed";		break;
				case "308":				$file_order_status= "in mounting";			$file_order_status_display="In Mounting";			break;
				case "330":				$file_order_status= "in transit";			$file_order_status_display="In Transit";			break;
				case "500":				$file_order_status= "delay issue 0";		$file_order_status_display="Delay Issue 0";			break;
				case "501":				$file_order_status= "delay issue 1";		$file_order_status_display="Delay Issue 1";			break;
				case "502":				$file_order_status= "delay issue 2";		$file_order_status_display="Delay Issue 2";			break;
				case "503":				$file_order_status= "delay issue 3";		$file_order_status_display="Delay Issue 3";			break;
				case "504":				$file_order_status= "delay issue 4";		$file_order_status_display="Delay Issue 4";			break;
				case "505":				$file_order_status= "delay issue 5";		$file_order_status_display="Delay Issue 5";			break;
				case "506":				$file_order_status= "delay issue 6";		$file_order_status_display="Delay Issue 6";			break;
				case "508":				$file_order_status= "waiting for frame"; 	$file_order_status_display="Waiting for Frame";	break;
				case "509":				$file_order_status= "waiting for shape";	$file_order_status_display="Waiting for Shape";		break;
				case "510":				$file_order_status= "in edging";	    	$file_order_status_display="";						break;
				default:				$file_order_status= "";						$file_order_status_display="NO CODE RECOGNIZED";	break;
			}
	
		$query="SELECT primary_key,order_date_processed,order_status,order_num,order_product_id FROM orders WHERE order_num='$order_num'";
		echo '<br><br>'. $query;
		$result=mysqli_query($con,$query)	or die  ('I cannot select items because: ' . mysqli_error($con));
		$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
				
		if ($listItem[order_num]==$orderArray[$c][1]){
			
			switch($listItem[order_status]){
				case "cancelled":					$order_status_display="Cancelled";				break;
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
				case "waiting for shape":			$order_status_display= "Waiting for Shape";		break;
				case "information in hand":			$order_status_display= "Information in Hand";	break;
				case "on hold":						$order_status_display= "On Hold";				break;
				case "re-do":						$order_status_display= "Re-do";					break;
				case "in transit":					$order_status_display= "In Transit";			break;
				case "filled":						$order_status_display= "Shipped";				break;
				case "waiting for lens":			$order_status_display= "Waiting for Lens";		break;
				default:							$order_status_display= "NO STATUS";				break;
			}
			
			
			if (($listItem[order_status]!="filled") && ($listItem[order_status]!="cancelled") && ($listItem[order_status]!=$file_order_status)){
			echo "<br>".$listItem[order_status]." ".$file_order_status;
			$files_changed++;


			$queryStatus  = "SELECT COUNT(status_history_id) AS NBR FROM STATUS_HISTORY WHERE order_num = $order_num  and order_status = '". $file_order_status. "'";
			echo '<br><br>'.$queryStatus;
			$resultStatus = mysqli_query($con,$queryStatus)		or die ('Could not select because: ' . mysqli_error($con));
			$DataStatus   = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
			$ExisteDeja = $DataStatus['NBR'];
			

			echo '<br>'. $queryStatus. '<br>';
			echo  ' Existe deja (0 = non, 1=oui):' . $ExisteDeja. '<br>';
		
		
		    if (($ExisteDeja == 0) && ($file_order_status_display<>'')) {//On ne met plus a jour le status in edging avec HKO
					
				$statusQuery="UPDATE orders SET order_status='$file_order_status' WHERE order_num=$order_num AND order_status!='basket'";	 //UPDATE THE STATUS OF THE ORDER
				echo '<br><br>'. $statusQuery;
				//exit();
				$statusResult=mysqli_query($con,$statusQuery)		or die ( "Query failed: " . mysqli_error($con));
			
				$todayDate = date("Y-m-d g:i a");// current date
				$currentTime = time($todayDate); //Change date into time
				$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
				$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
				$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$order_num', '$datecomplete','hupdate script 2.0')";
				echo '<br><br>' . $queryStatusHistory;
				$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
	
				
			}else {
				echo "<tr><td align=\"right\">$listItem[order_num] No Update :</td><td><b>".$listItem[order_status]. ' ' .	$file_order_status_display . "</b></td></tr>";
			}
			
			}//FIN SI EXISTEDEJA
			
		}//END IF DIFFERENT	
	
    }//END FOR
	
echo "</table>";

?>
</div>