<?php
session_start();

include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
include "../Connections/directlens.php";
include "../includes/getlang.php";

$anneeetmois = date("Y-m");//Donne exemple 2010-05
$jour = date("d");//Donne 18
$datecomplete = $anneeetmois  . '-' . $jour ;

 if ($mylang == 'lang_french'){
		$heading="Rapport détaillé d'utilisation des Mémos crédits";
 }else {
		$heading="Detailled Memo credits Usage report";	
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


<?php

	$query="Update labs set   other_lab='22' where primary_key = '21'";
	$result=mysql_query($query)		or die ("Un probleme est survenu");
	echo' Maintenant redirigé a DRUMMOND';

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
