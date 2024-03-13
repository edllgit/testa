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
	
	
	if (theForm.EYE[0].checked==true){//Both Eyes
		ActivateAll_fields();
	}
	
	if (theForm.EYE[1].checked==true){//Right Eye Only
		DesactivateLE_fields();
	}
	
	if (theForm.EYE[2].checked==true){//Left Eye only
		DesactivateRE_fields();
	}
}	
	
	
	
	
		
		
function validateVerresSeulement(theForm){

//alert(theForm.EYE[0].checked);Both
//alert(theForm.EYE[1].checked);Right only
//alert(theForm.EYE[2].checked);Left only
		
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
			
//Valider que le cylindre est fournit si on a un cylindre
if ((theForm.RE_AXIS.value > 0) && (theForm.RE_CYL_DEC.value==".00"))
{
	if ((theForm.RE_CYL_NUM.value=="+0")||(theForm.RE_CYL_NUM.value=="-0"))
	{
		alert("Vous devez entrer le cylindre droit")
		return (false);
	}
}

if ((theForm.LE_AXIS.value > 0) && (theForm.LE_CYL_DEC.value==".00"))
{
	if ((theForm.LE_CYL_NUM.value=="+0")||(theForm.LE_CYL_NUM.value=="-0"))
	{
		alert("Vous devez entrer le cylindre gauche")
		return (false);
	}
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
		
var x=document.forms["PRESCRIPTION"]["FRAME_A"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La valeur du champ Frame A doit etre un nombre';?>");
	theForm.FRAME_A.focus();
	return (false);
  } 
  		
 if (theForm.FRAME_A.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame A\".");
    theForm.FRAME_A.focus();
    return (false);
  }  
  
    if (theForm.FRAME_A.value != ""){
	  if ((theForm.FRAME_A.value> 75)||(theForm.FRAME_A.value<35))
	  {
		alert("<?php echo 'La valeur du Frame A doit etre entre 35 et 75';?>");
		theForm.FRAME_A.focus();
		return (false);
	  }  
  }
  
  
  var x=document.forms["PRESCRIPTION"]["FRAME_B"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La valeur du champ Frame B doit etre un nombre';?>");
	theForm.FRAME_B.focus();
	return (false);
  } 
   if (theForm.FRAME_B.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame B\".");
    theForm.FRAME_B.focus();
    return (false);
  }  
  
  if (theForm.FRAME_B.value != ""){
	  if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
	  {
		alert("<?php echo 'La valeur du  Frame B doit etre entre 20 et 52';?>");
		theForm.FRAME_B.focus();
		return (false);
	  }  
  }
  
  
  var x=document.forms["PRESCRIPTION"]["FRAME_ED"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La valeur du champ Frame ED doit etre un nombre';?>");
	theForm.FRAME_ED.focus();
	return (false);
  } 
   if (theForm.FRAME_ED.value== "")
  {
    alert("Veuillez entrer une valeur pour le champ \"Frame ED\".");
    theForm.FRAME_ED.focus();
    return (false);
  }  
  
  if (theForm.FRAME_ED.value != ""){
	  if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>70))
	  {
		alert("<?php echo 'La valeur du Frame ED doit etre entre 35 et 70';?>");
		theForm.FRAME_ED.focus();
		return (false);
	  }  
}
  
  
  var x=document.forms["PRESCRIPTION"]["FRAME_DBL"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La valeur du champ Frame DBL doit etre un nombre';?>");
	theForm.FRAME_DBL.focus();
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
  
  
  
if (theForm.FRAME_DBL.value != ""){
  if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>25))
  {
    alert("<?php echo 'La valeur du Frame DBL doit etre entre 10 et 25';?>");
    theForm.FRAME_DBL.focus();
    return (false);
  }  
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
  
  
   if (theForm.EYE[2].checked==false)
  {
		if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <20))
	  {
		alert("<?php echo 'Right eye PD must be between 20 and 50';?>");
		theForm.RE_PD.focus();
		return (false);
	  }
  }
  
  
   if (theForm.EYE[2].checked==true)
  {
		if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <20))
	  {
		alert("<?php echo 'Left eye PD must be between 20 and 50';?>");
		theForm.LE_PD.focus();
		return (false);
	  }
  }

	if((theForm.TINT.value=="Gradient")&&((theForm.FROM_PERC.value=="")||(theForm.TO_PERC.value==""))){//Gradient TINT
		alert("<?php echo $lbl_alert2_pl;?>");
	   theForm.FROM_PERC.focus();
		return (false);
	}


}//Fin validate Verres seulement EntrepotIFC		
		
		
		
		
		
		

function DesactivateLE_fields(form){//disable all left eye fields
	form.LE_SPH_NUM.disabled=true;
	form.LE_SPH_DEC.disabled=true;
	form.LE_CYL_NUM.disabled=true;
	form.LE_CYL_DEC.disabled=true;
	form.LE_ADD.disabled=true;
	form.LE_PR_IO.disabled=true;
	form.LE_PR_AX.disabled=true;
	form.LE_PR_AX2.disabled=true;
	form.LE_AXIS.disabled=true;
	form.LE_CT.disabled=true;
	form.LE_ET.disabled=true;
	form.LE_PD.disabled=true;
	form.LE_HEIGHT.disabled=true;
	form.RE_SPH_NUM.disabled=false;
	form.RE_SPH_DEC.disabled=false;
	form.RE_CYL_NUM.disabled=false;
	form.RE_CYL_DEC.disabled=false;
	form.RE_ADD.disabled=false;
	form.RE_PR_IO.disabled=false;
	form.RE_PR_AX.disabled=false;
	form.RE_HEIGHT.disabled=false;
	form.RE_AXIS.disabled=false;
	form.RE_CT.disabled=false;
	form.RE_ET.disabled=false;
	form.RE_PD.disabled=false;
	form.RE_HEIGHT.disabled=false;
	form.RE_PR_AX2.disabled=false;
	}


function DesactivateRE_fields(form){//disable all Right eye fields
	form.RE_SPH_NUM.disabled=true;
	form.RE_SPH_DEC.disabled=true;
	form.RE_CYL_NUM.disabled=true;
	form.RE_CYL_DEC.disabled=true;
	form.RE_ADD.disabled=true;
	form.RE_CT.disabled=true;
	form.RE_ET.disabled=true;
	form.RE_PD.disabled=true;
	form.RE_PR_IO.disabled=true;
	form.RE_PR_AX.disabled=true;
	form.RE_PR_AX2.disabled=true;
	form.RE_HEIGHT.disabled=true;
	form.RE_AXIS.disabled=true;
	form.LE_SPH_NUM.disabled=false;
	form.LE_SPH_DEC.disabled=false;
	form.LE_CYL_NUM.disabled=false;
	form.LE_CYL_DEC.disabled=false;
	form.LE_ADD.disabled=false;
	form.LE_PR_IO.disabled=false;
	form.LE_PR_AX.disabled=false;
	form.LE_HEIGHT.disabled=false;
	form.LE_AXIS.disabled=false;
	form.LE_PR_AX2.disabled=false;
	form.LE_PD.disabled=false;
	form.LE_CT.disabled=false;
	form.LE_ET.disabled=false;
	}
	
	
	
	function ActivateAll_fields(form){//enable all  eye fields
	form.LE_SPH_NUM.disabled=false;
	form.LE_SPH_DEC.disabled=false;
	form.LE_CYL_NUM.disabled=false;
	form.LE_CYL_DEC.disabled=false;
	form.LE_ADD.disabled=false;
	form.LE_PR_IO.disabled=false;
	form.LE_PR_AX.disabled=false;
	form.LE_PR_AX2.disabled=false;
	form.LE_HEIGHT.disabled=false;
	form.LE_AXIS.disabled=false;
	form.LE_PD.disabled=false;
	form.RE_SPH_NUM.disabled=false;
	form.RE_SPH_DEC.disabled=false;
	form.RE_CYL_NUM.disabled=false;
	form.RE_CYL_DEC.disabled=false;
	form.RE_ADD.disabled=false;
	form.RE_PR_IO.disabled=false;
	form.RE_PR_AX.disabled=false;
	form.RE_PR_AX2.disabled=false;
	form.RE_HEIGHT.disabled=false;
	form.RE_AXIS.disabled=false;
	form.RE_PD.disabled=false;
	form.RE_CT.disabled=false;
	form.RE_ET.disabled=false;
	form.LE_CT.disabled=false;
	form.LE_ET.disabled=false;
	}		
		
		
		
		
		
		
		
		
		
																											  
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
