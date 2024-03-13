<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
    
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

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>



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
<div id="rightColumn"> <div class="header">
		  	<?php echo $lbl_problem_txt;?></div>
		    <table width="770" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
              </tr>
              <tr>
              	<td align="left"  class="messageText"><p><?php echo $lbl_probmsg_txt;?> </p></td>
           	  </tr>
            </table>
		      	<p>&nbsp;</p>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>