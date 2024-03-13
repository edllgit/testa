
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
         <tr >
                <td colspan="5" bgcolor="#555555"><font color="#FFFFFF">Product - <?php echo $listItem[order_product_name] ?></font></td>
                <td width="76" bgcolor="#555555">&nbsp;</td>
				<form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="editStockBulkOrder.php"><td width="128" bgcolor="#555555" class="formCellNosidesRA"  align="right"><input name="Submit" type=
				<?php if ($listItem[order_status]=="filled"){
					echo "\"hidden\"";}
					else {
					echo "\"submit\"";}?>
				class="formField3" value="Edit" /><input name="pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" /></td></form></tr>
              <tr >
                <td width="77" bgcolor="#E5E5E5"><strong>Material</strong></td>
                <td width="53" bgcolor="#E5E5E5"><strong>Index</strong></td>
                <td width="74" align="center" bgcolor="#E5E5E5"><strong>Coating</strong></td>
                <td width="69" align="center" bgcolor="#E5E5E5"><strong>Sphere</strong></td>
                <td width="129" align="center" bgcolor="#E5E5E5"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong>Quantity</strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong>Price</strong></td>
              </tr>
              <tr>
                <td><?php echo $listItem[order_product_material] ?></td>
                <td align="right"><?php echo $listItem[order_product_index] ?></td>
                <td align="center"><?php echo $listItem[order_product_coating] ?></td>
                <td align="center"><?php echo $listItem[re_sphere] ?></td>
                <td align="center"><?php echo $listItem[re_cyl] ?></td>
                <td align="center" valign="top"><?php echo $listItem[order_quantity] ?></td>
                <td align="right" valign="top"><b>$<?php echo $listItem[order_product_price] ?></b><br>Subtotal: <b><?php echo $itemSubtotal;?></b></td>
              </tr>
            </table>
