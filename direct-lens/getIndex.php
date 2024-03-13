<?php
include "../sec_connectEDLL.inc.php";

	$query="SELECT d_index FROM products,prices where material='$_GET[MATERIAL]' and products.product_name not like '%tokai%' and products.product_name not like '%somo%' and d_index <> '1.733' and type='stock' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by d_index";
	
	echo $query;
	$result=mysqli_query($con,$query) or die ("Could not find products");
	while ($List=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				echo "$List[d_index],";
		}


?>
