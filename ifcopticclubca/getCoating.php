<?php
require('../Connections/sec_connect.inc.php');

	$query="select coating from products,prices where d_index='$_GET[INDEX]' and material='$_GET[MATERIAL]' and type='stock' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by coating";
	$result=mysql_query($query)		or die ("Could not find products");
	while ($List=mysql_fetch_array($result)){
				echo "$List[coating],";
		}


?>
