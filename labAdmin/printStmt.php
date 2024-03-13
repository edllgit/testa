<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
if($heading=="")
	$heading=$_SESSION["heading"];
?>
<html>
<head>
<title>Direct Lens Customer Statement</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</head>

<body>
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}
			
if ($usercount != 0){
	include("displayStmtForm.php");
}
else if ($heading==""){
}
else {
echo "<div class=\"formField\">No Orders Found</div>";}//END USERCOUNT CONDITIONAL
?>
  			<p>&nbsp;</p>
</body>
</html>
