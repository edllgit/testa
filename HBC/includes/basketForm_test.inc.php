<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
  <tr >
    <td align="left" class="formCellNosides"><?php echo $lbl_totalquan_txt;?></td>
    <td align="left" class="formCellNosidesRA"><?php echo $lbl_stockitemstray_txt;?>: <?php echo $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_stockitemsbulk_txt;?>:<?php echo $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_slct_ordrtype2;?>:<?php echo $prescrQuantity;?></td>
  </tr>
</table>
<form id="basketform" name="basketform" method="post" action="order_test.php"  onSubmit="return validate(this)">
  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

<input name="mypkey" type="hidden" id="mypkey" value="<?php echo $_SESSION['PrescrData']['mypkey'];?>" />
              <tr >
                <td width="80" align="right" bgcolor="#D5EEF7" class="formCellNosidesRA"><?php echo $lbl_ponumber_txt;?> </td>
                <td width="159" align="left" bgcolor="#D5EEF7" class="formCellNosides"><input name="po_num" type="text" class="formText" id="po_num" size="12" /></td>
                <td width="285" align="center" bgcolor="#D5EEF7" class="formCellNosidesCenter">
				
				<?php 
				
				$stock_quantity=$totalBulkQuant+$totalTrayQuant;
				
				$user_id=$_SESSION["sessionUser_Id"];
				
				$query="SELECT ship_chg_stock,ship_chg_rx FROM accounts,labs WHERE accounts.user_id='$user_id' AND accounts.main_lab=labs.primary_key";//GET SHIPPING COSTS
				$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
				$labItem=mysql_fetch_array($result);
				
				if ($prescrQuantity>0)
						$totalShippingRX=$labItem[ship_chg_rx];
						
				if ($stock_quantity>0)
						$totalShippingStock=$labItem[ship_chg_stock];
				$totalShipping=$totalShippingRX+$totalShippingStock;
					
				?>
				
				</td>
                <td align="center" bgcolor="#D5EEF7" class="formCellNosidesCenter">&nbsp;</td>
              </tr>
              <tr >
                <td colspan="3" align="left" class="Subheader"><?php echo $lbl_shopbasktotal_txt;?> </td>
                <td width="100" align="right" valign="middle" class="total"><b><?php echo $lbl_moneysym_txt;?>
				<?php 
				$totalPrice=money_format('%.2n',$totalPrice);
			echo $totalPrice; ?></b></td>
              </tr>
			  
			  <?php 
			  
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc);
			  if ($totalPriceDsc!=$totalPrice){
			  
			  echo "<tr ><td colspan=\"3\" align=\"left\"class=\"formCellNosides\">Basket Total with Your Account Discount </td>
                <td width=\"100\" align=\"right\" valign=\"middle\" class=\"total\"><b>$";
			echo $totalPriceDsc; 
			echo "</b></td></tr>";
			  }
			  ?>
  </table>
		   <div align="center" style="margin:11px">
		   
		   <input name="back" type="button" value="<?php echo $btn_contshop_txt;?>"  onclick="window.open('<?php 
		   if ($_POST[continue_redirect]==""){
		   $cont_red="prescription.php";
		   }
		   else{
		    $cont_red=$_POST[continue_redirect];
		   }
		   echo $cont_red;?>', '_top')"/>&nbsp;
		   
		   <input name="Submit" type="submit" value="<?php echo $btn_proceedcheck_txt;?>" />
		   
		   
			<input name="stock_quantity" type="hidden" id="stock_quantity" value="<?php echo $stock_quantity;?>" />
		   
                  <input name="totalPrice" type="hidden" value="<?php echo $totalPrice;?>" />
				  <input name="totalPriceDsc" type="hidden" value="<?php echo $totalPriceDsc;?>" />
				  <input name="totalShippingRX" type="hidden" value="<?php echo $totalShippingRX;?>" />
				  <input name="totalShippingStock" type="hidden" value="<?php echo $totalShippingStock;?>" />
				  <input name="totalShipping" type="hidden" value="<?php echo $totalShipping;?>" /></div>
</form>
