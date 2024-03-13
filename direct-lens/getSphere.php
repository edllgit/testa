<?php
include "../sec_connectEDLL.inc.php";

	$query="SELECT sph_base FROM products,prices where coating='$_GET[COATING]' and material='$_GET[MATERIAL]' and d_index='$_GET[INDEX]' and type='stock' and products.product_name not like '%somo%' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by sph_base desc";
	$result=mysqli_query($con,$query) or die ("Could not find products");
	
	echo $query;
	
	while ($List=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				if ($List[sph_base]>=0){
				$List[sph_base]="+".$List[sph_base];
				}
				echo "$List[sph_base],";
		}
?>
