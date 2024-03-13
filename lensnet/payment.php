<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";

session_start();

$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);

$user_id = $_SESSION["sessionUser_Id"];
	
if($_GET[frompage]=="process_order"){
}

$result=mysql_query("SELECT DATE_ADD('$_SESSION[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($_SESSION["currentTotal"], $_SESSION["order_shipping_cost"], 2);

if($discountdate_15 >= $today){
	$discountamt=bcmul('.02', $item_total, 2);
	$pass_disc=".02";
	$discount = "2%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.01', $item_total, 2);
	$pass_disc=".01";
	$discount = "1%";
}
$discounted_total_cost = bcsub($_SESSION["currentTotal"], $discountamt, 2);

$uniqid = rand(100000, 999999);//for refresh test only
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="https://www.lensnetclub.com/" />
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

U{
text-decoration:none;
}

.main{
	color:#666;
	font-family:Arial, Helvetica, sans-serif;
	font-size: 13px;
	text-align: left;
}
.main a{
	color:#2D358E;
	text-decoration:none;
}
.main a:hover{
text-decoration:underline;
}
.main H2{
	color:#666;
}
.main input, .main select {

}
.main select{
cursor:pointer;
}
.main #submit{
	cursor:auto;
}
.submitButton{

}
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
  	<?php   
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
  <div class="header">
		  	Make  Payment
		  </div><div class="loginText">User: 
			<?php 
			if ($_SESSION["sessionUser_Id"]!=""){
			echo $_SESSION["sessionUser_Id"];}
			else{
			echo "not logged in";}?></div>
		  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
		    <tr >
		      <td bgcolor="#000099" class="tableHead">&nbsp;</td>
	        </tr>
		    <tr>
		      <td align="center" ><div align="center" style="padding-top:15px"><img src="https://www.direct-lens.com/lensnet/images/CC.png" width="360" height="53" /></div></td>
	        </tr>
		    <tr>
		      <td align="left" ><div align="center" style="width:420px; margin-left:200px">[[FORM INSERT]]</div></td>
	        </tr>
      </table>
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