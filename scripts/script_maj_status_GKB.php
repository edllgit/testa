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
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');  
$time_start = microtime(true);
$ftp_server = constant("GKB_FTP");//Updated: 2016-11-02 8 AM
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

ftp_pasv($conn_id,true);//IMPORTANT mode passif nécessaire pour le ftp direct-lens sur serveur lunarpages
ftp_chdir($conn_id,"Directlab");
$directory=ftp_pwd($conn_id);
ftp_chdir($conn_id,"Status");
$directory=ftp_pwd($conn_id);

echo "<br>".$directory."<br>";

$contents=ftp_nlist($conn_id, ".");

$max=0;
$oldest_file="";
$oldest_file="";
foreach ($contents as $value) {//FIND OLDEST FILE
	
	$time=ftp_mdtm($conn_id,$value);
		//if ((strpos($value,"status")!==false)&&(strpos($value,".csv")!==false)){
		if (strpos($value,".csv")!==false){
			
		if ($time>$max){
			$max=$time;
			$oldest_file=$value;
		}
		echo "$value $time<br />\n";
	}
}//End For each

echo "<br>Plus Recent:".$oldest_file;

//Continuer le traitement uniquement si on a un fichier csv  à utiliser:
if ($oldest_file <> ''){
	
	$message="<html>";
	$message.="<head><style type='text/css'>
	<!--
	.TextSize {
		font-size: 8pt;
		font-family: Arial, Helvetica, sans-serif;
	}
	-->
	</style></head>";
	$message.="<body><table width=\"500\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$message.="<tr bgcolor=\"CCCCCC\">
				   <td align=\"center\">Fichier Utilisé:</td>
				   <td align=\"center\">$oldest_file</td>
				   <td align=\"center\">&nbsp;</td>
			   </tr>";
	
	$local_file='Fichier_Temporaire_MAJ_Status_GKB.csv';
	$server_file =$oldest_file;
	
	// try to download $server_file and save to $local_file
	if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
		echo "<br>Successfully written $server_file to $local_file\n";
		$message.="<tr>
				   <td align=\"center\">Fichier Copié:</td>
				   <td align=\"center\">Succès</td>
				   <td align=\"center\">&nbsp;</td>
			   </tr>";
		//Delete the newest file from the FTP
		if (ftp_delete($conn_id, $oldest_file)){
			echo "<br>$value deleted successful";
			$message.="<tr bgcolor=\"CCCCCC\">
				   <td align=\"center\">Fichier Effacé:</td>
				   <td align=\"center\">Succès</td>
				   <td align=\"center\">&nbsp;</td>
			   </tr>";
		}else {
			echo "<br>could not delete $value";
			$message.="<tr bgcolor=\"CCCCCC\">
				   <td align=\"center\">Fichier Effacé:</td>
				   <td align=\"center\">Une erreur est survenue</td>
			       <td align=\"center\">&nbsp;</td>
			   </tr>";
		}//IF DELETE
		
	}else{
		echo "There was a problem\n";
		$message.="<tr bgcolor=\"CCCCCC\">
				   <td align=\"center\">Fichier Copié:</td>
				   <td align=\"center\">Une erreur est survenue</td>
				   <td align=\"center\">&nbsp;</td>
			   </tr>";
	}
	
	$message.="</table>";
	
	// close the connection
	$orderArray=array();
	ftp_close($conn_id);  


	$row = 1;
	$handle = fopen("Fichier_Temporaire_MAJ_Status_GKB.csv", "r");
	$count=0;
	
	
	$message.="<table width=\"650\" cellspacing=\"0\" border=\"1\" class=\"TextSize\">
				<tr>
				   <td colspan=\"3\" align=\"center\">Contenu du fichier csv:</td>
			   </tr>
			   <tr>
				   <th align=\"center\">Order</th>
				   <th align=\"center\">Status Code</th>
				   <th align=\"center\">New Status</th>
				   <th align=\"center\">Current Status</th>
			   </tr>";
	
		
	while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {//COLLECT DATA INTO ARRAY FIRST
		$count++;
				
		
		echo '<br>Passe dans le fichier..';
		
		$orderArray[$count][1]=$data[0];//Order Number
		$CSV_Order_Num = filter_var($orderArray[$count][1], FILTER_SANITIZE_EMAIL);
		
		$orderArray[$count][2]=$data[2];//Code Status
		$CSV_Code_Status = filter_var($orderArray[$count][2], FILTER_SANITIZE_EMAIL);
		
		
		//switch($orderArray[$count][2]){
		switch($CSV_Code_Status){	
				//case "300":		$file_order_status_display="Order Imported";		break;
				case "302":		$file_order_status_display="In Production";			break;
				case "325":		$file_order_status_display="In Production";			break;
				case "306":		$file_order_status_display="In Coating";			break;
				case "307":		$file_order_status_display="Order Completed";		break;
				case "308":		$file_order_status_display="In Mounting";			break;
				case "330":		$file_order_status_display="In Transit";			break;
				case "500":		$file_order_status_display="Delay Issue 0";			break;
				case "501":		$file_order_status_display="Delay Issue 1";			break;
				case "502":		$file_order_status_display="Delay Issue 2";			break;
				case "503":		$file_order_status_display="Delay Issue 3";			break;
				case "504":		$file_order_status_display="Delay Issue 4";			break;
				case "505":		$file_order_status_display="Delay Issue 5";			break;
				case "506":		$file_order_status_display="Delay Issue 6";			break;
				case "508":		$file_order_status_display="Waiting for Frame";		break;
				case "509":		$file_order_status_display="Waiting for Shape";		break;
				default:		$file_order_status_display="NO CODE RECOGNIZED";	break;
			}
		
		$queryCurrentStatus  = "SELECT order_status FROM orders WHERE order_num=".  $CSV_Order_Num ;
		$resultCurrentStatus = mysqli_query($con,$queryCurrentStatus)		or die  ('I cannot select items because: ' . mysqli_error($con));	
		$DataCurrentStatus   = mysqli_fetch_array($resultCurrentStatus,MYSQLI_ASSOC);
		$CurrentStatus       = $DataCurrentStatus[order_status];
		
		//Afficher le contenu du csv dans le courriel de log
		$message.="
				<tr>
				   <td align=\"center\">".$CSV_Order_Num . "</td>
				   <td align=\"center\">". $CSV_Code_Status . "</td>
				    <td align=\"center\">$file_order_status_display</td>
					<td align=\"center\">$CurrentStatus</td>	
			   </tr>";

	
	//LOOP THROUGH ARRAY
	echo "<table width=\"600\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$files_changed=0;
    for ($c=0; $c <=$count; $c++) {
	

		echo '<br>Order Num:'. $CSV_Order_Num;
			switch($CSV_Code_Status){
				//case "300":			$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
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
				case "508":				$file_order_status= "waiting for frame";	$file_order_status_display="Waiting for Frame";		break;
				case "509":				$file_order_status= "waiting for shape";	$file_order_status_display="Waiting for Shape";		break;
				default:				$file_order_status= "";				    	$file_order_status_display="NO CODE RECOGNIZED";	break;
			}
			

		$query="SELECT primary_key,order_date_processed,order_status,order_num,order_product_id FROM orders WHERE order_num='$CSV_Order_Num'";
		echo '<br>query'. $query;
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		
		$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
		
		if ($listItem[order_num]==$CSV_Order_Num){
			
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
				case "waiting for lens":			$order_status_display= "Waiting for Lens";		break;
				case "waiting for shape":			$order_status_display= "Waiting for Shape";		break;
				case "information in hand":			$order_status_display= "Information in Hand";	break;
				case "on hold":						$order_status_display= "On Hold";				break;
				case "re-do":						$order_status_display= "Re-do";					break;
				case "in transit":					$order_status_display= "In Transit";			break;
				case "filled":						$order_status_display= "Shipped";				break;
				default:							$order_status_display= "NO STATUS";				break;
			}
						
			
			echo '<br>File order status:'. $file_order_status;
			echo '<br>$listItem[order_status]:'. $listItem[order_status];

			
						
			if (($file_order_status!="")&&($listItem[order_status]!="filled")&&($listItem[order_status]!="cancelled")){
			$files_changed++;	
			
			$queryStatus = "SELECT COUNT(status_history_id) AS NBR FROM STATUS_HISTORY WHERE order_num = $CSV_Order_Num  and order_status = '". $file_order_status. "'";
			echo '<br>queryStatus'. $queryStatus;
			$resultStatus= mysqli_query($con,$queryStatus)		or die ('Could not update because: ' . mysqli_error($con));
			$DataStatus  = mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
			$ExisteDeja  = $DataStatus['NBR'];

			echo '<br>'. $queryStatus. '<br>';
			echo  ' Existe deja (0 = non, 1=oui):' . $ExisteDeja. '<br>';
		
				if ($ExisteDeja == 0) {
				
					if ($CSV_Code_Status <> 300){
						$statusQuery="UPDATE orders SET order_status='$file_order_status' WHERE order_num=$CSV_Order_Num AND order_status!='basket'"; //UPDATE THE STATUS OF THE ORDER
						echo ' <br>query:'. $statusQuery;
						$statusResult=mysqli_query($con,$statusQuery)			or die ( "Query failed: " . mysqli_error($con));
					}
					
					//Code ajouté par Charles 2010-07-23
					$todayDate   	  = date("Y-m-d g:i a");// current date
					$currentTime 	  = time($todayDate); //Change date into time
					$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
					$datecomplete 	  = date("Y-m-d H:i:s",$timeAfterOneHour);
					$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$CSV_Order_Num', '$datecomplete','update script GKB 2.0')";
					echo '<br>QueryHistory: '.$queryStatusHistory;
					$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
					//Fin code ajouté par Charles
					
					}else {
					//echo "<tr><td align=\"right\">No Update :</td><td><b>".$listItem[order_status]. ' ' .	$file_order_status_display . "</b></td></tr>";
					}
			
				}
			
					
			}//END IF DIFFERENT
				
    }//END FOR
	
	echo '<br>Sortie du For'; 
		
echo "</table>";
	

	
	}//End While
	$message.="</table>";
	
	fclose($handle);
	echo '<br><br>';
	var_dump($orderArray);
	
}else{
	echo 'No file to use in folder Status';	
}//End If There is a file to update



//SEND EMAIL
if ($message <> ""){
	$send_to_address = array('rapports@direct-lens.com');
	echo "<br>".$send_to_address;
	$curTime         = date("m-d-Y");	
	$to_address      = $send_to_address;
	$from_address    = 'donotreply@entrepotdelalunette.com';
	$subject         = "Update Status GKB V2.0";
	$response        = office365_mail($to_address, $from_address, $subject, null, $message);
	echo 'Email sent';
}else{
	echo 'Email not sent because it\'s empty';
}

//Logger l'exécution du script
$time_end 		 = microtime(true);
$time	  		 =  $time_end  - $time_start;
$today 	  		 = date("Y-m-d");
$heure_execution = date("H:i:s");
$CronQuery 		 = "INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) 
					VALUES('Script MAJ status GKB 2.0', '$time','$today','$heure_execution','script_maj_status_GKB.php')"; 					
$cronResult      = mysqli_query($con,$CronQuery) or die ( "Query failed: " . mysqli_error($con));	
?>
</div>