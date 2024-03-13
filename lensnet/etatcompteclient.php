<?php 
session_start();
unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);
require_once(__DIR__.'/../constants/aws.constant.php');
require('../Connections/sec_connect.inc.php');
include "../includes/getlang.php";
include("../labAdmin/export_functions_w_prices.inc.php");
//include("admin/admin_functions.inc.php");

$user_id=$_SESSION["sessionUser_Id"];

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");


if ($_GET["q"]!=""){// IF COLUMN SORT CLICKED ON
	$query=$_SESSION["QUERY"]." ".$_GET["q"];
}
else if (($_SESSION["QUERY"]!="")&&($_GET["q"]=="")) {//IF SEARCH FORM USED
	$query=$_SESSION["QUERY"]." desc";
}
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

<?php

function firstOfMonth() {
return date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
}

$today=date("Y-m-d");
$firstDay = firstOfMonth();
$lastDay = lastOfMonth();

if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[stmt_search]=="prepare statements"){
	$rptQuery="SELECT buying_groups.bg_name, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.patient_ref_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date from orders
LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 
LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 
WHERE orders.lab='$lab_pkey' AND orders.order_num != '0'";

	$date_from=$_POST[stmt_year] . "-" . $_POST[stmt_month] . "-01";//select correct month and year for statements
	$month_test=strtotime($date_from);
	$days_in_month=date("t", $month_test);
	$date_to=$_POST[stmt_year] . "-" . $_POST[stmt_month] . "-" . $days_in_month;
	$dateInfo = $_POST[stmt_month] . "-" . $_POST[stmt_year];
	$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";

	if($_POST[stmt_sort]=="account")
		$rptQuery.=" group by order_num desc order by company";
	else
		$rptQuery.=" group by order_num desc order by bg_name";
		
	$heading=$dateInfo . "Statement for";
	$heading=ucwords($heading);
}
$_SESSION["RPTQUERY"]=$rptQuery;
$_SESSION["heading"]=$heading;
?>
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
	include("includes/sideNav.inc.php");
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form method="post" target="_blank" action="printBulkStmts.php" method="goto_date" name="goto_date" id="goto_date"><div class="header"><?php echo $lbl_titlemast_uma;?></div><div class="loginText"><?php echo $lbl_user_txt;?> 
<?php echo $_SESSION["sessionUser_Id"];?></div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
				 <tr align="center" bgcolor="#DDDDDD">
                    <td>
						<?php echo 'Show Statement for:'; ?>
					    <?php 
					$tomorrow = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
					$lemois = date("m", $tomorrow);
					?>
				
				<input name="stmt_type" type="hidden" value="individual" class="formField" checked="checked">
                
				<input name="date_from" type="text" class="formField" id="date_from" value="<?php echo $firstDay; ?>" size="11">
							<A HREF="#" onClick="cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor1xx" ID="anchor1xx"><img src="http://www.direct-lens.com/lensnet/images/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A>&nbsp;&nbsp;&nbsp;<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="date_to" type="text" class="formField" id="date_to" value="<?php echo $lastDay; ?>" size="11">
							<A HREF="#" onClick="cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor2xx" ID="anchor2xx"><img src="http://www.direct-lens.com/lensnet/images/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A></td>
				</tr>
				<tr>
					<td colspan="5"><div align="center"><input name="submit" type="submit" id="submit" value="<?php echo $btn_preparestate_txt; ?>" class="formField"><input name="stmt_search" type="hidden" id="stmt_search" value="prepare statements" class="formField"></div></td>
					</tr>	
                
			</table>
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