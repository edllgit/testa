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

if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[rpt_search]=="search orders"){
	$rptQuery="SELECT orders.order_num, orders.order_product_discount, orders.order_over_range_fee, orders.order_product_name, orders.order_quantity, orders.order_date_processed, orders.lab, accounts.user_id as user_id, accounts.company from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 

	WHERE orders.lab='$lab_pkey' AND orders.order_status!='basket' AND orders.order_status!='cancelled' AND order_product_coating='Dream AR' AND orders.order_num!='0'";

	$heading=$adm_dreamar_txt;
	
	if($_POST[acct_num]!=""){//if entered acct number
		$rptQuery.=" AND accounts.account_num='" . $_POST[acct_num] . "'";
		$query="select account_num, company from accounts where account_num='$_POST[acct_num]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" ".$adm_fromacct_txt." $acctData[company] with account number $_POST[acct_num]";
	}
	elseif($_POST[acctName]!=""){//if select ALL accounts
		$rptQuery.=" AND orders.user_id='" . $_POST[acctName] . "'";// ONE account was selected
		$query="select user_id, company from accounts where user_id='$_POST[acctName]'";
		$result=mysql_query($query)
			or die ("Could not find acct list");
		$acctData=mysql_fetch_array($result);
		$heading.=" ".$adm_fromacct_txt." $acctData[company]";
	}	

	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select Open orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " ".$adm_fordate_txt." " . $_POST[date_from] . " - " . $_POST[date_to];
		$rptQuery.=" AND orders.order_date_processed between '$date_from' and '$date_to'";
	}

	$rptQuery.=" order by company, order_product_name";
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
<form  method="post" name="goto_date" id="goto_date" action="reports_Dream_AR.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_titlemast_dream; ?></font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="25%"><div align="right">
						<?php echo $adm_datefr_txt; ?>
					</div></td>
					<td width="15%"><input name="date_from" type="text" class="formField" id="date_from" value="<?php (isset($_POST[date_from]) ? $_POST[date_from] : "All")?>" size="11">
					</td>
					<td width="15%"><div align="center">
						<?php echo $adm_through_txt; ?>
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value="<?php (isset($_POST[date_to]) ? $_POST[date_to] : "All")?>" size="11">
					</td>
					</tr>
				<tr bgcolor="#FFFFFF">
					<td><div align="right">
						<?php echo $adm_selacct_txt; ?>
					</div></td>
					<td align="left" nowrap ><select name="acctName" class="formField">
						<option value="" >All</option>
						<?php
	$query="select company, user_id, main_lab, approved from accounts where main_lab='$lab_pkey' and approved='approved' order by company";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[user_id]\" ".( (isset($_POST[acctName]) && $accountList[user_id] == $_POST[acctName]) ? "selected='selected'" : "").">$accountList[company]</option>";
}
?>
					</select></td>
					<td align="left" nowrap ><div align="right">
						<?php echo $adm_oracctnum_txt; ?>
					</div></td>
					<td align="left" nowrap ><input name="acct_num" value="<?php (isset($_POST[acct_num]) ? $_POST[acct_num] : "")?>" type="text" id="acct_num" size="10" class="formField"></td>
					</tr>
				<tr>
					<td colspan="4"><div align="center"><input name="submit" type="submit" id="submit" value="<?php echo $btn_searchord_txt; ?>" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
<?php 
$exportForm="<form  method=\"post\" name=\"export_form\" id=\"export_form\" action=\"export_file.php\" target=\"_blank\"><input name=\"exportData\" type=\"submit\" value=\"Export Data\" class=\"formField\"></form>";//Export Report button
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ($lbl_error1_txt . mysql_error());
	$rptCount=mysql_num_rows($rptResult);
//	$rptQuery="";
//	$_SESSION["RPTQUERY"]=$rptQuery;
}

echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">";
echo "<tr bgcolor=\"#000000\"><td colspan=\"2\"><font color=\"white\">$heading</font></td><td>$exportForm</td></tr>";

if ($rptCount != 0){
	echo "<tr bgcolor=\"DDDDDD\"><td>".$adm_prodname_txt."</td><td align=\"right\">".$adm_producttotal_txt."</td><td align=\"right\">".$adm_salestotal_txt."</td></tr>";
	$prodLineTotal=0;//product quantity by product name
	$prodAcctTotal=0;//product quantity by lab
	$grandProdTotal=0;//product quantity grand total
	$salesLineTotal=0;//sales total by product name
	$salesAcctTotal=0;//sales total by lab
	$grandSalesTotal=0;//sales grand total
	while ($listItem=mysql_fetch_array($rptResult)){
		if(($currentAcct=="")&&($currentProd=="")){//first go round
			$currentAcct=$listItem[company];//reset current lab
			$currentProd=$listItem[order_product_name];//reset current product
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
			<td>$currentProd</td>
			<td align=\"right\">$prodLineTotal</td>
			<td align=\"right\">\$$salesLineTotal</td>
			</tr>";
	echo "<tr bgcolor=\"#555555\"><td><font color=\"white\">".$adm_totdreamsalesfor_txt." $currentAcct</font></td><td align=\"right\"><font color=\"white\">$prodAcctTotal</font></td><td align=\"right\"><font color=\"white\">\$$salesAcctTotal</font></td></tr>";//print final total
			$salesLineTotal=0;
			$prodLineTotal=0;
			$prodAcctTotal=0;
			$salesAcctTotal=0;
			$currentAcct=$listItem[company];//reset current lab
			$currentProd=$listItem[order_product_name];//reset current product
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
		elseif($currentProd!=$listItem[order_product_name]){//encountered a new product to list, close out product total
			$prodAcctTotal=bcadd($prodAcctTotal, $prodLineTotal);
			$salesAcctTotal=bcadd($salesAcctTotal, $salesLineTotal, 2);
			echo "<tr>
			<td>$currentProd</td>
			<td align=\"right\">$prodLineTotal</td>
			<td align=\"right\">\$$salesLineTotal</td>
			</tr>";
			$prodLineTotal=0;
			$salesLineTotal=0;
			$currentProd=$listItem[order_product_name];//reset current product
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
		elseif(($currentAcct==$listItem[company])&&($currentProd==$listItem[order_product_name])){//nothing changes, just add the data
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_product_discount], 2);
			$salesLineTotal=bcadd($salesLineTotal, $listItem[order_over_range_fee], 2);
			$prodLineTotal=bcadd($prodLineTotal, $listItem[order_quantity]);
		}
	}//END WHILE
	echo "<tr><td>$currentProd</td><td align=\"right\">$prodLineTotal</td><td align=\"right\">\$$salesLineTotal</td></tr>";//print final product line
	$prodAcctTotal=bcadd($prodAcctTotal, $prodLineTotal);
	$grandProdTotal=bcadd($grandProdTotal, $prodAcctTotal);
	$salesAcctTotal=bcadd($salesAcctTotal, $salesLineTotal, 2);
	$grandSalesTotal=bcadd($grandSalesTotal, $salesAcctTotal, 2);
	echo "<tr bgcolor=\"#555555\"><td><font color=\"white\">".$adm_totdreamsalesfor_txt." $currentAcct</font></td><td align=\"right\"><font color=\"white\">$prodAcctTotal</font></td><td align=\"right\"><font color=\"white\">\$$salesAcctTotal</font></td></tr>";//print final total
	echo "<tr bgcolor=\"#000000\"><td><font color=\"white\">".$adm_totdreamsales_txt."</font></td><td align=\"right\"><font color=\"white\">$grandProdTotal</font></td><td align=\"right\"><font color=\"white\">\$$grandSalesTotal</font></td></tr>";//print grand total
	echo "<tr><td colspan='3' align='right'><a href='#' onclick='document.getElementById(\"goto_date\").target = \"_blank\"; document.getElementById(\"goto_date\").action = \"reports_Dream_AR_print.php\"; document.getElementById(\"goto_date\").submit(); return false;'>".$adm_print_txt."</a></td></tr>";//print grand total

}else{
	echo "<tr bgcolor=\"#FFFFFF\"><td colspan=\"3\">".$adm_noorders_txt."</td></tr>";
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
