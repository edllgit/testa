
<?php
if (!function_exists("shootmail")) {
function shootmail($sendto, $mysubject, $mymsg){
$send_to_address=str_split($sendto,150);//Mettre l'adresse dans un array
$send_copy_to = array('rco.daniel@gmail.com','thahn@direct-lens.com','dbeaulieu@direct-lens.com');//array avec  ceux qui doivent avoir une copie de ces rapports
$to_address = array_merge((array)$send_to_address, (array)$send_copy_to);//Concatenation des 2 arrays

echo '<br> Les addresses: ';
var_dump($to_address);
echo '<br>';
$curTime= date("m-d-Y");	
$from_address='donotreply@entrepotdelalunette.com';
$subject='Weekly Sales Rep Sales report: '.$curTime;
$response=office365_mail($to_address, $from_address, $subject, null, $mymsg);
if ($response)
echo "<br>success";
}}?>
<?php include '../Connections/directlens.php'; ?>
<?php include '../Connections/sec_connect.inc.php'; ?>
<?php include '../sales/salesmath.php'; ?>
<?php include('../includes/phpmailer_email_functions.inc.php'); ?>
<?php require_once('../includes/class.ses.php'); ?>


<?php
mysql_select_db($database_directlens, $directlens);
$query_sales_staff = sprintf("SELECT * FROM sales_reps where daily_report = '1'");
//$query_sales_staff = sprintf("SELECT * FROM sales_reps where id = 30 and daily_report = '1'");
$sales_staff = mysql_query($query_sales_staff, $directlens) or die(mysql_error());
$row_sales_staff = mysql_fetch_assoc($sales_staff);
$totalRows_sales_staff = mysql_num_rows($sales_staff);

do {
    $ch = curl_init(); 
	$myurl = "http://www.direct-lens.com/sales/sales-emailreport-week.php?sid=".$row_sales_staff["id"];
	curl_setopt($ch, CURLOPT_URL, $myurl); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch); 
    curl_close($ch);
	echo $row_sales_staff["rep_email"]."<br><br>".$output;
	//echo $myurl."-----------------------------------------------------------------";
//	shootmail($row_sales_staff["rep_email"], "Daily Report", "this is a test email");
	shootmail($row_sales_staff["rep_email"], "Weekly Sales Rep Sales report", $output);
} while ($row_sales_staff = mysql_fetch_assoc($sales_staff));

?>