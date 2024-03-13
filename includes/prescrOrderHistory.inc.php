<?php 
				$EstShipDateQuery="SELECT * from est_ship_date WHERE order_num= $listItem[order_num]";
				$EstShipDateResult=mysql_query($EstShipDateQuery)
					or die  ('I cannot select items because: ' . mysql_error().$EstShipDateQuery);
					
				$DateEstime=mysql_fetch_array($EstShipDateResult);
				$LadateEstime = $DateEstime['est_ship_date'];
?>

<link href="../dl.css" rel="stylesheet" type="text/css" />
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D7E1FF" class="tableSubHead">Product - <b><?php print $listItem[order_product_name] ?></b><br /><br /> Estimate shipping date: <b><?php print $LadateEstime ?> </b>
                <?php if ($listItem[warranty] != '') {  ?>
                &nbsp;&nbsp;Lens net club Warranty:
             <?php print $listItem[warranty]; echo ' year(s)';} ?>
             
             </td>
               
                 
             
                <td bgcolor="#D7E1FF" class="tableSubHead">&nbsp;</td>
				
				<?php				
				$ProductQuery="SELECT order_product_price, order_product_name,currency from orders WHERE order_num='$listItem[order_num]'";
				$ProductResult=mysql_query($ProductQuery)
					or die  ('I cannot select items because: ' . mysql_error().$ProductQuery);
					
				$productcount=mysql_num_rows($ProductResult);
				
				$disableFlag=FALSE;
				
				if ($productcount==0){
					$disableFlag=TRUE;
				}
				else{
					$orderItem=mysql_fetch_array($ProductResult);
			
					$PriceQuery="SELECT price,price_can,price_eur,e_lab_us_price,e_lab_can_price from exclusive WHERE product_name='$orderItem[order_product_name]'";
					$PriceResult=mysql_query($PriceQuery);
					$PriceItem=mysql_fetch_array($PriceResult);

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

			 <form id="<?php print $listItem[order_item_number] ?>" name="<?php print $listItem[order_item_number] ?>" method="post" action="reorder.php">
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" >
                  
                   
<input name="pkey" type="hidden" value="<?php print $listItem[primary_key]?>" />
<input name="order_num" type="hidden" value="<?php print $listItem[order_num]?>" /></td></form> 
		 <?php 
			}
			 ?>

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
              
			  <?php //Display the price column only if account_type is normal  ?>
			    <?php if ($_SESSION['account_type']=='normal') { ?>
			    <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong>Price</strong></td>
				<?php } ?>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">R.E.</td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_axis] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_add] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;". $listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td rowspan="8" align="center" valign="top" class="formCellNosidesCenter"><?php print $listItem[order_quantity] ?></td>
              
				<?php if ($_SESSION['account_type']=='normal')
				{
				?>
				
				<td rowspan="8" align="right" valign="top" class="formCellNosidesRA"><b>$ 
				<?php print $listItem[order_product_price];?></b><?php 
				if ($over_range!=0){print "<br> Over range: ";
				print $over_range;}?><?php 
				if ($e_total_price!=0){print "<br>". $e_products_string;}?><br>
				
				<?php if ($listItem[extra_product_price]!=0){print " Extra item: ";
				print $listItem[extra_product_price]."<br/>";}?><?php 
				if ($listItem[coupon_dsc]!=0){print "Coupon Discount: -";
				print $listItem[coupon_dsc]."<br/>";}?>
                
                   <?php  
			 
			 $queryTopUrgent = "select * FROM extra_product_orders WHERE category = 'Top urgent' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysql_query($queryTopUrgent)	or die ("Could not select items");
			 $countTopUrgent=mysql_num_rows($resultTopUrgent);
			 $DataUrgent=mysql_fetch_array($resultTopUrgent);
			if  ($countTopUrgent > 0)
			echo "<br>Top Urgent:<b>".  $DataUrgent['price']. "</b><br>";
			   ?>
                
                
                
                
                Subtotal: <?php print $itemSubtotal;?></td>
				
				<?php
				}
				?>

				
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
				<?php if (($listItem[PT] !="0")&&($listItem[PA]!="0")&&($listItem[vertex]!="0")){?>
                   <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong><?php print $listItem[PT] ?>&nbsp;&nbsp;&nbsp;<strong>PA: </strong><?php print $listItem[PA] ?>&nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> <?php print $listItem[vertex] ?></td>
                </tr>
                <?php }?>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>FRAME:</strong>
                  <?php 
				if ($e_order_string_frame!=""){
					print $e_order_string_frame;}
				else{
					print $e_order_string_edging;}
				?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>OTHER:</strong>
                  <?php 
					print $e_order_string_engraving.$e_order_string_tint;
				?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>Special
                  Instructions:</b> <?php print $listItem[special_instructions] ?></td>
              </tr>
            </table>
