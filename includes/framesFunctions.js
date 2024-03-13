function fetchCollectionData(url,COLLECTION_ID,COLLECTION_TEXT){

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
	filterCollectionData(pageRequest,COLLECTION_TEXT);
}

url=url+"?COLLECTION_ID="+COLLECTION_ID;

pageRequest.open('GET',url,true);
pageRequest.send(null);

filterCollectionData(pageRequest,COLLECTION_TEXT);

}

function filterCollectionData(pageRequest,COLLECTION_TEXT){
	
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		if(pageRequest.responseText!=''){
			document.getElementById(COLLECTION_TEXT).innerHTML=pageRequest.responseText;
		}
	}
}

function fetchFramesData(url,COLLECTION_ID,FRAME_TEXT,FRAME_ID){
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
	filterFramesData(pageRequest,FRAME_TEXT);
}

url=url+"?COLLECTION_ID="+COLLECTION_ID+"&FRAME_ID="+FRAME_ID;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterFramesData(pageRequest,FRAME_TEXT){
	
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
			document.getElementById(FRAME_TEXT).innerHTML=pageRequest.responseText;
		}
	}
}
function fetchFramesImageView(url,FRAMES_ID,FRAMES_IMAGE){

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
	filterFramesData(pageRequest,FRAMES_IMAGE);
}

url=url+"?FRAMES_ID="+FRAMES_ID;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterFramesImageViewData(pageRequest,FRAMES_IMAGE){
	
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
			document.getElementById(FRAMES_IMAGE).innerHTML=pageRequest.responseText;
			
		}
	}
}

function fetchFrameDataPopulateForm(url,COLORS_TEXT,FRAME_ID){
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
	filterFrameDataPopulateForm(pageRequest,COLORS_TEXT);
}

url=url+"?FRAME_ID="+FRAME_ID;

pageRequest.open('GET',url,true);
pageRequest.send(null);

}

function filterFrameDataPopulateForm(pageRequest,COLORS_TEXT){
	
	if ((pageRequest.readyState==4)&&((pageRequest.status==200)||(window.location.href.indexOf("http")==-1))){
		
		if(pageRequest.responseText!=''){
			document.getElementById(COLORS_TEXT).innerHTML=pageRequest.responseText;
		}
	}
}