<?php
include "../sec_connectEDLL.inc.php";

	$query="SELECT cyl_add FROM products,prices WHERE sph_base='$_GET[SPHERE]' and material='$_GET[MATERIAL]' and coating='$_GET[COATING]' and d_index='$_GET[INDEX]' and type='stock' AND products.product_name=prices.product_name AND (prices.price!=0 AND prices.price_can!=0) GROUP by cyl_add desc";
	$result=mysqli_query($con,$query) or die ("Could not find products");
	echo $query;
	
	while ($List=mysqli_fetch_array($result,MYSQLI_ASSOC)){
				if ($List[cyl_add]>0){
				$List[cyl_add]="+".$List[cyl_add];
				}
				else if  ($List[cyl_add]==0){
				$List[cyl_add]="-".$List[cyl_add];
				}
				echo "$List[cyl_add],";
		}


?>