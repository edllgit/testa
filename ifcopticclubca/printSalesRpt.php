<?php
session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("../includes/sales_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$user_id=$_SESSION["sessionUser_Id"];
$company=stripslashes($_SESSION["sessionUserData"]["company"]);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #FFFFFF;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.select1 {width:100px}
.style1 {	color: #FFFFFF;
	font-weight: bold;
}
.style2 {color: #FFFFFF}
-->
</style>

</head>
<body>
<?php
print $_SESSION["printRpt"];
?>
</body>
</html>
