	
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3"><form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="editStockOrder.php">
         <tr >
                <td colspan="6" bgcolor="#555555" class="formField3"><font color="#FFFFFF">Tray Reference - <?php echo $listItem[tray_num] ?></font></td><td  bgcolor="#555555">&nbsp;</td>
			<td  bgcolor="#555555" align="right">&nbsp;<input name="Submit" type=
				<?php if ($listItem[order_status]=="filled"){
					echo "\"hidden\"";}
					else {
					echo "\"submit\"";}?>
				class="formField3" value="Edit" /><input name="re_pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" /></td>
  </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formField">&nbsp;</td>
                <td  align="center" bgcolor="#E5E5E5" class="formField3"><strong>Product</strong></td>
                <td  align="center" bgcolor="#E5E5E5" class="formField3"><strong>Material</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Index</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Coating</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formField3"><strong>Price</strong></td>
              </tr>
              <tr>
                <td align="center" class="formField3"><?php echo $listItem[eye] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_name] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_material] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_index] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[order_product_coating] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[re_sphere] ?></td>
                <td align="center" class="formField3"><?php echo $listItem[re_cyl] ?></td>
                <td align="right" valign="top" class="formField3"><b>$<?php echo $listItem[order_product_price] ?></b></td>
              </tr>
