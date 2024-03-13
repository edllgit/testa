
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead"><?php echo $lbl_trayref2_txt;?> - <?php print $listItem[tray_num] ?></td>
            
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_productname_txt_stock;?></strong></td>
                <td  align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_material_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_index_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_coating_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_sphere_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_cylinder_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php echo $lbl_price_txt;?></strong></td>
              </tr>
              <tr>
                <td align="right" class="formCellNosides"><?php print $listItem[eye] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[order_product_name] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[order_product_material] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[order_product_index] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[order_product_coating] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_cyl] ?></td>
                <td align="right" valign="top" class="formCellNosidesRA"><b><?php echo $lbl_moneysym_txt;?><?php print $listItem[order_product_price] ?></b></td>
              </tr>
