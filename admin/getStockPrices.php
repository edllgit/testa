<?php
include("../Connections/sec_connect.inc.php");
$frames_collections_id=$_REQUEST['frames_collections_id'];
$Result=mysql_query("SELECT distinct stock_price FROM ifc_frames_french where misc_unknown_purpose ='$frames_collections_id' order by stock_price ");
if(mysql_num_rows($Result)==1)
{
	$Data=mysql_fetch_assoc($Result);	
	echo "<span style=\"color:red;\">Price: <b>" . $Data[stock_price] . "$</b></span>";
}elseif(mysql_num_rows($Result)> 1){
	echo "<span style=\"color:red;\">Please contact DirectlabNetwork to get the correct price</span>";
}
?>
