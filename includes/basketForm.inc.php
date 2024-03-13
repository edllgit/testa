<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
  <tr >
    <td align="left" class="formCellNosides"><?php echo $lbl_totalquan_txt;?></td>
    <td align="left" class="formCellNosidesRA"><?php echo $lbl_stockitemstray_txt;?>: <?php print $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;<?php echo $lbl_stockitemsbulk_txt;?>:<?php print $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;<?php echo $lbl_slct_ordrtype2;?>:<?php print $prescrQuantity;?>&nbsp;&nbsp;&nbsp;<?php echo ($mylang == "lang_french") ? "Monture de stock": "Stock Frames"; ?>:<?php echo $stockFrametraycount;?> </td>
  </tr>
</table>
<form id="basketform" name="basketform" method="post" action="order.php"  onSubmit="return validate(this)">
  <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

<input name="mypkey" type="hidden" id="mypkey" value="<?php print $_SESSION['PrescrData']['mypkey'];?>" />
              <tr >
                <td width="80" align="right" bgcolor="#D7E1FF" class="formCellNosidesRA"><?php echo $lbl_ponumber_txt;?> </td>
                <td width="159" align="left" bgcolor="#D7E1FF" class="formCellNosides"><input name="po_num" type="text" class="formText" id="po_num" size="12" /></td>
                <td width="285" align="center" bgcolor="#D7E1FF" class="formCellNosidesCenter">
				
				<?php 
				
				$stock_quantity=$totalBulkQuant+$totalTrayQuant;
				
				$user_id=$_SESSION["sessionUser_Id"];
				
				$query="SELECT ship_chg_stock,ship_chg_rx, currency FROM accounts,labs WHERE accounts.user_id='$user_id' AND accounts.main_lab=labs.primary_key";//GET SHIPPING COSTS
				$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
				$labItem=mysql_fetch_array($result);
				
				if ($prescrQuantity>0)
						$totalShippingRX=$labItem[ship_chg_rx];
						
				if ($stock_quantity>0)
					{	
							
							
					$queryOrderTotal = "SELECT * FROM orders WHERE user_id = '$user_id' AND order_num = -1 AND order_product_type <> 'exclusive'";
					$resultOrderTotal    = mysql_query($queryOrderTotal)		or die  ('I cannot select items 2 because: ' . mysql_error());
					$countResult         = mysql_num_rows($resultOrderTotal);
					$StockOrderValue = 0;
					if ($countResult  > 0){
						while ($DataOrderTotal = mysql_fetch_array($resultOrderTotal)){
							//calculer total de la commande
							$StockOrderValue = $StockOrderValue + ($DataOrderTotal[order_quantity] * $DataOrderTotal[order_product_discount]);
							//echo '<br>	StockOrderValue =  ' . $DataOrderTotal[order_quantity] . ' X '. $DataOrderTotal[order_product_discount];	
						}	
						//50$ et + shipping free
						if ($StockOrderValue> 49.99) 
							$totalShippingStock = 0;
						else
							$totalShippingStock=$labItem[ship_chg_stock];	
					}//End if count > 0
				}//End if stock quantity > 0
				
								
						
						
						
						
				$totalShipping=$totalShippingRX+$totalShippingStock;
					
				?>
				
				</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesCenter">&nbsp;</td>
              </tr>
              
               <?php 
			switch($labItem['currency']){
			case 'CA':     $CustomerCurrency = '$';     	  break;
			case 'US':     $CustomerCurrency = '$';    		  break;
			case 'EUR':    $CustomerCurrency = "&#128;";       break;
			}
			?>
              
              <tr >
                <td colspan="3" align="left" class="Subheader"><?php echo $lbl_shopbasktotal_txt;?> </td>
                <td width="100" align="right" valign="middle" class="total"><b><?php echo $CustomerCurrency;?>
				<?php 
				$totalPrice=money_format('%.2n',$totalPrice);
			print $totalPrice; ?></b></td>
              </tr>
			  
			  <?php 
			  
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc);
			  if ($totalPriceDsc!=$totalPrice){
			  
			  echo "<tr ><td colspan=\"3\" align=\"left\"class=\"formCellNosides\">Basket Total with Your Account Discount </td>
                <td width=\"100\" align=\"right\" valign=\"middle\" class=\"total\"><b>" . $CustomerCurrency;
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
		   print $cont_red;?>', '_top')"/>&nbsp;
		   
		   <input name="Submit" type="submit" value="<?php echo $btn_proceedcheck_txt;?>" />
		   
		   
			<input name="stock_quantity" type="hidden" id="stock_quantity" value="<?php print $stock_quantity;?>" />
            <input name="frame_stock_quantity" type="hidden" id="frame_stock_quantity" value="<?php print $stockFrametraycount;?>" />
		   
                  <input name="totalPrice" type="hidden" value="<?php print $totalPrice;?>" />
				  <input name="totalPriceDsc" type="hidden" value="<?php print $totalPriceDsc;?>" />
				  <input name="totalShippingRX" type="hidden" value="<?php print $totalShippingRX;?>" />
				  <input name="totalShippingStock" type="hidden" value="<?php print $totalShippingStock;?>" />
				  <input name="totalShipping" type="hidden" value="<?php print $totalShipping;?>" /></div>
</form>
