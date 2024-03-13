<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once('../Connections/directlens.php');

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE sales_reps SET rep_name=%s, rep_phone=%s, rep_email=%s, rep_manager=%s, rep_password=%s WHERE id=%s",
                       GetSQLValueString($_POST['myname'], "text"),
                       GetSQLValueString($_POST['myphone'], "text"),
                       GetSQLValueString($_POST['myemail'], "text"),
                       GetSQLValueString($_POST['mymanager'], "text"),
                       GetSQLValueString($_POST['mypassword'], "text"),
                       GetSQLValueString($_POST['myid'], "int"));

  mysql_select_db($database_directlens, $directlens);
  $Result1 = mysql_query($updateSQL, $directlens) or die(mysql_error());

  $updateGoTo = "man-reps.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

if ((isset($_POST['mydelid'])) && ($_POST['mydelid'] != "")) {
  $deleteSQL = sprintf("DELETE FROM sales_reps WHERE id=%s",
                       GetSQLValueString($_POST['mydelid'], "int"));

  mysql_select_db($database_directlens, $directlens);
  $Result1 = mysql_query($deleteSQL, $directlens) or die(mysql_error());

  $deleteGoTo = "man-reps.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
    $deleteGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $deleteGoTo));
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
$totalRows_sales_staff = "-1";
if (isset($_GET['sid'])) {
  $totalRows_sales_staff = $_GET['sid'];
}
$colname_sales_staff = "-1";
if (isset($_GET['sid'])) {
  $colname_sales_staff = $_GET['sid'];
}
mysql_select_db($database_directlens, $directlens);
$query_sales_staff = sprintf("SELECT * FROM sales_reps WHERE id = %s", GetSQLValueString($colname_sales_staff, "int"));
$sales_staff = mysql_query($query_sales_staff, $directlens) or die(mysql_error());
$row_sales_staff = mysql_fetch_assoc($sales_staff);
$totalRows_sales_staff = mysql_num_rows($sales_staff);$colname_sales_staff = "-1";

$colname_Recordset1 = "-1";
if (isset($_COOKIE['man_num'])) {
  $colname_Recordset1 = $_COOKIE['man_num'];
}
mysql_select_db($database_directlens, $directlens);
$query_Recordset1 = sprintf("SELECT * FROM sales_managers WHERE man_id = %s", GetSQLValueString($colname_Recordset1, "int"));
$Recordset1 = mysql_query($query_Recordset1, $directlens) or die(mysql_error());
$row_Recordset1 = mysql_fetch_assoc($Recordset1);
$totalRows_Recordset1 = mysql_num_rows($Recordset1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens - Prescription Search</title>
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
<script language="javascript">
function checkdelete(){
	if (confirm("Are you sure you wish to delete this rep?")){
		return true;	
	} else {
		return false;
	}
}

</script>
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
    <td width="685" valign="top">
      <div id="" class="header">Edit a  Sales Representative</div>
      <div style="height:600px;overflow-y:scroll;overflow-x:hidden;padding:10px;border:1px solid #000000;background-color:#ffffff">
      <table width="600" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <td colspan="2"><form id="form2" name="form2" method="post" action="" onsubmit="return checkdelete();">
            <input type="submit" name="button2" id="button2" value="Delete this Representative" /><input name="mydelid" type="hidden" id="mydelid" value="<?php echo $_GET["sid"];?>" />
          </form></td></tr>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
          <tr>
            <td style="text-align:right;"><span class="listheader">Representative Name:</span></td>
            <td><input name="myname" type="text" id="myname" value="<?php echo $row_sales_staff['rep_name']; ?>" />
              <input name="myid" type="hidden" id="myid" value="<?php echo $_GET["sid"];?>" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Representative Phone:</span></td>
            <td><input name="myphone" type="text" id="myphone" value="<?php echo $row_sales_staff['rep_phone']; ?>" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Representative email:</span></td>
            <td><input name="myemail" type="text" id="myemail" value="<?php echo $row_sales_staff['rep_email']; ?>" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Manager:</span></td>
            <td><span class="listheader"><?php echo $row_Recordset1['man_name']; ?></span>
              <input type="hidden" name="mymanager" id="mymanager" value="<?php echo $_COOKIE['man_num'];?>" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Representative Password:</span></td>
            <td><input name="mypassword" type="text" id="mypassword" value="<?php echo $row_sales_staff['rep_password']; ?>" /></td>
            </tr>
          <tr>
            <td colspan="2" style="text-align:center;">
                <input type="submit" name="button" id="button" value="Submit Changes" />
             </td>
            </tr>
          <tr>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="150" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="140" height="1" /></td>
            </tr>      <input type="hidden" name="MM_insert" value="form1" />
      <input type="hidden" name="MM_update" value="form1" />
      </form>
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

mysql_free_result($Recordset1);
?>
