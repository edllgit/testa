<script language="JavaScript">
<!--
// Copyright information must stay intact
// FormCheck v1.02
// Copyright NavSurf.com 2002, all rights reserved
// For more scripts, visit NavSurf.com at http://navsurf.com

function formCheck2(formobj){
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
<form name="form3" method="post" action="getBuying_group.php" onSubmit="return formCheck2(this);" class="formField">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">ADMIN
            					NEW BUYING GROUP FORM &ndash; LOGIN EXISTS, BUYING
            					GROUP NOT ADDED </font></b></td>
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
					<td align="left"><input name="contact_first" type="text" id="contact_first" size="20" class="formField" value="<?php print $_POST["contact_first"]; ?>"></td>
					<td align="left"><div align="right">
						Contact Last Name
					</div></td>
					<td align="left"><input name="contact_last" type="text" id="contact_last" size="20" class="formField" value="<?php print $_POST["contact_last"]; ?>"></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
						Email
					</div></td>
					<td align="left"><input name="email" type="text" id="email" size="40" class="formField" value="<?php print $_POST["email"]; ?>">
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
					<td align="left"><input name="global_dsc" type="text" id="global_dsc" size="2" maxlength="2" class="formField" value="<?php print $_POST["global_dsc"]; ?>" /></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#DDDDDD"><input type="submit" name="addBG" id="addBG" value="Add Group" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField"></td>
            		</tr>
			</table>
</form>