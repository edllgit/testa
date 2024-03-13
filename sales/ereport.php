
<?php
if (!function_exists("shootmail")) {
function shootmail($sendto, $mysubject, $mymsg){

}}?>
<?php include '../Connections/directlens.php'; ?>
<?php include '../Connections/sec_connect.inc.php'; ?>
<?php include '../sales/salesmath.php'; ?>

<?php
mysql_select_db($database_directlens, $directlens);
$query_sales_staff = sprintf("SELECT * FROM sales_reps where daily_report = '1'");
$sales_staff = mysql_query($query_sales_staff, $directlens) or die(mysql_error());
$row_sales_staff = mysql_fetch_assoc($sales_staff);
$totalRows_sales_staff = mysql_num_rows($sales_staff);

do {
    $ch = curl_init(); 
	$myurl = "http://www.direct-lens.com/sales/sales-emailreport.php?sid=".$row_sales_staff["id"];
	curl_setopt($ch, CURLOPT_URL, $myurl); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $output = curl_exec($ch); 
    curl_close($ch);
	echo $row_sales_staff["rep_email"]."<br><br>".$output;
	//echo $myurl."-----------------------------------------------------------------";
//	shootmail($row_sales_staff["rep_email"], "Daily Report", "this is a test email");
	shootmail($row_sales_staff["rep_email"], "email report", $output);
} while ($row_sales_staff = mysql_fetch_assoc($sales_staff));

?>