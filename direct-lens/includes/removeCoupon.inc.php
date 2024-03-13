   <form id="remove<?php print $listItem[order_item_number] ?>" name="remove<?php print $listItem[order_item_number] ?>" method="post" action="basket.php"><td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides">
    <?php 
	
	$CouponQuery="SELECT * FROM coupon_use WHERE order_id='$listItem[primary_key]'";
	$CouponResult=mysqli_query($con,$CouponQuery) or die ("Could not find codes");
	$CouponData=mysqli_fetch_array($CouponResult,MYSQLI_ASSOC);
	
	if ($CouponData[code] == 'PromoAR54218'){
	echo "Coupon Code: <b>One Year Promo<b>";
	}else{
	echo "Coupon Code: <b>".$CouponData[code]."<b>";
	}
	?>
	<input name="Submit" type="submit" class="formText" value="<?php echo $btn_removecoupon_txt;?>" />
	<input name="pkey" type="hidden" value="<?php print $listItem[primary_key]?>" />
				  <input name="remove_coupon" type="hidden" value="true" />
                  </td> </form>
