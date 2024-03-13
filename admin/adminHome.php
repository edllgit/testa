<?php
session_start();

header("Location: report.php?reset=y");
exit();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
require_once(__DIR__.'/../constants/url.constant.php');
include("../Connections/sec_connect.inc.php");

$dbh=mysql_connect($mysql_host, $mysql_user, $mysql_password) or die ("I cannot connect to the database because: " . mysql.error()); mysql_select_db($mysql_db);

If ($dbh==FALSE) {
echo "Connection to database has failed.";
exit();
}
?>


<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
</head>

<body>
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0" class="formField">
  	<tr bgcolor="#DDDDDD">
  		<td bgcolor="#000000"><div align="center">
  			<b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Direct
  			Lens  Admin
  			Area </font></b>
  		</div></td>
	  </tr>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">
   
   
    <tr bgcolor="#000000">
    	<td><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><b>ACCOUNTS</b></font></td>
    </tr>
    
    <tr bgcolor="#DDDDDD">
    	<td align="left"><form name="form1" method="post" action="getAccount.php">
    			<b>DIRECT-LENS:</b> Select Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="select primary_key, company, last_name, first_name from accounts where approved='approved' and product_line='directlens' order by company, last_name";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
   	  </tr>
      
      
      
        <tr bgcolor="#DDDDDD">
    	<td align="left"><form name="form1" method="post" action="getAccount.php">
    			<b>DIRECT-LENS:</b> WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="select primary_key, company, last_name, first_name from accounts where approved='pending' and product_line='directlens' order by company, last_name";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
   	  </tr>
      
      <tr bgcolor="#DDDDDD"><td>&nbsp;</td></tr>
        <tr bgcolor="#DDDDDD">
    	<td align="left"><form name="form1" method="post" action="getAccount.php">
    			<b>LENS NET CLUB </b> Select Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="select primary_key, company, last_name, first_name from accounts where approved='approved' and product_line='lensnetclub' order by company, last_name";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
   	  </tr>
      

  
        <tr bgcolor="#DDDDDD">
    	<td align="left"><form name="form1" method="post" action="getAccount.php">
    			<b>LENS NET CLUB </b> WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="select primary_key, company, last_name, first_name from accounts where approved='pending' and product_line='lensnetclub' order by company, last_name";
	$result=mysql_query($query)
		or die ("Could not find account list");
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
   	  </tr>
      
      
      
      
       <tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">Lab Admin Access</font></b></td>
	  </tr>
      
       <tr bgcolor="#DDDDDD">
		<td align="left"><p><a href="listaccess.php">Manage ACCESS</a></p></td>
	  </tr>
      
           
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">REPORTS</font></b></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left"><p><a href="report.php">Order Reports</a></p></td>
	  </tr>
  	<tr>
		<td align="left"><p><a href="reports_all_products.php">All Products Totals</a></p></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left"><p><a href="reports_Dream_AR.php">Dream AR Totals</a></p></td>
	  </tr>
  	<tr>
		<td align="left"><p><a href="reports_exclusive.php">Exclusive Products Totals</a></p></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left"><p><a href="reports_index.php">Index Totals</a></p></td>
	  </tr>
      	<tr >
		<td align="left"><p><a href="<?php echo constant('DIRECT_LENS_URL'); ?>/admin/reports_ReBilling_Admin.php">Re-Billing Statement</a></p></td>
	  </tr>
          	<tr >
		<td align="left"><p><a href="<?php echo constant('DIRECT_LENS_URL'); ?>/admin/reports_coupons.php">Coupon code Usage</a></p></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">PRODUCTS</font></b></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#FFFFFF"><a href="getCategory.php?category=stock">List
          Stock Products</a></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a href="getCategory.php?category=exclusive">List
          Exclusive Products</a></td>
    </tr>
  	
  	<tr bgcolor="#DDDDDD">
  	  <td align="left" bgcolor="#FFFFFF"><a href="newExclusiveProduct.php">Add
      an Exclusive Product</a></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
  	  <td align="left" bgcolor="#DDDDDD"><a href="newProduct.php">Add
	  a Stock Product</a></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
  	  <td align="left" valign="middle" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">FRAMES</font></b></td>
    </tr>
  <tr bgcolor="#DDDDDD">
        <td align="left" bgcolor="#FFFFFF"><a href="newFrameCollection.php">Frames Collections</a></td>
      </tr>
    <tr bgcolor="#DDDDDD">
        <td align="left" bgcolor="#DDDDDD"><a href="newFrame.php">Frames</a></td>
  </tr>
     <tr bgcolor="#DDDDDD">
        <td align="left" bgcolor="#FFFFFF"><a href="newFrameColor.php">Frames Temple Colors</a></td>
      </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">LABS</font></b></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#DDDDDD"><form name="form2" method="post" action="getLab.php">
				Select Lab <br>
				
				<select name="lab" class="formField">
		<option value="" selected="selected">Select Lab</option>
					<?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
?>
				</select>
				
				<input type="submit" name="Submit" value="Go" class="formField">
				 <br>
				(edit lab)
		</form></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#FFFFFF"><a href="newLab.php">Add
					a Lab </a></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">BUYING
					GROUPS </font></b></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#DDDDDD"><form action="getBuying_group.php" method="post" name="form3" id="form3">
			Select Buying Group <br />
			<select name="buying_group" class="formField">
				<option value="" selected="selected">Select Buying Group</option>
				<?php
	$query="select primary_key, bg_name from buying_groups order by bg_name";
	$result=mysql_query($query)
		or die ("Could not find bg list");
	while ($bgList=mysql_fetch_array($result)){
		if($bgList[primary_key]!=1)
			echo "<option value=\"$bgList[primary_key]\">$bgList[bg_name]</option>";
}
?>
			</select>
			<input type="submit" name="Submit" value="Go" class="formField" />
			<br />
			(edit group)
		</form></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#FFFFFF"><a href="newBG.php">Add a Buying Group</a></td>
	  </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">PROMOTIONS</font></b></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#FFFFFF"><a href="newCoupon.php">Coupon
          Codes </a></td>
    </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a href="promotionsUpload.php">Upload
          New Promotion</a></td>
    </tr>
  	
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000">&nbsp;</td>
    </tr>
  	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><p><a href="logout.php">Logout</a> <b>[<?php echo $_SESSION[adminData][username]?>]</b></p></td>
    </tr>
  	</table>
<p>&nbsp;</p>
</body>
</html>
