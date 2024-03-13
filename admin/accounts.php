<?php
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}

$type=$_GET[category];

if ($type=="stock"){
	$query="select * from prices order by product_name";
}
else{//IF EXCLUSIVE PRODUCT
	if($_GET[sort_by]!=""){
		$query="select * from exclusive order by ".$_GET[sort_by];
		$_GET[sort_by]="";
		}
	else{
		$query="select * from exclusive order by manufacturer,product_name,index_v,coating,photo,polar";
		}
}
$catResult=mysql_query($query)	or die ( "Query failed: " . mysql_error() );
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
		
		<?php
				include("account_detail.inc.php");
		?>
           
				
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
