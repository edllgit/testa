<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
  <tr >
  <?php /*?>  <td width="124" align="left"><?php echo $adm_totalquant_txt; ?></td>
    <td width="400" align="right"><b><?php echo $adm_stockbytray_txt; ?><?php echo $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $adm_stockbybulk_txt; ?><?php echo $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $adm_prescription_txt; ?><?php echo $prescrQuantity;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo 'Stock Frames:'; ?><?php echo $totalStockFrameQuantity;?></b></td><?php */?>
    <td width="100" align="right">&nbsp;</td>
  </tr>
</table>

  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
              <tr >
			   <form name="form4" method="post" action="display_order.php">
                <td align="left" bgcolor="#E5E5E5">
                  <?php echo $adm_shippinghandling_txt; ?> 
                  <select <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?> name="order_shipping_method"  class="formField" id="order_shipping_method" >
                    <option value="Next Day" <?php if ($order_shipping_method=="Next Day") echo "selected"; ?>>Next Day</option>
                    <option value="Second Day - FREE" <?php if ($order_shipping_method=="Second Day - FREE") echo "selected"; ?>>Second Day - FREE</option>
					 <option value="RX Shipping" <?php if ($order_shipping_method=="RX Shipping") echo "selected"; ?>>RX Shipping</option>
					 <option value="RX Shipping" <?php if ($order_shipping_method=="Stock Shipping") echo "selected"; ?>>Stock Shipping</option>
                    <option value="Special" <?php if ($order_shipping_method=="Special") echo "selected"; ?>>Special</option>
                  </select>                </td>
                <td width="360" align="right" valign="middle" bgcolor="#E5E5E5"><input name="Submit" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				   if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				 
				  ?> type="submit" disabled="disabled" class="formField3" value="<?php echo $btn_updateshipping_txt; ?>" />
                  <input name="from_shipping_form" type="hidden" id="from_shipping_form" value="true">
			      <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
			      <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>"></td>
			    <td width="83" align="right" valign="middle" bgcolor="#E5E5E5"><input  name="order_shipping_cost" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?>  type="text" class="formField2RA" value="<?php echo $order_shipping_cost; ?>" size="7" /></td>
			   </form>
              </tr>
              
			       <tr >
			   <form name="form5" method="post" action="display_order.php">
                <td align="left">
                  <?php echo $adm_additionalitem_txt; ?> 
                    <input <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled')) 
				 echo 'disabled="disabled"';
				 
				 if ($manage_additional_item == 'no'){
				 	echo 'disabled="disabled"';
				 }
				  ?> name="extra_product" type="text" class="formField3" id="extra_product" value="<?php echo $orderData[extra_product];?>" size="40" /></td>
                <td width="360" align="right" valign="middle"><input name="Submit" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				 
				 if ($manage_additional_item == 'no'){
				 	echo 'disabled="disabled"';
				 }
				 
				 if ($PmtType == 'CC'){
					echo 'disabled="disabled"';
				 }
				 
				  ?> type="submit" class="formField3" value="<?php echo $btn_upitem_txt;?>" />
                  <input name="from_extra_item_form" type="hidden" id="from_extra_item_form" value="true">
			      <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
		         <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>"></td>
			    <td width="83" align="right" valign="middle"><?php echo $adm_moneysym_txt; ?>
			      <input name="extra_product_price" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  
				   if ($PmtType == 'CC'){
					 echo 'disabled="disabled"';
					 }
					 
					 if ($manage_additional_item == 'no'){
						echo 'disabled="disabled"';
				 	 }
				  ?>  type="text" class="formField2RA" id="extra_product_price" value="<?php echo $orderData[extra_product_price];?>" size="7" /></td>
			   </form>
              </tr>
              

                
             <tr >
			   <form name="form6" method="post" action="display_order.php">
                <td  bgcolor="#E5E5E5" align="left">
                  <?php if ($mylang == 'lang_french') {
				  echo "Instruction Speciales: ";
				  }else {
				  echo "Special Instructions: ";
				  }
				 ?>
                    <input <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				   if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  ?> name="special_instructions" type="text" class="formField3" id="special_instructions"  value="<?php echo $orderData[special_instructions];?>" size="70" /></td>
                <td  bgcolor="#E5E5E5" width="360" align="right" valign="middle"><input name="Submit"   type="submit" class="formField3" 
                value="
				<?php if ($mylang == 'lang_french') {
				  echo "Sauvegarder l\'instruction speciale";
				  }else {
				  echo "Update Special Instruction";
				  }
				 ?>"
                    <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				   if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  ?>
                  />
                  <input name="from_special_instructions" type="hidden" id="from_special_instructions" value="true">
			      <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
		         <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>"></td>
			   <td  bgcolor="#E5E5E5" width="83" align="right" valign="middle">&nbsp;</td>
               </form>
              </tr>
                 

             <tr >
			   <form name="form6" method="post" action="display_order.php">
                <td  bgcolor="#E5E5E5" align="left">
                  <?php if ($mylang == 'lang_french') {
				  echo "Note interne: ";
				  }else {
				  echo "Internal note: ";
				  }
				 ?>
                    <input <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				   if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  ?> name="internal_note" type="text" class="formField3" id="internal_note"  value="<?php echo $orderData[internal_note];?>" size="70" /></td>
                <td  bgcolor="#E5E5E5" width="360" align="right" valign="middle"><input name="Submit"   type="submit" class="formField3" 
                value="
				<?php if ($mylang == 'lang_french') {
				  echo "Sauvegarder la note interne";
				  }else {
				  echo "Update Internal note";
				  }
				 ?>"
                 
                 
                 
                 <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				   if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  ?>
                  />
                  <input name="from_internal_note" type="hidden" id="from_internal_note" value="true">
			      <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
		         <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>"></td>
			    <td  bgcolor="#E5E5E5" width="83" align="right" valign="middle">&nbsp;</td>
			   </form>
              </tr>
              
              <tr><td>&nbsp;</td>              </tr>
              
              
              
			  
			  <?php if ($orderData[additional_dsc]!=0){
			    echo "<tr >";
                echo "<td width=\"524\" align=\"left\">Additional Discount (".$orderData[discount_type].$orderData[additional_dsc].")</td>";
                echo "<td colspan=\"2\" align=\"right\" valign=\"middle\" class=\"total\"><b>-";
				if ($orderData[discount_type]=="$"){
					$totalDiscount=money_format('%.2n',$orderData[additional_dsc]);
					}
				else if ($orderData[discount_type]=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($orderData[additional_dsc]/100));
				}
				echo $totalDiscount; 
				echo "</b></td></tr>";
			  
			  }?>
              <tr >
                <td width="524" align="left" bgcolor="#E5E5E5"><b><?php echo $adm_ordertotalwith_txt; ?></b></td>
                <td colspan="2" align="right" valign="middle" bgcolor="#E5E5E5" class="total"><b>
                  <b><?php echo $adm_moneysym_txt; ?> </b>
                  <?php 
//$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);
$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount);

if ($StockFrameOrder == 'yes'){
	$queryOrderTotal  = "SELECT order_total FROM orders WHERE order_num = ". $_GET[order_num];	
	$resultOrderTotal = mysql_query($queryOrderTotal)	or die ("Could not select items");
	$DataOrderTotal   = mysql_fetch_array($resultOrderTotal);
	$totalPriceDsc    = money_format('%.2n',$DataOrderTotal[order_total]+$order_shipping_cost);
}


$totalPriceDsc=$totalPriceDsc;
			echo $totalPriceDsc; ?>
                </b></td>
              </tr>
              <tr >
                <td align="left" bgcolor="#666666">&nbsp;</td>
                <td colspan="2" align="right" valign="middle" bgcolor="#666666" class="total">&nbsp;</td>
              </tr>
</table>
