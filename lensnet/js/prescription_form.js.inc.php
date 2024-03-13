<script language="JavaScript" type="text/javascript">
																											  
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
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
		theForm.ORDER_TYPE.value ="To Follow";
	}
	
}



 function resetform(theForm)
{
		theForm.LAST_NAME.value = '';
		theForm.FIRST_NAME.value= '';
		theForm.PT.value = '';
		theForm.PA.value = '';
		theForm.VERTEX.value = '';
		theForm.RE_PD.value = '';
		theForm.LE_PD.value = '';
		theForm.RE_PD_NEAR.value = '';
		theForm.LE_PD_NEAR.value = '';
		theForm.LE_HEIGHT.value = '';
		theForm.RE_HEIGHT.value = '';
		theForm.RE_AXIS.value = '';
		theForm.LE_AXIS.value = '';
		theForm.TRAY_NUM.value = '';
		theForm.PATIENT_REF_NUM.value = '';
		theForm.ENGRAVING.value = '';
		theForm.FRAME_A.value = '';
		theForm.FRAME_B.value = '';
		theForm.FRAME_ED.value = '';
		theForm.FRAME_DBL.value = '';
		theForm.TEMPLE.value = '';
		theForm.COLOR.value = '';
		theForm.SUPPLIER.value = '';
		theForm.FRAME_MODEL.value = '';
		theForm.TEMPLE_MODEL.value = '';
		theForm.LE_PR_AX.value  = '';
		theForm.LE_PR_AX2.value = '';
		theForm.RE_PR_AX.value  = '';
		theForm.RE_PR_AX2.value = '';
		theForm.FROM_PERC.value = '';
		theForm.TO_PERC.value = '';
		theForm.entry_fee.checked= false;
		
document.getElementById('Both').checked = true;
ActivateAll_fields(theForm);
document.getElementById('RE_PR_IO_None').checked = true;
document.getElementById('RE_PR_UD_None').checked = true;
document.getElementById('LE_PR_IO_None').checked = true;
document.getElementById('LE_PR_UD_None').checked = true;

var number1 = document.getElementById('INDEX');
selectItemByValue(number1, "ANY");

var number2 = document.getElementById('RE_SPH_NUM');
selectItemByValue(number2, "+0");

var number3 = document.getElementById('LE_SPH_NUM');
selectItemByValue(number3, "+0");

var number4 = document.getElementById('RE_SPH_DEC');
selectItemByValue(number4, ".00");

var number5 = document.getElementById('LE_SPH_DEC');
selectItemByValue(number5, ".00");

var number6 = document.getElementById('RE_CYL_NUM');
selectItemByValue(number6, "-0");

var number7 = document.getElementById('LE_CYL_NUM');
selectItemByValue(number7, "-0");

var number8 = document.getElementById('RE_CYL_DEC');
selectItemByValue(number8, ".00");

var number9 = document.getElementById('LE_CYL_DEC');
selectItemByValue(number9, ".00");

var number10 = document.getElementById('RE_ADD');
selectItemByValue(number10, "+0.00");

var number11 = document.getElementById('LE_ADD');
selectItemByValue(number11, "+0.00");

var number12 = document.getElementById('POLAR');
selectItemByValue(number12, "None");

var number13 = document.getElementById('PHOTO');
selectItemByValue(number13, "None");

var number14 = document.getElementById('SALESPERSON_ID');
selectItemByValue(number14, "");

var number15 = document.getElementById('COATING');
selectItemByValue(number15, "ANY");

var number16 = document.getElementById('rush');
selectItemByValue(number16, "no");

var number18 = document.getElementById('WARRANTY');
selectItemByValue(number18, "0");

var number19 = document.getElementById('BASE8');
selectItemByValue(number19, "no");


var number20 = document.getElementById('TINT');
selectItemByValue(number20, "None");

var number21 = document.getElementById('FRAME_TYPE');
selectItemByValue(number21, "");


var number22 = document.getElementById('lens_category');
selectItemByValue(number22, "all");


theForm.FROM_PERC.disabled = true;
theForm.TO_PERC.disabled = true;
theForm.TINT_COLOR.disabled = true;

}





function selectItemByValue(elmnt, value){
    for(var i=0; i < elmnt.options.length; i++)
    {
      if(elmnt.options[i].value == value)
        elmnt.selectedIndex = i;
    }
}



  
function validateRE_Axis(text) {
	var num=parseFloat(text.value);
	if (text.value=="") {
		return;
	}

	if (isNaN(num)) {
	
		 alert("<?php echo $lbl_alert26_pl;?>");
		focus();
		select();
		window.event.returnValue=false;
		return;
	}else if ((num>180)||(num<1)) {
		 alert("<?php echo $lbl_alert27_pl;?>");
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
			document.getElementById("spherechoice").style.display = "inline";
			//document.getElementById("myuploadbtn").disabled = false;
	
	}
function fixLE_SPH(form){//disable decimal if value is high or low

	if ((form.LE_SPH_NUM.value=="+14")||(form.LE_SPH_NUM.value=="-15")){
		form.LE_SPH_DEC.selectedIndex=3;
			form.LE_SPH_DEC.disabled=true;
			}
	else{
		form.LE_SPH_DEC.disabled=false;
			}
		
			document.getElementById("spherechoice").style.display = "inline";
			//document.getElementById("myuploadbtn").disabled = false;
		
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

function validate(theForm){
	
//alert(theForm.EYE[0].checked);Both
//alert(theForm.EYE[1].checked);Right only
//alert(theForm.EYE[2].checked);Left only
			
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

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

if((theForm.TINT.value=="Gradient")&&((theForm.FROM_PERC.value=="")||(theForm.TO_PERC.value==""))){//SOLID TINT
    alert("<?php echo $lbl_alert2_pl;?>");
   theForm.FROM_PERC.focus();
    return (false);
}


if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value<5)){//SOLID TINT
    alert("<?php echo 'The minimum value for the tint is 5%';?>");
   theForm.FROM_PERC.focus();
    return (false);
}

if((theForm.TINT.value=="Gradient")&&((theForm.FROM_PERC.value<5)||(theForm.TO_PERC.value< 5))){//SOLID TINT
    alert("<?php echo 'The minimum value for the tint is 5%';?>");
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

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>3.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX.focus();
    return (false);
  }
   if ((theForm.RE_PR_AX2.value!="")&&((theForm.RE_PR_AX2.value<.1)||(theForm.RE_PR_AX2.value>3.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX2.focus();
    return (false);
  }
   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>3.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR_AX.focus();
    return (false);
  }
  
   if ((theForm.LE_PR_AX2.value!="")&&((theForm.LE_PR_AX2.value<.1)||(theForm.LE_PR_AX2.value>3.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR2_AX.focus();
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
  
   if ((theForm.FRAME_A.value> 65)||(theForm.FRAME_A.value<35))
  {
    alert("<?php echo 'The value of Frame A must be between 35 AND 65';?>");
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
