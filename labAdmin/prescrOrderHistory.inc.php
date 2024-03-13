<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0"  class="formField3">
         <tr>
                <td colspan="6" bgcolor="#555555"><font color="#FFFFFF"><?php echo $adm_product_txt; ?> - <?php echo $listItem[order_product_name] ?></font></td>
                <td align="right" bgcolor="#555555"><form id="<?php echo $listItem[order_item_number] ?>" 
                name="<?php echo $listItem[order_item_number] ?>" method="post" action="re-doV3.php" style="margin: 0px; padding: 0px;">
               
               
              <?php  
			  //1- decouvrir le order from de la commande
			  $EstunPackage = false;
			  $queryOF = "SELECT order_from, user_id FROM orders WHERE order_num = $listItem[order_num]";
			  $resultOF=mysqli_query($con,$queryOF)			or die  ('I cannot select items because: ' . mysqli_error($con));
		 	  $DataOF=mysqli_fetch_array($resultOF,MYSQLI_ASSOC);
			  $OrderFrom = $DataOF[order_from];
			  $leUserID  =  $DataOF[user_id];
			
			$queryMirror  	 =  "SELECT * FROM extra_product_orders WHERE category='Mirror' and order_num =".  $listItem[order_num];
			$resultMirror 	 = mysqli_query($con,$queryMirror)			or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataMirror   	 = mysqli_fetch_array($resultMirror,MYSQLI_ASSOC);
			$NbrResultMirror = mysqli_num_rows($resultMirror);
			if ($NbrResultMirror > 0)
			$DetailMirror = 'Mirror:  ' . $DataMirror[tint_color];
			 			  
			  
			  if (($OrderFrom =='ifcclub') || ($OrderFrom =='ifcclubca')){
				  $queryPackage  = "SELECT count(*) as EstunPackage FROM extra_product_orders WHERE category = 'Frame'";
				  $resultPackage = mysqli_query($con,$queryPackage)			or die  ('I cannot select items because: ' . mysqli_error($con));
		 		  $DataPackage   = mysqli_fetch_array($resultPackage,MYSQLI_ASSOC);
				  
				  if ($DataPackage[EstunPackage]>0)
				     $EstunPackage = true;
				  }
			  
			  
              ?>
              <?php  if (($leUserID == 'redoifc') && ($_SESSION["accessid"] <> 194)){
				  		$Disabled = " disabled ";
				  	 }else{
			  			$Disabled = "  ";
					 }//End IF
			  
			  //Empêcher reprise sur commande cancellée
			   if ($listItem[order_status]=="cancelled"){
					$Disabled = " disabled ";   
			   }

			   ?>
              
              

                    <input  <?php echo $Disabled ?>  name="Submit" type="submit" class="formText"  value="<?php
                    if ($mylang == 'lang_french' || $mylang == 'lang_France')
                    {
                    echo 'Cr&eacute;er une reprise de commande';
                    }else {
                    echo 'Create Re-Do Order';
                    }?>" />

                
				<input name="pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" />
				<input name="order_num" type="hidden" value="<?php echo $listItem[order_num]?>" /></form> </td>
           		<form id="<?php echo $listItem[order_item_number] ?>" name="<?php echo $listItem[order_item_number] ?>" method="post" action="editPrescrOrder.php"><td width="128" bgcolor="#555555" class="formCellNosidesRA"  align="right"><input name="Submit" type=
				<?php if ($listItem[order_status]=="filled"){
					echo "\"hidden\"";}
					else {
					echo "\"submit\"";}?>
				class="formField3" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';

				  ?>  value="<?php echo $btn_edit_txt; ?>"/><input name="pkey" type="hidden" value="<?php echo $listItem[primary_key]?>" /></td></form>
  </tr>
           
          
          
          <?php 
		  $queryPaidby = "SELECT * from payments WHERE order_num = " . $listItem[order_num];
		  $resultPaidBy=mysqli_query($con,$queryPaidby)	or die ("Could not select items");
		  $DataPaidBy=mysqli_fetch_array($resultPaidBy,MYSQLI_ASSOC);
		  ?>
          
          <?php  if ($DataPaidBy[pmt_type]=='credit card'){ 
		  
		  $PmtType = 'CC';
		  ?>
          
           <tr>
               <td>Paid By:</td>
               <td><?php echo $DataPaidBy[pmt_type]; ?> on <?php echo $DataPaidBy[pmt_date]; ?> </td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
               <td>&nbsp;</td>
           </tr>
           <?php  }else{
		   $PmtType = 'other';
		   } ?>
          
           
           <tr>
           <td><h3>EYE:&nbsp;&nbsp;&nbsp;<b><?php echo $listItem[eye]; ?></b></h3></td>
           </tr>
           
           
              <tr>
                <td align="right" bgcolor="#FFFFFF"><strong><?php echo $adm_coating_txt; ?></strong></td>
                <td bgcolor="#FFFFFF"><?php echo $listItem[order_product_coating] ?></td>
               
                 <td align="right" bgcolor="#FFFFFF" >
                 <?php if ($mylang == 'lang_french'){ ?>
                 <strong><?php echo 'Transition:'; ?></strong>
                 <?php }else{ ?>
                 <strong><?php echo $adm_photochr_txt; ?></strong>
                 <?php } ?>
                 </td>
                <td bgcolor="#FFFFFF"><?php echo $listItem[order_product_photo] ?></td>
                <td align="right" bgcolor="#FFFFFF"><strong><?php echo $adm_polarized_txt; ?></strong></td>
                <td colspan="3" bgcolor="#FFFFFF"><?php echo $listItem[order_product_polar] ?></td>
              </tr>
              <tr >
                <td align="right" bgcolor="#FFFFFF"><b><?php echo $adm_patient_txt; ?></b></td>
                <td bgcolor="#FFFFFF"><?php echo $listItem[order_patient_first] ?>&nbsp;<?php echo $listItem[order_patient_last] ?>&nbsp;<b>&nbsp;&nbsp;</b></td>
                
                 <?php if ($mylang == 'lang_french'){ ?>
                 <td align="right" bgcolor="#FFFFFF"><b><?php echo 'Cabaret Client:'; ?> </b></td>
                 <?php }else{ ?>
                  <td align="right" bgcolor="#FFFFFF"><b><?php echo 'Customer Tray:'; ?> </b></td>
                 <?php }?>
                
                
                <td bgcolor="#FFFFFF"><?php echo $listItem[patient_ref_num] ?></td>
                
                <?php if ($mylang == 'lang_french'){ ?>
                <td align="right" bgcolor="#FFFFFF"><b><?php echo 'Cabaret Lab:'; ?></b></td>
                <?php }else{ ?>
                <td align="right" bgcolor="#FFFFFF"><b><?php echo 'Lab Tray:'; ?></b></td>
                <?php }?>
                
                <td bgcolor="#FFFFFF"><?php echo $listItem[tray_num] ?></td>
                
                <?php if ($mylang == 'lang_french'){ ?>
                <td align="right" bgcolor="#FFFFFF"><b><?php echo 'ID Vendeur:'; ?></b></td>
                <?php }else{ ?>
                 <td align="right" bgcolor="#FFFFFF"><b><?php echo $adm_salespersonid_txt; ?></b></td>
                <?php }?>
                
                <td align="left" bgcolor="#FFFFFF"><?php echo $listItem[salesperson_id] ?></td>
                <td colspan="2" align="left" bgcolor="#FFFFFF">&nbsp;</td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_sphere_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_cylinder_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_axis_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_addition_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_prism_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong><?php echo $adm_quantity_txt; ?></strong></td>
                <td align="center" bgcolor="#E5E5E5"><strong><?php echo $adm_price_txt; ?></strong></td>
              </tr>
              <tr >
                <td><?php echo $adm_re_txt; ?></td>
                <td align="center"><?php echo $listItem[re_sphere] ?></td>
                <td align="center"><?php echo $listItem[re_cyl] ?></td>
                <td align="center"><?php echo $listItem[re_axis] ?></td>
                <td align="center"><?php echo $listItem[re_add] ?></td>
                <td align="center"><?php echo $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td rowspan="7" align="center" valign="top" class="formCellNosidesCenter"><?php echo $listItem[order_quantity] ?></td>
               
               <?php 
			   $queryEntryFee  = "SELECT entry_fee FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultEntryFee = mysqli_query($con,$queryEntryFee)	or die ("Could not select items");
			   $DataEntryFee   = mysqli_fetch_array($resultEntryFee,MYSQLI_ASSOC);
			   
			   $queryWarranty  = "SELECT warranty FROM orders WHERE order_num  = " . $listItem[order_num];
			   $resultWarranty = mysqli_query($con,$queryWarranty)	or die ("Could not select items");
			   $DataWarranty   = mysqli_fetch_array($resultWarranty,MYSQLI_ASSOC);
			   ?>
               
                <td rowspan="7" align="right" valign="top"><b>$
				<?php echo $listItem[order_product_price];?></b>
				<?php if ($DataEntryFee[entry_fee] == 2) 
				{
				echo '<br><b>Data Entry Fee</b> 2$(inc. in product price)';
				}  ?>
                
                <?php if ($DataWarranty[warranty] == 1) 
				{
				echo '<br><b>Warranty Fee</b> 6$(inc. in product price)';
				}  ?>
                
                   <?php if ($DataWarranty[warranty] == 2) 
				{
				echo '<br><b>Warranty Fee</b> 10$(inc. in product price)';
				}  ?>
				<?php 
				if ($over_range!=0){echo "<br> Over range: ";
				echo $over_range;}?><?php 
				if ($e_total_price!=0){echo  $e_products_string;}?><br>
				<?php if ($listItem[extra_product_price]!=0){
				//echo " Extra item: ";
				//echo $listItem[extra_product_price]."<br>";
				}?>
				<?php if ($listItem[coupon_dsc]!=0){
				
				
				$queryDescriptionCoupon = "select description, code from coupon_codes where code =(Select code from coupon_use WHERE order_id = (Select primary_key from orders where order_num = ". $listItem[order_num] . ") LIMIT 0,1)";
				//echo $queryDescriptionCoupon;
				$resultDescriptionCoupon=mysqli_query($con,$queryDescriptionCoupon)	or die ("Could not select items". mysqli_error($con));
				$DataDescription=mysqli_fetch_array($resultDescriptionCoupon,MYSQLI_ASSOC);
				 
				 if ($DataDescription['description'] == ""){
				 $description = 'Coupon Code ';
				 }else{
				 $description = $DataDescription['description'];
				 }
				echo  '<b>' . $description . "</b>  -";
				echo $listItem[coupon_dsc]."<br>";
				
				}?>
				
                
             <?php  
			 
			 $queryTopUrgent = "SELECT * FROM extra_product_orders WHERE category = 'Top urgent' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysqli_query($con,$queryTopUrgent)	or die ("Could not select items");
			 $countTopUrgent=mysqli_num_rows($resultTopUrgent);
			 $DataUrgent=mysqli_fetch_array($resultTopUrgent,MYSQLI_ASSOC);
			 if  ($countTopUrgent > 0)
			 echo "Top Urgent:".  $DataUrgent['price']. "<br>";
			
			 $queryTopUrgent = "SELECT * FROM extra_product_orders WHERE category = 'Special_Base' AND order_num =  " . $listItem[order_num];
			 $resultTopUrgent=mysqli_query($con,$queryTopUrgent)	or die ("Could not select items");
			 $countTopUrgent=mysqli_num_rows($resultTopUrgent);
			 $DataUrgent=mysqli_fetch_array($resultTopUrgent,MYSQLI_ASSOC);
			 if  ($countTopUrgent > 0)
			 echo "Special Base:".  $DataUrgent['price']. "<br>";

			$queryMirror = "SELECT * FROM extra_product_orders WHERE category = 'Mirror' AND order_num =  " . $listItem[order_num];
			$resultMirror=mysqli_query($con,$queryMirror)	or die ("Could not select items");
			$countMirror=mysqli_num_rows($resultMirror);
			$DataMirror=mysqli_fetch_array($resultMirror,MYSQLI_ASSOC);
			if  ($countMirror > 0)
			echo "Mirror: ".  $DataMirror['price']. '<br>';
			
			$queryMirror = "SELECT * FROM extra_product_orders WHERE category = 'High Cylinder' AND order_num =  " . $listItem[order_num];
			$resultMirror=mysqli_query($con,$queryMirror)	or die ("Could not select items");
			$countMirror=mysqli_num_rows($resultMirror);
			$DataMirror=mysqli_fetch_array($resultMirror,MYSQLI_ASSOC);
			if  ($countMirror > 0)
			echo "High Cylinder: ".  $DataMirror['price']. '<br>';
					
			$queryMirror = "SELECT * FROM extra_product_orders WHERE category = 'High Addition' AND order_num =  " . $listItem[order_num];
			$resultMirror=mysqli_query($con,$queryMirror)	or die ("Could not select items");
			$countMirror=mysqli_num_rows($resultMirror);
			$DataMirror=mysqli_fetch_array($resultMirror,MYSQLI_ASSOC);
			if  ($countMirror > 0)
			echo "High Addition: ".  $DataMirror['price']. '<br>';
			
			$queryMirror = "SELECT * FROM extra_product_orders WHERE category = 'Special Size' AND order_num =  " . $listItem[order_num];
			$resultMirror=mysqli_query($con,$queryMirror)	or die ("Could not select items");
			$countMirror=mysqli_num_rows($resultMirror);
			$DataMirror=mysqli_fetch_array($resultMirror,MYSQLI_ASSOC);
			if  ($countMirror > 0)
			echo "Special Size: ".  $DataMirror['price']. '<br>';
			
			
			
			
			   ?>
                
               <?php if ($mylang == 'lang_french'){ ?>
                Sous-total:
               <?php }else{ ?>
                Subtotal:
               <?php }?>
               
                <b><?php echo $itemSubtotal;?></b>
                </td>
              </tr>
              <tr>
                <td colspan="6" align="left">
                <strong><?php if ($mylang == 'lang_french')  echo 'PD de loin:'; else echo 'Dist. PD:'; ?></strong> <?php echo $listItem[re_pd] ?>&nbsp;&nbsp;&nbsp;
                <strong><?php if ($mylang == 'lang_french')  echo 'PD de près:'; else echo 'Near PD:'; ?> </strong><?php echo $listItem[re_pd_near] ?>&nbsp;&nbsp;&nbsp;
                <strong><?php echo $adm_height_txt; ?></strong> <?php echo $listItem[re_height] ?></td>
    </tr>
              <tr>
                <td><?php echo $adm_le_txt; ?></td>
                <td align="center"><?php echo $listItem[le_sphere] ?></td>
                <td align="center"><?php echo $listItem[le_cyl] ?></td>
                <td align="center"><?php echo $listItem[le_axis] ?></td>
                <td align="center"><?php echo $listItem[le_add] ?></td>
                <td align="center"><?php echo $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
    </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF">
                <strong><?php if ($mylang == 'lang_french')  echo 'PD de loin:'; else echo 'Dist. PD:'; ?></strong> <?php echo $listItem[le_pd] ?>&nbsp;&nbsp;&nbsp;
                <strong><?php if ($mylang == 'lang_french')  echo 'PD de près:'; else echo 'Near PD:'; ?> </strong><?php echo $listItem[le_pd_near] ?>&nbsp;&nbsp;&nbsp;
                <strong><?php echo $adm_height_txt; ?></strong> <?php echo $listItem[le_height] ?></td>
    </tr>     
	<?php if (($listItem[PT] !="0")&&($listItem[PA]!="0")&&($listItem[vertex]!="0")){?>
                   <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $adm_pt_txt; ?> </strong><?php echo $listItem[PT] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $adm_pa_txt; ?> </strong><?php echo $listItem[PA] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $adm_vertex_txt; ?></strong> <?php echo $listItem[vertex] ?></td>
                </tr>
                <?php }?>
                
                
                <tr>
                <td><?php if ($mylang == 'lang_french') echo 'Épaisseur:'; else echo 'Thickness:'; ?></td><td>
                 <?php
			   	 echo  'RE CT:'                   . $listItem['RE_CT'] . '&nbsp;&nbsp;LE CT'  .$listItem['LE_CT'];  
				 echo  '&nbsp;&nbsp;&nbsp;RE ET:' . $listItem['RE_ET'] . '&nbsp;&nbsp;LE ET:' .$listItem['LE_ET'];  
                ?>
                </td>
                </tr>
                
                <tr>
                <td><?php if ($mylang == 'lang_french') echo 'Centre Optique:'; else echo 'Optical Center:'; ?>
                 <?php
				 echo  '&nbsp;' . $listItem['optical_center'];  
                ?>
                </td>
                <td>
                    <b>PT</b>:  	<?php echo   $listItem['PT']; ?>
                    <b>PA</b>: 		<?php echo   $listItem['PA']; ?>
                    <b>Vertex</b>:  <?php echo   $listItem['vertex']; ?>
                </td>
                </tr>
                
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong><?php echo $adm_frame2_txt; ?><span class="formCellNosides">
                  <?php 
				if ($e_order_string_frame!=""){
					echo $e_order_string_frame;}
				else{
					echo $e_order_string_edging;}
				?>
                </span></strong></td>
              </tr>
              
              
              
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><span class="formCellNosides"><strong><?php echo $adm_other_txt; ?></strong>
                <?php 
					echo $e_order_string_engraving.$e_order_string_tint. ' ' . $DetailMirror;
				?>
                </span></td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong><?php echo $adm_specialinstructions_txt; ?> </strong><?php echo $listItem[special_instructions] ?>&nbsp;&nbsp;</td>
              </tr>
			  
			  <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>
				<?php if ($mylang == 'lang_french'){
				echo 'Note interne';
				}else {
				echo 'Internal note';
				}
				?>
				</strong><?php echo $listItem[internal_note] ?>&nbsp;&nbsp;</td>
              </tr>
              
              
              <?php 
			  $QueryRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id =  $listItem[redo_reason_id]";
			  $resultRedoReason = mysqli_query($con,$QueryRedoReason) or die  ('I cannot select items because: ' . mysqli_error($con));
		 	  $DataRedoReason   = mysqli_fetch_array($resultRedoReason,MYSQLI_ASSOC);
			  $RedoReason       = $DataRedoReason[redo_reason_en];
			  $RaisonReprise    = $DataRedoReason[redo_reason_fr];
			   ?>
               <tr>
               <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>
				<?php if ($mylang == 'lang_french'){
				echo 'Raison de la reprise';
				}else {
				echo 'Redo reason' ;
				}
				?>
				</strong><?php if ($mylang == 'lang_french'){
				echo $RaisonReprise ;
				}else {
				echo $RedoReason ;
				}
				?>&nbsp;&nbsp;</td>
              </tr>
              
               <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>
				<?php if ($mylang == 'lang_french'){
				echo 'Autorisé par:';
				}else {
				echo 'Authorized by:' ;
				}
				?>
				</strong>
				<?php 
				echo $listItem[authorized_by] ;
				?>&nbsp;&nbsp;</td>
              </tr>
              
              
               <?php if ($listItem[warranty] != '0'){ ?>
               <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>
				<?php if ($mylang == 'lang_french'){
				echo 'Extra Garantie  ';
				}else {
				echo 'Extra Warranty ';
				}
				?>
				</strong>
				<?php  if ( $listItem[warranty]== 1)
				echo '1 Year';?>
                
                <?php  if ( $listItem[warranty]== 2)
				echo '2 Years';?>
                
                <?php  if ( $listItem[warranty]== 'extension')
				echo 'Extension Optics';?>
                
                 &nbsp;&nbsp;</td>
            
              </tr>
               <?php } ?>
              
			  
            </table>
