<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
require_once('../Connections/directlens.php'); 

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
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['myun'])) {
  $loginUsername=$_POST['myun'];
  $password=$_POST['mypw'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "man-reps.php";
  $MM_redirectLoginFailed = "man-login.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_directlens, $directlens);
  
  $LoginRS__query=sprintf("SELECT * FROM sales_managers left join labs on (labs.primary_key = sales_managers.man_lab) WHERE man_email=%s AND man_password=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $directlens) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    $row_manager = mysql_fetch_assoc($LoginRS);
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
	setcookie("man_num",$row_manager["man_id"], time()+3600*24);
	setcookie("lab_num",$row_manager["lab_num"], time()+3600*24);
	setcookie("lab_name",$row_manager["lab_name"], time()+3600*24);
	
    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess);
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
}
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
   <td width="215" valign="top">&nbsp;</td>
    <td width="685" valign="top"><form id="form1" name="form1" method="POST" action="<?php echo $loginFormAction; ?>">
      <div style="padding-top:80px; width:400px; margin-left:30px; text-align: center;">
        <p><span class="tableSubHead">Manager email:
          </span>
          <input type="text" name="myun" id="myun" />
        </p>
        <p><span class="tableSubHead">Password:</span>
          <input type="text" name="mypw" id="mypw" />
        </p>
        <p>
          <input type="submit" name="button" id="button" value="Login" />
        </p>
        <p>&nbsp;</p>
      </div>
    </form></td>
  </tr>
</table> </td>
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
