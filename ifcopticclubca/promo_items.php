<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

//Page de vente d'items promotionnels, créé par Charles le 2014-02-13

session_start();
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
</head>

<body>
<div id="container">
  	<div id="masthead">
  		<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
	</div>
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
            <p>Items promotionnels à vendre</p>
            <?php  	}else{ ?>
            <p>Promo Items for Sale</p>
            <?php 	} ?> </div>
           
            <div class="Subheader" style="height:600px;">

			</div><!--END Subheader-->             
       </div><!--END rightcolumn-->
	</div><!--END maincontent-->
	<div id="footer1"></div>
</div><!--END containter-->



</body>
</html>

<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>