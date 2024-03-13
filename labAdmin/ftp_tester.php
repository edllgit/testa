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
require_once(__DIR__.'/../constants/ftp.constant.php');
                     
$ftp_server = constant("UNKNOWN_FTP_SERVER_210");
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

$directory=ftp_pwd($conn_id);

echo "<br>".$directory."<br>";

ftp_chdir($conn_id,"TO_DL");

$contents=ftp_nlist($conn_id, "");
$max=0;
$newest_file="";

foreach ($contents as $value) {
	
	$time=ftp_mdtm($conn_id,$value);
	
	if ((strpos($value,"status")!==false)&&(strpos($value,".csv")!==false)){
		if ($time>$max){
			$max=$time;
			$newest_file=$value;
		}
	}
 	echo "$value $time<br />\n";
}

echo "<br><br>".$newest_file;

$local_file='temp.csv';
$server_file =$newest_file;

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
$handle = fopen("temp.csv", "r");
$count=0;

while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {//COLLECT DATA INTO ARRAY FIRST
	$count++;
	$orderArray[$count][1]=$data[1];
	$orderArray[$count][2]=$data[3];
}

fclose($handle);

$start_time=microtime(true);

include("../Connections/sec_connect.inc.php");

//LOOP THROUGH ARRAY

echo "<table width=\"600\" cellpadding=\"2\"  cellspacing=\"0\" class=\"TextSize\">";
    for ($c=0; $c <=$count; $c++) {
	
		$order_num=$orderArray[$c][1];
		
			switch($orderArray[$c][2]){
			case "300":
				$file_order_status= "processing";
			break;
			case "330":
				$file_order_status= "in transit";
			break;
			case "302":
				$file_order_status= "job started";
			break;
			case "325":
				$file_order_status= "job started";
			break;
			case "306":
				$file_order_status= "job started";
			break;
			case "307":
				$file_order_status= "job started";
			break;
			case "308":
				$file_order_status= "job started";
			break;
			}
	
		$query="SELECT order_status,order_num FROM orders WHERE order_num='$order_num'";
		$result=mysql_query($query)
				or die  ('I cannot select items because: ' . mysql_error());
		
		$listItem=mysql_fetch_array($result);
		
		if ($listItem[order_num]==$orderArray[$c][1]){
			echo "<tr><td>".$orderArray[$c][1]."</td><td>".$orderArray[$c][2]."</td><td align=\"right\">IN ORDER TABLE:</td><td><b>".$listItem[order_status]."</b></td><td align=\"right\">CHANGE TO:</td><td><b>".$file_order_status."</b></td></tr>";
		}
		else
		{
		  	echo "<tr><td>".$orderArray[$c][1]."</td><td>".$orderArray[$c][2]."</td><td></td><td></td><td></td><td></td></tr>";
		}
	
    }//END FOR
	
echo "</table>";
$end_time =microtime(true);
$total_time = $end_time-$start_time;

echo "TOTAL TIME:".$total_time;
?>
</div>