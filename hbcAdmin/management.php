<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_id=$_SESSION["labAdminData"]["primary_key"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if ($_POST['from_update_prices']=="true"){
	$manage_credit=$_POST['manage_credit'];
	$manage_statement=$_POST['manage_statement'];
	$customer_service=$_POST['customer_service'];
		
		
	$query=("UPDATE labs  SET manage_credit = 	 '$manage_credit',
							  manage_statement = '$manage_statement',
							  customer_service = '$customer_service'
							 WHERE primary_key='$lab_id'");
							 
							 
		$result=mysql_query($query)		or die ('Could not update because: ' . mysql_error());
	

}//END IF
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td colspan="6" align="center" class="formField1"><span class="formField2 style2"><?php echo 'Management people';?> </span></td>
       		  </tr>
			</table>

<table width="100%" border="0" cellpadding="4" cellspacing="0" class="formField2">
   
<?php

echo "<form action=\"management.php\" method=\"post\" name=\"form\"><tr bgcolor=\"#DDDDDD\">";
$counter=0;
	
		if ($counter%2==0)
			$bgcolor="#FFFFFF";
		else
			$bgcolor="#DDDDDD";
		$counter++;
		
		echo "</tr><tr bgcolor=$bgcolor>";
		
		
		$queryDetail = "Select manage_credit, manage_statement, customer_service from labs WHERE primary_key =" .$lab_id;
		$resultDetail=mysql_query($queryDetail)		or die ('Could not update because: ' . mysql_error());
		$DataDetail=mysql_fetch_array($resultDetail);
	
	echo "<td width=\"300\"><b>Who manage credits: </b></td>";
?>      
	<td><select name="manage_credit" class="formField" id="manage_credit">
	<option value="">Select a manager</option><?php
	$query="select * from employes order by first_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
	echo "<option value=\"$labList[id_employe]\""; if($DataDetail["manage_credit"]==$labList[id_employe]) echo " selected"; echo ">$labList[first_name] $labList[last_name] </option>";
}
?>
</select></td></tr>
                    
 <?php
	echo "<tr><td width=\"300\"><b>Who send the monthly statement to customers: </b></td> ";
?>      
	<td><select name="manage_statement" class="formField" id="manage_statement">
	<option value="">Select a manager</option><?php
	$query="select * from employes order by first_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
	echo "<option value=\"$labList[id_employe]\""; if($DataDetail["manage_statement"]==$labList[id_employe]) echo " selected"; echo ">$labList[first_name] $labList[last_name] </option>";
}
?>
</select></td></tr>
                    
 <?php
	
	echo "<tr><td width=\"300\"><b>Who offer customer service: </b> </td>";
	
	?>      
	<td><select name="customer_service" class="formField" id="customer_service">
	<option value="">Select a manager</option><?php
	$query="select * from employes order by first_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
	echo "<option value=\"$labList[id_employe]\""; if($DataDetail["customer_service"]==$labList[id_employe]) echo " selected"; echo ">$labList[first_name] $labList[last_name] </option>";
}
?>
<?php
echo "<tr><td align=\"center\" colspan=\"4\"><input type=\"hidden\" name=\"from_update_prices\" value=\"true\" /><input name=\"updateDisc\" type=\"submit\" value=\"Save\" class=\"formField2\" /></td></tr></form>";
?>
</table>
</td>
	  </tr>
</table>

</body>
</html>
