<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


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
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_from", "date_to"]);
}

</script>


</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="printBulkStmts_test.php" target="_blank">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="5"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo $adm_titlemast_pbs; ?> </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						<?php echo $adm_datefr_txt; ?>
					</div></td>
					<td width="30%"><input name="date_from" type="text" class="formField" id="date_from" value="<?php echo $firstDay; ?>" size="11">
							<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="date_to" type="text" class="formField" id="date_to" value="<?php echo $lastDay; ?>" size="11">
					</td>
					<td><div align="right">
						<?php echo $adm_showcred_txt; ?>
					</div></td>   <?php 
					
					
					$tomorrow = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
					$lemois = date("m", $tomorrow);
				
					
					?>
					<td width="10%"><select name="stmt_month" id="stmt_month" class="formField">
                 
						<option value="01"<?php  if ($lemois == 01)echo 'selected';?>>Jan - 01</option>
						<option value="02"<?php  if ($lemois == 02)echo 'selected';?>>Feb - 02</option>
						<option value="03"<?php  if ($lemois == 03)echo 'selected';?>>Mar - 03</option>
						<option value="04"<?php  if ($lemois == 04)echo 'selected';?>>Apr - 04</option>
						<option value="05"<?php  if ($lemois == 05)echo 'selected';?>>May - 05</option>
						<option value="06"<?php  if ($lemois == 06)echo 'selected';?>>Jun - 06</option>
						<option value="07"<?php  if ($lemois == 07)echo 'selected';?>>Jul - 07</option>
						<option value="08"<?php  if ($lemois == 08)echo 'selected';?>>Aug - 08</option>
						<option value="09"<?php  if ($lemois == 09)echo 'selected';?>>Sep - 09</option>
						<option value="10"<?php  if ($lemois == 10)echo 'selected';?>>Oct - 10</option>
						<option value="11"<?php  if ($lemois == 11)echo 'selected';?>>Nov - 11</option>
						<option value="12"<?php  if ($lemois == 12)echo 'selected';?>>Dec - 12</option>
					</select></td>
					<td><select name="stmt_year" id="stmt_year" class="formField">
						<option value="2008">2008</option>
						<option value="2009">2009</option>
						<option value="2010">2010</option>
						<option value="2011" >2011</option>
						<option value="2012" selected>2012</option>
						<option value="2013">2013</option>
						<option value="2014">2014</option>
						<option value="2015">2015</option>
					</select></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td nowrap><div align="right">
						<?php echo $adm_selstatetype_txt; ?></div></td>
					<td align="left" nowrap><input name="stmt_type" type="radio" value="individual" class="formField" checked="checked"><?php echo $adm_selacctbelow_txt; ?></td>
					<td align="left" nowrap><input name="stmt_type" type="radio" value="bulk" class="formField"><?php echo $adm_selbulk_txt; ?></td>
					<td colspan="2" align="left" nowrap>&nbsp;</td>
					</tr>
				<tr bgcolor="#DDDDDD">
					<td nowrap><div align="right">
						<?php echo $adm_selsort_txt; ?></div></td>
					<td align="left" nowrap><select name="acct_user_id" id="acct_user_id" class="formField">
	<option value=""><?php echo $adm_selacct_txt; ?></option>
	<?php
	$query="select user_id, company from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
}
?>
</select></td>
					<td align="left" nowrap><input name="stmt_sort" type="radio" value="account" class="formField" checked="checked"><?php echo $adm_acct_txt; ?>&nbsp;&nbsp;
<input name="stmt_sort" type="radio" value="buying group" class="formField"><?php echo $adm_buygrp_txt; ?></td>
					<td colspan="2" align="left" nowrap>&nbsp;</td>
					</tr>
				<tr>
					<td colspan="5"><div align="center"><input name="submit" type="submit" id="submit" value="<?php echo $btn_preparestate_txt; ?>" class="formField"><input name="stmt_search" type="hidden" id="stmt_search" value="prepare statements" class="formField"></div></td>
					</tr>
			</table>
</form>
</td>
	  </tr>
</table>
<SCRIPT LANGUAGE="JavaScript" ID="jscal1xx">
var cal1xx = new CalendarPopup("testdiv1");
cal1xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv1" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>

<SCRIPT LANGUAGE="JavaScript" ID="jscal2xx">
var cal2xx = new CalendarPopup("testdiv2");
cal2xx.showNavigationDropdowns();
</SCRIPT>
<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
