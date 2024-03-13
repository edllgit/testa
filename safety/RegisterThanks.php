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
.select1 {width:100px}
-->
</style>


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
<div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <div class="header">  <?php if ($mylang == 'lang_french') {
	  echo "Merci. ";
	  }else {
	  echo "Thank you.";
	  }
	    ?> </div>
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
    <tr >
      <td bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
    </tr>
    <tr>
      <td align="left"  class="formText">  
	  <?php if ($mylang == 'lang_french') {
	  echo "Merci de votre inscription à notre info-lettre. ";
	  }else {
	  echo "Thank you for suscribing to our newsletter.";
	  }
	    ?></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>
</div>
<!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->

</body>
</html>