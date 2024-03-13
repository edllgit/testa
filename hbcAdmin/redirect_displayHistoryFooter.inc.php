<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
  <tr >
    <td width="124" align="left">Total Quantities</td>
    <td width="400" align="left"><b>Prescription:<?php echo $prescrQuantity;?></b></td>
    <td width="100" align="right">&nbsp;</td>
  </tr>
</table>

  <table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
			  
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
                <td width="524" align="left" bgcolor="#666666">&nbsp;</td>
                <td width="443" align="right" valign="middle" bgcolor="#666666" class="total">&nbsp;</td>
              </tr>
</table>
