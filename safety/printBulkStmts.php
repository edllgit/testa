<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];
$logo_file=$_SESSION["labAdminData"]["logo_file"];

if($_POST["stmt_search"]=="prepare statements"){
	$rptQuery="SELECT buying_groups.bg_name, buying_groups.contact_first, buying_groups.contact_last, accounts.user_id as user_id, accounts.account_num, accounts.buying_group, accounts.title, accounts.first_name, accounts.last_name, accounts.bill_address1, accounts.bill_address2, accounts.bill_city, accounts.bill_state, accounts.bill_zip, accounts.bill_country, orders.order_num as order_num, orders.po_num, orders.patient_ref_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, accounts.company, orders.order_status, payments.pmt_amount, payments.pmt_marker, payments.pmt_date from orders

LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

LEFT JOIN (buying_groups) ON (accounts.buying_group = buying_groups.primary_key) 

LEFT JOIN (payments) ON (orders.order_num = payments.order_num) 

WHERE orders.lab='$lab_pkey' AND orders.order_num != '0'";

	$date_from=$_POST["stmt_year"] . "-" . $_POST["stmt_month"] . "-01";//select correct month and year for statements
	$month_test=strtotime($date_from);
	$days_in_month=date("t", $month_test);
	$date_to=$_POST["stmt_year"] . "-" . $_POST["stmt_month"] . "-" . $days_in_month;
	$dateInfo = $_POST["stmt_month"] . "-" . $_POST["stmt_year"];
	$rptQuery.=" AND orders.order_date_shipped between '$date_from' and '$date_to'";

	if($_POST["stmt_sort"]=="account")
		$rptQuery.=" group by order_num desc order by company";
	else
		$rptQuery.=" group by order_num desc order by bg_name, company";
		
	$heading=$dateInfo . " Direct-Lens Statement";
	$heading=ucwords($heading);
}
$_SESSION["RPTQUERY"]=$rptQuery;
$_SESSION["heading"]=$heading;

if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)
		or die  ('I cannot select items because: ' . mysql_error().$rptQuery);
	$orderCount=mysql_num_rows($rptResult);
	$rptQuery="";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Account Statement</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css" />

</head>

<body>
<?php
if ($orderCount != 0){
	$acctTotal=0;
	$current_header="";
	while ($listItem=mysql_fetch_array($rptResult)){
		if($_POST["stmt_sort"]=="account")
			$new_header=$listItem["company"];
		else
			$new_header=$listItem["bg_name"];
		if($current_header != $new_header){//we've encountered the next acct
			if($current_header!=""){//if this isn't the first acct print the previous acct totals
				$acct_user_id=$listItem["user_id"];
				$query="SELECT * from statement_credits WHERE acct_user_id='$acct_user_id' AND stmt_month='$_POST[stmt_month]' AND stmt_year='$_POST[stmt_year]'";//get end of month credit for this acct
				$result=mysql_query($query)
					or die  ('I cannot select credits because: ' . mysql_error().$rptQuery);
				$credit_count=mysql_num_rows($result);
				if($credit_count != 0){
					while($credit_acct=mysql_fetch_array($result)){
						$credit_amt=money_format('%.2n',$credit_acct["amount"]);
						print "<tr><td colspan=\"7\" class=\"Subheader\">$credit_acct[credit_option] Credit for $current_header</td><td class=\"Subheader\"><div align=\"right\">\$$credit_amt</div></td></tr>";//print the previous acct's totals
						$acctTotal=bcsub($acctTotal, $credit_amt, 2);//subtract end of month credit
					}
				}
				$acctTotal=money_format('%.2n',$acctTotal);
				print "<tr><td colspan=\"7\" class=\"Subheader\">TOTAL FOR $current_header</td><td class=\"Subheader\"><div align=\"right\">\$$acctTotal</div></td></tr>";//print the previous acct's totals
				print "</table>";
				$acctTotal=0;//zero out the counter
				print "<div style=\"page-break-after:always\"></div>";
			}//END IF A NEW ACCT AFTER THE 1ST
			$current_header=$new_header;//make the new acct the current acct
			if($_POST["stmt_sort"]=="account")
				include("displayBulkStmtAcct.php");//print the acct header info
			else
				include("displayBulkStmtBG.php");//print the BG header info
		}//END IF ANY NEW ACCT
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
			$orderTotal=money_format('%.2n',$listItem["order_total"]);
			if($listItem["pmt_amount"]==0){
				$pmt_status="Open";
				$pmt_amount="";
				$acctTotal=bcadd($acctTotal, $orderTotal, 2);
			}else{
				$pmt_status="Paid";
				$pmt_amount=money_format('%.2n',$listItem["pmt_amount"]);
			}
			$acct_user_id=$listItem["user_id"];
			include("displayBulkStmtOrders.php");//print the order row
	}//END WHILE
	$query="SELECT * from statement_credits WHERE acct_user_id='$acct_user_id' AND stmt_month='$_POST[stmt_month]' AND stmt_year='$_POST[stmt_year]'";//get end of month credit for this acct
	$result=mysql_query($query)
		or die  ('I cannot select credits because: ' . mysql_error().$rptQuery);
	$credit_count=mysql_num_rows($result);
	if($credit_count != 0){
		while($credit_acct=mysql_fetch_array($result)){
			$credit_amt=money_format('%.2n',$credit_acct["amount"]);
			print "<tr><td colspan=\"7\" class=\"Subheader\">$credit_acct[credit_option] Credit for $current_header</td><td class=\"Subheader\"><div align=\"right\">\$$credit_amt</div></td></tr>";//print the previous acct's totals
			$acctTotal=bcsub($acctTotal, $credit_amt, 2);//subtract end of month credit
		}
	}
	$acctTotal=money_format('%.2n',$acctTotal);//total up for the last acct
	print "<tr><td colspan=\"8\" class=\"Subheader\">TOTAL FOR $current_header</td><td class=\"Subheader\"><div align=\"right\">\$$acctTotal</div></td></tr>";//print the last acct's totals
	print "</table>";
}else{
	print "<div class=\"formField\">No Orders Found</div>";
}//END orderCount CONDITIONAL
?>
</body>
</html>