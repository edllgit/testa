
<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="5" bgcolor="#D5EEF7" class="tableSubHead">Product - <?php echo $listItem[order_product_name] ?></td>
                <td width="76" bgcolor="#D5EEF7" class="tableSubHead">&nbsp;</td>
                <td width="128" bgcolor="#D5EEF7"  class="formCellNosidesRA" >&nbsp;
                   </td>    
  </tr>
              <tr >
                <td width="77" bgcolor="#E5E5E5" class="formCellNosides"><strong>Material</strong></td>
                <td width="53" bgcolor="#E5E5E5" class="formCellNosides"><strong>Index</strong></td>
                <td width="74" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Coating</strong></td>
                <td width="69" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td width="129" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantity</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
              </tr>
              <tr>
                <td align="right" class="formCellNosides"><?php echo $listItem[order_product_material] ?></td>
                <td align="right" class="formCellNosides"><?php echo $listItem[order_product_index] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[order_product_coating] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_cyl] ?></td>
                <td align="center" valign="top" class="formCellNosidesCenter"><?php echo $listItem[order_quantity] ?></td>
                <td align="right" valign="top" class="formCellNosidesRA"><b>$<?php echo $listItem[order_product_price] ?></b><br>Subtotal: <?php echo $itemSubtotal;?></td>
              </tr>
            </table>
