<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
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
	unset($_SESSION["week_start"]);
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST["rpt_search"]=="build report"){
	$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.company, orders.order_num, orders.lab, orders.order_total, orders.order_date_processed from orders

	LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) ";

	if($_POST[date_range] == "day"){
		$day_of_week=date("l",strtotime($_POST["date_of_week"]));
		$date_of_week=date("Y-m-d",strtotime($_POST["date_of_week"]));
		$rptQuery.="WHERE orders.lab='$lab_pkey' AND orders.order_num != '0' AND orders.order_status!='cancelled' AND orders.order_date_processed = '$date_of_week'";
		$heading="Sales Report for $day_of_week, $date_of_week";
	}else{
		$day_of_week=date("w",strtotime($_POST["date_of_week"]));
		$date_of_week=date("Y-m-d",strtotime($_POST["date_of_week"]));
		switch($day_of_week){
			case 0:
				$week_start=$date_of_week;
			break;
			case 1:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -1 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
			case 2:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -2 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
			case 3:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -3 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
			case 4:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -4 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
			case 5:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -5 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
			case 6:
				$startQuery=mysql_query("SELECT DATE_ADD('$date_of_week', INTERVAL -6 DAY)");
				$week_start=mysql_result($startQuery, 0, 0);
			break;
		}
		$endQuery=mysql_query("SELECT DATE_ADD('$week_start', INTERVAL 6 DAY)");
		$week_end=mysql_result($endQuery, 0, 0);
		$_SESSION["week_start"] = $week_start;
		
		$rptQuery.="WHERE orders.lab='$lab_pkey' AND orders.order_num != '0' AND orders.order_status!='cancelled' AND orders.order_date_processed between '$week_start' and '$week_end'";
		$heading="Sales Report for week of $week_start - $week_end";
	}
	
	$rptQuery.=" group by order_num order by company";
	
}//END IF

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
    myCalendar = new dhtmlXCalendarObject(["date_of_week"]);
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
<form  method="post" name="goto_date" id="goto_date" action="reports_sales.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo $adm_titlemast_weekly;?></font></b></td>
            		</tr>
				<tr bgcolor="#DDDDDD">
					<td nowrap><div align="right">
						<?php echo $adm_reportdate_txt;?>
					</div></td>
					<td><input name="date_of_week" type="text" class="formField" id="date_of_week" value="<?php echo $_POST[date_of_week]; ?>"  size="11"><!-- order_date_processed (date order submitted) -->
					</td>
					<td><div align="left"> 
					  <input name="date_range" type="radio" class="formField" id="date_range" value="week" <?php if(($_POST[date_range]=="week")||($_POST[date_range]=="")) echo "checked"; ?>>
				    <?php echo $adm_week_txt;?> </div></td>
					<td><div align="left">
					  <input name="date_range" type="radio" class="formField" id="date_range" value="day" <?php if($_POST[date_range]=="day") echo "checked"; ?>>
				    <?php echo $adm_day_txt;?> </div></td>
					<td nowrap><div align="right"> <?php echo $adm_emailto_txt;?></div></td>
					<td><input name="rpt_email_addr" type="text" class="formField" id="rpt_email_addr" size="30" value="<?php echo $_POST[rpt_email_addr]; ?>"></td>
				  </tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="6"><div align="center"><input name="rpt_search" type="submit" id="rpt_search" value="<?php echo $btn_buildreport_txt;?>" class="formField">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="rpt_email" type="submit" id="rpt_email" value="<?php echo $btn_emailreport_txt;?>" class="formField"></div></td>
					</tr>
			</table>
</form>
<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ($lbl_error1_txt . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
	if($_POST[date_range])
		$_SESSION["date_range"]=$_POST[date_range];
	$date_range=$_SESSION["date_range"];
}
			
if (($usercount != 0)&&($date_range=="week")){//some orders were found
	include("reports_sales_weekly.inc.php");
}
elseif (($usercount != 0)&&($date_range=="day")){//some orders were found
	include("reports_sales_daily.inc.php");
}else{
	echo "<div class=\"formField\">".$adm_noorders_txt."</div>";
}//END USERCOUNT CONDITIONAL
if (($_POST[rpt_email] == "email report")&&($fileOutput!="")&&($_POST[rpt_email_addr]!="")){
	$success=email_report($fileOutput, $_POST[rpt_email_addr]);
}
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

<DIV ID="testdiv2" STYLE="position:absolute;visibility:hidden;background-color:#FFE000;layer-background-color:white;"></DIV>
</body>
</html>
