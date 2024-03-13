<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[rpt_search]=="search orders" || true){
	$rptQuery="SELECT orders.order_num, orders.order_product_discount, orders.order_over_range_fee, orders.order_product_name, orders.order_product_index, orders.order_quantity, orders.order_date_processed, orders.lab, accounts.user_id as user_id, accounts.company from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

	WHERE orders.lab='$lab_pkey' AND orders.order_status!='basket' AND orders.order_status!='cancelled'";

	$heading="$_POST[product_index] Index Order Totals";
	$heading=ucfirst($heading);
	
	if($_POST[product_index]!="all"){
		$rptQuery.=" AND orders.order_product_index='" . $_POST[product_index] . "'";
	}

	if($_POST[acct_num]!=""){//if entered acct number
		$rptQuery.=" AND accounts.account_num='" . $_POST[acct_num] . "'";
		$query="select account_num, company from accounts where account_num='$_POST[acct_num]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" from account $acctData[company] with account number $_POST[acct_num]";
	}
	elseif($_POST[acctName]!=""){//if select ALL accounts
		$rptQuery.=" AND orders.user_id='" . $_POST[acctName] . "'";// ONE account was selected
		$query="select user_id, company from accounts where user_id='$_POST[acctName]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" from account $acctData[company]";
	}	

	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select Open orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_date_processed between '$date_from' and '$date_to'";
	}

	$rptQuery.=" order by company, order_product_index";
	$heading.=$dateInfo;
	$heading=ucwords($heading);
}

if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>

</head>

<body onLoad="window.print(); window.close();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td>
<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	$rptCount=mysql_num_rows($rptResult);
	$rptQuery="";
	$_SESSION["RPTQUERY"]=$rptQuery;
}

echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
echo "<tr bgcolor=\"#000000\"><td colspan=\"3\"><font color=\"white\">$heading</font></td></tr>";

if ($rptCount != 0){
	$product_index=ucfirst($_POST[product_index]);
	echo "<tr bgcolor=\"DDDDDD\"><td>Index</td><td align=\"right\">Index Total</td><td align=\"right\">Sales Total</td></tr>";
	$prodLineTotal=0;//product quantity by product name
	$prodAcctTotal=0;//product quantity by acct
	$grandProdTotal=0;//product quantity grand total
	$salesLineTotal=0;//sales total by product name
	$salesAcctTotal=0;//sales total by acct
	$grandSalesTotal=0;//sales grand total
	while ($listItem=mysql_fetch_array($rptResult)){
		if(($currentAcct=="")&&($currentIndex=="")){//first go round
			$currentAcct=$listItem[company];//reset current lab
			$currentIndex=$listItem[order_product_index];//reset current product
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
		elseif($currentAcct!=$listItem[company]){//encountered a new lab to list, close out ALL totals and print totals line
			$prodAcctTotal=bcadd($prodAcctTotal, $prodLineTotal);
			$grandProdTotal=bcadd($grandProdTotal, $prodAcctTotal);
			$salesAcctTotal=bcadd($salesAcctTotal, $salesLineTotal, 2);
			$grandSalesTotal=bcadd($grandSalesTotal, $salesAcctTotal, 2);
			echo "<tr>
			<td>$currentIndex</td>
			<td align=\"right\">$prodLineTotal</td>
			<td align=\"right\">\$$salesLineTotal</td>
			</tr>";
	echo "<tr bgcolor=\"#555555\"><td><font color=\"white\">Total $product_index Index Sales for $currentAcct</font></td><td align=\"right\"><font color=\"white\">$prodAcctTotal</font></td><td align=\"right\"><font color=\"white\">\$$salesAcctTotal</font></td></tr>";//print final total
			$salesLineTotal=0;
			$prodLineTotal=0;
			$prodAcctTotal=0;
			$salesAcctTotal=0;
			$currentAcct=$listItem[company];//reset current lab
			$currentIndex=$listItem[order_product_index];//reset current product
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
		elseif($currentIndex!=$listItem[order_product_index]){//encountered a new product to list, close out product total
			$prodAcctTotal=bcadd($prodAcctTotal, $prodLineTotal);
			$salesAcctTotal=bcadd($salesAcctTotal, $salesLineTotal, 2);
			echo "<tr>
			<td>$currentIndex</td>
			<td align=\"right\">$prodLineTotal</td>
			<td align=\"right\">\$$salesLineTotal</td>
			</tr>";
			$prodLineTotal=0;
			$salesLineTotal=0;
			$currentIndex=$listItem[order_product_index];//reset current product
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
		elseif(($currentAcct==$listItem[company])&&($currentIndex==$listItem[order_product_index])){//nothing changes, just add the data
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
	}//END WHILE
	echo "<tr><td>$currentIndex</td><td align=\"right\">$prodLineTotal</td><td align=\"right\">\$$salesLineTotal</td></tr>";//print final product line
	$prodAcctTotal=bcadd($prodAcctTotal, $prodLineTotal);
	$grandProdTotal=bcadd($grandProdTotal, $prodAcctTotal);
	$salesAcctTotal=bcadd($salesAcctTotal, $salesLineTotal, 2);
	$grandSalesTotal=bcadd($grandSalesTotal, $salesAcctTotal, 2);
	echo "<tr bgcolor=\"#555555\"><td><font color=\"white\">Total $product_index Index Sales for $currentAcct</font></td><td align=\"right\"><font color=\"white\">$prodAcctTotal</font></td><td align=\"right\"><font color=\"white\">\$$salesAcctTotal</font></td></tr>";//print final total
	echo "<tr bgcolor=\"#000000\"><td><font color=\"white\">Total Sales by Index</font></td><td align=\"right\"><font color=\"white\">$grandProdTotal</font></td><td align=\"right\"><font color=\"white\">\$$grandSalesTotal</font></td></tr>";//print grand total

}else{
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\">No Orders Found</td></tr>";
}//END rptCount CONDITIONAL
echo "</table>";
?>
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
