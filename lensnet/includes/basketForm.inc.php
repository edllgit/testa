<table width="765px" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
  <tr >
    <td align="left" class="formCellNosides"><?php echo $lbl_totalquan_txt;?></td>
    <td align="left" class="formCellNosidesRA"><?php echo $lbl_stockitemstray_txt;?>: <?php print $totalTrayQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_stockitemsbulk_txt;?>:<?php print $totalBulkQuant;?>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $lbl_slct_ordrtype2;?>:<?php print $prescrQuantity;?></td>
  </tr>
</table>
<form id="basketform" name="basketform" method="post" action="order.php"  onSubmit="return validate(this)">
  <table width="765" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

<input name="mypkey" type="hidden" id="mypkey" value="<?php print $_SESSION['PrescrData']['mypkey'];?>" />
              <tr >
                <td width="80" align="right" bgcolor="#D7E1FF" class="formCellNosidesRA"><?php echo $lbl_ponumber_txt;?> </td>
                <td width="159" align="left" bgcolor="#D7E1FF" class="formCellNosides"><input name="po_num" type="text" class="formText" id="po_num" size="12" /></td>
                <td width="285" align="center" bgcolor="#D7E1FF" class="formCellNosidesCenter">
				
				<?php 
				
				$stock_quantity=$totalBulkQuant+$totalTrayQuant;
				
				$user_id=$_SESSION["sessionUser_Id"];
				
				$query="SELECT ship_chg_stock,ship_chg_rx FROM accounts,labs WHERE accounts.user_id='$user_id' AND accounts.main_lab=labs.primary_key";//GET SHIPPING COSTS
				$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
				$labItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
				
				
				
				if ($prescrQuantity>0)
				{
				$queryOrder="SELECT COUNT(distinct primary_key) as NbORder FROM orders WHERE order_num = -1 and user_id ='$user_id'  ";//GET NUMBER OF ORDERS
				$resultOrder=mysqli_query($con,$queryOrder)		or die  ('I cannot select items because: ' . mysqli_error($con));
				$DataOrder=mysqli_fetch_array($resultOrder,MYSQLI_ASSOC);
				//echo '<br>Nb ORder: ' . $DataOrder[NbORder];
				$totalShippingRX= $DataOrder[NbORder] * $labItem[ship_chg_rx];//Multipli� par le nombre de commande
				//$totalShippingRX=  $labItem[ship_chg_rx];//Multipli� par le nombre de commande
				}		
				if ($stock_quantity>0)
						$totalShippingStock=$labItem[ship_chg_stock];
				$totalShipping=$totalShippingRX+$totalShippingStock;
					
				?>
				
				</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesCenter">&nbsp;</td>
              </tr>
              <tr >
                <td colspan="3" align="left" class="Subheader"><?php echo $lbl_shopbasktotal_txt;?> </td>
                <td width="100" align="right" valign="middle" class="total"><b><?php echo  $CustomerCurrency ;?>
				<?php 
				$totalPrice=money_format('%.2n',$totalPrice);
			print $totalPrice; ?></b></td>
              </tr>
			  
			  <?php 
			  
			  	$totalPriceDsc=money_format('%.2n',$totalPriceDsc);
				if ($entry_fee <> 0){
					$totalPriceDsc = $totalPriceDsc-$entry_fee;
					$totalPriceDsc=money_format('%.2n',$totalPriceDsc);
				}
			  
				$totalPriceDsc=money_format('%.2n',$totalPriceDsc);
			  if ($totalPriceDsc!=$totalPrice){
			  
			  print "<tr ><td colspan=\"3\" align=\"left\"class=\"formCellNosides\">Basket Total with Your Account Discount </td>
                <td width=\"100\" align=\"right\" valign=\"middle\" class=\"total\"><b>" .  $CustomerCurrency ;
			print $totalPriceDsc; 
			print "</b></td></tr>";
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
		   
                  <input name="totalPrice" type="hidden" value="<?php print $totalPrice;?>" />
				  <input name="totalPriceDsc" type="hidden" value="<?php print $totalPriceDsc;?>" />
				  <input name="totalShippingRX" type="hidden" value="<?php print $totalShippingRX;?>" />
				  <input name="totalShippingStock" type="hidden" value="<?php print $totalShippingStock;?>" />
				  <input name="totalShipping" type="hidden" value="<?php print $totalShipping;?>" /></div>
</form>