<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("prod_functions_hbc.inc.php");
include("frames_functions.inc.php");
include("image_functions.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


if ($_GET[edit]=="true"){
	$editForm=TRUE;
	$query="SELECT * FROM ifc_frames_french WHERE ifc_frames_id='$_GET[pkey]'";
	$result=mysqli_query($con,$query)		or die ("Could not select items");
	$frameItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
}
else if ($_POST[from_form]=="edit"){
	update_frame($_POST['pkey']);
}else if ($_POST[from_form]=="add"){
	add_frame();
}


?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
window.onload = function() {
document.getElementById("model_num").onblur = function() {
var xmlhttp;
var model_num=document.getElementById("model_num");
if (model_num.value != "")
{
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("status").innerHTML=xmlhttp.responseText;
    }
  };
xmlhttp.open("POST","do_check.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("model_num="+encodeURIComponent(model_num.value));
document.getElementById("status").innerHTML="Vérification en cours...";
}
};
};
</script>





<script type="text/javascript">
function getPricesEntrepot() {
	var xmlhttp;
	var frames_collections_id=document.getElementById("frames_collections_id");
	if (frames_collections_id.value != "")
	{
	if (window.XMLHttpRequest)
	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("actualPrices").innerHTML=xmlhttp.responseText;
		}
	  };
	xmlhttp.open("POST","getPrices.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("frames_collections_id="+encodeURIComponent(frames_collections_id.value));
	document.getElementById("actualPrices").innerHTML="Recherche en cours...";
	}
}//End function
</script>


<script type="text/javascript">
function getStockPrices() {
	var xmlhttp;
	var frames_collections_id=document.getElementById("frames_collections_id");
	if (frames_collections_id.value != "")
	{
	if (window.XMLHttpRequest)
	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("actualStockPrices").innerHTML=xmlhttp.responseText;
		}
	  };
	xmlhttp.open("POST","getStockPrices.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("frames_collections_id="+encodeURIComponent(frames_collections_id.value));
	document.getElementById("actualStockPrices").innerHTML="Recherche en cours...";
	}
}//End function
</script>


<script type="text/javascript">
function getDiscountedPrices() {
	var xmlhttp;
	var frames_collections_id=document.getElementById("frames_collections_id");
	if (frames_collections_id.value != "")
	{
	if (window.XMLHttpRequest)
	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("actualDiscountedPrices").innerHTML=xmlhttp.responseText;
		}
	  };
	xmlhttp.open("POST","getDiscountedPrices.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("frames_collections_id="+encodeURIComponent(frames_collections_id.value));
	document.getElementById("actualDiscountedPrices").innerHTML="Recherche en cours...";
	}
}//End function
</script>

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkSelect(formname, 'frames_collections_id', 'Collection');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td>
		<?php
		if ($editForm){include("frameEditForm.inc.php");}
		else{include("frameForm.inc.php");}
		?>	
</td>
<td>
		<?php
			include("frameList.inc.php");
		?>		
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
