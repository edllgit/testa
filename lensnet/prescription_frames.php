<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");
	
	require('../Connections/sec_connect.inc.php');
	
	$query="SELECT * from frames
	LEFT JOIN (frames_collections) on (frames_collections.frames_collections_id=frames.frames_collections_id)
	WHERE frames_id='$_POST[frames_id]'"; ///GET FRAME INFO
	$result=mysql_query($query)
		or die ("Could not select frames items because".mysql_error());
	$frameItem=mysql_fetch_array($result);
	
	$query="SELECT * from frames_colors WHERE temple_model_num='$_POST[temple_model_num]'"; //GET TEMPLE INFO
	$result=mysql_query($query)
		or die ("Could not select items because".mysql_error());
	$templeItem=mysql_fetch_array($result);

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

<?php include "js/prescription_form_frames.js.inc.php";?>

</head>

<body>
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
<div id="rightColumn"><form action="prescriptionList.php" method="post" name="PRESCRIPTION" id="PRESCRIPTION"   onSubmit="return validate(this)">
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		     <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="3" bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_patient;?></td>
                <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td class="formCellNosides"><?php echo $adm_refnumber_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_slsperson_txt;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" size="25" /></td>
                <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" size="25" /></td>
                <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" size="10" /></td>
                <td class="formCellNosides"><select name="SALESPERSON_ID" class="formText" id="SALESPERSON_ID">
                        <option value="" selected="selected"><?php echo $adm_none_txt;?></option>
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
				<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_mast1;?>
                  <span class="formCell">
                  <input name="EYE" type="radio" value="Both" checked="checked" />
                  </span><?php echo $lbl_prescription1_pl;?> <span class="formCell">
                  &nbsp;
                  <input name="EYE" type="radio" value="R.E." />
                  </span><?php echo $lbl_prescription2_pl;?>&nbsp; <span class="formCell">
                   <input name="EYE" type="radio" value="L.E." />
                  </span><?php echo $lbl_prescription3_pl;?></td>
                </tr>
              <tr>
                <td colspan="2" valign="middle"  class="formCell">&nbsp;</td>
                <td align="center" valign="middle" class="formCellNosides"><?php echo $adm_sphere_txt;?></td>
                <td align="center" valign="middle" class="formCellNosides"><?php echo $adm_cylinder_txt;?></td>
                <td align="center" valign="middle" class="formCellNosides"><?php echo $adm_axis_txt;?></td>
                <td align="center" valign="middle" class="formCellNosides"><?php echo $adm_addition_txt;?></td>
                <td align="center" valign="middle" class="formCell"><?php echo $adm_prism_txt;?>                  </td>
              </tr>
              <tr >
                <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/lensnet/images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                <td align="right" valign="top"  class="formCell"><?php echo $adm_re_txt;?> </td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                 <option value="+14">+14</option>
                  <option value="+13">+13</option>
                  <option value="+12">+12</option>
                  <option value="+11">+11</option>
                  <option value="+10">+10</option>
                  <option value="+9">+9</option>
                  <option value="+8">+8</option>
				  <option value="+7">+7</option>
				  <option value="+6">+6</option>
				  <option value="+5">+5</option>
				  <option value="+4">+4</option>
				  <option value="+3">+3</option>
				  <option value="+2">+2</option>
				  <option value="+1">+1</option>
				  <option value="+0">+0</option>
                  <option value="-0" selected="selected">-0</option>
				  <option value="-1">-1</option>
				  <option value="-2">-2</option>
				  <option value="-3">-3</option>
				  <option value="-4">-4</option>
				  <option value="-5">-5</option>
				  <option value="-6">-6</option>
				  <option value="-7">-7</option>
				  <option value="-8">-8</option>
				  <option value="-9">-9</option>
				  <option value="-10">-10</option>
				  <option value="-11">-11</option>
				  <option value="-12">-12</option>
				  <option value="-13">-13</option>
				  <option value="-14">-14</option>
				  <option value="-15">-15</option>
                  </select>
                  <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                    <option value=".75">.75</option>
                    <option value=".50">.50</option>
                    <option value=".25">.25</option>
                    <option value=".00"  selected="selected">.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                  <option value="+6">+6</option>
                  <option value="+5">+5</option>
                  <option value="+4">+4</option>
                  <option value="+3">+3</option>
                  <option value="+2">+2</option>
                  <option value="+1">+1</option>
                  <option value="+0">+0</option>
                  <option value="-0" selected="selected">-0</option>
                  <option value="-1">-1</option>
                  <option value="-2">-2</option>
                  <option value="-3">-3</option>
                  <option value="-4">-4</option>
                  <option value="-5">-5</option>
				  <option value="-6">-6</option>
				  <option value="-7">-7</option>
				  <option value="-8">-8</option>
                </select>
                  <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                    <option value=".75">.75</option>
                    <option value=".50">.50</option>
                    <option value=".25">.25</option>
                    <option value=".00"  selected="selected">.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
                  <option value="+3.50">+3.50</option>
                  <option value="+3.25">+3.25</option>
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
                  <option value="+0.50">+0.50</option>
                  <option value="+0.25">+0.25</option>
                  <option value="+0.00" selected="selected">+0.00</option>
                    </select></td>
                <td align="right" valign="top"class="formCell">
                  <input name="RE_PR_IO" type="radio" value="In" />
                  <?php echo $adm_in_txt;?> &nbsp;
                    <input name="RE_PR_IO" type="radio" value="Out" />
                  <?php echo $adm_out_txt;?> &nbsp;
                    <input name="RE_PR_IO" type="radio" value="None" checked="checked" />
                    <?php echo $adm_none_txt;?> <input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="3" maxlength="3" /><br />
                  <input name="RE_PR_UD" type="radio" value="Up" />
                  <?php echo $adm_up_txt;?>&nbsp;
                    <input name="RE_PR_UD" type="radio" value="Down" />
                  <?php echo $adm_down_txt;?>&nbsp;
                    <input name="RE_PR_UD" type="radio" value="None" checked="checked" />
                    <?php echo $adm_none_txt;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="3" maxlength="3" />
                </td>
              </tr>
              <tr >
                <td colspan="2" align="right" valign="top"class="formCell"><?php echo $adm_le_txt;?> </td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_SPH_NUM" class="formText" id="LE_SPH_NUM"  onchange="fixLE_SPH(this.form)">
                <option value="+14">+14</option>
                <option value="+13">+13</option>
				 <option value="+12">+12</option>
                  <option value="+11">+11</option>
                  <option value="+10">+10</option>
                  <option value="+9">+9</option>
                  <option value="+8">+8</option>
                  <option value="+7">+7</option>
                  <option value="+6">+6</option>
                  <option value="+5">+5</option>
                  <option value="+4">+4</option>
                  <option value="+3">+3</option>
                  <option value="+2">+2</option>
                  <option value="+1">+1</option>
                  <option value="+0">+0</option>
                  <option value="-0" selected="selected">-0</option>
                  <option value="-1">-1</option>
                  <option value="-2">-2</option>
                  <option value="-3">-3</option>
                  <option value="-4">-4</option>
                  <option value="-5">-5</option>
                  <option value="-6">-6</option>
                  <option value="-7">-7</option>
                  <option value="-8">-8</option>
                  <option value="-9">-9</option>
                  <option value="-10">-10</option>
                  <option value="-11">-11</option>
                  <option value="-12">-12</option>
                  <option value="-13">-13</option>
                  <option value="-14">-14</option>
                  <option value="-15">-15</option>
                </select>
                  <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                    <option value=".75">.75</option>
                    <option value=".50">.50</option>
                    <option value=".25">.25</option>
                    <option value=".00"  selected="selected">.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">
             <option value="+6">+6</option>
                  <option value="+5">+5</option>
                  <option value="+4">+4</option>
                  <option value="+3">+3</option>
                  <option value="+2">+2</option>
                  <option value="+1">+1</option>
                  <option value="+0">+0</option>
                  <option value="-0" selected="selected">-0</option>
                  <option value="-1">-1</option>
                  <option value="-2">-2</option>
                  <option value="-3">-3</option>
                  <option value="-4">-4</option>
                  <option value="-5">-5</option>
				  <option value="-6">-6</option>
				  <option value="-7">-7</option>
				  <option value="-8">-8</option>
                </select>
                  <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                    <option value=".75">.75</option>
                    <option value=".50">.50</option>
                    <option value=".25">.25</option>
                    <option value=".00"  selected="selected">.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
                  <option value="+3.50">+3.50</option>
                  <option value="+3.25">+3.25</option>
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
                  <option value="+0.50">+0.50</option>
                  <option value="+0.25">+0.25</option>
                  <option value="+0.00" selected="selected">+0.00</option>
                </select></td>
                <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In" />
                  <?php echo $adm_in_txt;?>&nbsp;
                  <input name="LE_PR_IO" type="radio" value="Out" />
                  <?php echo $adm_out_txt;?>
                  <input name="LE_PR_IO" type="radio" value="None" checked="checked" />
<?php echo $adm_none_txt;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" size="3" maxlength="3" /><br /><input name="LE_PR_UD" type="radio" value="Up" />
                  <?php echo $adm_up_txt;?>&nbsp;
                  <input name="LE_PR_UD" type="radio" value="Down" />
                  <?php echo $adm_down_txt;?>
                  <input name="LE_PR_UD" type="radio" value="None" checked="checked" />
<?php echo $adm_none_txt;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="3" maxlength="3" /></td>
              </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
              <tr>
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                </tr>
              <tr>
                <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                    <td align="left" class="formCellNosides"><span style="margin:11px">
                      <select name="INDEX" class="formText" id="INDEX">
                        <option value="ANY" selected="selected"><?php echo $lbl_material1_pl;?></option>
                        <?php
  $query="select index_v from exclusive group by index_v asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[index_v]\">";$name=stripslashes($listItem[index_v]);echo "$name</option>";}?>
                      </select>
                    </span></td>
                    <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_coating_txt;?></span></td>
                    <td align="left" class="formCellNosides"><span style="margin:11px">
                       <select name="COATING" class="formText" id="COATING">
                       <option value="ANY" selected="selected">ANY</option>
                      <option value="Hard Coat">Hard Coat</option>
                      <option value="AR">AR</option>
                      <option value="Uncoated">Uncoated</option>                    

                    
                       </select>
                    </span></td>
              </tr>
              <tr>
                <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_photochr_txt;?></span></td>
                <td align="left" class="formCellNosides"><span style="margin:11px"><select name="PHOTO" class="formText" id="PHOTO">
                        <option value="None" selected="selected"><?php echo $adm_none_txt;?></option>
                        <?php
  $query="select photo from exclusive group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 if ($listItem[photo]!="None"){
 echo "<option value=\"$listItem[photo]\">";
 $name=stripslashes($listItem[photo]);
 echo "$name</option>";}}?>
                      </select></span></td>
                <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_polarized_txt;?></span></td>
                <td align="left" class="formCellNosides"><span style="margin:11px"><select name="POLAR" class="formText" id="POLAR">
                        <option value="None" selected="selected"><?php echo $adm_none_txt;?></option>
                        <?php
  $query="select polar from exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 if ($listItem[polar]!="None"){
 echo "<option value=\"$listItem[polar]\">";
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}}?>
                      </select></span></td>
              </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
              <tr>
                <td colspan="9" bgcolor="#000099" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?></td>
              </tr>
              <tr>
                <td align="center" class="formCellNosides"><?php echo $lbl_pd_txt_pl;?><br />
                  <input name="RE_PD" type="text" class="formText" id="RE_PD" size="4" maxlength="4" />
                  <br />
                  <?php echo $adm_re_txt;?></td>
                <td align="left" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/lensnet/images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                <td align="center" class="formCellNoleft"><?php echo $lbl_pd_txt_pl;?><br />
                    <input name="LE_PD" type="text" class="formText" id="LE_PD" size="4" maxlength="4" />
                    <br />
<?php echo $adm_le_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo $adm_nearpd_txt;?><br />
                    <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" />
                    <br />
<?php echo $adm_pe_txt;?></td>
                <td align="center" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/lensnet/images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                <td align="center" class="formCellNoleft"><?php echo $adm_nearpd_txt;?><br />
                    <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" />
                    <br />
<?php echo $adm_le_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                    <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" size="4" maxlength="4" />
                    <br />
<?php echo $adm_re_txt;?></td>
                <td align="center" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/lensnet/images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                    <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" size="4" maxlength="4" />
                    <br />
<?php echo $adm_le_txt;?></td>
              </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
		      <tr>
		        <td colspan="6" bgcolor="#000099" class="tableHead"><?php echo $lbl_mywrldcoll_txt_pl;?></td>
		        </tr>
		      <tr>
		        <td align="right" class="formCell"><?php echo $lbl_pt_txt_pl;?></td>
		        <td align="left" class="formCellNosides">&nbsp;
		          <input name="PT" type="text" class="formText" id="PT" size="2" maxlength="2" />
		          <?php echo $lbl_pt1_pl;?></td>
		        <td align="right" class="formCell"><?php echo $lbl_pa_txt_pl;?></td>
		        <td align="left" class="formCellNosides"><input name="PA" type="text" class="formText" id="PA" size="2" maxlength="2" />
		          <?php echo $lbl_pa1_pl;?></td>
		        <td align="right" class="formCell"><?php echo $adm_vertex_txt;?></td>
		        <td align="left" class="formCellNosides"><input name="VERTEX" type="text" class="formText" id="VERTEX" size="2" maxlength="2" /><?php echo $lbl_vertex1_pl;?></td>
		        </tr>
		      </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
		      <tr>
                <td colspan="8" bgcolor="#000099" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?> </td>
              </tr>
              <tr>
                <td align="right" class="formCell"><?php echo $lbl_engrave_txt_pl;?> </td>
                <td align="left" class="formCellNosides">&nbsp;
                        <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" size="4" maxlength="8" /></td>
                <td align="right" class="formCell"><?php echo $adm_tint_txt;?></td>
                <td align="left" class="formCellNosides"><span style="margin:11px"><select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                  <option value="None" selected="selected"><?php echo $adm_none_txt;?></option>
                  <option value="Solid">Solid</option>
                  <option value="Gradient">Gradient</option>
                                                                  </select> </span></td>
                <td align="left" class="formCellNosides"><?php echo $adm_from_txt;?>
                  <input name="FROM_PERC" type="text" class="formText" id="FROM_PERC" size="4" maxlength="4" disabled />
%</td>
                <td align="left" class="formCellNosides"><?php echo $adm_to_txt;?>
                    <input name="TO_PERC" type="text" class="formText" id="TO_PERC" size="4" maxlength="4" disabled/ >
%</td>
                <td align="left" class="formCellNosides"><?php echo $adm_color_txt;?></td>
                <td align="left" class="formCellNosides"><span style="margin:11px">
                <select name="TINT_COLOR" disabled class="formText" id="TINT_COLOR">
                  <option value="Brown"><?php echo $adm_brwn_txt;?></option>
                  <option value="Gray"><?php echo $adm_gray_txt;?></option>
                </select>
                </span></td>
              </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
              <tr>
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_framespec_txt_pl;?></td>
              </tr>
                 <tr>
                <td colspan="4" align="left"class="formCell"><?php echo $adm_frmmodsel_txt;?> <b><?php echo $frameItem[model_num];?></b></td>
              </tr>
              <tr>
                <td align="right" class="formCell"><?php echo $adm_eyec_txt;?></td>
                <td align="left" class="formCellNosides"><?php echo $lbl_a_txt_pl;?>
                  &nbsp;
                  <input name="FRAME_A" type="text" class="formText" id="FRAME_A" size="4" maxlength="4" value="<?php echo $frameItem[frame_A];?>"/>
                  &nbsp;<?php echo $lbl_b_txt_pl;?>
                  <input name="FRAME_B" type="text" class="formText" id="FRAME_B" size="4" maxlength="4" value="<?php echo $frameItem[frame_B];?>"/>
                  &nbsp;&nbsp;<?php echo $lbl_ed_txt_pl;?>
                  <input name="FRAME_ED" type="text" class="formText" id="FRAME_ED" size="4" maxlength="4" value="<?php echo $frameItem[frame_ED];?>"/>
                  &nbsp;&nbsp;<?php echo $lbl_dbl_txt_pl;?>
                  <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL" size="4" maxlength="4"  value="<?php echo $frameItem[frame_DBL];?>"/>
                  &nbsp;<?php echo $adm_temple_txt;?>
                  <input name="TEMPLE" type="text" class="formText" id="TEMPLE" value="0" size="4" />
                  </td>
                <td align="right" class="formCell"><?php echo $adm_type_txt;?></td>
                <td align="left" class="formCell">
                  <input name="FRAME_TYPE" type="text" class="formText" id="FRAME_TYPE" size="10" maxlength="10"  value="Drill and Notch" readonly/>
                </td>
              </tr>
              <tr>
                <td colspan="4" align="center" class="formCell"><?php echo $adm_jobtype_txt;?>
                  
                  <input name="JOB_TYPE" type="text" class="formText" id="JOB_TYPE"  value="Edge and Mount" size="20" maxlength="20" readonly="readonly"/>
                  &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $adm_frame_txt;?>
                  &nbsp;&nbsp;
                  <input name="ORDER_TYPE" type="text" class="formText" id="ORDER_TYPE"  value="Provide" size="15" maxlength="15" readonly="readonly"/>                  &nbsp; </td>
              </tr>
              <tr>
                <td colspan="4" align="center" class="formCell"><input name="SUPPLIER" type="hidden" id="SUPPLIER" value="<?php echo $templeItem[collection_code]; ?>" />
                  <?php echo $adm_shpmod_txt;?>
                  <input name="FRAME_MODEL" type="text" class="formText" id="FRAME_MODEL" size="12" value="<?php echo $frameItem[model_num];?>" readonly />
&nbsp;&nbsp;<?php echo $adm_framemod_txt;?>
<input name="TEMPLE_MODEL" type="text" class="formText" id="TEMPLE_MODEL" size="12" value="<?php echo $templeItem[collection_code]; ?>" />
&nbsp;&nbsp;<?php echo $adm_color_txt;?>
<input name="COLOR" type="text" class="formText" id="COLOR" size="12" value="<?php echo $templeItem[frame_color]; ?>" readonly /></td>
              </tr>
            </table>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
             <tr>
                 <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt;?> </span></td>
                <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"></textarea></td>
              </tr>
            </table>
		    <div align="center" style="margin:11px">
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="Submit" type="submit" value="<?php echo $btn_submit_txt;?>" />
		    </div>
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