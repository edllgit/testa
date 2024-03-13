<script language="JavaScript" type="text/javascript">
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

function validate(theForm)
{
	
		
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){//SOLID TINT
    alert("Please enter a Solid tint percentage in the From: field.");
   theForm.FROM_PERC.focus();
    return (false);
}

if((theForm.TINT.value=="GRADIENT")&&((theForm.FROM_PERC.value=="")||(theForm.TO_PERC.value==""))){//SOLID TINT
    alert("Please enter a tint Gradient percentages in the From: and To: fields.");
   theForm.FROM_PERC.focus();
    return (false);
}

if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.SUPPLIER.value=="")){
alert("Please enter a value for the \"Supplier\" field.");
   theForm.SUPPLIER.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.FRAME_MODEL.value=="")){
alert("Please enter a value for the \"Frame Model\" field.");
   theForm.FRAME_MODEL.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.TEMPLE_MODEL.value=="")){
alert("Please enter a value for the \"Temple Model\" field.");
   theForm.TEMPLE_MODEL.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.COLOR.value=="")){
alert("Please enter a value for the \"Color\" field.");
   theForm.COLOR.focus();
    return (false);
}
if((theForm.JOB_TYPE.value=="Edge and Mount")&&(theForm.TEMPLE.value=="")){
alert("Please enter a value for the \"Temple\" field.");
   theForm.TEMPLE.focus();
    return (false);
}

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>6.0)))
  {
    alert("Prism value must be between 0.1 and 5.0.");
    theForm.RE_PR_AX.focus();
    return (false);
  }
   if ((theForm.RE_PR_AX2.value!="")&&((theForm.RE_PR_AX2.value<.1)||(theForm.RE_PR_AX2.value>6.0)))
  {
    alert("Prism value must be between 0.1 and 5.0.");
    theForm.RE_PR_AX2.focus();
    return (false);
  }
   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>6.0)))
  {
    alert("Prism value must be between 0.1 and 5.0.");
    theForm.LE_PR_AX.focus();
    return (false);
  }
  
   if ((theForm.LE_PR_AX2.value!="")&&((theForm.LE_PR_AX2.value<.1)||(theForm.LE_PR_AX2.value>6.0)))
  {
    alert("Prism value must be between 0.1 and 5.0.");
    theForm.LE_PR2_AX.focus();
    return (false);
  }
  
    if ((theForm.PT.value!="")&&((theForm.PT.value<1)||(theForm.PT.value>20)))
  {
    alert("PT value must be between 1 and 20.");
    theForm.PT.focus();
    return (false);
  }
  
    if ((theForm.PA.value!="")&&((theForm.PA.value<0)||(theForm.PA.value>25)))
  {
    alert("PA value must be between 0 and 25.");
    theForm.PA.focus();
    return (false);
  }
  
    if ((theForm.VERTEX.value!="")&&((theForm.VERTEX.value<1)||(theForm.VERTEX.value>20)))
  {
    alert("Vertex value must be between 1 and 20.");
    theForm.VERTEX.focus();
    return (false);
  }
  
 if ((theForm.FRAME_B.value*2)<(theForm.RE_HEIGHT.value*2))
  {
    alert("Frame B height must be greater than R.E. Fitting Height.");
    theForm.FRAME_B.focus();
    return (false);
  }
  
   if ((theForm.FRAME_B.value*2)<(theForm.LE_HEIGHT.value*2))
  {
    alert("Frame B height must be greater than L.E. Fitting Height.");
    theForm.FRAME_B.focus();
    return (false);
  }
  
if ((theForm.RE_AXIS.value== "")&&(((theForm.RE_CYL_NUM.value!="+0")&&(theForm.RE_CYL_NUM.value!="-0"))||(theForm.RE_CYL_DEC.value!=".00")))
  {
    alert("Please enter a value for the \"RE Axis\" field.");
    theForm.RE_AXIS.focus();
    return (false);
  }
    if ((theForm.LE_AXIS.value== "")&&(((theForm.LE_CYL_NUM.value!="+0")&&(theForm.LE_CYL_NUM.value!="-0"))||(theForm.LE_CYL_DEC.value!=".00")))
  {
    alert("Please enter a value for the \"LE Axis\" field.");
    theForm.LE_AXIS.focus();
    return (false);
  }
  
   if (theForm.INDEX.value== "")
  {
    alert("Please select a value for the \"Material Index\" field.");
    theForm.INDEX.focus();
    return (false);
  }
      if (theForm.RE_PD.value== "")
  {
    alert("Please enter a value for the \"PD RE\" field.");
    theForm.RE_PD.focus();
    return (false);
  }
  
 if (theForm.LE_PD.value== "")
  {
    alert("Please enter a value for the \"PD LE\" field.");
    theForm.LE_PD.focus();
    return (false);
  }
  
    if ((theForm.RE_HEIGHT.value=="")&&(theForm.RE_ADD.value!="+0.00"))
  {
    alert("Please enter a value for the \"RE Height\" field.");
    theForm.RE_HEIGHT.focus();
    return (false);
  }
  
 if ((theForm.LE_HEIGHT.value=="")&&(theForm.LE_ADD.value!="+0.00"))
  {
    alert("Please enter a value for the \"LE Height\" field.");
    theForm.LE_HEIGHT.focus();
    return (false);
  }
  
   if (theForm.FRAME_A.value== "")
  {
    alert("Please enter a value for the \"Frame A\" field.");
    theForm.FRAME_A.focus();
    return (false);
  }  
   if (theForm.FRAME_B.value== "")
  {
    alert("Please enter a value for the \"Frame B\" field.");
    theForm.FRAME_B.focus();
    return (false);
  }  
   if (theForm.FRAME_ED.value== "")
  {
    alert("Please enter a value for the \"Frame ED\" field.");
    theForm.FRAME_ED.focus();
    return (false);
  }  
   if (theForm.FRAME_DBL.value== "")
  {
    alert("Please enter a value for the \"Frame DBL\" field.");
    theForm.FRAME_DBL.focus();
    return (false);
  }
  
   if (theForm.FRAME_TYPE.value== "")
  {
    alert("Please select a frame type.");
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
</script>