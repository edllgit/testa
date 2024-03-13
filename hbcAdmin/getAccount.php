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
$heading="ADMIN ACCOUNT FORM";

if ($_POST[editAcct] == $btn_editacct_txt){
	$pkey = $_POST[pkey];
	edit_account($pkey);
}
if ($_POST[deleteAcct] == "Delete Account"){
	$pkey = $_POST[pkey];
	delete_account($pkey);
}

if ($_POST[updateDisc] == $btn_updiscounts_txt){
	$pkey = $_POST[pkey];
	update_discounts($_POST[user_id]);
}
if($_POST[acctName])/* primary key posted from side nav accounts form */
	$pkey=$_POST[acctName];
	
$query="select * from accounts
		LEFT JOIN (acct_credit_limit) ON (accounts.user_id = acct_credit_limit.cl_user_id) 
		where primary_key = '$pkey'";
$acctResult=mysql_query($query)
	or die ("Could not find account");
$accountData=mysql_fetch_array($acctResult);
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("title", "first_name", "last_name", "company", "phone", "email", "bill_address1", "bill_city", "bill_zip", "password");
	// field description to appear in the dialog box
	var fieldDescription = Array("Title", "First Name", "Last Name", "Company", "Phone", "Email", "Billing Address", "Billing City", "Billing Zip", "Password");
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
		if ($_POST[deleteAcct] == "Delete Account"){
			$heading="ADMIN ACCOUNT FORM - ACCOUNT DELETED";
			include("deleteAcctPage.php");
		}
		elseif ($_POST[editAcct] == $btn_editacct_txt){
			$heading="ADMIN ACCOUNT FORM - ACCOUNT EDITED";
			include("editAcctForm.php");
		}else{
			include("editAcctForm.php"); /* if first viewing of account data */
		}
		?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
