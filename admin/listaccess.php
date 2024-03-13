<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");



if ($_REQUEST[lab] <> ''){
$rptQuery="SELECT * from access WHERE lab_primary_key = $_REQUEST[lab] order by name";
}else{
$rptQuery="SELECT * from access  order by name";
}
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="goto_date.order_num.focus();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="listaccess.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr  bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Manage labAdmin Access</font></b></td>
            		</tr>
			</table>

			<?php 
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}
		
echo '<h3 align="center"><a style="text-decoration:none;" href="addaccess.php">Add an access</a></h3>';
			
?>
<p align="center">
<select name="lab" id="lab" class="formField">
	<option value="">Select a lab to see the access related to this lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\""; 
		 print ">$labList[lab_name]</option>";
}
mysql_free_result($result);
?>

 </select>
 <input type="submit" name="filter" id="filter" value="Filter" class="formField">
  </p>    
  <?php    
  
 if ($_REQUEST[lab] <> ''){

$rptLab="SELECT lab_name from labs WHERE primary_key = ". $_REQUEST[lab];
$resultLab=mysql_query($rptLab)		or die  ('I cannot select items because: ' . mysql_error());
$DataLab=mysql_fetch_array($resultLab);
mysql_free_result($resultLab);
echo '<b>Access created for : '. $DataLab['lab_name'] . '</b>';
}
  
            
if ($usercount != 0){

print "<br><table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" ><tr bgcolor=\"#000000\">";
print "</tr>";


			  print "<tr>
                <th align=\"center\">Name</th>
                <th align=\"center\">Lab associé</th>
                <th align=\"center\">Username</th>
				<th align=\"center\">Delete Access</th>";
		  print "</tr>";
			  
			  
while ($listItem=mysql_fetch_array($rptResult)){
			  	
				
			$queryLab = "Select * from labs where primary_key = 	$listItem[lab_primary_key]";
			$rptLab=mysql_query($queryLab)		or die  ('I cannot select items because: ' . mysql_error());
			$DataLab=mysql_fetch_array($rptLab);
			mysql_free_result($rptLab);	
			$lelab= $DataLab['lab_name'];
			
											
               print "<td bgcolor=\"#CCCCCC\"  align=\"center\"><a style=\"text-decoration:none;\" href=\"editaccess.php?id=$listItem[id]\">$listItem[name]</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">$lelab</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">$listItem[username]</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\"><a style=\"text-decoration:none;\" href=\"deleteaccess.php?id=$listItem[id]\">Delete</a>";
				//if ($listItem[can_edit_account] == 'yes') 
				//echo "X"; 
				
		
				
				//print "</td>";
				print "</tr><tr><td></td></tr>";
				print "</tr><tr><td></td></tr>";
				print "</tr><tr><td></td></tr>";

		}//END WHILE
		mysql_free_result($rptResult);	
		print "</table>";

}
?>
</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>