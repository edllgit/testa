<?php
require('../Connections/sec_connect.inc.php');

	$query="select sph_base from products,prices where coating='$_GET[COATING]' and material='$_GET[MATERIAL]' and d_index='$_GET[INDEX]' and type='stock' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by sph_base desc";
	$result=mysql_query($query)
		or die ("Could not find products");
	while ($List=mysql_fetch_array($result)){
				if ($List[sph_base]>=0){
				$List[sph_base]="+".$List[sph_base];
				}
				echo  "$List[sph_base],";
		}


?>
