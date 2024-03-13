<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_id=$_SESSION["labAdminData"]["primary_key"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];

if ($_POST['from_update_prices']=="true"){
	$prod_count=$_POST['prod_count'];
	for ($i=1;$i<=$prod_count;$i++){
		$prod_id=$_POST[prod_id][$i];
		$price_us=$_POST[price_us][$i];
		$price_can=$_POST[price_can][$i];
		$price_eur=$_POST[price_eur][$i];
		
		$query="SELECT * from extra_prod_price_lab WHERE lab_id='$lab_id' and extra_prod_id='$prod_id'";
		$result=mysql_query($query);
		$usercount=mysql_num_rows($result);
		if ($usercount==0){
			$query=("INSERT INTO extra_prod_price_lab (price_us, price_can, price_eur, lab_id, extra_prod_id) VALUES ('$price_us', '$price_can', '$price_eur','$lab_id','$prod_id')");
		$result=mysql_query($query);
		}
		else{
		$query=("UPDATE extra_prod_price_lab SET price_us='$price_us', price_can='$price_can', price_eur='$price_eur' WHERE lab_id='$lab_id' AND extra_prod_id='$prod_id'");
		$result=mysql_query($query)
		or die ('Could not update because: ' . mysql_error());
		}//END IF USERCOUNT
	}// END FOR
}//END IF
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
.style2 {
	color: #FFFFFF;
	font-weight: bold;
}
-->
</style>

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
  		<td width="75%">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td colspan="6" align="center" class="formField1"><span class="formField2 style2"><?php echo $adm_titlemast_exprod;?> </span></td>
       		  </tr>
			</table>

<table width="100%" border="0" cellpadding="4" cellspacing="0" class="formField2">
   
<?php
$prodQuery="SELECT * from extra_products WHERE activated='active' order by category,name";
$prodResult=mysql_query($prodQuery)
	or die ("Could not find product prices");

$prodCount=mysql_num_rows($prodResult);
echo "<form action=\"extra_product_pricing.php\" method=\"post\" name=\"form\"><tr bgcolor=\"#DDDDDD\">";

$counter=0;

while ($prodData=mysql_fetch_array($prodResult)){
	$priceQuery="SELECT * from extra_prod_price_lab WHERE lab_id = '$lab_id' AND extra_prod_id = '$prodData[prod_id]'";
	$priceResult=mysql_query($priceQuery)
		or die ("Could not find stock discounts");
	$priceData=mysql_fetch_array($priceResult);
	$priceCount=mysql_num_rows($priceResult);
	
	$price_us=$priceData[price_us];
	$price_can=$priceData[price_can];
	$price_eur=$priceData[price_eur];

		if ($counter%2==0)
			$bgcolor="#FFFFFF";
		else
			$bgcolor="#DDDDDD";
		$counter++;
		
		echo "</tr><tr bgcolor=$bgcolor>";
	
	echo "<td align=\"right\"><b>$prodData[name]:</b></td>";
	echo"<td>".$adm_priceus_txt."<input name=\"price_us[$counter]\" type=\"text\" size=\"6\" value=\"$price_us\" class=\"formField2\" /></td>";
	
	echo "<td>".$adm_pricecan_txt."<input name=\"price_can[$counter]\" type=\"text\" size=\"6\" value=\"$price_can\" class=\"formField2\" /></td>";
	
	echo "<td>".$adm_priceeuro_txt."<input name=\"price_eur[$counter]\" type=\"text\" size=\"6\" value=\"$price_eur\" class=\"formField2\" /><input type=\"hidden\" name=\"prod_id[$counter]\" value=\"$prodData[prod_id]\" /></td></tr>";
}
echo "<tr><td align=\"center\"  colspan=\"4\"><input type=\"hidden\" name=\"from_update_prices\" value=\"true\" /><input disabled=\"disabled\" type=\"hidden\" name=\"prod_count\" value=\"$counter\" /><input  name=\"updateDisc\" type=\"submit\" value=\"Update Prices\" class=\"formField2\" /></td></tr></form>";

?>
</table>
</td>
	  </tr>
</table>

</body>
</html>
