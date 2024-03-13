<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "Connections/directlens.php";
include "includes/getlang.php";
global $drawme;
require_once "upload/phpuploader/include_phpuploader.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");
	
	require('Connections/sec_connect.inc.php');
		
$query="select index_v from exclusive group by index_v asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);

$queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
$resultLab=mysql_query($queryLab)	or die ("Could not select items");
$DataLab=mysql_fetch_array($resultLab);
$LabNum=$DataLab[main_lab];	

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens - Prescription Search</title>
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
<link href="../dl.css" rel="stylesheet" type="text/css" />

<script language="JavaScript" type="text/javascript">

function setEnabled(theForm){
	
	//FIX JOB TYPE PULLDOWN
	
	if(theForm.JOB_TYPE.value=="Uncut"){//Uncut
		theForm.SUPPLIER.disabled=true;
		theForm.FRAME_MODEL.disabled=true;
		theForm.TEMPLE_MODEL.disabled=true;
		theForm.COLOR.disabled=true;
		theForm.TEMPLE.disabled=true;
		theForm.ORDER_TYPE.disabled=true;
		
		theForm.SUPPLIER.value="";
		theForm.FRAME_MODEL.value="";
		theForm.TEMPLE_MODEL.value="";
		theForm.COLOR.value="";
		theForm.EYE_SIZE.value="";
		theForm.BRIDGE_SIZE.value="";
		theForm.TEMPLE.value="";
	}
	else if(theForm.JOB_TYPE.value=="Edge and Mount"){//Edge and Mount
		theForm.SUPPLIER.disabled=false;
		theForm.FRAME_MODEL.disabled=false;
		theForm.TEMPLE_MODEL.disabled=false;
		theForm.COLOR.disabled=false;
		theForm.TEMPLE.disabled=false;
		theForm.ORDER_TYPE.disabled=false;
	}
	
	//FIX TINT PULLDOWN
	
	if(theForm.TINT.value=="None"){//NONE TINT
		theForm.TO_PERC.disabled=true;
		theForm.FROM_PERC.disabled=true;
		theForm.TINT_COLOR.disabled=true;
		
		theForm.TO_PERC.value="";
		theForm.FROM_PERC.value="";
	}
	else if(theForm.TINT.value=="Solid"){//SOLID TINT
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
		theForm.TO_PERC.disabled=true;
		
		theForm.TO_PERC.value="";
	}
	else if(theForm.TINT.value=="Gradient"){//GRADIENT TINT
		theForm.TO_PERC.disabled=false;
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
	}

}

function updateTINT(theForm)
{
	if(theForm.TINT.value=="None"){//NONE TINT
		theForm.TO_PERC.disabled=true;
		theForm.FROM_PERC.disabled=true;
		theForm.TINT_COLOR.disabled=true;
		
		theForm.TO_PERC.value="";
		theForm.FROM_PERC.value="";
	}
	else 	if(theForm.TINT.value=="Solid"){//SOLID TINT
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
		theForm.TO_PERC.disabled=true;
		
		theForm.TO_PERC.value="";
	}
	else if(theForm.TINT.value=="Gradient"){//GRADIENT TINT
		theForm.TO_PERC.disabled=false;
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
	}
}
 function updateJOB_TYPE(theForm)
{
	if(theForm.JOB_TYPE.value=="Uncut"){//Uncut
		theForm.SUPPLIER.disabled=true;
		theForm.FRAME_MODEL.disabled=true;
		theForm.TEMPLE_MODEL.disabled=true;
		theForm.COLOR.disabled=true;
		theForm.TEMPLE.disabled=true;
		theForm.ORDER_TYPE.disabled=true;
		theForm.SUPPLIER.value="";
		theForm.FRAME_MODEL.value="";
		theForm.TEMPLE_MODEL.value="";
		theForm.COLOR.value="";
		theForm.TEMPLE.value="";
	}
	else 	if(theForm.JOB_TYPE.value=="Edge and Mount"){//Edge and Mount
		theForm.SUPPLIER.disabled=false;
		theForm.FRAME_MODEL.disabled=false;
		theForm.TEMPLE_MODEL.disabled=false;
		theForm.COLOR.disabled=false;
		theForm.TEMPLE.disabled=false;
		theForm.ORDER_TYPE.disabled=false;
	}
}

function validate(theForm){
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){//SOLID TINT
    alert("<?php echo $lbl_alert1_pl;?>");
   theForm.FROM_PERC.focus();
    return (false);
}

if((theForm.TINT.value=="GRADIENT")&&((theForm.FROM_PERC.value=="")||(theForm.TO_PERC.value==""))){//SOLID TINT
    alert("<?php echo $lbl_alert2_pl;?>");
   theForm.FROM_PERC.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.SUPPLIER.value=="")){
 alert("<?php echo $lbl_alert3_pl;?>");
   theForm.SUPPLIER.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.FRAME_MODEL.value=="")){
 alert("<?php echo $lbl_alert4_pl;?>");
   theForm.FRAME_MODEL.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.TEMPLE_MODEL.value=="")){
 alert("<?php echo $lbl_alert5_pl;?>");
   theForm.TEMPLE_MODEL.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.COLOR.value=="")){
 alert("<?php echo $lbl_alert6_pl;?>");
   theForm.COLOR.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.TEMPLE.value=="")){
 alert("<?php echo $lbl_alert7_pl;?>");
   theForm.TEMPLE.focus();
    return (false);
}

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>10.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX.focus();
    return (false);
  }
   if ((theForm.RE_PR_AX2.value!="")&&((theForm.RE_PR_AX2.value<.1)||(theForm.RE_PR_AX2.value>10.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX2.focus();
    return (false);
  }
   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>10.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR_AX.focus();
    return (false);
  }
  
   if ((theForm.LE_PR_AX2.value!="")&&((theForm.LE_PR_AX2.value<.1)||(theForm.LE_PR_AX2.value>10.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR2_AX.focus();
    return (false);
  }
  
  if ((theForm.PT.value!="")&&((theForm.PT.value<1)||(theForm.PT.value>20)))
  {
    alert("<?php echo $lbl_alert9_pl;?>");
    theForm.PT.focus();
    return (false);
  }
  
    if ((theForm.PA.value!="")&&((theForm.PA.value<0)||(theForm.PA.value>25)))
  {
    alert("<?php echo $lbl_alert10_pl;?>");
    theForm.PA.focus();
    return (false);
  }
  
      if ((theForm.VERTEX.value!="")&&((theForm.VERTEX.value<0)||(theForm.VERTEX.value>20)))
  {
    alert("<?php echo $lbl_alert11_pl;?>");
    theForm.VERTEX.focus();
    return (false);
  }
  
 if ((theForm.FRAME_B.value*2)<(theForm.RE_HEIGHT.value*2))
  {
    alert("<?php echo $lbl_alert12_pl;?>");
    theForm.FRAME_B.focus();
    return (false);
  }
  
   if ((theForm.FRAME_B.value*2)<(theForm.LE_HEIGHT.value*2))
  {
    alert("<?php echo $lbl_alert13_pl;?>");
    theForm.FRAME_B.focus();
    return (false);
  }
  
if ((theForm.RE_AXIS.value== "")&&(((theForm.RE_CYL_NUM.value!="+0")&&(theForm.RE_CYL_NUM.value!="-0"))||(theForm.RE_CYL_DEC.value!=".00")))
  {
    alert("<?php echo $lbl_alert14_pl;?>");
    theForm.RE_AXIS.focus();
    return (false);
  }
    if ((theForm.LE_AXIS.value== "")&&(((theForm.LE_CYL_NUM.value!="+0")&&(theForm.LE_CYL_NUM.value!="-0"))||(theForm.LE_CYL_DEC.value!=".00")))
  {
    alert("<?php echo $lbl_alert15_pl;?>");
    theForm.LE_AXIS.focus();
    return (false);
  }
  
   if (theForm.INDEX.value== "")
  {
    alert("<?php echo $lbl_alert16_pl;?>");
    theForm.INDEX.focus();
    return (false);
  }

     if (theForm.RE_PD.value== "")
  {
    alert("<?php echo $lbl_alert17_pl;?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
 if (theForm.LE_PD.value== "")
  {
    alert("<?php echo $lbl_alert18_pl;?>");
    theForm.LE_PD.focus();
    return (false);
  }
  
    if ((theForm.RE_HEIGHT.value=="")&&(theForm.RE_ADD.value!="+0.00"))
  {
    alert("<?php echo $lbl_alert19_pl;?>");
    theForm.RE_HEIGHT.focus();
    return (false);
  }
  
 if ((theForm.LE_HEIGHT.value=="")&&(theForm.LE_ADD.value!="+0.00"))
  {
    alert("<?php echo $lbl_alert20_pl;?>");
    theForm.LE_HEIGHT.focus();
    return (false);
  }
  
   if (theForm.FRAME_A.value== "")
  {
    alert("<?php echo $lbl_alert21_pl;?>");
    theForm.FRAME_A.focus();
    return (false);
  }  
   if (theForm.FRAME_B.value== "")
  {
    alert("<?php echo $lbl_alert22_pl;?>");
    theForm.FRAME_B.focus();
    return (false);
  }  
   if (theForm.FRAME_ED.value== "")
  {
    alert("<?php echo $lbl_alert23_pl;?>");
    theForm.FRAME_ED.focus();
    return (false);
  }  
   if (theForm.FRAME_DBL.value== "")
  {
    alert("<?php echo $lbl_alert24_pl;?>');");
    theForm.FRAME_DBL.focus();
    return (false);
  }
  
   if (theForm.FRAME_TYPE.value== "")
  {
    alert("<?php echo $lbl_alert25_pl;?>");
    theForm.FRAME_TYPE.focus();
    return (false);
  }
  
}	

  
function validateRE_Axis(text) {
	var num=parseFloat(text.value);
	if (text.value=="") {
		return;
	}

	if (isNaN(num)) {
	
		alert("Please enter a numeric value.");
		focus();
		select();
		window.event.returnValue=false;
		return;
	}else if ((num>180)||(num<1)) {
		alert("Please enter a number between 001 and 180.");
		text.focus();
		text.select();
		window.event.returnValue=false;
		return;
	}
	text.value = parseInt(num)
}

function fixRE_SPH(form){//disable decimal if value is high or low

	if ((form.RE_SPH_NUM.value=="+14")||(form.RE_SPH_NUM.value=="-15")){
		form.RE_SPH_DEC.selectedIndex=3;
			form.RE_SPH_DEC.disabled=true;
			}
	else{
		form.RE_SPH_DEC.disabled=false;
			}
	}
function fixLE_SPH(form){//disable decimal if value is high or low

	if ((form.LE_SPH_NUM.value=="+14")||(form.LE_SPH_NUM.value=="-15")){
		form.LE_SPH_DEC.selectedIndex=3;
			form.LE_SPH_DEC.disabled=true;
			}
	else{
		form.LE_SPH_DEC.disabled=false;
			}
	}
function fixRE_CYL(form){//disable decimal if value is high or low

	if ((form.RE_CYL_NUM.value=="+6")||(form.RE_CYL_NUM.value=="-8")){
		form.RE_CYL_DEC.selectedIndex=3;
			form.RE_CYL_DEC.disabled=true;
			}
	else{
		form.RE_CYL_DEC.disabled=false;
			}
	}
function fixLE_CYL(form){//disable decimal if value is high or low

	if ((form.LE_CYL_NUM.value=="+6")||(form.LE_CYL_NUM.value=="-8")){
		form.LE_CYL_DEC.selectedIndex=3;
			form.LE_CYL_DEC.disabled=true;
			}
	else{
		form.LE_CYL_DEC.disabled=false;
			}
	}
function copyRE() { //v2.0
document.PRESCRIPTION.LE_SPH_NUM.selectedIndex=document.PRESCRIPTION.RE_SPH_NUM.selectedIndex;
document.PRESCRIPTION.LE_SPH_DEC.selectedIndex=document.PRESCRIPTION.RE_SPH_DEC.selectedIndex;
document.PRESCRIPTION.LE_CYL_NUM.selectedIndex=document.PRESCRIPTION.RE_CYL_NUM.selectedIndex;
document.PRESCRIPTION.LE_CYL_DEC.selectedIndex=document.PRESCRIPTION.RE_CYL_DEC.selectedIndex;
document.PRESCRIPTION.LE_ADD.selectedIndex=document.PRESCRIPTION.RE_ADD.selectedIndex;
 fixLE_SPH(document.PRESCRIPTION);
 fixLE_CYL(document.PRESCRIPTION);
 
 	for (i=0;i<3;i++){
		if (document.PRESCRIPTION.RE_PR_IO[i].checked==true){
		document.PRESCRIPTION.LE_PR_IO[i].checked=true;}
		}
	
	for (i=0;i<3;i++){
		if (document.PRESCRIPTION.RE_PR_UD[i].checked==true){
		document.PRESCRIPTION.LE_PR_UD[i].checked=true;}
		}
		
	document.PRESCRIPTION.LE_PR_AX.value=document.PRESCRIPTION.RE_PR_AX.value;
	document.PRESCRIPTION.LE_PR_AX2.value=document.PRESCRIPTION.RE_PR_AX2.value;
	document.PRESCRIPTION.LE_AXIS.value=document.PRESCRIPTION.RE_AXIS.value;
	
}

function removefile(){
window.location = "removeprofile.php";
}
	</script>
</head>

<body onload="setEnabled(PRESCRIPTION)">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td width="215" valign="top"><div id="leftColumn"><?php 
	include("includes/sideNav.inc.php");
	?></div></td>
    <td width="685" valign="top"><form action="prescriptionList.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION"   onSubmit="return validate(this)"><div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_user_txt;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;</div>
              <div>
		     <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
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
                <td class="formCellNosides"><input name="LAST_NAME" type="text" class="formText" id="LAST_NAME" value="<?php print $_SESSION['PrescrData']['LAST_NAME'];?>" size="20" /></td>
                <td class="formCellNosides"><input name="FIRST_NAME" type="text" class="formText" id="FIRST_NAME"  value="<?php print $_SESSION['PrescrData']['FIRST_NAME'];?>" size="20" /></td>
                <td class="formCellNosides"><input name="PATIENT_REF_NUM" type="text" class="formText" id="PATIENT_REF_NUM"  value="<?php print $_SESSION['PrescrData']['PATIENT_REF_NUM'];?>" size="10" /></td>
                <td class="formCellNosides"><input name="TRAY_NUM" type="text" class="formText" id="TRAY_NUM"  value="<?php print $_SESSION['PrescrData']['TRAY_NUM'];?>" size="8" /></td>
                <td class="formCellNosides"><select name="SALESPERSON_ID" class="formText" id="SALESPERSON_ID">
                  <option value="" selected="selected"><?php echo $lbl_none_txt_pl;?></option>
                  <?php
						$user_id=$_SESSION["sessionUser_Id"];
  $query="select sales_id,first_name,last_name from salespeople WHERE acct_user_id='$user_id' AND removed!='Yes' ORDER by last_name,first_name"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 print "<option value=\"$listItem[sales_id]\"";
 
 if ($listItem[sales_id]==$_SESSION['PrescrData']['SALESPERSON_ID']){
 	print "selected=\"selected\"";}
 
 print ">";
 $name=stripslashes($listItem[first_name])." ".stripslashes($listItem[last_name]);
 print "$name</option>";}?>
                </select></td>
              </tr>
		     </table></div>
             <div>
				<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_mast1;?>
                  <span class="formCell">
                  <input name="EYE" type="radio" value="Both" <?php if ($_SESSION['PrescrData']['EYE']=="Both") print "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription1_pl;?> <span class="formCell"> &nbsp;
                  <input name="EYE" type="radio" value="R.E." <?php if ($_SESSION['PrescrData']['EYE']=="R.E.") print "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription2_pl;?>&nbsp; <span class="formCell">
                  <input name="EYE" type="radio" value="L.E." <?php if ($_SESSION['PrescrData']['EYE']=="L.E.") print "checked=\"checked\"";?>/>
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
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
                <td align="right" valign="top"  class="formCell"><?php echo $adm_re_txt;?></td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_SPH_NUM" class="formText" id="RE_SPH_NUM" onchange="fixRE_SPH(this.form)">
                    <option value="+14"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+14") print "selected=\"selected\"";?>>+14</option>
                        <option value="+13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+13") print "selected=\"selected\"";?>>+13</option>
                  <option value="+12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+12") print "selected=\"selected\"";?>>+12</option>
                  <option value="+11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+11") print "selected=\"selected\"";?>>+11</option>
                  <option value="+10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+10") print "selected=\"selected\"";?>>+10</option>
                  <option value="+9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+9") print "selected=\"selected\"";?>>+9</option>
                  <option value="+8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+8") print "selected=\"selected\"";?>>+8</option>
				  <option value="+7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+7") print "selected=\"selected\"";?>>+7</option>
				  <option value="+6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+6") print "selected=\"selected\"";?>>+6</option>
				  <option value="+5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+5") print "selected=\"selected\"";?>>+5</option>
				  <option value="+4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+4") print "selected=\"selected\"";?>>+4</option>
				  <option value="+3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+3") print "selected=\"selected\"";?>>+3</option>
				  <option value="+2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+2") print "selected=\"selected\"";?>>+2</option>
				  <option value="+1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+1") print "selected=\"selected\"";?>>+1</option>
				  <option value="+0"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="+0") print "selected=\"selected\"";?>>+0</option>
				  <option value="-0"<?php if (($_SESSION['PrescrData']['RE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_SPH_NUM'])<2)) print "selected=\"selected\"";?>>-0</option>
				  <option value="-1"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-1") print "selected=\"selected\"";?>>-1</option>
				  <option value="-2"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-2") print "selected=\"selected\"";?>>-2</option>
				  <option value="-3"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-3") print "selected=\"selected\"";?>>-3</option>
				  <option value="-4"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-4") print "selected=\"selected\"";?>>-4</option>
				  <option value="-5"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-5") print "selected=\"selected\"";?>>-5</option>
				  <option value="-6"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-6") print "selected=\"selected\"";?>>-6</option>
				  <option value="-7"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-7") print "selected=\"selected\"";?>>-7</option>
				  <option value="-8"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-8") print "selected=\"selected\"";?>>-8</option>
				  <option value="-9"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-9") print "selected=\"selected\"";?>>-9</option>
				  <option value="-10"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-10") print "selected=\"selected\"";?>>-10</option>
				  <option value="-11"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-11") print "selected=\"selected\"";?>>-11</option>
				  <option value="-12"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-12") print "selected=\"selected\"";?>>-12</option>
				  <option value="-13"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-13") print "selected=\"selected\"";?>>-13</option>
				  <option value="-14"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-14") print "selected=\"selected\"";?>>-14</option>
				  <option value="-15"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-15") print "selected=\"selected\"";?>>-15</option>
                  </select>
                  <select name="RE_SPH_DEC" class="formText" id="RE_SPH_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".75") print "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".50") print "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['RE_SPH_DEC']==".25") print "selected=\"selected\"";?>>.25</option>
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['RE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_SPH_DEC'])<2)) print "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_CYL_NUM" class="formText" id="RE_CYL_NUM" onchange="fixRE_CYL(this.form)">
                  <option value="-0" <?php if (($_SESSION['PrescrData']['RE_CYL_NUM']==="-0")||(strlen($_SESSION['PrescrData']['RE_CYL_NUM'])<2)) print "selected=\"selected\"";?>>-0</option>
                  <option value="-1"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-1") print "selected=\"selected\"";?>>-1</option>
                  <option value="-2"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-2") print "selected=\"selected\"";?>>-2</option>
                  <option value="-3"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-3") print "selected=\"selected\"";?>>-3</option>
                  <option value="-4"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-4") print "selected=\"selected\"";?>>-4</option>
                  <option value="-5"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-5") print "selected=\"selected\"";?>>-5</option>
                  <option value="-6"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-6") print "selected=\"selected\"";?>>-6</option>
                  <option value="-7"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-7") print "selected=\"selected\"";?>>-7</option>
                  <option value="-8"<?php if ($_SESSION['PrescrData']['RE_CYL_NUM']=="-8") print "selected=\"selected\"";?>>-8</option>
                </select>
                  <select name="RE_CYL_DEC" class="formText" id="RE_CYL_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".75") print "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".50") print "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['RE_CYL_DEC']==".25") print "selected=\"selected\"";?>>.25</option>
                    <option value=".00" <?php if (($_SESSION['PrescrData']['RE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['RE_CYL_DEC'])< 2)) print "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="RE_AXIS" type="text" class="formText" id="RE_AXIS" size="4" maxlength="3" 
                value="<?php	if ($_SESSION['PrescrData']['RE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['RE_AXIS'];
				 ?>"
                 onchange="validateRE_Axis(this)" />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="RE_ADD" class="formText" id="RE_ADD">
                  <option value="+3.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.50") print "selected=\"selected\"";?>>+3.50</option>
                  <option value="+3.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.25") print "selected=\"selected\"";?>>+3.25</option>
                  <option value="+3.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+3.00") print "selected=\"selected\"";?>>+3.00</option>
                  <option value="+2.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.75") print "selected=\"selected\"";?>>+2.75</option>
                  <option value="+2.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.50") print "selected=\"selected\"";?>>+2.50</option>
                  <option value="+2.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.25") print "selected=\"selected\"";?>>+2.25</option>
                  <option value="+2.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+2.00") print "selected=\"selected\"";?>>+2.00</option>
                  <option value="+1.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.75") print "selected=\"selected\"";?>>+1.75</option>
                  <option value="+1.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.50") print "selected=\"selected\"";?>>+1.50</option>
                  <option value="+1.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.25") print "selected=\"selected\"";?>>+1.25</option>
                  <option value="+1.00"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+1.00") print "selected=\"selected\"";?>>+1.00</option>
                  <option value="+0.75"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.75") print "selected=\"selected\"";?>>+0.75</option>
                  <option value="+0.50"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.50") print "selected=\"selected\"";?>>+0.50</option>
                  <option value="+0.25"<?php if ($_SESSION['PrescrData']['RE_ADD']=="+0.25") print "selected=\"selected\"";?>>+0.25</option>
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['RE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['RE_ADD'])< 2)) print "selected=\"selected\"";?>>+0.00</option>
                    </select></td>
                <td align="right" valign="top"class="formCell"><input name="RE_PR_IO" type="radio" value="In" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='In') print 'checked="checked"';?>/>
<?php echo $adm_in_txt;?>&nbsp;<input name="RE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='Out') print 'checked="checked"';?>/><?php echo $adm_out_txt;?>
<input name="RE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_IO']=='None') print 'checked="checked"';?>/> 
<?php echo $adm_none_txt;?>

<input name="RE_PR_AX" type="text" class="formText" id="RE_PR_AX" size="4" maxlength="4" 
value="<?php	if ($_SESSION['PrescrData']['RE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['RE_PR_AX'];
				 ?>" 
/><br /><input name="RE_PR_UD" type="radio" value="Up" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Up') print 'checked="checked"';?>/><?php echo $adm_up_txt;?>&nbsp;<input name="RE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='Down') print 'checked="checked"';?>/><?php echo $adm_down_txt;?>
<input name="RE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['RE_PR_UD']=='None') print 'checked="checked"';?>/> 
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
                <option value="+14"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+14") print "selected=\"selected\"";?>>+14</option>
                <option value="+13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+13") print "selected=\"selected\"";?>>+13</option>
			 <option value="+12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+12") print "selected=\"selected\"";?>>+12</option>
                  <option value="+11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+11") print "selected=\"selected\"";?>>+11</option>
                  <option value="+10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+10") print "selected=\"selected\"";?>>+10</option>
                  <option value="+9"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+9") print "selected=\"selected\"";?>>+9</option>
                  <option value="+8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+8") print "selected=\"selected\"";?>>+8</option>
				  <option value="+7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+7") print "selected=\"selected\"";?>>+7</option>
				  <option value="+6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+6") print "selected=\"selected\"";?>>+6</option>
				  <option value="+5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+5") print "selected=\"selected\"";?>>+5</option>
				  <option value="+4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+4") print "selected=\"selected\"";?>>+4</option>
				  <option value="+3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+3") print "selected=\"selected\"";?>>+3</option>
				  <option value="+2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+2") print "selected=\"selected\"";?>>+2</option>
				  <option value="+1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+1") print "selected=\"selected\"";?>>+1</option>
				  <option value="+0"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="+0") print "selected=\"selected\"";?>>+0</option>
				  <option value="-0"<?php if (($_SESSION['PrescrData']['LE_SPH_NUM']==="-0")||(strlen($_SESSION['PrescrData']['LE_SPH_NUM'])<2)) print "selected=\"selected\"";?>>-0</option>
				  <option value="-1"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-1") print "selected=\"selected\"";?>>-1</option>
				  <option value="-2"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-2") print "selected=\"selected\"";?>>-2</option>
				  <option value="-3"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-3") print "selected=\"selected\"";?>>-3</option>
				  <option value="-4"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-4") print "selected=\"selected\"";?>>-4</option>
				  <option value="-5"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-5") print "selected=\"selected\"";?>>-5</option>
				  <option value="-6"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-6") print "selected=\"selected\"";?>>-6</option>
				  <option value="-7"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-7") print "selected=\"selected\"";?>>-7</option>
				  <option value="-8"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-8") print "selected=\"selected\"";?>>-8</option>
				  <option value="-9"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-9") print "selected=\"selected\"";?>>-9</option>
				  <option value="-10"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-10") print "selected=\"selected\"";?>>-10</option>
				  <option value="-11"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-11") print "selected=\"selected\"";?>>-11</option>
				  <option value="-12"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-12") print "selected=\"selected\"";?>>-12</option>
				  <option value="-13"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-13") print "selected=\"selected\"";?>>-13</option>
				  <option value="-14"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-14") print "selected=\"selected\"";?>>-14</option>
				  <option value="-15"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-15") print "selected=\"selected\"";?>>-15</option>
                  </select>
                  <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".75") print "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".50") print "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".25") print "selected=\"selected\"";?>>.25</option>
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['LE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_SPH_DEC'])<2)) print "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">   
                  <option value="-0" <?php if (($_SESSION['PrescrData']['LE_CYL_NUM']==="-0")||($_SESSION['PrescrData']['LE_CYL_NUM']=="–")) print "selected=\"selected\"";?>>-0</option>
                  <option value="-1"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-1") print "selected=\"selected\"";?>>-1</option>
                  <option value="-2"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-2") print "selected=\"selected\"";?>>-2</option>
                  <option value="-3"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-3") print "selected=\"selected\"";?>>-3</option>
                  <option value="-4"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-4") print "selected=\"selected\"";?>>-4</option>
                  <option value="-5"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-5") print "selected=\"selected\"";?>>-5</option>
                  <option value="-6"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-6") print "selected=\"selected\"";?>>-6</option>
                  <option value="-7"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-7") print "selected=\"selected\"";?>>-7</option>
                  <option value="-8"<?php if ($_SESSION['PrescrData']['LE_CYL_NUM']=="-8") print "selected=\"selected\"";?>>-8</option>
                </select>
                  <select name="LE_CYL_DEC" class="formText" id="LE_CYL_DEC">
                                   <option value=".75"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".75") print "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".50") print "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['LE_CYL_DEC']==".25") print "selected=\"selected\"";?>>.25</option>
                    <option value=".00" <?php if (($_SESSION['PrescrData']['LE_CYL_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_CYL_DEC'])<2)) print "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><input name="LE_AXIS" type="text" class="formText" id="LE_AXIS" size="4" maxlength="3" onchange="validateRE_Axis(this)" 
                value="<?php	if ($_SESSION['PrescrData']['LE_AXIS']>0)
				 echo  $_SESSION['PrescrData']['LE_AXIS'];
				 ?>"
                />
                  (001-180)</td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_ADD" class="formText" id="LE_ADD">
                      <option value="+3.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.50") print "selected=\"selected\"";?>>+3.50</option>
                  <option value="+3.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.25") print "selected=\"selected\"";?>>+3.25</option>
                  <option value="+3.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+3.00") print "selected=\"selected\"";?>>+3.00</option>
                  <option value="+2.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.75") print "selected=\"selected\"";?>>+2.75</option>
                  <option value="+2.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.50") print "selected=\"selected\"";?>>+2.50</option>
                  <option value="+2.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.25") print "selected=\"selected\"";?>>+2.25</option>
                  <option value="+2.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+2.00") print "selected=\"selected\"";?>>+2.00</option>
                  <option value="+1.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.75") print "selected=\"selected\"";?>>+1.75</option>
                  <option value="+1.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.50") print "selected=\"selected\"";?>>+1.50</option>
                  <option value="+1.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.25") print "selected=\"selected\"";?>>+1.25</option>
                  <option value="+1.00"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+1.00") print "selected=\"selected\"";?>>+1.00</option>
                  <option value="+0.75"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.75") print "selected=\"selected\"";?>>+0.75</option>
                  <option value="+0.50"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.50") print "selected=\"selected\"";?>>+0.50</option>
                  <option value="+0.25"<?php if ($_SESSION['PrescrData']['LE_ADD']=="+0.25") print "selected=\"selected\"";?>>+0.25</option>
                  <option value="+0.00" <?php if (($_SESSION['PrescrData']['LE_ADD']=="+0.00")||(strlen($_SESSION['PrescrData']['LE_ADD'])<2)) print "selected=\"selected\"";?>>+0.00</option>
                </select></td>
                <td align="right" valign="top"class="formCell"><input name="LE_PR_IO" type="radio" value="In"<?php if ($_SESSION['PrescrData']['LE_PR_IO']=='In') print 'checked="checked"';?>/><?php echo $adm_in_txt;?>&nbsp;<input name="LE_PR_IO" type="radio" value="Out" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='Out') print 'checked="checked"';?>/><?php echo $adm_out_txt;?>
                  <input name="LE_PR_IO" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_IO']=='None') print 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX" type="text" class="formText" id="LE_PR_AX" 
                  value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX'];
				 ?>"
                   size="4" maxlength="4" /><br /><input name="LE_PR_UD" type="radio" value="Up"<?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Up') print 'checked="checked"';?>/><?php echo $adm_up_txt;?>&nbsp;<input name="LE_PR_UD" type="radio" value="Down" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='Down') print 'checked="checked"';?>/><?php echo $adm_down_txt;?>&nbsp;
                  <input name="LE_PR_UD" type="radio" value="None" <?php if ($_SESSION['PrescrData']['LE_PR_UD']=='None') print 'checked="checked"';?>/>
                  <?php echo $adm_none_txt;?>
                  <input name="LE_PR_AX2" type="text" class="formText" id="LE_PR_AX2" 
                  value="<?php	if ($_SESSION['PrescrData']['LE_PR_AX2']>0)
				 echo  $_SESSION['PrescrData']['LE_PR_AX2'];
				 ?>"
                   size="4" maxlength="4" /><input type="hidden" name="uploadhold" id="uploadhold" value="<?php	echo $_SESSION['PrescrData']['myupload']; ?>" /></td>
              </tr>
            </table></div>
                          <?php    
			
			if($_SESSION['PrescrData']['myupload'] != "none"){ ?>
                <div id="spherechoice" >
                <table width="650" align="center">
                <tr>
                <td colspan="7" align="right" valign="top" bgcolor="#B4D7FF"class="formCell">
                   <div style="width:500px; margin:0 auto;">
                   <input name="incfile" type="radio" id="incfile" value="yes" checked="checked" onChange="changestate('on')" />
                   	I would like to supply a lens profile file.
                   <input type="radio" name="incfile" id="incfile" value="no" onChange="changestate('off')"/>
                	I would like to supply a lens profile file later. 
                    </div>
                </td>
                  </tr>
                <tr>
                <td colspan="4" align="right" valign="top" bgcolor="#FFFFFF" style="font-family:Arial, Helvetica, sans-serif;font-size:12px;">
				<?php 
				echo $_SESSION['PrescrData']['myupload']; 
				?>
                </td>
                <td colspan="3" align="left" valign="top" bgcolor="#FFFFFF">
                <input type="button" value="Remove Profile" onClick="removefile()" />
                </td>
                </tr>
                </table>
                </div>
                      <?php }else{
               
			 		   
			   ?>
                      <div id="spherechoice">
                        <table width="650" align="center">
                          <tr >
                            <td colspan="7" align="right" valign="top" bgcolor="#B4D7FF"class="formCell"><div style="width:500px; margin:0 auto;">
                                <input name="incfile" type="radio" id="incfile" value="yes" checked="checked" onChange="changestate('on')" />
                                I would like to supply a lens profile file.
                                <input type="radio" name="incfile" id="incfile" value="no" onChange="changestate('off')"/>
                                I would like to supply a lens profile file later. </div></td>
                          </tr>
                          <tr >
                            <td colspan="7" align="right" valign="top" bgcolor="#FFFFFF"class="formCell"><div id="uploaderdiv" style="width:400px; margin:0 auto; text-align: center;">
                                <p>
                                  <input type="file" name="myupload" id="myupload" size="40">
                                  
                                </p>
                              </div></td>
                          </tr>
                        </table>
                      </div>
                      <?php }?>
	         <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
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
 while ($listItem=mysql_fetch_array($result)){
  
  print "<option value=\"$listItem[index_v]\"";
  
 if ($_SESSION['PrescrData']['INDEX']=="$listItem[index_v]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[index_v]);
 print "$name</option>";}?>
                       </select>
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_coating_txt;?></span></td>
                   <td align="left" class="formCellNosides">
                   <select name="COATING" class="formText" id="COATING">
                     <option value="ANY" selected="<?php if ($_SESSION['PrescrData']['COATING']=="ANY") print "selected=\"selected\"";?>"  >ANY</option>
                     <option value="Hard Coat" <?php if ($_SESSION['PrescrData']['COATING']=="Hard Coat") print "selected=\"selected\"";?>>Hard Coat</option>
                     <option value="AR" <?php if ($_SESSION['PrescrData']['COATING']=="AR") print "selected=\"selected\"";?>>AR</option>
                     <option value="Uncoated" <?php if ($_SESSION['PrescrData']['COATING']=="Uncoated") print "selected=\"selected\"";?>>Uncoated</option>  
                   </select></td> 
                   </tr>
                   
                
                   
                   
                 <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_photochr_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="PHOTO" class="formText" id="PHOTO">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['PHOTO']=="None") print "selected=\"selected\"";?>"><?php echo $adm_none_txt;?></option>
                       <?php
  $query="select photo from exclusive group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 
 if ($listItem[photo]!="None"){
  
  print "<option value=\"$listItem[photo]\"";
  
 if ($_SESSION['PrescrData']['PHOTO']=="$listItem[photo]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[photo]);
 print "$name</option>";}}?>
                       </select>
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_polarized_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['POLAR']=="None") print "selected=\"selected\"";?>">None</option>
                       <?php
  $query="select polar from exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
 while ($listItem=mysql_fetch_array($result)){
 
 if ($listItem[polar]!="None"){
  
  print "<option value=\"$listItem[polar]\"";
  
 if ($_SESSION['PrescrData']['POLAR']=="$listItem[polar]") 
 print "selected=\"selected\"";
 print ">";
 $name=stripslashes($listItem[polar]);
 print "$name</option>";}}?>
                       </select>
                     </span></td>
                   </tr>
                   
                   
                   
                   
                       <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
				echo 'Catégorie de verres:';
				}else {
				echo 'Lens category:';
				}
				?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                   
                <?php if ($mylang == 'lang_french'){				?>
              <select name="lens_category">
                <option  value="all">Tous</option>
                <option  value="bifocal">Bi-focal</option>
               <option  value="glass">Glass</option>
                 <option  value="all prog">Tous Progressifs</option>
                <option  value="prog cl">Progressif Classique</option>
                <option  value="prog ds">Progressif DS</option>
                <option  value="prog ff">Progressif FF</option>
                 <option value="sv">Sv</option>
                </select>
                <?php 
				}else {
				?>
				 <select name="lens_category">
                <option  value="all">All</option>
                <option  value="bifocal">Bi-focal</option>
               <option  value="glass">Glass</option>
                   <option  value="all prog">All Progressives</option>
                <option  value="prog cl">Progressive Classic</option>
                <option  value="prog ds">Progressive DS</option>
                <option  value="prog ff">Progressive FF</option>
                 <option value="sv">Sv</option>
                </select>
				<?php
                }
				?>
                  </span></td>
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
                   
                   
                   
                   
                   
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
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
                   <td align="left" class="formCellNosides"><img src="images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
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
                   <td align="center" class="formCellNosides"><img src="images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft"><?php echo $adm_nearpd_txt;?><br />
                     <input name="LE_PD_NEAR" type="text" class="formText" id="LE_PD_NEAR" 
                     value="<?php	if ($_SESSION['PrescrData']['LE_PD_NEAR']>0)
				 echo  $_SESSION['PrescrData']['LE_PD_NEAR'];
				 ?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_le_txt;?></td>
                   <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                     <input name="RE_HEIGHT" type="text" class="formText" id="RE_HEIGHT" 
                     value="<?php	if ($_SESSION['PrescrData']['RE_HEIGHT']>0)
				 echo  $_SESSION['PrescrData']['RE_HEIGHT'];
				 ?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_re_txt;?></td>
                   <td align="center" class="formCellNosides"><img src="images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
                   <td align="center" class="formCellNosides"><?php echo $adm_height_txt;?><br />
                     <input name="LE_HEIGHT" type="text" class="formText" id="LE_HEIGHT" 
                     value="<?php	if ($_SESSION['PrescrData']['LE_HEIGHT']>0)
				 echo  $_SESSION['PrescrData']['LE_HEIGHT'];
				 ?>" size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_le_txt;?></td>
                   </tr>
               </table>
             </div>
            
            
            
            
             
      <?php 
	  echo '<input type="hidden" name="warranty" value="0" />';
	  ?>
           
            <div>
              <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                <tr>
                  <td colspan="6" bgcolor="#000099" class="tableHead"><?php echo $lbl_mywrldcoll_txt_pl;?>&nbsp;</td>
                  </tr>
                <tr>
                <td align="right" class="formCell"><?php echo $lbl_pt_txt_pl;?></td>
                  <td align="left" class="formCellNosides">&nbsp;  <input name="PT" type="text" class="formText" id="PT" value="<?php print $_SESSION['PrescrData']['PT'];?>" size="2" maxlength="2" />
                    
                    <?php echo $lbl_pt1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $lbl_pa_txt_pl;?>&nbsp;</td>
                  <td align="left" class="formCellNosides"><input name="PA" type="text" class="formText" id="PA" value="<?php print $_SESSION['PrescrData']['PA'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_pa1_pl;?></td>
                  <td align="right" class="formCell"><?php echo $adm_vertex_txt;?></td>
                  <td align="left" class="formCellNosides"><input name="VERTEX" type="text" class="formText" id="VERTEX" value="<?php print $_SESSION['PrescrData']['VERTEX'];?>" size="2" maxlength="2" />
                    <?php echo $lbl_vertex1_pl;?>&nbsp;</td>
                  </tr>
              </table>
            </div>
		    
          
            
            
            
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="6" bgcolor="#000099" class="tableHead">TOP URGENT&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="left" class="formCell">TOP URGENT&nbsp;
                  <select name="top_urgent" class="formText" id="top_urgent">
                 <option value="regular">Regular</option  ><?php if ($_SESSION['PrescrData']['top_urgent']=="regular") 
 print "selected=\"selected\"";
 print ">";
 ?>               <option value="TOP_URGENT">TOP URGENT (extra fee)</option ><?php if ($_SESSION['PrescrData']['top_urgent']=="top_urgent") 
 print "selected=\"selected\"";
 print ">";        ?>            
					 </select>

             		            
      
                   
                   </td>
                  
                   <td align="left" class="formCellNosides">&nbsp;</td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#000099" class="tableHead"><?php echo $lbl_otherspec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo 'Engraving';?> </td>
                   <td align="left" class="formCellNosides">&nbsp;
                     <input name="ENGRAVING" type="text" class="formText" id="ENGRAVING" value="<?php print $_SESSION['PrescrData']['ENGRAVING'];?>" size="4" maxlength="8" /></td>
                   <td align="right" class="formCell"><?php echo $adm_tint_txt;?></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT" class="formText" id="TINT" onchange="updateTINT(this.form)" >
                       <option value="None" <?php if ($_SESSION['PrescrData']['TINT']=="None") print "selected=\"selected\"";?>><?php echo $adm_none_txt;?></option>
                       <option value="Solid" <?php if ($_SESSION['PrescrData']['TINT']=="Solid") print "selected=\"selected\"";?>><?php echo $lbl_tint2_pl;?></option>
                       <option value="Gradient" <?php if ($_SESSION['PrescrData']['TINT']=="Gradient") print "selected=\"selected\"";?>><?php echo $adm_gradient_txt;?></option>
                       </select>
                     </span></td>
                   <td align="left" class="formCellNosides"><?php echo $adm_from_txt;?>
                     <input name="FROM_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") print "disabled=\"disabled\"";?> class="formText" id="FROM_PERC" value="<?php print $_SESSION['PrescrData']['FROM_PERC'];?>" size="4" maxlength="4" />
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $adm_to_txt;?>
                     <input name="TO_PERC" type="text" <?php if ($_SESSION['PrescrData']['TINT']=="None") print "disabled=\"disabled\"";?> class="formText" id="TO_PERC" value="<?php print $_SESSION['PrescrData']['TO_PERC'];?>" size="4" maxlength="4">
                     %</td>
                   <td align="left" class="formCellNosides"><?php echo $adm_color_txt;?></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="TINT_COLOR" <?php if ($_SESSION['PrescrData']['TINT']=="None") print "disabled=\"disabled\"";?> class="formText" id="TINT_COLOR">
                       <option value="Brown" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Brown") print "selected=\"selected\"";?>><?php echo $adm_brwn_txt;?></option>
                       <option value="Gray" <?php if ($_SESSION['PrescrData']['TINT_COLOR']=="Gray") print "selected=\"selected\"";?>><?php echo $adm_gray_txt;?></option>
                       
                       
                        <option value="SW007"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW007") print "selected=\"selected\"";?>><?php echo 'SW007' ;?></option>
                        <option value="SW008"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW008") print "selected=\"selected\"";?>><?php echo 'SW008' ;?></option>                        <option value="SW009"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW009") print "selected=\"selected\"";?>><?php echo 'SW009' ;?></option>
                        
                        <option value="SW016"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW016") print "selected=\"selected\"";?>><?php echo 'SW016' ;?></option>
                        <option value="SW025/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025/85") print "selected=\"selected\"";?>><?php echo 'SW025/85' ;?></option>
                        <option value="SW022"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW022") print "selected=\"selected\"";?>><?php echo 'SW022' ;?></option>
                         
                       <option value="SW025/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025/75") print "selected=\"selected\"";?>><?php echo 'SW025/75' ;?></option>
                       <option value="SW017"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW017") print "selected=\"selected\"";?>><?php echo 'SW017' ;?></option>
                       <option value="GOL"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="GOL") print "selected=\"selected\"";?>><?php echo 'GOL' ;?></option>
                       
                       <option value="SW027/50"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/50") print "selected=\"selected\"";?>><?php echo 'SW027/50' ;?></option> 
                       <option value="SW027/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/75") print "selected=\"selected\"";?>><?php echo 'SW027/75' ;?></option> 
                       <option value="SW027/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/85") print "selected=\"selected\"";?>><?php echo 'SW027/85' ;?></option> 
                       
                       <option value="SW030/50"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/50") print "selected=\"selected\"";?>><?php echo 'SW030/50' ;?></option> 
                       <option value="SW030/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/75") print "selected=\"selected\"";?>><?php echo 'SW030/75' ;?></option> 
                       <option value="SW030/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/85") print "selected=\"selected\"";?>><?php echo 'SW030/85' ;?></option> 
                       
                       <option value="SW043"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW043") print "selected=\"selected\"";?>><?php echo 'SW043' ;?></option> 
                       <option value="SW044"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW044") print "selected=\"selected\"";?>><?php echo 'SW044' ;?></option> 
                      <option value="SW051"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW051") print "selected=\"selected\"";?>><?php echo 'SW051' ;?></option> 
                      
                       </select>
                     </span></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_framespec_txt_pl;?>&nbsp;</td>
                   </tr>
                 <tr>
                   <td align="right" class="formCell"><?php echo $adm_eyec_txt;?></td>
                   <td align="left" class="formCellNosides"> <?php echo $lbl_a_txt_pl;?>
                     &nbsp;
                     <input name="FRAME_A" type="text" class="formText" id="FRAME_A" value="<?php print $_SESSION['PrescrData']['FRAME_A'];?>" size="4" maxlength="4" />		          &nbsp;
                     
                     <?php echo $lbl_b_txt;?>
                     <input name="FRAME_B" type="text" class="formText" id="FRAME_B" value="<?php print $_SESSION['PrescrData']['FRAME_B'];?>" size="4" maxlength="4" />
                     &nbsp;&nbsp;<?php echo $adm_ed_txt;?>
                     <input name="FRAME_ED" type="text" class="formText" id="FRAME_ED" value="<?php print $_SESSION['PrescrData']['FRAME_ED'];?>" size="4" maxlength="4" />
                     &nbsp;&nbsp;
                     
                     <?php echo $adm_dbl_txt;?>
                     <input name="FRAME_DBL" type="text" class="formText" id="FRAME_DBL" value="<?php print $_SESSION['PrescrData']['FRAME_DBL'];?>" size="4" maxlength="4" />
                     &nbsp;<?php echo $adm_temple_txt;?>
                     <input name="TEMPLE" type="text" class="formText" id="TEMPLE" value="<?php print $_SESSION['PrescrData']['TEMPLE'];?>" size="4" />
                     </td>
                   <td align="right" class="formCell"><?php echo $adm_type_txt;?></td>
                   <td align="left" class="formCell">
                     <select name="FRAME_TYPE" class="formCellNosides" id="FRAME_TYPE">
                       <option value="Nylon Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Nylon Groove") print "selected=\"selected\"";?>><?php echo $adm_nylgrve_txt;?></option>
                       <option value="Metal Groove"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal Groove") print "selected=\"selected\"";?>><?php echo $adm_mtlgrve_txt;?></option>
                       <option value="Plastic"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Plastic") print "selected=\"selected\"";?>><?php echo $adm_plas_txt;?></option>
                       <option value="Metal"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Metal") print "selected=\"selected\"";?>><?php echo $adm_mtl_txt;?></option>
                       <option value="Edge Polish"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Edge Polish") print "selected=\"selected\"";?>><?php echo $adm_edgepol_txt;?></option>
                       <option value="Drill and Notch"<?php if ($_SESSION['PrescrData']['FRAME_TYPE']=="Drill and Notch") print "selected=\"selected\"";?>><?php echo $adm_drillnotc_txt;?></option>
                       </select></td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $adm_jobtype_txt;?>
                     <select name="JOB_TYPE" class="formText" id="JOB_TYPE" onchange="updateJOB_TYPE(this.form)">
                       <option value="Uncut" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print "selected=\"selected\"";?>><?php echo $adm_uncut_txt;?></option>
                       <option value="Edge and Mount" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Edge and Mount") print "selected=\"selected\"";?>><?php echo $adm_edgemount_txt;?></option>
                       
                        <option value="remote edging" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="remote edging") print "selected=\"selected\"";?>>
						<?php if ($mylang == 'lang_french'){
						echo 'Taillé Non monté';
						}else {
						echo 'Remote Edging';
						}
						?></option>
                        
                       </select>
                     &nbsp;&nbsp;&nbsp; <?php echo $adm_frame_txt;?>
                     <select name="ORDER_TYPE" class="formText" id="ORDER_TYPE" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print 'disabled="disabled"';?>>
                      <option value="To Follow" <?php if ($_SESSION['PrescrData']['ORDER_TYPE']=="To Follow") print "selected=\"selected\"";?> ><?php echo $adm_tofol_txt;?></option>
                       <option value="Provide" <?php if ($_SESSION['PrescrData']['ORDER_TYPE']=="Provide") print "selected=\"selected\"";?>><?php echo $adm_prov_txt;?></option>
                      
                       </select>
                     &nbsp;&nbsp;&nbsp;&nbsp;</td>
                   </tr>
                 <tr>
                   <td colspan="4" align="center" class="formCell"><?php echo $adm_supplier_txt;?>
                     <input name="SUPPLIER" type="text"  <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print 'disabled="disabled"';?> class="formText" id="SUPPLIER" value="<?php print $_SESSION['PrescrData']['SUPPLIER'];?>" size="12"/>
                     &nbsp;&nbsp;<?php echo $adm_shpmod_txt;?>
                     <input name="FRAME_MODEL" type="text" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print 'disabled="disabled"';?> class="formText" id="FRAME_MODEL" value="<?php print $_SESSION['PrescrData']['FRAME_MODEL'];?>" size="12"/>
                     </span> &nbsp;&nbsp;&nbsp;
                     <?php echo $adm_framemod_txt;?>
                     <input name="TEMPLE_MODEL" type="text" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print 'disabled="disabled"';?> class="formText" id="TEMPLE_MODEL" value="<?php print $_SESSION['PrescrData']['TEMPLE_MODEL'];?>" size="12"/>&nbsp;&nbsp;&nbsp; <?php echo $adm_color_txt;?>
                     <input name="COLOR" type="text"<?php if ($_SESSION['PrescrData']['JOB_TYPE']=="Uncut") print 'disabled="disabled"';?> class="formText" id="COLOR" value="<?php print $_SESSION['PrescrData']['COLOR'];?>" size="12" /></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $adm_specialinstructions_txt;?> </span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"><?php print $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?></textarea></td>
                   </tr>
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="4"  bgcolor="#000099" class="tableHead">INTERNAL NOTE </td>
                   </tr>
                 <tr>
                   <td width="134" align="center" valign="top" class="formCell"><span class="tableSubHead">Internal Note</span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="INTERNAL_NOTE" cols="70" rows="2" class="formText" id="INTERNAL_NOTE"><?php print $_SESSION['PrescrData']['INTERNAL_NOTE'];?></textarea></td>
                   </tr>
               </table>
             </div>
			 
			 
		    <div align="center" style="margin:11px">&nbsp;&nbsp;&nbsp;
		      <input name="Reset" type="reset" value="<?php echo $btn_reset_txt;?>" />
		      &nbsp;
		      <input name="Submit" type="submit" value="<?php echo $btn_submit_txt;?>" />
		    </div></div>
		  </form></td>
  </tr>
</table> </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table><?php echo "+".$_SESSION['PrescrData']['myupload']."+"; ?>
</body>
</html>
