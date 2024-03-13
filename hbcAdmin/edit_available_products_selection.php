<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");
$user_id = $_POST[user_id];
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
 <?php 
 if (isset($_POST[select_from_full_list])){
 	$Recherche = 'full list';	 
 }elseif (isset($_POST[select_from_collection])){
 	$Recherche = 'collection';	 
 }elseif (isset($_POST[search_product_name])){
 	$Recherche = 'by product name';	 
 }elseif(isset($_POST[id_a_ajouter])){
	 
$IDaAjouter = $_POST[id_a_ajouter];
foreach ($IDaAjouter as &$value) {
	$queryUpdate  = "INSERT INTO acct_available_products(user_id,product_id) VALUES ('$user_id',$value)";
	$resultUpdate = mysql_query($queryUpdate)  or die ('Erreur');
	//echo '<br>Queryupdate:'. $queryUpdate;
}//End for each
//Redirection vers la page acct_available_products.php
?>
<form id="redirect" action="acct_available_products.php" method="get">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
</form>
<script>
    document.getElementById('redirect').submit();
</script>

<?php
echo '<br>Wait for redirection..';		
exit();
	 
 }elseif ($_POST[cancelled_btn]=="Go back"){ ?>
<form id="redirect" action="acct_available_products.php" method="get">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">
</form>
<script>
    document.getElementById('redirect').submit();
</script>

<?php
echo '<br>Wait for redirection..';		
exit();	 
 
 }else{
 	echo 'Nothing Submitted: An error occured, Please contact technical support'; 
 }
 ?>  
 <td width="400" align="left">
<?php
switch($Recherche){
	case 'full list':
	$queryRecherche 		  = "SELECT primary_key, product_name, collection, price_can, price FROM exclusive WHERE prod_status='active'";
	$description 			  = 'All actives products';
	break;
	
	case 'collection':
	$collection_name 		  = $_POST[collection_name];
	$queryRecherche 		  = "SELECT primary_key, product_name, collection, price_can, price FROM exclusive WHERE prod_status='active' AND collection = '$collection_name'";
	$description 			  = 'Actives products in collection '. $_POST[collection_name];
	break;  
	
	case 'by product name':
	$product_name			  = $_POST[product_name];
	$queryRecherche 		  = "SELECT primary_key, product_name, collection, price_can, price FROM exclusive WHERE prod_status='active' AND product_name like '%$product_name%'";
	$description			  = 'Actives products labeled like:  '. $product_name;
	break;
}

$resultRecherche = mysql_query($queryRecherche) or die ('Error');



		echo "<form action=\"edit_available_products_selection.php\" method=\"post\" name=\"form_add\" id=\"form_add\">";
		echo "<table width=\"70%\" border=\"1\" cellpadding=\"4\" cellspacing=\"0\" class=\"formField2\">";
		echo '<tr><th style="background-color:#CCC"  colspan=\'7\'><p>'.$description.'</th></tr>';
		
		
		echo "<input type=\"hidden\" name=\"user_id\" id=\"user_id\" value=\"$user_id\" ";
		
		echo '<tr>';
		echo '<th width="75">Select to Add</th>';
		echo '<th width="75">Primary Key</th>';
		echo '<th width="300">Product Name</th>';
		echo '<th width="200">Collection</th>';
		echo '<th width="100">Price CAN</th>';
		echo '<th width="100">Price US</th>';
		echo '<th width="100">Already activated ?</th>';
		echo '</tr>';
		
		while ($DataAvailableProducts  = mysql_fetch_array($resultRecherche)){
		
		
if ($DataAvailableProducts[primary_key] <> '')
{
	$queryAlreadyActive   = "SELECT count(*) as nbrResult FROM acct_available_products WHERE user_id='$user_id' AND product_id=". $DataAvailableProducts[primary_key];
	$resultAlreadyActive  = mysql_query($queryAlreadyActive) or die ('Error' .$queryAlreadyActive  );
	$DataAlreadyActive    = mysql_fetch_array($resultAlreadyActive);
	if ($DataAlreadyActive[nbrResult] > 0)
	$ProductAlreadyActivated = 	'Yes';
	else
	$ProductAlreadyActivated = 	'No';	
}
				
			echo '<tr>';	
			echo "<td width=\"75\"  align=\"center\"";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo "><input type=\"checkbox\" id=\"id_acct_available_product\"";

			if ($ProductAlreadyActivated == 'Yes') echo " disabled ";
			echo "  value=\"$DataAvailableProducts[primary_key]\" name=\"id_a_ajouter[$DataAvailableProducts[primary_key]]\">
			</td>";
			
			echo "<td ";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo "width=\"75\"  align=\"center\">$DataAvailableProducts[primary_key]</td>";
			
			echo "<td ";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo "width=\"300\" align=\"center\">$DataAvailableProducts[product_name]</td>";
			
			
			echo "<td";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo " width=\"200\" align=\"center\">$DataAvailableProducts[collection]</td>";
			
			echo "<td ";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo "width=\"100\" align=\"center\">$DataAvailableProducts[price_can]$</td>";	
			
			echo "<td";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo " width=\"100\" align=\"center\">$DataAvailableProducts[price]$</td>";
			
			echo "<td ";
			if ($ProductAlreadyActivated == 'Yes') echo " bgcolor=\"#B3B0B0\"";
			echo "width=\"100\" align=\"center\">$ProductAlreadyActivated</td>";		
			
			echo '</tr>';
				
		}//End While
		
		echo '<tr>';	
		echo "<td colspan=\"6\" align=\"center\"><input name=\"cancelled_btn\" id=\"cancelled_btn\" value=\"Go back\" type=\"submit\">&nbsp;&nbsp;
		<input name=\"add_selected\" id=\"add_selected\" value=\"Add Selected\" type=\"submit\"></td>";	
		echo '</tr>';
		echo '</form>';

?>

 </td>
</table>
</td>
</tr>
</table>
</body>
</html>