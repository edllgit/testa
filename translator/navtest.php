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

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_mylanguage = 50;
$pageNum_mylanguage = 0;
if (isset($_GET['pageNum_mylanguage'])) {
  $pageNum_mylanguage = $_GET['pageNum_mylanguage'];
}
$startRow_mylanguage = $pageNum_mylanguage * $maxRows_mylanguage;

mysql_select_db($database_directlens, $directlens);
$query_mylanguage = "SELECT * FROM lang_french";
$query_limit_mylanguage = sprintf("%s LIMIT %d, %d", $query_mylanguage, $startRow_mylanguage, $maxRows_mylanguage);
$mylanguage = mysql_query($query_limit_mylanguage, $directlens) or die(mysql_error());
$row_mylanguage = mysql_fetch_assoc($mylanguage);

if (isset($_GET['totalRows_mylanguage'])) {
  $totalRows_mylanguage = $_GET['totalRows_mylanguage'];
} else {
  $all_mylanguage = mysql_query($query_mylanguage);
  $totalRows_mylanguage = mysql_num_rows($all_mylanguage);
}
$totalPages_mylanguage = ceil($totalRows_mylanguage/$maxRows_mylanguage)-1;

$queryString_mylanguage = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_mylanguage") == false && 
        stristr($param, "totalRows_mylanguage") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_mylanguage = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_mylanguage = sprintf("&totalRows_mylanguage=%d%s", $totalRows_mylanguage, $queryString_mylanguage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<p>xx
<table border="0">
  <tr>
    <td><?php if ($pageNum_mylanguage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, 0, $queryString_mylanguage); ?>">First</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mylanguage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, max(0, $pageNum_mylanguage - 1), $queryString_mylanguage); ?>">Previous</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mylanguage < $totalPages_mylanguage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, min($totalPages_mylanguage, $pageNum_mylanguage + 1), $queryString_mylanguage); ?>">Next</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_mylanguage < $totalPages_mylanguage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, $totalPages_mylanguage, $queryString_mylanguage); ?>">Last</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
</p>
<?php do { ?>
  <?php echo $row_mylanguage['progkey']; ?><?php echo $row_mylanguage['languagetext']; ?><br />
  <?php } while ($row_mylanguage = mysql_fetch_assoc($mylanguage)); ?>
</body>
</html>
<?php
mysql_free_result($mylanguage);
?>
