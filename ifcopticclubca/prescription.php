<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include "config.inc.php";
global $drawme;
require_once "../upload/phpuploader/include_phpuploader.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	require('../Connections/sec_connect.inc.php');

$queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
$resultLab=mysql_query($queryLab)	or die ("Could not select items");
$DataLab=mysql_fetch_array($resultLab);
$LabNum=$DataLab[main_lab];

mysql_query("SET CHARACTER SET UTF8");
 
 if ($_GET['prod']!=""){
	$frameQuery = "SELECT * FROM ifc_frames_french WHERE ifc_frames_id='$_GET[prod]'";
	$frameResult=mysql_query($frameQuery)	or die ("ERROR:");
	$frameItem=mysql_fetch_array($frameResult);
	$frameItem[prod_tn]="prod_images/".$frameItem[image];
	$frameItem[prod_tn]="prod_images/".$frameItem['code']."/images/". $frameItem['code']."_19.jpg";
 }
 
$_SESSION['REFERRER']="prescription.php";// CATCH FOR RETRY


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>

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

   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form.js.inc.php";?>

</head>

<body onload="setEnabled(this)">
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateVerresSeulement(this);">
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      <div>
              
           
                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                

                  <tr >
                    <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_client;?></td>
                    <td bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret client'; else echo 'Customer Tray';?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret lab'; else echo 'Lab Tray';?>&nbsp;</td>
                    <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'ID Vendeur'; else echo 'Salesperson ID';?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" size="25" value="<?php echo $_SESSION['PrescrData']['LAST_NAME'];?>" /></td>
                    <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" size="25" value="<?php echo $_SESSION['PrescrData']['FIRST_NAME'];?>" /></td>
                    
                     <td class="formCellNosides"><input name="TRAY_NUM" value="<?php echo $_SESSION['PrescrData']['TRAY_NUM'];?>" type="text" class="formText" id="TRAY_NUM" size="10" /></td>
                    
                    <td class="formCellNosides"><input name="PATIENT_REF_NUM" value="<?php echo $_SESSION['PrescrData']['PATIENT_REF_NUM'];?>" type="text" class="formText" id="PATIENT_REF_NUM" size="10" /></td>
                    <td class="formCellNosides"><select name="SALESPERSON_ID" class="formText" id="SALESPERSON_ID">
                      <option value="" selected="selected"><?php echo $lbl_slsperson1;?></option>
                      <?php
						$user_id=$_SESSION["sessionUser_Id"];
  $query="select sales_id,first_name,last_name from salespeople WHERE acct_user_id='$user_id' AND removed!='Yes' ORDER by last_name,first_name"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[sales_id]\">";$name=stripslashes($listItem[first_name])." ".stripslashes($listItem[last_name]);echo "$name</option>";}?>
                      </select></td>
                    </tr>
                </table>
              </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                 <tr>
                    <td colspan="7" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_prescription_txt_pl;?>
                    <input name="EYE" type="radio" onclick="ActivateAll_fields(this.form);" value="Both" checked="checked"  <?php if ($_SESSION['PrescrData']['EYE']=="Both") echo "checked=\"checked\"";?>  /> 
                    <?php echo $lbl_prescription1_pl;?>&nbsp;
                    <input name="EYE" type="radio" onclick="DesactivateLE_fields(this.form);" value="R.E." <?php if ($_SESSION['PrescrData']['EYE']=="R.E.") echo "checked=\"checked\"";?> />
                    <?php echo $lbl_prescription2_pl;?> 
                    <input name="EYE" type="radio" onclick="DesactivateRE_fields(this.form);"  value="L.E." <?php if ($_SESSION['PrescrData']['EYE']=="L.E.") echo "checked=\"checked\"";?> />
                    <?php echo $lbl_prescription3_pl;?> 
                 	</td>
                 </tr>
                 <tr>
                   <td colspan="2" valign="middle"  class="formCell">&nbsp;</td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_sphere_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_cylinder_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_axis_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_addition_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCell"><?php echo $lbl_prism_txt_pl;?></td>
                   </tr>
                 <tr >
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="OD = OG" /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php echo $lbl_re_txt_pl;?></td>
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
				  <option value="-0"<?php if (($_SESSION['PrescrData']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
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
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['RE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                   <td align="center" valign="top" class="formCellNosides">
                   <select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
              
                  <option value="-0" <?php if (($_SESSION['PrescrData']['RE_CYL_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_CYL_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
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
                    <option value=".00" <?php if (($_SESSION['PrescrData']['RE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)"  value="<?php
				if ($_SESSION['PrescrData']['RE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['RE_AXIS'];
				 ?>" />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides">
                   <select name="RE_ADD" class="formText" id="RE_ADD">
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
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['RE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['RE_ADD'])<2)) echo "selected=\"selected\"";?>>+0.00</option>
                    </select></td>
                    
                     <td align="right" valign="top"class="formCell">
                     <input name="RE_PR_IO" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='In') echo 'checked="checked"';?> type="radio" value="In" />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='Out') echo 'checked="checked"';?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="None"  <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='None') echo 'checked="checked"';?> <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='') echo 'checked="checked"';?> />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" 
					 value="<?php if ($_SESSION['PrescrData']['RE_PR_AX'] > 0) echo  $_SESSION['PrescrData']['RE_PR_AX']; ?>" /><br />
                     <input name="RE_PR_UD" type="radio" value="Up" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Up') echo 'checked="checked"';?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Down') echo 'checked="checked"';?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='None') echo 'checked="checked"';?> <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='') echo 'checked="checked"';?>  />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4"  value="<?php	if ($_SESSION['PrescrData']['RE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['RE_PR_AX2'];
				 ?>"/>
                     </td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php echo $lbl_le_txt_pl;?></td>
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
				  <option value="-0"<?php if (($_SESSION['PrescrData']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
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
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['LE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">   
                	
                  <option value="-0" <?php if (($_SESSION['PrescrData']['LE_CYL_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_CYL_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
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
                    <option value=".00" <?php if (($_SESSION['PrescrData']['LE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)"  value="<?php
				if ($_SESSION['PrescrData']['RE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['RE_AXIS'];
				 ?>" />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
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
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['LE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['LE_ADD'])<2)) echo "selected=\"selected\"";?>>+0.00</option>
                </select></td>
                   <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='In') echo 'checked="checked"';?> />
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='Out') echo 'checked="checked"';?> />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='None') echo 'checked="checked"';?> <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='') echo 'checked="checked"';?> />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX'];
				 ?>" /><br /><input name="LE_PR_UD" type="radio" value="Up"  <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Up') echo 'checked="checked"';?> />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Down') echo 'checked="checked"';?> />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='') echo 'checked="checked"';?> <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='None') echo 'checked="checked"';?>/>
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="4" maxlength="4"  
                     value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX2'] > 0) echo $_SESSION['PrescrData']['LE_PR_AX2']; ?>" /></td>
                   </tr>
               </table>
             </div>
             
             
           
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.50") echo 'selected="selected"';  ?> value="1.50">1.50</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.53") echo 'selected="selected"';  ?> value="1.53">1.53</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.56") echo 'selected="selected"';  ?> value="1.56">1.56</option>
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.58") echo 'selected="selected"';  ?> value="1.58">1.58 Hivex</option>     
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.59") echo 'selected="selected"';  ?> value="1.59">1.59</option>   
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.60") echo 'selected="selected"';  ?> value="1.60">1.60</option>  
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.67") echo 'selected="selected"';  ?> value="1.67">1.67</option>   
                       <option <?php if ($_SESSION['PrescrData']['INDEX']=="1.74") echo 'selected="selected"';  ?> value="1.74">1.74</option>                 
                 </select>
                     </span></td>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="selected">
                        <?php if ($mylang == 'lang_french'){?>
                            Tous
                        <?php }else {?>
                            All
                        <?php }?>
                		</option>
                       <option value="HC" <?php if ($_SESSION['PrescrData']['COATING']=="HC") echo "selected=\"selected\"";?>>HC</option>
                       <option value="AR" 	     <?php if ($_SESSION['PrescrData']['COATING']=="AR") echo "selected=\"selected\"";?>>AR</option>
                       <option value="AR Backside" 	     <?php if ($_SESSION['PrescrData']['COATING']=="AR Backside") echo "selected=\"selected\"";?>>AR Backside</option>
                       <option value="AR+ETC"   <?php if ($_SESSION['PrescrData']['COATING']=="AR+ETC") echo "selected=\"selected\"";?>>AR+ETC</option>
                       <option value="XLR"       <?php if ($_SESSION['PrescrData']['COATING']=="XLR")    echo "selected=\"selected\"";?>>XLR</option>
                       <?php if ($_SESSION["CompteEntrepot"] =='yes'){  ?>
                       <option value="HD AR" <?php if ($_SESSION['PrescrData']['COATING']=="HD AR")   						   echo "selected=\"selected\"";?>>HD AR</option>
                       <option value="iBlu" <?php if ($_SESSION['PrescrData']['COATING']=="iBlu")   		echo "selected=\"selected\"";?>>iBlu</option>
                       <option value="StressFree" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree")   		echo "selected=\"selected\"";?>>StressFree</option>
                       <option value="StressFree 32" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree 32")   		echo "selected=\"selected\"";?>>StressFree 32</option>
                       <option value="StressFree Noflex" <?php if ($_SESSION['PrescrData']['COATING']=="StressFree Noflex")    echo "selected=\"selected\"";?>>StressFre Noflex</option>
                       <?php } ?>
                     </select>
                     </span></td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
                        echo 'Transitions';
                        }else {
                        echo 'Transitions';
                        }
                        ?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                    <select name="PHOTO" class="formText" id="PHOTO">
						<?php if ($mylang == 'lang_french'){ ?>
						   <option value="none" selected="selected">Aucun</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Action B") echo 'selected="selected"'; ?> value="Action B">Action B</option>           
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Blue") echo 'selected="selected"'; ?> value="Blue">Bleu</option> 
						   <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Brown") echo 'selected="selected"'; ?> value="Brown">Brun</option>  
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Day Nite") echo 'selected="selected"'; ?> value="Day Nite">Day Nite</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Drivewear") echo 'selected="selected"'; ?> value="Drivewear">Drivewear</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Extra Active Grey") echo 'selected="selected"'; ?> value="Extra Active Grey">Extra Active Grey</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Golf Green") echo 'selected="selected"'; ?> value="Golf Green">Golf Green</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grey") echo 'selected="selected"'; ?> value="Grey">Gris</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grafite") echo 'selected="selected"'; ?> value="Grafite">Grafite</option>    
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Pink") echo 'selected="selected"'; ?> value="Pink">Rose</option> 
                        <?php }else{ ?>
						   <option value="none" selected="selected">None</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Action B") echo 'selected="selected"'; ?> value="Action B">Action B</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Blue") echo 'selected="selected"'; ?> value="Blue">Blue</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Brown") echo 'selected="selected"'; ?> value="Brown">Brown</option>        
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Day Nite") echo 'selected="selected"'; ?> value="Day Nite">Day Nite</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Drivewear") echo 'selected="selected"'; ?> value="Drivewear">Drivewear</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Extra Active Grey") echo 'selected="selected"'; ?> value="Extra Active Grey">Extra Active Grey</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Golf Green") echo 'selected="selected"'; ?> value="Golf Green">Golf Green</option>
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grey")  echo 'selected="selected"'; ?> value="Grey">Grey</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Grafite") echo 'selected="selected"'; ?> value="Grafite">Grafite</option> 
                           <option <?php if ($_SESSION['PrescrData']['PHOTO']=="Pink") echo 'selected="selected"'; ?> value="Pink">Pink</option>
                        <?php } ?>             
                 	</select></span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px"><select name="POLAR" class="formText" id="POLAR">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['POLAR']=="None") echo "selected=\"selected\"";?>">Aucun</option>
                       <?php
  $query="select polar from ifc_ca_exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 
 if ($listItem[polar]!="None"){
  
  echo "<option value=\"$listItem[polar]\"";
  
 if ($_SESSION['PrescrData']['POLAR']=="$listItem[polar]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}}?>
                   </select></span></td>
                   </tr>
                   
                
                   
                   
                  <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				<?php if ($mylang == 'lang_french'){
				echo 'Catégorie de verres:';
				}else {
				echo 'Lens category';
				}
				?></span></td>
                   <td align="left" colspan="1" class="formCellNosides"><span style="margin:11px">                 
<select name="lens_category">
<option  value="tous" <?php if ($_SESSION['lens_category']=="tous") echo 'selected="selected"'; ?>>Tous</option> 
<option  value="bifocal-entrepot"  <?php if ($_SESSION['PrescrData']['lens_category']=="bifocal-entrepot") echo 'selected="selected"'; ?>>Bifocaux</option>
<option  value="iaction-entrepot"  <?php if ($_SESSION['PrescrData']['lens_category']=="iaction-entrepot") echo 'selected="selected"'; ?>>Indivudialisé Iaction</option>
<option  value="ifree-entrepot"     <?php if ($_SESSION['PrescrData']['lens_category']=="ifree-entrepot")  echo 'selected="selected"'; ?>>Individualisé Ifree</option>
<option  value="ioffice-entrepot"   <?php if ($_SESSION['PrescrData']['lens_category']=="ioffice-entrepot")   echo 'selected="selected"'; ?>>iOffice</option>
<option  value="irelax-entrepot"   <?php if ($_SESSION['PrescrData']['lens_category']=="irelax-entrepot")     echo 'selected="selected"'; ?>>iRelax</option>
<option  value="crystal-entrepot"    <?php if ($_SESSION['PrescrData']['lens_category']=="crystal-entrepot")  echo 'selected="selected"'; ?>>Optimisé</option>
<option  value="progressif-entrepot" <?php if ($_SESSION['PrescrData']['lens_category']=="progressif-entrepot")echo 'selected="selected"'; ?>>Progressifs</option>
<option  value="sv" <?php if ($$_SESSION['PrescrData']['lens_category']=="sv") echo 'selected="selected"'; ?>>SV</option>
</select> </span>
                   
                   
                    <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?> 
                   <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
					echo 'Courbure de base';
					}else {
					echo 'Base Curve';
					}
					?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  
                  <select name="BASE_CURVE" class="formText" id="BASE_CURVE">
                    	<option selected="selected" value="" >
					<?php if ($mylang == 'lang_french'){
					echo 'Sélectionner';
					}else {
					echo 'Select Base Curve';
					}
					?></option>
                      	<option value="1" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="1") echo 'selected="selected"'; ?>>1</option>
                        <option value="2" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="2") echo 'selected="selected"'; ?>>2</option>
                        <option value="3" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="3") echo 'selected="selected"'; ?>>3</option>
                        <option value="4" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="4") echo 'selected="selected"'; ?>>4</option>
                        <option value="5" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="5") echo 'selected="selected"'; ?>>5</option>
                        <option value="6" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="6") echo 'selected="selected"'; ?>>6</option>
                        <option value="7" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="7") echo 'selected="selected"'; ?>>7</option>
                        <option value="8" <?php if ($_SESSION['PrescrData']['BASE_CURVE']=="8") echo 'selected="selected"'; ?>>8</option>                   
					</select></span>
                   </td>
                   <?php }else{ ?> 
                   <input type="hidden" name="base_curve" id="base_curve" value="" />
                   <td align="right" class="formCell"><span class="tableSubHead">&nbsp;</span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px">
                   &nbsp; </span></td>
                    <?php }//End IF compte = entrepot ?> 
                    </tr>
                    
                  <tr>
                     <td align="right" class="formCell">
                    <span class="tableSubHead">
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'Corridor';
				      	 }else{
					     echo 'Corridor';
					     }?></span>&nbsp;</td>
                  
                   <td align="left" class="formCellNosides">
 <select  name="CORRIDOR" class="formText" id="CORRIDOR">
     <option value="none" 	  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="None")       echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Aucun';  else echo 'None';?></option>
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Digital Progressive" disabled="disabled" >Digital Progressive Optotech</option> 
     <option value="DProg_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_9")  echo "selected=\"selected\"";?>>9</option>  
     <option value="DProg_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="DProg_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_13") echo "selected=\"selected\"";?>>13</option>     
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Digital Progressive" disabled="disabled" >Digital Progressive IOT</option> 
     <option value="DProgI_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_9")  echo "selected=\"selected\"";?>>9</option>  
     <option value="DProgI_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="DProgI_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DProg_13") echo "selected=\"selected\"";?>>13</option>     
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Progressive HD IOT" disabled="disabled" >Progressive HD IOT</option> 
     <option value="HdIOT_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="HdIOT_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="HdIOT_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="HdIOT_13") echo "selected=\"selected\"";?>>13</option>  
     <option value="HdIOT_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="HdIOT_15") echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
    
     <option value="Individualisé iFree" disabled="disabled" >Individualisé iFree</option>  
     <option value="iFree_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iFree_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="iFree_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iFree_13") echo "selected=\"selected\"";?>>13</option>  
     <option value="iFree_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iFree_15") echo "selected=\"selected\"";?>>15</option>       
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Individualisé iAction" disabled="disabled" >Individualisé iAction</option> 
     <option value="iAction_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iAction_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="iAction_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iAction_13") echo "selected=\"selected\"";?>>13</option>  
     <option value="iAction_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="iAction_15") echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 

     <option value="Ind. Platine 4d" disabled="disabled" >Ind. Platine 4d</option> 
     <option value="Platine4d_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="Platine4d_9") echo "selected=\"selected\"";?>>9</option>  
     <option value="Platine4d_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="Platine4d_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="Platine4d_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="Platine4d_13") echo "selected=\"selected\"";?>>13</option>       
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Innovative 1.53 NXT" disabled="disabled" >Innovative 1.53 NXT</option> 
     <option value="NXT_5" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="NXT_5") echo "selected=\"selected\"";?>>5</option>  
     <option value="NXT_7" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="NXT_7") echo "selected=\"selected\"";?>>7</option>  
     <option value="NXT_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="NXT_9") echo "selected=\"selected\"";?>>9</option> 
     <option value="NXT_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="NXT_11") echo "selected=\"selected\"";?>>11</option> 
     <option value="NXT_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="NXT_13") echo "selected=\"selected\"";?>>13</option>           
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Progressif Classique" disabled="disabled" >Progressif Classique</option> 
     <option value="ProgClassic_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProgClassic_9")  echo "selected=\"selected\"";?>>9</option>  
     <option value="ProgClassic_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProgClassic_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="ProgClassic_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProgClassic_13") echo "selected=\"selected\"";?>>13</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
    

       
     <option value="Alpha 4D" disabled="disabled" >Alpha 4D</option> 

     <option value="AlphaH_9"   <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaH_9")  echo "selected=\"selected\"";?>>9</option>  
     <option value="AlphaH_11"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaH_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="AlphaH_13"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaH_13") echo "selected=\"selected\"";?>>13</option> 
     <option value="AlphaH_15"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaH_15") echo "selected=\"selected\"";?>>15</option>    
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Alpha Lecture/Quotidien/Extérieur" disabled="disabled" >Alpha Lecture/Quotidien/Extérieur</option> 
     <option value="AlphaHD_9"   <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaHD_9")  echo "selected=\"selected\"";?>>9</option>  
     <option value="AlphaHD_11"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaHD_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="AlphaHD_13"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaHD_13") echo "selected=\"selected\"";?>>13</option>  
     <option value="AlphaHD_15"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaHD_15") echo "selected=\"selected\"";?>>15</option>      
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     
     <option value="Alpha Premier Porteur" disabled="disabled" >Alpha Premier Porteur/Beginners</option>   
     <option value="AlphaPP_11"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaPP_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="AlphaPP_13"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaPP_13") echo "selected=\"selected\"";?>>13</option>  
     <option value="AlphaPP_15"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaPP_15") echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Alpha Ultra Short" disabled="disabled" >Alpha Ultra Court/Ultra Short</option>   
     <option value="AlphaUS_5"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaUS_5") echo "selected=\"selected\"";?>>5</option>   
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Alpha Conduire Auto" disabled="disabled" >Alpha Conduite Auto/Auto Drive</option>   
     <option value="AlphaAUTO_11"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="AlphaAUTO_11") echo "selected=\"selected\"";?>>11</option>   
     <option value="" disabled="disabled" >&nbsp;</option> 
        
     <option value="" disabled="disabled" >Camber (Premier Porteur/Beginners)</option> 
     <option value="CamberBeginners_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberBeginners_9")    echo "selected=\"selected\"";?>>9</option> 
	 <option value="CamberBeginners_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberBeginners_11")   echo "selected=\"selected\"";?>>11</option> 
 	 <option value="CamberBeginners_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberBeginners_13")   echo "selected=\"selected\"";?>>13</option> 
 	 <option value="CamberBeginners_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberBeginners_15")   echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
     <option value="" disabled="disabled" >Camber (Quotidien/Daily)</option> 
	 <option value="CamberDaily_7"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberDaily_7")   echo "selected=\"selected\"";?>>7</option> 
	 <option value="CamberDaily_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberDaily_9")   echo "selected=\"selected\"";?>>9</option>  
 	 <option value="CamberDaily_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberDaily_11")  echo "selected=\"selected\"";?>>11</option> 
 	 <option value="CamberDaily_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberDaily_13")  echo "selected=\"selected\"";?>>13</option> 
     <option value="CamberDaily_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberDaily_15")  echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option>     
     <option value="" disabled="disabled" >Camber (Extérieur/Outdoor)</option> 
	 <option value="CamberOutdoor_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberOutdoor_9")   echo "selected=\"selected\"";?>>9</option> 
     <option value="CamberOutdoor_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberOutdoor_11")  echo "selected=\"selected\"";?>>11</option> 
     <option value="CamberOutdoor_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberOutdoor_13")  echo "selected=\"selected\"";?>>13</option> 
     <option value="CamberOutdoor_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberOutdoor_15")  echo "selected=\"selected\"";?>>15</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
     <option value="" disabled="disabled" >Camber (Intérieur/Indoor)</option> 
	 <option value="CamberIndoor_7"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberIndoor_7")   echo "selected=\"selected\"";?>>7</option> 
	 <option value="CamberIndoor_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberIndoor_9")   echo "selected=\"selected\"";?>>9</option> 
     <option value="CamberIndoor_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberIndoor_11")  echo "selected=\"selected\"";?>>11</option> 
     <option value="CamberIndoor_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberIndoor_13")  echo "selected=\"selected\"";?>>13</option> 
     <option value="CamberIndoor_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="CamberIndoor_15")  echo "selected=\"selected\"";?>>15</option>    
     <option value="" disabled="disabled" >&nbsp;</option>
     
     
     <option value="Promo Duo Digital"  disabled="disabled" >Promo Duo Digital</option> 
     <option value="DuoDigital_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoDigital_9") echo "selected=\"selected\"";?>>9</option>  
     <option value="DuoDigital_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoDigital_11") echo "selected=\"selected\"";?>>11</option>  
     <option value="DuoDigital_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoDigital_13") echo "selected=\"selected\"";?>>13</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
     
     <option value="Promo Prog HD"      disabled="disabled" >Promo Prog HD/Promo Duo HD</option> 
     <option value="DuoProgHD_5" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoProgHD_5") echo "selected=\"selected\"";?>>5</option>  
     <option value="DuoProgHD_7" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoProgHD_7") echo "selected=\"selected\"";?>>7</option>  
     <option value="DuoProgHD_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoProgHD_9") echo "selected=\"selected\"";?>>9</option> 
     <option value="DuoProgHD_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoProgHD_11") echo "selected=\"selected\"";?>>11</option> 
     <option value="DuoProgHD_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="DuoProgHD_13") echo "selected=\"selected\"";?>>13</option> 
     <option value="" disabled="disabled" >&nbsp;</option> 
     <option value="Promo Prog Ind."    disabled="disabled" >Promo Prog Ind./Promo Duo Ind.</option> 
     <option value="ProdInd_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProdInd_9")   echo "selected=\"selected\"";?>>9</option> 
     <option value="ProdInd_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProdInd_11") echo "selected=\"selected\"";?>>11</option> 
     <option value="ProdInd_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProdInd_13") echo "selected=\"selected\"";?>>13</option> 
     <option value="ProdInd_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProdInd_15") echo "selected=\"selected\"";?>>15</option> 
      <option value="" disabled="disabled" >&nbsp;</option> 
      
      
<option value="Promo Ind. Alpha 4D (HD AR DISPO)"    disabled="disabled" >Promo Ind. Alpha 4D (HD AR DISPO)</option> 

<option value="ProDuoAlpha_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlpha_9") echo "selected=\"selected\"";?>>9</option> 
<option value="ProDuoAlpha_11"<?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlpha_11") echo "selected=\"selected\"";?>>11</option><option value="ProDuoAlpha_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlpha_13") echo "selected=\"selected\"";?>>13</option> 
<option value="ProDuoAlpha_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlpha_15") echo "selected=\"selected\"";?>>15</option> 



      <option value="" disabled="disabled" >&nbsp;</option> 
      
      
<option value="Promo Duo Alpha HD (HD AR DISPO)"    disabled="disabled" >Promo Duo Alpha HD (HD AR DISPO)</option> 
<option value="ProDuoAlphaHD_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlphaHD_9") echo "selected=\"selected\"";?>>9</option> 
<option value="ProDuoAlphaHD_11"<?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlphaHD_11") echo "selected=\"selected\"";?>>11</option>
<option value="ProDuoAlphaHD_13" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlphaHD_13") echo "selected=\"selected\"";?>>13</option> 
<option value="ProDuoAlphaHD_15" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoAlphaHD_15") echo "selected=\"selected\"";?>>15</option> 
    <option value="" disabled="disabled" >&nbsp;</option>     
  
<option value="Promo Duo Internet"    disabled="disabled" >Promo Duo Internet</option> 
<option value="ProDuoInternet_9" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoInternet_9")   echo "selected=\"selected\"";?>>9</option>
<option value="ProDuoInternet_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoInternet_11") echo "selected=\"selected\"";?>>11</option> 
<option value="ProDuoInternet_13"<?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProDuoInternet_13") echo "selected=\"selected\"";?>>13</option>
  
<option value="" disabled="disabled" >&nbsp;</option>     
<option value="Promo Internet / MAS"    disabled="disabled" >Promo Internet/MAS</option> 
<option value="ProMAS_9"  <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProMAS_9")  echo "selected=\"selected\"";?>>9</option>
<option value="ProMAS_11" <?php if ($_SESSION['PrescrData']['CORRIDOR']=="ProMAS_11") echo "selected=\"selected\"";?>>11</option> 

  
 </select>
 
                   </td>
                     <td align="left" class="formCellNosides">&nbsp;</td>
                     <td align="left" class="formCellNosides">&nbsp;</td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                 </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_tint_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                       <select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                       <option value="None" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "selected=\"selected\"";?>><?php echo $adm_none_txt;?></option>
                       <option value="Solid" <?php if ($_SESSION['PrescrData']['TINT']=="Solid") echo "selected=\"selected\"";?>><?php echo $lbl_tint2_pl;?></option>
                       <option value="Gradient" <?php if ($_SESSION['PrescrData']['TINT']=="Gradient") echo "selected=\"selected\"";?>><?php echo $adm_gradient_txt;?></option>
                   </select>
                   </span></td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_from_txt_pl;?>
                     <input name="FROM_PERC" type="text" class="formText" id="FROM_PERC" size="4" maxlength="4" 
					 <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> value="<?php echo $_SESSION['PrescrData']['FROM_PERC'];?>" />
                     %</td>
                   <td align="left" class="formCellNosides">
				   
                   <?php if ($mylang == 'lang_french'){
					echo 'À:';
					}else {
					echo 'To:';
					}
					?>
                     <input name="TO_PERC" type="text" class="formText" id="TO_PERC" size="4" maxlength="4" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> value="<?php echo $_SESSION['PrescrData']['TO_PERC'];?>">
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_color_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR" <?php if ($_SESSION['PrescrData']['TINT']=="None") echo "disabled=\"disabled\"";?> class="formText" id="TINT_COLOR">
                       <option value="" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="")           echo "selected=\"selected\"";?>>&nbsp;</option>
                       <option value="Brown" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Brown") echo "selected=\"selected\"";?>><?php echo $adm_brwn_txt;?></option>
                       <option value="Grey" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Gray") echo "selected=\"selected\"";?>><?php echo $adm_gray_txt;?></option>
                       <option value="Blue" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Blue")   echo "selected=\"selected\"";?>><?php echo 'Blue';?></option>
                       <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>
                             <option value="SW010"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW010")    echo "selected=\"selected\"";?>><?php echo 'SW010';?></option>
                             <option value="SW027/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/50") echo "selected=\"selected\"";?>><?php echo 'SW027/50';?></option>
                             <option value="SW030/50" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/50") echo "selected=\"selected\"";?>><?php echo 'SW030/50';?></option>
                             <option value="SW051" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW051")    echo "selected=\"selected\"";?>><?php echo 'SW051';?></option>
                             <option value="SW035" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW035")    echo "selected=\"selected\"";?>><?php echo 'SW035';?></option>
                             <option value="GOL" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="GOL")      echo "selected=\"selected\"";?>><?php echo 'GOL';?></option>
                             <option value="SW015" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW015")    echo "selected=\"selected\"";?>><?php echo 'SW015';?></option>
                             <option value="RAV" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="RAV")      echo "selected=\"selected\"";?>><?php echo 'RAV';?></option>
                             <option value="SW034" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW034")    echo "selected=\"selected\"";?>><?php echo 'SW034';?></option>
                             <option value="SW012" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW012")    echo "selected=\"selected\"";?>><?php echo 'SW012';?></option>
                             <option value="SW023" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW023")    echo "selected=\"selected\"";?>><?php echo 'SW023';?></option>
                             <option value="SW046" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW046")    echo "selected=\"selected\"";?>><?php echo 'SW046';?></option>
                             <option value="SW025" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025")    echo "selected=\"selected\"";?>><?php echo 'SW025';?></option>
                             <option value="SW004" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW004")    echo "selected=\"selected\"";?>><?php echo 'SW004';?></option>
                             <option value="SW036" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW036")    echo "selected=\"selected\"";?>><?php echo 'SW036';?></option>
                             <option value="SW054" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW054")    echo "selected=\"selected\"";?>><?php echo 'SW054';?></option>
                             <option value="SW062"    <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW062")    echo "selected=\"selected\"";?>><?php echo 'SW062';?></option>
                             <option value="SW026" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW026")    echo "selected=\"selected\"";?>><?php echo 'SW026';?></option>
                             <option value="SW032" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW032")    echo "selected=\"selected\"";?>><?php echo 'SW032';?></option>
                             <option value="TEN" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="TEN")      echo "selected=\"selected\"";?>><?php echo 'TEN';?></option>
                             <option value="AZU" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="AZU")      echo "selected=\"selected\"";?>><?php echo 'AZU';?></option>
                             <option value="SW007" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW007")    echo "selected=\"selected\"";?>><?php echo 'SW007';?></option>
                             <option value="SW001" 	  <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW001")    echo "selected=\"selected\"";?>><?php echo 'SW001';?></option>
                            <?php } ?>
                        
                       </select>
                   </span></td>
                 </tr>
                 
                 <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>  
               <tr>
                   <td align="right" class="formCell">
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'Miroir';
				      	 }else{
					     echo 'Mirror';
					     }?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
 <select name="MIRROR" class="formText" id="MIRROR">
 <option value="none" 	  <?php if ($_SESSION['PrescrData']['MIRROR']=="None")       echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Aucun';  else echo 'None';?></option>
 <option value="Aston" <?php if ($_SESSION['PrescrData']['MIRROR']=="Aston")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Aston';  else echo 'Aston';?></option>
 <option value="Balloon Blue" <?php if ($_SESSION['PrescrData']['MIRROR']=="Balloon Blue")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Balloon Blue';  else echo 'Balloon Blue';?></option>
 <option value="Canyon"<?php if ($_SESSION['PrescrData']['MIRROR']=="Canyon") echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Canyon'; else echo 'Canyon';?></option>
 <option value="Dona" <?php if ($_SESSION['PrescrData']['MIRROR']=="Dona")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Dona';  else echo 'Dona';?></option>    
 <option value="Ocean Flash" <?php if ($_SESSION['PrescrData']['MIRROR']=="Ocean Flash")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Ocean Flash';  else echo 'Ocean Flash';?></option> 
 <option value="Pasha Silver" <?php if ($_SESSION['PrescrData']['MIRROR']=="Pasha Silver")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Pasha Silver';  else echo 'Pasha Silver';?></option>
 <option value="Pink Panther" <?php if ($_SESSION['PrescrData']['MIRROR']=="Pink Panther")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Pink Panther';  else echo 'Pink Panther';?></option>
 <option value="Sahara" <?php if ($_SESSION['PrescrData']['MIRROR']=="Sahara")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Sahara';  else echo 'Sahara';?></option>
 <option value="Tank" <?php if ($_SESSION['PrescrData']['MIRROR']=="Tank")   echo "selected=\"selected\"";?>><?php if ($mylang == 'lang_french') echo 'Tank';  else echo 'Tank';?></option>      
 </select>
                   </span>(Swiss Only)</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                 </tr>
               
                 
                 <?php }//end if ?>


 
                 
                 
                 
                   <tr>
                   <td align="right" class="formCell"><?php echo 'O.C.';?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
            <input name="OPTICAL_CENTER" type="text" class="formText" id="OPTICAL_CENTER" value="<?php echo $_SESSION['PrescrData']['OPTICAL_CENTER'];?>" size="4" maxlength="4">
                   </span></td>
                   <td colspan="3">&nbsp;</td>
                 </tr>  
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides">
                    <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.D.';
					     }?>
                   <br />
                     <input name="RE_PD" value="<?php	if ($_SESSION['PrescrData']['RE_PD']>0)
				 echo  $_SESSION['PrescrData']['RE_PD'];?>" type="text" class="formText" id="RE_PD" size="4" maxlength="4" /><br />R.E.
                   </td>
                   <td align="left" class="formCellNosides"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft">
                    <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.G.';
					     }?>
                   <br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PD']>0)
				 echo  $_SESSION['PrescrData']['LE_PD'];?>" /><br />L.E.
                    


 <td align="center" class="formCellNosides"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['RE_PD_NEAR']>0) echo  $_SESSION['PrescrData']['RE_PD_NEAR'];?>" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/direct-lens/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" value="<?php	if ($_SESSION['PrescrData']['LE_PD_NEAR']>0) echo  $_SESSION['PrescrData']['LE_PD_NEAR'];?>"/>
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>



                   <td align="center" class="formCellNosides">Hauteur<br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" size="4" maxlength="4" value="<?php if ($_SESSION['PrescrData']['RE_HEIGHT'] > 0)	echo  $_SESSION['PrescrData']['RE_HEIGHT'];?>" />
                     <br />
                     R.E.</td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/ifcopticclubca/design_images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="LE_HEIGHT" value="<?php	if ($_SESSION['PrescrData']['LE_HEIGHT'] > 0) echo  $_SESSION['PrescrData']['LE_HEIGHT'];?>" type="text" class="formText" id="LE_HEIGHT" size="4" maxlength="4" />
                     <br />
                     L.E.</td>
                   </tr>
               </table>
             </div>
             
              <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#17A2D2" class="tableHead">
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'INDIVIDUALISE IFREE SEULEMENT';
				      	 }else{
					     echo 'INDIVIDUALISEZ IFREE ONLY';
					     }?></td>
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
             
             
             
             
             
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
             <tr>
                <td colspan="10" bgcolor="#17A2D2" class="tableHead">
				<?php if ($mylang == 'lang_french'){
				echo 'ÉPAISSEURS SPÉCIALES';
				}else {
				echo 'SPECIAL THICKNESS';
				}
				?>&nbsp;
                </td>
                 </tr>
                 <tr>
                   <td align="left" class="formCell">RE CT</td>
                   <td align="left" class="formCellNosides"><input name="RE_CT" type="text" class="formText" value="<?php echo $_SESSION['PrescrData']['RE_CT'];?>"  id="RE_CT" size="4" maxlength="6"></td>
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
                   <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
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
                     <select name="FRAME_TYPE" class="formText" id="FRAME_TYPE">
                       <option value="">SELECT ONE</option>
                       <option value="Nylon Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Nylon Groove") echo "selected=\"selected\"";?>><?php echo $adm_nylgrve_txt;?></option>
                       <option value="Metal Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal Groove") echo "selected=\"selected\"";?>><?php echo $adm_mtlgrve_txt;?></option>
                       <option value="Plastic"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Plastic") echo "selected=\"selected\"";?>><?php echo $adm_plas_txt;?></option>
                       <option value="Metal"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal") echo "selected=\"selected\"";?>><?php echo $adm_mtl_txt;?></option>
                       <option value="Drill and Notch"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Drill and Notch") echo "selected=\"selected\"";?>><?php echo $adm_drillnotc_txt;?></option>
                       </select></td>
                   </tr>
                
                
                
                 
       <div>    
      
            
                 <tr>
                   <td colspan="1" align="center" class="formCell"><?php echo $lbl_jobtype_txt_pl;?>
                     <select name="JOB_TYPE" class="formText" d="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Edge and Mount"><?php echo $lbl_jobtype2_pl;?></option>
                       <option value="remote edging">
						<?php if ($mylang == 'lang_french'){
						echo 'Taillé Non monté';
						}else {
						echo 'Remote Edging';
						}
						?></option>
                       </select>&nbsp;&nbsp;&nbsp;
                    </td>
                    <td colspan="3" align="center" class="formCell">
                    <?php if ($mylang == 'lang_french'){
						echo 'Polir les biseaux';
						}else {
						echo 'Edge Polish';
						}
						?>
                        &nbsp;&nbsp;<input type="checkbox"  name="EDGE_POLISH" id="EDGE_POLISH" <?php  if ($_SESSION['PrescrData']['EDGE_POLISH']=='yes') echo 'checked="checked"';?> value="yes" />
                    </td>
                   </tr>
                  
       </div>    
      
                   
                   
                   
                   
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
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">
                   <input type="text" name="SPECIAL_INSTRUCTIONS" size="80" class="formText" id="SPECIAL_INSTRUCTIONS" value="<?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS']?>">
                   </td>
                 </tr>
                 
                  <?php if ($_SESSION["CompteEntrepot"] == 'yes') {?> 
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo 'Note Interne (Peut contenir du français)';?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">
                   <input type="text" name="INTERNAL_NOTE" size="80" class="formText" id="INTERNAL_NOTE" value="<?php echo $_SESSION['PrescrData']['INTERNAL_NOTE']?>">
                   </td>
                 </tr>
                 <?php } ?>
               </table>
        </div>
        
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <?php /*?><input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" /><?php */?>
		      &nbsp;
              <a href="destroy.php">Reset</a>&nbsp;&nbsp;&nbsp;
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
            
<input name="PACKAGE" type="hidden" id="PACKAGE" value="<?php print $frameItem['misc_unknown_purpose']; ?>"/>
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

$sucess_action_redirect= constant('DIRECT_LENS_URL').'/ifcopticclubca/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement

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
            <tr bgcolor="#17A2D2">
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