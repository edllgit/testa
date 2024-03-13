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


if($_SESSION["account_type"]=="restricted")
	{
	header("Location:order_history.php");
	}
	
  $queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysql_query($queryLab)	or die ("Could not select items");
  $DataLab=mysql_fetch_array($resultLab);
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

<?php include "js/prescription_form.js.inc.php";?>

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
<div id="rightColumn"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validate(this);">
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
 <?php if ($mylang == 'lang_english')
 		{	?>
<?php echo '<p class="header">Brands Available:</p>';?>
<?php  	}    	?>
   <p>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo1.jpeg" alt="Direct Lens" width="75"  />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo2.jpg" alt="Direct Lens" width="75" />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo3.jpg" alt="Direct Lens" width="75" />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo4.jpg" alt="Direct Lens" width="75" />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo5.jpg" alt="Direct Lens" width="75" />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo6.gif" alt="Direct Lens" width="75"  />&nbsp;&nbsp;&nbsp;
  <img src="http://www.direct-lens.com/lensnet/images/logo7.jpg" alt="Direct Lens" width="75"  />&nbsp;&nbsp;&nbsp;
  </p>   
              
              <div>
                <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
                  <tr >
                    <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_patient;?></td>
                    <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><?php echo $lbl_lname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_fname_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_refnum_preslenses;?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo 'Tray (Lab Only)';?>&nbsp;</td>
                    <td class="formCellNosides"><?php echo $lbl_slsperson_txt;?>&nbsp;</td>
                    </tr>
                  <tr >
                    <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" size="20" /></td>
                    <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME" size="20" /></td>
                    <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM" size="10" /></td>
                    <td class="formCellNosides"><input name="TRAY_NUM" type="text" class="formText" id="TRAY_NUM" size="8" /></td>
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
                 <tr >
                   <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_prescription_txt_pl;?>
                    
                       <input name="EYE" type="radio" value="Both"  onclick="ActivateAll_fields(this.form);" checked="checked" />
                       <?php echo $lbl_prescription1_pl;?> 
                         &nbsp;
                         <input name="EYE" type="radio" onclick="DesactivateLE_fields(this.form);" value="<?php echo $lbl_re_txt_pl;?>" />
                         <?php echo $lbl_prescription2_pl;?> 
                           <input name="EYE" type="radio" onclick="DesactivateRE_fields(this.form);" value="<?php echo $lbl_le_txt_pl;?>" />
                          <?php echo $lbl_prescription3_pl;?> </td>
                   </tr>
                 <tr>
                   <td colspan="2" valign="middle"  class="formCell">&nbsp;</td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_sphere_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo '<a target="_blank" title="Aide à la conversion/Help convert Cylinder"  href="http://www.dicoptic.izispot.com/conversions_785.htm#ConvCyl">' . $lbl_cylinder_txt_pl . '</a>';?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_axis_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCellNosides"><?php echo $lbl_addition_txt_pl;?></td>
                   <td align="center" valign="middle" class="formCell"><?php echo $lbl_prism_txt_pl;?></td>
                   </tr>
                 <tr >
                   <td align="center" valign="top"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/lensnet/images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                   <td align="right" valign="top"  class="formCell"><?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)" >
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
                     <option value="+4.00">+4.00</option>
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
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="Out" />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="RE_PR_IO" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" /><br />
                     <input name="RE_PR_UD" type="radio" value="Up" />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="Down" />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="RE_PR_UD" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="RE_PR_AX2" type="text" class="formText" id="RE_PR_AX2" size="4" maxlength="4" />
                     </td>
                   </tr>
                 <tr >
                   <td colspan="2" align="right" valign="top"class="formCell"><?php echo $lbl_le_txt_pl;?></td>
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
                     <option value="+4.00">+4.00</option>
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
                     <?php echo $lbl_in_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="Out" />
                     <?php echo $lbl_out_txt_pl;?>
                     <input name="LE_PR_IO" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" size="4" maxlength="4" /><br /><input name="LE_PR_UD" type="radio" value="Up" />
                     <?php echo $lbl_up_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="Down" />
                     <?php echo $lbl_down_txt_pl;?>
                     <input name="LE_PR_UD" type="radio" value="None" checked="checked" />
                     <?php echo $lbl_none_txt_pl;?><input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" size="4" maxlength="4" /></td>
                   </tr>
               </table>
             </div>
             
             
             <div id="spherechoice" style="display:none">
			<table width="770" align="center">
                 <tr >
                <td colspan="7" align="right" valign="top" bgcolor="#B4D7FF"class="formCell">
                <div style="width:500px; margin:0 auto;">
                <input name="incfile" type="radio" id="incfile" value="yes" checked="checked" onchange="changestate('on')" /> 
                  I would like to supply a lens profile file.
                  <input type="radio" name="incfile" id="incfile" value="no" onchange="changestate('off')"/>
                  I would like to supply a lens profile file later.
                  </div>
                  </td>
                </tr>
              <tr >
                <td colspan="7" align="right" valign="top" bgcolor="#FFFFFF"class="formCell">

               <div id="uploaderdiv" style="width:400px; margin:0 auto; text-align: center;">
                 <p>
                   <input type="file" name="myupload" id="myupload" size="40"></p>
				</div>
                </td></tr>               
              </table>
               </div>
            
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_material_txt_pl;?></span></td>
                   <td width="138" align="left" class="formCellNosides"><span style="margin:11px">
                    
                   <!--  <select name="INDEX" class="formText" id="INDEX">
                       <option value="ANY" selected="selected"><?php //echo $lbl_material1_pl;?></option>-->
                       <?php
 // $query="select index_v from exclusive group by index_v asc"; /* select all openings */
//$result=mysql_query($query)		or die ("Could not select items");
//$usercount=mysql_num_rows($result);
// while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[index_v]\">";$name=stripslashes($listItem[index_v]);echo "$name</option>";}?>
                     <!--  </select>-->
                     
                     
                     <select name="INDEX" class="formText" id="INDEX">  
                        <option value="ANY" selected="selected">ANY</option>
                        <option value="1.50">1.50</option>
                        <option value="1.52">1.52</option>
                        <option value="1.53">1.53</option>
                        <option value="1.54">1.54</option>
                        <option value="1.56">1.56</option>
                        <option value="1.57">1.57</option>
                        <option value="1.58">1.58</option>
                        <option value="1.59">1.59 Tintable</option>
                        <option value="1.592">1.59 Tegra</option>
                        <option value="1.60">1.60</option>
                        <option value="1.67">1.67</option>
                        <option value="1.70">1.70</option>
                        <option value="1.74">1.74</option>
                        <option value="1.80">1.80</option>
                        <option value="1.90">1.90</option>                      
                    </select>
                      
                     </span></td>
                   <td width="33" align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_coating_txt_pl;?></span></td>
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
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_photochro_txt_pl;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px"><select name="PHOTO" class="formText" id="PHOTO">
                     <option value="None" selected="selected"><?php echo $lbl_photochro1;?></option>
                     <?php
  $query="select photo from exclusive  where photo not in ('Yellow','Orange','Pink','Blue','Violet') group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 if ($listItem[photo]!="None"){
 echo "<option value=\"$listItem[photo]\">";
 $name=stripslashes($listItem[photo]);
 echo "$name</option>";}}?>
                     </select></span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $lbl_polarized_txt_pl;?></span></td>
                   <td width="157" align="left" class="formCellNosides"><span style="margin:11px"><select name="POLAR" class="formText" id="POLAR">
                     <option value="None" selected="selected"><?php echo $lbl_polarized1;?></option>
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
                   
                   
                   
                   
                     <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
				echo 'Catégorie de verres:';
				}else {
				echo 'Lens category';
				}
				?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                   
                   <?php if ($mylang == 'lang_french'){				?>
         		 <select name="lens_category">
                <option  value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>Tous</option> 
                <option  value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
               <option  value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                <option value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>Tous Progressifs</option>
                <option  value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressif Classique</option>
                <option  value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                <option  value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                </select>
                <?php 
				}else {
				?>
				 <select name="lens_category">
                <option  value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>All</option>
                <option  value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
               <option  value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                 <option value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>All Progressives</option>
                <option  value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressive Classic</option>
                <option  value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressive DS</option>
                <option  value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressive FF</option>
                 <option value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                </select>
				<?php
                }
				?>
                   
                   </span>
                   </td>
                   
                   
                       <td align="right" class="formCell"><span class="tableSubHead">
                    <?php if ($mylang == 'lang_french'){
				echo 'Prioritaire';
				}else {
				echo 'Rush';
				}
				?></span></td>
                   <td align="left" width="157" align="left" class="formCellNosides">
                  <span style="margin:11px">  <select name="rush" class="formText" id="rush">
                    	<option selected="selected" value="no" >No</option>
                      	<option value="yes">Yes</option>                    
					</select></span>
                   </td>
                   </tr>
                   
                   
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#000099" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="left" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_pd_txt_pl;?><br />
                     <input name="LE_PD" type="text" class="formText" id="LE_PD" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="RE_PD_NEAR" type="text" class="formText" id="RE_PD_NEAR" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $lbl_nearpd_txt_pl;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_re_txt_pl;?></td>
                   <td align="center" class="formCellNosides"><img src="http://www.direct-lens.com/lensnet/images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $lbl_height_txt_pl;?><br />
                     <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" size="4" maxlength="4" />
                     <br />
                     <?php echo $lbl_le_txt_pl;?></td>
                   </tr>
               </table>
             </div>
   

             
              <div>
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
                      <option value="0" selected="selected">None</option>
                      <option value="1">1 year (6$ extra)</option>
                      <option value="2">2 years (10$ extra)</option> 
                <?php /*?>      <option value="2 complete">2 years Without Restriction (23.50$ extra)</option>  <?php */?>         
                     <?php /*?> <option value="extension">Extension Optics (40$ extra)</option>         <?php */?>
                     </select>
                     </span></td>
                 </tr>
               </table>
             </div> 
             
             
             
             
           <?php /*?>    
              <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				<?php if ($mylang == 'lang_french'){
				echo 'Base Spéciale';
				}else {
				echo 'Special Base';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="BASE8" class="formText" id="BASE8">
                      <option value="no" selected="selected">No</option>
                      <option value="yes">Base 8 (30$ extra)</option>
                     </select>
                     </span></td>
                 </tr>
               </table>
             </div> <?php */?>
             
             
             
       <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">    
				   <?php if ($mylang == 'lang_french'){
				echo 'Frais d\'entrée de données';
				}else {
				echo 'Data entry fee';
				}
				?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCellNosides"><span style="margin:11px">   
                    <?php if ($mylang == 'lang_french'){
				echo '2$ Frais d\'entrée de données';
				}else {
				echo '2$ Data entry fee';
				}
				?>
                 <input name="entry_fee" id="entry_fee" type="checkbox" value="yes" />  </span></td>
                 </tr>
               </table>
             </div> 
             
   
             
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#000099" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $lbl_engrav_txt_pl;?> </td>
                   <td align="left" class="formCellNosides">&nbsp;
                     <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" size="4" maxlength="8" /></td>
                   <td align="right" class="formCell"><?php echo $lbl_tint_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px"><select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                     <option value="None" selected="selected"><?php echo $lbl_tint1_pl;?></option>
                     <option value="Solid"><?php echo $lbl_tint2_pl;?></option>
                     <option value="Gradient"><?php echo $lbl_tint3_pl;?></option>
                     </select> </span></td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_from_txt_pl;?>
                     <input name="FROM_PERC" type="text" class="formText" id="FROM_PERC" size="4" maxlength="4" disabled />
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_to_txt_pl;?>
                     <input name="TO_PERC" type="text" class="formText" id="TO_PERC" size="4" maxlength="4" disabled/ >
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $lbl_color_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR" disabled class="formText" id="TINT_COLOR">
                       <option value="<?php echo $lbl_color1_pl;?>"><?php echo $lbl_color1_pl;?></option>
                       <option value="<?php echo $lbl_color2_pl;?>"><?php echo $lbl_color2_pl;?></option>
                       <option value="Brown">Brown</option>
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
                   <td align="right" class="formCell"><?php echo $lbl_eye_txt_pl;?></td>
                   <td align="left" class="formCellNosides">                <?php echo $lbl_a_txt_pl;?>
                     &nbsp;
                     <input name="FRAME_A" type="text" class="formText" id="FRAME_A" size="4" maxlength="4" />
                     &nbsp;
                     
                     <?php echo $lbl_b_txt_pl;?>
                     <input name="FRAME_B" type="text" class="formText" id="FRAME_B" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_ed_txt_pl;?>
                     <input name="FRAME_ED" type="text" class="formText" id="FRAME_ED" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $lbl_dbl_txt_pl;?>
                     <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL" size="4" maxlength="4" />
                     &nbsp;<?php echo $lbl_temple_txt_pl;?>
                     <input name="TEMPLE" type="text" class="formText" id="TEMPLE" value="0" size="4" />
                     </td>
                   <td align="right" class="formCell"><?php echo $lbl_type_txt_pl;?>&nbsp;</td>
                   <td align="left" class="formCell">
                     <select name="FRAME_TYPE" class="formText"  id="FRAME_TYPE">
                       <option value=""><?php echo $lbl_type1_pl;?></option>
                       <option value="Nylon Groove"><?php echo $lbl_type2_pl;?></option>
                       <option value="Metal Groove"><?php echo $lbl_type3_pl;?></option>
                       <option value="Plastic"><?php echo $lbl_type4_pl;?></option>
                       <option value="Metal"><?php echo $lbl_type5_pl;?></option>
                       <option value="Edge Polish"><?php echo $lbl_type6_pl;?></option>
                       <option value="Drill and Notch"><?php echo $lbl_type7_pl;?></option>
                       </select>                    </td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $lbl_jobtype_txt_pl;?>
                     <select name="JOB_TYPE" class="formText" d="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Uncut"><?php echo $lbl_jobtype1_pl;?></option>
                       <option value="Edge and Mount"><?php echo $lbl_jobtype2_pl;?></option>
                       </select>
                     &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_frame_txt_pl;?>
                     <select name="ORDER_TYPE" class="formText" id="ORDER_TYPE" disabled="disabled">
                       <option value="To Follow"><?php echo $lbl_frame1_pl;?></option>
                       <option value="Provide" selected="selected" ><?php echo $lbl_frame2_pl;?></option>
                       </select>
                     &nbsp;&nbsp;&nbsp; </td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell">&nbsp; 
                     <?php echo $lbl_supplier_txt_pl;?> 
                     <input name="SUPPLIER" type="text" class="formText" id="SUPPLIER" size="12"  disabled/>
                     &nbsp;&nbsp;<?php echo $lbl_shapemodel_txt_pl;?> 
                     <input name="FRAME_MODEL" type="text" class="formText" id="FRAME_MODEL" size="12" disabled/>
                     </span> &nbsp;&nbsp;&nbsp;<?php echo $lbl_framemodel_txt_pl;?>
                     <input name="TEMPLE_MODEL" type="text" class="formText" id="TEMPLE_MODEL" size="12" disabled/>                  
                     &nbsp;&nbsp;&nbsp;<?php echo $lbl_color_txt_pl;?> 
                     <input name="COLOR" type="text" class="formText" id="COLOR" size="12"disabled />             </td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $lbl_specinstr_txt_pl;?>  </span></td>
                   <td width="502" valign="top"  class="tableSubHead">             
                 <?php
				 $querySpecialInst = "SELECT allow_special_instruction from accounts  WHERE user_id = '". $_SESSION["sessionUser_Id"] . "'";
				 echo  $querySpecialInst;
				 exit();
				 $resultSpecialInst=mysql_query($querySpecialInst)	or die ("Could not select items");
  				 $DataSpecialInst=mysql_fetch_array($resultSpecialInst);
				 
				 
				 if ($DataSpecialInst[allow_special_instruction]=='no') 
				 {?>
                     <textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS">
                    <?php
				        if ($mylang == 'lang_french') {
                        echo 'Toutes les demandes spéciales doivent passer par les produits Direct-Lens ou téléphonez à votre service à la clientèle si besoin.';
                        }else{
                        echo 'All special request need to go through directlens products or call your customers services if needed.<br>';
                        }?>
                 </textarea>
			<?php }else{ ?>
				 <textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"></textarea>
            <?php } ?>
               </td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4"  bgcolor="#000099" class="tableHead">
                     <?php if ($mylang == 'lang_french'){
				echo 'NOTE INTERNE';
				}else {
				echo 'INTERNAL NOTE';
				}
				?>
                     </td>
                   </tr>
                 <tr>
                   <td width="134" align="center" valign="top" class="formCell"><span class="tableSubHead">
                     <?php if ($mylang == 'lang_french'){
				echo 'Note interne';
				}else {
				echo 'Internal note';
				}
				?></span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="INTERNAL_NOTE" cols="70" rows="2" class="formText" id="INTERNAL_NOTE"></textarea></td>
                   </tr>
               </table>
             </div>
			
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
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