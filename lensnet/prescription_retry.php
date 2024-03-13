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
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("includes/pw_functions.inc.php");
require('../includes/dl_order_functions.inc.php');
global $drawme;
//require_once "../upload/phpuploader/include_phpuploader.php";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
	
		
$query="SELECT index_v FROM exclusive group by index_v asc"; /* select all openings */
$result=mysqli_query($con,$query)	or die ("Could not select items");
$usercount=mysqli_num_rows($result);

$queryLab = "SELECT main_lab FROM accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
$resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
$DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
$LabNum=$DataLab[main_lab];	


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Lensnet Club</title>
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

<?php //} ?>


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form_retry.js.inc.php";?>
</head>

<body onload="setEnabled(PRESCRIPTION)">
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION"   onSubmit="return validate(this)"><div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_user_txt;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;</div>
              <div>
		     <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_patient;?>&nbsp;</td>
                <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td class="formCellNosides"><?php echo $adm_refnumber_txt;?></td>
                <td class="formCellNosides"><?php echo 'Tray (Lab Only)';?>&nbsp;</td>
                <td class="formCellNosides"><?php echo $lbl_salesname_txt;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php echo $_SESSION['PrescrData']['LAST_NAME'];?>" size="20" /></td>
                <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME"  value="<?php echo $_SESSION['PrescrData']['FIRST_NAME'];?>" size="20" /></td>
                <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM"  value="<?php echo $_SESSION['PrescrData']['PATIENT_REF_NUM'];?>" size="10" /></td>
                 <td class="formCellNosides"><input name="TRAY_NUM" type="text" class="formText" id="TRAY_NUM"  value="<?php echo $_SESSION['PrescrData']['TRAY_NUM'];?>" size="8" /></td>
                <td class="formCellNosides"><select name="SALESPERSON_ID" class="formText" id="SALESPERSON_ID">
                  <option value="" selected="selected"><?php echo $lbl_none_txt_pl;?></option>
                  <?php
						$user_id=$_SESSION["sessionUser_Id"];
  $query="SELECT sales_id,first_name,last_name FROM salespeople WHERE acct_user_id='$user_id' AND removed!='Yes' ORDER by last_name,first_name"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 echo "<option value=\"$listItem[sales_id]\"";
 
 if ($listItem[sales_id]==$_SESSION['PrescrData']['SALESPERSON_ID']){
 	echo "selected=\"selected\"";}
 
 echo ">";
 $name=stripslashes($listItem[first_name])." ".stripslashes($listItem[last_name]);
 echo "$name</option>";}?>
                </select></td>
              </tr>
	      </table></div>
             <div>
				<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_mast1;?>
                  <span class="formCell">
                  <input name="EYE" type="radio" value="Both" onclick="ActivateAll_fields(this.form);"  <?php if ($_SESSION['PrescrData']['EYE']=="Both")   echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription1_pl;?> <span class="formCell"> &nbsp;
                  <input name="EYE" type="radio"  onclick="DesactivateLE_fields(this.form);" value="R.E." <?php if ($_SESSION['PrescrData']['EYE']=="R.E.") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription2_pl;?>&nbsp; <span class="formCell">
                  <input name="EYE" type="radio" onclick="DesactivateRE_fields(this.form);"  value="L.E." <?php if ($_SESSION['PrescrData']['EYE']=="L.E.") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription3_pl;?> </td>
                </tr>
              <tr>
                <td colspan="2"  class="formCell">&nbsp;</td>
                <td align="center" class="formCellNosides"><?php echo $adm_sphere_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo $adm_cylinder_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo $adm_axis_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo $adm_addition_txt;?></td>
                <td align="center" class="formCell"><?php echo $adm_prism_txt;?></td>
              </tr>
              <tr >
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/lensnet/images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                <td align="right" valign="top"  class="formCell"><?php echo $adm_re_txt;?></td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                    <option value="+14"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+14") echo "selected=\"selected\"";?>>+14</option>
                        <option value="+13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+13") echo "selected=\"selected\"";?>>+13</option>
                  <option value="+12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+12") echo "selected=\"selected\"";?>>+12</option>
                  <option value="+11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+11") echo "selected=\"selected\"";?>>+11</option>
                  <option value="+10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+10") echo "selected=\"selected\"";?>>+10</option>
                  <option value="+9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+9") echo "selected=\"selected\"";?>>+9</option>
                  <option value="+8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
				  <option value="+7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
				  <option value="+6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
				  <option value="+5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
				  <option value="+4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
				  <option value="+3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
				  <option value="+2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
				  <option value="+1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
				  <option value="+0"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
				  <option value="-0"<?php if (($_SESSION['PrescrData']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_SPH_NUM'])< 2)) echo "selected=\"selected\"";?>>-0</option>
				  <option value="-1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
				  <option value="-2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
				  <option value="-3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
				  <option value="-4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
				  <option value="-5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
				  <option value="-6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
				  <option value="-7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
				  <option value="-8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
				  <option value="-9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
				  <option value="-10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
				  <option value="-11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-11") echo "selected=\"selected\"";?>>-11</option>
				  <option value="-12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-12") echo "selected=\"selected\"";?>>-12</option>
				  <option value="-13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-13") echo "selected=\"selected\"";?>>-13</option>
				  <option value="-14"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-14") echo "selected=\"selected\"";?>>-14</option>
				  <option value="-15"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-15") echo "selected=\"selected\"";?>>-15</option>
                  </select>
                  <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['RE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_SPH_DEC'])< 2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
              
                  <option value="-0" <?php if (($_SESSION['PrescrData']['RE_CYL_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_CYL_NUM'])< 2)) echo "selected=\"selected\"";?>>-0</option>
                  <option value="-1"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                  <option value="-2"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                  <option value="-3"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                  <option value="-4"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                  <option value="-5"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                  <option value="-6"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                  <option value="-7"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                  <option value="-8"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                </select>
                  <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                    <option value=".00" <?php if (($_SESSION['PrescrData']['RE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_CYL_DEC'])< 2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" 
                value="<?php	if ($_SESSION['PrescrData']['RE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['RE_AXIS'];
				 ?>"
                 onchange="validateRE_Axis(this)" />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
                  <option value="+4.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+4.00") echo "selected=\"selected\"";?>>+4.00</option>
                  <option value="+3.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.50") echo "selected=\"selected\"";?>>+3.50</option>
                  <option value="+3.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.25") echo "selected=\"selected\"";?>>+3.25</option>
                  <option value="+3.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.00") echo "selected=\"selected\"";?>>+3.00</option>
                  <option value="+2.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.75") echo "selected=\"selected\"";?>>+2.75</option>
                  <option value="+2.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.50") echo "selected=\"selected\"";?>>+2.50</option>
                  <option value="+2.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.25") echo "selected=\"selected\"";?>>+2.25</option>
                  <option value="+2.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.00") echo "selected=\"selected\"";?>>+2.00</option>
                  <option value="+1.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.75") echo "selected=\"selected\"";?>>+1.75</option>
                  <option value="+1.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.50") echo "selected=\"selected\"";?>>+1.50</option>
                  <option value="+1.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.25") echo "selected=\"selected\"";?>>+1.25</option>
                  <option value="+1.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.00") echo "selected=\"selected\"";?>>+1.00</option>
                  <option value="+0.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.75") echo "selected=\"selected\"";?>>+0.75</option>
                  <option value="+0.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.50") echo "selected=\"selected\"";?>>+0.50</option>
                  <option value="+0.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.25") echo "selected=\"selected\"";?>>+0.25</option>
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['RE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['RE_ADD'])< 2)) echo "selected=\"selected\"";?>>+0.00</option>
                    </select></td>
                <td align="right" valign="top"class="formCell"><input name="RE_PR_IO" type="radio" value="In" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='In') echo 'checked="checked"';?>/>
<?php echo $adm_in_txt;?>&nbsp;<input name="RE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='Out') echo 'checked="checked"';?>/><?php echo $adm_out_txt;?>
<input name="RE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='None') echo 'checked="checked"';?>/> 
<?php echo $adm_none_txt;?>

<input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" 
value="<?php	if ($_SESSION['PrescrData']['RE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['RE_PR_AX'];
				 ?>"

 /><br /><input name="RE_PR_UD" type="radio" value="Up" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Up') echo 'checked="checked"';?>/><?php echo $adm_up_txt;?>&nbsp;<input name="RE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Down') echo 'checked="checked"';?>/><?php echo $adm_down_txt;?>
<input name="RE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='None') echo 'checked="checked"';?>/> 
<?php echo $adm_none_txt;?>

<input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4"
 value="<?php	if ($_SESSION['PrescrData']['RE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['RE_PR_AX2'];
				 ?>"
 /></td>
              </tr>
              <tr >
                <td colspan="2" align="right" valign="top"class="formCell"><?php echo $adm_le_txt;?></td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                <option value="+14"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+14") echo "selected=\"selected\"";?>>+14</option>
                <option value="+13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+13") echo "selected=\"selected\"";?>>+13</option>
			 <option value="+12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+12") echo "selected=\"selected\"";?>>+12</option>
                  <option value="+11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+11") echo "selected=\"selected\"";?>>+11</option>
                  <option value="+10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+10") echo "selected=\"selected\"";?>>+10</option>
                  <option value="+9"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+9") echo "selected=\"selected\"";?>>+9</option>
                  <option value="+8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
				  <option value="+7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
				  <option value="+6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
				  <option value="+5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
				  <option value="+4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
				  <option value="+3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
				  <option value="+2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
				  <option value="+1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
				  <option value="+0"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
				  <option value="-0"<?php if (($_SESSION['PrescrData']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_SPH_NUM'])< 2)) echo "selected=\"selected\"";?>>-0</option>
				  <option value="-1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
				  <option value="-2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
				  <option value="-3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
				  <option value="-4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
				  <option value="-5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
				  <option value="-6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
				  <option value="-7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
				  <option value="-8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
				  <option value="-9"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
				  <option value="-10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
				  <option value="-11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-11") echo "selected=\"selected\"";?>>-11</option>
				  <option value="-12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-12") echo "selected=\"selected\"";?>>-12</option>
				  <option value="-13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-13") echo "selected=\"selected\"";?>>-13</option>
				  <option value="-14"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-14") echo "selected=\"selected\"";?>>-14</option>
				  <option value="-15"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-15") echo "selected=\"selected\"";?>>-15</option>
                  </select>
                  <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['LE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_SPH_DEC'])< 2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">   
                	
                  <option value="-0" <?php if (($_SESSION['PrescrData']['LE_CYL_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_CYL_NUM'])< 2)) echo "selected=\"selected\"";?>>-0</option>
                  <option value="-1"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                  <option value="-2"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                  <option value="-3"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                  <option value="-4"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                  <option value="-5"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                  <option value="-6"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                  <option value="-7"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                  <option value="-8"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                </select>
                  <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                                   <option value=".75"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                    <option value=".00" <?php if (($_SESSION['PrescrData']['LE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_CYL_DEC'])< 2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" 
                value="<?php	if ($_SESSION['PrescrData']['LE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['LE_AXIS'];
				 ?>"
                
                />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
                  <option value="+4.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+4.00") echo "selected=\"selected\"";?>>+4.00</option>
                  <option value="+3.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.50") echo "selected=\"selected\"";?>>+3.50</option>
                  <option value="+3.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.25") echo "selected=\"selected\"";?>>+3.25</option>
                  <option value="+3.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.00") echo "selected=\"selected\"";?>>+3.00</option>
                  <option value="+2.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.75") echo "selected=\"selected\"";?>>+2.75</option>
                  <option value="+2.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.50") echo "selected=\"selected\"";?>>+2.50</option>
                  <option value="+2.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.25") echo "selected=\"selected\"";?>>+2.25</option>
                  <option value="+2.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.00") echo "selected=\"selected\"";?>>+2.00</option>
                  <option value="+1.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.75") echo "selected=\"selected\"";?>>+1.75</option>
                  <option value="+1.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.50") echo "selected=\"selected\"";?>>+1.50</option>
                  <option value="+1.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.25") echo "selected=\"selected\"";?>>+1.25</option>
                  <option value="+1.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.00") echo "selected=\"selected\"";?>>+1.00</option>
                  <option value="+0.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.75") echo "selected=\"selected\"";?>>+0.75</option>
                  <option value="+0.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.50") echo "selected=\"selected\"";?>>+0.50</option>
                  <option value="+0.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.25") echo "selected=\"selected\"";?>>+0.25</option>
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['LE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['LE_ADD'])< 2)) echo "selected=\"selected\"";?>>+0.00</option>
                </select></td>
                <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In"<?php if ($_SESSION['PrescrData']['LE_PR_IO']=='In') echo 'checked="checked"';?>/><?php echo $adm_in_txt;?>&nbsp;<input name="LE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='Out') echo 'checked="checked"';?>/><?php echo $adm_out_txt;?>
                  <input name="LE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='None') echo 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" 
                  value=""<?php	if ($_SESSION['PrescrData']['LE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX'];
				 ?>""
                   size="4" maxlength="4" /><br /><input name="LE_PR_UD" type="radio" value="Up"<?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Up') echo 'checked="checked"';?>/><?php echo $adm_up_txt;?>&nbsp;<input name="LE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Down') echo 'checked="checked"';?>/><?php echo $adm_down_txt;?>&nbsp;
                  <input name="LE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='None') echo 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" 
                  value=""<?php	if ($_SESSION['PrescrData']['LE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX2'];
				 ?>""
                  
                   size="4" maxlength="4" /></td>
              </tr>
          </table></div>
                            
			
			
			   <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                  
                  
                   <select name="INDEX" class="formText" id="INDEX">  
                        <option value="ANY" selected="selected">ANY</option>
                        <option value="1.50" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.50") echo "selected=\"selected\""; echo ">"; ?>1.50</option>                        
                        <option value="1.52" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.52") echo "selected=\"selected\""; echo ">"; ?>1.52</option>
                        <option value="1.53" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.53") echo "selected=\"selected\""; echo ">"; ?>1.53</option>
                        <option value="1.54" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.54") echo "selected=\"selected\""; echo ">"; ?>1.54</option>
                        <option value="1.56" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.56") echo "selected=\"selected\""; echo ">"; ?>1.56</option>
                        <option value="1.57" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.57") echo "selected=\"selected\""; echo ">"; ?>1.57</option>
                        <option value="1.58" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.58") echo "selected=\"selected\""; echo ">"; ?>1.58</option>
                        <option value="1.59" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.59") echo "selected=\"selected\""; echo ">"; ?>1.59 Tintable</option>
                        <option value="1.592" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.592") echo "selected=\"selected\""; echo ">"; ?>1.59 Tegra</option>
                        <option value="1.60" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.60") echo "selected=\"selected\""; echo ">"; ?>1.60</option>
                        <option value="1.67" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.67") echo "selected=\"selected\""; echo ">"; ?>1.67</option>
                        <option value="1.70" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.70") echo "selected=\"selected\""; echo ">"; ?>1.70</option>
                        <option value="1.74" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.74") echo "selected=\"selected\""; echo ">"; ?>1.74</option>
                        <option value="1.80" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.80") echo "selected=\"selected\""; echo ">"; ?>1.80</option>
                        <option value="1.90" <?php  if ($_SESSION['PrescrData']['INDEX']=="1.90") echo "selected=\"selected\""; echo ">"; ?>1.90</option>                      
                    </select>
                  
                  
               
                       
                       
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_coating_txt;?></span></td>
                   <td align="left" class="formCellNosides"><select name="COATING" class="formText" id="COATING">
                     <option value="ANY" selected="<?php if ($_SESSION['PrescrData']['COATING']=="ANY") echo "selected=\"selected\"";?>"  >ANY</option>
                     <option value="Hard Coat" <?php if ($_SESSION['PrescrData']['COATING']=="Hard Coat") echo "selected=\"selected\"";?>>Hard Coat</option>
                     <option value="AR" <?php if ($_SESSION['PrescrData']['COATING']=="AR") echo "selected=\"selected\"";?>>AR</option>
                     <option value="Uncoated" <?php if ($_SESSION['PrescrData']['COATING']=="Uncoated") echo "selected=\"selected\"";?>>Uncoated</option>  
                   </select></td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_photochr_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="PHOTO" class="formText" id="PHOTO">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['PHOTO']=="None") echo "selected=\"selected\"";?>"><?php echo $adm_none_txt;?></option>
                       <?php
  $query="SELECT photo FROM exclusive WHERE photo NOT IN ('Yellow','Orange','Pink','Blue','Violet','Extra Active Brown')  group by photo asc"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 
 if ($listItem[photo]!="None"){
  
  echo "<option value=\"$listItem[photo]\"";
  
 if ($_SESSION['PrescrData']['PHOTO']=="$listItem[photo]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[photo]);
 echo "$name</option>";}}?>
                   </select>
                   </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_polarized_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['POLAR']=="None") echo "selected=\"selected\"";?>">None</option>
                       <?php
  $query="SELECT polar FROM exclusive group by polar asc"; /* select all openings */
$result=mysqli_query($con,$query) or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 
 if ($listItem[polar]!="None"){
  
  echo "<option value=\"$listItem[polar]\"";
  
 if ($_SESSION['PrescrData']['POLAR']=="$listItem[polar]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}}?>
                   </select>
                   </span></td>
                   </tr>
                   
                   
                          <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
				echo 'Catégorie de verres:';
				}else {
				echo 'Lens Filter';
				}
				?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                   
<?php if ($mylang == 'lang_french'){				?>
<select name="lens_category">
    <option disabled="disabled" value="">*CATÉGORIE DE VERRES*</option>
    <option value="all"            <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='all')            echo 'selected="selected"'; ?>>Tous</option> 
    <option value="bifocal" 	   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='bifocal') 	     echo 'selected="selected"'; ?>>Bi-focal</option>
    <option value="glass"  	       <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='glass') 	     echo 'selected="selected"'; ?>>Glass</option>
    <option value="all prog"  	   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='all prog') 	     echo 'selected="selected"'; ?>>Tous Progressifs</option>
    <option value="prog cl"  	   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog cl') 	     echo 'selected="selected"'; ?>>Progressif Classique</option>
    <option value="prog ds"  	   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog ds') 	     echo 'selected="selected"'; ?>>Progressif DS</option>
    <option value="prog ff"  	   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog ff') 	     echo 'selected="selected"'; ?>>Progressif FF</option>
    <option value="sv"             <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='sv') 		     echo 'selected="selected"'; ?>>Sv</option>
    <option disabled="disabled" value="">&nbsp;</option>
    <option disabled="disabled" value="">*TYPE DE VERRES*</option>
    <option value="AO Compact"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='AO Compact')     echo 'selected="selected"'; ?>>AO Compact</option> 
    <option value="Compact Ultra HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Compact Ultra HD') echo 'selected="selected"'; ?>>Compact Ultra HD</option> 
    <option value="EZ" 			   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='EZ') 			 echo 'selected="selected"'; ?>>EZ BY CZV</option> 
    <option value="FT28" 		   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT28') 		     echo 'selected="selected"'; ?>>FT28</option> 
    <option value="FT35" 		   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT35') 			 echo 'selected="selected"'; ?>>FT35</option> 
    <option value="FT45" 		   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT45') 	         echo 'selected="selected"'; ?>>FT45</option> 
    <option value="Innovative 1.0" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 1.0") echo 'selected="selected"'; ?>>Innovative 1.0</option> 
    <option value="Innovative 2.0" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 2.0") echo 'selected="selected"'; ?>>Innovative 2.0</option> 
    <option value="Innovative 3.0" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 3.0") echo 'selected="selected"'; ?>>Innovative 3.0</option> 
    <option value="RD" 			   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='RD') 		     echo 'selected="selected"'; ?>>RD</option> 
    <option value="SelectionRx"    <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='SelectionRx')    echo 'selected="selected"'; ?>>SelectionRx</option> 
    <option value="Single Vision"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Single Vision')  echo 'selected="selected"'; ?>>Single Vision</option> 
    <option value="Sola Easy"      <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Sola Easy')      echo 'selected="selected"'; ?>>Sola Easy</option> 
    <option value="Trifocal 7x28"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Trifocal 7x28')  echo 'selected="selected"'; ?>>Trifocal 7x28</option>
    <option value="Trifocal 8x35"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Trifocal 8x35')  echo 'selected="selected"'; ?>>Trifocal 8x35</option> 
    <option value="Vision Eco"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Vision Eco')     echo 'selected="selected"'; ?>>Vision Eco</option> 
    <option disabled="disabled" value="">&nbsp;</option>
    <option disabled="disabled" value="">*FABRICANT*</option>
    <option value="ESSILOR"        <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='ESSILOR')        echo 'selected="selected"'; ?>>ESSILOR</option> 
    <option value="HOYA"           <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='HOYA')           echo 'selected="selected"'; ?>>HOYA</option> 
    <option value="KODAK"  	       <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='KODAK')          echo 'selected="selected"'; ?>>KODAK</option> 
    <option value="OPTOVISION"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='OPTOVISION')     echo 'selected="selected"'; ?>>OPTOVISION</option>
    <option value="RODENSTOCK"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='RODENSTOCK')     echo 'selected="selected"'; ?>>RODENSTOCK</option> 
    <option value="IOT"            <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="IOT")            echo 'selected="selected"'; ?>>IOT</option> 
    <option value="OPTOTECH"       <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="OPTOTECH")       echo 'selected="selected"'; ?>>OPTOTECH</option>  
    <option value="SOLA/ZEISS"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SOLA/ZEISS")     echo 'selected="selected"'; ?>>SOLA/ZEISS</option>  
</select>

<?php 
}else {
?>

<select name="lens_category">
    <option  disabled="disabled" value="">*LENS CATEGORY*</option>
    <option  value="all"  		    <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='all') 			  echo 'selected="selected"'; ?>>All</option>
    <option  value="bifocal"  	    <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='bifocal')        echo 'selected="selected"'; ?>>Bi-focal</option>
    <option  value="glass" 			<?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='glass') 		  echo 'selected="selected"'; ?>>Glass</option>
    <option  value="all prog"       <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='all prog')       echo 'selected="selected"'; ?>>All Progressives</option>
    <option value="prog cl"         <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog cl') 	      echo 'selected="selected"'; ?>>Progressive Classic</option>
    <option value="prog ds"         <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog ds') 		  echo 'selected="selected"'; ?>>Progressive DS</option>
    <option value="prog ff"         <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='prog ff')   	  echo 'selected="selected"'; ?>>Progressive FF</option>
    <option value="sv"              <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='sv')             echo 'selected="selected"'; ?>>SV</option>
    <option disabled="disabled" value="">&nbsp;</option>
    <option disabled="disabled" value="">*LENS TYPE*</option>
    <option value="AO Compact"      <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='AO Compact')     echo 'selected="selected"'; ?>>AO Compact</option> 
    <option value="Compact Ultra HD"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Compact Ultra HD') echo 'selected="selected"'; ?>>Compact Ultra HD</option> 
    <option value="EZ"    			<?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='EZ')  		      echo 'selected="selected"'; ?>>EZ BY CZV</option> 
    <option value="FT28"  			<?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT28')		      echo 'selected="selected"'; ?>>FT28</option> 
    <option value="FT35"        	<?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT35') 		  echo 'selected="selected"'; ?>>FT35</option> 
    <option value="FT45"        	<?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='FT45') 		  echo 'selected="selected"'; ?>>FT45</option> 
    <option value="Innovative 1.0"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 1.0") echo 'selected="selected"'; ?>>Innovative 1.0</option> 
    <option value="Innovative 2.0"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 2.0") echo 'selected="selected"'; ?>>Innovative 2.0</option> 
    <option value="Innovative 3.0"  <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative 3.0") echo 'selected="selected"'; ?>>Innovative 3.0</option> 
    <option value="RD"              <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='RD') 			  echo 'selected="selected"'; ?>>RD</option> 
    <option value="SelectionRx"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='SelectionRx')    echo 'selected="selected"'; ?>>SelectionRx</option> 
    <option value="Single Vision"   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Single Vision')  echo 'selected="selected"'; ?>>Single Vision</option> 
    <option value="Sola Easy"       <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Sola Easy')      echo 'selected="selected"'; ?>>Sola Easy</option> 
    <option value="Trifocal 7x28"   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Trifocal 7x28')  echo 'selected="selected"'; ?>>Trifocal 7x28</option>
    <option value="Trifocal 8x35"   <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=='Trifocal 8x35')  echo 'selected="selected"'; ?>>Trifocal 8x35</option> 
    <option disabled="disabled" value="">&nbsp;</option>
    <option disabled="disabled" value="">*MANUFACTURER*</option>
    <option value="IOT"             <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="IOT")            echo 'selected="selected"'; ?>>IOT</option> 
    <option value="OPTOTECH"        <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="OPTOTECH")       echo 'selected="selected"'; ?>>OPTOTECH</option>  
    <option value="SOLA/ZEISS"      <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SOLA/ZEISS")     echo 'selected="selected"'; ?>>SOLA/ZEISS</option>   
</select>
				<?php
                }
				?></span>
                </td>
                
                   
                   
                      <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
				echo 'Prioritaire:';
				}else {
				echo 'Rush:';
				}
				?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  <select name="rush" class="formText" id="rush">
                    	<option value="no" <?php if ($_SESSION['PrescrData']['rush'] == 'no') echo ' selected';  ?> >No</option>
                      	<option value="yes" <?php if ($_SESSION['PrescrData']['rush'] == 'yes') echo ' selected';  ?>>Yes</option>                    
					</select></span>
                   </td>
                   
                 </tr>  
                 
                 
                 
                      <tr> 
                    <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
				echo 'Courbure de base';
				}else {
				echo 'Base Curve' ;
				}
				?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  
                  <select name="base_curve" class="formText" id="base_curve">
                    	<option <?php if ($_SESSION['PrescrData']['base_curve'] == '') echo ' selected';  ?>  value="" >Select Base Curve</option>
                      	<option <?php if ($_SESSION['PrescrData']['base_curve'] == 1) echo ' selected';  ?> value="1">1</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 2) echo ' selected';  ?> value="2">2</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 3) echo ' selected';  ?> value="3">3</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 4) echo ' selected';  ?> value="4">4</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 5) echo ' selected';  ?> value="5">5</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 6) echo ' selected';  ?> value="6">6</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 7) echo ' selected';  ?> value="7">7</option>
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 8) echo ' selected';  ?> value="8">8</option>                   
					</select></span>
                   </td></tr>
              </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#000099" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp; </td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" 
                     value="<?php	if ($_SESSION['PrescrData']['RE_PD']>0)
				 echo  $_SESSION['PrescrData']['RE_PD'];
				 ?>"
                     
                      size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_re_txt;?></td>
                   <td align="left" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" 
                     value="<?php	if ($_SESSION['PrescrData']['LE_PD']>0)
				 echo  $_SESSION['PrescrData']['LE_PD'];
				 ?>"
                      size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_le_txt;?></td>
                   <td align="center" class="formCellNosides"><?php echo $adm_nearpd_txt;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR"
                      value="<?php	if ($_SESSION['PrescrData']['RE_PD_NEAR']>0)
				 echo  $_SESSION['PrescrData']['RE_PD_NEAR'];
				 ?>"
                      
                       size="4" maxlength="4" />
                     <br />
                   <?php echo $adm_re_txt;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $adm_nearpd_txt;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" 
                     value="<?php	if ($_SESSION['PrescrData']['LE_PD_NEAR']>0)
				 echo  $_SESSION['PrescrData']['LE_PD_NEAR'];
				 ?>"
                      size="4" maxlength="4" />
                     <br />
                   <?php echo $adm_le_txt;?></td>
                   <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" 
                     value="<?php	if ($_SESSION['PrescrData']['RE_HEIGHT']>0)
				 echo  $_SESSION['PrescrData']['RE_HEIGHT'];
				 ?>"
                      size="4" maxlength="4" />
                     <br />
                   <?php echo $adm_re_txt;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                     <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" 
                     value="<?php	if ($_SESSION['PrescrData']['LE_HEIGHT']>0)
				 echo  $_SESSION['PrescrData']['LE_HEIGHT'];
				 ?>"
                      size="4" maxlength="4" />
                     <br />
                   <?php echo $adm_le_txt;?></td>
                   </tr>
               </table>
             </div>
            
            
          
          
          <div>
              <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                <tr>
                  <td colspan="6" bgcolor="#000099" class="tableHead"><?php echo $lbl_mywrldcoll_txt_pl;?>&nbsp;</td>
                  </tr>
                <tr>
                <td align="right" class="formCell"><?php echo $lbl_pt_txt_pl;?></td>
                  <td align="left" class="formCellNosides">&nbsp;  <input name="PT" type="text" class="formText" id="PT" value="<?php echo $_SESSION['PrescrData']['PT'];?>" size="2" maxlength="2" />
                    
                    <?php echo $lbl_pt1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $lbl_pa_txt_pl;?>&nbsp;</td>
                  <td align="left" class="formCellNosides"><input name="PA" type="text" class="formText" id="PA" value="<?php echo $_SESSION['PrescrData']['PA'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_pa1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $adm_vertex_txt;?></td>
                  <td align="left" class="formCellNosides"><input name="VERTEX" type="text" class="formText" id="VERTEX" value="<?php echo $_SESSION['PrescrData']['VERTEX'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_vertex1_pl;?>&nbsp;</td>
                  </tr>
              </table>
            </div>
          
          
          
           <input / type="hidden" name="WARRANTY" ID="WARRANTY" value="0">
              <?php /*?><div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				   <?php if ($mylang == 'lang_french'){
				echo 'Extra garantie';
				}else {
				echo 'Extra Warranty';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="WARRANTY" class="formText" id="WARRANTY">
                      <option value="0" selected="selected">
                       <?php if ($mylang == 'lang_french'){
						echo "Aucune";
						}else {
						echo "None";
						}
						?>
                      </option>
                      <option value="1">
					    <?php if ($mylang == 'lang_french'){
						echo "1 an (6$ extra)";
						}else {
						echo "1 year ($6 extra)";
						}
						?></option>
                     
                      <option value="2">
                       <?php if ($mylang == 'lang_french'){
						echo "2 ans (10$ extra)";
						}else {
						echo "2 years ($10 extra)";
						}
						?>
                      </option>
                       
                      <option value="gold">
                       <?php if ($mylang == 'lang_french'){
						echo "Garantie Or (20$ extra)";
						}else {
						echo "Gold Warranty ($20 extra)";
						}
						?>
                      </option> 
                      
                     </select>
                     </span>
                      <?php if ($mylang == 'lang_french'){
						echo "<font size=\"3\">Cliquez</font><a style=\"font-size:16px;\" target=\"_blank\" href=\"http://c.direct-lens.com/lensnet/pdf/gold_fr.pdf\"><b> ici </b></a><font size=\"3\">pour plus de renseignements sur le nouveau Programme OR!</font>";
						}else {
						echo "<font size=\"3\">Click</font><a style=\"font-size:16px;\" target=\"_blank\" href=\"http://c.direct-lens.com/lensnet/pdf/gold_en.pdf\"><b> here</b></a><font size=\"3\"> for more information about the New Gold Warranty Plan!</font>";
						}
						?>
                     
                     </td>
                 </tr> 
               </table>
             </div><?php */?> 
  
          
            
            
<div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 
                 <tr>
                 <td colspan="6" bgcolor="#000099" class="tableHead"><?php echo 'Special Base';?>&nbsp;</td>
                 </tr>
                 
  <tr>             
  <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="BASE8" class="formText" id="BASE8">
                         <option value="no" <?php  if ($_SESSION['PrescrData']['BASE8']=='non') echo 'selected'; ?>>No</option>
                         <option value="yes"  <?php  if ($_SESSION['PrescrData']['BASE8']=='yes') echo 'selected'; ?>>Base 8 (30$ extra)</option>
                     </select>
                     </span></td>
                 </tr>
              </table>
             </div>   
        
              
                   
       <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				   <?php if ($mylang == 'lang_french'){
				echo 'Frais d\'entré de donnés';
				}else {
				echo 'Data entry fee';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">   
                    <?php if ($mylang == 'lang_french'){
				echo '2$ Frais d\'entré de donnés';
				}else {
				echo '2$ Data entry fee';
				}
				?>
                 <input name="entry_fee"  id="entry_fee" type="checkbox" <?php if ($_SESSION['PrescrData']['entry_fee']=='yes') echo ' checked="checked"'  ?> value="yes" />  </span></td>
                 </tr>
               </table>
             </div> 
             
          
          
           <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="10" bgcolor="#000099" class="tableHead">SPECIAL THICKNESS&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCell">RE CT</td>
                   <td align="left" class="formCellNosides"><input name="RE_CT" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['RE_CT'];?>" id="RE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE CT</td>
                   <td align="left" class="formCellNosides"><input name="LE_CT" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['LE_CT'];?>" id="LE_CT" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
               
                   <td align="left" class="formCell">RE ET</td>
                   <td align="left" class="formCellNosides"><input name="RE_ET" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['RE_ET'];?>" id="RE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCell">LE ET</td>
                   <td align="left" class="formCellNosides"><input name="LE_ET" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['LE_ET'];?>" id="LE_ET" size="4" maxlength="6"></td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                 </tr>
               </table>
             </div> 
             
          
          
          
          
          

<div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#000099" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo 'Engraving';?> </td>
                   <td align="left" class="formCellNosides">&nbsp;
                     <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" value="<?php echo $_SESSION['PrescrData']['ENGRAVING'];?>" size="4" maxlength="8" /></td>
                   <td align="right" class="formCell"><?php echo $adm_tint_txt;?></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                       <option value="None" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "selected=\"selected\"";?>><?php echo $adm_none_txt;?></option>
                       <option value="Solid" <?php if ($_SESSION['PrescrData']['TINT']=="Solid") echo "selected=\"selected\"";?>><?php echo $lbl_tint2_pl;?></option>
                       <option value="Gradient" <?php if ($_SESSION['PrescrData']['TINT']=="Gradient") echo "selected=\"selected\"";?>><?php echo $adm_gradient_txt;?></option>
                   </select>
                   </span></td>
                   <td align="left" class="formCellNosides"><?php echo $adm_from_txt;?>
                     <input name="FROM_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="FROM_PERC" value="<?php echo $_SESSION['PrescrData']['FROM_PERC'];?>" size="4" maxlength="4" />
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $adm_to_txt;?>
                     <input name="TO_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="TO_PERC" value="<?php echo $_SESSION['PrescrData']['TO_PERC'];?>" size="4" maxlength="4">
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $adm_color_txt;?></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="TINT_COLOR">
                       <option value="Brown" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Brown") echo "selected=\"selected\"";?>><?php echo $adm_brwn_txt;?></option>
                       <option value="Gray" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Gray") echo "selected=\"selected\"";?>><?php echo $adm_gray_txt;?></option>
                       </select>
                     </span></td>
                   </tr>
              </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $adm_eyec_txt;?></td>
                   <td align="left" class="formCellNosides"> <?php echo $lbl_a_txt_pl;?>
                     &nbsp;
                     <input name="FRAME_A" type="text" class="formText" id="FRAME_A" value="<?php echo $_SESSION['PrescrData']['FRAME_A'];?>" size="4" maxlength="4" />		          &nbsp;
                     
                     <?php echo $lbl_b_txt;?>
                     <input name="FRAME_B" type="text" class="formText" id="FRAME_B" value="<?php echo $_SESSION['PrescrData']['FRAME_B'];?>" size="4" maxlength="4" />
                     &nbsp;&nbsp;<?php echo $adm_ed_txt;?>
                     <input name="FRAME_ED" type="text" class="formText" id="FRAME_ED" value="<?php echo $_SESSION['PrescrData']['FRAME_ED'];?>" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $adm_dbl_txt;?>
                     <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL" value="<?php echo $_SESSION['PrescrData']['FRAME_DBL'];?>" size="4" maxlength="4" />
                     &nbsp;<?php echo $adm_temple_txt;?>
                     <input name="TEMPLE" type="text" class="formText" id="TEMPLE" value="<?php echo $_SESSION['PrescrData']['TEMPLE'];?>" size="4" />
                     </td>
                   <td align="right" class="formCell"><?php echo $adm_type_txt;?></td>
                   <td align="left" class="formCell">
                     <select name="FRAME_TYPE" class="formCellNosides" id="FRAME_TYPE">
                       <option value="Nylon Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Nylon Groove") echo "selected=\"selected\"";?>><?php echo $adm_nylgrve_txt;?></option>
                       <option value="Metal Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal Groove") echo "selected=\"selected\"";?>><?php echo $adm_mtlgrve_txt;?></option>
                       <option value="Plastic"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Plastic")		    echo "selected=\"selected\"";?>><?php echo $adm_plas_txt;?></option>
                       <option value="Metal"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal") 				echo "selected=\"selected\"";?>><?php echo $adm_mtl_txt;?></option>
                       <option value="Edge Polish"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Edge Polish") 	echo "selected=\"selected\"";?>><?php echo $adm_edgepol_txt;?></option>
                       <option value="Drill and Notch"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Drill and Notch") echo "selected=\"selected\"";?>><?php echo $adm_drillnotc_txt;?></option>
                       </select></td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $adm_jobtype_txt;?>
                     <select name="JOB_TYPE" class="formText" id="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Uncut" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo "selected=\"selected\"";?>><?php echo $adm_uncut_txt;?></option>
                       <option value="Edge and Mount" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Edge and Mount") echo "selected=\"selected\"";?>><?php echo $adm_edgemount_txt;?></option>
                       <option value="remote edging">
						<?php if ($mylang == 'lang_french'){
						echo 'Taillé Non monté';
						}else {
						echo 'Remote Edging';
						}
						?></option>
                     </select>
                     &nbsp;&nbsp;&nbsp; <?php echo $adm_frame_txt;?>
                     <select name="ORDER_TYPE" class="formText" id="ORDER_TYPE" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo 'disabled="disabled"';?>>
                     <option value="To Follow" <?php if ($_SESSION['PrescrData']['ORDER_TYPE']=="To Follow") echo "selected=\"selected\"";?> ><?php echo $adm_tofol_txt;?></option>
                       <option value="Provide" <?php if ($_SESSION['PrescrData']['ORDER_TYPE']=="Provide") echo "selected=\"selected\"";?>><?php echo $adm_prov_txt;?></option>
                     
                     </select>
                   &nbsp;&nbsp;&nbsp;&nbsp;</td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $adm_supplier_txt;?>
                     <input name="SUPPLIER" type="text"  <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo 'disabled="disabled"';?> class="formText" id="SUPPLIER" value="<?php echo $_SESSION['PrescrData']['SUPPLIER'];?>" size="12"/>
                     &nbsp;&nbsp;<?php echo $adm_shpmod_txt;?>
                     <input name="FRAME_MODEL" type="text" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo 'disabled="disabled"';?> class="formText" id="FRAME_MODEL" value="<?php echo $_SESSION['PrescrData']['FRAME_MODEL'];?>" size="12"/>
                     </span> &nbsp;&nbsp;&nbsp;
                     <?php echo $adm_framemod_txt;?>
                     <input name="TEMPLE_MODEL" type="text" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo 'disabled="disabled"';?> class="formText" id="TEMPLE_MODEL" value="<?php echo $_SESSION['PrescrData']['TEMPLE_MODEL'];?>" size="12"/>&nbsp;&nbsp;&nbsp; <?php echo $adm_color_txt;?>
                   <input name="COLOR" type="text"<?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo 'disabled="disabled"';?> class="formText" id="COLOR" value="<?php echo $_SESSION['PrescrData']['COLOR'];?>" size="12" /></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $adm_specialinstructions_txt;?> </span></td>
                   <td width="502" valign="top"  class="tableSubHead">
                   
                    <?php
				 $querySpecialInst = "SELECT allow_special_instruction from accounts  WHERE user_id = '". $_SESSION["sessionUser_Id"] . "'";
				 $resultSpecialInst=mysqli_query($con,$querySpecialInst)	or die ("Could not select items");
  				 $DataSpecialInst=mysqli_fetch_array($resultSpecialInst,MYSQLI_ASSOC);
							 
				 if ($DataSpecialInst[allow_special_instruction]=='no') 
				 {?>
                    <input type="hidden" name="SPECIAL_INSTRUCTIONS" value="" /><?php
				        if ($mylang == 'lang_french') {
                        echo 'Toutes les demandes spéciales doivent passer par les produits Direct-Lens ou téléphonez à votre service à la clientèle si besoin.';
                        }else{
                        echo 'All special requests need to go through Direct-Lens products or call your customers services if needed.';
                        }?>
		<?php    }else{ ?>
				 <input name="SPECIAL_INSTRUCTIONS" type="text" size="75" class="formText" value="<?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?>" id="SPECIAL_INSTRUCTIONS">
            <?php } ?>             
                </td>
                   </tr>
               </table>
             </div>
             
               <input name="INTERNAL_NOTE" type="hidden"  id="INTERNAL_NOTE" value="">
			 
			 
<div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="Submit" type="submit" value="<?php echo $btn_submit_txt;?>" />
		    </div></div>
		  </form>
          
 
<form method="post"  enctype="multipart/form-data" action="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/" name="formShape" id="formShape" target="_blank">
            
            <?php          	
//Code pour uploader sur S3
if (!class_exists('S3')) require_once '../s3/S3.php';
	
// AWS access info
// TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
if (!defined('awsAccessKey')) define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));
if (!defined('awsSecretKey')) define('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));

// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");

// Pointless without your keys!
if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
  // TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
	"define('awsAccessKey', constant('AWS_S3_USER_ACCESS_KEY'));\ndefine('awsSecretKey', constant('AWS_S3_USER_SECRET_KEY'));\n\n");

S3::setAuth(awsAccessKey, awsSecretKey);



//Dans quel Bucket Uploader ces fichiers
$bucket = 'direct-lens-public';
$path = 'Shapes/'; // Dans quel dossier

$lifetime = 3600; // Period for which the parameters are valid
$maxFileSize = (1024 * 1024 * 50); // 50 MB



$metaHeaders = array('uid' => 123);
$requestHeaders = array(
    'Content-Type'        => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename=${filename}'
);

$sucess_action_redirect= constant('DIRECT_LENS_URL').'/lensnet/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement

$params = S3::getHttpUploadPostParams(
    $bucket,
    $path,
    S3::ACL_PUBLIC_READ,
    $lifetime,
    $maxFileSize,
    $sucess_action_redirect, // Or a URL to redirect to on success
    $metaHeaders,
    $requestHeaders,
    false // False since we're not using flash
);

foreach ($params as $p => $v)
	echo "        <input type=\"hidden\" name=\"{$p}\" value=\"{$v}\" />\n";
?>

   
             <div id="spherechoice" >
			<table width="770" align="center">
            <tr bgcolor="#000099">
                   <td width="134" align="center" valign="top"  class="tableHead">
				   <?php if ($mylang == 'lang_french'){
					echo 'ENVOYER UNE TRACE';
					}else {
					echo 'UPLOAD A SHAPE';
					}
					?></td>
              <tr >
                <td colspan="7" align="right" valign="top" bgcolor="#FFFFFF"class="formCell">

               <div id="uploaderdiv" style="width:400px; margin:0 auto; text-align: center;">
               <?php 
			   $DisableUploadButton = 'no';
			   if ($_SESSION['PrescrData']['myupload'] <> '') 
			   {
			   echo 'Shape Uploaded: ' . $_SESSION['PrescrData']['myupload'];
			   $DisableUploadButton = 'yes';
			   }
			    ?>
                 <p>
                   <input type="file" onclick="btnupload.disabled=false;btnupload.value='Upload'"  name="file" id="file" size="40">&nbsp;
                   <input type="submit" value="Upload" id="btnupload"   onclick="this.disabled=true;this.value='Uploaded';this.form.submit();" <?php if ($DisableUploadButton =='yes') echo ' disabled ';  ?>  />
                  </p>
				</div>
                </td></tr>               
              </table>
              

               </div>
            </form>         
          
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>