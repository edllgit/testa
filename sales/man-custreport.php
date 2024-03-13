<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once('../Connections/directlens.php');
require_once('../Connections/sec_connect.inc.php');
include '../sales/salesmath.php';

	if(!isset($_POST["date3"])){
		$mysqldate1 = date('Y')."-".(date('m')-3)."-".date('d');
		$date1 = date('Y')."-".(date('m')-3)."-".date('d');
		$mysqldate2 = date('Y')."-".date('m')."-".date('d');
		$date2 = date('Y')."-".date('m')."-".date('d');
	} else{
		$mysqldate1 = date('Y-m-d',strtotime($_POST["date3"]));
		$date1 = date('Y',strtotime($_POST["date3"]))."-".date('m',strtotime($_POST["date3"]))."-".date('d',strtotime($_POST["date3"]));
		$mysqldate2 = date('Y-m-d',strtotime($_POST["date4"]));
		$date2 = date('Y',strtotime($_POST["date4"]))."-".date('m',strtotime($_POST["date4"]))."-".date('d',strtotime($_POST["date4"]));
	}
?>

<?php
//initialize the session
if (!isset($_SESSION)) {
  session_start();
}

// ** Logout the current user. **
$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
  $logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
  //to fully log out a visitor we need to clear the session varialbles
  $_SESSION['MM_Username'] = NULL;
  $_SESSION['MM_UserGroup'] = NULL;
  $_SESSION['PrevUrl'] = NULL;
  unset($_SESSION['MM_Username']);
  unset($_SESSION['MM_UserGroup']);
  unset($_SESSION['PrevUrl']);
	
  $logoutGoTo = "index.php";
  if ($logoutGoTo) {
    header("Location: $logoutGoTo");
    exit;
  }
}
?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "man-login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_orders = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_orders = $_SESSION['MM_Username'];
}

$colname_sales_staff = "-1";
if (isset($_COOKIE['man_num'])) {
  $colname_sales_staff = $_COOKIE['man_num'];
}
mysql_select_db($database_directlens, $directlens);
$query_sales_staff = sprintf("SELECT * FROM sales_reps WHERE rep_manager = %s", GetSQLValueString($colname_sales_staff, "text"));
$sales_staff = mysql_query($query_sales_staff, $directlens) or die(mysql_error());
$row_sales_staff = mysql_fetch_assoc($sales_staff);
$totalRows_sales_staff = mysql_num_rows($sales_staff);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens - Sales</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.listheader{
font-family:Arial, Helvetica, sans-serif;
font-weight:bold;
font-size:10px;	
}
.listvalue{
font-family:Arial, Helvetica, sans-serif;
font-weight:normal;
font-size:10px;	
}

-->
</style>
<link href="../dl.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td width="135" valign="top">
   <div style="padding:20px 0 0 20px">
     <p class="Subheader"><a href="man-mysettings.php">My Settings</a></p>
     <p class="Subheader"><a href="man-reps.php">Sales Staff</a></p>
     <p class="Subheader"><a href="../labadmin/fullaccountlist.php">Customers</a></p>
     <p class="Subheader"><a href="<?php echo $logoutAction ?>"></a><a href="<?php echo $logoutAction ?>">Log Out</a></p>
</div>
   </td>
    <td valign="top">
      <div id="" class="header" style="padding-top:10px;height:25px">
        <div style="width:460px;float:right;"><form id="form1" name="form1" method="post" action="#"><div style="margin-top:5px;width:460px;font-family: Arial, Helvetica, sans-serif; font-size: 12px;">DATE RANGE:
      		<script language="javascript" src="../calendar/calendar.js"></script>
      		
       <?php
		require_once('../calendar/classes/tc_calendar.php');
	
		$mydate1 = date("l, F j, Y", strtotime($date1));
		$mydate2 = date("l, F j, Y", strtotime($date2));
	  $myCalendar = new tc_calendar("date3", true, false);
	  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($date1))
            , date('m', strtotime($date1))
            , date('Y', strtotime($date1)));
	  $myCalendar->setPath("../calendar/");
	  $myCalendar->setYearInterval(2000, 2020);
	  $myCalendar->writeScript();	  
	  echo "&nbsp;&nbsp;&nbsp;TO:&nbsp;&nbsp;&nbsp;";
	  $myCalendar = new tc_calendar("date4", true, false);
	  $myCalendar->setIcon("../calendar/images/iconCalendar.gif");
	  $myCalendar->setDate(date('d', strtotime($mydate2))
           , date('m', strtotime($date2))
           , date('Y', strtotime($date2)));
	  $myCalendar->setPath("../calendar/");
	  $myCalendar->setYearInterval(2000, 2020);
	  $myCalendar->writeScript();	  
	  ?><input type="submit" name="button" id="button" value="Find Orders" style="width:80px; font-size:10px;"/>
      
      		</div></form>
</div> 
        <div style="width:280px;">Manager Report - Customers</div>

      </div>
      <div style="height:600px;overflow-y:scroll;overflow-x:hidden;padding:10px;border:1px solid #000000;background-color:#ffffff;width:730px">
<div style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:14px;text-align:center;"><span style="width:300px"><?php echo $_COOKIE["lab_name"];?></span></div>
        <table width="600" border="0" cellspacing="0" cellpadding="2">
        <?php do { ?>


<?php        mysql_select_db($database_directlens, $directlens);
$query_customers = "SELECT * FROM accounts WHERE sales_rep = '".$row_sales_staff['id']."' order by accounts.user_id";
$customers = mysql_query($query_customers, $directlens) or die(mysql_error());
$row_customers = mysql_fetch_assoc($customers);
$totalRows_customers = mysql_num_rows($customers);?>
          <tr>
            <td colspan="7" bgcolor="#B4C8F3"><div style="padding:5px; border:1px solid #000000; background-color:#000066; color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 12px;"><div style="width:300px"><?php echo $row_sales_staff['rep_name'];?>
      - <?php echo $row_sales_staff['rep_email']; ?></div>
      <div style="margin-top:2px;color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 10px;">Sales in Date Range: <?php echo "$".money_format('%.2n',getgrandtotalsales($row_sales_staff['id'],$mysqldate1,$mysqldate2));
	
	  ?>&nbsp;&nbsp;&nbsp;&nbsp;Commissions in Date Range: <?php echo "$".money_format('%.2n',getgrandtotalcomms($row_sales_staff['id'],$mysqldate1,$mysqldate2)); ?>
      </div>
      <div style="margin-top:2px;color: #FFF; font-family: Arial, Helvetica, sans-serif; font-size: 10px;">Grand Total Sales: <?php echo "$".money_format('%.2n',allmysales($row_sales_staff['id']));
	
	  ?>&nbsp;&nbsp;&nbsp;&nbsp;Grand Total Commissions: <?php echo "$".money_format('%.2n',allmycomms($row_sales_staff['id'])); ?>
      </div></div></td>
            </tr>
          <tr>
            <td bgcolor="#B4C8F3"><span class="listheader">Customer              <br />
              Acct Num / User ID</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Contact Name:</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Contact email:</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Contact Phone:</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Rate</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Sales<br />
              Total:</span></td>
            <td bgcolor="#B4C8F3"><span class="listheader">Comms<br />
              Total:</span></td>
            </tr>
          <?php $mycolor = 1; ?>
        <?php do { ?>
        <?php if($mycolor == 1){ $thecolor = "#e1e1e1"; $mycolor = 2;}else{$thecolor = "#ffffff"; $mycolor = 1;} ?>

<?php if(getmytotalsales($row_customers['account_num'],$mysqldate1,$mysqldate2) > 0){?>
        <tr bgcolor="<?php echo $thecolor;?>">
            <td>
              <span class="listheader">#<?php echo $row_customers['account_num']; ?>/<br />
              <?php echo $row_customers['user_id']; ?></span></td>
            <td><span class="listheader"><?php echo $row_customers['last_name']; ?>, <?php echo $row_customers['first_name']; ?></span></td>
            <td><div style="width:200px;overflow:hidden;"><span class="listheader"><?php echo $row_customers['email']; ?></span></div></td>
            <td><span class="listheader"><?php echo $row_customers['phone']; ?></span></td>
            <td bgcolor="<?php echo $thecolor;?>">
            <span class="listheader">
			<?php 
				echo $row_customers['sales_commission'];
			?>%
            </span></td>
            <td><span class="listheader">$<?php echo money_format('%.2n',getmytotalsales($row_customers['account_num'],$mysqldate1,$mysqldate2));?></span></td>
            <td><span class="listheader">$<?php echo money_format('%.2n',getmytotalcommissions($row_customers['account_num'],$mysqldate1,$mysqldate2));?></span></td>
            </tr><?php } ?>
        <?php } while ($row_customers = mysql_fetch_assoc($customers)); ?>
		<?php } while ($row_sales_staff = mysql_fetch_assoc($sales_staff)); ?>
          <tr>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="70" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="130" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="210" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="90" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="40" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="75" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="75" height="1" /></td>
            </tr>
      </table>
         
      </div>
      </td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($sales_staff);

mysql_free_result($customers);
?>
