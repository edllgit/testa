<?php require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION["labAdminData"]["username"]==""){
	echo "You are not logged in. Click <a href='/labAdmin'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

	if($_POST["order_num"]!="")
	{//search for order number only and ignore all other form settings
		$rptQuery="SELECT * FROM memo_credits_status_history WHERE  order_num = '$_POST[order_num]' ORDER BY  mcred_memo_num, status_history_id ";
		$heading="Order Number $_POST[order_num]";
	}
	//echo '<br><br>' . $rptQuery;
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
<?php echo "<div style='font-size:10px'>Welcome Back ".$_SESSION["labAdminData"]["username"]."</div>"; ?>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="check_credit_status.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo 'Check Credit Status (Credits emitted with the Old system will not appear in this report)'; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td nowrap bgcolor="#DDDDDD" ><div align="right">
						<?php echo $adm_ordernum_txt; ?>
					</div></td>
					<td colspan="3" align="left" nowrap="nowrap"><input name="order_num" type="text" id="order_num" size="10" class="formField"></td>
				</tr>
				
				<tr bgcolor="#DDDDDD">
					<td colspan="6"><div align="center"><input name="submit" type="submit" id="submit" value="<?php echo $btn_searchord_txt; ?>" class="formField"><input name="rpt_search" type="hidden" id="rpt_search" value="search orders" class="formField"></div></td>
					</tr>
			</table>
</form>
			<?php 
			
			if ($rptQuery!=""){
				$rptResult=mysql_query($rptQuery)		or die  ('I cannot select items because: ' . mysql_error());
			$usercount=mysql_num_rows($rptResult);
				$rptQuery="";}	
if ($usercount != 0){//some orders were found
	echo "<table width=\"100%\" border=\"10\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\"><tr bgcolor=\"#000000\">";
	if((($_POST["acctName"]!="")||($_POST["acct_num"]!=""))&&(($_POST["order_status"]=="filled")&&($_POST["order_type"]=="all"))){
		echo "<td colspan=\"11\"><font color=\"white\">$heading</font></td>";//show Print Statement button if one acct is selected, order status is filled (Shipped) and order type is ALL
	}
	echo "</tr>";
	
	//print the top heading row
	echo "<tr>
			<th align=\"center\">Credit Number</th>
			<th align=\"center\">Status EN</th>
			<th align=\"center\">Status FR</th>
			<th align=\"center\">Update time</th>";
	echo "</tr>";
	
	do{
		
if ($CurrentMemoCred == '')		
$CurrentMemoCred      = $listItem[mcred_memo_num];	

if ($listItem[mcred_memo_num] <> $CurrentMemoCred){
echo "<tr>";						
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "</tr>";
echo "<tr>";						
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "<td align=\"center\">&nbsp;</td>";
echo "</tr>";
$CurrentMemoCred      = $listItem[mcred_memo_num];	
}
	
echo  "<tr>";						
echo "<td align=\"center\">$listItem[mcred_memo_num]</td>";
echo "<td align=\"center\">$listItem[request_status]</td>";
echo "<td align=\"center\">$listItem[request_status_fr]</td>";
echo "<td align=\"center\">$listItem[update_time]</td>";
echo "</tr>";

	}while ($listItem=mysql_fetch_array($rptResult));
	
	//END WHILE

	echo "</table>";

}else{
	echo "<div class=\"formField\">".$adm_noorders_txt."</div>";
}//END USERCOUNT CONDITIONAL
?>
</td>
	  </tr>
</table>	
  <p>&nbsp;</p>
</body>
</html>