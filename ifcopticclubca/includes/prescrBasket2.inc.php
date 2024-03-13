 <?php  
  $queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysql_query($queryLab)	or die ("Could not select items");
  $DataLab=mysql_fetch_array($resultLab);
  $LabNum=$DataLab[main_lab];	
  if ($LabNum == 31)  {
  $AfficherWarningCoupon = "Yes";
  }else{
  $AfficherWarningCoupon = "No";
  }
//only package on IFc.ca
 $Commande = " Package ";
?>
<br>
<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#D5EEF7" class="tableSubHead"><?php echo  $Commande . ' ' ;?> <?php echo  $listItem[order_product_name] ?>
                </td>
                <td bgcolor="#D5EEF7" class="tableSubHead">&nbsp;</td><form id="<?php echo  $listItem[order_item_number] ?>" name="<?php echo  $listItem[order_item_number] ?>" method="post" action="basket2.php">
                <td bgcolor="#D5EEF7"  class="formCellNosidesRA" ><input name="pkey" type="hidden" value="<?php echo  $listItem[primary_key]?>" /> 
                  
                    <input name="Submit" type="submit" class="formText"value="<?php echo $btn_remove_txt;?>" />
                   
                    <input name="delete" type="hidden" value="true" />
					</td>     
       </tr>  </form>   
       
      <?php if (($LabNum==66) ||($LabNum==67)){   ?>
      
      <form id="<?php echo  $listItem[order_item_number] ?>" name="<?php echo  $listItem[order_item_number] ?>" method="post" action="basket2.php" target="_blank">
           <input name="appliquercoupon" type="hidden" value="true" />
           <input name="pkey" type="hidden" value="<?php echo  $listItem[primary_key]?>" /> 
           <tr>

           <td bgcolor="#D5EEF7" colspan="8"> <input name="Valider"  class="formText"value="<?php echo 'Valider cette commande';?>" <?php if ($listItem[coupon_dsc]<> 0.01) echo 'type="submit"'; else echo 'type="hidden"'; ?> /></td>

           </tr>
      </form>
      
      <?php } ?>
                    
                  
  </tr>
         <tr >
           <td colspan="8" bgcolor="#D5EEF7" class="tableSubHead">
           <?php if($listItem[myupload] != ""){
           	echo "The file ".$listItem[myupload]." has been uploaded as a lens profile.";
		   }
		   ?>
		   </td>
           
         
           
         </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong><?php echo $lbl_coating_txt_pl;?></strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[order_product_coating] ?></td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong><?php echo $lbl_photochrom2_txt;?></strong></td>
                 <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php if (($listItem[order_product_photo]  == 'None') &&  ($mylang == 'lang_french')){echo 'Non';}else{echo  $listItem[order_product_photo] ;}?></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong><?php echo $lbl_polarized2_txt;?></strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php if (($listItem[order_product_polar]  == 'None') &&  ($mylang == 'lang_french')){echo 'Non';}else{echo  $listItem[order_product_polar] ;}?>
                
                &nbsp;&nbsp;&nbsp;<?php if ($_SESSION["CompteEntrepot"] =='yes')  echo '<b>Reference Promo</b>:' . $listItem[reference_promo];  ?>
                
                </td>

              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_patient_txt;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[order_patient_first] ?>&nbsp;<?php echo  $listItem[order_patient_last] ?>&nbsp;</td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA">&nbsp;<b><?php echo $lbl_refnum_preslenses;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php echo $listItem[patient_ref_num] ?></td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_salesperid2_txt;?> </b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[salesperson_id] ?></td>
                
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo 'Tray:';?> </b></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[tray_num] ?></td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_sphere_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_cylinder_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_axis_txt_pl;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_addition_txt_pl;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_prism_txt_pl;?></strong></td>
               <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong><?php //echo $lbl_quantity_txt;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php echo $lbl_price_txt;?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $lbl_re_txt_pl;?></td>
                <td align="center" class="formCellNosides"><?php if($listItem[re_sphere] <> '?')echo $listItem[re_sphere]; ?></td>
                <td align="center" class="formCellNosides"><?php if($listItem[re_cyl] <> '?') echo $listItem[re_cyl]; ?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem[re_axis]<> '?')echo  $listItem[re_axis];?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem[re_add]<> '?') echo   $listItem[re_add]; ?></td>
                <td align="center" class="formCellNosides"><?php 
				
			 if (($listItem[re_pr_ax]=='None') &&  ($mylang == 'lang_french')){echo 'Non';}elseif($listItem[re_pr_ax]<> '?'){echo $listItem[re_pr_ax];}
		     if (($listItem[re_pr_io]=='None')&&  ($mylang == 'lang_french')){echo 'Non';}elseif($listItem[re_pr_io]<> '?'){echo $listItem[re_pr_io];}
			 
			 echo "&nbsp;&nbsp;";
			 if (($listItem[re_pr_ax2]=='None') && ($mylang=='lang_french')){echo 'Non';}elseif($listItem[re_pr_ax2]<> '?'){echo $listItem[re_pr_ax2];}
			 echo "&nbsp;";
			 	 if (($listItem[re_pr_ud]=='None') && ($mylang=='lang_french')){echo 'Non';}elseif($listItem[re_pr_ud]<> '?'){echo $listItem[re_pr_ud];}
			  ?></td>
                <td rowspan="6" align="center" valign="top" class="formCellNosidesCenter">
				<?php /*?><form id="<?php echo  $listItem[order_item_number] ?>" name="<?php echo  $listItem[order_item_number] ?>" method="post" action="basket2.php" onSubmit="return validate_quantity(this)">
<input name="quantity" type="text" class="formText" id="quantity" value="<?php echo  $listItem[order_quantity] ?>" size="3" /><input name="Submit" type="submit" class="formText" value="<?php echo $btn_update_txt;?>" /><input name="update_quantity" type="hidden" value="true" />
<input name="pkey" type="hidden" value="<?php echo  $listItem[primary_key]?>" /></form><?php */?></td>
                <td rowspan="6" align="right" valign="top" class="formCellNosidesRA"><b>
				<?php 
				if  ($mylang == 'lang_french') {
				echo $listItem[order_product_price]. '$';
			   }else{
				echo '$'. $listItem[order_product_price];
				}?>
				</b>
				<?php 
				if ($over_range!=0){echo  "<br> Surcharge indice élevé: ";echo  $over_range;}?><?php 
				
				//We remove the total of extra product and we replace it with the detail
				
				//PRISM
				if (($_SESSION['PrescrData']['RE_PR_IO']!="None")||($_SESSION['PrescrData']['RE_PR_UD']!="None")||($_SESSION['PrescrData']['LE_PR_IO']!="None")||($_SESSION['PrescrData']['LE_PR_UD']!="None")){
			 $PR_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Prism' ";
				
			$PR_result=mysql_query($PR_query)					or die  ('I cannot select items because: ' . mysql_error());
			$PR_listItem=mysql_fetch_array($PR_result);
			  $prism_text="<div>Prisme:</div>";
			  }
			  
			  
			  $main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
			  $tint=$_SESSION['PrescrData']['TINT'];
			  $frame_type=$_SESSION['PrescrData']['FRAME_TYPE'];
			  
			  
			//PRISM
			$PR_query="SELECT * from extra_product_orders  				WHERE  order_id='$listItem[primary_key]' AND category='Prism' ";
			$PR_result=mysql_query($PR_query)					or die  ('I cannot select items because: ' . mysql_error());
			$PR_listItem=mysql_fetch_array($PR_result);
			$Price_Prism = $PR_listItem[price];
						  
			//FRAME
			$FRAME_query="SELECT * from extra_product_orders  				WHERE  order_id='$listItem[primary_key]' AND category='Frame' ";
			$FRAME_result=mysql_query($FRAME_query)					or die  ('I cannot select items because: ' . mysql_error());
			$Frame_item=mysql_fetch_array($FRAME_result);
			$Frame_Price = $Frame_item[price];
			
			//Edging
			$Edging_query = "SELECT * from extra_product_orders  	WHERE  order_id='$listItem[primary_key]' AND category='Edging' ";
			$Edging_result= mysql_query($Edging_query)				or die  ('I cannot select items because: ' . mysql_error());
			$Data_Edging  = mysql_fetch_array($Edging_result);
			$Edging_Price = $Data_Edging[price];
			
			//MIRROR
			$Mirror_Price  = 0;
			$MIRROR_query  = "SELECT * from extra_product_orders  		WHERE  order_id='$listItem[primary_key]' AND category='Mirror' ";
			$MIRROR_result = mysql_query($MIRROR_query)					or die  ('I cannot select items because: ' . mysql_error());
			$MIRROR_item   = mysql_fetch_array($MIRROR_result);
			$Mirror_Price  = $MIRROR_item[price];
								  
			//TINT
			$TI_query="SELECT * from extra_product_orders  				WHERE  order_id='$listItem[primary_key]' AND category='Tint' ";
			$TI_result=mysql_query($TI_query)					or die  ('I cannot select items because: ' . mysql_error());
			$TI_listitem=mysql_fetch_array($TI_result);
			$Tint_Price = $TI_listitem[price];
			
			//Cylinder Over Range
			$QueryOverRange="SELECT * from extra_product_orders  				WHERE  order_id='$listItem[primary_key]' AND category='Cylinder Over Range' ";
			$resultOverRange =mysql_query($QueryOverRange)					or die  ('I cannot select items because: ' . mysql_error());
			$DataOverRange=mysql_fetch_array($resultOverRange);
			$Cylinder_Over_range_price = $DataOverRange[price];

			//Edge Polish
			$queryEdgePolish     = "SELECT price from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Edge Polish' ";
			$ResultEdgePolish    = mysql_query($queryEdgePolish)					or die  ('I cannot select items because: ' . mysql_error());
			$DataEdgePolish = mysql_fetch_array($ResultEdgePolish);
			$EdgePolish  = $DataEdgePolish[price];

				
				if (($Price_Prism<> '') &&  ($mylang == 'lang_french')){echo  "<br> Prisme: ";echo  $Price_Prism . '$';}
				if (($Price_Prism<> '') &&  ($mylang == 'lang_english')){echo  "<br> Prism: ";echo  '$' . $Price_Prism ;}
				
				if (($Tint_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Teinte: ";echo  $Tint_Price . '$';}
				if (($Tint_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Tint: ";echo  '$' . $Tint_Price ;}
				
				if (($Frame_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Frame: ";echo  '$'  . $Frame_Price ;}
				if (($Frame_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Monture: ";echo   $Frame_Price . '$' ;}
				
				if (($Mirror_Price>0) &&  ($mylang == 'lang_english')){echo  "<br> Mirror:: ";echo  '$'  . $Mirror_Price ; $TexteMirror  = ':'. $MIRROR_item[tint_color];}
				if (($Mirror_Price>0) &&  ($mylang == 'lang_french')){echo  "<br> Miroir:: ";echo  	     $Mirror_Price . '$' ; $TexteMirror  = ':'. $MIRROR_item[tint_color];}
 				
				if (($Cylinder_Over_range_price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Cylindre Over Range: ";echo  $Cylinder_Over_range_price . '$';}
				if (($Cylinder_Over_range_price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Cylinder Over Range: ";echo  '$' . $Cylinder_Over_range_price ;}
				
				if (($Edging_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Taillage: ";echo  $Edging_Price . '$';}
				if (($Edging_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Edging: ";echo  '$' . $Edging_Price ;}
				

				if (($EdgePolish<> '') &&  ($mylang == 'lang_english')){echo  "<br> Edge Polish: ";echo  '$' . $EdgePolish ;}
								
				?>
                
				<?php if ($mylang =='lang_french'){
				 echo '<br>Sous-total: '.  $itemSubtotal . '$';
				}else{
				 echo '<br>Subtotal: $' . $itemSubtotal;
				}?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong><?php echo $lbl_distpd_txt;?></strong>  <?php echo  $listItem[re_pd] ?>					                
                 &nbsp;&nbsp;&nbsp;<strong><?php echo 'PD pres';?></strong><?php echo  $listItem[re_pd_near] ?>	
                &nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_height_txt;?></strong> <?php if ( $listItem[re_height] <> '?') echo  $listItem[re_height];  ?></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  <?php echo $lbl_le_txt_pl;?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem[le_sphere] <> '?') echo $listItem[le_sphere]; ?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem[le_cyl] <> '?') echo $listItem[le_cyl]; ?></td> 
                <td align="center" class="formCellNosides"><?php if ($listItem[le_axis] <> '?') echo $listItem[le_axis]; ?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem[le_add] <> '?') echo $listItem[le_add]; ?></td>
                <td align="center" class="formCellNosides">
				
				<?php if (($listItem[le_pr_ax]=='None') && ($mylang=='lang_french')){echo 'Non';}elseif($listItem[le_pr_ax]<> '?'){echo $listItem[le_pr_ax];}
				
			if (($listItem[le_pr_io] == 'None') && ($mylang=='lang_french')){echo 'Non';}elseif($listItem[le_pr_io] <>'?'){echo $listItem[le_pr_io];}
			
			echo "&nbsp;&nbsp;";
			
			if (($listItem[le_pr_ax2]=='None') && ($mylang=='lang_french')) {echo 'Non';}elseif($listItem[le_pr_ax2]<> '?'){ echo $listItem[le_pr_ax2]; }
			echo "&nbsp;";
			
			if (($listItem[le_pr_ud] =='None') && ($mylang=='lang_french')) {echo 'Non';}elseif($listItem[le_pr_ud] <> '?'){echo $listItem[le_pr_ud]; }?></td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides">
				<strong><?php echo $lbl_distpd_txt;?> </strong>
				<?php if( $listItem[le_pd] <> '?' )  echo $listItem[le_pd]; ?>
                &nbsp;&nbsp;
                <strong><?php echo 'PD pres';?> </strong> <?php echo $listItem[le_pd_near]; ?>
                
                &nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_height_txt;?></strong> 
				
            	<?php 	if (($listItem[le_height]=='None')&&  ($mylang == 'lang_french')) {echo 'Non';}elseif($listItem[le_height]<> '?'){ echo $listItem[le_height]; } ?>
			
				</td>
                
                
                </tr>
              <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_frame2_txt;?></strong>
				<?php 
					echo   $e_order_string_edging;
				?></td>
              </tr>
              
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_other_txt;?></strong> 
				<?php 
					echo  $e_order_string_engraving;
					if ($e_order_string_tint=='Teinte: Gradient'){
					echo 'Teinte Dégradé';
					}else{
					echo $e_order_string_tint;
					}
					
				?></td>
              </tr>
              
              <?php if ($Mirror_Price > 0){ ?>
               <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo 'Miroir';?></strong> 
				<?php 
					echo  $TexteMirror;				
				?></td>
              </tr>
                <?php } ?>
              
               <tr>
              <td class="formCellNosides">
			  <?php if ($mylang == 'lang_french'){
			  	 echo "<b>&Eacute;paiseur sp&eacute;ciales</b>";
			  }else {
			  	 echo "<b>Special Thickness</b>";
			  }
				?></td>
                 <td class="formCellNosides">RE CT: <?php echo $listItem[RE_CT]?></td>
                 <td class="formCellNosides">LE CT: <?php echo $listItem[LE_CT]?></td>
                 <td class="formCellNosides">RE ET: <?php echo $listItem[RE_ET]?></td>
                 <td class="formCellNosides">LE ET: <?php echo $listItem[LE_ET]?></td>
              </tr>
              
               <tr>
              <td class="formCellNosides">
			  <?php if ($mylang == 'lang_french'){
			  	 echo "<b>Mesures personnalisées</b>";
			  }else {
			  	 echo "<b>Individualised Parameters</b>";
			  }
				?></td>
                 <td class="formCellNosides">Vertex: <?php echo $listItem[vertex]?></td>
                 <td class="formCellNosides">PT: <?php echo $listItem[PT]?></td>
                 <td class="formCellNosides">PA: <?php echo $listItem[PA]?></td>
              </tr>
              
              <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b><?php echo $lbl_specinstr_txt;?></b> <?php echo  $listItem[special_instructions] ?></td>
              </tr>   
			  
			  <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo 'Note au magasin :';
				}else {
				echo 'Internal note : ';
				}
			?>
				</b> <?php echo  $listItem[internal_note] ?></td>
              </tr> 
              
               <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo '# Optipro :';
				}else {
				echo '# Optipro : ';
				}
			?>
				</b> <?php echo  $listItem[order_num_optipro] ?></td>
              </tr> 
              
               <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo '# Optic-box :';
				}else {
				echo '# Optic-box : ';
				}
			?>
				</b> <?php echo  $listItem[order_num_opticbox] ?></td>
              </tr> 
              
                <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo 'Autoris&eacute; par:';
				}else {
				echo 'Authorized By : ';
				}
			?>
				</b> <?php echo  $listItem[authorized_by] ?></b></td>
              </tr> 
              
              
              
              
              <?php if (($LabNum==66) ||($LabNum==67)){   ?>
                 <tr> 
                    <td colspan="8" align="left" <?php if ($listItem[coupon_dsc]==0.01) echo  ' bgcolor="#94D08D"'; else echo ' bgcolor="#C5A0A0"';?>  class="formCellNosides">
                    <?php if ($mylang == 'lang_french'){
                    echo 'Commande validée:';
                    }else {
                    echo 'Order validated: ';
                    }
                ?>
                 <b>
                    <?php if  ($listItem[coupon_dsc] == 0.01){
                            if ($mylang == 'lang_french'){
                            echo 'OUI';
                            }else {
                            echo 'YES';
                            }
                    }else{
                    if ($mylang == 'lang_french'){
                            echo 'NON';
                            }else {
                            echo 'NO';
                            }
                        
                    }
                     ?></b>
                    </td>
                  </tr> 
			 <?php }  ?>
</table>