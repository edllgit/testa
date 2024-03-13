<?php 
include "../Connections/directlens.php";
include "inc/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr-FR">
<head profile="http://gmpg.org/xfn/11">
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Direct-Lens.com || Direct-Lab Network</title>
    
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="css/txt-style.css"/>     
    <link rel="stylesheet" type="text/css" href="css/style-den.css"/> 
	<link rel="stylesheet" type="text/css" href="css/form.css"/>  
       
    <script type="text/javascript" src="js/formvalidator-en.js"></script>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/coin-slider.min.js"></script>     
	<script type="text/javascript">
    window.onload = function() {
    document.getElementById("username").onblur = function() {
    var xmlhttp;
    var user_id=document.getElementById("username");
    if (user_id.value != "")
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
    xmlhttp.open("POST","getUserid.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("user_id="+encodeURIComponent(user_id.value));
    document.getElementById("status").innerHTML="<br>Vérification / Verifying...";
    }
    };
    };
    </script>
    
    
    <script type="text/javascript">
    window.onload = function() {
    document.getElementById("user_id").onblur = function() {
    var xmlhttp;
    var user_id=document.getElementById("user_id");
    if (user_id.value != "")
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
    xmlhttp.open("POST","getUserid.php",true);
    xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    xmlhttp.send("user_id="+encodeURIComponent(user_id.value));
    document.getElementById("status").innerHTML="<br>Vérification / Verifying...";
    }
    };
    };
    </script>
    
</head>
<body>

<div id="wrapper">
	<div id="header">
    	<h1>Direct-Lens</h1>
    	<div id="nav">
			<ul>
			    <li><a href="/direct-lens/index.php">Home</a></li>        
                <li><a href="/direct-lens/create-account-den.php">Create Account</a></li> 
            </ul>
  	  	</div>
        <div class="clear"></div> 
    </div>  <!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Untitled Document</title>
</head>

<body>
</body>
</html>