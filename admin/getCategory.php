<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

//INCLUSIONS
include "../sec_connectEDLL.inc.php";
include("admin_functions.inc.php");

session_start();


if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}


$type=$_GET[category];

if ($type=="stock"){
	$query="SELECT * FROM prices
	LEFT JOIN stock_collections ON (stock_collections.stock_collections_id=prices.stock_collections_id)
	ORDER BY product_name ";
}
else{//IF EXCLUSIVE PRODUCT
	if($_GET[sort_by]!=""){
		//$query="select * from exclusive where prod_status='active' AND COLLECTION IN ('Private 2','Innovation FF HD') and price_can =0  order by ".$_GET[sort_by];
		$query="select * from exclusive where prod_status='active' AND collection not like '%bbg%' AND collection not like '%optovision%' AND collection not like '%private hko%' order by ".$_GET[sort_by];
		$_GET[sort_by]="";
		}
	else{
		//$query="select * from exclusive where prod_status='active' AND COLLECTION IN ('Private 2','Innovation FF HD')  and price_can =0  order by manufacturer,product_name,index_v,coating,photo,polar";
		$query="select * from exclusive where prod_status='active' AND collection not like '%bbg%' AND collection not like '%optovision%' AND collection not like '%private hko%'  order by manufacturer,product_name,index_v,coating,photo,polar";
		}
}
$catResult=mysqli_query($con,$query)	or die ( "Query failed: " . mysqli_error($con));
//echo '<br>Query: '. $query;
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css">
</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="100%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
		
		<?php
			if ($type=="stock"){
				include("stockList.inc.php");}
			else{
				include("exclusiveList.inc.php");}
		?>
           
				
</td>
    </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
