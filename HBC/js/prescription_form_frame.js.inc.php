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
		theForm.TO_PERC.value    = "";
		theForm.FROM_PERC.value  = "";
		theForm.TINT_COLOR.value = "";
	}
	else if(theForm.TINT.value=="Solid"){//SOLID TINT
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.disabled=false;
		theForm.TO_PERC.disabled=true;
		theForm.TO_PERC.value="";
	}else if(theForm.TINT.value=="Gradient"){//GRADIENT TINT
		theForm.TO_PERC.disabled=false;
		theForm.FROM_PERC.disabled=false;
		theForm.TINT_COLOR.value="";
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


function validerFrameType() { //v2.0
 var type_montage  =document.forms["PRESCRIPTION"]["FRAME_TYPE"].value;
 
if (type_montage == 'Nylon Groove'){
	//alert('Pour ce type de taillage l\'indice 1.50 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
	}

if (type_montage == 'Drill and Notch'){
	//alert('Pour ce type de taillage l\'indice 1.50 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
	}
	
}





function validateFr(theForm){

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

//Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
  if (isNaN(oc)) 
  {
    alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
  } 
  
  

//Validate the pds
	if ((theForm.RE_PD.value> 40) || (theForm.RE_PD.value <20))
  {
	alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 20 et 40';?>");
	theForm.RE_PD.focus();
	return (false);
  }

		if ((theForm.LE_PD.value> 40) || (theForm.LE_PD.value <20))
  {
	alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 20 et 40';?>");
	theForm.LE_PD.focus();
	return (false);
  }
  
	
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
		
if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}		
		
 

//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}


//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}		


/*
//Validation Épaisseur demandées si Nylon Groove
if(theForm.FRAME_TYPE.value=="Nylon Groove"){
	var compteur = 0;
	
	if(theForm.RE_CT.value==""){
	   compteur = compteur + 1;		
	}
	if(theForm.LE_CT.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.LE_ET.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.RE_ET.value==""){
       compteur = compteur + 1;	
	}
	
	if((theForm.RE_CT.value!="")&&(theForm.LE_CT.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.LE_CT.focus();
		return (false);	
	}
	
	if((theForm.LE_CT.value!="")&&(theForm.RE_CT.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.RE_CT.focus();
		return (false);	
	}
	
	if((theForm.RE_ET.value!="")&&(theForm.LE_ET.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.LE_ET.focus();
		return (false);	
	}
	
	if((theForm.LE_ET.value!="")&&(theForm.RE_ET.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.RE_ET.focus();
		return (false);	
	}
	
	if (compteur > 2){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");	
		return (false);
	}
	
}//Fin validation des épaisseurs si NYLON GROOVE*/

		
	
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
		
if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}		
		
		
		
		
/*//Validation Épaisseur demandées si Nylon Groove
if(theForm.FRAME_TYPE.value=="Nylon Groove"){
	var compteur = 0;
	
	if(theForm.RE_CT.value==""){
	   compteur = compteur + 1;		
	}
	if(theForm.LE_CT.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.LE_ET.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.RE_ET.value==""){
       compteur = compteur + 1;	
	}
	
	if((theForm.RE_CT.value!="")&&(theForm.LE_CT.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.LE_CT.focus();
		return (false);	
	}
	
	if((theForm.LE_CT.value!="")&&(theForm.RE_CT.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.RE_CT.focus();
		return (false);	
	}
	
	if((theForm.RE_ET.value!="")&&(theForm.LE_ET.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.LE_ET.focus();
		return (false);	
	}
	
	if((theForm.LE_ET.value!="")&&(theForm.RE_ET.value=="")){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");
		theForm.RE_ET.focus();
		return (false);	
	}
	
	if (compteur > 2){
		alert("<?php //echo 'Avec un montage de Fil Nylon vous devez obligatoirement spécifier les épaisseurs désirées';?>");	
		return (false);
	}
	
}//Fin validation des épaisseurs si NYLON GROOVE*/
 

//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}



//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.TO_PERC.focus();
	return (false);
}


//Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
  if (isNaN(oc)) 
  {
    alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
  } 
 
//Validate the pds
    if ((theForm.RE_PD.value> 40) || (theForm.RE_PD.value <20))
  {
    alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 20 et 40';?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
    if ((theForm.LE_PD.value> 40) || (theForm.LE_PD.value <20))
  {
    alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 20 et 40';?>");
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





function validate(theForm){

	
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


//Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
  if (isNaN(oc)) 
  {
    alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
  } 
  
//Validate the pds
	if ((theForm.RE_PD.value> 40) || (theForm.RE_PD.value <20))
  {
	alert("<?php echo 'The right eye PD must be between 20 and 40';?>");
	theForm.RE_PD.focus();
	return (false);
  }

		if ((theForm.LE_PD.value> 40) || (theForm.LE_PD.value <20))
  {
	alert("<?php echo 'The left eye PD must be between 20 and 40';?>");
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
		
		
	
	//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'You need to select the tint percentage';?>");
	theForm.FROM_PERC.focus();
	return (false);
}


//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'You need to select the tint percentage FROM';?>");
	theForm.FROM_PERC.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'You need to select the to percentage';?>");
	theForm.TO_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
		alert("<?php echo 'You need to select the tint color';?>");
		theForm.TINT_COLOR.focus();
		return (false);
	}

if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
		alert("<?php echo 'You need to select the tint color';?>");
		theForm.TINT_COLOR.focus();
		return (false);
}	


/*//Validation Épaisseur demandées si Nylon Groove
if(theForm.FRAME_TYPE.value=="Nylon Groove"){
	var compteur = 0;
	
	if(theForm.RE_CT.value==""){
	   compteur = compteur + 1;		
	}
	if(theForm.LE_CT.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.LE_ET.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.RE_ET.value==""){
       compteur = compteur + 1;	
	}
	
	if((theForm.RE_CT.value!="")&&(theForm.LE_CT.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.LE_CT.focus();
		return (false);	
	}
	
	if((theForm.LE_CT.value!="")&&(theForm.RE_CT.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.RE_CT.focus();
		return (false);	
	}
	
	if((theForm.RE_ET.value!="")&&(theForm.LE_ET.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.LE_ET.focus();
		return (false);	
	}
	
	if((theForm.LE_ET.value!="")&&(theForm.RE_ET.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.RE_ET.focus();
		return (false);	
	}
	
	if (compteur > 2){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");	
		return (false);
	}
	
}//Fin validation des épaisseurs si NYLON GROOVE*/
			

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
  
  
 }	



function validateSVEN(theForm){
	
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


//Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
  if (isNaN(oc)) 
  {
    alert("<?php echo 'Le centre optique doit être un nombre';?>");
	theForm.OPTICAL_CENTER.focus();
	return (false);
  } 
  
 
//Validate the pds
    if ((theForm.RE_PD.value> 40) || (theForm.RE_PD.value <20))
  {
    alert("<?php echo 'Right eye PD must be between 20 and 40';?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
    if ((theForm.LE_PD.value> 40) || (theForm.LE_PD.value <20))
  {
    alert("<?php echo 'Left eye PD must be between 20 and 40';?>");
    theForm.LE_PD.focus();
    return (false);
  }
   
 
	
	//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'You need to select the tint percentage';?>");
	theForm.FROM_PERC.focus();
	return (false);
}

if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}


//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'You need to select the tint percentage FROM';?>");
	theForm.FROM_PERC.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'You need to select the to percentage';?>");
	theForm.TO_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.TO_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
		alert("<?php echo 'You need to select the tint color';?>");
		theForm.TINT_COLOR.focus();
		return (false);
	}

	if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
		alert("<?php echo 'You need to select the tint color';?>");
		theForm.TINT_COLOR.focus();
		return (false);
	}	
			
/*//Validation Épaisseur demandées si Nylon Groove
if(theForm.FRAME_TYPE.value=="Nylon Groove"){
	var compteur = 0;
	
	if(theForm.RE_CT.value==""){
	   compteur = compteur + 1;		
	}
	if(theForm.LE_CT.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.LE_ET.value==""){
       compteur = compteur + 1;	
	}
	if(theForm.RE_ET.value==""){
       compteur = compteur + 1;	
	}
	
	if((theForm.RE_CT.value!="")&&(theForm.LE_CT.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.LE_CT.focus();
		return (false);	
	}
	
	if((theForm.LE_CT.value!="")&&(theForm.RE_CT.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.RE_CT.focus();
		return (false);	
	}
	
	if((theForm.RE_ET.value!="")&&(theForm.LE_ET.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.LE_ET.focus();
		return (false);	
	}
	
	if((theForm.LE_ET.value!="")&&(theForm.RE_ET.value=="")){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");
		theForm.RE_ET.focus();
		return (false);	
	}
	
	if (compteur > 2){
		alert("<?php //echo 'With Nylon Groove frames,  the requested thickness is mandatory';?>");	
		return (false);
	}
	
}//Fin validation des épaisseurs si NYLON GROOVE*/
 
   
 
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




function validateEntrepotFr(theForm){
	
	
if((theForm.LAST_NAME.value=="")&&(theForm.PATIENT_REF_NUM.value=="")){//PATIENT REF AND NAME
    alert("<?php echo $lbl_alert25_pl;?>");
   theForm.LAST_NAME.focus();
    return (false);
}

//Valider que l'axe est fournit si on a un cylindre
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
   
  
  //Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
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
 
	if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <20))
  {
	alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 20 et 50';?>");
	theForm.RE_PD.focus();
	return (false);
  }

if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <20))
  {
	alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 20 et 50';?>");
	theForm.LE_PD.focus();
	return (false);
  }
  
  
   var x=document.forms["PRESCRIPTION"]["RE_HEIGHT"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La hauteur de l\'oeil droit doit etre un nombre';?>");
	theForm.RE_HEIGHT.focus();
	return (false);
  } 
  
   var x=document.forms["PRESCRIPTION"]["LE_HEIGHT"].value;
  if (isNaN(x)) 
  {
    alert("<?php echo 'La hauteur de l\'oeil gauche doit etre un nombre';?>");
	theForm.LE_HEIGHT.focus();
	return (false);
  } 
  
  
	
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
  
  	
if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}


//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}



if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}		

if((theForm.TINT_COLOR.value!="")&&(theForm.TINT.value=="None")){
	alert("<?php echo 'SVP Enlever la couleur de la teinte ou choisissez  le type de teinte';?>");
	theForm.TINT_COLOR.focus();
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
  
   if ((theForm.FRAME_A.value> 75)||(theForm.FRAME_A.value<35))
  {
    alert("<?php echo 'La valeur du Frame A doit etre entre 35 et 75';?>");
    theForm.FRAME_A.focus();
    return (false);
  }  
  
  
<?php /*?>  
  var x=document.forms["PRESCRIPTION"]["nwd"].value;
  if ((x> 120) || (x < 20)) 
  {
    alert("<?php echo 'The NWD (Near working distance must be between 20 and 120 cm ';?>");
	theForm.nwd.focus();
	return (false);
  } 
  <?php */?>
  

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
  
  
  
    if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
  {
    alert("<?php echo 'La valeur du  Frame B doit etre entre 20 et 52';?>");
    theForm.FRAME_B.focus();
    return (false);
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
  
    
    if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>78))
  {
    alert("<?php echo 'La valeur du Frame ED doit etre entre 35 et 78';?>");
    theForm.FRAME_ED.focus();
    return (false);
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



  
  
     if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>27))
  {
    alert("<?php echo 'La valeur du Frame DBL doit etre entre 10 et 27';?>");
    theForm.FRAME_DBL.focus();
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


}//End ValidateEntrepot Prog


 function validerFrameType() { //v2.0
 var type_montage  =document.forms["PRESCRIPTION"]["FRAME_TYPE"].value;
 
if (type_montage == 'Nylon Groove'){
	//alert('Pour ce type de taillage l\'indice 1.5 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
	}

if (type_montage == 'Drill and Notch'){
	//alert('Pour ce type de taillage l\'indice 1.5 n\'est pas disponible. For this type of edging the index 1.50 is not available.');
	}
	
}





function validateEntrepotSV(theForm){
	
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
 
		
if((theForm.TINT.value=="Solid")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TINT_COLOR.value=="")){
	alert("<?php echo 'Vous devez choisir la couleur de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}		
		
 

//Teinte Solide
if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.FROM_PERC.focus();
	return (false);
}

if((theForm.TINT.value=="Solid")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}


//Teinte Dégradée
if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TINT_COLOR.focus();
	return (false);
}

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value=="")){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte';?>");
	theForm.TO_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.FROM_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.FROM_PERC.focus();
	return (false);
}	

if((theForm.TINT.value=="Gradient")&&(theForm.TO_PERC.value< 5)){
	alert("<?php echo 'Vous devez choisir les pourcentage de la teinte (minimum 5%)';?>");
	theForm.TO_PERC.focus();
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
  
   
 if ((theForm.FRAME_A.value> 75)||(theForm.FRAME_A.value<35))
  {
    alert("<?php echo 'La valeur du Frame A doit etre entre 35 et 75';?>");
    theForm.FRAME_A.focus();
    return (false);
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
  
     if ((theForm.FRAME_B.value< 20)||(theForm.FRAME_B.value>52))
  {
    alert("<?php echo 'La valeur du  Frame B doit etre entre 20 et 52';?>");
    theForm.FRAME_B.focus();
    return (false);
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
  
    if ((theForm.FRAME_ED.value< 35)||(theForm.FRAME_ED.value>78))
  {
    alert("<?php echo 'La valeur du Frame ED doit etre entre 35 et 78';?>");
    theForm.FRAME_ED.focus();
    return (false);
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
  
 
  
 
   
  
     if ((theForm.FRAME_DBL.value< 10)||(theForm.FRAME_DBL.value>27))
  {
    alert("<?php echo 'La valeur du Frame DBL doit etre entre 10 et 27';?>");
    theForm.FRAME_DBL.focus();
    return (false);
  }  
 
 
 
 //Optical Center
  var oc =document.forms["PRESCRIPTION"]["OPTICAL_CENTER"].value;
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


    if ((theForm.RE_PD.value> 50) || (theForm.RE_PD.value <20))
  {
    alert("<?php echo 'Le PD de l\'oeil droit doit etre entre 20 et 50';?>");
    theForm.RE_PD.focus();
    return (false);
  }
  
    if ((theForm.LE_PD.value> 50) || (theForm.LE_PD.value <20))
  {
    alert("<?php echo 'Le PD de l\'oeil gauche doit etre entre 20 et 50';?>");
    theForm.LE_PD.focus();
    return (false);
  }
  
  
  
 //SI on a un cylindre, Axe est obligatoire
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


}	//End IF validateEntrepotSV

</script>
