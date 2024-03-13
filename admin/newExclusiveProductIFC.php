<?php
session_start();
if ($_SESSION[adminData][username]==""){
	print "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("../Connections/sec_connect.inc.php");

$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}
?>
<html>
<head>
<title>Direct-Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%"><form action="update_exclusive_productIFC.php" method="post" enctype="multipart/form-data" name="form4">
  		  <table width="100%" border="0" cellpadding="2" cellspacing="0">
            <tr bgcolor="#000000">
              <td colspan="6" align="center"><b><font color="#FFFFFF" size="2" face="Helvetica, sans-serif, Arial">DIRECT-LENS
                    ADMIN UPDATE EXCLUSIVE PRODUCT FORM</font></b></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td width="16%" align="right" nowrap><p> <font size="1" face="Arial, Helvetica, sans-serif">Product Name:</font></p></td>
              <td colspan="3" align="left" nowrap><font size="1">
                <input name="product_name" type="text" class="formText" id="product_make" value="" size="30" />
              </font></td>
              <td align="right" nowrap><font size="1" face="Arial, Helvetica, sans-serif">Exclusive
              Collection :</font></td>
              <td align="left" nowrap> <select name="collection" class="formText" id="collection">
                  <option value="" selected="selected">Select a Collection</option>
                  <?php 
    $query="select collection_name from liste_collection_info ORDER by collection_name asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[collection_name]\">";$name=stripslashes($listItem[collection_name]);echo "$name</option>";}?>
              </select></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Manufacturer:</font></td>
              <td width="38%" colspan="3" align="left" bgcolor="#FFFFFF">
                <select name="manufacturer" class="formText" id="manufacturer">
                  <option value="" selected="selected">Select a Manufacturer</option>
                  <?php 
    $query="select manufacturer from exclusive group by manufacturer asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[manufacturer]\">";$name=stripslashes($listItem[manufacturer]);echo "$name</option>";}?>
              </select>              </td>
              <td width="18%" align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">ABBE:</font></td>
              <td width="28%" align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1">
                <input name="abbe" type="text" class="formText" id="product_model" value="" size="10" />
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Index:</font></td>
              <td colspan="3" align="left" bgcolor="#DDDDDD"><font size="1">
                <input name="index_v" type="text" class="formText" id="product_model" size="10" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Density:</font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
                <input name="density" type="text" class="formText" id="weight" value="" size="10" />
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
               <td align="right" valign="top" nowrap="nowrap" bgcolor="#FFFFFF"><font size="2">Lens category:</font></td>
        <td colspan="3" align="left" nowrap="nowrap" bgcolor="#FFFFFF">


 
<select name="lens_category">
<option  value="sv">Sv</option>
<option  value="glass">Glass</option>
<option   value="bifocal">Bi-focal</option>
<option   value="prog cl">Progressive Classic</option>
<option   value="prog ds">Progressive DS</option>
<option   value="prog ff">Progressive FF</option>
<option   value="">None</option>
</select>
        </td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Product
              Status:</font></td>
              <td align="left" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Active:</font>
                <input name="prod_status" type="checkbox" id="prod_status" value="active" checked></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" valign="top" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Description:</font></td>
              <td  align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1">
                <textarea name="description" cols="50" rows="6" class="formText" id="product_model"></textarea>
              </font></td>
               <td>&nbsp;</td>
                 <td>&nbsp;</td>
          <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Tintable:</font>
            <input name="tintable" type="checkbox" id="tintable" value="yes"/></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td colspan="6" valign="middle" nowrap="nowrap" bgcolor="#FFE5E5"><div align="center">
       
          <font size="1" face="Helvetica, sans-serif, Arial">   <hr />
          <font color="#FF0000"><b>Note:
          When edited, items above line are updated for ALL records with same
          product name. </b></font></font></div></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Product
                  Code:</font></td>
              <td colspan="3" align="left" bgcolor="#FFFFFF"><font size="1">
                <input name="product_code" type="text" class="formText" id="retail_price" value="" size="10" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Color
              Code: </font></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1">
                <input name="color_code" type="text" class="formText" id="retail_price" value="" size="10" />
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Price
              USA:</font></td>
              <td align="left" bgcolor="#DDDDDD"><font size="1">
                $
                  
              <input name="price" type="text" class="formText" id="price" value="" size="6" />
              </font></td>
              <td align="right" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">E-Lab Price
              USA:</font></td>
              <td align="left" bgcolor="#DDDDDD"><font size="1">$
                  <input name="e_lab_us_price" type="text" class="formText" id="e_lab_us_price" value="" size="6" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Coating: </font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="coating" class="formText" id="coating">
                  <option value="" selected="selected">Select a Coating</option>
                  <?php 
    $query="select coating from exclusive group by coating asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
while ($listItem=mysql_fetch_array($result)){echo "<option value=\"$listItem[coating]\">";$name=stripslashes($listItem[coating]);echo "$name</option>";}?>
              </select></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">Price
              Canada:</font></td>
              <td align="left" bgcolor="#FFFFFF"><font size="1">
                $
                  
              <input name="price_can" type="text" class="formText" id="price_ca" value="" size="6" />
              </font></td>
              <td align="right" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">E-Lab Price
                Canada:</font></td>
              <td align="left" bgcolor="#FFFFFF"><font size="1">$
                <input name="e_lab_can_price" type="text" class="formText" id="e_lab_can_price" value="" size="6" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Photochromatic:</font></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><select name="photo" class="formText" id="photo">
                  <option value="None" selected="selected">None</option>
                  <?php 
    $query="select photo from exclusive group by photo asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
while ($listItem=mysql_fetch_array($result)){
if ($listItem[photo]!="None"){
	echo "<option value=\"$listItem[photo]\">";
	$name=stripslashes($listItem[photo]);
	echo "$name</option>";}}?>
              </select></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">Price
                  Euro:</font></td>
              <td colspan="3" align="left" bgcolor="#DDDDDD"><font size="1"> $
                <input name="price_eur" type="text" class="formText" id="retail_price" value="" size="6" />
              </font></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Polarization:</font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><select name="polar" class="formText" id="polar">
                  <option value="None" selected="selected">None</option>
                  <?php 
    $query="select polar from exclusive group by polar asc"; /* select all openings */
$result=mysql_query($query)
		or die ("Could not select items");
$usercount=mysql_num_rows($result);
while ($listItem=mysql_fetch_array($result)){
if ($listItem[polar]!="None"){
	echo "<option value=\"$listItem[polar]\">";
	$name=stripslashes($listItem[polar]);
	echo "$name</option>";}}?>
              </select></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Sphere
                    Max: </font></p></td>
              <td colspan="2" align="left" bgcolor="#FFFFFF"><select name="sphere_max" class="formText" id="sphere_max">
                <option selected>Select Sphere Max</option>
                   <option value="14.00">+14.00</option>
                  <option value="13.75">+13.75</option>
                  <option value="13.50">+13.50</option>
                  <option value="13.25">+13.25</option>
                  <option value="13.00">+13.00</option>
                  <option value="12.75">+12.75</option>
                  <option value="12.50">+12.50</option>
                  <option value="12.25">+12.25</option>
                  <option value="12.00">+12.00</option>
                  <option value="11.75">+11.75</option>
                  <option value="11.50">+11.50</option>
                  <option value="11.25">+11.25</option>
                  <option value="11.00">+11.00</option>
                  <option value="10.75">+10.75</option>
                  <option value="10.50">+10.50</option>
                  <option value="10.25">+10.25</option>
                  <option value="10.00">+10.00</option>
                  <option value="9.75">+9.75</option>
                  <option value="9.50">+9.50</option>
                  <option value="9.25">+9.25</option>
                  <option value="9.00">+9.00</option>
                  <option value="8.75">+8.75</option>
                  <option value="8.50">+8.50</option>
                  <option value="8.25">+8.25</option>
                  <option value="8.00">+8.00</option>
                  <option value="7.75">+7.75</option>
                  <option value="7.50">+7.50</option>
                  <option value="7.25">+7.25</option>
                  <option value="7.00">+7.00</option>
                  <option value="6.75">+6.75</option>
                  <option value="6.50">+6.50</option>
                  <option value="6.25">+6.25</option>
                  <option value="6.00">+6.00</option>
                  <option value="5.75">+5.75</option>
                  <option value="5.50">+5.50</option>
                  <option value="5.25">+5.25</option>
                  <option value="5.00">+5.00</option>
                  <option value="4.75">+4.75</option>
                  <option value="4.50">+4.50</option>
                  <option value="4.25">+4.25</option>
                  <option value="4.00">+4.00</option>
                  <option value="3.75">+3.75</option>
                  <option value="3.50">+3.50</option>
                  <option value="3.25">+3.25</option>
                  <option value="3.00">+3.00</option>
                  <option value="2.75">+2.75</option>
                  <option value="2.50">+2.50</option>
                  <option value="2.25">+2.25</option>
                  <option value="2.00">+2.00</option>
                  <option value="1.75">+1.75</option>
                  <option value="1.50">+1.50</option>
                  <option value="1.25">+1.25</option>
                  <option value="1.00">+1.00</option>
                  <option value="0.75">+0.75</option>
                  <option value="0.50">+0.50</option>
                  <option value="0.25">+0.25</option>
                  <option value="0.00">+0.00</option>
                  <option value="-0.25">-0.25</option>
                  <option value="-0.50">-0.50</option>
                  <option value="-0.75">-0.75</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-1.25">-1.25</option>
                  <option value="-1.50">-1.50</option>
                  <option value="-1.75">-1.75</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-2.25">-2.25</option>
                  <option value="-2.50">-2.50</option>
                  <option value="-2.75">-2.75</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-3.25">-3.25</option>
                  <option value="-3.50">-3.50</option>
                  <option value="-3.75">-3.75</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-4.25">-4.25</option>
                  <option value="-4.50">-4.50</option>
                  <option value="-4.75">-4.75</option>
                  <option value="-5.00">-5.00</option>
                  <option value="-5.25">-5.25</option>
                  <option value="-5.50">-5.50</option>
                  <option value="-5.75">-5.75</option>
                  <option value="-6.00">-6.00</option>
                  <option value="-6.25">-6.25</option>
                  <option value="-6.50">-6.50</option>
                  <option value="-6.75">-6.75</option>
                  <option value="-7.00">-7.00</option>
                  <option value="-7.25">-7.25</option>
                  <option value="-7.50">-7.50</option>
                  <option value="-7.75">-7.75</option>
                  <option value="-8.00">-8.00</option>
                  <option value="-8.25">-8.25</option>
                  <option value="-8.50">-8.50</option>
                  <option value="-8.75">-8.75</option>
                  <option value="-9.00">-9.00</option>
                  <option value="-9.25">-9.25</option>
                  <option value="-9.50">-9.50</option>
                  <option value="-9.75">-9.75</option>
                  <option value="-10.00">-10.00</option>
                  <option value="-10.25">-10.25</option>
                  <option value="-10.50">-10.50</option>
                  <option value="-10.75">-10.75</option>
                  <option value="-11.00">-11.00</option>
                  <option value="-11.25">-11.25</option>
                  <option value="-11.50">-11.50</option>
                  <option value="-11.75">-11.75</option> 
                  <option value="-12.00">-12.00</option>
                  <option value="-12.25">-12.25</option> 
                  <option value="-12.50">-12.50</option> 
                  <option value="-12.75">-12.75</option> 
                  <option value="-13.00">-13.00</option> 
                  <option value="-13.25">-13.25</option>   
                  <option value="-13.50">-13.50</option>  
                   <option value="-13.75">-13.75</option>
                   <option value="-14.00">-14.00</option>
                  <option value="-14.25">-14.25</option>   
                  <option value="-14.50">-14.50</option>  
                   <option value="-14.75">-14.75</option>
                   <option value="-15.00">-15.00</option>
              </select></td>
              
               <td align="left" bgcolor="#FFFFFF"><font size="1" face="Helvetica, sans-serif, Arial">Minimum Height: </font>
                  <input name="min_height" type="text" class="formText" id="min_height" value="0" size="6" />
              </td>
              
              

              
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Sphere
                    Min: </font></p></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">
                <select name="sphere_min" class="formText" id="sphere_min">
                  <option selected>Select Sphere Min</option>
                  <option value="0.00">+0.00</option>
                  <option value="-0.25">-0.25</option>
                  <option value="-0.50">-0.50</option>
                  <option value="-0.75">-0.75</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-1.25">-1.25</option>
                  <option value="-1.50">-1.50</option>
                  <option value="-1.75">-1.75</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-2.25">-2.25</option>
                  <option value="-2.50">-2.50</option>
                  <option value="-2.75">-2.75</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-3.25">-3.25</option>
                  <option value="-3.50">-3.50</option>
                  <option value="-3.75">-3.75</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-4.25">-4.25</option>
                  <option value="-4.50">-4.50</option>
                  <option value="-4.75">-4.75</option>
                  <option value="-5.00">-5.00</option>
                  <option value="-5.25">-5.25</option>
                  <option value="-5.50">-5.50</option>
                  <option value="-5.75">-5.75</option>
                  <option value="-6.00">-6.00</option>
                  <option value="-6.25">-6.25</option>
                  <option value="-6.50">-6.50</option>
                  <option value="-6.75">-6.75</option>
                  <option value="-7.00">-7.00</option>
                  <option value="-7.25">-7.25</option>
                  <option value="-7.50">-7.50</option>
                  <option value="-7.75">-7.75</option>
                  <option value="-8.00">-8.00</option>
                  <option value="-8.25">-8.25</option>
                  <option value="-8.50">-8.50</option>
                  <option value="-8.75">-8.75</option>
                  <option value="-9.00">-9.00</option>
                  <option value="-9.25">-9.25</option>
                  <option value="-9.50">-9.50</option>
                  <option value="-9.75">-9.75</option>
                  <option value="-10.00">-10.00</option>
                  <option value="-10.25">-10.25</option>
                  <option value="-10.50">-10.50</option>
                  <option value="-10.75">-10.75</option>
                  <option value="-11.00">-11.00</option>
                  <option value="-11.25">-11.25</option>
                  <option value="-11.50">-11.50</option>
                  <option value="-11.75">-11.75</option>
                  <option value="-12.00">-12.00</option> 
                  <option value="-12.25">-12.25</option>
                  <option value="-12.50">-12.50</option>
                  <option value="-12.75">-12.75</option>
                  <option value="-13.00">-13.00</option>
                   <option value="-13.25">-13.25</option>
                  <option value="-13.50">-13.50</option>
                  <option value="-13.75">-13.75</option>
                  <option value="-14.00">-14.00</option>
                   <option value="-14.25">-14.25</option>
                  <option value="-14.50">-14.50</option>
                  <option value="-14.75">-14.75</option>
                  <option value="-15.00">-15.00</option>
                </select>
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Sphere
              Overage Max: </font></td>
              <td colspan="2" align="left" bgcolor="#DDDDDD"><select name="sphere_over_max" class="formText" id="sphere_over_max">
                <option selected>Select Sphere Overage Max</option>
                      <option value="14.00">+14.00</option>
                  <option value="13.75">+13.75</option>
                  <option value="13.50">+13.50</option>
                  <option value="13.25">+13.25</option>
                  <option value="13.00">+13.00</option>
                  <option value="12.75">+12.75</option>
                  <option value="12.50">+12.50</option>
                  <option value="12.25">+12.25</option>
                  <option value="12.00">+12.00</option>
                  <option value="11.75">+11.75</option>
                  <option value="11.50">+11.50</option>
                  <option value="11.25">+11.25</option>
                  <option value="11.00">+11.00</option>
                  <option value="10.75">+10.75</option>
                  <option value="10.50">+10.50</option>
                  <option value="10.25">+10.25</option>
                  <option value="10.00">+10.00</option>
                  <option value="9.75">+9.75</option>
                  <option value="9.50">+9.50</option>
                  <option value="9.25">+9.25</option>
                  <option value="9.00">+9.00</option>
                  <option value="8.75">+8.75</option>
                  <option value="8.50">+8.50</option>
                  <option value="8.25">+8.25</option>
                  <option value="8.00">+8.00</option>
                  <option value="7.75">+7.75</option>
                  <option value="7.50">+7.50</option>
                  <option value="7.25">+7.25</option>
                  <option value="7.00">+7.00</option>
                  <option value="6.75">+6.75</option>
                  <option value="6.50">+6.50</option>
                  <option value="6.25">+6.25</option>
                  <option value="6.00">+6.00</option>
                  <option value="5.75">+5.75</option>
                  <option value="5.50">+5.50</option>
                  <option value="5.25">+5.25</option>
                  <option value="5.00">+5.00</option>
                  <option value="4.75">+4.75</option>
                  <option value="4.50">+4.50</option>
                  <option value="4.25">+4.25</option>
                  <option value="4.00">+4.00</option>
                  <option value="3.75">+3.75</option>
                  <option value="3.50">+3.50</option>
                  <option value="3.25">+3.25</option>
                  <option value="3.00">+3.00</option>
                  <option value="2.75">+2.75</option>
                  <option value="2.50">+2.50</option>
                  <option value="2.25">+2.25</option>
                  <option value="2.00">+2.00</option>
                  <option value="1.75">+1.75</option>
                  <option value="1.50">+1.50</option>
                  <option value="1.25">+1.25</option>
                  <option value="1.00">+1.00</option>
                  <option value="0.75">+0.75</option>
                  <option value="0.50">+0.50</option>
                  <option value="0.25">+0.25</option>
                  <option value="0.00">+0.00</option>
                  <option value="-0.25">-0.25</option>
                  <option value="-0.50">-0.50</option>
                  <option value="-0.75">-0.75</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-1.25">-1.25</option>
                  <option value="-1.50">-1.50</option>
                  <option value="-1.75">-1.75</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-2.25">-2.25</option>
                  <option value="-2.50">-2.50</option>
                  <option value="-2.75">-2.75</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-3.25">-3.25</option>
                  <option value="-3.50">-3.50</option>
                  <option value="-3.75">-3.75</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-4.25">-4.25</option>
                  <option value="-4.50">-4.50</option>
                  <option value="-4.75">-4.75</option>
                  <option value="-5.00">-5.00</option>
                  <option value="-5.25">-5.25</option>
                  <option value="-5.50">-5.50</option>
                  <option value="-5.75">-5.75</option>
                  <option value="-6.00">-6.00</option>
                  <option value="-6.25">-6.25</option>
                  <option value="-6.50">-6.50</option>
                  <option value="-6.75">-6.75</option>
                  <option value="-7.00">-7.00</option>
                  <option value="-7.25">-7.25</option>
                  <option value="-7.50">-7.50</option>
                  <option value="-7.75">-7.75</option>
                  <option value="-8.00">-8.00</option>
                  <option value="-8.25">-8.25</option>
                  <option value="-8.50">-8.50</option>
                  <option value="-8.75">-8.75</option>
                  <option value="-9.00">-9.00</option>
                  <option value="-9.25">-9.25</option>
                  <option value="-9.50">-9.50</option>
                  <option value="-9.75">-9.75</option>
                  <option value="-10.00">-10.00</option>
                  <option value="-10.25">-10.25</option>
                  <option value="-10.50">-10.50</option>
                  <option value="-10.75">-10.75</option>
                  <option value="-11.00">-11.00</option>
                  <option value="-11.25">-11.25</option>
                  <option value="-11.50">-11.50</option>
                  <option value="-11.75">-11.75</option> 
                  <option value="-12.00">-12.00</option>
                  <option value="-12.25">-12.25</option> 
                  <option value="-12.50">-12.50</option> 
                  <option value="-12.75">-12.75</option> 
                  <option value="-13.00">-13.00</option> 
                  <option value="-13.25">-13.25</option>   
                  <option value="-13.50">-13.50</option>  
                   <option value="-13.75">-13.75</option>
                   <option value="-14.00">-14.00</option>
                  <option value="-14.25">-14.25</option>   
                  <option value="-14.50">-14.50</option>  
                   <option value="-14.75">-14.75</option>
                   <option value="-15.00">-15.00</option>
              </select></td>
            
            <td align="left" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Maximum Height:</font>
                  <input name="max_height" type="text" class="formText" id="max_height" value="0" size="6" />
              </td>
            
            
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Sphere Overage
              Min: </font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">
                <select name="sphere_over_min" class="formText" id="sphere_over_min">
                  <option selected>Select Sphere Overage Min</option>
              <option value="0.00">+0.00</option>
                  <option value="-0.25">-0.25</option>
                  <option value="-0.50">-0.50</option>
                  <option value="-0.75">-0.75</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-1.25">-1.25</option>
                  <option value="-1.50">-1.50</option>
                  <option value="-1.75">-1.75</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-2.25">-2.25</option>
                  <option value="-2.50">-2.50</option>
                  <option value="-2.75">-2.75</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-3.25">-3.25</option>
                  <option value="-3.50">-3.50</option>
                  <option value="-3.75">-3.75</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-4.25">-4.25</option>
                  <option value="-4.50">-4.50</option>
                  <option value="-4.75">-4.75</option>
                  <option value="-5.00">-5.00</option>
                  <option value="-5.25">-5.25</option>
                  <option value="-5.50">-5.50</option>
                  <option value="-5.75">-5.75</option>
                  <option value="-6.00">-6.00</option>
                  <option value="-6.25">-6.25</option>
                  <option value="-6.50">-6.50</option>
                  <option value="-6.75">-6.75</option>
                  <option value="-7.00">-7.00</option>
                  <option value="-7.25">-7.25</option>
                  <option value="-7.50">-7.50</option>
                  <option value="-7.75">-7.75</option>
                  <option value="-8.00">-8.00</option>
                  <option value="-8.25">-8.25</option>
                  <option value="-8.50">-8.50</option>
                  <option value="-8.75">-8.75</option>
                  <option value="-9.00">-9.00</option>
                  <option value="-9.25">-9.25</option>
                  <option value="-9.50">-9.50</option>
                  <option value="-9.75">-9.75</option>
                  <option value="-10.00">-10.00</option>
                  <option value="-10.25">-10.25</option>
                  <option value="-10.50">-10.50</option>
                  <option value="-10.75">-10.75</option>
                  <option value="-11.00">-11.00</option>
                  <option value="-11.25">-11.25</option>
                  <option value="-11.50">-11.50</option>
                  <option value="-11.75">-11.75</option>
                  <option value="-12.00">-12.00</option> 
                  <option value="-12.25">-12.25</option>
                  <option value="-12.50">-12.50</option>
                  <option value="-12.75">-12.75</option>
                  <option value="-13.00">-13.00</option>
                   <option value="-13.25">-13.25</option>
                  <option value="-13.50">-13.50</option>
                  <option value="-13.75">-13.75</option>
                  <option value="-14.00">-14.00</option>
                   <option value="-14.25">-14.25</option>
                  <option value="-14.50">-14.50</option>
                  <option value="-14.75">-14.75</option>
                  <option value="-15.00">-15.00</option>
              </select>
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
              Max: </font></p></td>
              <td colspan="3" align="left" bgcolor="#FFFFFF"><select name="cyl_max" class="formText" id="cyl_max">
                <option selected>Select Cylinder Max</option>
                       <option value="6.00">+6.00</option>
                  <option value="5.00">+5.00</option>
                  <option value="4.00">+4.00</option>
                  <option value="3.00">+3.00</option>
                  <option value="2.00">+2.00</option>
                  <option value="1.00">+1.00</option>
                  <option value="0.00">+0.00</option>
              </select></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
              Min: </font></p></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><font size="1" face="Arial, Helvetica, sans-serif">
                <select name="cyl_min" class="formText" id="cyl_min">
                  <option selected>Select Cylinder Min</option>
                  <option value="0.00">+0.00</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-5.00">-5.00</option>
                  <option value="-6.00">-6.00</option>
                </select>
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#DDDDDD">&nbsp;</td>
              <td colspan="3" align="left" bgcolor="#DDDDDD">&nbsp;</td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Helvetica, sans-serif, Arial">Cylinder
                  Overage Min: </font></td>
              <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><font size="1" face="Arial, Helvetica, sans-serif">
                <select name="cyl_over_min" class="formText" id="cyl_over_min">
                  <option>Select Cylinder Overage Min</option>
                  <option value="0.00">+0.00</option>
                  <option value="-1.00">-1.00</option>
                  <option value="-2.00">-2.00</option>
                  <option value="-3.00">-3.00</option>
                  <option value="-4.00">-4.00</option>
                  <option value="-5.00">-5.00</option>
				  <option value="-6.00">-6.00</option>
              </select>
              </font></td>
            </tr>
            <tr bgcolor="#DDDDDD">
              <td align="right" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">ADD Max: </font></p></td>
              <td colspan="3" align="left" bgcolor="#FFFFFF"><select name="add_max" class="formText" id="add_max">
                <option selected>Select ADD Max</option>
                <option value="4.00">+4.00</option>
                <option value="3.75">+3.75</option>
                <option value="3.50">+3.50</option>
                <option value="3.25">+3.25</option>
                <option value="3.00">+3.00</option>
                <option value="2.75">+2.75</option>
                <option value="2.50">+2.50</option>
                <option value="2.25">+2.25</option>
                <option value="2.00">+2.00</option>
                <option value="1.75">+1.75</option>
                <option value="1.50">+1.50</option>
                <option value="1.25">+1.25</option>
                <option value="1.00">+1.00</option>
                <option value="0.75">+0.75</option>
                <option value="0.50">+0.50</option>
                <option value="0.00">+0.00</option>
              </select></td>
              <td align="right" valign="middle" nowrap="nowrap" bgcolor="#FFFFFF"><p><font size="1" face="Helvetica, sans-serif, Arial">ADD
              Min: </font></p></td>
              <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><select name="add_min" class="formText" id="add_min">
                <option selected>Select ADD Min</option>
             <option value="4.00">+4.00</option>
                <option value="3.75">+3.75</option>
                <option value="3.50">+3.50</option>
                <option value="3.25">+3.25</option>
                <option value="3.00">+3.00</option>
                <option value="2.75">+2.75</option>
                <option value="2.50">+2.50</option>
                <option value="2.25">+2.25</option>
                <option value="2.00">+2.00</option>
                <option value="1.75">+1.75</option>
                <option value="1.50">+1.50</option>
                <option value="1.25">+1.25</option>
                <option value="1.00">+1.00</option>
                <option value="0.75">+0.75</option>
                <option value="0.50">+0.50</option>
                <option value="0.00">+0.00</option>
              </select></td>
            </tr>
            <tr bgcolor="#FFFFFF">
              <td colspan="6" align="center" bgcolor="#DDDDDD"> 
              <input name="createProduct" type="submit" class="formText" id="edit" value="Create Product"> 
              &nbsp;
              <input name="dupeProduct" type="submit" class="formText" id="dup" value="Create Product and Duplicate Values for New Product">
              &nbsp; <input name="cancel" type="button" class="formText" id="cancel" onClick="window.open('adminHome.php', '_top')" value="Cancel"> <a href="importExclusiveFile.php"><font size="1" face="Helvetica, sans-serif, Arial">&nbsp;Import
              Data File</font></a> </td>
            </tr>
          </table>
  		</form>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
