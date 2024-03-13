<?php require_once('../Connections/directlens.php'); ?>
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

if (isset($_POST['myname'])) {
  $loginUsername=$_POST['myname'];
  $password=$_POST['mypassword'];
  $MM_fldUserAuthorization = "";
  $MM_redirectLoginSuccess = "step1.php";
  $MM_redirectLoginFailed = "index.php";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_directlens, $directlens);
  
  $LoginRS__query=sprintf("SELECT translatorname, translatorpwd FROM languages_admin WHERE translatorname=%s AND translatorpwd=%s",
    GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $directlens) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
     $loginStrGroup = "";
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
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
<title>Untitled Document</title>
<link href="../dl_pt.css" rel="stylesheet" type="text/css" />
</head>

<body>
<form action="<?php echo $loginFormAction; ?>" method="POST" enctype="application/x-www-form-urlencoded" name="form1" id="form1">        <p>&nbsp;</p>
    <p>&nbsp;</p>     
<div style="width:650px;border:1px solid #000; margin:0 125px;">
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
            <tr bgcolor="#A8B50A">
              <td colspan="2" bgcolor="#1C396B" class="bodycopy"><div align="center" class="style6" style="font-size:16px;height:80px">
                <p>&nbsp;</p>
                <h1><span class="bodycopy" style="text-align:left;"><span class="revheader">ADMIN LOGIN</span><span><strong><em></em></strong></span></span></h1>
                </div></td>
              </tr>
            <tr>
              <td colspan="2" bgcolor="#FFFFFF" class="bodycopy"><div style="width:45%;margin:0 25%;padding:10px;text-align:center">
                <p>Username: 
                  <input type="text" name="myname" id="myname" />
                </p>
                <p>Password: 
                  <input type="password" name="mypassword" id="mypassword" />
                </p>
              </div></td>
              </tr>
            <tr>
              <td colspan="2" bgcolor="#1C396B" class="bodycopy"><div align="center" style="height:30px;">
                <p class="bodycopy"><span class="frameless"><img src="images/spacer.gif" width="600" height="1" />
                  <input type="submit" name="button" id="button" value="Submit" />
                </span></p>
                </div></td>
            </tr>
            </table>
  </div>
    <br clear="all" />
</form>
</body>
</html>