<?php
include "../sec_connectEDLL.inc.php";

$code=$_GET[coupon_code];
$pkey=$_GET[primary_key];
$CouponQuery="select * from coupon_codes WHERE code='$code'";
$CouponResult=mysqli_query($con,$CouponQuery)		or die ("Could not find codes");
$CouponData=mysqli_fetch_array($CouponResult,MYSQLI_ASSOC);
$itemCount=mysqli_num_rows($CouponResult);
$today=date("Y-m-d");
		
if ($itemCount==0){
	echo "not found";}
else if ($CouponData[date]<$today){
	echo "has expired";}
else if ($CouponData[type]=="inactive"){
	echo "no longer valid";}
else if ($CouponData[select_by]=="system"){
	//Valide on one system only we need to validate this 		
	$OrderQuery		   = "SELECT order_product_price,eye,order_product_id,order_product_name,user_id,order_product_coating FROM orders WHERE primary_key='$pkey'";
	$OrderResult	   = mysqli_query($con,$OrderQuery)	or die ("Could not find codes");
	$OrderData		   = mysqli_fetch_array($OrderResult,MYSQLI_ASSOC);
	
	$queryProductLine  = "SELECT product_line FROM accounts WHERE user_id = '$OrderData[user_id]'";
	$resultProductLine = mysqli_query($con,$queryProductLine)	or die ("Could not find codes");
	$DataProductLine   = mysqlifetch_array($resultProductLine,MYSQLI_ASSOC);
	$ProductLine 	   = $DataProductLine[product_line]; 	


	if ($OrderData[eye] == 'L.E.')	$Amount = $CouponData[amount] / 2;		
	if ($OrderData[eye] == 'R.E.')	$Amount = $CouponData[amount] / 2;
	if ($OrderData[eye] == 'Both')	$Amount = $CouponData[amount];
	
	if($ProductLine != $CouponData[system])  
	echo 'is not valid';
	
	//else if (($OrderData[order_product_price] < $Amount))
	//echo "is higher than the product price";
	else if($ProductLine == $CouponData[system])   
	echo 'valid';
	else
	echo 'is not valid';
			
}else{
		
	$OrderQuery="SELECT order_product_price,eye,order_product_id,order_product_name,user_id,order_product_coating FROM orders WHERE primary_key='$pkey'";
	$OrderResult=mysqli_query($con,$OrderQuery)	or die ("Could not find codes");
	$OrderData=mysqli_fetch_array($OrderResult,MYSQLI_ASSOC);
	
	$queryProductLine="SELECT product_line FROM accounts WHERE user_id = '$OrderData[user_id]'";
	$resultProductLine =mysqli_query($con,$queryProductLine)	or die ("Could not find codes");
	$DataProductLine =mysqli_fetch_array($resultProductLine,MYSQLI_ASSOC);
	$ProductLine = $DataProductLine[product_line]; 
	//echo'<br>product line:'.  $ProductLine  ;
		
	if ($OrderData[eye] == 'L.E.')	$Amount = $CouponData[amount] / 2;		
	if ($OrderData[eye] == 'R.E.')	$Amount = $CouponData[amount] / 2;
	if ($OrderData[eye] == 'Both')	$Amount = $CouponData[amount];

		
	$ProdQuery="SELECT collection, coating FROM exclusive WHERE primary_key='$OrderData[order_product_id]'";
	$ProdResult=mysqli_query($con,$ProdQuery)	or die ("Could not find codes");
	$ProdData=mysqli_fetch_array($ProdResult,MYSQLI_ASSOC);	
			
		
			
	if (($ProdData[collection]!=$CouponData[collection])&&($OrderData[order_product_name]!=$CouponData[product_name])&&($OrderData[order_product_coating]!=$CouponData[coating])&&($CouponData[select_by]!="all")){
		echo "not applicable to this product";
	}
	elseif($CouponData[type]=="one-time"){
				
			$UseQuery="select user_id from coupon_use WHERE user_id='$OrderData[user_id]' AND code='$code'";
			$UseResult=mysqli_query($con,$UseQuery)	or die ("Could not find code use");
			$UseData=mysqli_fetch_array($UseResult,MYSQLI_ASSOC);
			$UseCount=mysqli_num_rows($UseResult);
									
			if ($UseCount!=0)//coupon bon une seule fois par client et a déja été utilisé
			{					
				echo "code has already been used";
			}else{
				//Valider le montant du coupon versus le total de la commande
				$queryExtra  = "SELECT SUM(price) as TotalExtra FROM extra_product_orders WHERE order_id = '$pkey'";
				$ResultExtra = mysqli_query($con,$queryExtra)	or die ("Could not calculate extra total value");
				$DataExtra   = mysqli_fetch_array($ResultExtra,MYSQLI_ASSOC);
				$TotalExtra  = $DataExtra [TotalExtra];
				
				$OrderQuery		   = "SELECT order_product_discount,eye,order_product_id,order_product_name,user_id,order_product_coating FROM orders WHERE primary_key='$pkey'";
				$OrderResult	   = mysqli_query($con,$OrderQuery)	or die ("Could not find codes");
				$OrderData		   = mysqli_fetch_array($OrderResult,MYSQLI_ASSOC);
				$OrderPrice		   = $OrderData[order_product_discount];
				
				$TotalOrderIncludingExtra =  $OrderPrice + $TotalExtra ;
				
				if ($Amount > $TotalOrderIncludingExtra){
					//Modifier le montant du coupon afin de matcher le montant total de la commande (afin que ca arrive a 0)
					$queryUpdateCoupon  = "UPDATE coupon_codes SET amount = $TotalOrderIncludingExtra   WHERE code = '$CouponData[code]'";
					//echo $queryUpdateCoupon;
					$ResultUpdateCoupon = mysqli_query($con,$queryUpdateCoupon)	or die ("Could not update coupon");
				}
				echo "valid";
			}
	
	}elseif($OrderData[order_product_price] >= $Amount){
		echo "valid";
	}else{
		echo "is higher than the product price";
	}
}// END IF ITEMCOUNT ELSE

?>