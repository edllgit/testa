<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
  <tr >
    <td width="124" align="left"><?php echo $adm_totalquant_txt; ?></td>
    <td width="400" align="right"><b><?php echo $adm_stockbytray_txt; ?><?php echo $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $adm_stockbybulk_txt; ?><?php echo $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $adm_prescription_txt; ?><?php echo $prescrQuantity;?></b></td>
    <td width="100" align="right">&nbsp;</td>
  </tr>
</table>

  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
           
			       
          
              
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
                <td width="524" align="left" bgcolor="#E5E5E5"><b></b></td>
                <td colspan="2" align="right" valign="middle" bgcolor="#E5E5E5" class="total"><b>
                  <b><?php  ?> </b>
  
                </b></td>
              </tr>
              <tr >
                <td align="left" bgcolor="#666666">&nbsp;</td>
                <td colspan="2" align="right" valign="middle" bgcolor="#666666" class="total">&nbsp;</td>
              </tr>
</table>
