<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
    
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
<script type="text/JavaScript">
<!--

var delayTimer=null;
var delayTimer2=null;

//COLLECTION SECTION

function fetchCollectionInfo(collection_id,ObjectID,CSSProp,newVal){// MOUSE OVER COLLECTION
	var viewWidth=viewHeight=0;
	
	var object=document.getElementsByTagName('img');
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
			var obj=object.item(i);
			
			if (obj.offsetParent) {
				do {
				viewWidth+=obj.offsetLeft;
				viewHeight+=obj.offsetTop;
					} while (obj=obj.offsetParent);
			}
		}
	}
	var center=(document.width/2)-(viewWidth/2);
	highlightBox(ObjectID,CSSProp,newVal,"img");
	fetchCollectionData('getCollectionInfo.php',collection_id,'COLLECTION_TEXT');
	document.getElementById('collectionView').style.top=(viewHeight-301)+"px";
	document.getElementById('collectionView').style.left=(viewWidth-150)+"px";
	//document.getElementById('collectionView').style.visibility="visible";
	delayTimer=setTimeout("document.getElementById('collectionView').style.visibility='visible';",800);
}

function unhighlightBox(ObjectID,CSSProp,newVal) { //MOUSE OUT COLLECTION

if (delayTimer) clearTimeout(delayTimer);
	
hideCollectionInfo();
var object=document.getElementsByTagName('img');
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
		object.item(i).style[CSSProp]=newVal;
		}
	}
}

function hideCollectionInfo(){
	document.getElementById('COLLECTION_TEXT').innerHTML="&nbsp;";
	document.getElementById('collectionView').style.visibility="hidden";
}

function highlightBox(ObjectID,CSSProp,newVal,tag) { //v3.0

var object=document.getElementsByTagName(tag);
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
		object.item(i).style[CSSProp]=newVal;
		}
	}
}

//FRAMES SECTION

function fetchFramesInfo(collection_id,ObjectID,CSSProp,newVal,CSSProp2,newVal2,CSSProp3,newVal3){//MOUSE CLICKED ON COLLECTION
	var viewWidth=viewHeight=0;
	
	var object=document.getElementsByTagName('div');
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
			var obj=object.item(i);
			
			if (obj.offsetParent) {
				do {
				viewWidth+=obj.offsetLeft;
				viewHeight+=obj.offsetTop;
					} while (obj=obj.offsetParent);
			}
		}
	}
	fetchFramesData('getFramesInfo.php',collection_id,'FRAMES_TEXT','');
	expandBox(ObjectID,CSSProp,newVal,CSSProp2,newVal2,CSSProp3,newVal3);
	
	hideExtrasForm();
	
	delayTimer2=setTimeout("jQuery('html, body').animate({scrollTop: '400px'}, 800);",1000);
}

function expandBox(ObjectID,CSSProp,newVal,CSSProp2,newVal2,CSSProp3,newVal3) { //v3.0
var object=document.getElementsByTagName('div');
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute( 'name' ) ==ObjectID){
		object.item(i).style[CSSProp]=newVal;
			object.item(i).style[CSSProp2]=newVal2;
			object.item(i).style[CSSProp3]=newVal3;
		}
	}
}

function highlightFrameBox(frames_id,ObjectID,CSSProp,newVal) { //v3.0

var viewWidth=viewHeight=0;
	
	var object=document.getElementsByTagName('img');
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
			var obj=object.item(i);
			
			if (obj.offsetParent) {
				do {
				viewWidth+=obj.offsetLeft;
				viewHeight+=obj.offsetTop;
					} while (obj=obj.offsetParent);
			}
		}
	}
	
	var center=(document.width/2)-(viewWidth/2);
	highlightBox(ObjectID,CSSProp,newVal,"img");
	fetchFramesImageView('getFrameImageView.php',frames_id,'FRAMES_IMAGE');
	document.getElementById('frameView').style.top=(viewHeight-263)+"px";
	document.getElementById('frameView').style.left=(viewWidth-150)+"px";
	//document.getElementById('frameView').style.visibility="visible";
	
	delayTimer=setTimeout("document.getElementById('frameView').style.visibility='visible';",800);
}

function unhighlightFrameBox(ObjectID,CSSProp,newVal,tag) { //v3.0
hideFrameInfo();
var object=document.getElementsByTagName(tag);
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
		object.item(i).style[CSSProp]=newVal;
		}
	}
}

function hideFrameInfo(){
	document.getElementById('FRAMES_IMAGE').innerHTML="&nbsp;";
	document.getElementById('frameView').style.visibility="hidden";
}

function setProp(ObjectID,CSSProp,newVal,tag) { //v3.0
if (delayTimer) clearTimeout(delayTimer);
var object=document.getElementsByTagName(tag);
	for(var i=0;i<object.length;i++){
  		if(object.item(i).getAttribute('name') ==ObjectID){
		object.item(i).style[CSSProp]=newVal;
		}
	}
}

//EXTRAS SECTION

function populateExtrasForm(frame_id, ObjectID,CSSProp,newVal,CSSProp2,newVal2,CSSProp3,newVal3){
	
	hideFrameInfo();
	fetchFramesData('getFramesInfo.php','','FRAMES_TEXT',frame_id);
	expandBox(ObjectID,CSSProp,newVal,CSSProp2,newVal2,CSSProp3,newVal3);
	fetchFrameDataPopulateForm('getFrameInfoPopulateForm.php','COLORS_TEXT',frame_id);
	
	delayTimer2=setTimeout("jQuery('html, body').animate({scrollTop: '550px'}, 800);",1000);
 	
}
function hideExtrasForm(){
	document.getElementById('COLORS_TEXT').innerHTML="&nbsp;";
	document.getElementById('extrasViewBox').style.height="10px";
	document.getElementById('extrasViewBox').style.visibility="hidden";
}


//-->
</script>

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
<link href="../frames.css" rel="stylesheet" type="text/css" />
<script src="../includes/framesFunctions.js" type="text/javascript"></script>
<style type="text/css">
<!--
#collectionView {
	position:absolute;
	left:374px;
	top:108px;
	width:511px;
	height:301px;
	z-index:100;
	visibility:hidden;
}
#frameView {
	position:absolute;
	left:374px;
	top:108px;
	width:511px;
	height:258px;
	z-index:100;
	visibility: hidden;
}
-->
</style>


</head>


<body><div class="collectionViewBox" id="collectionView"><div id="COLLECTION_TEXT" class="collectionViewText">collection text</div></div>
<div class="frameViewBox" id="frameView"><div id="FRAMES_IMAGE">frame text</div></div>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">

<div id="headerBox" class="header"><?php echo $lbl_titlemast_frames;?></div></td><td>&nbsp;</td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		     <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#000099" class="tableHead"><?php echo $lbl_subhead_frames;?></td>
               </tr>
              <tr >
                <td class="formCellNosides">
                <div align="center"><?php include("../includes/frames_collections.inc.php"); ?></div></td>
               </tr>
		     </table>
   <div id="frameTypeBox" name="frameBox">
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
    <tr >
      <td bgcolor="#000099" class="tableHead"><?php echo $lbl_subhead2_frames;?>&nbsp;</td>
    </tr>
    <tr >
      <td class="formCellNosides"><div id="FRAMES_TEXT" class="collectionViewText"></div></td>
    </tr>
  </table>
</div>
<div id="extrasViewBox" name="extrasBox">
<form action="prescription_frames.php" method="post" enctype="application/x-www-form-urlencoded" id="form">
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
    <tr>
      <td bgcolor="#000099" class="tableHead"><?php echo $lbl_subhead3_frames;?>&nbsp;</td>
    </tr>
   
    <tr>
      <td align="center" class="formCell"><div id="COLORS_TEXT" class="collectionViewText"></div></td>
    </tr>
  </table>
        <div align="center"><br /><input name="button" type="submit" class="formText" id="button" value="Submit" /></div>
  
</form></div>

</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>