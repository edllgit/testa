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

mysql_select_db($database_directlens, $directlens);
$query_languages = "SELECT * FROM languages";
$languages = mysql_query($query_languages, $directlens) or die(mysql_error());
$row_languages = mysql_fetch_assoc($languages);
$totalRows_languages = mysql_num_rows($languages);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
<link href="../dl_pt.css" rel="stylesheet" type="text/css" />
<script language="javascript">
function checkme(){
	
	if (document.getElementById("mydellang").value != "English"){
		var msg = "Are you sure you want to delete the " + document.getElementById("mydellang").value + " language? Deleting is permanent!";
		var myanswer = confirm(msg);
		if (myanswer== true){
			return true;
 		}else{
			return false;
	  	}
	}else{
		alert("You cannot delete the English language")
		return false;
	}
}



</script>
</head>

<body style="text-align: center; font-family: Arial, Helvetica, sans-serif;">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p style="text-align: center">&nbsp;</p>

<p style="text-align: center">&nbsp;</p>
<div style="width:500px;margin:30px auto">
  <table width="500" border="0" cellpadding="5">
    <tr>
      <td bgcolor="#666666" style="text-align: center; color: #FFF;">Add a Language</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC" style="text-align: center"><form id="form1" name="form1" method="post" action="step2.php">
        <p><span class="bigtagline">Name of Language To Add:</span>
          <input type="text" name="mylang" id="mylang" />
          <input type="hidden" name="rsfunc" id="rsfunc" value="addlang" />
          </p>
        <p>    (Only use letters &amp; no spaces)
          </p>
        <p><span style="text-align: center">
          <input type="submit" name="button" id="button" value="Add Language to Database" />
  </span></p>
  </form></td>
    </tr>
    <tr>
      <td bgcolor="#666666" style="text-align: center"><span style="text-align: center; color: #FFF;">Edit a Language</span></td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC" style="text-align: center"><form id="form2" name="form2" method="get" action="step2.php">
  <input type="hidden" name="editme" id="editme" value="editme"/>
        <p><span class="bigtagline">Name of Language To Edit:</span>
          <select name="mylang" id="mylang">
            <?php
do {  
?>
            <option value="<?php echo $row_languages['languagename']?>"><?php echo $row_languages['languagename']?></option>
            <?php
} while ($row_languages = mysql_fetch_assoc($languages));
  $rows = mysql_num_rows($languages);
  if($rows > 0) {
      mysql_data_seek($languages, 0);
	  $row_languages = mysql_fetch_assoc($languages);
  }
?>
            </select>
          </p>
        <p><span style="text-align: center">
          <input type="submit" name="button2" id="button2" value="Edit Language" />
          </span></p>
  </form></td>
    </tr>
    <tr>
      <td bgcolor="#666666" style="text-align: center; color: #FFF;">Delete a Language</td>
    </tr>
    <tr>
      <td bgcolor="#CCCCCC" style="text-align: center">
      <div id="deletediv">
      <form id="form3" name="form3" method="post" action="deletelang.php" onSubmit="return checkme()">
  		<input type="hidden" name="deleteme" id="deleteme" value="deleteme"/>
        <p><span class="bigtagline">Name of Language To Delete:</span>
          <select name="mydellang" id="mydellang">
            <?php
do {  
?>
            <option value="<?php echo $row_languages['languagename']?>"><?php echo $row_languages['languagename']?></option>
            <?php
} while ($row_languages = mysql_fetch_assoc($languages));
  $rows = mysql_num_rows($languages);
  if($rows > 0) {
      mysql_data_seek($languages, 0);
	  $row_languages = mysql_fetch_assoc($languages);
  }
?>
            </select>
          </p>
        <p><span style="text-align: center">
          <input type="submit" name="button2" id="button2" value="Delete Language" />
          </span></p>
  </form></div></td>
    </tr>
  </table>
</div>
<p style="text-align: center">&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($languages);
?>
