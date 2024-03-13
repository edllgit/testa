<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if ($_POST[addSales]=="yes"){
	if ($_SESSION["sessionUser_Id"] != ""){
		if(addSalesperson($_SESSION["sessionUser_Id"]))
			$message = "Salesperson has been added.";
		else
			$message = "Salesperson ID already exists for your account. Please choose another ID.";
	}
}
if ($_GET[remove_sp]){
	if ($_SESSION["sessionUser_Id"] != ""){
		if(removeSalesperson($_GET[remove_sp]));
			$message = "Salesperson has been removed.";
	}
}
if ($_GET[return_sp]){
	if ($_SESSION["sessionUser_Id"] != ""){
		if(returnSalesperson($_GET[return_sp]));
			$message = "Salesperson has been returned.";
	}
}

$query="select * from salespeople where acct_user_id = '{$_SESSION[sessionUser_Id]}' ORDER BY removed, last_name, first_name";
$result=mysql_query($query)
	or die ("Could not find salespeople");
$salescount=mysql_num_rows($result);
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

<style type="text/css">
<!--
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
  errors += checkText(formname, 'first_name', 'First Name');
  errors += checkText(formname, 'last_name', 'Last Name');
  errors += checkText(formname, 'sales_id', 'Salesperson ID');
  checkThisForm(formname, submitbutton, errors);
}
//-->
</script>

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
<div id="rightColumn">
<form action="mySalespeople.php" method="post" name="salesForm" id="salesForm"><div class="bigwelcome">
    	<?php echo $lbl_titlemast_mas;?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		    <table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
				<tr >
					<td colspan="6" bgcolor="#000099" class="tableHead"><?php echo $lbl_subtitle_mas;?>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="6" align="left"  class="formText"><?php echo $lbl_subtext_mas;?></td>
				</tr>
				<tr>
					<td align="left"  class="formCellNosides"><div align="right">
						<?php echo $lbl_salespersonid_txt;?>
					</div></td>
					<td align="left" class="formCellNosides"><input name="sales_id" type="text" id="sales_id" size="12"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_fname_mas;?></div></td>
					<td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="20"></td>
					<td align="left" nowrap class="formCellNosides"><div align="right"><?php echo $lbl_lname_mas;?></div></td>
					<td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20"></td>
				</tr>
			</table>
		    <div align="center" style="margin:11px">
		      	<p>
		      		<input type="hidden" name="addSales" value="yes">
		      		<input name="Reset" type="reset" class="formText" value="<?php echo $btn_reset_txt;?>">
		      		&nbsp;
		      		<input name="submitBttn" type="button" class="formText" id="submitBttn" onClick="check('salesForm', this.name);" value="<?php echo $btn_submit_txt;?>">
	      	  </p>
	    </div>
	  </form>
		  <?php
		  if ($salescount == 0){/* no salespeople */
		  	echo "<div class=\"formText\" align=\"center\">".$lbl_nopeople_txt."</div>";
		}else{
			echo "<table width=\"750\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
				<tr>
					<td colspan=\"4\" bgcolor=\"#000099\" class=\"tableHead\">";
					if($message!="") echo "$message"; else echo "&nbsp;";
					echo "</td>
				</tr>
				<tr>
					<td align=\"left\"  class=\"formCellNosides\"><div align=\"left\">".$lbl_salespersonid_txt."</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">".$lbl_fname_mas."</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">".$lbl_lname_mas."</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">".$lbl_remove_txt."</div></td>
					</tr>";
			while($salesData=mysql_fetch_array($result)){
				echo "<tr>
					<td align=\"left\"  class=\"formCellNosides\"><div align=\"left\">$salesData[sales_id]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">$salesData[first_name]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">$salesData[last_name]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">";
					if($salesData[removed]=="yes") 
						echo "<a href=\"mySalespeople.php?return_sp=$salesData[primary_key_sp]\">return";//activate salesperson
					else
						echo "<a href=\"mySalespeople.php?remove_sp=$salesData[primary_key_sp]\">remove";//deactivate salesperson
					echo "</div></td></tr>";
			}
			echo "</table>";

		}
		  ?>
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