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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>
   
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
    
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>   
         
        <div class="header"><?php echo $lbl_problem_txt;?></div>
        <table width="770" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
          <tr >
            <td bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
          </tr>
          <tr>
            <td align="left"  class="messageText"><p><?php echo $lbl_probmsg_txt;?> </p></td>
          </tr>
        </table>
        <br /><br />         
	</div><!--END maincontent-->
  	<?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>