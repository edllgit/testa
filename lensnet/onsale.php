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
      		<div class="bigwelcome">
		  	<?php if ($mylang == 'lang_french') {  ?>
            <?php echo 'LIQUIDATION';?>
            <?php  }else{ ?>
            <?php echo 'ON SALE';?>
            <?php } ?>   </div>
            <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		    <br /><br />
	    	<p>...</p>
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