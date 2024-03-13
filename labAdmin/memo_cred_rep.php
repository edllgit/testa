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
		$heading="Rapport d�taill� d'utilisation des M�mos cr�dits";
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
 if ($mylang == 'lang_french'){
		echo '<br><br><p align="center"><a style="text-decoration:none;" href="reports_memo_cred_details.php">Rapport d�taill� de l\'utilisation des m�mo-cr�dits</a><br><br><a style="text-decoration:none;" href="reports_memo_cred.php">Rapport r�sum� de l\'utilisation des m�mo-cr�dits</a></p>';
 }else {
		echo '<br><br><p align="center"><a style="text-decoration:none;" href="reports_memo_cred_details.php">Detailled report on Memo credit</a><br><br><a style="text-decoration:none;" href="reports_memo_cred.php">Summary of Memo credits usage</a></p>';
 }
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
