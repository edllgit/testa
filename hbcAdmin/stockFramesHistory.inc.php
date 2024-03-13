	
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3"><form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="editFrameOrder.php">
         <tr >
                <td colspan="6" bgcolor="#555555" class="formField3"><font color="#FFFFFF">Reference - <?php echo $listItem[tray_num] ?></font></td><td  bgcolor="#555555">&nbsp;</td>
			<td  bgcolor="#555555" align="right">&nbsp;<input name="Submit" type=
				<?php if ($listItem[order_status]=="filled"){
					echo "\"hidden\"";}
					else {
					echo "\"submit\"";}?>
				class="formField3" value="Edit" disabled /><input name="re_pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" /></td>
  </tr>
              <tr>
                <td width="395"  align="center" bgcolor="#E5E5E5" class="formField3"><strong>Frame</strong></td>
                <td width="355"  align="center" bgcolor="#E5E5E5" class="formField3"><strong>Frame type</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Qty</strong></td>
                <td width="265" align="center" bgcolor="#E5E5E5" class="formField3"><strong>Unit Price</strong></td>
                <td width="320" align="center" bgcolor="#E5E5E5" class="formField3"><strong>Subtotal</strong></td>
              </tr>
              
            <?php 
			$SousTotal  =  $listItem[order_product_price] * $listItem[order_quantity];  
			$SousTotal  =  money_format('%.2n',$SousTotal);

			?>
              <tr>
                <td align="center" class="formField3"><?php echo $listItem[order_product_name] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_material] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_quantity] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_price] ?></td>
                <td align="center" valign="top" class="formField3"><b>$<?php echo $SousTotal; ?></b></td>
              </tr>
              
               
              
              </form>
              </table>
