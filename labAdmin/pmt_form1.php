<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("pmt_form1_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];
$today=date("m/d/Y");
if($_GET["reset"]=="y"){
	unset($_SESSION["RPTQUERY"]);
	unset($_SESSION["heading"]);
	$_SESSION["order_numbers"]=array();
	$_SESSION["ORDERSBALDATA"]=array();
	$_SESSION["order_amts"]=array();
	$_SESSION["POSTVARS"]=array();
	unset($_SESSION["orderCount"]);
	unset($_SESSION["totalCharge"]);
	unset($_SESSION["grandTotal"]);
	unset($_SESSION["user_id"]);
	unset($_SESSION["COMPANY"]);
	unset($_SESSION["FIRST_NAME"]);
	unset($_SESSION["LAST_NAME"]);
	unset($_SESSION["ADDRESS1"]);
	unset($_SESSION["ZIP"]);
	unset($_SESSION["CCLAST4"]);
	unset($_SESSION["CHECK_NO"]);
	unset($_SESSION["pay_allTotal"]);
	unset($_SESSION["user_id_total"]);
}

if(($_POST["submitPmt"]=="Submit")&&($_SESSION["grandTotal"])){//payment form on this page posts back to this page
	$message=make_labadmin_pmt();
}
if($_POST[find_orders]=="find order(s)"){//find orders form on this page posts back to this page
//reset current acct to posted customer acct
	$query="SELECT user_id, first_name, last_name, bill_address1, bill_zip, company from accounts WHERE user_id = '$_POST[acctName]' LIMIT 1";
	$result=mysql_query($query)
		or die ("Could not find account");
	$companyData = mysql_fetch_assoc($result);
	$_SESSION["COMPANY"] = $companyData["company"];
	$_SESSION["FIRST_NAME"] = $companyData["first_name"];
	$_SESSION["LAST_NAME"] = $companyData["last_name"];
//	$_SESSION["ADDRESS1"] = "3381 Steeles Avenue East"; //for Global Canadian testing
	$_SESSION["ADDRESS1"] = $companyData["bill_address1"];
//	$_SESSION["ZIP"] = "M2H 3S7"; //for Global Canadian testing
	$_SESSION["ZIP"] = $companyData["bill_zip"];
//reset payment variables to defaults
	$_SESSION["pay_all"] = "yes";
	$_SESSION["pmt_by"] = "check";
//calculate statement dates
	$date_from=date("Y-m-d", strtotime($_POST[date_from]));//select correct month and year for statements
	$date_to=date("Y-m-d", strtotime($_POST[date_to]));
	$stmt_credit_month=date("m", strtotime($_POST[date_to]));
	$stmt_credit_year=date("Y", strtotime($_POST[date_to]));
	$dateInfo = $_POST[date_from] . "-" . $_POST[date_to];
	$_SESSION["DATE_FROM"]=$date_from;
	$_SESSION["DATE_TO"]=$date_to;
	$_SESSION["STMT_CREDIT_MONTH"]=$stmt_credit_month;
	$_SESSION["STMT_CREDIT_YEAR"]=$stmt_credit_year;
//calculate previous balance and get previous balance orders array
	$_SESSION["ORDERSBALDATA"]=calc_prev_balance_with_memo();
	if($_SESSION["ORDERSBALDATA"]){//this should be an orders array if there are previous orders
		$count_prev_orders=count($_SESSION["ORDERSBALDATA"]);
		$prev_bal=$_SESSION["ORDERSBALDATA"][$count_prev_orders]["prev_bal"];
	}else{
		$prev_bal="0.00";
	}
	if($prev_bal=="")
		$prev_bal="0.00";
	$_SESSION["PREV_BAL"]=$prev_bal;
//calculate new current balance
	$_SESSION["order_numbers"]=array();
	$_SESSION["order_amts"]=array();
	unset($_SESSION["orderCount"]);
	unset($_SESSION["totalCharge"]);
	unset($_SESSION["grandTotal"]);
	
	$rptQuery="SELECT buying_groups.bg_name, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, orders.order_shipping_cost, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date, payments.order_paid_in_full, payments.order_balance from orders

LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 

LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 

WHERE orders.lab='$lab_pkey' AND orders.user_id='$_POST[acctName]' ";

	$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";

	$rptQuery.=" group by order_num desc";
		
	$heading=$dateInfo . " Statement for " . $_SESSION["COMPANY"];
$_SESSION["RPTQUERY"]=$rptQuery;
$_SESSION["heading"]=$heading;
}
$_SESSION["user_id"]=$_POST["acctName"];
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
<form  method="post" name="goto_date" id="goto_date" action="pmt_form1.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> Customer
            					Account Payment </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td><div align="right">
						
						Account
					</div></td>
					<td><select name="acctName" id="acctName" class="formField">
						<?php
	$query="select company, user_id, main_lab, approved from accounts where main_lab='$lab_pkey' and approved='approved' order by company";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		if($_POST[acctName]==$accountList["user_id"])
			echo "<option value=\"$accountList[user_id]\" selected=\"selected\">$accountList[company]</option>";
		else
			echo "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
}
?>
					</select></td>
					<td nowrap><div align="right">
						
						Statement Dates
				  </div></td>
					<td width="10%" nowrap><input name="date_from" type="text" class="formField" id="date_from" value="<?php if(isset($_POST[date_from])) echo $_POST[date_from]; else echo $today; ?>" size="11">
					</td><td nowrap><div align="center"> through </div></td>
				  <td nowrap><input name="date_to" type="text" class="formField" id="date_to" value="<?php if(isset($_POST[date_to])) echo $_POST[date_to]; else echo $today; ?>" size="11">
				  </td>
					</tr>
				<tr>
					<td colspan="6"><div align="center"><input name="find_orders" type="submit" id="find_orders" value="find order(s)" class="formField"></div></td>
					</tr>
			</table>
</form>
<?php
if($message=="Payment has been successfully submitted."){
	if(!$_SESSION["grandTotal"]){
		include("pmtRefreshError.php");
	}else{
		echo $message;
		include("getPmtReceipt.php");
		$_SESSION["order_numbers"]=array();
		$_SESSION["ORDERSBALDATA"]=array();
		$_SESSION["order_amts"]=array();
		$_SESSION["POSTVARS"]=array();
		unset($_SESSION["orderCount"]);
		unset($_SESSION["totalCharge"]);
		unset($_SESSION["grandTotal"]);
		unset($_SESSION["user_id"]);
		unset($_SESSION["COMPANY"]);
		unset($_SESSION["FIRST_NAME"]);
		unset($_SESSION["LAST_NAME"]);
		unset($_SESSION["ADDRESS1"]);
		unset($_SESSION["ZIP"]);
		unset($_SESSION["CCLAST4"]);
		unset($_SESSION["CHECK_NO"]);
		unset($_SESSION["pay_allTotal"]);
		unset($_SESSION["user_id_total"]);
	}
}
elseif($message=="There was a problem with the credit card payment. Please try again."){
	echo $message;
	include("getPmtInfo.php");
}
if($_POST[find_orders]=="find order(s)"){
	include("payStmtForm.php");
}
if($_POST[payOrders]=="pay order(s)"){
	$order_num = strtotime("now");
	include("getPmtInfo.php");
}
elseif(isset($_POST[pay_all])){
	$rptQuery=$_SESSION["RPTQUERY"];
	include("payStmtForm.php");
}
?>

<div class="formField2">
  <br/><b>Note:</b> Credits are ALWAYS applied, and must be accounted for when applying partial payments (subtracted from amount owed, or added to amount being applied).
</div>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
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