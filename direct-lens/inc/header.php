<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="fr-FR">
<head profile="http://gmpg.org/xfn/11">
    
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Direct-Lens.com || Direct-Lab Network</title>
    
    <meta name="description" content=""/>
    <meta name="keywords" content=""/>
    <meta name="author" content="Directlab Network"/>
    
    <link rel="stylesheet" type="text/css" href="css/reset.css"/>
    <link rel="stylesheet" type="text/css" href="css/txt-style.css"/>     
    <link rel="stylesheet" type="text/css" href="css/style.css"/> 
	<link rel="stylesheet" type="text/css" href="css/form.css"/>  
       
    <script type="text/javascript" src="js/formvalidator-<?php echo ($mylang == "lang_french") ? "fr": "en"; ?>.js"></script>
    <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/script.js"></script>
    <script type="text/javascript" src="js/coin-slider.min.js"></script> 
        
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
        <div id="social-ico">
            <ul>                           
                <li><?php include('inc/translator.php'); ?></li>
            </ul>
            <div class="clear"></div>  
        </div>  
    	<div id="nav">
			<ul>
			<?php  if ($mylang == 'lang_french') {  ?>
            	<li><a href="/direct-lens/index.php">Accueil</a></li>             
                         
            <?php  }else{ ?>
            	<li><a href="<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens">Home</a></li>             
                           
            <?php  } ?>                        
                <li><a href="/direct-lens/contact.php">Contact</a></li>
            </ul>
  	  	</div>
        <div class="clear"></div> 
    </div>  