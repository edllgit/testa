<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$user_id = $_POST[user_id];
//echo 'User id: '. $user_id ;
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
            		<td colspan="6" align="center" class="formField1"><span class="formField2 style2"><?php echo 'Edit available products for a customer';?> </span></td>
       		  </tr>
			</table>

<table width="100%" border="0" cellpadding="4" cellspacing="0" class="formField2">
   
 <td width="400" align="left">
 <h3>You have 3 options to add products to a customer's account:</h3><br><br><br>
 <form action="edit_available_products_selection.php" method="post" name="form1" id="form1">
	<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>">
    1- <input type="submit" name="select_from_full_list" id="select_from_full_list" value="Select From Complete Products List">
    <br><br><br>
    2- 
    <select name="collection_name" id="collection_name" class="formField">
            <?php
            $queryCollection  = "SELECT distinct collection FROM exclusive WHERE prod_status='active' order by collection";
            $resultCollection = mysql_query($queryCollection) or die ('Error');
            while ($DataCollection=mysql_fetch_array($resultCollection)){
            	echo "<option value=\"$DataCollection[collection]\">$DataCollection[collection]</option>";
            }
            ?>
    </select>&nbsp;&nbsp;
    
    <input type="submit" name="select_from_collection" id="select_from_collection" value="Select From a Specific Collection">
    <br><br><br>
    3- Type some text to search by Product Name <input type="text" name="product_name" id="product_name" size="15"> <input type="submit" name="search_product_name" id="search_product_name" value="Search by Product Name">
    <br><br><br>	
 </form>
        </td><?php
echo "</form>";


if ($FormSubmitted){
echo "<form action=\"edit_available_products.php\" method=\"post\" name=\"form1\" id=\"form1\">";
echo $DataCompany[user_id];
echo '<td>Company:<b> '. $DataCompany[company].'</b>&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="submit" name="edit_products" value="Edit Available Products">';
echo'</td></tr></table>';
echo '<input type="hidden" name=\"user_id\" value=\"$user_id\">';

echo '</form>';
}
?>

</table>
</td>
</tr>
</table>
</body>
</html>