
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead">
				<?php  if ($mylang == 'lang_french') {  ?>
                       R&eacute;f&eacute;rence
                <?php  }else{ ?>
                       Reference
                <?php  } ?>  - <?php echo $listItem[tray_num] ?></td>
                <form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="basket.php">
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >
                  
                    <input name="Submit" type="submit" class="formText"value="<?php echo $btn_remove_txt;?>" />
                    <input name="delete_frame_tray" type="hidden" value="true" />
<input name="tray_num" type="hidden" value="<?php echo $listItem[tray_num]?>" />                  </td>     </form>   
  </tr>
              <tr >
                <td width="220" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo ($mylang == "lang_french") ? "Monture": "Frame"; ?></strong></td>
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td width="150" align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo ($mylang == "lang_french") ? "Type de monture": "Frame Type"; ?></strong></td>
                <td align="center"  bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo ($mylang == "lang_french") ? "Quantit&eacute;": "Quantity"; ?></strong></td>
                <td align="center"  bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php echo ($mylang == "lang_french") ? "Prix unitaire": "Unit Price"; ?></strong></td>
                <td colspan="3" align="center"  bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php echo ($mylang == "lang_french") ? "Sous-total": "Sub-total"; ?></strong></td>
              </tr>
              <tr>
                <td width="200" align="center" class="formCellNosides"><?php echo $listItem[order_product_name] ?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td width="150" align="center" class="formCellNosides"><?php echo $listItem[order_product_material] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[order_quantity] ?></td>
                <td align="right" valign="top" class="formCellNosidesRA"><b><?php echo $lbl_moneysym_txt;?><?php echo $listItem[order_product_price]; ?></b></td>
                <td colspan="3" align="right" valign="top" class="formCellNosidesRA"><b><?php echo $lbl_moneysym_txt;?><?php echo $listItem[order_total]; ?></b></td>
              </tr>
