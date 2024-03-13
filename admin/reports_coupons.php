<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");

session_start();

if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}



$anneeetmois = date("Y-m");//Donne exemple 2010-05
$jour = date("d");//Donne 18
$datecomplete = $anneeetmois  . '-' . $jour ;
$heading="Coupon code Usage report";
	

		
if ($_POST[date_from] != "All" && $_POST[date_to] != "All" ){//select between these dates
	$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];	
	}	
	
$heading.=$dateInfo;
$heading=ucwords($heading);

$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<form  method="post" name="goto_date" id="goto_date" action="reports_coupons.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Coupons Code Usage Report</font></b></td>
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
                        
                       	<td valign="top" align="left">
						<div align="left">Product code:</div>
					<select style="width:110px;"  size="15" name="primary_key_code" class="formField" id="primary_key_code">
                    
	 <?php
	$query="SELECT distinct code FROM coupon_codes WHERE code not like '%promo%' order by code ";
	$result=mysqli_query($con,$query)		or die ("Could not find lab list");
	while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		print "<option value=\"$labList[code]\">$labList[code]</option>";
}
?>
					</select></td>
					</tr>

				<tr >
					<td colspan="4"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
<?php 

if ($_POST[date_from] != "All" && $_POST[date_to] != "All" && $_POST[date_to] != "" ){//select between these dates
print '<p align="center">'. $heading . '</p';
}
print "<table width=\"30%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";

$queryAccount = "SELECT distinct A.*, C.user_id  FROM  accounts A, coupon_use C
WHERE A.user_id = C.user_id AND A.terms = 'agree'  AND A.approved = 'approved'";


if ($_POST[primary_key_code] <> "all"){
$queryAccount = $queryAccount . " AND c.code = '". $_POST[primary_key_code] . "'";
}



if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select between these dates
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$queryAccount.=" AND C.use_date between '$date_from' and '$date_to'";
	}


$rptAccount=mysqli_query($con,$queryAccount) 		or die  ('I cannot select items because: ' . mysqli_error($con));

while ($listAccount=mysqli_fetch_array($rptAccount,MYSQLI_ASSOC))
{
	print "<tr   bgcolor=\"#000000\"'><td><font color=\"white\">";
	print  $listAccount['company'] . "  : " . $listAccount['user_id']   . '</font></td>';
	echo "<td><font color=\"white\">Coupon Use</font></td>" ;
	print "</tr>";
	$queryCouponCode = "select * from coupon_codes WHERE  date > $datecomplete";
	

	$rptCouponCode=mysqli_query($con,$queryCouponCode) 		or die  ('I cannot select items because: ' . mysqli_error($con));
	while ($listCouponCode=mysqli_fetch_array($rptCouponCode,MYSQLI_ASSOC))
	{
		
	$queryCountCoupon = "SELECT COUNT(*) as NbrUsageduCoupon FROM coupon_use WHERE user_id = '" . $listAccount['user_id']. "'  and   code = '".   $listCouponCode['code']. "'" . " AND code = '". $_POST[primary_key_code] . "'";

if ($_POST['primary_key_code'] == 'all') {
$queryCountCoupon = "SELECT COUNT(*) as NbrUsageduCoupon FROM coupon_use WHERE user_id = '" . $listAccount['user_id']. "'  and   code = '".   $listCouponCode['code']. "'";
}



	
	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select between these dates
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];
		$queryCountCoupon.=" AND use_date between '$date_from' and '$date_to'";
	}
	
	


	$rptCountCoupon=mysqli_query($con,$queryCountCoupon) 	or die  ('I cannot select items because: ' . mysqli_error($con));
	$listCountCoupon=mysqli_fetch_array($rptCountCoupon,MYSQLI_ASSOC);

		if ($listCountCoupon['NbrUsageduCoupon'] > 0)
		{
		echo "<tr><td>" .$listCouponCode['code'] . "</td>" ;
		echo "<td align=\"center\">" . $listCountCoupon['NbrUsageduCoupon'] .  "</td></tr>" ;
		}
	
	}//End while listCouponCode
}//End while listAccount
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