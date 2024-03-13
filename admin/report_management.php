<?php
session_start();

include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../Connections/directlens.php";
include "../includes/getlang.php";


 if ($mylang == 'lang_french'){
		$heading="Qui manage quoi";
 }else {
		$heading="Management people Report";	
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
<form  method="post" name="goto_date" id="goto_date" action="report_management.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">
					<?php if ($mylang == 'lang_french'){
					echo 'Rapport sur qui manage quoi' ;
					}else {
					echo 'Management people Report' ;
					}
					?> 
					
					</font></b></td>
            		</tr>
				

				
</form>
<?php 

if ($_POST[date_from] != "All" && $_POST[date_to] != "All" && $_POST[date_to] != "" ){//select between these dates

}
print "<table width=\"50%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\">";
print '<tr><td>&nbsp;</td></tr><tr><td align="center">'. $heading . '</td><td>&nbsp;</td></tr>';
print '<tr><td>&nbsp;</td></tr>';

$queryLabs = "SELECT labs.* from labs where primary_key not in (8,10,11,12,15,19,23,24,25,26,30,35) ORDER BY lab_name";
$rptLabs=mysql_query($queryLabs) 		or die  ('I cannot select items because: ' . mysql_error());

while ($listLab=mysql_fetch_array($rptLabs))
{
	if ($listLab['manage_credit'] <> ''){
	$queryCredit = "Select first_name, last_name from employes WHERE id_employe = " . $listLab['manage_credit'];
	$rptCredit=mysql_query($queryCredit) 		or die  ('I cannot select items because: ' . mysql_error());
	$DataCredit=mysql_fetch_array($rptCredit);
	}else{
	$DataCredit['first_name'] = '';
	$DataCredit['last_name'] = '';
	}
	
	if ($listLab['manage_statement'] <> ''){
	$queryStatement = "Select first_name, last_name from employes WHERE id_employe = " . $listLab['manage_statement'];
	$rptStatement=mysql_query($queryStatement) 		or die  ('I cannot select items because: ' . mysql_error());
	$DataStatement=mysql_fetch_array($rptStatement);
	}else{
	$DataStatement['first_name'] = '';
	$DataStatement['last_name'] = '';
	}
	
	if ($listLab['customer_service'] <> ''){
	$queryService = "Select first_name, last_name from employes WHERE id_employe = " . $listLab['customer_service'];
	$rptService=mysql_query($queryService) 		or die  ('I cannot select items because: ' . mysql_error());
	$DataService=mysql_fetch_array($rptService);
	}else{
	$DataService['first_name'] = '';
	$DataService['last_name'] = '';
	}
	
	print "<tr ><td>";
	print  '<b>'. $listLab['lab_name']  . '</b><br>
	Manage Credit: ' 					.   $DataCredit['first_name']    . ' ' . $DataCredit['last_name']     .
	'<br>Send statement to customers: ' .   $DataStatement['first_name'] . ' ' . $DataStatement['last_name']  .
	'<br>Offers customer service: '     .   $DataService['first_name']   . ' ' . $DataService['last_name']    . ' </td>';
	print "</tr>";
	echo '<tr><td> &nbsp;</td></tr>';
	echo '<tr><td> &nbsp;</td></tr>';
		
}//End while listAccount


 	 	 

print "</table>";
?>
</td>
	  </tr>
</table><br><br></table>
<p>&nbsp;</p>
</body>
</html>
