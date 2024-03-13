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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO sales_managers (man_name, man_phone, man_email, man_lab, man_password) VALUES (%s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['myname'], "text"),
                       GetSQLValueString($_POST['myphone'], "text"),
                       GetSQLValueString($_POST['myemail'], "text"),
                       GetSQLValueString($_POST['mylab'], "text"),
                       GetSQLValueString($_POST['mypassword'], "text"));

  mysql_select_db($database_directlens, $directlens);
  $Result1 = mysql_query($insertSQL, $directlens) or die(mysql_error());

  $insertGoTo = "../labadmin/adminHome.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
  if (isset($_GET['lid'])) {
mysql_select_db($database_directlens, $directlens);
$query_labs = "SELECT * FROM labs WHERE primary_key = '".$_GET['lid']."'";
  } else {
	  header("location:index.php");
  }
$labs = mysql_query($query_labs, $directlens) or die(mysql_error());
$row_labs = mysql_fetch_assoc($labs);
$totalRows_labs = mysql_num_rows($labs);
$_SESSION["mylabid"] = $row_labs["primary_key"];
$_SESSION["mylabname"] = $row_labs["lab_name"];
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
     <p class="Subheader"><a href="index.php">Cancel</a></p>
</div>
   </td>
    <td width="685" valign="top">
      <div id="" class="header">Add a New Manager</div>
      <div style="height:600px;overflow-y:scroll;overflow-x:hidden;padding:10px;border:1px solid #000000;background-color:#ffffff">
      <div style="font-family:Arial, Helvetica, sans-serif;font-size:10px;line-height:16px;text-align:center;"><span style="width:300px"><?php echo $row_labs['lab_name']; ?></span></div>
      <form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
      <table width="600" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <td style="text-align:right;"><span class="listheader">Manager Name:</span></td>
            <td><input type="text" name="myname" id="myname" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Manager Phone:</span></td>
            <td><input type="text" name="myphone" id="myphone" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Manager email:</span></td>
            <td><input type="text" name="myemail" id="myemail" /></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Laboratory:</span></td>
            <td><span class="listheader"><?php echo $row_labs['lab_name']; ?><input type="hidden" name="mylab" value="<?php echo $row_labs['primary_key']; ?>" /></span></td>
            </tr>
          <tr>
            <td style="text-align:right;"><span class="listheader">Manager Password:</span></td>
            <td><input type="text" name="mypassword" id="mypassword" /></td>
            </tr>
          <tr>
            <td colspan="2" style="text-align:center;">
                <input type="submit" name="button" id="button" value="Submit" />
             </td>
            </tr>
          <tr>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="150" height="1" /></td>
            <td><img src="http://www.direct-lens.com/direct-lens/design_images/spacer.gif" width="140" height="1" /></td>
            </tr>
      </table>
      <input type="hidden" name="MM_insert" value="form1" />
      </form>
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
mysql_free_result($labs);
?>
