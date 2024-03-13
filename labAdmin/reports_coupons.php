<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../Connections/directlens.php";
include "../includes/getlang.php";

$anneeetmois = date("Y-m");//Donne exemple 2010-05
$jour = date("d");//Donne 18
$datecomplete = $anneeetmois  . '-' . $jour ;


 if ($mylang == 'lang_french'){
		$heading="Rapport d'utilisation des coupons-codes";
 }else {
		$heading="Coupon code Usage report";	
 }
	
		
if ($_POST[date_from] != "All" && $_POST[date_to] != "All" ){//select between these dates

	 if ($mylang == 'lang_french'){
			$dateInfo = " pour l'intervalle de dates: " . $_POST[date_from] . " - " . $_POST[date_to];	
	 }else {
			$dateInfo = " for date range: " . $_POST[date_from] . " - " . $_POST[date_to];	
	 }	

}	
	
$heading.=$dateInfo;
//$heading=ucwords($heading);

$_SESSION["heading"]=$heading;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
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
<form  method="post" name="goto_date" id="goto_date" action="reports_coupons.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
					<?php if ($mylang == 'lang_french'){
					echo 'Rapport sur l\'utilisation des coupons-codes' ;
					}else {
					echo 'Coupons Code Usage Report' ;
					}
					?> 
					
					</font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td width="15%"><div align="right">
		<?php if ($mylang == 'lang_french'){
		echo 'De';
		}else {
		echo 'Date From';
		}
		?>
			
					</div></td>
					<td width="15%"><input name="date_from" type="text" class="formField" id="date_from" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> size="11">
							</td>
					<td width="15%"><div align="right">
		<?php if ($mylang == 'lang_french'){
		echo 'Jusqu\'au';
		}else {
		echo 'Through';
		}
		?>
						
					</div></td>
					<td colspan="2"><input name="date_to" type="text" class="formField" id="date_to" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> size="11">
						</td>
					
					<td>
			
			<?php if ($mylang == 'lang_french'){
					echo 'Chercher dans un seul compte:';
					}else {
					echo 'Search in a specific account:';
					}
					?> 
			
			
			<select name="account" id="account" class="formField">
				<option value=""><?php echo '';?></option>
				<?php
	$query="select primary_key, company, last_name, first_name from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company, last_name";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
			</select>
					</td>
					
					</tr>

				<tr >
					<td align="right" colspan="4"><div align="right">
					 
					<input align="middle" name="rpt_search" type="submit" id="rpt_search" 
					
					<?php if ($mylang == 'lang_french'){
					echo 'value="Rechercher"' ;
					}else {
					echo 'value="search orders"' ;
					}
					?> 
					class="formField">
					</div>
					</td>
				</tr>		
</form>
<?php 

if ($_POST[date_from] != "All" && $_POST[date_to] != "All" && $_POST[date_to] != "" ){//select between these dates

}
echo "<table width=\"50%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
echo '<tr><td>&nbsp;</td></tr><tr><td align="center">'. $heading . '</td><td>&nbsp;</td></tr>';
echo '<tr><td>&nbsp;</td></tr>';

$queryAccount = "SELECT distinct A.*, C.user_id  FROM  accounts A, coupon_use C
WHERE A.user_id = C.user_id AND A.terms = 'agree'  AND A.approved = 'approved' AND A.main_lab =". $_SESSION[lab_pkey];

if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select between these dates
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$queryAccount.=" AND C.use_date between '$date_from' and '$date_to'";
	}

	if ($_POST[account] != ''){
		$queryAccount .= " AND A.primary_key = " . $_POST[account] ;
		}
$rptAccount=mysql_query($queryAccount) 		or die  ('I cannot select items because: ' . mysql_error());

while ($listAccount=mysql_fetch_array($rptAccount))
{
	echo "<tr   bgcolor=\"#000000\"'><td><font color=\"white\">";
	
	
	if ($mylang == 'lang_french'){
	echo  'Compte: ' . $listAccount['company'] . "  : "   . '</font></td>';
	}else {
	echo  'Account: '. $listAccount['company'] . "  : "    . '</font></td>';
	}

	
	if ($mylang == 'lang_french'){
	echo "<td><font color=\"white\">Nombre de coupon</font></td>" ;
	}else {
	echo "<td><font color=\"white\">Coupon Use</font></td>" ;
	}
				

	echo "</tr>";
	$queryCouponCode = "select * from coupon_codes WHERE date > $datecomplete";
	$rptCouponCode=mysql_query($queryCouponCode) 		or die  ('I cannot select items because: ' . mysql_error());
	while ($listCouponCode=mysql_fetch_array($rptCouponCode))
	{
		
	$queryCountCoupon = "SELECT COUNT(*) as NbrUsageduCoupon FROM coupon_use WHERE user_id = '" . $listAccount['user_id']. "' AND   code = '".   $listCouponCode['code']. "'";
	
	if ($_POST[date_from] != "All" && $_POST[date_to] != "All"){//select between these dates
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$queryCountCoupon.=" AND use_date between '$date_from' and '$date_to'";
	}
	
	
	$rptCountCoupon=mysql_query($queryCountCoupon) 	or die  ('I cannot select items because: ' . mysql_error());
	$listCountCoupon=mysql_fetch_array($rptCountCoupon);

		if ($listCountCoupon['NbrUsageduCoupon'] > 0)
		{
		echo '<tr><td>&nbsp;</td></tr>';
		echo "<tr><td>" .$listCouponCode['code'] . "</td>" ;
		echo "<td align=\"center\">" . $listCountCoupon['NbrUsageduCoupon'] .  "</td></tr>" ;
		}
	
	}//End while listCouponCode
echo '<tr><td>&nbsp;</td></tr>';

}//End while listAccount
echo "</table>";
?>
</td>
	  </tr>
</table><br><br></table>
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
