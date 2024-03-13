<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
global $drawme;
require_once "../upload/phpuploader/include_phpuploader.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");
	
	require('../Connections/sec_connect.inc.php');
		
$query="select index_v from exclusive group by index_v asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);

$queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
$resultLab=mysql_query($queryLab)	or die ("Could not select items");
$DataLab=mysql_fetch_array($resultLab);
$LabNum=$DataLab[main_lab];	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>


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

   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form_retry.js.inc.php";?>
</head>

<body onload="setEnabled(PRESCRIPTION)">
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION"   onSubmit="return validate(this)"><div>
<div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      <div>
		     <?php
				 echo '<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr >
                    <td bgcolor="#17A2D2" class="tableHead">MONTURE</td>
                    <td bgcolor="#17A2D2" class="tableHead" width=\"250\">NUMERO DE MONTURE</td>
                    </tr>';
				echo "<tr><td><img src=\"$frameItem[prod_tn]\" alt=\"$frameItem[model]\" border=\"0\" title=\"$frameItem[model]\" width=\"450\" ></td ><td >";
				echo "<div class=\"frame-specs\" ><b>MODELE: $frameItem[model]</b></div>";
				echo "<div class=\"frame-specs\" ><b>TYPE:</b> $frameItem[type]</div>";
				echo "<div class=\"frame-specs\" ><b>GENRE:</b> $frameItem[gender]</div>";
				echo "<div class=\"frame-specs\" ><b>MATIERE:</b> $frameItem[material]</div>";
				echo "<div class=\"frame-specs\" ><b>COULEURS:</b> $frameItem[color]</div>";
				echo "<div class=\"frame-specs\" ><b>TAILLE:</b> $frameItem[boxing]</div>";
			
				echo "</td></tr></table>";
				?><table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="3" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_patient;?>&nbsp;</td>
                <td bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td class="formCellNosides"><?php echo $adm_refnumber_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_salesname_txt;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php echo $_SESSION['PrescrData']['LAST_NAME'];?>" size="25" /></td>
                <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME"  value="<?php echo $_SESSION['PrescrData']['FIRST_NAME'];?>" size="25" /></td>
                <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM"  value="<?php echo $_SESSION['PrescrData']['PATIENT_REF_NUM'];?>" size="10" /></td>
                 <td class="formCellNosides"><input name="SALESPERSON_ID" type="text" class="formText" id="SALESPERSON_ID"  value="<?php echo $_SESSION['PrescrData']['SALESPERSON_ID'];?>" size="15" maxlength="15" /></td>
              </tr>
	      </table></div>
             <div>
				<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_mast1;?>
                  <span class="formCell">
                  <input name="EYE" type="radio" value="Both" <?php if ($_SESSION['PrescrData']['EYE']=="Both") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription1_pl;?> <span class="formCell"> &nbsp;
                  <input name="EYE" type="radio" value="R.E." <?php if ($_SESSION['PrescrData']['EYE']=="R.E.") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription2_pl;?>&nbsp; <span class="formCell">
                  <input name="EYE" type="radio" value="L.E." <?php if ($_SESSION['PrescrData']['EYE']=="L.E.") echo "checked=\"checked\"";?>/>
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
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/safety/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
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
                <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
              
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
                <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" 
                
                value="<?php
				if ($_SESSION['PrescrData']['RE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['RE_AXIS'];
				 ?>"
                
                 onchange="validateRE_Axis(this)" />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
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
                <td align="right" valign="top"class="formCell"><input name="RE_PR_IO" type="radio" value="In" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='In') echo 'checked="checked"';?>/>
<?php echo $adm_in_txt;?>&nbsp;<input name="RE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='Out') echo 'checked="checked"';?>/><?php echo $adm_out_txt;?>
<input name="RE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='None') echo 'checked="checked"';?>/> 
<?php echo $adm_none_txt;?>

<input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" 
value="<?php
				if ($_SESSION['PrescrData']['RE_PR_AX']>0)
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
                <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" 
                
                value="<?php
				if ($_SESSION['PrescrData']['LE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['LE_AXIS'];
				 ?>"/>
                 
                 
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
                <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In"<?php if ($_SESSION['PrescrData']['LE_PR_IO']=='In') echo 'checked="checked"';?>/><?php echo $adm_in_txt;?>&nbsp;<input name="LE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='Out') echo 'checked="checked"';?>/><?php echo $adm_out_txt;?>
                  <input name="LE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='None') echo 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" 
                  value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX'];
				 ?>"
                  
                   size="4" maxlength="4" /><br /><input name="LE_PR_UD" type="radio" value="Up"<?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Up') echo 'checked="checked"';?>/><?php echo $adm_up_txt;?>&nbsp;<input name="LE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Down') echo 'checked="checked"';?>/><?php echo $adm_down_txt;?>&nbsp;
                  <input name="LE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='None') echo 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" 
                  value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX2'];
				 ?>"
                  
                   size="4" maxlength="4" /><input type="hidden" name="uploadhold" id="uploadhold" value="<?php	echo $_SESSION['PrescrData']['myupload']; ?>" /></td>
              </tr>
          </table></div>
                          
<div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                       <?php
  $query="select index_v from ifc_ca_exclusive group by index_v asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
  
  echo "<option value=\"$listItem[index_v]\"";
  
 if ($_SESSION['PrescrData']['INDEX']=="$listItem[index_v]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[index_v]);
 			switch ($listItem[index_v] ){
					case '1.56' :
						$name.=" AS"; 
					break;
					case '1.59' :
						$name.=" PC"; 
					break;
					case '1.60' :
						$name.=" AS"; 
					break;
					case '1.67' :
						$name.=" AS"; 
					break;               
				}
 echo "$name</option>";}?>
                       </select>
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_coating_txt;?></span></td>
                   <td align="left" class="formCellNosides"><select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="<?php if ($_SESSION['PrescrData']['COATING']=="ANY") echo "selected=\"selected\"";?>"  >Tous</option>
                     <option value="Hard Coat" <?php if ($_SESSION['PrescrData']['COATING']=="Hard Coat") echo "selected=\"selected\"";?>>Durci</option>
                     <option value="AR" <?php if ($_SESSION['PrescrData']['COATING']=="HMC") echo "selected=\"selected\"";?>>HMC</option>
                    <option value="AR" <?php if ($_SESSION['PrescrData']['COATING']=="HMC EMI") echo "selected=\"selected\"";?>>HMC EMI</option>
                     <option value="Uncoated" <?php if ($_SESSION['PrescrData']['COATING']=="Uncoated") echo "selected=\"selected\"";?>>Nu</option>  
                   </select></td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_photochr_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="PHOTO" class="formText" id="PHOTO">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['PHOTO']=="None") echo "selected=\"selected\"";?>"><?php echo $adm_none_txt;?></option>
                       <?php
  $query="select photo from ifc_ca_exclusive  where photo not in ('Yellow','Orange','Pink','Blue','Violet') group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 
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
                   </select>
                   </span></td>
                   </tr>
                   
                   
                  <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_france'){
				echo 'Catégorie de verres:';
				}else {
				echo 'Lens category';
				}
				?></span></td>
                   <td align="left" class="formCellNosides" colspan="3"><span style="margin:11px">
                   
                <?php if ($mylang == 'lang_france'){				?>
               <select name="lens_category">
                <option  value="all">Tous</option>
                <option  value="prog 14">Progressif 14</option>
                <option  value="prog 17">Progressif 17</option>
                </select>
                <?php 
				}else {
				?>
				 <select name="lens_category">
                <option  value="all">All</option>
                <option  value="bifocal">Bi-focal</option>
               <option  value="glass">Glass</option>
                <option  value="prog cl">Progressive Classic</option>
                <option  value="prog ds">Progressive DS</option>
                <option  value="prog ff">Progressive FF</option>
                 <option value="sv">Sv</option>
                </select>
				<?php
                }
				?></span></td>
                </tr>
                   
              </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp; </td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides">&nbsp;</td>
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   <td align="center" class="formCellNoleft">&nbsp;</td>
                   <td align="center" class="formCellNosides"><?php echo $adm_nearpd_txt;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" 
                     value="<?php	if ($_SESSION['PrescrData']['RE_PD_NEAR']>0)
				 echo  $_SESSION['PrescrData']['RE_PD_NEAR'];
				 ?>" size="4" maxlength="4" />
                     <br />
                   <?php echo $adm_re_txt;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/safety/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
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
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/safety/design_images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
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
                   <td colspan="8" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_engrav_txt_pl;?> </td>
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
                   <td colspan="4" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
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
                       <option value="Plastic"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Plastic") echo "selected=\"selected\"";?>><?php echo $adm_plas_txt;?></option>
                       <option value="Metal"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal") echo "selected=\"selected\"";?>><?php echo $adm_mtl_txt;?></option>
                       <option value="Edge Polish"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Edge Polish") echo "selected=\"selected\"";?>><?php echo $adm_edgepol_txt;?></option>
                       <option value="Drill and Notch"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Drill and Notch") echo "selected=\"selected\"";?>><?php echo $adm_drillnotc_txt;?></option>
                       </select></td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $adm_jobtype_txt;?>
                     <select name="JOB_TYPE" class="formText" id="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Uncut" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") echo "selected=\"selected\"";?>><?php echo $adm_uncut_txt;?></option>
                       <option value="Edge and Mount" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Edge and Mount") echo "selected=\"selected\"";?>><?php echo $adm_edgemount_txt;?></option>
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
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"><?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?></textarea></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4"  bgcolor="#17A2D2" class="tableHead">NOTE INTERNE RESERVEE AU MAGASIN </td>
                   </tr>
                 <tr>
                   <td width="134" align="center" valign="top" class="formCell"><span class="tableSubHead">NOTE INTERNE RESERVEE AU MAGASIN</span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="INTERNAL_NOTE" cols="70" rows="2" class="formText" id="INTERNAL_NOTE"><?php echo $_SESSION['PrescrData']['INTERNAL_NOTE'];?></textarea></td>
                   </tr>
               </table>
             </div>
			 
			 
<div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="Submit" type="submit" value="<?php echo $btn_submit_txt;?>" />
		    </div></div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>