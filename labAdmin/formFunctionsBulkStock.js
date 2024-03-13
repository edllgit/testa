function fetchSphere(url,SPHERE_ID,CYLINDER_ID,product_value){
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
	filterSphere(pageRequest,SPHERE_ID,CYLINDER_ID);
}

url=url+"?PRODUCT="+product_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterSphere(pageRequest,SPHERE_ID,CYLINDER_ID){
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
				object.options[0]=new Option("Select a Sphere Value","");
			var arrSecondaryData=pageRequest.responseText.split(',');
				for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!='')
						object.options[object.options.length]=new Option(arrSecondaryData[i],arrSecondaryData[i]);
					}
		}
	}
}
function fetchCylinder(url,CYLINDER_ID,product_value,sphere_value){

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
	filterCylinder(pageRequest,CYLINDER_ID);
}

url=url+"?SPHERE="+sphere_value+"&PRODUCT="+product_value;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterCylinder(pageRequest,CYLINDER_ID){
	if (pageRequest.readyState==4){
		 		var object=document.getElementById(CYLINDER_ID);
		object.options.length=0;
		object.options[0]=new Option("-","");
		}
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
				object.options[0]=new Option("Select a Cylinder Value","");
			var arrSecondaryData=pageRequest.responseText.split(',');
				for(i=0;i<arrSecondaryData.length;i++){
					if (arrSecondaryData[i]!='')
						object.options[object.options.length]=new Option(arrSecondaryData[i],arrSecondaryData[i]);
					}
		}
	}
}