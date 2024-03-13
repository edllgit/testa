<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$user_id   = $_POST[user_id];
$date_from = $_POST[date_from]; 
$date_to   = $_POST[date_to]; 

/*
echo '<br>User id:'   . $user_id ;
echo '<br>date_from:' . $date_from ;
echo '<br>date_to:'   . $date_to ;
*/


$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[stmt_search]=="view customer portrait"){
$rptQuery="SELECT SUM(order_total) as totalPurchases FROM orders WHERE orders.lab='$lab_pkey'  AND orders.order_num != '0'";

	if ($user_id <> '')
	$rptQuery .= " AND orders.user_id = '$user_id'";
	
	
	
	
	$rptQuery .= " AND orders.order_date_shipped between '$date_from' and '$date_to'";
	$heading=$dateInfo . "Statement for";
	$heading=ucwords($heading);
}
if (isset($rptQuery))

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


            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
                <tr bgcolor="#000000">
                    <td align="center" colspan="5"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo 'View credits details'; ?> </font></b></td>
                </tr>
			</table>



<form  method="post" name="goto_date" id="goto_date" action="report_portrait_customer_cred_detail.php" target="_blank">
<?php if (isset($rptQuery))
{
	//SI passe ici, la requete est prete on doit afficher le total des commandes
	
$queryCompany  = "SELECT company FROM accounts WHERE user_id = '$user_id'";
$resultCompany = mysql_query($queryCompany) or die ("Could not find account list" . mysql_error());
$DataCompany   = mysql_fetch_array($resultCompany);

$resultPurchases = mysql_query($rptQuery) or die ("Could not find account list" . mysql_error());
$DataPurchases   = mysql_fetch_array($resultPurchases);
$TotalPurchases  = $DataPurchases[totalPurchases];

$queryCredit    = "SELECT sum(mcred_abs_amount) as TotalCredited FROM memo_credits WHERE mcred_acct_user_id = '$user_id' AND mcred_date BETWEEN  '$date_from' AND '$date_to'";
$resultCredit   = mysql_query($queryCredit) or die ("Could not find account list" . mysql_error());
$DataCredit     = mysql_fetch_array($resultCredit);
$TotalCredited  = $DataCredit[TotalCredited];

$TotalCredited  = money_format('%.2n',$TotalCredited);
$TotalPurchases = money_format('%.2n',$TotalPurchases);	
$pourcentageCreditvsAchat = ($TotalCredited/$TotalPurchases) * 100;
$pourcentageCreditvsAchat = money_format('%.2n',$pourcentageCreditvsAchat);	
?>
    
	<table align="center" width="70%" border="1 px solid black;">
   
    <tr>
        <th width="200px;">Customer:</td><td width="150px;"><?php echo $DataCompany[company] ; ?></th>
    </tr>
    
    <tr>
        <th width="200px;">Total purchases (Between <?php echo $date_from . ' and ' . $date_to   ?>):</th><td width="150px;">&nbsp;<?php  echo $TotalPurchases; ?>$</td>
    </tr>
    
      
    <tr>
        <th width="200px;">Total credited &nbsp;&nbsp;&nbsp;(Between <?php echo $date_from . ' and ' . $date_to   ?>):</th><td width="150px;">&nbsp;<?php  echo $TotalCredited . '$' . ' ('. $pourcentageCreditvsAchat.'% of purchases)'; ?><input name="stmt_search" type="hidden" id="stmt_search" value="view customer portrait" class="formField"></td>
    </tr>
    <input type="hidden" id="user_id"    name="user_id"   value="<?php echo $_POST[acct_user_id] ?>"  >
    <input type="hidden" id="date_from"  name="date_from" value="<?php echo $_POST[date_from] ?>"  >
    <input type="hidden" id="date_to"    name="date_to"   value="<?php echo $_POST[date_to] ?>"  >
	<?php 
} ?>
</table>
<br><br><br>

<table align="center" width="70%" border="1 px solid black;">
    <tr align="center">
        <th align="center" width="50%">Credit Reason</th>
        <th align="center" width="12%">Credit Code</th>
        <th align="center" width="25%">Amount</th>
        <th align="center" width="12%">%</th>
    </tr>
    
<?php
// On va chercher les différentes raisons de crédits utilisés pour ce client durant le range de date sélectionné
$queryCreditDetail    = "SELECT distinct  mcred_memo_code, mc_description  FROM memo_credits, memo_codes WHERE memo_codes.memo_code = memo_credits.mcred_memo_code AND memo_codes.mc_lab = $lab_pkey AND mcred_acct_user_id = '$user_id' AND mcred_date BETWEEN  '$date_from' AND '$date_to' order by mcred_memo_code";
$resultCreditDetail   = mysql_query($queryCreditDetail) or die ("Could not find account list" . mysql_error());
  
while ($DataCreditDetail=mysql_fetch_array($resultCreditDetail)){
	
$queryMemoCodeDetail = "SELECT sum(mcred_abs_amount) as TotalCreditedforthisCode FROM memo_credits WHERE mcred_acct_user_id = '$user_id' AND mcred_date BETWEEN  '$date_from' AND '$date_to' AND mcred_memo_code = '$DataCreditDetail[mcred_memo_code]' ";

$resultMemoCodeDetail   = mysql_query($queryMemoCodeDetail) or die ("Could not find account list" . mysql_error());
$DataMemoCodeDetail     = mysql_fetch_array($resultMemoCodeDetail);
$pourcentage            = ($DataMemoCodeDetail[TotalCreditedforthisCode] / $TotalCredited) * 100;
$pourcentage  		    = money_format('%.2n',$pourcentage);
        echo "<tr>
		<td align=\"center\">$DataCreditDetail[mc_description]</option></td>
		<td align=\"center\">$DataCreditDetail[mcred_memo_code]</option></td>
		<td align=\"center\">$DataMemoCodeDetail[TotalCreditedforthisCode]$</option></td>
		<td align=\"center\">$pourcentage%</option></td></tr>";
}  
?>
</table>





</td>
	  </tr>
</table>
</form>
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