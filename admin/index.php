<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ADMIN LOGIN PAGE</title>
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("username_test", "password_test");
	// field description to appear in the dialog box
	var fieldDescription = Array("User Name", "Password");
	// dialog message
	var alertMsg = "Please complete the following fields:\n";
	
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
	}
}
// -->
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

</head>

<body onLoad="form1.username_test.focus();">
<form name="form1" method="post" action="adminLogin.php" onSubmit="return formCheck(this);">
  <p>&nbsp;</p>
  <table width="700" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr bgcolor="#000000"> 
      <td colspan="2" align="right"> <div align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">ADMIN</font></b></div></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td width="202" align="right"> <p><font size="2" face="Arial, Helvetica, sans-serif">Login</font></p></td>
      <td width="604" align="left"> <input name="username_test" type="text" id="username_test" size="40"></td>
    </tr>
    <tr> 
      <td align="right"> <p><font size="2" face="Arial, Helvetica, sans-serif">Password</font></p></td>
      <td align="left"> <input name="password_test" type="password" id="password_test" size="40"></td>
    </tr>
    <tr bgcolor="#DDDDDD"> 
      <td colspan="2" align="center"> <input type="submit" name="submit" value="Connexion"> 
		</td>
    </tr>
  </table>
</form>
  
</body>
</html>
