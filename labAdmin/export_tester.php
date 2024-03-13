<?php
include("../Connections/sec_connect.inc.php");
include("export_functions.inc.php");

//CREATE EXPORT FILE//

$limitNum="100";

$total_time_start = microtime(true);

$today=date("Y-m-d");

$filename="export_tester.csv";
$fp=fopen($filename, "w");

$total_q_time_start = microtime(true);

$orderQuery="SELECT * from orders
LEFT JOIN (exclusive) ON (orders.order_product_id = exclusive.primary_key) 
WHERE order_status='filled' AND order_product_type='exclusive' ORDER by orders.primary_key  desc LIMIT $limitNum";

$orderResult=mysql_query($orderQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$itemcount=mysql_num_rows($orderResult);

$total_q_time_end = microtime(true);

$headerstring=get_header_string();

fwrite($fp,$headerstring);

while ($orderData=mysql_fetch_array($orderResult)){
	
$avg_time_start=microtime(true);

$outputstring=export_order($orderData[order_num]);

$avg_time_end=microtime(true);		

fwrite($fp,$outputstring);

$total_avg_time=$total_avg_time+($avg_time_end - $avg_time_start);

	}
	
$avg_time=$total_avg_time/$limitNum;

fclose($fp);

$total_time_end = microtime(true);
$total_time= $total_time_end - $total_time_start;


$total_q_time= $total_q_time_end - $total_q_time_start;

//MAIL THE FILE//
  
  	$path = "";
	$to = "dbeaulieu@direct-lens.com";
	$from_name = "orders@direct-lens.com"; 
	$from_mail = "orders@direct-lens.com"; 
	$subject = "EXPORT FILE TEST"; 
	$message = "Total number of orders: ".$itemcount; 
	$message.= "\r\nTotal time: ".$total_time; 
	$message.= "\r\nTotal query: ".$total_q_time; 
	$message.= "\r\nAverage individual order export: ".$total_avg_time; 
	
	$file = $path.$filename; 

    $file_size = filesize($file); 
    $handle = fopen($file, "r"); 
    $content = fread($handle, $file_size); 
    fclose($handle); 
    $content = chunk_split(base64_encode($content)); 
	$uid = md5(uniqid(time())); 
	
	 $header = "From: ".$from_name." <".$from_mail.">\r\n"; 
    $header .= "MIME-Version: 1.0\r\n"; 
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n"; 
    $header .= "This is a multi-part message in MIME format.\r\n"; 
    $header .= "--".$uid."\r\n"; 
    $header .= "Content-type: text/plain; charset=UTF-8\r\n"; 
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n"; 
    $header .= $message."\r\n\r\n"; 
	$header .= "--".$uid."\r\n"; 

    $header .= "Content-Type: application/vnd.ms-excel; name=\"".$filename."\"\r\n"; // use diff. tyoes here 
    $header .= "Content-Transfer-Encoding: base64\r\n"; 
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n"; 
    $header .= $content."\r\n\r\n"; 
    $header .= "--".$uid."--"; 
	
   if (mail($to, $subject, "", $header)) { 
		echo " <br>Total number of orders: ".$itemcount.
		"<br>Total query: ".$total_q_time.
		"<br><br>Average start: ".$avg_time_start.
		"<br>Average end: ".$avg_time_end.
		"<br>Average individual order export: ".$avg_time.
		"<br>Total individual order export: ".$total_avg_time.
		
		"<br><br>Total time: ".$total_time;
  }
?>
