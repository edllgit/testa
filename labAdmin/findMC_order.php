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
	var fieldRequired = Array("order_num");
	// field description to appear in the dialog box
	var fieldDescription = Array("Order Number");
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
  		<td width="75%"><form name="form3" method="post" action="createMemoCredit.php">
            <table border="0" cellpadding="2" cellspacing="0" class="formField" width="100%">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_titlemast_amcos;?></font></b></td>
            		</tr>
            	<?php if($_GET["message"]==1)
					echo "<tr bgcolor=\"#ffffff\">
            		<td align=\"center\" colspan=\"6\"><b><font color=\"#000000\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">No orders found matching your search parameters.</font></b></td></tr>";
				?>

				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						<?php echo $adm_ordernum_txt;?>
					</div></td>
					<td align="left"><input name="order_num" type="text" id="order_num" size="24" maxlength="24"></td>
					<td align="left"><div align="right">
						<?php echo $adm_patrefnum_txt;?></div></td>
					<td align="left"><input name="patient_ref_num" type="text" id="patient_ref_num" size="24"></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="4" align="center"><input type="submit" name="submit" value="<?php echo $btn_searchord_txt;?>" class="formField"><input type="hidden" name="searchOrders" value="Search Orders" class="formField"></td>
				</tr></table>
  		</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
