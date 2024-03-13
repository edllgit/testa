<?php
require('../Connections/sec_connect.inc.php');

	$query="select sph_base from products where product_name='$_GET[PRODUCT]' GROUP by sph_base desc";
	$result=mysql_query($query)
		or die ("Could not find products");
	while ($List=mysql_fetch_array($result)){
				if ($List[sph_base]>=0){
				$List[sph_base]="+".$List[sph_base];
				}
				echo "$List[sph_base],";
		}


?>
