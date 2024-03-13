<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);

//print $teset;
 
if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


$heading="ADMIN LAB FORM";

if ($_POST[addLab] == "Add Lab"){
	$pkey = add_lab($mysql_db);
	if($pkey=="This user id is already in use."){
		$_SESSION[formVars]=$_POST;
		header("Location:newLab.php");
	}
	else{addLabsStockRedirections($pkey);}
}
if ($_POST[editLab] == "Edit Lab"){
	$pkey = $_POST[pkey];
	edit_lab($pkey);
	addLabsStockRedirections($pkey);
}
if ($_POST[deleteLab] == "Delete Lab"){
	$pkey = $_POST[pkey];
	delete_lab($pkey);
	addLabsStockRedirections($pkey);
}
if($_POST[lab])
	$pkey=$_POST[lab];
	
$query="SELECT * FROM labs WHERE primary_key = '$pkey'";
$labResult=mysqli_query($con,$query)	or die ("Could not find lab");
$labData=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
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
	var fieldRequired = Array("lab_name", "address1", "city", "zip", "phone", "email");
	// field description to appear in the dialog box
	var fieldDescription = Array("Lab Name", "Address 1", "City", "Zip", "Phone", "Email");
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
		if ($_POST[deleteLab] == "Delete Lab"){
			$heading="ADMIN LAB FORM - LAB DELETED";
			include("deleteLabPage.php");
		}
		elseif ($_POST[editLab] == "Edit Lab"){
			$heading="ADMIN LAB FORM - LAB EDITED";
			include("editLabForm.php");
		}
		elseif ($_POST[addLab] == "Add Lab"){
			$heading="ADMIN LAB FORM - LAB ADDED";
			include("editLabForm.php");
		}else{
			include("editLabForm.php"); /* if first viewing of lab data */
		}
		?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
