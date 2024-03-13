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
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
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
    <div id="maincontent">
        <div id="leftColumn">
            <div id="leftnav">
                <?php include("includes/sideNav.inc.php"); ?>
            </div><!--END leftnav-->
        </div><!--END leftcolumn-->
        <div id="rightColumn">
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">
                <?php 	if ($mylang == 'lang_french') {  ?>
                    Catalogues                   
                <?php  	}else{ ?>
                    Catalogs
                <?php 	} ?>     
            </div>
            <div class="Subheader" style="height:600px;">
                <?php 	if ($mylang == 'lang_french') {  ?>
                                     
                <?php  	}else{ ?>

                <?php 	} ?>

			</div><!--END Subheader-->             
       </div><!--END rightcolumn-->
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->

</body>
</html>