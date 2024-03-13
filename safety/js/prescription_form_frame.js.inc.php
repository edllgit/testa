<script language="JavaScript" type="text/javascript">
																											  
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}



function validerFrameType() { //v2.0
 var type_montage  =document.forms["PRESCRIPTION"]["FRAME_TYPE"].value;
 
if (type_montage == 'Nylon Groove'){
	alert('Pour ce type de taillage l\'indice 1.50 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
	}

if (type_montage == 'Drill and Notch'){
	alert('Pour ce type de taillage l\'indice 1.50 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
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
	
	else if(theForm.TINT.value=="Solid 70 n"){//Solid 70% NURBS TINT
	alert('passe 70');
		theForm.TINT_COLOR.disabled=false;
		theForm.TINT_COLOR.options[3].disabled=true;
		theForm.TINT_COLOR.options[0].disabled=true;
		theForm.TINT_COLOR.options[1].disabled=false;
		theForm.TINT_COLOR.options[4].disabled=false;
	}
	
	
	else if(theForm.TINT.value=="Solid 80 n"){//Solid 80% NURBS TINT
	alert('passe 80');
		theForm.TINT_COLOR.disabled=false;
		theForm.TINT_COLOR.options[1].disabled=false;
		theForm.TINT_COLOR.options[2].disabled=false;
		theForm.TINT_COLOR.options[3].disabled=false;
		theForm.TINT_COLOR.options[4].disabled=false;
		
		theForm.TINT_COLOR.options[1].disabled=true;
		theForm.TINT_COLOR.options[2].disabled=true;
		theForm.TINT_COLOR.options[3].disabled=true;
		alert('test'.theForm.TINT_COLOR.options[3].value);
	}
	
	
	else if(theForm.TINT.value=="Solid 85 n"){//Solid 85% NURBS TINT
	alert(theForm.TINT_COLOR.options[2].value);
		theForm.TINT_COLOR.disabled=false;
		theForm.TINT_COLOR.options[3].disabled=true;//Green
		theForm.TINT_COLOR.options[0].disabled=true;
		theForm.TINT_COLOR.options[1].disabled=false;
		theForm.TINT_COLOR.options[4].disabled=false;
	}
	
	
	else {
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


document.PRESCRIPTION.LE_AXIS.value=document.PRESCRIPTION.RE_AXIS.value;
	
 fixLE_SPH(document.PRESCRIPTION);
 fixLE_CYL(document.PRESCRIPTION);
 
 	for (i=0;i<3;i++){
		if (document.PRESCRIPTION.RE_PR_IO[i].checked==true){
		document.PRESCRIPTION.LE_PR_IO[i].checked=true;}
		}

document.PRESCRIPTION.LE_PR_AX.value=document.PRESCRIPTION.RE_PR_AX.value;		

	
}




function validate(theForm){
var dust_bar_checked = document.getElementById("dust_bar").checked;
var cushion_checked  = document.getElementById("cushion").checked;

customer_language = 'english';
var customer_language  = document.getElementById("mylang").value;
if (customer_language == 'lang_french')
customer_language = 'french';
else
customer_language = 'english';


if ((dust_bar_checked == true) && (cushion_checked == true)){
alert('Vous ne pouvez pas sélectionner en même temps les Coussinets et les Pare-Poussières');
return (false);
}
  
  if (theForm.FRAME_TYPE.value== "")
  {
    alert("Vous devez choisir le type de monture.");
    theForm.FRAME_TYPE.focus();
    return (false);
  }
  	
	
  if (theForm.RE_HEIGHT.value=="")
  {
    alert("<?php echo 'Right eye height is mandatory';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
  }
  
  if (theForm.LE_HEIGHT.value=="")
  {
    alert("<?php echo 'Left eye height is mandatory';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
  }
		
//Optical Center
var oc = document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
if (isNaN(oc)) 
{
	alert("<?php echo 'Optical Center must be a number';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
}   	
		
 //Validate the pds
   var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
	if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
  {
	alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 25 et 50';?>");
	theForm.RE_PD.focus();
	return (false);
  }

		if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
  {
	alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 25 et 50';?>");
	theForm.LE_PD.focus();
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

if((theForm.TINT.value=="Solid 70")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
return (false);
}	
		
		
if((theForm.TINT.value=="Solid 85")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
theForm.TINT_COLOR.focus();
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

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX.focus();
    return (false);
  }

   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR_AX.focus();
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

  
  
  

}	

function validateSV(theForm){
	
var dust_bar_checked = document.getElementById("dust_bar").checked;
var cushion_checked  = document.getElementById("cushion").checked;

customer_language = 'english';
var customer_language  = document.getElementById("mylang").value;
if (customer_language == 'lang_french')
customer_language = 'french';
else
customer_language = 'english';


if ((dust_bar_checked == true) && (cushion_checked == true)){
alert('Vous ne pouvez pas sélectionner en même temps les Coussinets et les Pare-Poussières');
return (false);
}	
	
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

 if (theForm.FRAME_TYPE.value== "")
  {
    alert("Vous devez choisir un type de monture.");
    theForm.FRAME_TYPE.focus();
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

 
 
//Validate the pds
  var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
    if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
  {
    alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 25 et 50';?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
    if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
  {
    alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 25 et 50';?>");
    theForm.LE_PD.focus();
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





function validateFr(theForm){
var dust_bar_checked = document.getElementById("dust_bar").checked;
var cushion_checked  = document.getElementById("cushion").checked;

customer_language = 'english';
var customer_language  = document.getElementById("mylang").value;
if (customer_language == 'lang_french')
customer_language = 'french';
else
customer_language = 'english';


if ((dust_bar_checked == true) && (cushion_checked == true)){
alert('Problem with the extras: You cannot select both the Dust Bar and the Cushion');
return (false);
}
	
	
if (theForm.FRAME_TYPE.value== "")
{
  alert("Please select a frame type.");
  theForm.FRAME_TYPE.focus();
  return (false);
}

	
if (theForm.RE_HEIGHT.value=="")
  {
    alert("<?php echo 'The right fitting height is mandatory';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
  }
  
  if (theForm.LE_HEIGHT.value=="")
  {
    alert("<?php echo 'The left fitting height is mandatory';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
  }
		
		
//Optical Center
var oc = document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
if (isNaN(oc)) 
{
	alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
}   
		
  //Validate the pds
  var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
	if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
  {
	alert("<?php echo 'The right eye PD must be between 25 and 50';?>");
	theForm.RE_PD.focus();
	return (false);
  }

		if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
  {
	alert("<?php echo 'The left eye PD must be between 25 and 50';?>");
	theForm.LE_PD.focus();
	return (false);
  }
	
if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'You must select the tint color';?>");
theForm.TINT_COLOR.focus();
return (false);
}

if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'You must select the tint color';?>");
theForm.TINT_COLOR.focus();
return (false);
}	
		
	
//Validate if Lens category = prog 14	
if((theForm.lens_category.value=="prog 14")&&(theForm.LE_HEIGHT.value>18)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.LE_HEIGHT.value<14)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.RE_HEIGHT.value>18)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 14")&&(theForm.RE_HEIGHT.value<14)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}



//Validate if Lens category = prog 17	
if((theForm.lens_category.value=="prog 17")&&(theForm.LE_HEIGHT.value>30)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.LE_HEIGHT.value<17)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.LE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.RE_HEIGHT.value>30)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}

if((theForm.lens_category.value=="prog 17")&&(theForm.RE_HEIGHT.value<17)){
    alert("<?php echo 'This fitting height is not available';?>");
    theForm.RE_HEIGHT.focus();
    return (false);
}

	
	
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

 if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.RE_PR_AX.focus();
    return (false);
  }

   if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>12.0)))
  {
    alert("<?php echo $lbl_alert8_pl;?>");
    theForm.LE_PR_AX.focus();
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
  
  
    if ((theForm.RE_HEIGHT.value>0)&&(theForm.RE_ADD.value=="+0.00"))
  {
    alert("<?php echo 'The right addition is mandatory';?>");
    theForm.RE_ADD.focus();
    return (false);
  }
  
  if ((theForm.LE_HEIGHT.value>0)&&(theForm.LE_ADD.value=="+0.00"))
  {
    alert("<?php echo 'The left addition is mandatory';?>");
    theForm.LE_ADD.focus();
    return (false);
  }
 }	









function validateSVEN(theForm){
	
var dust_bar_checked = document.getElementById("dust_bar").checked;
var cushion_checked  = document.getElementById("cushion").checked;

customer_language = 'english';
var customer_language  = document.getElementById("mylang").value;
if (customer_language == 'lang_french')
customer_language = 'french';
else
customer_language = 'english';


if ((dust_bar_checked == true) && (cushion_checked == true)){
alert('Problem with the extras: You cannot select both the Dust Bar and the Cushion');
return (false);
}
	
	
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

 if (theForm.FRAME_TYPE.value== "")
  {
    alert("Please select a frame type.");
    theForm.FRAME_TYPE.focus();
    return (false);
  }

//Optical Center
var oc = document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
if (isNaN(oc)) 
{
	alert("<?php echo 'Optical Center must be a number';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
}   
  
 
 
 
//Validate the pds
  var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
    if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
  {
    alert("<?php echo 'Right eye PD must be between 25 and 50';?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
    if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
  {
    alert("<?php echo 'Left eye PD must be between 25 and 50';?>");
    theForm.LE_PD.focus();
    return (false);
  }
   
 
if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'You need to select the tint color';?>");
theForm.TINT_COLOR.focus();
return (false);
}

if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
alert("<?php echo 'You need to select the tint color';?>");
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




function validateEntrepotSafe(theForm){
		
	if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
	alert("<?php echo $lbl_alert25_pl;?>");
	theForm.LAST_NAME.focus();
	return (false);
	}
	
	//Validate the pds
  var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
//Optical Center
var oc = document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
if (isNaN(oc)) 
{
	alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
}   
  
  
	if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
	{
	alert("<?php echo 'The right eye PD must be between 25 and 50';?>");
	theForm.RE_PD.focus();
	return (false);
	}
	
	if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
	{
	alert("<?php echo 'The left eye PD must be between 25 and 50';?>");
	theForm.LE_PD.focus();
	return (false);
	} 
	  
	if (theForm.RE_HEIGHT.value=="")
	{
	alert("<?php echo 'The right fitting height is mandatory';?>");
	theForm.RE_HEIGHT.focus();
	return (false);
	}
	  
	if (theForm.LE_HEIGHT.value=="")
	{
	alert("<?php echo 'The left fitting height is mandatory';?>");
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
	  
	  
	if ((theForm.RE_HEIGHT.value>0)&&(theForm.RE_ADD.value=="+0.00"))
	{
	alert("<?php echo 'The right addition is mandatory';?>");
	theForm.RE_ADD.focus();
	return (false);
	}
	  
	if ((theForm.LE_HEIGHT.value>0)&&(theForm.LE_ADD.value=="+0.00"))
	{
	alert("<?php echo 'The left addition is mandatory';?>");
	theForm.LE_ADD.focus();
	return (false);
	}
	  
	if ((theForm.FRAME_A.value> 66)||(theForm.FRAME_A.value<35))
	{
	alert("<?php echo 'La valeur du Frame A doit etre entre 35 et 66';?>");
	theForm.FRAME_A.focus();
	return (false);
	}  
	  
	if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
	{
	alert("<?php echo 'La valeur du  Frame B doit etre entre 20 et 52';?>");
	theForm.FRAME_B.focus();
	return (false);
	}  
		
	if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>70))
	{
	alert("<?php echo 'La valeur du Frame ED doit etre entre 35 et 70';?>");
	theForm.FRAME_ED.focus();
	return (false);
	}  
	  
	if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>25))
	{
	alert("<?php echo 'La valeur du Frame DBL doit etre entre 10 et 25';?>");
	theForm.FRAME_DBL.focus();
	return (false);
	}  
	  
	if (theForm.FRAME_TYPE.value== "")
	{
	alert("Please select a frame type.");
	theForm.FRAME_TYPE.focus();
	return (false);
	}
		
	if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
	alert("<?php echo 'You must select the tint color';?>");
	theForm.TINT_COLOR.focus();
	return (false);
	}
	
	if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
	alert("<?php echo 'You must select the tint color';?>");
	theForm.TINT_COLOR.focus();
	return (false);
	}	
	
	if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>12.0)))
	{
	alert("<?php echo $lbl_alert8_pl;?>");
	theForm.RE_PR_AX.focus();
	return (false);
	}
	
	if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>12.0)))
	{
	alert("<?php echo $lbl_alert8_pl;?>");
	theForm.LE_PR_AX.focus();
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
	  
	var dust_bar_checked = document.getElementById("dust_bar").checked;
	var cushion_checked  = document.getElementById("cushion").checked;
	
	customer_language = 'english';
	var customer_language  = document.getElementById("mylang").value;
	if (customer_language == 'lang_french')
	customer_language = 'french';
	else
	customer_language = 'english';
	
	if ((dust_bar_checked == true) && (cushion_checked == true)){
	alert('Problem with the extras: You cannot select both the Dust Bar and the Cushion');
	return (false);
	}
}		




function validateSVEntrepot(theForm){
		
	if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
	alert("<?php echo $lbl_alert25_pl;?>");
	theForm.LAST_NAME.focus();
	return (false);
	}
	
	
//Optical Center
var oc = document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
if (isNaN(oc)) 
{
	alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
}   
  
	
	//Validate the pds
	  var x=document.forms["PRESCRIPTION"]["RE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Right eye PD must be a number';?>");
	theForm.RE_PD.focus();
	return (false);
  } 
  
  var x=document.forms["PRESCRIPTION"]["LE_PD"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'Left eye PD must be a number';?>");
	theForm.LE_PD.focus();
	return (false);
  } 
  
	if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <25))
	{
	alert("<?php echo 'The right eye PD must be between 25 and 50';?>");
	theForm.RE_PD.focus();
	return (false);
	}
	
	if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <25))
	{
	alert("<?php echo 'The left eye PD must be between 25 and 50';?>");
	theForm.LE_PD.focus();
	return (false);
	} 
	    	  
			  
	if (theForm.FRAME_A.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame A\".");
    theForm.FRAME_A.focus();
    return (false);
  }  
   if (theForm.FRAME_B.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame B\".");
    theForm.FRAME_B.focus();
    return (false);
  }  
   if (theForm.FRAME_ED.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame ED\".");
    theForm.FRAME_ED.focus();
    return (false);
  }  
   if (theForm.FRAME_DBL.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame DBL\".");
    theForm.FRAME_DBL.focus();
    return (false);
  }
  

   if (theForm.FRAME_TYPE.value== "")
  {
    alert("Veuillez sélectionner un type de monture.");
    theForm.FRAME_TYPE.focus();
    return (false);
  }

		  
	if ((theForm.FRAME_A.value> 66)||(theForm.FRAME_A.value<35))
	{
	alert("<?php echo 'La valeur du Frame A doit etre entre 35 et 66';?>");
	theForm.FRAME_A.focus();
	return (false);
	}  
	  
	if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
	{
	alert("<?php echo 'La valeur du  Frame B doit etre entre 20 et 52';?>");
	theForm.FRAME_B.focus();
	return (false);
	}  
		
	if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>70))
	{
	alert("<?php echo 'La valeur du Frame ED doit etre entre 35 et 70';?>");
	theForm.FRAME_ED.focus();
	return (false);
	}  
	  
	if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>25))
	{
	alert("<?php echo 'La valeur du Frame DBL doit etre entre 10 et 25';?>");
	theForm.FRAME_DBL.focus();
	return (false);
	}  
	  
	if (theForm.FRAME_TYPE.value== "")
	{
	alert("Veuillez choisir le type de montage.");
	theForm.FRAME_TYPE.focus();
	return (false);
	}
		
	if((theForm.TINT.value=="Solid 60")&&(theForm.TINT_COLOR.value=="none")){
	alert("<?php echo 'You must select the tint color';?>");
	theForm.TINT_COLOR.focus();
	return (false);
	}
	
	if((theForm.TINT.value=="Solid 80")&&(theForm.TINT_COLOR.value=="none")){
	alert("<?php echo 'You must select the tint color';?>");
	theForm.TINT_COLOR.focus();
	return (false);
	}	
	
	if ((theForm.RE_PR_AX.value!="")&&((theForm.RE_PR_AX.value<.1)||(theForm.RE_PR_AX.value>12.0)))
	{
	alert("<?php echo $lbl_alert8_pl;?>");
	theForm.RE_PR_AX.focus();
	return (false);
	}
	
	if ((theForm.LE_PR_AX.value!="")&&((theForm.LE_PR_AX.value<.1)||(theForm.LE_PR_AX.value>12.0)))
	{
	alert("<?php echo $lbl_alert8_pl;?>");
	theForm.LE_PR_AX.focus();
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
	  
	var dust_bar_checked = document.getElementById("dust_bar").checked;
	var cushion_checked  = document.getElementById("cushion").checked;
	
	customer_language = 'english';
	var customer_language  = document.getElementById("mylang").value;
	if (customer_language == 'lang_french')
	customer_language = 'french';
	else
	customer_language = 'english';
	
	if ((dust_bar_checked == true) && (cushion_checked == true)){
	alert('Problem with the extras: You cannot select both the Dust Bar and the Cushion');
	return (false);
	}
}		


</script>