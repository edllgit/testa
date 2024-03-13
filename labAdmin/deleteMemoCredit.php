<?php
session_start();

include "../Connections/directlens.php";
include "../includes/getlang.php";

if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
if ($_REQUEST['mcred_order_num'] <> ''){

//We need to check if some optiPoints were removed from the customer account
$queryMemoCred = "SELECT * FROM memo_credits WHERE  mcred_memo_num = '". $_REQUEST['mcred_order_num'] . "'";
$resultMemoCred=mysql_query($queryMemoCred)	or die ("Could not Get memo credit details");
$DataMemoCred=mysql_fetch_array($resultMemoCred);

$OptiPoints_to_Substract =  $DataMemoCred[optipoints_to_substract];
$Order_num 				 =  $DataMemoCred[mcred_order_num];
$OptiPoints_Reason   	 =  $DataMemoCred[optipoints_reason];
$User_id_Customer    	 =  $DataMemoCred[mcred_acct_user_id];
$lnc_reward_detail 	     = $Order_num . ': ' . '' . $OptiPoints_Reason ;

$QueryUserDetail = "SELECT * from accounts WHERE user_id = '" . $User_id_Customer . "'";
$resultUserDetail=mysql_query($QueryUserDetail)	or die ("Could not get user details");
$DataUser=mysql_fetch_array($resultUserDetail);
$ActualPointBalance = $DataUser[lnc_reward_points];
$NewBalance = $ActualPointBalance + $OptiPoints_to_Substract;



if ($OptiPoints_to_Substract > 0)
{
//We need to add the points that were substractec to the customer accounts
$queryUpdate = "UPDATE ACCOUNTS SET lnc_reward_points = $NewBalance WHERE user_id = '" . $User_id_Customer  . "'";
$resultUpdate=mysql_query($queryUpdate)	or die ("Could not delete memo credit");

$queryValidation  = "SELECT * FROM lnc_reward_history WHERE detail = '" . $lnc_reward_detail   . "'";
$resultValidation=mysql_query($queryValidation)	or die ("Could not delete memo credit");
$nbrResult = mysql_num_rows($resultValidation);

	if ($nbrResult > 0)
	{
	//Then we delete the line from the lnc_reward_history
	$queryUpdate = "DELETE FROM  lnc_reward_history  WHERE detail = '" . $lnc_reward_detail   . "'";
	$resultUpdate=mysql_query($queryUpdate)	or die ("Could not delete memo credit");
	}
}


$query="DELETE from memo_credits where mcred_memo_num = '". $_REQUEST['mcred_order_num'] . "'";
$result=mysql_query($query)	or die ("Could not delete memo credit");

}
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>
<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck(formobj){
	// name of mandatory fields
	var fieldRequired = Array("mcred_amount", "mcred_date");
	// field description to appear in the dialog box
	var fieldDescription = Array("Discount Value", "Date");
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
		echo 'The memo credit has been deleted';
		?>  <p>&nbsp;</p>
</td>
	  </tr>
</table>
  
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
