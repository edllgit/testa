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
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<div class="header">
		  	The Login You Entered is in Use
	  </div>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
              </tr>
              <tr>
              	<td align="left"  class="messageText">Please try a different login. </td>
           	  </tr>
            </table>
      <div align="center" style="margin:11px">
		      	<p>&nbsp;</p>
		      	</div>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>