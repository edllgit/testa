<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");
include("export_functions_w_prices.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if ($_POST["exportData"]=="Export Data"){

	$filename="OrderData2_" . $lab_pkey . ".csv";
	$fp=fopen($filename, "w");

}
if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
	
	//echo '<br>query'. $rptQuery;

//$orderQuery="SELECT * FROM orders WHERE order_status='processing' GROUP by order_num"; //find user's data
$orderResult=mysql_query($rptQuery)
	or die  ('I cannot select items because: ' . mysql_error());

$itemcount=mysql_num_rows($orderResult);
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<title>Direct Lens Admin Area</title>
</head>

<body>
<!--
<form action="export_file.php" method="post" enctype="multipart/form-data" name="form1" id="form1">
  <input type="submit" name="Submit" value="Submit" />
  <input name="report" type="checkbox" id="report" value="yes" />
  Create Export File <br />
  
</form>
-->
<?php
if ($_POST["exportData"]=="Export Data"){
	//echo "<a href='$filename'\">Download Order Data File</a>";
	
	$headerstring=get_header_string_labadmin();
	fwrite($fp,$headerstring);

	while ($orderData=mysql_fetch_array($orderResult)){
	
		$outputstring=export_order_labadmin($orderData["order_num"]);
		fwrite($fp, $outputstring);

//INSERT LIST DISPLAY CODE HERE
	}

fclose($fp);
}
?>
<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td width="95%" colspan="2" align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> Order Reports</font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="50%" height="25" bgcolor="#DDDDDD"><div align="center">
						<input name="downloadFile" type="button" id="downloadFile" value="Download File" onClick="parent.location='<?php echo "$filename"; ?>'" class="formField"></div></td>
					<td width="50%"><div align="center"><input name="closeWindow" type="button" value="Close Window" onclick="window.close()" class="formField"></div></td>
				</tr>
			</table>
</body>
</html>
