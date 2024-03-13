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
	else 	if(theForm.TINT.value=="Solid 60"){//SOLID TINT
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
		theForm.TO_PERC.disabled=true;
		
		theForm.TO_PERC.value="";
	}
	else 	if(theForm.TINT.value=="Solid 80"){//SOLID TINT
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
	
	
	
	if (theForm.RE_HEIGHT.value=="")
  {
    alert("<?php echo 'Vous devez entrer la hauteur de droite';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
  }
  
  if (theForm.LE_HEIGHT.value=="")
  {
    alert("<?php echo 'Vous devez entrer la hauteur de gauche';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
  }
	
	

if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
return (false);
}

if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
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
  
	
//Validate if Lens category = prog 14	
if((theForm.lens_category.value=="prog 14")&&(theForm.LE_HEIGHT.value>18)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.LE_HEIGHT.value<14)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.RE_HEIGHT.value>18)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.RE_HEIGHT.value<14)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}



//Validate if Lens category = prog 17	
if((theForm.lens_category.value=="prog 17")&&(theForm.LE_HEIGHT.value>30)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.LE_HEIGHT.value<17)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.RE_HEIGHT.value>30)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.RE_HEIGHT.value<17)){
    alert("<?php echo 'Valeur de hauteur non-disponible';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}	
	
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

if((theForm.TINT.value=="GRADIENT")&&((theForm.FROM_PERC.value=="")||(theForm.TO_PERC.value==""))){//SOLID TINT
    alert("<?php echo $lbl_alert2_pl;?>");
   theForm.FROM_PERC.focus();
    return (false);
}


 if ((theForm.RE_HEIGHT.value>0)&&(theForm.RE_ADD.value=="+0.00"))
  {
    alert("<?php echo 'Vous devez entrer une addition pour l\'oeil droit';?>");
    theForm.RE_ADD.focus();
    return (false);
  }
  
  if ((theForm.LE_HEIGHT.value>0)&&(theForm.LE_ADD.value=="+0.00"))
  {
    alert("<?php echo 'Vous devez entrer une addition pour l\'oeil gauche';?>");
    theForm.LE_ADD.focus();
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
  







}	





function validateSV(theForm){

if(theForm.DIAMETER.value==""){//Diametre
    alert("<?php echo 'Vous devez entrer un diametre';?>");
   theForm.DIAMETER.focus();
    return (false);
}
 

if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
return (false);
}

if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
return (false);
}	



	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}
 
 
  if (theForm.RE_PD_NEAR.value== "")
  {
    alert("<?php echo $lbl_alert17_pl;?>");
    theForm.RE_PD_NEAR.focus();
    return (false);
  }
  
 if (theForm.LE_PD_NEAR.value== "")
  {
    alert("<?php echo $lbl_alert18_pl;?>");
    theForm.LE_PD_NEAR.focus();
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
  
  
  
 
  
  
  

}	

</script>
