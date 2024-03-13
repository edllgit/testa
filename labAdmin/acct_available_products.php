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


$FormSubmitted = false;
if (isset($_POST[user_id])){
	$FormSubmitted = true;
	$user_id = $_POST[user_id];
	//A user ID has been submitted, we show the actives products 
	$queryCompany  = "SELECT user_id, company FROM accounts WHERE user_id = '$_POST[user_id]'";
	$resultCompany = mysql_query($queryCompany)            or die ('Erreur');
	$DataCompany   = mysql_fetch_array($resultCompany);
}elseif (isset($_REQUEST[user_id])){
	$FormSubmitted = true;
	$user_id = $_REQUEST[user_id];
	//A user ID has been submitted, we show the actives products 
	$queryCompany  = "SELECT user_id, company FROM accounts WHERE user_id = '$_REQUEST[user_id]'";
	$resultCompany = mysql_query($queryCompany)            or die ('Erreur');
	$DataCompany   = mysql_fetch_array($resultCompany);
}



if (isset($_POST[id_a_effacer]) && (isset($_POST[user_id]))){
	$IDaEffacer = $_POST[id_a_effacer];
	$FromDelete = true;
	$user_id = $_POST[user_id];
	foreach ($IDaEffacer as &$value) {
		$queryUpdate  = "DELETE FROM  acct_available_products  WHERE product_id = $value and user_id='$user_id'";
		$resultUpdate = mysql_query($queryUpdate)  or die ('Erreur');
		//echo '<br><br>'. $queryUpdate;
	}//End for each
			
}
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
<script language='JavaScript'>
checked = false;
function checkedAll () {
if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('form1').elements.length; i++) {
	document.getElementById('form1').elements[i].checked = checked;
	}
}
</script>
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
            		<td colspan="6" align="center" class="formField1"><span class="formField2 style2"><?php echo 'Select the products a customer can order';?> </span></td>
       		  </tr>
			</table>

<table width="100%" border="0" cellpadding="4" cellspacing="0" class="formField2">
   
 <td width="400" align="left"><form action="acct_available_products.php" method="post" name="form1" id="form1">
			 Select an Existing Account to see/update their available products<br />
            <select name="user_id" id="user_id" class="formField">
            <option value=""><?php echo $adm_selectaccount_txt;?></option>
            <?php
            $query="select primary_key, company, last_name, first_name, user_id from accounts where  approved='approved'  order by company, last_name";
            $resultAcct=mysql_query($query) or die ($adm_error1_txt);
            while ($accountList=mysql_fetch_array($resultAcct)){
            	echo "<option value=\"$accountList[user_id]\">$accountList[company], $accountList[first_name] $accountList[last_name]  User id:$accountList[user_id] </option>";
            }
            mysql_free_result($resultAcct);
            ?>
            </select>&nbsp;<input type="submit" name="Submit" value="Select" class="formField" />
            </form>
        </td><?php
echo "</form>";


if ($FormSubmitted){
echo "<form action=\"edit_available_products.php\" method=\"post\" name=\"form1\" id=\"form1\">";
echo '<td>Company:<b> '. $DataCompany[company].'</b>&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<input type="submit" name="edit_products" value="Edit Available Products">';
echo'</td></tr></table>';
echo "<input type=\"hidden\" name=\"user_id\" value=\"$user_id\">";

echo '</form>';
}
?>

<?php
if ($FormSubmitted){
	//Get products that this customer has already access	
	$queryAlreadyActivated	 = "SELECT primary_key, product_name FROM exclusive WHERE primary_key
	IN (SELECT DISTINCT product_id FROM `acct_available_products` WHERE user_id = '$user_id')";
	$resultAlreadyActivated  = mysql_query($queryAlreadyActivated) or die ('error');
	$NbrResult 		         = mysql_num_rows($resultAlreadyActivated);
	
	
	//S'il y a des produits deja activés
	if ($NbrResult > 0){
		echo "<form action=\"acct_available_products.php\" method=\"post\" name=\"form_delete\" id=\"form_delete\">";
		echo "<input name=\"user_id\" type=\"hidden\"  id=\"user_id\" value=\"$user_id\">";
		echo "<table width=\"70%\" border=\"1\" cellpadding=\"4\" cellspacing=\"0\" class=\"formField2\">";
		echo '<tr><th style="background-color:#CCC"  colspan=\'6\'><p>Products  activated for this account</th></tr>';
		?>
		<tr>
		<th width="75">Check All<input type="checkbox" onclick='checkedAll();' id="checkAll" name="checkAll"></th>
		<th width="75">Primary Key</th>
		<th width="300">Product Name</th>
		<th width="200">Collection</th>
		<th width="100">Price CAN</th>
		<th width="100">Price US</th>
		</tr>

		<?php
		while ($DataAlreadyActivated  = mysql_fetch_array($resultAlreadyActivated)){
			
			$queryProduct   = 'SELECT collection, price, price_can  FROM exclusive WHERE primary_key =  ' . $DataAlreadyActivated[primary_key];	
			$resultProduct  = mysql_query($queryProduct) or die ('error');
			$DataProduct    = mysql_fetch_array($resultProduct);
	
	
	
			echo '<tr>';	
			echo "<td width=\"75\"  align=\"center\">
			<input type=\"checkbox\" id=\"id_acct_available_product\"  value=\"$DataAlreadyActivated[primary_key]\" name=\"id_a_effacer[$DataAlreadyActivated[primary_key]]\">
			</td>";
			echo "<td width=\"75\"  align=\"center\">$DataAlreadyActivated[primary_key]</td>";
			echo "<td width=\"300\" align=\"center\">$DataAlreadyActivated[product_name]</td>";
			echo "<td width=\"200\" align=\"center\">$DataProduct[collection]</td>";
			echo "<td width=\"100\" align=\"center\">$DataProduct[price_can]$</td>";	
			echo "<td width=\"100\" align=\"center\">$DataProduct[price]$</td>";	
			echo '</tr>';
				
		}//End While
		
			echo '<tr>';	
			echo "<td colspan=\"6\" align=\"center\"><input name=\"delete_selected\" id=\"delete_selected\" value=\"Delete Selected\" type=\"submit\"></td>";	
			echo '</tr>';
			echo '</form>';
		
	}else{
		echo '<p>No products are activated for this account</p>';
	}

}
?>
</table>
</td>
</tr>
</table>
</body>
</html>