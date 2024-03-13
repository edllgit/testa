<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if ($_POST[updateAcct]=="yes"){
	if ($_SESSION["sessionUser_Id"] != ""){
		$result=UpdateIFCAccount($_SESSION["sessionUser_Id"]);
		$_SESSION["sessionUserData"]=mysql_fetch_array($result);
		$_SESSION["sessionUserData"]["email"]=stripslashes($_SESSION["sessionUserData"]["email"]);
		 header("Location:lens_cat_selection.php");
 		 exit();
	}
$message = "Your account information has been updated.";
}


if ($_SESSION["sessionUser_Id"] <> "") {
$queryClient = "Select * from accounts where user_id = '".$_SESSION["sessionUser_Id"]. "'";
$resultClient=mysql_query($queryClient)		or die ("Could not find account");
$DataClient=mysql_fetch_array($resultClient);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>


<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

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
  errors = checkEmail(formname, 'email', 'Email');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
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
<div id="rightColumn"><form action="update_account.php" method="post"  name="accountForm" id="accountForm">
    <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <div class="header"><?php echo 'Pour commander, confirmer votre adresse mail et modifier votre mot de passe';?></div>
    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
	  <tr >
					<td colspan="4" bgcolor="#17A2D2" >Information actuelle</td>
				</tr>
				
			
			
				<tr>
					<td align="left" colspan="2"  class="formCellNosides"><div align="left"><?php echo $lbl_emailtxt;?>&nbsp;&nbsp;&nbsp;<?php print $_SESSION["sessionUserData"]["email"]; ?> </td>
				</tr>
                
                <tr>
				<td align="left" class="formCellNosides">Mot de passe actuel:&nbsp;&nbsp;&nbsp;<?php echo '111111';?></td>
					<td align="left" nowrap class="formCellNosides"></td>
				
				</tr>
			
			
				<tr bgcolor="#17A2D2">
					<td colspan="4" align="left"><div align="left" ><?php echo 'Informations à confirmer (veuillez confirmer votre mail et mettre à jour votre mot de passe)';?></div></td>
				</tr>

				<tr>
					<td align="left" colspan="2"  class="formCellNosides"><div align="left"><?php echo $lbl_emailtxt;?>&nbsp;&nbsp;&nbsp;<input name="email" type="text" id="email" value="<?php print $_SESSION["sessionUserData"]["email"]; ?>" size="30">&nbsp;&nbsp;&nbsp; </td>
				</tr>
                
                
				<tr>
					<td align="left"  class="formCellNosides"><div align="left">
						<?php echo 'Nouveau mot de passe';?>&nbsp;&nbsp;&nbsp;
					<input name="newPW" value="" type="password" id="newPW" size="20"></td>
									
					<td align="left" nowrap class="formCellNosides"><div align="left">
						<?php echo 'Confirmation du mot de passe';?>
					&nbsp;&nbsp;&nbsp;<input name="confirmPW" value="" type="password" id="confirmPW" size="20"></td>
				</tr>
				
			</table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="updateAcct" value="yes">
		      		&nbsp;
		      		<input name="submitBttn" type="submit" class="formText" id="submitBttn" onClick="check('accountForm', this.name);" value="<?php echo $btn_submit_txt;?>">
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
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>