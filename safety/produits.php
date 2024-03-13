<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?>
<?php 
session_start();?>

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
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      <div class="loginText">
        <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        <?php print $_SESSION["sessionUser_Id"];?></div>
      <div class="header"><?php echo ucwords(strtolower($lbl_btn_prod_serv));?></div>
      
        <?php 	if ($mylang == 'lang_french') {  ?>
        <p>Fier distributeur de la collection ArmouRX, nous offrons le style et la sécurité dans la même monture. </p>
        <p>Faites votre choix l’esprit tranquille. Chaque monture  est testée auprès de professionnels indépendants.  Chaque monture respecte les normes ANSI Z87.1. Soyez à la mode, tout en étant en sécurité et confortable.</p>
      <p>Ajustée à votre vue à partir de 59$. Pourquoi attendre? Ouvrez votre compte aujourd’hui. </p>
        <?php  	}else{ ?>
        <p><strong>English version coming soon...</strong></p>
        <p>Fier distributeur de la collection ArmouRX, nous offrons le style et la sécurité dans la même monture. </p>
        <p>Faites votre choix l’esprit tranquille. Chaque monture  est testée auprès de professionnels indépendants.  Chaque monture respecte les normes ANSI Z87.1. Soyez à la mode, tout en étant en sécurité et confortable.</p>
        <p>Ajustée à votre vue à partir de 59$. Pourquoi attendre? Ouvrez votre compte aujourd’hui. </p>
        <?php 	} ?>         
 
</div><!--END rightcolumn-->
</div><!--END maincontent-->
    <div id="footerBox">
        <div class="accueil_footer_nav">
            © 2015 Safety - Avantage Sécurité by <a href="http://directlab.ca/">DirectLab.ca</a> 
            and <a href="http://Direct-lens.com">Direct-lens.com</a> | 
            <?php 	if ($mylang == 'lang_french') {  ?>
                <a href="conditions.php">Conditions</a>             
            <?php  	}else{ ?>
                <a href="conditions-en.php">Terms and Conditions</a>
            <?php 	} ?>         
        </div>
    </div>
</div><!--END containter-->

</body>
</html>
