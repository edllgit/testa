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
	
  $logoutGoTo = "../index.php";
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

$colname_orders = "-1";
if (isset($_SESSION['MM_Username'])) {
  $colname_orders = $_SESSION['MM_Username'];
}
mysql_select_db($database_directlens, $directlens);
$query_orders = sprintf("SELECT * FROM orders LEFT JOIN labs on (orders.lab = labs.primary_key) WHERE salesperson_id = %s", GetSQLValueString($colname_orders, "text"));
$orders = mysql_query($query_orders, $directlens) or die(mysql_error());
$row_orders = mysql_fetch_assoc($orders);
$totalRows_orders = mysql_num_rows($orders);
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
     <p class="Subheader"><a href="sales-mysettings.php">My Settings</a></p>
     <p class="Subheader"><a href="sales-report.php">My Orders</a></p>
     <p class="Subheader"><a href="<?php echo $logoutAction ?>">Log Out</a></p>
   </div>
   </td>
    <td width="685" valign="top">
      <div id="" class="header">Orders on Record - <?php echo $row_orders['salesperson_id']; ?></div>
      <div style="height:600px;overflow-y:scroll;overflow-x:hidden;padding:10px;border:1px solid #000000;background-color:#ffffff">
      <?php $mycolor = 1; ?>
        <?php do { ?>
        <?php if($mycolor == 1){ $thecolor = "#e1e1e1"; $mycolor = 2;}else{$thecolor = "#ffffff"; $mycolor = 1;} ?>
          <div style="padding:5px;border:0px solid #000000;<?php echo "background-color:".$thecolor?>">
          <div style="width:340px;float:left;"><span style="margin-top:3px;width:220px;"><span class="listheader">Order Number: </span><span class="listvalue"><?php echo $row_orders['order_num']; ?></span><span class="listheader"><br />
Order Quantity:</span> <span class="listvalue"><?php echo $row_orders['order_quantity']; ?></span><span style="margin-top:3px;width:210px;"><span class="listheader"><br />
Order Status: <span class="listvalue"><?php echo $row_orders['order_status']; ?></span></span><br />
<span class="listheader">Order Date Processed: </span><span class="listvalue"><?php echo $row_orders['order_date_processed']; ?></span></span></span><br />
<span style="margin-top:3px;width:210px;"><span class="listheader">Order Date Shipped: </span><span class="listvalue"><?php echo $row_orders['order_date_shipped']; ?></span></span>
<div style="margin-top:3px;width:220px;"></div>
          </div>
            <div style="width:340px;float:right;"><span style="margin-top:3px;width:320px;"><span class="listheader">Patient  Name:</span> <span class="listvalue"><?php echo $row_orders['order_patient_last']; ?>,</span> <span class="listvalue"><?php echo $row_orders['order_patient_first']; ?></span><span style="margin-top:3px;width:260px;"><span class="listheader"><br />
              Patient Ref Num: <span class="listvalue"><?php echo $row_orders['patient_ref_num']; ?></span></span><br />
              <span class="listheader">Laboratory:</span> <span class="listvalue"><?php echo $row_orders['lab_name']; ?></span>            </span></span><br />
              <span style="margin-top:3px;width:260px;"><span class="listheader">Phone:</span> <span class="listvalue"><?php echo $row_orders['phone']; ?></span></span> <br />
            </div><br clear="all" />
            
          </div>
          <?php } while ($row_orders = mysql_fetch_assoc($orders)); ?>
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
mysql_free_result($orders);
?>
