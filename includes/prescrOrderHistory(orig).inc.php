<link href="../dl.css" rel="stylesheet" type="text/css" />
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product - <?php print $listItem[order_product_name] ?></td>
                <td bgcolor="#D7E1FF" class="tableSubHead">&nbsp;</td>
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >&nbsp;</td>
  </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Coating:</strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_coating] ?></td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong>Photochromatic:</strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_photo] ?></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>Polarized:</strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_polar] ?></td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Patient:</b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_patient_first] ?>&nbsp;<?php print $listItem[order_patient_last] ?><b>&nbsp;&nbsp;</b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Ref
                Number: </b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><b><?php print $listItem[patient_ref_num] ?></b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>Salesperson
                ID : </b></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[salesperson_id] ?></td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Sphere</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Cylinder</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Axis</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Addition</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>Prism</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>Quantity</strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">R.E.</td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_axis] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_add] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;". $listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td rowspan="7" align="center" valign="top" class="formCellNosidesCenter"><?php print $listItem[order_quantity] ?></td>
                <td rowspan="7" align="right" valign="top" class="formCellNosidesRA"><b>$
				<?php print $listItem[order_product_price];?></b><?php 
				if ($over_range!=0){print "<br> Over range: ";
				print $over_range;}?><br><?php if ($listItem[extra_product_price]!=0){print " Extra item: ";
				print $listItem[extra_product_price]."<br/>";}?><?php 
				if ($listItem[coupon_dsc]!=0){print "Coupon Discount: -";
				print $listItem[coupon_dsc]."<br/>";}?>Subtotal: <?php print $itemSubtotal;?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong>Dist.
                    PD:</strong>  <?php print $listItem[re_pd] ?>&nbsp;&nbsp;&nbsp;<strong>Near
                    PD:</strong>  <?php print $listItem[re_pd_near] ?>&nbsp;&nbsp;&nbsp;<strong>Height:</strong> <?php print $listItem[re_height] ?></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  L.E.</td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_axis] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_add] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;". $listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
  </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>Dist.
                    PD: </strong><?php print $listItem[le_pd] ?>&nbsp;&nbsp;&nbsp;<strong>Near
                PD: </strong><?php print $listItem[le_pd_near] ?>&nbsp;&nbsp;&nbsp;<strong>Height:</strong> <?php print $listItem[le_height] ?></td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:
                    &nbsp;&nbsp;&nbsp;Eye: A: </strong><?php print $listItem[frame_a] ?>&nbsp;&nbsp;&nbsp;<strong>B: </strong><?php print $listItem[frame_b] ?><strong>&nbsp;&nbsp;&nbsp;ED: </strong><?php print $listItem[frame_ed] ?><strong>&nbsp;&nbsp;&nbsp;DBL: </strong><?php print $listItem[frame_dbl] ?>&nbsp;&nbsp;&nbsp;<strong>Type:</strong> <?php print $listItem[frame_type] ?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Special
                  Instructions:</b> <?php print $listItem[special_instructions] ?></td>
              </tr>
            </table>
