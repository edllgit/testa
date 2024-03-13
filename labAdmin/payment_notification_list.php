<?php /*?><?php
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
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST["rpt_search"]=="search orders"){
		
	$rptQuery="SELECT payment_history.*, accounts.company from payment_history, accounts
	WHERE payment_history.customer = accounts.primary_key ";
	
	if ($_POST[check_account] <> '') {
	$rptQuery.=" AND check_account =  '". $_POST[check_account]. "'";
	}
	
		
		
		
	if (($_POST["date_from"] != "All") && ($_POST["date_to"] != "All")){//select Filled orders
		$date_from=date("Y-m-d",strtotime($_POST[date_from]));
		$date_to=date("Y-m-d",strtotime($_POST[date_to]));
		$dateInfo = " for date range: " . $_POST["date_from"] . " - " . $_POST["date_to"];
		$rptQuery.=" AND date_received  BETWEEN '$date_from' and '$date_to'";
	}

	$heading.=$dateInfo;
	$heading=ucwords($heading);
}//END IF ($_POST[updateStatus]=="fill order(s)")

if($rptQuery=="")
	$rptQuery=$_SESSION["RPTQUERY"];
$_SESSION["RPTQUERY"]=$rptQuery;
if($heading=="")
	$heading=$_SESSION["heading"];
$_SESSION["heading"]=$heading;
if($_POST["order_status"]!="")
	$_SESSION["order_status"]=$_POST["order_status"];
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
<form  method="post" name="goto_date" id="goto_date" action="payment_notification_list.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="8"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Search through Payment notification';?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
							
					<td width="15%" nowrap="nowrap"><div align="right">
						<?php echo $adm_datefr_txt;?>
					</div></td>
					<td width="20%" align="left" nowrap ><input name="date_from" type="text" class="formField" id="date_from" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?>  size="11"><!-- order_date_processed (date order submitted) --></td>
					<td width="5%" align="left" nowrap ><div align="center">
						<?php echo $adm_through_txt;?>
					</div></td>
					<td width="40%" align="left" nowrap ><input name="date_to" type="text" class="formField" id="date_to" value=<?php $today=getdate(time()); echo "\"".$today[mon]."/".$today[mday]."/".$today[year]."\""; ?> size="11"><!-- order_date_processed (date order submitted) --></td>
                        
                        <td>Check Account
                 <select name="check_account" id="check_account">
               <option     value="">All accounts</option>
                 <option <?php if($_POST[check_account]=='aitlensclub') echo 'selected';?> value="aitlensclub">AitLens Club</option>
                  <option <?php if($_POST[check_account]=='bbg') echo 'selected';?> value="bbg">Bbg Club</option>
                <option <?php if($_POST[check_account]=='directlab') echo 'selected';?> value="directlab">Directlab</option>
                <option <?php if($_POST[check_account]=='lensnetclub') echo 'selected';?> value="lensnetclub">LensNetClub</option>
                <option <?php if($_POST[check_account]=='mylensclub') echo 'selected';?> value="mylensclub">MyLensClub</option>

              </select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="8" bgcolor="#FFFFFF"><div align="center">
						<input name="submit" type="submit" id="submit" value="<?php echo 'Find Payment notifications';?>" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
                    <tr><td>&nbsp;</td></tr>
                    <?php  
					$tomorrow = mktime(0,0,0,date("m"),date("d")+1,date("Y"));
					$datedemain = date("Y-m-d", $tomorrow);
					
					$hier = mktime(0,0,0,date("m"),date("d")-1,date("Y"));
					$datehier = date("Y-m-d", $hier);
					
					
					$ajd = mktime(0,0,0,date("m"),date("d"),date("Y"));
					$dateajd = date("Y-m-d", $ajd);
				
					?> 
			</table>
</form>
<?php 
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery)		or die  ($lbl_error1_txt . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}			
			
if ($usercount != 0){//some orders were found
	echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
    echo "<td colspan=\"10\"><font color=\"white\">$heading</font></td>";
	echo "</tr>";
	if($_SESSION["order_status"]=="processing")
		echo "<form action=\"payment_notification_list.php\" method=\"post\" name=\"statusForm\">";
		//print the top heading row
		echo "
		<tr>
			<th align=\"center\">Date received</td>
			<th align=\"center\">Date recorded</td>
			<th align=\"center\">Customer</td>
			<th align=\"center\">Check account</td>
			<th align=\"center\">Deposited date</td>
			<th align=\"center\">Deposed by</td>
			<th align=\"center\">Comments</td>
			<th align=\"center\">Who fill form</td>
			<th align=\"center\">Amount</td>
		</tr>";
		while ($listItem=mysql_fetch_array($rptResult)){

	
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_processed]','%m-%d-%Y')");
			$order_date=mysql_result($new_result,0,0);
			$new_result=mysql_query("SELECT DATE_FORMAT('$listItem[order_date_shipped]','%m-%d-%Y')");
			$ship_date=mysql_result($new_result,0,0);
			$orderTotal=money_format('%.2n',$listItem["order_total"]);
			$orderTotal = 'N/A';
			
			echo  "<tr>
				
				<td align=\"center\">$listItem[date_received]</td>
                <td align=\"center\">$listItem[date_recorded]</td>
				<td align=\"center\">$listItem[company]</td>
				<td align=\"center\">$listItem[check_account]</td>
                <td align=\"center\">$listItem[deposited_date]</td>
                <td align=\"center\">$listItem[deposed_by]</td>
                <td align=\"center\">$listItem[comment]</td>
                <td align=\"center\">$listItem[who_fill_form]</td>
                <td align=\"center\">$listItem[amount]</td>";
              echo "</tr>";
		}//END WHILE
			
		echo "</table>";

}else{
	echo "<div class=\"formField\">No Payment notification Found</div>";}//END USERCOUNT CONDITIONAL
?></td>
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
</html><?php */?>