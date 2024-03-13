<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
  <tr >
    <td align="left" class="formCellNosides">Total Quantites</td>
    <td align="left" class="formCellNosidesRA">Stock by Tray:<?php print $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;Stock
      by Bulk:<?php print $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;Prescription:<?php print $prescrQuantity;?></td>
  </tr>
</table>

  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
  
     <?php
		if ($extra_product_price!=0){
			  
		print "<tr ><td align=\"left\"  class=\"formCellNosides\">Additional Item:&nbsp;".$extra_product."</td>";
		print "<td align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>".$extra_product_price.'</b></td></tr>';
			  
		}?>
            
               
			<?php  //Display the costs only if account type is normal ?>
			<?php  
			if ($_SESSION['account_type']=='normal') {
			 ?>
			  <tr >
			  <td align="left" class="formCellNosides">Shipping: <?php print $order_shipping_method; ?></td>
			  <td align="right" valign="middle" class="formCellNosidesRA"><?php print $order_shipping_cost; ?></td>
              </tr>
			  
			  
			  
			     <?php if ($additional_dsc!=0){
			    print "<tr >";
                print "<td width=\"524\" align=\"left\"  class=\"formCellNosides\">Additional Discount (".$discount_type.$additional_dsc.")</td>";
                print "<td colspan=\"2\" align=\"right\" valign=\"middle\" class=\"formCellNosidesRA\"><b>-";
				if ($discount_type=="$"){
					$totalDiscount=money_format('%.2n',$additional_dsc);
					}
				else if ($discount_type=="%"){
					$totalDiscount=money_format('%.2n',$totalPriceDsc*($additional_dsc/100));
				}
				print $totalDiscount; 
				print "</b></td></tr>";
			  
			  }?>
              <tr >
                <td width="524" align="left" class="Subheader">Order Total with All Discounts</td>
			
                <td width="100" align="right" valign="middle" class="total"><b>$
				
				
				
				<?php 
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc+$order_shipping_cost-$totalDiscount+$extra_product_price);
			print $totalPriceDsc." ".$currency; ?></b></td>
              </tr>
			  <?php } ?>
  </table>
