<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$heading="ADMIN BUYING GROUP FORM";

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
	var fieldRequired = Array("bg_name", "contact_first", "contact_last", "bg_email", "username", "password");
	// field description to appear in the dialog box
	var fieldDescription = Array("Group Name", "Contact First Name", "Contact Last Name", "Email", "Login", "Password");
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
  		<td width="75%"><form name="form3" method="post" action="getBuying_group.php" onSubmit="return formCheck(this);" class="formField">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">ADMIN
            					NEW BUYING GROUP FORM </font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Group
						Name						
					</div></td>
					<td align="left">
						<input name="bg_name" type="text" id="bg_name" size="20" class="formField" value="<?php print $_POST["bg_name"]; ?>">					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Contact First Name
					</div></td>
					<td align="left"><input name="contact_first" type="text" id="contact_first" size="20" class="formField"></td>
					<td align="left"><div align="right">
						Contact Last Name
					</div></td>
					<td align="left"><input name="contact_last" type="text" id="contact_last" size="20" class="formField"></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
						Email
					</div></td>
					<td align="left"><input name="email" type="text" id="email" size="40" class="formField">
							<div align="right">
						</div></td>
					<td align="left"><div align="right">
						Login
					</div></td>
					<td align="left"><input name="username" type="text" id="username" size="20" class="formField" /></td>
					<td align="left"><div align="right">
						Password
					</div></td>
					<td align="left"><input name="password" type="text" id="password" size="20" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
						Global Discount
					</div></td>
					<td align="left"><input name="global_dsc" type="text" id="global_dsc" size="2" maxlength="2" class="formField" /></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="submit" name="addBG" id="addBG" value="Add Group" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField"></td>
            		</tr>
			</table>
  		</form></td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
