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
//ini_set('memory_limit', '256M');
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
ini_set('display_errors', '1');
require_once(__DIR__.'/../constants/ftp.constant.php');
include("../sec_connectEDLL.inc.php");
include('../phpmailer_email_functions.inc.php');
require_once('../class.ses.php');  
$time_start = microtime(true);                    
$ftp_server = constant("OVG_LAB_FTP");
$ftp_user   = constant("FTP_USER_OVG_LAB");

$ftp_pass   = constant("FTP_PASSWORD_OVG_LAB");

// set up a connection or die
$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server"); 

// try to login
if (@ftp_login($conn_id, $ftp_user, $ftp_pass)) {
    echo "Connected as $ftp_user@$ftp_server\n";
} else {
    echo "Couldn't connect as $ftp_user\n";
}
ftp_pasv($conn_id,true);//IMPORTANT mode passif nécessaire pour le ftp direct-lens sur serveur lunarpages

ftp_chdir($conn_id,"Status");
$directory=ftp_pwd($conn_id);
echo "<br>".$directory."<br>";

//Initialisation des variables
$max=0;
$newest_file="";
$champ     =  date("Ym");// current date


//************************
//FONCTIONNEMENT ACTUEL
$contents=ftp_nlist($conn_id, ".");
$champ     =  date("Ym");// current date
echo '<br>Champ:'. $champ .'<br>';
$lechamp= '/'.$champ . '/';
//TEST CHARLES ICI
switch($champ){
	case '201901': 	$contents = preg_grep('/201901/', $contents);  	break;
	case '201902': 	$contents = preg_grep('/201902/', $contents);  	break;
	case '201903': 	$contents = preg_grep('/201903/', $contents);  	break;
	case '201904': 	$contents = preg_grep('/201904/', $contents);  	break;
	case '201905':  $contents = preg_grep('/201905/', $contents);  	break;
	case '201906':  $contents = preg_grep('/201906/', $contents);  	break;
	case '201907':  $contents = preg_grep('/201907/', $contents);  	break;
	case '201908':  $contents = preg_grep('/201908/', $contents);  	break;
	case '201909':  $contents = preg_grep('/201909/', $contents);  	break;
	case '201910':  $contents = preg_grep('/201910/', $contents);  	break;	
	case '201911':  $contents = preg_grep('/201911/', $contents);  	break;
	case '201912':  $contents = preg_grep('/201912/', $contents);  	break;
	case '202001':  $contents = preg_grep('/202001/', $contents);  	break;
	case '202002':  $contents = preg_grep('/202002/', $contents);  	break;
	case '202003':  $contents = preg_grep('/202003/', $contents);  	break;
	case '202004':  $contents = preg_grep('/202004/', $contents);  	break;
	case '202005':  $contents = preg_grep('/202005/', $contents);  	break;
	case '202006':  $contents = preg_grep('/202006/', $contents);  	break;
	case '202007':  $contents = preg_grep('/202007/', $contents);  	break;
	case '202008':  $contents = preg_grep('/202008/', $contents);  	break;
	case '202009':  $contents = preg_grep('/202009/', $contents);  	break;
	case '202010':  $contents = preg_grep('/202010/', $contents);  	break;
	case '202011':  $contents = preg_grep('/202011/', $contents);  	break;
	case '202012':  $contents = preg_grep('/202012/', $contents);  	break;
	case '202101':  $contents = preg_grep('/202101/', $contents);  	break;
	case '202102':  $contents = preg_grep('/202102/', $contents);  	break;
	case '202103':  $contents = preg_grep('/202103/', $contents);  	break;
	case '202104':  $contents = preg_grep('/202104/', $contents);  	break;
	case '202105':  $contents = preg_grep('/202105/', $contents);  	break;
	case '202106':  $contents = preg_grep('/202106/', $contents);  	break;
	case '202107':  $contents = preg_grep('/202107/', $contents);  	break;
	case '202108':  $contents = preg_grep('/202108/', $contents);  	break;
	case '202109':  $contents = preg_grep('/202109/', $contents);  	break;
	case '202110':  $contents = preg_grep('/202110/', $contents);  	break;
	case '202111':  $contents = preg_grep('/202111/', $contents);  	break;
	case '202112':  $contents = preg_grep('/202112/', $contents);  	break;
	case '202401':  $contents = preg_grep('/202401/', $contents);  	break;
	default:  echo 'Lechamp:'.$lechamp.'<br>';      $contents = preg_grep($lechamp, $contents); 
}//FIN TEST CHARLES


foreach ($contents as $value) {//FIND NEWEST FILE
	
	$time=ftp_mdtm($conn_id,$value);
		//if ((strpos($value,"status")!==false)&&(strpos($value,".csv[2018")!==false)){
		//if ((strpos($value,"status_0R005_[2019")!==false)&&(strpos($value,".csv")!==false)){
			if ((strpos($value,"status_0R005_[$champ")!==false)&&(strpos($value,".csv")!==false)){
		if ($time>$max){
			$max=$time;
			$newest_file=$value;
		}
	}
 	echo "$value $time<br />\n";
}//END FOR EACH
//************************


/*
//---------------------------
//NOUVEAU TEST
$files = ftp_nlist($conn_id, '.' );
$filteredFiles = preg_grep( '/\.csv$/i', $files );
var_dump($filteredFiles);
//ftp_close($ftp); 
//---------------------------*/


echo "<br>Plus Recent:".$newest_file;


echo "Execution time recherche du csv:  $time seconds\n";
$local_file='Fichier_Temporaire_MAJ_Status_OVG_LAB.csv';
$server_file =$newest_file;

echo 'c3<br>';


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
$handle = fopen("Fichier_Temporaire_MAJ_Status_OVG_LAB.csv", "r");
$orderArray = array();
$count=0;

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $count++;

    // Utilisez var_dump($data) pour afficher toutes les données de la ligne
    echo '<br>$data= ' . var_dump($data);
	
	    if (isset($data[0])) {
        // Utilisez explode pour séparer les données de la première colonne
        $columnData = explode(";", $data[0]);

        // Assurez-vous que l'indice 1 existe après l'explosion
        if (isset($columnData[1])) {
            $orderArray[$count][1] = $columnData[1];
        }
		
		if (isset($columnData[3])) {
			$orderArray[$count][2] = $columnData[3];
		}
		
    }
	

}



fclose($handle);

var_dump($orderArray);



//LOOP THROUGH ARRAY
echo "<table width=\"600\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
	$files_changed=0;
    for ($c=0; $c <=$count; $c++) {
	
	    $order_num = $orderArray[$c][1];
		$status_code = $orderArray[$c][2];
		

			
			echo '<br>$order_num = ';
			var_dump($order_num);

			echo '<br>$status_code = ';
			var_dump($status_code);


			echo "<br>\$order_num = $order_num";
			echo "<br>\$status_code = $status_code";
			
			switch($status_code){
					
				case "300":				$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
				case "205":				$file_order_status= "order imported";		$file_order_status_display="Order Imported";		break;
				case "302":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "210":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "306":				$file_order_status= "in coating";			$file_order_status_display="In Coating";			break;
				case "211":				$file_order_status= "in coating";			$file_order_status_display="In Coating";			break;
				case "325":				$file_order_status= "job started";			$file_order_status_display="In Production";			break;
				case "307":				$file_order_status= "order completed";		$file_order_status_display="Order Completed";		break;
				case "228":				$file_order_status= "order completed";		$file_order_status_display="Order Completed";		break;
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
				case "508":				$file_order_status= "waiting for frame OVG";	$file_order_status_display="Waiting for Frame OVG";	break;
				case "601":				$file_order_status= "waiting for frame OVG";	$file_order_status_display="Waiting for Frame OVG";	break;
				case "509":				$file_order_status= "waiting for shape";	$file_order_status_display="Waiting for Shape";		break;
				default:				$file_order_status= "";				    	$file_order_status_display="NO CODE RECOGNIZED";	break;


			}
	echo '<br>c8<br>';
		$query="SELECT primary_key,order_date_processed,order_status,order_num,order_product_id FROM orders WHERE order_num='$order_num'";
		echo '<br>'.$query .'<br>';
		$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
		$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);

		
		if ($listItem[order_num]==$orderArray[$c][1]){
			//echo 'c8a<br>';
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
				case "waiting for frame OVG":		$order_status_display= "Waiting for Frame OVG";		break;
				case "waiting for lens":			$order_status_display= "Waiting for Lens";		break;
				case "waiting for shape":			$order_status_display= "Waiting for Shape";		break;
				case "information in hand":			$order_status_display= "Information in Hand";	break;
				case "on hold":						$order_status_display= "On Hold";				break;
				case "re-do":						$order_status_display= "Re-do";					break;
				case "in transit":					$order_status_display= "In Transit";			break;
				case "filled":						$order_status_display= "Shipped";				break;
				default:							$order_status_display= "NO STATUS";				break;
			}
			
			//echo 'a';			
			if (($file_order_status!="")&&($listItem[order_status]!="filled")&&($listItem[order_status]!="cancelled")){
			$files_changed++;
				
			//echo 'a2<br>';	
			$queryStatus = "SELECT COUNT(status_history_id) AS NBR FROM STATUS_HISTORY WHERE order_num = $order_num  and order_status = '". $file_order_status. "'";
			echo $queryStatus .'<br>';
			$resultStatus=mysqli_query($con,$queryStatus)		or die ('Could not update because: ' . mysqli_error($con));
			$DataStatus=mysqli_fetch_array($resultStatus,MYSQLI_ASSOC);
			$ExisteDeja = $DataStatus['NBR'];
//echo 'a3<br>';
		
		
		echo '<br>'. $queryStatus. '<br>';
		echo  ' Existe deja (0 = non, 1=oui):' . $ExisteDeja. '<br>';
		
				if ($ExisteDeja == 0) {
				

					$statusQuery="UPDATE orders SET order_status='$file_order_status' WHERE order_num=$order_num AND order_status!='basket'"; //UPDATE THE STATUS OF THE ORDER
					echo $statusQuery. '<br>';
					$statusResult=mysqli_query($con,$statusQuery)			or die ( "Query failed: " . mysqli_error($con));
				
					//Code ajouté par Charles 2010-07-23
					$todayDate = date("Y-m-d g:i a");// current date
					$currentTime = time($todayDate); //Change date into time
					$timeAfterOneHour = $currentTime+((60*60)*3);	//Add 3 hours to server time to get actual time
					$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
					$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type)VALUES ('$file_order_status','$order_num', '$datecomplete','update script S 2.1')";
					echo $queryStatusHistory .'<br>';
					$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
					//Fin code ajouté par Charles
					
					}else {
						echo "<tr><td align=\"right\"><b>$listItem[order_num]</b> No Update:</td><td><b>".$listItem[order_status]. ' ' .	$file_order_status_display . "</b></td></tr>";
					}
			
				}
			
					
			}//END IF DIFFERENT
				
    }//END FOR
	
echo "</table>";

echo '<br>Fin du traitement de ce fichier sans erreur.';

$time_end = microtime(true);
$time =  $time_end  - $time_start;
echo "Execution time:  $time seconds\n";

$today = date("Y-m-d");// current date
$timeplus3heures = date("H:i:s");
$CronQuery="INSERT INTO cron_duration(cron_script_name,cron_duration, cron_date, cron_time,php_page) VALUES('Update status Swiss', '$time','$today','$timeplus3heures','cron_update_satus.php') "  ; 					
$cronResult=mysqli_query($con,$CronQuery)			or die ( "Query failed: " . mysqli_error($con));	
?>
</div>