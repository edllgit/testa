function fetchIndex(url,MATERIAL_ID,INDEX_ID,COATING_ID,SPHERE_ID,CYLINDER_ID,material_value){

var pageRequest=false;
if (window.XMLHttpRequest){
	pageRequest=new XMLHttpRequest();
	}
else if (window.ActiveXObject) {
	pageRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
else {
	return false;}

pageRequest.onreadystatechange=function(){
	filterIndex(pageRequest,INDEX_ID,MATERIAL_ID,COATING_ID,SPHERE_ID,CYLINDER_ID);
}

url=url+"?MATERIAL="+material_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterIndex(pageRequest,INDEX_ID,MATERIAL_ID,COATING_ID,SPHERE_ID,CYLINDER_ID){
	
	if (pageRequest.readyState==4){
		
		var object=document.getElementById(INDEX_ID);
		object.options.length=0;
		object.options[0]=new Option("-","");
		
		var coat_object=document.getElementById(COATING_ID);
		coat_object.options.length=0;
		coat_object.options[0]=new Option("-","");
		
		var sphere_object=document.getElementById(SPHERE_ID);
		sphere_object.options.length=0;
		sphere_object.options[0]=new Option("-","");
		
		var cyl_object=document.getElementById(CYLINDER_ID);
		cyl_object.options.length=0;
		cyl_object.options[0]=new Option("-","");
			
		}
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
				object.options[0]=new Option("Select an Index","");
			var arrSecondaryData=pageRequest.responseText.split(',');
				for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!='')
						object.options[object.options.length]=new Option(arrSecondaryData[i],arrSecondaryData[i]);
					}
		}
	}
}

function fetchCoating(url,INDEX_ID,COATING_ID,SPHERE_ID,CYLINDER_ID,material_value,index_value){
	
var pageRequest=false;
if (window.XMLHttpRequest){
	pageRequest=new XMLHttpRequest();
	}
else if (window.ActiveXObject) {
	pageRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
else {
	return false;}

pageRequest.onreadystatechange=function(){
	filterCoating(pageRequest,INDEX_ID,COATING_ID,SPHERE_ID,CYLINDER_ID);
}

url=url+"?INDEX="+index_value+"&MATERIAL="+material_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);
}

function filterCoating(pageRequest,INDEX_ID,COATING_ID,SPHERE_ID,CYLINDER_ID){
	if (pageRequest.readyState==4){
		var cyl_object=document.getElementById(CYLINDER_ID);
		cyl_object.options.length=0;
		cyl_object.options[0]=new Option("-","");
		
		 var object=document.getElementById(SPHERE_ID);
		object.options.length=0;
		object.options[0]=new Option("-","");
		
		var coat_object=document.getElementById(COATING_ID);
			coat_object.options.length=0;
			coat_object.options[0]=new Option("-","");
		
		}
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
				coat_object.options[0]=new Option("Select a Coating","");
			var arrSecondaryData=pageRequest.responseText.split(',');
			for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!=''){
					
						if (arrSecondaryData[i]=='UC')
							var optionText="Un-Coated";
							
						if (arrSecondaryData[i]=='AR')
							var optionText="Anti-Reflective";
	
						if (arrSecondaryData[i]=='SR')
							var optionText="Scratch Resistant";

						if (arrSecondaryData[i]=='SR AR')
							var optionText="Scratch Resistant and Anti-Reflective";
					
						coat_object.options[coat_object.options.length]=new Option(optionText,arrSecondaryData[i]);
					}
				}
			}
	}
}

function fetchSphere(url,SPHERE_ID,COATING_ID,CYLINDER_ID,material_value,index_value,coating_value){
var pageRequest=false;
if (window.XMLHttpRequest){
	pageRequest=new XMLHttpRequest();
	}
else if (window.ActiveXObject) {
	pageRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
else {
	return false;}

pageRequest.onreadystatechange=function(){
	filterSphere(pageRequest,SPHERE_ID,COATING_ID,CYLINDER_ID);
}

url=url+"?COATING="+coating_value+"&MATERIAL="+material_value+"&INDEX="+index_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterSphere(pageRequest,SPHERE_ID,COATING_ID,CYLINDER_ID){
	if (pageRequest.readyState==4){
		var cyl_object=document.getElementById(CYLINDER_ID);
		cyl_object.options.length=0;
		cyl_object.options[0]=new Option("-","");
		
		 var object=document.getElementById(SPHERE_ID);
		object.options.length=0;
		object.options[0]=new Option("-","");
		}
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
				object.options[0]=new Option("Select","");
			var arrSecondaryData=pageRequest.responseText.split(',');
				for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!='')
						object.options[object.options.length]=new Option(arrSecondaryData[i],arrSecondaryData[i]);
					}
		}
	}
}
function fetchCylinder(url,CYLINDER_ID,SPHERE_ID,material_value,index_value,coating_value,sphere_value){

var pageRequest=false;
if (window.XMLHttpRequest){
	pageRequest=new XMLHttpRequest();
	}
else if (window.ActiveXObject) {
	pageRequest=new ActiveXObject("Microsoft.XMLHTTP");
	}
else {
	return false;}

pageRequest.onreadystatechange=function(){
	filterCylinder(pageRequest,CYLINDER_ID,SPHERE_ID);
}

url=url+"?COATING="+coating_value+"&MATERIAL="+material_value+"&INDEX="+index_value+"&SPHERE="+sphere_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterCylinder(pageRequest,CYLINDER_ID,SPHERE_ID){
	if (pageRequest.readyState==4){
		 		var object=document.getElementById(CYLINDER_ID);
		object.options.length=0;
		object.options[0]=new Option("-","");
		}
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
				object.options[0]=new Option("Select","");
			var arrSecondaryData=pageRequest.responseText.split(',');
				for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!='')
						object.options[object.options.length]=new Option(arrSecondaryData[i],arrSecondaryData[i]);
					}
		}
	}
}