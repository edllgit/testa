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

if ($_POST[createMC] == "Create Memo Code"){
	$code_test=create_memo_code($lab_pkey);
	if($code_test==true)
		$heading="ADMIN MEMO CODE FORM - MEMO CODE CREATED";
}

$codeQuery="SELECT * from memo_codes WHERE mc_lab='$lab_pkey' ORDER BY memo_code";

$codeResult=mysql_query($codeQuery)
	or die ('I cannot select items because: ' . mysql_error());
$codeCount=mysql_num_rows($codeResult);
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
  		<td width="75%"><form name="form3" method="post" action="createMemoCode.php" onSubmit="return formCheck(this);">
            <table border="0" cellpadding="2" cellspacing="0" class="formField" width="100%">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if($heading=="") echo $adm_titlemast_amcf; else echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td width="30%"><div align="right">
						<?php echo $adm_memocode_txt;?>
					</div></td>
					<td align="left"><input name="memo_code" type="text" id="memo_code" size="20" maxlength="20"></td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
												<?php echo $adm_memocodedsc_txt;?>			
					</div></td>
					<td align="left"><textarea name="mc_description" cols="40" rows="2" id="mc_description"></textarea></td>
					</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="2" align="center"><input type="submit" name="submit" value="<?php echo $btn_creatememo_txt;?>	" class="formField"><input type="hidden" name="createMC" value="Create Memo Code" class="formField"></td>
				</tr></table>
  		</form>
<?php
if ($codeCount != 0){
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
	echo "<tr bgcolor=\"#000000\"><td colspan=\"3\" align=\"center\"><b><font color=\"#FFFFFF\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">".$adm_memocodes_txt."</font></b></td></tr>";
	echo "<tr><td><b>".$adm_memocode_txt."</b></td><td><b>".$adm_descr_txt."</b></td><td><b>Edit</b></td></tr>";
	while($codeData=mysql_fetch_array($codeResult)){
		echo "<tr><td>$codeData[memo_code]</td><td>$codeData[mc_description]</td><td><a href='editMemoCode.php?pkey=$codeData[mc_primary_key]'>".$adm_edit_txt."</a></td></tr>";
	}
	echo "</table>";
}else{
	echo "<div align=\"center\"><b><font color=\"#000000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">".$adm_error3_txt."</b></font></div>";
}
?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
