<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

if($_POST[rpt_search]=="search orders"){
	$rptQuery="SELECT orders.order_num, orders.order_product_discount, orders.order_over_range_fee, orders.order_product_name, orders.order_product_index, orders.order_quantity, orders.order_date_processed, orders.lab, labs.primary_key as lab_key, labs.lab_name from orders

	LEFT JOIN (labs) ON (orders.lab = labs.primary_key) 

	WHERE orders.order_status!='basket' AND orders.order_status!='cancelled'";

	$heading="$_POST[product_index] Index Order Totals";
	$heading=ucfirst($heading);
	
	if($_POST[product_index]!="all"){
		$rptQuery.=" AND orders.order_product_index='" . $_POST[product_index] . "'";
	}

	if($_POST[lab_name]!="all"){
		$rptQuery.=" AND orders.lab='" . $_POST[lab_name] . "'";
		$query="select primary_key, lab_name from labs where primary_key='$_POST[lab_name]'";
		$result=mysql_query($query)
			or die ("Could not find lab list");
		$labData=mysql_fetch_array($result);
		$heading.=" from lab $labData[lab_name]";
	}

	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select Open orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_date_processed between '$date_from' and '$date_to'";
	}

	$rptQuery.=" order by lab_name, order_product_index";
	$heading.=$dateInfo;
	$heading=ucwords($heading);
}

//$stmtForm="<form  method=\"post\" name=\"stmt_form\" id=\"stmt_form\" action=\"printStmt.php\" target=\"_blank\"><input name=\"accountStmt\" type=\"hidden\" value=\"$_POST[acctName]\"><input name=\"printStmt\" type=\"submit\" value=\"Print Statement\" class=\"formField\"></form>";//Print Statement button
//$exportForm="<form  method=\"post\" name=\"export_form\" id=\"export_form\" action=\"export_file.php\" target=\"_blank\"><input name=\"exportData\" type=\"submit\" value=\"Export Data\" class=\"formField\"></form>";//Export Report button
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

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="reports_index.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Sales
            					Totals by Index </font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="25%"><div align="right">
						Date From
					</div></td>
					<td width="15%"><input name="date_from" type="text" class="formField" id="date_from" value="All" size="11">
							<A HREF="#" onClick="cal1xx.select(document.goto_date.date_from,'anchor1xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor1xx" ID="anchor1xx"><img src="../includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A> &nbsp;&nbsp;&nbsp;</td>
					<td width="15%"><div align="center">
						Through
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="All" size="11">
						<A HREF="#" onClick="cal2xx.select(document.goto_date.date_to,'anchor2xx','MM/dd/yyyy'); return false;" TITLE="Popup calendar for quick date selection" NAME="anchor2xx" ID="anchor2xx"><img src="../includes/popup_cal.gif" width="14" height="14" hspace="2" border="0" align="absmiddle"></A></td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
						Select Lab
					</div></td>
					<td align="left" nowrap ><select name="lab_name" class="formField">
						<option value="all" selected>All</option>
						<?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		print "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
?>
					</select></td>
					<td align="left" nowrap ><div align="right">
						Select Index
					</div></td>
					<td align="left" nowrap ><select name="product_index" id="product_index" class="formField">
						<option value="all" selected>All</option>
						<?php
	$query="select order_product_index from orders group by order_product_index";
	$result=mysql_query($query)
		or die ("Could not find product list");
	while ($indexList=mysql_fetch_array($result)){
		print "<option value=\"$indexList[order_product_index]\">$indexList[order_product_index]</option>";
}
?>
					</select>
					</td>
					</tr>
				<tr>
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
	$rptCount=mysql_num_rows($rptResult);
	$rptQuery="";
	$_SESSION["RPTQUERY"]=$rptQuery;
}

print "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
print "<tr bgcolor=\"#000000\"><td colspan=\"3\"><font color=\"white\">$heading</font></td></tr>";

if ($rptCount != 0){
	$product_index=ucfirst($_POST[product_index]);
	print "<tr bgcolor=\"DDDDDD\"><td>Index</td><td align=\"right\">Index Total</td><td align=\"right\">Sales Total</td></tr>";
	$labTotal=0;//lab sales total
	$prodCount=0;//product quantity by product name
	$prodTotal=0;//product quantity by lab
	$prodAmtTotal=0;//product sales total by lab
	while ($listItem=mysql_fetch_array($rptResult)){
		if(($currentLab=="")&&($currentIndex=="")){//first go round
			$currentLab=$listItem[lab_name];//reset current lab
			$currentIndex=$listItem[order_product_index];//reset current product
			$labTotal=bcadd($labTotal, $listItem[order_product_discount], 2);
			$labTotal=bcadd($labTotal, $listItem[order_over_range_fee], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_product_discount], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_over_range_fee], 2);
			$prodCount=bcadd($prodCount, $listItem[order_quantity]);
		}
		elseif($currentLab!=$listItem[lab_name]){//encountered a new lab to list, close out ALL totals and print totals line
			$prodTotal=bcadd($prodTotal, $prodCount);
			print "<tr>
			<td>$currentIndex</td>
			<td align=\"right\">$prodCount</td>
			<td align=\"right\">\$$prodAmtTotal</td>
			</tr>";
			print "<tr bgcolor=\"#555555\"><td><font color=\"white\">Total $product_index Index Sales for $currentLab</font></td><td align=\"right\"><font color=\"white\">$prodTotal</font></td><td align=\"right\"><font color=\"white\">\$$labTotal</font></td></tr>";
			$labTotal=0;
			$prodCount=0;
			$prodTotal=0;
			$prodAmtTotal=0;
			$currentLab=$listItem[lab_name];//reset current lab
			$currentIndex=$listItem[order_product_index];//reset current product
			$labTotal=bcadd($labTotal, $listItem[order_product_discount], 2);
			$labTotal=bcadd($labTotal, $listItem[order_over_range_fee], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_product_discount], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_over_range_fee], 2);
			$prodCount=bcadd($prodCount, $listItem[order_quantity]);
		}
		elseif($currentIndex!=$listItem[order_product_index]){//encountered a new product to list, close out product total
			print "<tr>
			<td>$currentIndex</td>
			<td align=\"right\">$prodCount</td>
			<td align=\"right\">\$$prodAmtTotal</td>
			</tr>";
			$prodTotal=bcadd($prodTotal, $prodCount);
			$prodCount=0;
			$prodAmtTotal=0;
			$currentIndex=$listItem[order_product_index];//reset current product
			$labTotal=bcadd($labTotal, $listItem[order_product_discount], 2);
			$labTotal=bcadd($labTotal, $listItem[order_over_range_fee], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_product_discount], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_over_range_fee], 2);
			$prodCount=bcadd($prodCount, $listItem[order_quantity]);
		}
		elseif(($currentLab==$listItem[lab_name])&&($currentIndex==$listItem[order_product_index])){//nothing changes, just add the data
			$labTotal=bcadd($labTotal, $listItem[order_product_discount], 2);
			$labTotal=bcadd($labTotal, $listItem[order_over_range_fee], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_product_discount], 2);
			$prodAmtTotal=bcadd($prodAmtTotal, $listItem[order_over_range_fee], 2);
			$prodCount=bcadd($prodCount, $listItem[order_quantity]);
		}
	}//END WHILE
	print "<tr><td>$currentIndex</td><td align=\"right\">$prodCount</td><td align=\"right\">\$$prodAmtTotal</td></tr>";//print final product line
	$prodTotal=bcadd($prodTotal, $prodCount);
	print "<tr bgcolor=\"#555555\"><td><font color=\"white\">Total $product_index Index Sales for $currentLab</font></td><td align=\"right\"><font color=\"white\">$prodTotal</font></td><td align=\"right\"><font color=\"white\">\$$labTotal</font></td></tr>";//print final total

}else{
	print "<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\">No Orders Found</td></tr>";
}//END rptCount CONDITIONAL
print "</table>";
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
