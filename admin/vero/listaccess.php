<?php
session_start();
if ($_SESSION[adminData][username]!="superadmin"){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../../Connections/sec_connect.inc.php");

$rptQuery="SELECT * from access_admin order by name";
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../admin.css" rel="stylesheet" type="text/css" />
</head>
<body onLoad="goto_date.order_num.focus();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("../adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="listaccess.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr  bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Manage Main Admin Access</font></b></td>
            		</tr>
			</table>
</form>
			<?php 
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}
		
			
if ($usercount != 0){

echo '<a style="text-decoration:none;" href="addaccess.php"><h3 align="center">Add an access</h3></a>';
print "<br><table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" ><tr bgcolor=\"#000000\">";
print "</tr>";


			  print "<tr>
                <th align=\"center\">Name</th>
                <th align=\"center\">Username</th>
				<th align=\"center\">Delete Access</th>";
		  print "</tr>";
			  
			  
while ($listItem=mysql_fetch_array($rptResult)){
			  	
				
		
			
											
               print "<td bgcolor=\"#CCCCCC\"  align=\"center\"><a style=\"text-decoration:none;\" href=\"editaccess.php?id=$listItem[id]\">$listItem[name]</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\">$listItem[username]</td>
                <td bgcolor=\"#CCCCCC\" align=\"center\"><a style=\"text-decoration:none;\" href=\"deleteaccess.php?id=$listItem[id]\">Delete</a>";
				//if ($listItem[can_edit_account] == 'yes') 
				//echo "X"; 
				
		
				
				//print "</td>";
				print "</tr><tr><td></td></tr>";
				print "</tr><tr><td></td></tr>";
				print "</tr><tr><td></td></tr>";

		}//END WHILE
			
		print "</table>";

}
?></td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>