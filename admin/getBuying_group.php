<?php
session_start();
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$heading="ADMIN BUYING GROUP FORM";

if ($_POST[addBG] == "Add Group"){
	$pkey = add_BG($mysql_db);
}
if ($_POST[editBG] == "Edit Group"){
	$pkey = $_POST[pkey];
	edit_BG($pkey);
}
if ($_POST[deleteBG] == "Delete Group"){
	$pkey = $_POST[pkey];
	delete_BG($pkey);
}
if($_POST[buying_group])
	$pkey=$_POST[buying_group];
	
$query="select * from buying_groups where primary_key = '$pkey'";
$BGResult=mysql_query($query)
	or die ("Could not find group");
$BGData=mysql_fetch_array($BGResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("bg_name", "contact_first", "contact_last", "bg_email");
	// field description to appear in the dialog box
	var fieldDescription = Array("Group Name", "Contact First Name", "Contact Last Name", "Email");
	// dialog message
	var alertMsg = "Please enter:\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "select-one":
				if (obj.selectedIndex == "" || obj.options[obj.selectedIndex].text == ""){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "select-multiple":
				if (obj.selectedIndex == -1){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			default:
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length != l_Msg){
		alert(alertMsg);
		return false;
	}else{
	var goodEmail=document.form3.email.value.match(/\b(^(\S+@).+((\.com)|(\.net)|(\.edu)|(\.mil)|(\.gov)|(\.org)|(\.biz)|(\..{2,2}))$)\b/gi);
	if (!goodEmail){
		var emailMsg="The email address you've entered doesn't appear to be valid. \nPlease edit the email field and resubmit.\n";
		alert(emailMsg);
		return false;
		}	
	}
}
// -->
</script>
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
		<?php
		if ($_POST[deleteBG] == "Delete Group"){
			$heading="ADMIN BUYING GROUP FORM - GROUP DELETED";
			include("deleteBGPage.php");
		}
		elseif ($_POST[editBG] == "Edit Group"){
			$heading="ADMIN BUYING GROUP FORM - GROUP EDITED";
			include("editBGForm.php");
		}
		elseif (($_POST[addBG] == "Add Group")&&($pkey==false)){
			include("newBGForm.php");
		}
		elseif (($_POST[addBG] == "Add Group")&&($pkey!=false)){
			$heading="ADMIN BUYING GROUP FORM - GROUP ADDED";
			include("editBGForm.php");
		}else{
			include("editBGForm.php"); /* if first viewing of BG data */
		}
		?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
