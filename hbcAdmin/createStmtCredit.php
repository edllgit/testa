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

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if ($_POST[createCredit] == "Issue Credit"){
	$acct_user_id = $_POST[acct_user_id];
	$_POST[amount]=abs($_POST[amount]);
	$credit_test=create_stmt_credit($acct_user_id);
	if($credit_test==true)
		$heading="ADMIN CREDIT FORM - CREDIT ISSUED";
}
if ($_GET[delete_key]){
	$credit_key = $_GET[delete_key];
	delete_stmt_credit($credit_key);
	$heading="ADMIN CREDIT FORM - CREDIT DELETED";
}

$creditQuery="SELECT * from statement_credits

LEFT JOIN (accounts) ON (statement_credits.acct_user_id = accounts.user_id) 

LEFT JOIN (labs) ON (accounts.main_lab = labs.primary_key) 

WHERE accounts.main_lab='$lab_pkey' ORDER BY statement_credits.stmt_year DESC, statement_credits.stmt_month DESC";

$creditResult=mysql_query($creditQuery)
	or die ('I cannot select items because: ' . mysql_error());
$creditCount=mysql_num_rows($creditResult);
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
	var fieldRequired = Array("acct_user_id", "amount", "credit_option", "cr_description");
	// field description to appear in the dialog box
	var fieldDescription = Array("Account", "Credit Amount", "Credit Option", "Credit Description");
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
  		<td width="75%"><form name="form3" method="post" action="createStmtCredit.php" onSubmit="return formCheck(this);">
            <table border="0" cellpadding="2" cellspacing="0" class="formField" width="100%">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if($heading=="") echo $adm_titlemast_statecred; else echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td><div align="right">
												<?php echo $adm_statemon_txt;?>
</div></td>
					<td width="10%" align="left"><select name="stmt_month" id="stmt_month" class="formField">
						<option value="01">Jan
						- 01</option>
						<option value="02">Feb
						- 02</option>
						<option value="03">Mar
						- 03</option>
						<option value="04">Apr
						- 04</option>
						<option value="05">May
						- 05</option>
						<option value="06">Jun
						- 06</option>
						<option value="07">Jul
						- 07</option>
						<option value="08">Aug
						- 08</option>
						<option value="09">Sep
						- 09</option>
						<option value="10">Oct
						- 10</option>
						<option value="11">Nov
						- 11</option>
						<option value="12">Dec
						- 12</option>
					</select></td>
					<td><div align="right">
												<?php echo $adm_stateyr_txt;?>					
					</div></td>
					<td align="left"><select name="stmt_year" id="stmt_year" class="formField">
						<option value="2008" selected>2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011" selected>2011</option>
						<option value="2012">2012</option>
						<option value="2013">2013</option>
						<option value="2014">2014</option>
						<option value="2015">2015</option>
										</select></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
						<?php echo $adm_acct_txt;?></div></td>
					<td align="left" ><select name="acct_user_id" id="acct_user_id" class="formField">
						<option value="">Select Account</option>
						<?php
	$query="select user_id, company from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
}
?>
					</select></td>
					<td align="left"><div align="right">
						<?php echo $adm_credamt_txt;?>
					</div></td>
					<td align="left" nowrap="nowrap">&nbsp;&ndash;&nbsp;<?php echo $lbl_moneysym_txt;?>
						<input name="amount" type="text" id="amount" size="8" maxlength="8"></td>
					</tr>
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						<?php echo $adm_credopt_txt;?>
					</div></td>
					<td align="left" ><select name="credit_option" id="credit_option" class="formField">
						<option value="">Select Option</option>
						<option value="Non-Adapt">Non-Adapt</option>
						<option value="Dr Changes/Redo">Dr Changes/Redo</option>
						<option value="Cancellation">Cancellation</option>
						<option value="Lenses Warranty">Lenses Warranty</option>
						<option value="Coating Warranty">Coating Warranty</option>
						<option value="Other">Other</option>
					</select>
					</td>
					<td align="left" ><div align="right">
						<?php echo $adm_descr_txt;?>
					</div></td>
					<td align="left" ><input name="cr_description" type="text" id="cr_description" size="30" class="formField" /></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td colspan="4" align="center"><input type="submit" name="submit" value="Issue Credit" class="formField"><input type="hidden" name="createCredit" value="Issue Credit" class="formField"></td>
				</tr></table>
  		</form>
<?php
if ($creditCount != 0){
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
	echo "<tr bgcolor=\"#000000\"><td colspan=\"6\" align=\"center\"><b><font color=\"#FFFFFF\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">".$adm_monacctcred_txt."</font></b></td></tr>";
	echo "<tr><td><b>".$adm_acct_txt."</b></td><td><b>".$adm_statemonyr_txt."</b></td><td><b>".$adm_amount_txt."</b></td><td><b>".$adm_credopt."</b></td></tr>";
	while($creditData=mysql_fetch_array($creditResult)){
		echo "<tr><td>$creditData[company]</td><td>$creditData[stmt_month]-$creditData[stmt_year]</td><td>\$$creditData[amount]</td><td>$creditData[credit_option]</td><td>$creditData[cr_description]</td><td><a href=\"createStmtCredit.php?delete_key=$creditData[primary_key_cr]\">".$adm_delcredit_txt."</a></td></tr>";
	}
	echo "</table>";
}else{
	echo "<div align=\"center\"><b><font color=\"#000000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">".$adm_nocredits_txt."</b></font></div>";
}
?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
