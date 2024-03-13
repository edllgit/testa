<?php require_once('../Connections/directlens.php'); ?>
<?php header('Content-type: text/html; charset=UTF-8');?>
<?php echo "<div class=\"bigwelcome\">Step 2</div>"; ?>	
<?php
if (isset($_POST["updater"])){
	$mylangtable = $_POST["mylangtable"];
	mysql_select_db($database_directlens, $directlens);
	$mysqlstr = "UPDATE ".$mylangtable." set languagetext ='".mysql_real_escape_string($_POST["mylangtext"])."' WHERE id =".$_POST["updater"];
	mysql_query("SET CHARACTER SET UTF8"); 
	$result = mysql_query($mysqlstr) or die(mysql_error());
	echo "<div id='headline' class=\"bigtagline\" style=\"text-align:left;\">Editing the language: ".$_POST["mylang"].".<br></div>";
} else {
		if($_POST["rsfunc"] == "addlang"){
			if(copy_table('lang_english', 'lang_'.$_POST["mylang"])) {
				echo "<div id='headline' class=\"bigtagline\">The language ".$_POST["mylang"]." was successfully added to the database.<br>";
				echo "Please fill out the form below for the translation.</div>";
    			$insertSQL = "INSERT INTO languages (languagename, mysql_table) VALUES ('".$_POST["mylang"]."','lang_".$_POST["mylang"]."')";
  				mysql_select_db($database_directlens, $directlens);
  				$Result1 = mysql_query($insertSQL, $directlens) or die(mysql_error());
			} 
		} else {
			echo "<div id='headline' class=\"bigtagline\" style=\"text-align:left;\">Editing the language: ".$_POST["mylang"].".<br></div>";		
		}
}
?>
<?php 
function copy_table($from, $to) {

    if(table_exists($to)) {
        $success = false;
    }
    else {
        mysql_query("CREATE TABLE $to LIKE $from");
        mysql_query("INSERT INTO $to SELECT * FROM $from");
        $success = true;
    }
   
    return $success;
   
}

function table_exists($tablename, $database = false) {

    if(!$database) {
        $res = mysql_query("SELECT DATABASE()");
        $database = mysql_result($res, 0);
    }
   
    $res = mysql_query("
        SELECT COUNT(*) AS count
        FROM information_schema.tables
        WHERE table_schema = '$database'
        AND table_name = '$tablename'
    ");
   
    return mysql_result($res, 0) == 1;
}
?>
<?php
if (isset($_GET["mylang"])){
	$mychosenlanguage = $_GET['mylang'];
	}else{
	$mychosenlanguage = $_POST['mylang'];
		}

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
mysql_query("SET CHARACTER SET UTF8"); 
$query_mylanguage = "SELECT * FROM lang_".$mychosenlanguage;
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
$queryString_mylanguage = sprintf("&totalRows_mylanguage=%d%s&mylang=".$mychosenlanguage, $totalRows_mylanguage, $queryString_mylanguage);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="../dl_pt.css" rel="stylesheet" type="text/css" />
<script>
function saving(){
document.getElementById("headline").innerHTML = "<span style=\"color:red;\">Saving Language to Database...Please Wait.</span>"
return true;	
}
</script>
</head>

<body style="background:#e1e1e1; font-family: Arial, Helvetica, sans-serif; font-size: 24px;">
<p><br />
<a href="step1.php"><span style="font-size: 14px">Return to Menu</span></a></p>
<table border="0">
  <tr>
    <td><?php if ($pageNum_mylanguage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, 0, $queryString_mylanguage); ?>">First 50</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mylanguage > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, max(0, $pageNum_mylanguage - 1), $queryString_mylanguage); ?>">Previous 50</a>
        <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_mylanguage < $totalPages_mylanguage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, min($totalPages_mylanguage, $pageNum_mylanguage + 1), $queryString_mylanguage); ?>">Next 50</a>
        <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_mylanguage < $totalPages_mylanguage) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_mylanguage=%d%s", $currentPage, $totalPages_mylanguage, $queryString_mylanguage); ?>">Last 50</a>
        <?php } // Show if not last page ?></td>
  </tr>
</table>
<table border="1">
  <?php do { ?>
    <tr class="bigtagline">
      <td bgcolor="#CCCCCC">ENGLISH TRANSLATION</td>
      <td bgcolor="#999999">ENTER NEW TRANSLATION</td>
      <td bgcolor="#999999">&nbsp;</td>
    </tr>
    <form action="<?php echo 'step2.php?mylang='.$mychosenlanguage ?>" method="POST" name="<?php echo 'form_'.$row_mylanguage['progkey']; ?>" id="<?php echo 'form_'.$row_mylanguage['progkey']; ?>" onSubmit="saving();">
      <tr>
        <td bgcolor="#CCCCCC" class="bodycopy"><div style="width:300px; text-align: center;">
		<?php 
		$query_englishlang = "SELECT * FROM lang_english where id ='".$row_mylanguage['id']."'";
		$englishlang = mysql_query($query_englishlang, $directlens) or die(mysql_error());
		$row_englishlang = mysql_fetch_assoc($englishlang);
		echo $row_englishlang['languagetext']; ?>
        </div></td>
        <td bgcolor="#999999"><div style="text-align:center;">
            <input type="hidden" name="mylangtable" id="mylangtable" value="<?php if (isset($_GET['mylang'])){echo 'lang_'.$_GET['mylang'];}else{echo 'lang_'.$_POST['mylang'];}?>" />
            <input type="hidden" name="updater" id="updater" value="<?php echo $row_mylanguage['id']; ?>" />
            <input type="hidden" name="mylang" id="mylang" value="<?php if (isset($_GET["mylang"])){echo 'lang_'.$_GET["mylang"];}else{echo 'lang_'.$_POST["mylang"];}?>" />
            <textarea name="mylangtext" cols="30" rows="5" id="mylangtext"><?php echo $row_mylanguage['languagetext']; ?>
            </textarea>
        </div></td>
        <td bgcolor="#999999"><input type="submit" name="button" id="button" value="<- Update Entry" />
        <span style="text-align:center;font-size:9px;"><?php echo "(".$row_mylanguage['id'].")";?></span></td>
      </tr>
    </form>
    <?php $row_englishlang = mysql_fetch_assoc($englishlang); ?>
    <?php } while ($row_mylanguage = mysql_fetch_assoc($mylanguage)); ?>
  <tr class="bodycopy">
    <td><img src="../images/spacer.gif" width="300" height="1" /></td>
    <td><img src="../images/spacer.gif" width="300" height="1" /></td>
    <td><img src="../images/spacer.gif" width="150" height="1" /></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($mylanguage);

mysql_free_result($englishlang);
?>
