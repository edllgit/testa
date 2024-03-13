<?php
require('../Connections/sec_connect.inc.php');

	$query="select cyl_add from products where sph_base='$_GET[SPHERE]' and product_name='$_GET[PRODUCT]' and type='stock' GROUP by cyl_add desc";
	$result=mysql_query($query)
		or die ("Could not find products");
	while ($List=mysql_fetch_array($result)){
				if ($List[cyl_add]>=0){
				$List[cyl_add]="+".$List[cyl_add];
				}
				echo "$List[cyl_add],";
		}


?>
