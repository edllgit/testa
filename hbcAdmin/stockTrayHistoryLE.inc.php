<tr>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[eye] ?></td>
                <td align="center"  valign="top" class="formField3"><?php echo $listItem[order_product_name] ?></td>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[order_product_material] ?></td>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[order_product_index] ?></td>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[order_product_coating] ?></td>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[re_sphere] ?></td>
                <td align="center" valign="top" class="formField3"><?php echo $listItem[re_cyl] ?></td>
                <td align="right" valign="top" class="formField3"><b>$<?php echo $listItem[order_product_price] ?></b><br>Subtotal: <?php echo $itemSubtotal;?><input name="le_pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" /></td>
              </tr></form>
            </table>
