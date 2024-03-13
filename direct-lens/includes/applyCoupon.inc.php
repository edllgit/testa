   <form id="<?php print $listItem[order_item_number] ?>" name="<?php print $listItem[order_item_number] ?>" method="post" action="basket.php" onSubmit="return test_coupon(this)"><td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides">
    <input name="coupon_code" type="text" class="formText" id="coupon_code" size="10" />
	<input name="textVar<?php print $listItem[primary_key]?>" type="hidden" id="textVar<?php print $listItem[primary_key]?>" value="EMPTY"/>
				   
      <input name="Submit" type="submit" class="formText" value="<?php echo $btn_applycode_txt;?>" />
	<input name="pkey" type="hidden" value="<?php print $listItem[primary_key]?>" />
	<input name="orderTotal" type="hidden" value="<?php print $itemSubtotal?>" />
				  <input name="apply_coupon" type="hidden" value="true" />
                  </td> </form>
