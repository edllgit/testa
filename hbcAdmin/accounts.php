<?php
//session_start();
include("../Connections/sec_connect.inc.php");


echo 'valeur: ' . var_dump($_SESSION["labAdminData"]["lab_name"]);
if ($_SESSION["MM_Username"]!="admin"){
	echo "You are not logged in. Click <a href='http://www.direct-lens.com/labAdmin'>here</a> to login.";
	exit();
}

exit();
$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}

	if($_GET[sort_by]!=""){
		$query="select * from accounts order by ".$_GET[sort_by];
		$_GET[sort_by]="";
		}
	else{
		$query="select accounts.*, labs.lab_name from accounts, labs where user_id NOT IN ('grmstock','grm64364','grm64362','rgiguere') AND accounts.main_lab = labs.primary_key AND main_lab NOT IN (11,15,8,12,19,23,25) ";
		}
			
	if ($_REQUEST['accounts'] != "") {

	switch($_REQUEST['accounts']){
		
	case 'approved':
	$query = $query . " and accounts.approved='approved'";
	break;
	
	case 'pending':
	$query = $query . " and accounts.approved='pending'";
	break;
	}
}


if ($_REQUEST['lab'] != "") {

	switch($_REQUEST['lab']){
		
	case 'sct':
	$query = $query . " and accounts.main_lab=3 ";
	break;
	
	case 'tr':
		$query = $query . " and accounts.main_lab= 21 ";
	break;
	
	case 'vot':
		$query = $query . " and accounts.main_lab=1 ";
	break;
	
	case 'dr':
		$query = $query . " and accounts.main_lab=22 ";
	break;
	
	case 'lensnetqc':
		$query = $query . " and accounts.main_lab=28 ";
	break;
	
	case 'lensneton':
		$query = $query . " and accounts.main_lab=29 ";
	break;

	case 'lensnetor':
		$query = $query . " and accounts.main_lab= 31";
	break;
	
	case 'lensnetusa':
		$query = $query . " and accounts.main_lab= 32";
	break;
	
	case 'lensnetatlantic':
		$query = $query . " and accounts.main_lab= 33";
	break;
	
	case 'lensnetwest':
		$query = $query . " and accounts.main_lab= 34";
	break;
	}
}

$query = $query . " order by company ";

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
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="100%">
		
		<?php
		include("account_detail.php");
		?> 	
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
