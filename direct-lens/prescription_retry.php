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
include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');
global $drawme;


// require_once "upload/phpuploader/include_phpuploader.php";

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

		
$query="SELECT index_v FROM exclusive group by index_v asc"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);

$queryLab = "SELECT main_lab, product_line FROM accounts WHERE user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
$resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
$DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
$LabNum=$DataLab[main_lab];	
$Product_line=$DataLab[product_line];


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
<link href="dl.css" rel="stylesheet" type="text/css" />

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
	//alert(theForm.EYE[0].checked);Both
	//alert(theForm.EYE[1].checked);Right only
	//alert(theForm.EYE[2].checked);Left only
 
 //Valider que le cylindre est fournit si on a un cylindre
if ((theForm.RE_AXIS.value > 0) && (theForm.RE_CYL_DEC.value==".00"))
{
	if ((theForm.RE_CYL_NUM.value=="+0")||(theForm.RE_CYL_NUM.value=="-0"))
	{
		alert("The right cylinder is mandatory")
		return (false);
	}
}

if ((theForm.LE_AXIS.value > 0) && (theForm.LE_CYL_DEC.value==".00"))
{
	if ((theForm.LE_CYL_NUM.value=="+0")||(theForm.LE_CYL_NUM.value=="-0"))
	{
		alert("The left cylinder is mandatory")
		return (false);
	}
}

 
 //Validate the pds
  if (theForm.EYE[2].checked==false)
  {
		if ((theForm.RE_PD.value> 40) || (theForm.RE_PD.value <20))
	  {
		alert("<?php echo 'Right eye PD must be between 20 and 40';?>");
		theForm.RE_PD.focus();
		return (false);
	  }
  }
  
  
   if (theForm.EYE[1].checked==false)
  {
		if ((theForm.LE_PD.value> 40) || (theForm.LE_PD.value <20))
	  {
		alert("<?php echo 'Left eye PD must be between 20 and 40';?>");
		theForm.LE_PD.focus();
		return (false);
	  }
  }
  

   if (theForm.RE_PD_NEAR.value!="")
   {
	   if ((theForm.RE_PD_NEAR.value> 40) || (theForm.RE_PD_NEAR.value <20))
	  {
		alert("<?php echo 'Right eye near PD must be between 20 and 40';?>");
		theForm.RE_PD_NEAR.focus();
		return (false);
	  }
   }
  
  
   if (theForm.LE_PD_NEAR.value!="")
   {
		if ((theForm.LE_PD_NEAR.value> 40) || (theForm.LE_PD_NEAR.value <20))
	  {
		alert("<?php echo 'Left eye near PD must be between 20 and 40';?>");
		theForm.LE_PD_NEAR.focus();
		return (false);
	  }
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

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX.focus();
    return (false);
  }
   if ((theForm.RE_PR_AX2.value!="")&&((theForm.RE_PR_AX2.value<.1)||(theForm.RE_PR_AX2.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX2.focus();
    return (false);
  }
   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR_AX.focus();
    return (false);
  }
  
   if ((theForm.LE_PR_AX2.value!="")&&((theForm.LE_PR_AX2.value<.1)||(theForm.LE_PR_AX2.value>20.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR2_AX.focus();
    return (false);
  }
  
  if ((theForm.PT.value!="")&&((theForm.PT.value<0)||(theForm.PT.value>20)))
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
  

if (theForm.EYE[2].checked==false)
{	
	if ((theForm.RE_AXIS.value== "")&&(((theForm.RE_CYL_NUM.value!="+0")&&(theForm.RE_CYL_NUM.value!="-0"))||(theForm.RE_CYL_DEC.value!=".00")))
	  {
		alert("<?php echo $lbl_alert14_pl;?>");
		theForm.RE_AXIS.focus();
		return (false);
	  }
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

     if ((theForm.RE_PD.value== "") && (theForm.EYE[2].checked==false))
  {
    alert("<?php echo $lbl_alert17_pl;?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
 if ((theForm.LE_PD.value== "") && (theForm.EYE[1].checked==false))
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
  
  //ICI
  
   if ((theForm.FRAME_A.value> 66)||(theForm.FRAME_A.value<35))
  {
    alert("<?php echo 'The value of Frame A must be between 35 AND 66';?>");
    theForm.FRAME_A.focus();
    return (false);
  }  
  
    if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
  {
    alert("<?php echo 'The value of Frame B must be between 20 AND 52';?>");
    theForm.FRAME_B.focus();
    return (false);
  }  
    
    if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>70))
  {
    alert("<?php echo 'The value of Frame ED must be between 35 AND 70';?>");
    theForm.FRAME_ED.focus();
    return (false);
  }  
  
     if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>25))
  {
    alert("<?php echo 'The value of Frame DBL must be between 10 AND 25';?>");
    theForm.FRAME_DBL.focus();
    return (false);
  }  

  
   if (theForm.FRAME_TYPE.value== "")
  {
    alert("<?php echo 'Please select a frame type';?>");
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




function DesactivateLE_fields(form){//disable all left eye fields
	
	form.LE_SPH_NUM.disabled=true;
	form.LE_SPH_DEC.disabled=true;
	form.LE_CYL_NUM.disabled=true;
	form.LE_CYL_DEC.disabled=true;
	form.LE_ADD.disabled=true;
	form.LE_PR_IO.disabled=true;
	form.LE_PR_AX.disabled=true;
	form.LE_PD_NEAR.disabled=true;
	form.LE_HEIGHT.disabled=true;
	form.LE_AXIS.disabled=true;
	
	form.RE_SPH_NUM.disabled=false;
	form.RE_SPH_DEC.disabled=false;
	form.RE_CYL_NUM.disabled=false;
	form.RE_CYL_DEC.disabled=false;
	form.RE_ADD.disabled=false;
	form.RE_PR_IO.disabled=false;
	form.RE_PR_AX.disabled=false;
	form.RE_PD_NEAR.disabled=false;
	form.RE_HEIGHT.disabled=false;
	form.RE_AXIS.disabled=false;
	}


function DesactivateRE_fields(form){//disable all Right eye fields
	
	form.RE_SPH_NUM.disabled=true;
	form.RE_SPH_DEC.disabled=true;
	form.RE_CYL_NUM.disabled=true;
	form.RE_CYL_DEC.disabled=true;
	form.RE_ADD.disabled=true;
	form.RE_PR_IO.disabled=true;
	form.RE_PR_AX.disabled=true;
	form.RE_PD_NEAR.disabled=true;
	form.RE_HEIGHT.disabled=true;
	form.RE_AXIS.disabled=true;
	
	form.LE_SPH_NUM.disabled=false;
	form.LE_SPH_DEC.disabled=false;
	form.LE_CYL_NUM.disabled=false;
	form.LE_CYL_DEC.disabled=false;
	form.LE_ADD.disabled=false;
	form.LE_PR_IO.disabled=false;
	form.LE_PR_AX.disabled=false;
	form.LE_PD_NEAR.disabled=false;
	form.LE_HEIGHT.disabled=false;
	form.LE_AXIS.disabled=false;
	}
	
	
	function ActivateAll_fields(form){//enable all  eye fields
	
	form.RE_SPH_NUM.disabled=false;
	form.RE_SPH_DEC.disabled=false;
	form.RE_CYL_NUM.disabled=false;
	form.RE_CYL_DEC.disabled=false;
	form.RE_ADD.disabled=false;
	form.RE_PR_IO.disabled=false;
	form.RE_PR_AX.disabled=false;
	form.RE_PD_NEAR.disabled=false;
	form.RE_HEIGHT.disabled=false;
	form.RE_AXIS.disabled=false;
	
	form.LE_SPH_NUM.disabled=false;
	form.LE_SPH_DEC.disabled=false;
	form.LE_CYL_NUM.disabled=false;
	form.LE_CYL_DEC.disabled=false;
	form.LE_ADD.disabled=false;
	form.LE_PR_IO.disabled=false;
	form.LE_PR_AX.disabled=false;
	form.LE_PD_NEAR.disabled=false;
	form.LE_HEIGHT.disabled=false;
	form.LE_AXIS.disabled=false;
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
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_user_txt;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;</div>
              <div>
		     <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_patient;?>&nbsp;</td>
                <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slsperson;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret client'; else echo 'Customer Tray';?>&nbsp;</td>
                <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'Cabaret lab'; else echo 'Lab Tray';?>&nbsp;</td>
                <td class="formCellNosides"><?php if ($mylang == 'lang_french') echo 'ID Vendeur'; else echo 'Salesperson ID';?>&nbsp;</td>
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
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 echo "<option value=\"$listItem[sales_id]\"";
 
 if ($listItem[sales_id]==$_SESSION['PrescrData']['SALESPERSON_ID']){
 	echo "selected=\"selected\"";}
 
 echo ">";
 $name=stripslashes($listItem[first_name])." ".stripslashes($listItem[last_name]);
 echo "$name</option>";
 }
 ?>
                </select></td>
              </tr>
		     </table></div>
             <div>
				<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_mast1;?>
                  <span class="formCell">
                  <input name="EYE" type="radio" onclick="ActivateAll_fields(this.form);"  value="Both" <?php if ($_SESSION['PrescrData']['EYE']=="Both") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription1_pl;?> <span class="formCell"> &nbsp;
                  <input name="EYE" type="radio" onclick="DesactivateLE_fields(this.form);"  value="R.E." <?php if ($_SESSION['PrescrData']['EYE']=="R.E.") echo "checked=\"checked\"";?>/>
                  </span><?php echo $lbl_prescription2_pl;?>&nbsp; <span class="formCell">
                  <input name="EYE" type="radio" value="L.E." onclick="DesactivateRE_fields(this.form);" <?php if ($_SESSION['PrescrData']['EYE']=="L.E.") echo "checked=\"checked\"";?>/>
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
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a></td>
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
                  <option value="-16"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-16") echo "selected=\"selected\"";?>>-16</option>
                  <option value="-17"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-17") echo "selected=\"selected\"";?>>-17</option>
                  <option value="-18"<?php if ($_SESSION['PrescrData']['RE_SPH_NUM']=="-18") echo "selected=\"selected\"";?>>-18</option>
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
                  <option value="-16"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-16") echo "selected=\"selected\"";?>>-16</option>
                  <option value="-17"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-17") echo "selected=\"selected\"";?>>-17</option>
                  <option value="-18"<?php if ($_SESSION['PrescrData']['LE_SPH_NUM']=="-18") echo "selected=\"selected\"";?>>-18</option>
                  </select>
                  <select name="LE_SPH_DEC" class="formText" id="LE_SPH_DEC">
                    <option value=".75"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".75") echo "selected=\"selected\"";?>>.75</option>
                    <option value=".50"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".50") echo "selected=\"selected\"";?>>.50</option>
                    <option value=".25"<?php if ($_SESSION['PrescrData']['LE_SPH_DEC']==".25") echo "selected=\"selected\"";?>>.25</option>
                    <option value=".00"  <?php if (($_SESSION['PrescrData']['LE_SPH_DEC']==".00")||(strlen($_SESSION['PrescrData']['LE_SPH_DEC'])<2)) echo "selected=\"selected\"";?>>.00</option>
                  </select></td>
                <td align="center" valign="top" class="formCellNosides"><select name="LE_CYL_NUM" class="formText" id="LE_CYL_NUM" onchange="fixLE_CYL(this.form)">   
                  <option value="-0" <?php if (($_SESSION['PrescrData']['LE_CYL_NUM']==="-0")||($_SESSION['PrescrData']['LE_CYL_NUM']=="–")) echo "selected=\"selected\"";?>>-0</option>
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
                   size="4" maxlength="4" /></td>
              </tr>
            </table></div>

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
						$query="SELECT index_v FROM exclusive group by index_v asc"; /* select all openings */
						$result=mysqli_query($con,$query) or die ("Could not select items");
						$usercount=mysqli_num_rows($result);
						while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
							echo "<option value=\"$listItem[index_v]\"";
							if ($_SESSION['PrescrData']['INDEX']=="$listItem[index_v]") 
							echo "selected=\"selected\"";
							echo ">";
							$name=stripslashes($listItem[index_v]);
							echo "$name</option>";
						}
						  ?>
                       </select>
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_coating_txt;?></span></td>
                   <td align="left" class="formCellNosides">
                   <select name="COATING" class="formText" id="COATING">
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
					  $query="SELECT photo FROM exclusive group by photo asc"; /* select all openings */
					$result=mysqli_query($con,$query) or die ("Could not select items");
					$usercount=mysqli_num_rows($result);
					 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
					 
					 if ($listItem[photo]!="None"){
					  
					  echo "<option value=\"$listItem[photo]\"";
					  
					 if ($_SESSION['PrescrData']['PHOTO']=="$listItem[photo]") 
					 echo "selected=\"selected\"";
					 echo ">";
					 $name=stripslashes($listItem[photo]);
					 echo "$name</option>";}
					 }
					  
					 ?>
                       </select>
                     </span></td>
                   <td align="right" class="formCell"><span class="tableSubHead"><?php echo $adm_polarized_txt;?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                     <select name="POLAR" class="formText" id="POLAR">
                       <option value="None" selected="<?php if ($_SESSION['PrescrData']['POLAR']=="None") echo "selected=\"selected\"";?>">None</option>
                       <?php
  $query="SELECT polar FROM exclusive group by polar asc"; /* select all openings */
$result=mysqli_query($con,$query)		or die ("Could not select items");
$usercount=mysqli_num_rows($result);
 while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
 
 if ($listItem[polar]!="None"){
  
  echo "<option value=\"$listItem[polar]\"";
  
 if ($_SESSION['PrescrData']['POLAR']=="$listItem[polar]") 
 echo "selected=\"selected\"";
 echo ">";
 $name=stripslashes($listItem[polar]);
 echo "$name</option>";}
 }
  ?>
                       </select>
                     </span></td>
                   </tr>
                   
                  
                   
                   
                       <tr>
                   <td align="right" class="formCell"><span class="tableSubHead"> 
				   <?php if ($mylang == 'lang_french'){
				echo 'Catégorie:';
				}else {
				echo 'Lens category:';
				}
				?></span></td>
                   <td align="left" class="formCellNosides"><span style="margin:11px">
                   
                   <?php if (($mylang == 'lang_french') && ($Product_line <> 'eye-recommend')){				?>
        <select name="lens_category">
                 <option   disabled="disabled" value="">CATÉGORIE DE VERRES*</option>
                 <option   value="all" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="all") echo 'selected="selected"'; ?>>Tous</option> 
                 <option   value="bifocal" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                 <option   value="glass" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="glass") echo 'selected="selected"'; ?>>Verre</option>
                 <option   value="all prog" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="all prog") echo 'selected="selected"'; ?>>Tous Progressifs</option>
                 <option   value="prog cl" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="prog cl") echo 'selected="selected"'; ?>>Progressif Classique</option>
                 <option   value="prog ds" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                 <option   value="prog ff" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option   value="sv" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                 <option  disabled="disabled" value="">&nbsp;</option>
                 <option   disabled="disabled" value="">TYPE DE VERRES*</option>
                 <option   value="iFree" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="iFree") echo 'selected="selected"'; ?>>iFree</option> 
                 <option   value="iAction" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="iAction") echo 'selected="selected"'; ?>>iAction</option> 
                 <option   value="CMF 2 HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="CMF 2 HD") echo 'selected="selected"'; ?>>CMF 2 HD</option> 
                 <option   value="iAction SV" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="iAction SV") echo 'selected="selected"'; ?>>iAction SV</option> 
                 <option   value="iRelax" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="iRelax") echo 'selected="selected"'; ?>>iRelax</option> 
                 <option   value="iOffice" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="iOffice") echo 'selected="selected"'; ?>>iOffice</option> 
				 <option   value="Precision Daily" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Precision Daily") echo 'selected="selected"'; ?>>Precision Daily</option> 
				 <option   value="Precision Active" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Precision Active") echo 'selected="selected"'; ?>>Precision Active</option> 
                 <option   value="Precision SV HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Precision SV HD") echo 'selected="selected"'; ?>>Precision SV HD</option> 
                 <option   value="Identity by Optotech" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Identity by Optotech") echo 'selected="selected"'; ?>>Identity by Optotech</option> 
 				 <option   value="TrueHD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="TrueHD") echo 'selected="selected"'; ?>>TrueHD</option> 
				 <option   value="EasyOne" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="EasyOne") echo 'selected="selected"'; ?>>EasyOne</option> 
                 <option   value="Infocus RX Direct Progressive" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Infocus RX Direct Progressive") echo 'selected="selected"'; ?>>Infocus RX Direct Progressive</option> 
 				 <option   value="Infocus Flat Top" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Infocus Flat Top") echo 'selected="selected"'; ?>>Infocus Flat Top</option> 
                 <option   value="Infocus Single Vision" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Infocus Single Vision") echo 'selected="selected"'; ?>>Infocus Single Vision</option> 
 				 <option   value="Vision Pro HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Vision Pro HD") echo 'selected="selected"'; ?>>Vision Pro HD</option> 
 				 <option   value="Mini Pro HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Mini Pro HD") echo 'selected="selected"'; ?>>Mini Pro HD</option> 
                 <option   value="Innovative II DS" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Innovative II DS") echo 'selected="selected"'; ?>>Innovative II DS</option> 
 				 <option   value="Econo Choice" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Econo Choice") echo 'selected="selected"'; ?>>Econo Choice</option> 
  				 <option   value="Econo Choice Ultra Short" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Econo Choice Ultra Short") echo 'selected="selected"'; ?>>Econo Choice Ultra Short</option> 
  				 <option   value="Econo Choice Ultra One" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Econo Choice Ultra One") echo 'selected="selected"'; ?>>Econo Choice Ultra One</option> 
 				 <option   value="Pro EZ HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Pro EZ HD") echo 'selected="selected"'; ?>>Pro EZ HD</option>
 				 <option   value="Vision Classique HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Vision Classique HD") echo 'selected="selected"'; ?>>Vision Classique HD</option> 
                  <option   value="IPL"     <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="IPL")     echo 'selected="selected"'; ?>>Alpha (Formerly Optimize IPL)</option> 
                 <option   value="Acuform" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Acuform") echo 'selected="selected"'; ?>>Optimize ACUFORM</option> 
                 <option   value="FIT" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="FIT") echo 'selected="selected"'; ?>>Optimize FIT</option> 
                 <option   value="Horizon" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Horizon") echo 'selected="selected"'; ?>>Optimize Horizon+</option> 
                 <option   value="DMT" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="DMT") echo 'selected="selected"'; ?>>Innovative (Formerly DMT)</option> 
                 <option   value="Lifestyle" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Lifestyle") echo 'selected="selected"'; ?>>Office Premium</option>
                 <option   value="Anti-Fatigue" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Anti-Fatigue") echo 'selected="selected"'; ?>>Eye Fatigue</option>
                 <option   value="Purelife HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Purelife HD") echo 'selected="selected"'; ?>>Purelife HD</option> 
                 <option   value="Life II" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Life II") echo 'selected="selected"'; ?>>Life II</option> 
                 <option   value="Life XS" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Life XS") echo 'selected="selected"'; ?>>Life XS</option> 
                 <option   value="SelectionRx" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SelectionRx") echo 'selected="selected"'; ?>>SelectionRx</option> 
  				 <option   value="SV" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SV") echo 'selected="selected"'; ?>>SV</option> 
				 <option   value="ST-28" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="ST-28") echo 'selected="selected"'; ?>>ST-28</option> 
				 <option   value="ST-25" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="ST-25") echo 'selected="selected"'; ?>>ST-25</option> 
				 <option   value="Ovation" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="Ovation") echo 'selected="selected"'; ?>>Ovation</option> 
                 <option   value="ELPS HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="ELPS HD") echo 'selected="selected"'; ?>>ELPS HD</option> 
                 <option   value="PSI HD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="PSI HD") echo 'selected="selected"'; ?>>PSI HD</option>
                 <option   disabled="disabled" value="">&nbsp;</option>
				 <option   disabled="disabled" value="">FABRICANT*</option>
                 <option   value="ESSILOR" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="ESSILOR") echo 'selected="selected"'; ?>>ESSILOR</option> 
                 <option   value="MY WORLD" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="MY WORLD") echo 'selected="selected"'; ?>>MY WORLD</option> 
                 <option   value="OPTIMIZE" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="OPTIMIZE") echo 'selected="selected"'; ?>>OPTIMIZE</option> 
                 <option   value="OPTOTECH" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option> 
                 <option   value="PRECISION" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="PRECISION") echo 'selected="selected"'; ?>>PRECISION</option> 
                 <option   value="RODENSTOCK" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="RODENSTOCK") echo 'selected="selected"'; ?>>RODENSTOCK</option> 
                 <option   value="SEIKO" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SEIKO") echo 'selected="selected"'; ?>>SEIKO</option> 
                 <option   value="SHAMIR" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SHAMIR") echo 'selected="selected"'; ?>>SHAMIR</option> 
                 <option   value="SOLA" <?php if ($_SESSION['PrescrData']['LENS_CATEGORY']=="SOLA") echo 'selected="selected"'; ?>>SOLA</option>       
        </select>
        
                <?php 
				}elseif (($mylang <> 'lang_french') && ($Product_line <> 'eye-recommend')) {
				?>
			<select name="lens_category">
                 <option  disabled="disabled" value="">LENS CATEGORY*</option>
                 <option   value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>All</option> 
                 <option   value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                 <option   value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                 <option   value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>All Progressives</option>
                 <option   value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressive Classique</option>
                 <option   value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                 <option   value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option   value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                 <option  disabled="disabled" value="">&nbsp;</option>
                 <option  disabled="disabled" value="">LENS TYPE*</option>
                 <option   value="iFree" <?php if ($_POST['lens_category']=="iFree") echo 'selected="selected"'; ?>>iFree</option> 
                 <option   value="iAction" <?php if ($_POST['lens_category']=="iAction") echo 'selected="selected"'; ?>>iAction</option> 
                 <option   value="CMF 2 HD" <?php if ($_POST['lens_category']=="CMF 2 HD") echo 'selected="selected"'; ?>>CMF 2 HD</option> 
                 <option   value="iAction SV" <?php if ($_POST['lens_category']=="iAction SV") echo 'selected="selected"'; ?>>iAction SV</option> 
                 <option   value="iRelax" <?php if ($_POST['lens_category']=="iRelax") echo 'selected="selected"'; ?>>iRelax</option> 
                 <option   value="iOffice" <?php if ($_POST['lens_category']=="iOffice") echo 'selected="selected"'; ?>>iOffice</option> 
				 <option   value="Precision Daily" <?php if ($_POST['lens_category']=="Precision Daily") echo 'selected="selected"'; ?>>Precision Daily</option> 
				 <option   value="Precision Active" <?php if ($_POST['lens_category']=="Precision Active") echo 'selected="selected"'; ?>>Precision Active</option> 
                 <option   value="Precision SV HD" <?php if ($_POST['lens_category']=="Precision SV HD") echo 'selected="selected"'; ?>>Precision SV HD</option> 
                 <option   value="Identity by Optotech" <?php if ($_POST['lens_category']=="Identity by Optotech") echo 'selected="selected"'; ?>>Identity by Optotech</option> 
 				 <option   value="TrueHD" <?php if ($_POST['lens_category']=="TrueHD") echo 'selected="selected"'; ?>>TrueHD</option> 
				 <option   value="EasyOne" <?php if ($_POST['lens_category']=="EasyOne") echo 'selected="selected"'; ?>>EasyOne</option> 
                 <option   value="Infocus RX Direct Progressive" <?php if ($_POST['lens_category']=="Infocus RX Direct Progressive") echo 'selected="selected"'; ?>>Infocus RX Direct Progressive</option> 
 				 <option   value="Infocus Flat Top" <?php if ($_POST['lens_category']=="Infocus Flat Top") echo 'selected="selected"'; ?>>Infocus Flat Top</option> 
                 <option   value="Infocus Single Vision" <?php if ($_POST['lens_category']=="Infocus Single Vision") echo 'selected="selected"'; ?>>Infocus Single Vision</option> 
 				 <option   value="Vision Pro HD" <?php if ($_POST['lens_category']=="Vision Pro HD") echo 'selected="selected"'; ?>>Vision Pro HD</option> 
 				 <option   value="Mini Pro HD" <?php if ($_POST['lens_category']=="Mini Pro HD") echo 'selected="selected"'; ?>>Mini Pro HD</option> 
 				 <option   value="Econo Choice" <?php if ($_POST['lens_category']=="Econo Choice") echo 'selected="selected"'; ?>>Econo Choice</option> 
  				 <option   value="Econo Choice Ultra Short" <?php if ($_POST['lens_category']=="Econo Choice Ultra Short") echo 'selected="selected"'; ?>>Econo Choice Ultra Short</option> 
  				 <option   value="Econo Choice Ultra One" <?php if ($_POST['lens_category']=="Econo Choice Ultra One") echo 'selected="selected"'; ?>>Econo Choice Ultra One</option> 
 				 <option   value="Pro EZ HD" <?php if ($_POST['lens_category']=="Pro EZ HD") echo 'selected="selected"'; ?>>Pro EZ HD</option>
                  <option   value="Innovative II DS" <?php if ($_POST['lens_category']=="Innovative II DS") echo 'selected="selected"'; ?>>Innovative II DS</option> 
 				 <option   value="Vision Classique HD" <?php if ($_POST['lens_category']=="Vision Classique HD") echo 'selected="selected"'; ?>>Vision Classique HD</option> 
                 <option   value="IPL"     <?php if ($_POST['lens_category']=="IPL")     echo 'selected="selected"'; ?>>Alpha (Formerly Optimize IPL)</option> 
                 <option   value="Acuform" <?php if ($_POST['lens_category']=="Acuform") echo 'selected="selected"'; ?>>Optimize ACUFORM</option> 
                 <option   value="FIT" <?php if ($_POST['lens_category']=="FIT") echo 'selected="selected"'; ?>>Optimize FIT</option> 
                 <option   value="Horizon" <?php if ($_POST['lens_category']=="Horizon") echo 'selected="selected"'; ?>>Optimize Horizon+</option> 
                 <option   value="DMT" <?php if ($_POST['lens_category']=="DMT") echo 'selected="selected"'; ?>>Innovative (Formerly DMT)</option> 
                 <option   value="Lifestyle" <?php if ($_POST['lens_category']=="Lifestyle") echo 'selected="selected"'; ?>>Office Premium</option>
                 <option   value="Anti-Fatigue" <?php if ($_POST['lens_category']=="Anti-Fatigue") echo 'selected="selected"'; ?>>Eye Fatigue</option>
                 <option   value="Purelife HD" <?php if ($_POST['lens_category']=="Purelife HD") echo 'selected="selected"'; ?>>Purelife HD</option> 
                 <option   value="Life II" <?php if ($_POST['lens_category']=="Life II") echo 'selected="selected"'; ?>>Life II</option> 
                 <option   value="Life XS" <?php if ($_POST['lens_category']=="Life XS") echo 'selected="selected"'; ?>>Life XS</option> 
                 <option   value="SelectionRx" <?php if ($_POST['lens_category']=="SelectionRx") echo 'selected="selected"'; ?>>SelectionRx</option> 
  				 <option   value="SV" <?php if ($_POST['lens_category']=="SV") echo 'selected="selected"'; ?>>SV</option> 
				 <option   value="ST-28" <?php if ($_POST['lens_category']=="ST-28") echo 'selected="selected"'; ?>>ST-28</option> 
				 <option   value="ST-25" <?php if ($_POST['lens_category']=="ST-25") echo 'selected="selected"'; ?>>ST-25</option> 
				 <option   value="Ovation" <?php if ($_POST['lens_category']=="Ovation") echo 'selected="selected"'; ?>>Ovation</option> 
                 <option   value="ELPS HD" <?php if ($_POST['lens_category']=="ELPS HD") echo 'selected="selected"'; ?>>ELPS HD</option> 
                 <option   value="PSI HD" <?php if ($_POST['lens_category']=="PSI HD") echo 'selected="selected"'; ?>>PSI HD</option>
                 <option   disabled="disabled" value="">&nbsp;</option>
				  <option  disabled="disabled" value="">MANUFACTURER*</option>
                 <option   value="ESSILOR" <?php if ($_POST['lens_category']=="ESSILOR") echo 'selected="selected"'; ?>>ESSILOR</option> 
                 <option   value="MY WORLD" <?php if ($_POST['lens_category']=="MY WORLD") echo 'selected="selected"'; ?>>MY WORLD</option> 
                 <option   value="OPTIMIZE" <?php if ($_POST['lens_category']=="OPTIMIZE") echo 'selected="selected"'; ?>>OPTIMIZE</option> 
                 <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option> 
                 <option   value="PRECISION" <?php if ($_POST['lens_category']=="PRECISION") echo 'selected="selected"'; ?>>PRECISION</option> 
                 <option   value="RODENSTOCK" <?php if ($_POST['lens_category']=="RODENSTOCK") echo 'selected="selected"'; ?>>RODENSTOCK</option> 
                 <option   value="SEIKO" <?php if ($_POST['lens_category']=="SEIKO") echo 'selected="selected"'; ?>>SEIKO</option> 
                 <option   value="SHAMIR" <?php if ($_POST['lens_category']=="SHAMIR") echo 'selected="selected"'; ?>>SHAMIR</option> 
                 <option   value="SOLA" <?php if ($_POST['lens_category']=="SOLA") echo 'selected="selected"'; ?>>SOLA</option>       
        </select>
               
				<?php
                }elseif($Product_line == 'eye-recommend'){
				//EYE RECOMMEND LENS CATEGORY	
				?>
                 <select name="lens_category" id="lens_category">
                 <option  disabled="disabled" value="">LENS CATEGORY*</option>
                 <option   value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>All</option> 
                 <option   value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                 <option   value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>All Progressives</option>
                 <option   value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                 <option   value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option   value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>    
                 <option   value="stock" <?php if ($_POST['lens_category']=="stock") echo 'selected="selected"'; ?>>Stock</option>       
                 <option  disabled="disabled" value="">&nbsp;</option>
                 <option  disabled="disabled" value="">LENS TYPE*</option>
                 <option   value="iAction" <?php if ($_POST['lens_category']=="iAction") echo 'selected="selected"'; ?>>iAction</option> 
                 <option   value="iAction SV" <?php if ($_POST['lens_category']=="iAction SV") echo 'selected="selected"'; ?>>iAction SV</option> 
                 <option   value="iFree" <?php if ($_POST['lens_category']=="iFree") echo 'selected="selected"'; ?>>iFree</option> 
                 <option   value="iOffice" <?php if ($_POST['lens_category']=="iOffice") echo 'selected="selected"'; ?>>iOffice</option> 
                 <option   value="iRelax" <?php if ($_POST['lens_category']=="iRelax") echo 'selected="selected"'; ?>>iRelax</option> 
                 <option   value="iReader" <?php if ($_POST['lens_category']=="iRelax") echo 'selected="selected"'; ?>>iReader</option> 
                 <option   value="Acuform" <?php if ($_POST['lens_category']=="Acuform") echo 'selected="selected"'; ?>>Universal (Formerly Optimize Acuform)</option> 
                 <option   value="IPL"     <?php if ($_POST['lens_category']=="IPL")     echo 'selected="selected"'; ?>>Alpha (Formerly Optimize IPL)</option> 
                 <option   value="DMT" <?php if ($_POST['lens_category']=="DMT") echo 'selected="selected"'; ?>>Innovative (Formerly DMT)</option> 
				 <option   value="Lifestyle" <?php if ($_POST['lens_category']=="Lifestyle") echo 'selected="selected"'; ?>>Office Premium</option>
                 <option   value="Anti-Fatigue" <?php if ($_POST['lens_category']=="Anti-Fatigue") echo 'selected="selected"'; ?>>Eye Fatigue</option>
                 <option   value="Pro EZ" <?php if ($_POST['lens_category']=="Pro EZ") echo 'selected="selected"'; ?>>Pro EZ HD</option>
                 <option   value="revolution" <?php if ($_POST['lens_category']=="revolution") echo 'selected="selected"'; ?>>Revolution</option>
 				 <option   value="revolution sv" <?php if ($_POST['lens_category']=="revolution sv") echo 'selected="selected"'; ?>>Revolution SV</option>
  				 <option   value="SV" <?php if ($_POST['lens_category']=="SV") echo 'selected="selected"'; ?>>SV</option> 
                  <option   value="camber" <?php if ($_POST['lens_category']=="camber") echo 'selected="selected"'; ?>>Ultimate Freestyle (Camber)</option> 
                 <option   disabled="disabled" value="">&nbsp;</option>
				 <option  disabled="disabled" value="">SOFTWARE DESIGN*</option>
 
                 <option   value="IOT" <?php if ($_POST['lens_category']=="IOT") echo 'selected="selected"'; ?>>IOT</option> 
                 <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option> 
                 <option   value="SHAMIR" <?php if ($_POST['lens_category']=="SHAMIR") echo 'selected="selected"'; ?>>SHAMIR</option> 
                    
        </select>
                
                <?php }//End if Eye Recommend?>
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
                        <option <?php if ($_SESSION['PrescrData']['base_curve'] == 8) echo ' selected';  ?> value=" 8">8</option>                   
					</select></span>
                   </td></tr>
                   
                   
                   
               </table>
             </div>
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="9" bgcolor="#000099" class="tableHead"><?php echo $lbl_pupdist_txt_pl;?>&nbsp; </td>
                   </tr>
                 <tr>
                   <td align="center" class="formCellNosides"> 
				   <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.D.';
					     }?><br />
                     <input name="RE_PD" type="text" class="formText" id="RE_PD" 
                     value="<?php	if ($_SESSION['PrescrData']['RE_PD']>0)
				 echo  $_SESSION['PrescrData']['RE_PD'];
				 ?>"
                      size="4" maxlength="4" />
                     <br />
                     <?php echo $adm_re_txt;?></td>
                   <td align="left" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/design_images/PD_dist.gif" alt="Pupillary Distance" width="91" height="53" /></td>
                   <td align="center" class="formCellNoleft">
				    <?php if ($mylang == 'lang_french'){
					 	 echo 'P.D. de loin';
				      	 }else{
					     echo 'P.D.';
					     }?>
                   <br />
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
                   <td align="center" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/design_images/PD_near.gif" alt="Pupillary Distance" width="91" height="53" /></td>
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
                   <td align="center" class="formCellNosides"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/design_images/PD_height.gif" alt="Pupillary Distance" width="91" height="44" /></td>
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
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="10" bgcolor="#000099" class="tableHead"><?php if ($mylang == 'lang_french') echo 'EPAISSEURS SPECIALES'; else echo ' SPECIAL THICKNESS';?></td>
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
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td colspan="8" bgcolor="#000099" class="tableHead">
				   <?php if ($Product_line <> 'eye-recommend'){
				  	 echo $lbl_otherspec_txt_pl;
				   }else{
				   	 echo '"i" Series, IPL and Acuform only';
				   }
				   ?>
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
                       
                       
                        <option value="SW007"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW007") echo "selected=\"selected\"";?>><?php echo 'SW007' ;?></option>
                        <option value="SW008"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW008") echo "selected=\"selected\"";?>><?php echo 'SW008' ;?></option>                        <option value="SW009"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW009") echo "selected=\"selected\"";?>><?php echo 'SW009' ;?></option>
                        
                        <option value="SW016"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW016") echo "selected=\"selected\"";?>><?php echo 'SW016' ;?></option>
                        <option value="SW025/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025/85") echo "selected=\"selected\"";?>><?php echo 'SW025/85' ;?></option>
                        <option value="SW022"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW022") echo "selected=\"selected\"";?>><?php echo 'SW022' ;?></option>
                         
                       <option value="SW025/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW025/75") echo "selected=\"selected\"";?>><?php echo 'SW025/75' ;?></option>
                       <option value="SW017"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW017") echo "selected=\"selected\"";?>><?php echo 'SW017' ;?></option>
                       <option value="GOL"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="GOL") echo "selected=\"selected\"";?>><?php echo 'GOL' ;?></option>
                       
                       <option value="SW027/50"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/50") echo "selected=\"selected\"";?>><?php echo 'SW027/50' ;?></option> 
                       <option value="SW027/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/75") echo "selected=\"selected\"";?>><?php echo 'SW027/75' ;?></option> 
                       <option value="SW027/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW027/85") echo "selected=\"selected\"";?>><?php echo 'SW027/85' ;?></option> 
                       
                       <option value="SW030/50"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/50") echo "selected=\"selected\"";?>><?php echo 'SW030/50' ;?></option> 
                       <option value="SW030/75"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/75") echo "selected=\"selected\"";?>><?php echo 'SW030/75' ;?></option> 
                       <option value="SW030/85"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW030/85") echo "selected=\"selected\"";?>><?php echo 'SW030/85' ;?></option> 
                       
                       <option value="SW043"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW043") echo "selected=\"selected\"";?>><?php echo 'SW043' ;?></option> 
                       <option value="SW044"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW044") echo "selected=\"selected\"";?>><?php echo 'SW044' ;?></option> 
                      <option value="SW051"<?php if ($_SESSION['PrescrData']['TINT_COLOR']=="SW051") echo "selected=\"selected\"";?>><?php echo 'SW051' ;?></option> 
                      
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
                       
                        <option value="remote edging" <?php if ($_SESSION['PrescrData']['JOB_TYPE']=="remote edging") echo "selected=\"selected\"";?>>
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
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="134" align="right" valign="top" class="formCell"><span class="tableSubHead"><?php echo $adm_specialinstructions_txt;?> </span></td>
                   <td width="502" valign="top"  class="tableSubHead"><textarea name="SPECIAL_INSTRUCTIONS" cols="70" rows="2" class="formText" id="SPECIAL_INSTRUCTIONS"><?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?></textarea></td>
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
                
            // TODO - Determine if S3 Bucket is still used, refactor to remove keys - Currently no user exists in AWS with these keys
            // AWS access info
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
            
            $sucess_action_redirect= constant('DIRECT_LENS_URL').'/close_page.php?filename='. $requestHeaders[Content-Disposition];//Page qui se ferme automatiquement
            
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
			<table width="650" align="center">
            <tr bgcolor="#000099">
                   <td width="134" align="center" valign="top"  class="tableHead"><?php echo 'UPLOAD A SHAPE';?></td>
            </tr>
            <tr>
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
                   <input type="file" name="file" onclick="btnupload.disabled=false;btnupload.value='Upload'" id="file" size="40">&nbsp;
                   <input type="submit" value="Upload" id="btnupload"   onclick="this.disabled=true;this.value='Uploaded';this.form.submit();" <?php if ($DisableUploadButton =='yes') echo ' disabled ';  ?>  />
                  </p>
				</div>
                </td></tr>               
              </table>
              </div>
              
               
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
</table>
</body>
</html>