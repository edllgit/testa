<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");
include("frames_functions.inc.php");
include("image_functions.inc.php");

if ($_POST[from_form]=="add"){
create_color();
}
else if ($_GET[delete]=="true"){
	delete_color($_GET['pkey']);
}
else if ($_GET[edit]=="true"){
	$editForm=TRUE;
	
	$query="SELECT * FROM frames_colors WHERE frames_colors_id='$_GET[pkey]'";
	$result=mysql_query($query)
		or die ("Could not select items");
	$colorItem=mysql_fetch_array($result);
}
else if ($_POST[from_form]=="edit"){
	update_color($_POST['pkey']);
}
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
  		<td>
		<?php
		if ($editForm){include("frameEditColorForm.inc.php");}
		else{include("frameColorForm.inc.php");}
		?>	
</td>
<td>
		<?php
			include("frameColorList.inc.php");
		?>		
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
