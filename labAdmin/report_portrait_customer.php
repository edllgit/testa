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

function firstOfMonth() {
return date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
}

function lastOfMonth() {
return date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))));
}

$today=date("Y-m-d");
$firstDay = firstOfMonth();
$lastDay = lastOfMonth();

if($_GET[reset]=="y"){
	unset($rptQuery);
	unset($_SESSION["RPTQUERY"]);
	unset($heading);
	unset($_SESSION["heading"]);
}

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if($_POST[stmt_search]=="view customer portrait"){
/*$rptQuery="SELECT accounts.user_id as user_id, accounts.account_num, accounts.buying_group, orders.order_num as order_num, orders.po_num, orders.patient_ref_num, orders.order_total, orders.order_date_processed, orders.order_date_shipped, accounts.company, orders.order_status FROM orders
LEFT JOIN (accounts) ON (orders.user_id = accounts.user_id) 
WHERE orders.lab='$lab_pkey' AND orders.order_num != '0'";*/

$rptQuery="SELECT SUM(order_total) as totalPurchases FROM orders WHERE orders.lab='$lab_pkey'  AND orders.order_num != '0'";

//var_dump($_POST);
if ($_POST[acct_user_id] <> '')
$rptQuery .= " AND orders.user_id = '$_POST[acct_user_id]'";

	$date_from = $_POST[date_from];
	$date_to   = $_POST[date_to];
	$rptQuery .= " AND orders.order_date_shipped between '$date_from' and '$date_to'";
	//$rptQuery .= " group by order_num ";
	$heading=$dateInfo . "Statement for";
	$heading=ucwords($heading);
}
if (isset($rptQuery))
//echo '<br>'. $rptQuery;
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

<form  method="post" name="goto_date" id="goto_date" action="report_portrait_customer.php" target="_self">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="5"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$lab_name"; ?> <?php echo 'View sales and credits of a customer'; ?> </font></b></td>
            	</tr>
                
				<tr bgcolor="#DDDDDD">
					<td align="right"><?php echo $adm_datefr_txt; ?>
					</td>
					<td width="60%"><input name="date_from" type="text" class="formField" id="date_from" value="<?php echo $firstDay; ?>" size="11">
							<?php echo $adm_through_txt; ?>&nbsp;&nbsp;&nbsp;<input name="date_to" type="text" class="formField" id="date_to" value="<?php echo $lastDay; ?>" size="11">
					</td>	
				</tr>

	<tr bgcolor="#DDDDDD">
        <td nowrap><div align="center">&nbsp;</div></td>
        <td align="left" nowrap>
        <select name="acct_user_id" id="acct_user_id" class="formField">
        <option value=""><?php echo $adm_selacct_txt; ?></option>
        <?php
        $query="SELECT user_id, company from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company";
        $result=mysql_query($query) or die ("Could not find account list" . mysql_error());
        while ($accountList=mysql_fetch_array($result)){
        echo "<option value=\"$accountList[user_id]\">$accountList[company]</option>";
        }
        ?>
        </select>
        </td>
	</tr>
            
            
				<tr>
					<td colspan="5"><div align="center"><input name="submit" type="submit" id="submit" value="<?php echo 'View Sales/Credits'; ?>" class="formField"><input name="stmt_search" type="hidden" id="stmt_search" value="view customer portrait" class="formField"></div></td>
			   </tr>
			</table>
</form>


<form  method="post" name="goto_date" id="goto_date" action="report_portrait_customer_cred_detail.php" target="_blank">
<?php if (isset($rptQuery))
{
	//SI passe ici, la requete est prete on doit afficher le total des commandes
	
$queryCompany  = "SELECT company FROM accounts WHERE user_id = '$_POST[acct_user_id]'";
$resultCompany = mysql_query($queryCompany) or die ("Could not find account list" . mysql_error());
$DataCompany   = mysql_fetch_array($resultCompany);

$resultPurchases = mysql_query($rptQuery) or die ("Could not find account list" . mysql_error());
$DataPurchases   = mysql_fetch_array($resultPurchases);
$TotalPurchases  = $DataPurchases[totalPurchases];

$date_from = $_POST[date_from];
$date_to   = $_POST[date_to];

$queryCredit    = "SELECT sum(mcred_abs_amount) as TotalCredited FROM memo_credits WHERE mcred_acct_user_id = '$_POST[acct_user_id]' AND mcred_date BETWEEN  '$date_from' AND '$date_to'";
$resultCredit   = mysql_query($queryCredit) or die ("Could not find account list" . mysql_error());
$DataCredit     = mysql_fetch_array($resultCredit);
$TotalCredited  = $DataCredit[TotalCredited];

$TotalCredited  = money_format('%.2n',$TotalCredited);
$TotalPurchases = money_format('%.2n',$TotalPurchases);	
$pourcentageCreditvsAchat = ($TotalCredited/$TotalPurchases) * 100;
$pourcentageCreditvsAchat = money_format('%.2n',$pourcentageCreditvsAchat);	
?>
    
	<table width="60%" border="1 px solid black;">
   
    <tr>
        <th width="200px;">Customer:</td><td width="150px;"><?php echo $DataCompany[company] ; ?></th>
    </tr>
    
    <tr>
        <th width="200px;">Total purchases (Between <?php echo $date_from . ' and ' . $date_to   ?>):</th><td width="150px;">&nbsp;<?php  echo $TotalPurchases; ?>$</td>
    </tr>
    
      
    <tr>
        <th width="200px;">Total credited &nbsp;&nbsp;&nbsp;(Between <?php echo $date_from . ' and ' . $date_to   ?>):</th><td width="150px;">&nbsp;<?php  echo $TotalCredited . '$' . ' ('. $pourcentageCreditvsAchat.'% of purchases)'; ?>&nbsp;&nbsp;<input name="submit" type="submit" id="submit" value="<?php echo 'View Details'; ?>" class="formField"><input name="stmt_search" type="hidden" id="stmt_search" value="view customer portrait" class="formField"></td>
    </tr>
    <input type="hidden" id="user_id"    name="user_id"   value="<?php echo $_POST[acct_user_id] ?>"  >
    <input type="hidden" id="date_from"  name="date_from" value="<?php echo $_POST[date_from] ?>"  >
    <input type="hidden" id="date_to"    name="date_to"   value="<?php echo $_POST[date_to] ?>"  >
	<?php 
} ?>

<table>

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