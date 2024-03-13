<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[memoCodesBack]=="Back to Memo Codes"){
	header("Location: createMemoCode.php");
}
elseif($_POST[editMC]=="Update Memo Code"){
	$mc_pkey=$_POST[pkey];
	$code_test=edit_memo_code($lab_pkey, $_POST[pkey]);
	if($code_test==true)
		$heading="ADMIN MEMO CODE FORM - MEMO CODE EDITED";
}
elseif ($_GET[pkey]){
	$mc_pkey=$_GET[pkey];
	$heading="ADMIN MEMO CODE FORM";
}

$query="SELECT * from memo_codes WHERE mc_primary_key = '$mc_pkey'";//find this memo code
$result=mysql_query($query)
	or die  ('I cannot select memo code because: ' . mysql_error());
$codeTest=mysql_num_rows($result);
if($codeTest!=0){
	$codeData=mysql_fetch_array($result);
	foreach($codeData as $x => $y){
		$codeData[$x] = stripslashes($y);
	}
}else{
	$heading="ADMIN MEMO CODE FORM - MEMO CODE NOT FOUND";
}
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
	var fieldRequired = Array("memo_code", "mc_description");
	// field description to appear in the dialog box
	var fieldDescription = Array("Memo Code", "Memo Code Description");
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
  		<td width="75%"><form name="form3" method="post" action="editMemoCode.php" onSubmit="return formCheck(this);">
            <table border="0" cellpadding="2" cellspacing="0" class="formField" width="100%">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if($heading=="") echo "ADMIN MEMO CODE FORM"; else echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td width="30%"><div align="right">
						Memo Code
					</div></td>
					<td align="left"><input name="memo_code" type="text" id="memo_code" size="20" maxlength="20" value="<?php echo $codeData[memo_code]; ?>"></td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
												Memo	Code	Description			
					</div></td>
					<td align="left"><textarea name="mc_description" cols="40" rows="2" id="mc_description"><?php echo $codeData[mc_description]; ?></textarea></td>
					</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="2" align="center"><input type="hidden" name="pkey" value="<?php echo $codeData[mc_primary_key]; ?>">
						<input type="submit" name="editMC" value="Update Memo Code" class="formField">
						&nbsp;&nbsp;
						<input name="memoCodesBack" type="submit" value="Back to Memo Codes" class="formField"></td>
				</tr></table>
  		</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
