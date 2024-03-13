<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');

if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$currency="price";}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$currency="price_can";}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$currency="price_eur";}
		
$query="select * from products,prices WHERE type='stock' AND products.product_name=prices.product_name AND prices.".$currency."!=0 group by products.product_name,mfg asc"; /* select all openings */

$result=mysql_query($query)
		or die($lbl_error1_txt . mysql_error());
$usercount=mysql_num_rows($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>

   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
    
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

<script src="formFunctions.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">

function validate(theForm)
{
 if (theForm.product_name.value=="")
  {
    alert(<?php echo $lbl_alert1_bulk;?>);
    theForm.product_name.focus();
    return (false);
  }
}
  </script>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>

</head>

<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">  
<form action="stock_bulk_form.php" method="post" name="stock" id="stock" onSubmit="return validate(this)">
      <div class="header"><?php echo $lbl_titlemast_bulk;?> </div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td align="right" bgcolor="#17A2D2" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysql_fetch_array($result)){echo "<option value=\"$listProducts[product_name]\">";$name=stripslashes($listProducts[product_name]);echo "$name</option>";}?>
		        </select>
		      &nbsp;
		      <input name="Submit" type="submit" class="formText" id="Submit" value="<?php echo $btn_go_txt;?>">
		      &nbsp;
		      <input name="from_bulk_form" type="hidden" id="from_bulk_form" value="true">
		    </div></td>
      </tr></table></form>
                
                </div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>