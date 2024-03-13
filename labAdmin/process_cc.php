<?php
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if ($_POST[billCard]=="Enter Payment"){//admin enters order payment
	$_GET[order_num]=makeAdminPmt($_POST[user_id]);
	header("Location:http://www.direct-lens.com/labAdmin/display_order.php?order_num=$_GET[order_num]");
}
?>