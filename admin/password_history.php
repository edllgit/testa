<?php
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$anneeetmois = date("Y-m");//Donne exemple 2010-05
$jour = date("d");//Donne 18
$datecomplete = $anneeetmois  . '-' . $jour ;
$heading="Password access history";
	
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
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">LabAdmin Password History</font></b></td>
            		</tr>
			

			</table>
</form>
<?php 

print "<table width=\"80%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
$queryHistory = "SELECT * from password_history order by  dateandtime asc";
$resultHistory=mysql_query($queryHistory)	or die ("Could not create list: " . mysql_error());
echo '<th>Compagnie</th>
<th>Main lab</th>
	<th>Date</th>
	<th>User ID</th>
	<th>Who</th>';
	
	
while ($DataHistory=mysql_fetch_array($resultHistory))
{


$queryCompagnie = "SELECT  labs.lab_name, accounts.company from accounts, labs where labs.primary_key = accounts.main_lab AND user_id='". $DataHistory['user_id'] . "'";
//echo $queryCompagnie . '<br><br>';
$resultCompagnie=mysql_query($queryCompagnie)	or die ("Could not create list: " . mysql_error());
$DataCompagnie=mysql_fetch_array($resultCompagnie);
mysql_free_result($resultCompagnie);
$compagnie = $DataCompagnie['company'];


	echo "<tr align=\"center\">";
	echo "<td align=\"center\">"  .  $compagnie                 . "</td>";
	echo "<td align=\"center\">"  . $DataCompagnie['lab_name']    . "</td>";
	echo "<td align=\"center\">"  . $DataHistory['user_id']     . "</td>";
	echo  "<td align=\"center\">" . $DataHistory['dateandtime'] . "</td>";
	echo "<td align=\"center\">"  . $DataHistory['who']         . "</td>";
	echo "</tr>";
	
}//End while DataHistory
mysql_free_result($resultHistory);
print "</table>";
?>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>