<?php 
				$EstShipDateQuery="SELECT * from est_ship_date WHERE order_num= $listItem[order_num]";
				$EstShipDateResult=mysqli_query($con,$EstShipDateQuery)					or die  ('I cannot select items because: ' . mysqli_error($con).$EstShipDateQuery);
					
				$DateEstime=mysqli_fetch_array($EstShipDateResult,MYSQLI_ASSOC);
				$LadateEstime = $DateEstime['est_ship_date'];
?>

<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#fbe6d8" class="tableSubHead">
            <?php  if ($mylang == 'lang_french')
			{
			echo 'Produit - ';
			}else{
			echo 'Product - ';
			} ?>
                
                
                <b><?php echo $listItem[order_product_name] ?></b><br /><br />
                 <?php  if ($mylang == 'lang_french')
			{
			echo "Date estim&eacute; de livraison: ";
			}else{
			echo 'Estimated delivery date: ';
			} ?>
                
                 <b><?php echo $LadateEstime ?> </b>
             </td>
               
                 
             
                <td bgcolor="#fbe6d8" class="tableSubHead">&nbsp;</td>
				
				<?php				
				$ProductQuery="SELECT order_product_price, order_product_name,currency FROM orders WHERE order_num='$listItem[order_num]'";
				$ProductResult=mysqli_query($con,$ProductQuery) or die  ('I cannot select items because: ' . mysqli_error($con).$ProductQuery);
				$productcount=mysqli_num_rows($ProductResult);
				
				$disableFlag=FALSE;
				
				if ($productcount==0){
					$disableFlag=TRUE;
				}
				else{
					$orderItem=mysqli_fetch_array($ProductResult,MYSQLI_ASSOC);
			
					$PriceQuery="SELECT price,price_can,price_eur,e_lab_us_price,e_lab_can_price FROM exclusive WHERE product_name='$orderItem[order_product_name]'";
					$PriceResult=mysqli_query($con,$PriceQuery);
					$PriceItem=mysqli_fetch_array($PriceResult,MYSQLI_ASSOC);

					if ($_SESSION["sessionUserData"]["currency"]=="US"){
						$ProdPrice=$PriceItem[price];}
					else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
						$ProdPrice=$PriceItem[price_can];}
					else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
						$ProdPrice=$PriceItem[price_eur];}
						
					if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
						$ProdPrice=$PriceItem[e_lab_us_price];}
					else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
						$ProdPrice=$PriceItem[e_lab_can_price];}
						
				if ($ProdPrice!=$orderItem[order_product_price]){
					$disableFlag=TRUE;}
				}
				?>

          <?php //Display the  re-Order button only if the customer use a normal account (not restricted) ?>
		     <?php 
			 if ($_SESSION['account_type']=='normal'){
			 ?>

			 <form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="reorder.php">
                <td bgcolor="#fbe6d8"  class="formCellNosidesRA" >
                  
            <?php /*?> <?php  if ($mylang == 'lang_french'){ ?>
			<input name="Submit" type="submit" class="formText" value="RE-COMMANDER"  <?php if ($disableFlag) echo "disabled='disabled'";?> />
			<?php }else{ ?>
			<input name="Submit" type="submit" class="formText" value="RE-ORDER"  <?php if ($disableFlag) echo "disabled='disabled'";?> />
			<?php } ?>
                    <?php */?>
                    
                    
<input name="pkey" type="hidden" value="<?php echo $echo[primary_key]?>" />
<input name="order_num" type="hidden" value="<?php echo $listItem[order_num]?>" /></td></form> 
		 <?php 
			}
			 ?>

  </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Traitement:';
				}else{
				echo 'Coating:';
				}
				 ?>
                </strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $listItem[order_product_coating] ?></td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong>
                <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Photochromatic:';
				}else{
				echo 'Transition:';
				}
				 ?>
                
                </strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides">
				<?php if(( $listItem[order_product_photo] == 'None') && ($mylang == 'lang_french'))
				{
				echo 'Non';
				}else{
				echo $listItem[order_product_photo];
				} ?></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong>
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Polaris&eacute;:';
				}else{
				echo 'Polarized:';
				}
				 ?>
                </strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides">
				<?php  if(( $listItem[order_product_polar] == 'None')  && ($mylang == 'lang_french'))
				{
				echo 'Non';
				}else{
				echo $listItem[order_product_polar];
				} ?>
                
			
                
                </td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b> 
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Client';
				}else{
				echo 'Customer';
				}
				 ?>:</b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $listItem[order_patient_first] ?>&nbsp;<?php echo $listItem[order_patient_last] ?><b>&nbsp;&nbsp;</b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>
                <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Ref Client:';
				}else{
				echo 'Patient Reference';
				}
				 ?>
                 </b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><b><?php echo $listItem[patient_ref_num] ?></b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><b>
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo ' Vendeur ID :';
				}else{
				echo 'Salesperson ID';
				}
				 ?>
               
                
                 </b></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $listItem[salesperson_id] ?></td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo "Sph&egrave;re";
				}else{
				echo 'Sphere';
				}
				 ?>
                
                </strong></td>
                
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>
                <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Cylindre';
				}else{
				echo 'Cylinder';
				}
				 ?>
                
                </strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong> <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Axes';
				}else{
				echo 'Axis';
				}
				 ?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>
               <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Addition';
				}else{
				echo 'Addition';
				}
				 ?>
                </strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong>
                <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Prisme';
				}else{
				echo 'Prism';
				}
				 ?>
                </strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong>
                 <?php 
				if ($mylang == 'lang_french')
				{
				echo 'Quantit&eacute;';
				}else{
				echo 'Quantity';
				}
				 ?>
               
                
                </strong></td>
              
			  <?php //Display the price column only if account_type is normal  ?>
			    <?php if ($_SESSION['account_type']=='normal') { ?>
			    <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php 
				if ($mylang == 'lang_french')
				{
				echo 'Prix';
				}else{
				echo 'Price';
				}
				 ?></strong></td>
				<?php } ?>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">  
				<?php      if ($mylang == 'lang_french')
			{
			echo 'O.D.';
			}else{
			echo 'R.E.';
			} ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_axis] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_add] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;". $listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td rowspan="8" align="center" valign="top" class="formCellNosidesCenter"><?php echo $listItem[order_quantity] ?></td>
              
				<?php if ($_SESSION['account_type']=='normal')
				{
				?>
				
				<td rowspan="8" align="right" valign="top" class="formCellNosidesRA"><b>
				
				<?php	
				if ($mylang == 'lang_french'){
					echo  $listItem[order_product_price] . '$';
					}else{
					echo  '$'. $listItem[order_product_price];
					}
				
				?></b>
				
				<?php 
				if ($over_range!=0){echo "<br> Over range: ";
				echo $over_range;}?><?php 
				if ($e_total_price!=0){
				
					if ($mylang == 'lang_french'){
					echo "<br>". $e_products_string . '$';
					}else{
					//echo "<br>". '$'. $e_products_string;
					echo "<br>".  $e_products_string . '$';
					}
				
				}?>
				  <br>
				
				<?php if ($listItem[extra_product_price]!=0){echo " Extra item: ";
				echo $listItem[extra_product_price]."<br/>";}?><?php 
				if ($listItem[coupon_dsc]!=0){echo "Coupon Discount: -";
				echo $listItem[coupon_dsc]."<br/>";}?>
                
                   <?php  
			 
			 $queryTopUrgent = "SELECT * FROM extra_product_orders WHERE category = 'Top urgent' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysqli_query($con,$queryTopUrgent)	or die ("Could not select items");
			 $countTopUrgent=mysqli_num_rows($resultTopUrgent);
			 $DataUrgent=mysqli_fetch_array($resultTopUrgent,MYSQLI_ASSOC);
			if  ($countTopUrgent > 0)
			echo "<br>Top Urgent:<b>".  $DataUrgent['price']. "</b><br>";
			 ?>
                

              <?php 	if  ($mylang == 'lang_french'){
			  //echo 'Sous-total';
			  }else{
  			  //echo 'Subtotal';
			  }?></td>
				
				<?php
				}
				?>

				
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong>
            <?php  if ($mylang == 'lang_french')
			{
			echo 'Dist. PD';
			}else{
			echo 'PD';
			} ?>
           </strong><?php echo $listItem[re_pd] ?><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
           <?php  if ($mylang == 'lang_french')
			{
			echo 'Hauteur';
			}else{
			echo 'Height';
			} ?>
             : </strong> <?php echo $listItem[re_height] ?></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
             <?php      if ($mylang == 'lang_french')
			{
			echo 'O.G.';
			}else{
			echo 'L.E.';
			} ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[le_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[le_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[le_axis] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[le_add] ?></td>
                <td align="center" class="formCellNosides"><?php echo $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;". $listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
  </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>
                <?php  if ($mylang == 'lang_french')
			{
			echo 'Dist. PD';
			}else{
			echo 'PD';
			} ?>
                    </strong><?php echo $listItem[le_pd] ?>&nbsp;&nbsp;&nbsp;<strong> </strong><?php echo $listItem[le_pd_near] ?>&nbsp;&nbsp;&nbsp;<strong> 
            
              <?php      if ($mylang == 'lang_french')
			{
			echo 'Hauteur';
			}else{
			echo 'Height';
			} ?>:</strong> <?php echo $listItem[le_height] ?></td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>
               <?php  if ($mylang == 'lang_french')
			{
			echo 'MONTURE';
			}else{
			echo 'FRAME';
			}?>
               :</strong>
                  <?php 
				if ($e_order_string_frame!=""){
					echo $e_order_string_frame;}
				else{
					echo $e_order_string_edging;}
				?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>
				<?php  if ($mylang == 'lang_french')
			{
			echo 'AUTRES';
			}else{
			echo 'OTHER';
			}?>:</strong>
                  <?php 
					echo $e_order_string_engraving;
					if (($e_order_string_tint=='Gradient') && ($mylang =='lang_french'))
					echo 'Dégradé';
				?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
                <?php      if ($mylang == 'lang_french')
			{
			echo 'Instructions Sp&eacute;ciales:';
			}else{
			echo 'Special instructions:';
			} ?>
                
                
                 </b> <?php echo $listItem[special_instructions] ?></td>
              </tr>
            </table>