<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include "config.inc.php";
global $drawme;
require_once "../upload/phpuploader/include_phpuploader.php";

session_start();

$_SESSION['REFERRER']="sv_form.php";// CATCH FOR RETRY

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	require('../Connections/sec_connect.inc.php');

if($_SESSION["account_type"]=="restricted")
	{
	header("Location:order_history.php");
	}
	
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


   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<?php include "js/prescription_form.js.inc.php";?>

</head>

<body>
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
<div id="rightColumn"><form action="svList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateSV(this);">
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">Unifocaux</div></td><td><div id="headerGraphic">
              <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      <div>
              
                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                

                  <tr >
                    <td colspan="3" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_client;?></td>
                    <td bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_refnum_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_slsperson_txt;?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php echo $_SESSION['svFormVars']['LAST_NAME'];?>" size="25" /></td>
                    <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" value="<?php echo $_SESSION['svFormVars']['FIRST_NAME'];?>" size="25" /></td>
                    <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" value="<?php echo $_SESSION['svFormVars']['PATIENT_REF_NUM'];?>" size="10" /></td>
                    <td class="formCellNosides"><input name="SALESPERSON_ID" type="text" class="formText" id="SALESPERSON_ID" value="<?php echo $_SESSION['svFormVars']['SALESPERSON_ID'];?>" size="15" maxlength="15" /></td>
                    </tr>
                </table>
              </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                 <tr >
                   <td colspan="7" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>
                   <input name="EYE" type="radio" value="Both" checked="checked" />
                       <?php echo $lbl_prescription1_pl;?> 
                         &nbsp;
                         <input name="EYE" type="radio" value="R.E." />
                         <?php echo $lbl_prescription2_pl;?> 
                           <input name="EYE" type="radio" value="L.E."  />
                          <?php echo $lbl_prescription3_pl;?> </td>
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
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/ifcopticclub/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="OD = OG" /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                     <option value="+8"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['svFormVars']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['svFormVars']['RE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
                     <option value="-10"<?php if ($_SESSION['svFormVars']['RE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                   </select>
                     <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['svFormVars']['RE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['svFormVars']['RE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['svFormVars']['RE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['svFormVars']['RE_SPH_DEC']==".00")||(strlen($_SESSION['svFormVars']['RE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                    <option value="+4"<?php if ($_SESSION['svFormVars']['RE_CYL_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['svFormVars']['RE_CYL_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['svFormVars']['RE_CYL_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['svFormVars']['RE_CYL_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0" <?php if (($_SESSION['svFormVars']['RE_CYL_NUM']==="+0")||(strlen($_SESSION['svFormVars']['RE_CYL_NUM'])<2)) echo "selected=\"selected\"";?>>+0</option>
                   </select>
                     <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['svFormVars']['RE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['svFormVars']['RE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['svFormVars']['RE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['svFormVars']['RE_CYL_DEC']==".00")||(strlen($_SESSION['svFormVars']['RE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['svFormVars']['RE_AXIS'];?>" size="4" maxlength="3"  />
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD" disabled>
                     <option value="+3.00">+3.00</option>
                     <option value="+2.75">+2.75</option>
                     <option value="+2.50">+2.50</option>
                     <option value="+2.25">+2.25</option>
                     <option value="+2.00">+2.00</option>
                     <option value="+1.75">+1.75</option>
                     <option value="+1.50">+1.50</option>
                     <option value="+1.25">+1.25</option>
                     <option value="+1.00">+1.00</option>
                     <option value="+0.75">+0.75</option>
                     <option value="+0.00" selected="selected">+0.00</option>
                     </select></td>
                     <td align="right" valign="top"class="formCell">Temporale
                     <input name="RE_PR_IO" type="radio" value="Temporale" <?php if ($_SESSION['svFormVars']['RE_PR_IO']=='Temporale') echo 'checked="checked"';?>/>
                    Nasale
                     <input name="RE_PR_IO" type="radio" value="Nasale" <?php if ($_SESSION['svFormVars']['RE_PR_IO']=='Nasale') echo 'checked="checked"';?>/> <?php echo $lbl_none_txt_pl;?><input name="RE_PR_IO" type="radio" value="None" <?php if (($_SESSION['svFormVars']['RE_PR_IO']=='')||($_SESSION['svFormVars']['RE_PR_IO']=='None')) echo 'checked="checked"';?>/>
                    
                   
                   <input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" value="<?php echo $_SESSION['prFormVars']['RE_PR_AX'];?>" size="4" maxlength="4" /> Puissance<br /></td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                     <option value="+8"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+8") echo "selected=\"selected\"";?>>+8</option>
                     <option value="+7"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+7") echo "selected=\"selected\"";?>>+7</option>
                     <option value="+6"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+6") echo "selected=\"selected\"";?>>+6</option>
                     <option value="+5"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+5") echo "selected=\"selected\"";?>>+5</option>
                     <option value="+4"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="+0") echo "selected=\"selected\"";?>>+0</option>
                     <option value="-0"<?php if (($_SESSION['svFormVars']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['svFormVars']['LE_SPH_NUM'])<2)) echo "selected=\"selected\"";?>>-0</option>
                     <option value="-1"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-1") echo "selected=\"selected\"";?>>-1</option>
                     <option value="-2"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-2") echo "selected=\"selected\"";?>>-2</option>
                     <option value="-3"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-3") echo "selected=\"selected\"";?>>-3</option>
                     <option value="-4"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-4") echo "selected=\"selected\"";?>>-4</option>
                     <option value="-5"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-5") echo "selected=\"selected\"";?>>-5</option>
                     <option value="-6"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-6") echo "selected=\"selected\"";?>>-6</option>
                     <option value="-7"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-7") echo "selected=\"selected\"";?>>-7</option>
                     <option value="-8"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-8") echo "selected=\"selected\"";?>>-8</option>
                     <option value="-9"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-9") echo "selected=\"selected\"";?>>-9</option>
                     <option value="-10"<?php if ($_SESSION['svFormVars']['LE_SPH_NUM']=="-10") echo "selected=\"selected\"";?>>-10</option>
                   </select>
                     <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                       <option value=".75"<?php if ($_SESSION['svFormVars']['LE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['svFormVars']['LE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['svFormVars']['LE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00"  <?php if (($_SESSION['svFormVars']['LE_SPH_DEC']==".00")||(strlen($_SESSION['svFormVars']['LE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">
                      <option value="+4"<?php if ($_SESSION['svFormVars']['LE_CYL_NUM']=="+4") echo "selected=\"selected\"";?>>+4</option>
                     <option value="+3"<?php if ($_SESSION['svFormVars']['LE_CYL_NUM']=="+3") echo "selected=\"selected\"";?>>+3</option>
                     <option value="+2"<?php if ($_SESSION['svFormVars']['LE_CYL_NUM']=="+2") echo "selected=\"selected\"";?>>+2</option>
                     <option value="+1"<?php if ($_SESSION['svFormVars']['LE_CYL_NUM']=="+1") echo "selected=\"selected\"";?>>+1</option>
                     <option value="+0" <?php if (($_SESSION['svFormVars']['LE_CYL_NUM']==="+0")||(strlen($_SESSION['svFormVars']['LE_CYL_NUM'])<2)) echo "selected=\"selected\"";?>>+0</option> 
                   </select>
                     <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                       <option value=".75"<?php if ($_SESSION['svFormVars']['LE_CYL_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                       <option value=".50"<?php if ($_SESSION['svFormVars']['LE_CYL_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                       <option value=".25"<?php if ($_SESSION['svFormVars']['LE_CYL_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                       <option value=".00" <?php if (($_SESSION['svFormVars']['LE_CYL_DEC']==".00")||(strlen($_SESSION['svFormVars']['LE_CYL_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                     </select></td>
                   <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" onchange="validateRE_Axis(this)" value="<?php echo $_SESSION['svFormVars']['LE_AXIS'];?>" size="4" maxlength="3"/>
                     (001-180)</td>
                   <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD" disabled>
                     <option value="+3.00">+3.00</option>
                     <option value="+2.75">+2.75</option>
                     <option value="+2.50">+2.50</option>
                     <option value="+2.25">+2.25</option>
                     <option value="+2.00">+2.00</option>
                     <option value="+1.75">+1.75</option>
                     <option value="+1.50">+1.50</option>
                     <option value="+1.25">+1.25</option>
                     <option value="+1.00">+1.00</option>
                     <option value="+0.75">+0.75</option>
                     <option value="+0.00" selected="selected">+0.00</option>
                     </select></td>
                  <td align="right" valign="top"class="formCell">Temporale
                     <input name="LE_PR_IO" type="radio" value="Temporale" <?php if ($_SESSION['prFormVars']['LE_PR_IO']=='Temporale') echo 'checked="checked"';?>/>
                    Nasale
                     <input name="LE_PR_IO" type="radio" value="Nasale" <?php if ($_SESSION['prFormVars']['LE_PR_IO']=='Nasale') echo 'checked="checked"';?>/><?php echo $lbl_none_txt_pl;?><input name="LE_PR_IO" type="radio" value="None" <?php if (($_SESSION['prFormVars']['LE_PR_IO']=='')||($_SESSION['prFormVars']['LE_PR_IO']=='None')) echo 'checked="checked"';?>/>
                   
                   <input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" value="<?php echo $_SESSION['prFormVars']['LE_PR_AX'];?>" size="4" maxlength="4" /> Puissance<br /></td>
                   </tr>
               </table>
   </div>
             
             
        
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">

                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                       <option <?php if ($_SESSION['svFormVars']['INDEX']=="1.50") echo 'selected="selected"';  ?> value="1.50">1.50</option>
                       <option <?php if ($_SESSION['svFormVars']['INDEX']=="1.56") echo 'selected="selected"';  ?> value="1.56">1.56</option>
                       <option <?php if ($_SESSION['svFormVars']['INDEX']=="1.58") echo 'selected="selected"';  ?> value="1.56">1.58</option>
                       <option <?php if ($_SESSION['svFormVars']['INDEX']=="1.60") echo 'selected="selected"';  ?> value="1.60">1.60</option>           
                       <option <?php if ($_SESSION['svFormVars']['INDEX']=="1.67") echo 'selected="selected"';  ?> value="1.67">1.67</option>               
                 </select>
                     </span></td>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="COATING" class="formText" id="COATING">
                      <option value="ANY" selected="<?php if ($_SESSION['svFormVars']['COATING']=="ANY") echo "selected=\"selected\"";?>">Tous</option>
                      <option value="Uncoated" <?php if ($_SESSION['svFormVars']['COATING']=="Uncoated") echo "selected=\"selected\"";?>>Nu</option>  
                      <option value="Hard Coat" <?php if ($_SESSION['svFormVars']['COATING']=="Hard Coat") echo "selected=\"selected\"";?>>Durci</option>
                      <option value="HMC" <?php if ($_SESSION['svFormVars']['COATING']=="HMC") echo "selected=\"selected\"";?>>AR</option>
                      <option value="HMC EMI" <?php if ($_SESSION['svFormVars']['COATING']=="HMC EMI") echo "selected=\"selected\"";?>>AR+ETC</option>
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
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     
                   <?php if ($mylang == 'lang_france'){				?>
                   <select name="lens_category">
                      <option  value="sv" <?php if ($_SESSION['lens_category']=="sv") echo 'selected="selected"'; ?>>Unifocaux</option>
                   </select>
                   <?php 
				}else {
				?>
                   <select name="lens_category">
                     <option  value="sv" <?php if ($_SESSION['lens_category']=="sv") echo 'selected="selected"'; ?>>Unifocaux</option>
                   </select>
                   <?php
                }
				?>
                     
                     
                   </span>
                     
                   </td>
                 
                 
                   <input name="PHOTO" type="hidden" value="None"  id="PHOTO">
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px"><select name="POLAR" class="formText" id="POLAR">
                     <option value="None" selected="selected"><?php echo $lbl_polarized1;?></option>
                     <?php
  $query="select polar from ifc_exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 if ($listItem[polar]!="None"){
 echo "<option value=\"$listItem[polar]\"";
 
 if ($_SESSION['svFormVars']['POLAR']=="$listItem[polar]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}}?>
                     </select></span></td>
                   </tr>
                   
                   
                   
                   
                     <tr>
                  
                   <td align="right" class="formCell"><span class="tableSubHead">Dia.</span></td>
                   <td align="left" class="formCellNosides">
                <select name="DIAMETER" class="formText" id="DIAMETER">
                <option  value="" selected="selected">Sélectionner</option>
                <option  value="55" <?php if ($_SESSION['svFormVars']['DIAMETER']=="55") echo "selected=\"selected\"";?> >55 mm</option>
                <option  value="60" <?php if ($_SESSION['svFormVars']['DIAMETER']=="60") echo "selected=\"selected\"";?> >60 mm</option>
                <option  value="65" <?php if ($_SESSION['svFormVars']['DIAMETER']=="65") echo "selected=\"selected\"";?> >65 mm</option>
                <option  value="70"  <?php if ($_SESSION['svFormVars']['DIAMETER']=="70") echo "selected=\"selected\"";?>>70 mm</option>
                <option  value="75"  <?php if ($_SESSION['svFormVars']['DIAMETER']=="75") echo "selected=\"selected\"";?>>75 mm</option>
                </select>
</td>


					<td   align="right" class="formCell"><span class="tableSubHead">Photochromique:</span></td>
                        <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                  <select name="PHOTO" class="formText" id="PHOTO">
                       <option value="none" selected="selected"><?php echo Aucun;?></option>
                       <option <?php if ($_SESSION['svFormVars']['PHOTO']=="Gris") echo 'selected="selected"';  ?> value="Gris">Gris</option>           
                       <option <?php if ($_SESSION['svFormVars']['PHOTO']=="Brun") echo 'selected="selected"';  ?> value="Brun">Brun</option>               
                 </select>
                   </span></td>
                   </tr>
                   
               </table>
   </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                 </tr>
                 <tr>
                  
                
                     <input name="ENGRAVING" type="hidden" class="formText" id="ENGRAVING" value="<?php echo $_SESSION['svFormVars']['ENGRAVING'];?>" size="4" maxlength="8" disable />
                   <td align="right" class="formCell"><?php echo $lbl_tint_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                       <option value="None" <?php if ($_SESSION['svFormVars']['TINT']=="None") echo "selected=\"selected\"";?>><?php echo $adm_none_txt;?></option>
                     <?php /*?>  <option value="Solid" <?php if ($_SESSION['svFormVars']['TINT']=="Solid") echo "selected=\"selected\"";?>><?php echo $lbl_tint2_pl;?></option>
                       <option value="Gradient" <?php if ($_SESSION['svFormVars']['TINT']=="Gradient") echo "selected=\"selected\"";?>><?php echo $adm_gradient_txt;?></option><?php */?>
                       <option value="Solid 60" <?php if ($_SESSION['svFormVars']['TINT']=="Solid 60") echo "selected=\"selected\"";?>>  <?php echo 'CAT 2 (60%)';?></option>
                       <option value="Solid 80"   <?php if ($_SESSION['svFormVars']['TINT']=="Solid 80") echo "selected=\"selected\"";?>><?php echo 'CAT 3 (82%) ';?></option>
                     </select>
                   </span></td>
               
                     <input name="FROM_PERC" type="hidden" disabled="disabled" class="formText" id="FROM_PERC" value="<?php echo $_SESSION['svFormVars']['FROM_PERC'];?>" size="4" maxlength="4" />
                   
                     <input name="TO_PERC" type="hidden" disabled="disabled" class="formText" id="TO_PERC" value="<?php echo $_SESSION['svFormVars']['TO_PERC'];?>" size="4" maxlength="4">
                    
                   <td align="left" class="formCellNosides"><?php echo $lbl_color_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR"  <?php if ($_SESSION['svFormVars']['TINT_COLOR'] == '') echo 'disabled="disabled"';?> class="formText" id="TINT_COLOR">
                        <option <?php if ($_SESSION['svFormVars']['TINT_COLOR']=="none")       echo  "selected=\"selected\"";?> value="<?php echo 'none';?>"><?php echo 'Non';?></option>
                       <option <?php if ($_SESSION['svFormVars']['TINT_COLOR']=="Brown") echo "selected=\"selected\"";?> value="<?php echo $lbl_color1_pl;?>"><?php echo $lbl_color1_pl;?></option>
                       <option <?php if ($_SESSION['svFormVars']['TINT_COLOR']=="Grey") echo "selected=\"selected\"";?> value="<?php echo 'Grey';?>"><?php echo 'Gris';?></option>
                        <option <?php if ($_SESSION['svFormVars']['TINT_COLOR']=="G-15") echo "selected=\"selected\"";?>  value="<?php echo 'G-15';?>"><?php echo 'G-15';?></option>
                     </select>
                   </span></td>
                 </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"><?php echo $_SESSION['svFormVars']['SPECIAL_INSTRUCTIONS'];?></textarea></td>
                 </tr>
               </table>
        </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4"  bgcolor="#17A2D2" class="tableHead">
                     <?php if ($mylang == 'lang_france'){
				echo 'NOTE INTERNE RESERVEE AU MAGASIN';
				}else {
				echo 'INTERNAL NOTE';
				}
				?>
                   </td>
                 </tr>
                 <tr>
                   <td width="134" align="center" valign="top" class="formCell"><span class="tableSubHead">
                     <?php if ($mylang == 'lang_france'){
				echo 'NOTE INTERNE RESERVEE AU MAGASIN';
				}else {
				echo 'Internal note';
				}
				?></span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="INTERNAL_NOTE" cols="70" rows="2" class="formText" id="INTERNAL_NOTE"><?php echo $_SESSION['svFormVars']['INTERNAL_NOTE'];?></textarea></td>
                 </tr>
               </table>
         </div>
			
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
            
<input name="SUPPLIER" type="hidden" id="SUPPLIER" value="<?php echo $frameItem['collection']; ?>"/>
<input name="FRAME_MODEL" type="hidden" id="FRAME_MODEL" value="<?php echo $frameItem['frame_shape']; ?>"/>
<input name="PACKAGE" type="hidden" id="PACKAGE" value="<?php echo $frameItem['misc_unknown_purpose']; ?>"/>
<input name="TEMPLE_MODEL" type="hidden" id="TEMPLE_MODEL" value="<?php echo $frameItem['model']; ?>"/>
<input name="COLOR" type="hidden" id="COLOR" value="<?php echo $frameItem['color']; ?>" /> 
<input name="JOB_TYPE" type="hidden" id="JOB_TYPE" value="Edge and Mount" /> 
<input name="ORDER_TYPE" type="hidden" id="ORDER_TYPE" value="Provide" /> 
<input name="FRAME_TYPE" type="hidden" id="FRAME_TYPE" value="<?php echo $frameItem['material']; ?>" /> 

                     
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->



</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>