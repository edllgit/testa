<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");



if ($_POST[updateAcct]=="yes"){
	if ($_SESSION["sessionUser_Id"] != ""){
		$result=editAccount($_SESSION["sessionUser_Id"]);
		$_SESSION["sessionUserData"]=mysqli_fetch_array($result,MYSQLI_ASSOC);
		$_SESSION["sessionUserData"]["first_name"]=stripslashes($_SESSION["sessionUserData"]["first_name"]);
		$_SESSION["sessionUserData"]["last_name"]=stripslashes($_SESSION["sessionUserData"]["last_name"]);
		$_SESSION["sessionUserData"]["company"]=stripslashes($_SESSION["sessionUserData"]["company"]);
		$_SESSION["sessionUserData"]["bill_address1"]=stripslashes($_SESSION["sessionUserData"]["bill_address1"]);
		$_SESSION["sessionUserData"]["bill_address2"]=stripslashes($_SESSION["sessionUserData"]["bill_address2"]);
		$_SESSION["sessionUserData"]["bill_city"]=stripslashes($_SESSION["sessionUserData"]["bill_city"]);
		$_SESSION["sessionUserData"]["bill_state"]=stripslashes($_SESSION["sessionUserData"]["bill_state"]);
		$_SESSION["sessionUserData"]["bill_zip"]=stripslashes($_SESSION["sessionUserData"]["bill_zip"]);
		$_SESSION["sessionUserData"]["ship_address1"]=stripslashes($_SESSION["sessionUserData"]["ship_address1"]);
		$_SESSION["sessionUserData"]["ship_address2"]=stripslashes($_SESSION["sessionUserData"]["ship_address2"]);
		$_SESSION["sessionUserData"]["ship_city"]=stripslashes($_SESSION["sessionUserData"]["ship_city"]);
		$_SESSION["sessionUserData"]["ship_state"]=stripslashes($_SESSION["sessionUserData"]["ship_state"]);
		$_SESSION["sessionUserData"]["ship_zip"]=stripslashes($_SESSION["sessionUserData"]["ship_zip"]);
		$_SESSION["sessionUserData"]["phone"]=stripslashes($_SESSION["sessionUserData"]["phone"]);
		$_SESSION["sessionUserData"]["other_phone"]=stripslashes($_SESSION["sessionUserData"]["other_phone"]);
		$_SESSION["sessionUserData"]["fax"]=stripslashes($_SESSION["sessionUserData"]["fax"]);
		$_SESSION["sessionUserData"]["email"]=stripslashes($_SESSION["sessionUserData"]["email"]);
		$_SESSION["sessionUserData"]["VAT_no"]=stripslashes($_SESSION["sessionUserData"]["VAT_no"]);
	}
$message = "Your account information has been updated.";
}


if ($_SESSION["sessionUser_Id"] <> "") {
$queryClient = "SELECT * FROM accounts WHERE user_id = '".$_SESSION["sessionUser_Id"]. "'";
$resultClient=mysqli_query($con,$queryClient) or die ("Could not find account");
$DataClient=mysqli_fetch_array($resultClient,MYSQLI_ASSOC);
$Bill_Country = $DataClient['bill_country'];
$Ship_Country = $DataClient['ship_country'];
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}


function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'title', 'Title');
  errors += checkText(formname, 'first_name', 'First Name');
  errors += checkText(formname, 'last_name', 'Last Name');
  errors += checkText(formname, 'bill_address1', 'Billing Address 1');
  errors += checkText(formname, 'bill_city', 'Billing City');
  errors += checkSelect(formname, 'bill_state', 'Billing State/Province');
  errors += checkText(formname, 'bill_zip', 'Billing Zip/Postal Code');
  errors += checkSelect(formname, 'bill_country', 'Billing Country');
  errors += checkText(formname, 'phone', 'Phone');
  errors += checkEmail(formname, 'email', 'Email');
  if(Trim(eval('document.'+formname+'.newPW.value'))!=''){
    errors += checkText(formname, 'oldPW', 'Existing Password');
	errors += checkPW(formname, 'newPW', 'pw_confirm', 'New Password Fields');
  }
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
 <?php 
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="myAccount.php" method="post" enctype="application/x-www-form-urlencoded" name="accountForm" id="accountForm"><div class="bigwelcome"><?php echo $lbl_titlemast_uma;?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
				<tr >
					<td colspan="4" bgcolor="#000099" class="tableHead"><?php if($message!="") echo "$message"; else echo "&nbsp;"; ?></td>
				</tr>
				<tr>
					<td colspan="4" align="left"  class="formText"><?php echo $lbl_submast_uma;?>&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_titletxt;?></div></td>
				  <td align="left" class="formCellNosides"><select name="title" id="title">
				    <option value=""><?php echo $lbl_selectxt;?></option>
				    <option value="<?php echo $lbl_selectxt1;?>" <?php if($_SESSION["sessionUserData"]["title"]==$lbl_selectxt1) echo " selected"; ?>><?php echo $lbl_selectxt1;?></option>
				    <option value="<?php echo $lbl_selectxt2;?>" <?php if($_SESSION["sessionUserData"]["title"]==$lbl_selectxt2) echo " selected"; ?>><?php echo $lbl_selectxt2;?></option>
				    <option value="<?php echo $lbl_selectxt3;?>" <?php if($_SESSION["sessionUserData"]["title"]==$lbl_selectxt3) echo " selected"; ?>><?php echo $lbl_selectxt3;?></option>
				    <option value="<?php echo $lbl_selectxt4;?>" <?php if($_SESSION["sessionUserData"]["title"]==$lbl_selectxt4) echo " selected"; ?>><?php echo $lbl_selectxt4;?></option>
				    </select></td>
				  <td align="left" nowrap class="formCellNosides">&nbsp;</td>
				  <td align="left" class="formCellNosides">&nbsp;</td>
				  </tr>
				<tr>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_fname_txt;?></div></td>
					<td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20" value="<?php echo $_SESSION["sessionUserData"]["first_name"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right">
						<?php echo $lbl_lname_txt;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value="<?php echo $_SESSION["sessionUserData"]["last_name"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_comp_txt;?></div></td>
					<td align="left" class="formCellNosides"><input name="company" type="text" id="company" size="20" value="<?php echo $_SESSION["sessionUserData"]["company"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right">
						Buying Group
					</div></td>
					<td align="left" class="formCellNosides"><select name="buying_group" class="formField">
							<option value="" selected="selected">None</option>
							<?php
	$query="SELECT primary_key, bg_name FROM buying_groups order by bg_name";
	$result=mysqli_query($con,$query) or die ("Could not find bg list");
	while ($bgList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
	if($bgList[primary_key]==$_SESSION["sessionUserData"]["buying_group"])
		echo "<option value=\"$bgList[primary_key]\" selected>$bgList[bg_name]</option>";
	else
		echo "<option value=\"$bgList[primary_key]\">$bgList[bg_name]</option>";
}
?>
						</select></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_phonetxt1;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="20" value="<?php echo $_SESSION["sessionUserData"]["phone"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right">
						Other Phone
					</div></td>
					<td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $_SESSION["sessionUserData"]["other_phone"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_faxtxt;?></div></td>
					<td align="left" class="formCellNosides"><input name="fax" type="text" id="fax" size="20" value="<?php echo $_SESSION["sessionUserData"]["fax"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right">
						VAT Number
					</div></td>
					<td align="left" class="formCellNosides"><input name="VAT_no" type="text" id="VAT_no" size="20" value="<?php echo $_SESSION["sessionUserData"]["VAT_no"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_emailtxt;?></div></td>
					<td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="50" value="<?php echo $_SESSION["sessionUserData"]["email"]; ?>">
					  <div align="right">
</div></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_busstype_txt;?></div></td>
					<td align="left" class="formCellNosides"><select name="business_type">
							<option value="<?php echo $lbl_busstype_txt1;?>" <?php if($_SESSION["sessionUserData"]["business_type"]==$lbl_busstype_txt1) echo " selected"; ?>><?php echo $lbl_busstype_txt1;?></option>
							<option value="<?php echo $lbl_busstype_txt2;?>" <?php if($_SESSION["sessionUserData"]["business_type"]==$lbl_busstype_txt2) echo " selected"; ?>><?php echo $lbl_busstype_txt2;?></option>
							<option value="<?php echo $lbl_busstype_txt3;?>" <?php if($_SESSION["sessionUserData"]["business_type"]==$lbl_busstype_txt3) echo " selected"; ?>><?php echo $lbl_busstype_txt3;?></option>
					</select>
                    </td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_mlprf_txt;?></div></td>
					<td align="left" class="formCellNosides">&nbsp;</td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_currency_txt;?></div></td>
					<td align="left" class="formCellNosides"><select name="currency" id="currency">
		<option value="<?php echo $lbl_currency_abr_txt1;?>" <?php if($_SESSION["sessionUserData"]["currency"]==$lbl_currency_abr_txt1) echo " selected=\"selected\""; ?>><?php echo $lbl_currency_txt1;?></option>
		<option value="<?php echo $lbl_currency_abr_txt2;?>" <?php if($_SESSION["sessionUserData"]["currency"]==$lbl_currency_abr_txt2) echo " selected=\"selected\""; ?>><?php echo $lbl_currency_txt2;?></option>
		<option value="<?php echo $lbl_currency_abr_txt3;?>" <?php if($_SESSION["sessionUserData"]["currency"]==$lbl_currency_abr_txt3) echo " selected=\"selected\""; ?>><?php echo $lbl_currency_txt3;?></option>
	</select> 
    </td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_orderunits_txt;?></div></td>
					<td align="left" class="formCellNosides"><input name="purchase_unit" type="radio" value="single" <?php if($_SESSION["sessionUserData"]["purchase_unit"]=="single") echo " checked=\"checked\""; ?> />
					  <?php echo $lbl_orderunits1;?>
					  <input name="purchase_unit" type="radio" value="pair" <?php if($_SESSION["sessionUserData"]["purchase_unit"]=="pair") echo " checked=\"checked\""; ?> />
					  <?php echo $lbl_orderunits2;?></td>
				</tr>
				<tr bgcolor="#000099">
					<td colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"><font color="#FFFFFF"><b><?php echo $lbl_titlemast2;?></b></font></div></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_address1_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="bill_address1" type="text" id="bill_address1" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address1"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_address2_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="bill_address2" type="text" id="bill_address2" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address2"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_city_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="bill_city" type="text" id="bill_city" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_city"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_state_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="bill_state" type="text" id="bill_state" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_state"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_zip_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="bill_zip" type="text" id="bill_zip" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_zip"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_country_txt_ba;?></div></td>
					<td align="left" class="formCellNosides">
                    <select name = "bill_country" id="bill_country">
				 
                   <option value="">Select One</option>
                       <option value ="BE" <?php if($Bill_Country=="BE") echo " selected"; ?>>Benin</option>
                  <option value ="BF" <?php if($Bill_Country=="BF") echo " selected"; ?>>Burkina Faso</option>
                  <option value ="CA" <?php if($Bill_Country=="CA") echo " selected"; ?>>Canada</option>
                  <option value ="CAM"<?php if($Bill_Country=="CAM") echo " selected"; ?>>Cameroun</option>
                  <option value ="CR" <?php if($Bill_Country=="CR") echo " selected"; ?>>Caribbean</option>
                  <option value ="CB" <?php if($Bill_Country=="CB") echo " selected"; ?>>Congo-Brazzaville</option>
                  <option value ="CI" <?php if($Bill_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
                  <option value ="FR" <?php if($Bill_Country=="FR") echo " selected"; ?>>France</option>
                  <option value ="GA" <?php if($Bill_Country=="GA") echo " selected"; ?>>Gabon</option>
                  <option value ="IT" <?php if($Bill_Country=="IT") echo " selected"; ?>>Italy</option>
                  <option value ="MA" <?php if($Bill_Country=="MA") echo " selected"; ?>>Mali</option>
                  <option value ="SE" <?php if($Bill_Country=="SE") echo " selected"; ?>>Senegal</option>
                  <option value ="RDC"<?php if($Bill_Country=="RDC")echo " selected"; ?>>Republique democratique du Congo</option>
                  <option value ="TO" <?php if($Bill_Country=="TO") echo " selected"; ?>>Togo</option>
                  <option value ="US" <?php if($Bill_Country=="US") echo " selected"; ?>>United States</option>
					
                    </select>
                                        </td>
				</tr>
				<tr bgcolor="#000099">
					<td colspan="4" align="left"  class="formCellNosides"><div align="center">
							<span class="style1"><font color="#FFFFFF"><b><?php echo $lbl_titlemast3;?></b></font></span>
					</div></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_address1_sa;?></div></td>
					<td align="left" class="formCellNosides"><input name="ship_address1" type="text" id="ship_address1" size="20" value="<?php echo $_SESSION["sessionUserData"]["ship_address1"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_address2_sa;?></div></td>
					<td align="left" class="formCellNosides"><input name="ship_address2" type="text" id="ship_address2" size="20" value="<?php echo $_SESSION["sessionUserData"]["ship_address2"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_city_sa;?></div></td>
					<td align="left" class="formCellNosides"><input name="ship_city" type="text" id="ship_city" size="20" value="<?php echo $_SESSION["sessionUserData"]["ship_city"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_state_sa;?></div></td>
					<td align="left" class="formCellNosides"><input name="ship_state" type="text" id="ship_state" size="20" value="<?php echo $_SESSION["sessionUserData"]["ship_state"]; ?>"></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right"><?php echo $lbl_zip_ba;?></div></td>
					<td align="left" class="formCellNosides"><input name="ship_zip" type="text" id="ship_zip" size="20" value="<?php echo $_SESSION["sessionUserData"]["ship_zip"]; ?>"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_country_txt_sa;?></div></td>
					<td align="left" class="formCellNosides"><select name = "ship_country" id="ship_country">
				 
                   <option value="">Select One</option>
                   <option value ="BE" <?php if($Ship_Country=="BE") echo " selected"; ?>>Benin</option>
                  <option value ="BF" <?php if($Ship_Country=="BF") echo " selected"; ?>>Burkina Faso</option>
                  <option value ="CA" <?php if($Ship_Country=="CA") echo " selected"; ?>>Canada</option>
                  <option value ="CAM"<?php if($Ship_Country=="CAM") echo " selected"; ?>>Cameroun</option>
                  <option value ="CR" <?php if($Ship_Country=="CR") echo " selected"; ?>>Caribbean</option>
                  <option value ="CB" <?php if($Ship_Country=="CB") echo " selected"; ?>>Congo-Brazzaville</option>
                  <option value ="CI" <?php if($Ship_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
                  <option value ="FR" <?php if($Ship_Country=="FR") echo " selected"; ?>>France</option>
                  <option value ="GA" <?php if($Ship_Country=="GA") echo " selected"; ?>>Gabon</option>
                  <option value ="IT" <?php if($Ship_Country=="IT") echo " selected"; ?>>Italy</option>
                  <option value ="MA" <?php if($Ship_Country=="MA") echo " selected"; ?>>Mali</option>
                  <option value ="SE" <?php if($Ship_Country=="SE") echo " selected"; ?>>Senegal</option>
                  <option value ="RDC"<?php if($Ship_Country=="RDC") echo " selected"; ?>>Republique democratique du Congo</option>
                  <option value ="TO" <?php if($Ship_Country=="TO") echo " selected"; ?>>Togo</option>
                  <option value ="US" <?php if($Ship_Country=="US") echo " selected"; ?>>United States</option>
					</select>
                                        </td>
				</tr>
				<tr bgcolor="#000099">
					<td colspan="4" align="left"  class="formCellNosides"><div align="center" class="style1"><font color="#FFFFFF"><b><?php echo $lbl_titlemast4;?></b></font></div></td>
				</tr>

				<tr>
					<td colspan="4" align="left"  class="formText"><?php echo $lbl_login_txt_uma;?></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_existpw_txt;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="oldPW" type="password" id="oldPW" size="20"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right">
						<?php echo $lbl_newpw_txt;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="newPW" type="password" id="newPW" size="20"></td>
				</tr>
				<tr>
					<td colspan="3" align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_confirmnewpw_txt;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="pw_confirm" type="password" id="pw_confirm" size="20"></td>
				</tr>
				<tr>
			  <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="100" height="1"></td>
              <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="50" height="1"></td>
              <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="100" height="1"></td>
              <td align="left"  class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/spacer.gif" width="50" height="1"></td>
            
				  </tr>
			</table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="updateAcct" value="yes">
		      		&nbsp;
		      		<input name="submitBttn" type="button" class="formText" id="submitBttn" onClick="check('accountForm', this.name);" value="<?php echo $btn_submit_txt;?>">
		      	</p>
		      	<p class="formText"><?php echo $lbl_footer_uma;?></p>
		    </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html