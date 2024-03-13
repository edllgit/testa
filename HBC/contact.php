<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";

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
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header"><?php echo $lbl_titlemast_contactus;?></div>
            <div class="Subheader" style="height:600px;">
                
			
				<?php /*
				
				<?php 	if ($mylang == 'lang_french') {  ?>
            	<p>Merci d'avoir visité IFC Club Canada. Si vous avez des questions ou commentaires, veuillez nous contacter au 1-877-570-3522 ou écrivez-nous 
                <a href="mailto:dbeaulieu@direct-lens.com">ordersrco@direct-lens.com</a>.</p>
                <?php  	}else{ ?>
                <p>Thank you for visiting IFC Club Canada. If you have any questions, comments or concerns, please contact us at 1-800-418-2901 or e-mail us
                at <a href="mailto:dbeaulieu@direct-lens.com">ordersrco@direct-lens.com</a>.</p>
                <?php 	} ?>                 
              
                <h2>I.F.C.</h2>
                <p> 240 rue des Forges, Bureau 203<br />
                Trois-Rivières, QC<br />
                G9A 2G8</p>
                <p>T : 1-877-570-3522 or 905-682-2124<br />
                F : 877-590-3522</p>
				<p>dbeaulieu@direct-lens.com</p>   
				
				*/
				?>
				
				
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