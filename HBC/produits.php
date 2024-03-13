<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}

-->
</style>

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkText(formname, 'Name', '<?php echo $lbl_nameerror1;?>');
  errors += checkText(formname, 'Phone', '<?php echo $lbl_phoneerror1;?>');
  //errors += checkRadio(formname, 'Question1', 'Question 1');
  //errors += checkText(formname, 'Question1_explain', 'Explain Question 1');
  //errors += checkSelect(formname, 'Country', 'Country Of Residence');
  //errors += checkText(formname, 'age', 'Age Of Person');
  //errors += checkNum(formname, 'age', 'Age Of Person');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      <div class="loginText">
        <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        </div>
      <div class="header"><?php echo ucwords(strtolower($lbl_btn_prod_serv));?></div>
     

	 <?php if ($mylang == 'lang_french') {  ?>
      <div class="linksSubheader"><a 
      href="/ifcopticclubca/optimize.php">Verres Optimize Family</a></div>  
     <?php  	}else{ ?>
      <div class="linksSubheader"><a 
      href="/ifcopticclubca/optimize.php">Optimize Family Lenses</a></div>  
     <?php  	}  
	 
	 
	 // SI CONNECTÉ
	 if($_SESSION["sessionUser_Id"]!=""){ 
		if ($mylang == 'lang_french') {  ?>
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/Brochure%20IFC%20CANADA_lr.pdf">Programme IFC Club</a></div>    
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/LOWR_Brochure%20FREE%20LUX._CANADA_FR.pdf">Brochure FREE LUX Canada</a></div> 
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/LOWR_Catalogue%20IFC_CANADA_FR.pdf">Catalogue IFC</a></div>
     <?php  	}else{ ?>
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/Brochure%20IFC%20CANADA_ENG_lr.pdf">IFC Club Concept</a></div>    
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/LOWR_Brochure%20FREE%20LUX._CANADA.pdf">Canada FREE LUX Brochue</a></div> 
        <div class="linksSubheader"><a 
        href="http://www.direct-lens.com/ifcopticclubca/pdfs/LOWR_Catalogue%20IFC_CANADA_en.pdf">IFC Catalog</a></div>
     <?php  	}                
    }?>     
    
	<?php  
	// SI NON CONNECTÉ
	if($_SESSION["sessionUser_Id"]==""){     	
		if ($mylang == 'lang_french') {  ?>
        <div class="header">Pour voir les fichiers suivants, vous devez être connecté</div>   
        
        <div class="linksSubheader">Programme IFC Club</div>    
        <div class="linksSubheader">Brochure FREE LUX Canada</div> 
        <div class="linksSubheader">Catalogue IFC</div>
    <?php  	}else{ ?>
        <div class="header">Log in first to view the pdf files</div>      
    
        <div class="linksSubheader">IFC Club Concept</div>    
        <div class="linksSubheader">Canada FREE LUX Brochure</div> 
        <div class="linksSubheader">IFC Catalog</div>
    <?php  	}                
    }?>           
            
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->


<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>

</body>
</html>
