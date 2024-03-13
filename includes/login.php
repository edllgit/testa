<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
    
<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}

</script>
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<script type="text/javascript">
<!--
/* This script and many more are available free online at
The JavaScript Source :: http://javascript.internet.com
Created by: Amit Wadhwa :: http://amitwadhwa.fcpages.com/javascript.com/formvalidator.html */

function check(formname, submitbutton) {
  var errors = '';
  errors += checkText(formname, 'user_id', 'Login');
  errors += checkText(formname, 'password', 'Password');
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
  <img src="http://www.direct-lens.com/direct-lens/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"> <form action="dllogin.php" method="post" name="loginForm" id="loginForm">
		    <div class="header">
	  	Bienvenue sur IFC Optique du Club</div>
		    <br />
		    <br />
		    <table width="750" border="0" align="center" cellpadding="5" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
					<td align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_logintxt_cust;?>
					</div></td>
              	<td width="10%" align="left" class="formCellNosides"><input name="user_id" type="text" id="user_id" size="20"></td>
              	<td align="left" nowrap class="formCellNosides"><div align="right">
              		<?php echo $lbl_pwdtxt_cust;?>
              		</div></td>
              	<td align="left" class="formCellNosides"><input name="password" type="password" id="password" size="20"></td>
              	</tr>
              <tr valign="bottom">
              	<td colspan="4" align="center"  class="formCellNosides"><div align="center">
              	    <b><?php echo $lbl_choicetxt;?></b>&nbsp;&nbsp;
<!--<input name="lens_type" type="radio" value="stock">-->
              	    <?php //echo $lbl_choice1;?>
              	    
              	    &nbsp;&nbsp;
              	    &nbsp;&nbsp;
              	    <!--<input name="lens_type" type="radio" value="stock_bulk">-->
              	    <?php //echo $lbl_choice2;?>
  &nbsp;&nbsp;
  &nbsp;&nbsp;
  <input name="lens_type" type="radio" value="prescription" checked>Pack Monture
           	      <?php //echo $lbl_choice3;?> 
              	    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              	    <!--<input name="lens_type" type="radio" value="frames">--> 
              	    <?php //echo $lbl_choice4;?>
</div>              	  <div align="left"></div></td>
              	</tr>
              <tr valign="bottom">
                <td colspan="4" align="center"  class="formCellNosides"><div align="center">
      	 <input name="login" type="button" class="formText" value="<?php echo $btn_submit_txt;?>" onClick="check('loginForm', this.name);">
	      	  
      	</div></td>
              </tr>
            </table>
	    <p>&nbsp;</p>
		    <p>&nbsp;</p>
		    <p>&nbsp;</p>
</form>
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