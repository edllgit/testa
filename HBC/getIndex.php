<?php
require('../Connections/sec_connect.inc.php');

	$query="select d_index from products,prices where material='$_GET[MATERIAL]' and type='stock' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by d_index";
	$result=mysql_query($query)
		or die ("Could not find products");
	while ($List=mysql_fetch_array($result)){
				echo "$List[d_index],";
		}

?>
