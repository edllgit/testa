<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
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
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
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
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>


</head>


<body>
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
<div id="rightColumn"><form action="../send_email.php" method="post" name="contactForm" id="contactForm">
      <div class="header">
		  	</div><div class="loginText"></div>
		    <br>
		    <br>
		    <div class="Subheader"></div>
	   
		   
		   
		  </form>
</div><!--END rightcolumn-->

            
            <?php
  if ($mylang == 'lang_french') {
	echo '<a href="http://www.lensnetclub.com/images/liste_prix_innovative_fr.jpg"><img src="'.constant('DIRECT_LENS_URL').'/lensnet/images/liste_prix_innovative_fr.jpg" width="700" /></a>';
			}else{
	echo '<a href="http://www.lensnetclub.com/images/liste_prix_innovative_en.jpg"><img src="'.constant('DIRECT_LENS_URL').'/lensnet/images/liste_prix_innovative_en.jpg" width="700"  /></a>';
			}
?>
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