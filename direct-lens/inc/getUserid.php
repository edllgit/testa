<?php
include("../../Connections/sec_connect.inc.php");

$user_id=$_REQUEST['user_id'];
$data=mysql_query("SELECT * FROM accounts where user_id ='$user_id'");
if(mysql_num_rows($data)>0)
{
print "<span style=\"color:red;\">Username is not available !". $data. "</span>";
}
else
{
print "<span style=\"color:green;\">Username is available". $data. "</span>";
}
?>
