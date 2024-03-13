 <?php  
  $queryLab = "SELECT main_lab FROM accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
  $DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
  $LabNum=$DataLab[main_lab];	
  if ($LabNum == 31)  {
  $AfficherWarningCoupon = "Yes";
  }else{
  $AfficherWarningCoupon = "No";
  }
//only package on SAFETY
 $Commande = " Package ";
//Inserer image de la monture choisie

$queryFrame = "SELECT temple_model_num  from extra_product_orders WHERE  order_id='$listItem[primary_key]'";
$resultFrame=mysqli_query($con,$queryFrame)	or die ("Could not select items");
$DataFrame=mysqli_fetch_array($resultFrame,MYSQLI_ASSOC);

$queryImage  = "SELECT image from safety_frames_french WHERE code = '$DataFrame[temple_model_num]' LIMIT 0,1";
$resultImage = mysqli_query($con,$queryImage)	or die ("Could not select items");
$DataImage   = mysqli_fetch_array($resultImage,MYSQLI_ASSOC); 
$UrlImage = "http://www.direct-lens.com/safety/frames_images/" . $DataImage[image];
?>

<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="6" bgcolor="#fbe6d8" class="tableSubHead"><?php echo  $Commande . ' ' ;?> <?php echo  $listItem[order_product_name]; ?>  </td>                <td bgcolor="#fbe6d8" class="tableSubHead">&nbsp;</td><form id="<?php echo  $listItem[order_item_number] ?>" name="<?php echo  $listItem[order_item_number] ?>" method="post" action="basket.php">
                <td bgcolor="#fbe6d8"  class="formCellNosidesRA" ><input name="pkey" type="hidden" value="<?php echo  $listItem[primary_key]?>" /> 
                  
                    <input name="Submit" type="submit" class="formText"value="<?php echo $btn_remove_txt;?>" />
                    <input name="delete" type="hidden" value="true" />
					</td>     </form>   
  </tr>
         <tr >
           <td colspan="8" bgcolor="#fbe6d8" class="tableSubHead">
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
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php if (($listItem[order_product_polar]  == 'None') &&  ($mylang == 'lang_french')){echo 'Non';}else{echo  $listItem[order_product_polar] ;}?></td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_patient_txt;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[order_patient_first] ?>&nbsp;<?php echo  $listItem[order_patient_last] ?>&nbsp;</td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA">&nbsp;<b><?php echo $lbl_refnum_preslenses;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php echo $listItem[patient_ref_num] ?></td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_salesperid2_txt;?> </b></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php echo  $listItem[salesperson_id] ?></td>
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
				<?php /*?><form id="<?php echo  $listItem[order_item_number] ?>" name="<?php echo  $listItem[order_item_number] ?>" method="post" action="basket.php" onSubmit="return validate_quantity(this)">
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
				
			$PR_result=mysqli_query($con,$PR_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$PR_listItem=mysqli_fetch_array($PR_result,MYSQLI_ASSOC);
			  $prism_text="<div>Prisme:</div>";
			  }
			  
			  
			  $main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
			  $tint=$_SESSION['PrescrData']['TINT'];
			  $frame_type=$_SESSION['PrescrData']['FRAME_TYPE'];
			  
			//PRISM
			$PR_query="SELECT * from extra_product_orders WHERE  order_id='$listItem[primary_key]' AND category='Prism' ";
			$PR_result=mysqli_query($con,$PR_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$PR_listItem=mysqli_fetch_array($PR_result,MYSQLI_ASSOC);
			$Price_Prism = $PR_listItem[price];


						  
			//TINT
			$TI_query="SELECT * from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Tint' ";
			$TI_result=mysqli_query($con,$TI_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$TI_listitem=mysqli_fetch_array($TI_result,MYSQLI_ASSOC);
			$Tint_Price = $TI_listitem[price];

			//Removable side shield
			$RSS_query="SELECT * from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Removable Side Shield' ";
			$RSS_result=mysqli_query($con,$RSS_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$RSS_listitem=mysqli_fetch_array($RSS_result,MYSQLI_ASSOC);
			$RSS_Price = $RSS_listitem[price];
			
			//Removable side shield
			$PSS_query="SELECT * FROM extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Side Shield' ";
			$PSS_result=mysqli_query($con,$PSS_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$PSS_listitem=mysqli_fetch_array($PSS_result,MYSQLI_ASSOC);
			$PSS_Price = $PSS_listitem[price];

            //Dust Bar
			$DustBar_query="SELECT * FROM extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Dust Bar' ";
			$DustBar_result=mysqli_query($con,$DustBar_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$DustBar_listitem=mysqli_fetch_array($DustBar_result,MYSQLI_ASSOC);
			$DustBar_Price = $DustBar_listitem[price];

			//Cushion
			$FrameQuery  = "SELECT * FROM extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Frame' ";
			$FrameResult = mysqli_query($con,$FrameQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
			$DataFrame   = mysqli_fetch_array($FrameResult,MYSQLI_ASSOC);
			$FramePrice  = $DataFrame[price];
			
			//Frame Detail
			$Cushion_query="SELECT * from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Removable Cushion' ";
			$Cushion_result=mysqli_query($con,$Cushion_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$Cushion_listitem=mysqli_fetch_array($Cushion_result,MYSQLI_ASSOC);
			$Cushion_Price = $Cushion_listitem[price];
			
			//Dispensing Fee PROG
			$QueryDispFeeP="SELECT temple from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Dispensing Fee Progressive' ";
			$DispFeePResult=mysqli_query($con,$QueryDispFeeP) or die  ('I cannot select items because: ' . mysqli_error($con));
			$DispensingFeeDataProg=mysqli_fetch_array($DispFeePResult,MYSQLI_ASSOC);
			$DispensingFeeProgPrice = $DispensingFeeDataProg[temple];
			
			//Dispensing Fee Bifocal
			$QueryDispFeeBifocal="SELECT temple from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Dispensing Fee Bifocal' ";
			$DispFeeBifocalResult=mysqli_query($con,$QueryDispFeeBifocal)					or die  ('I cannot select items because: ' . mysqli_error($con));
			$DispensingFeeBifocalData=mysqli_fetch_array($DispFeeBifocalResult,MYSQLI_ASSOC);
			$DispensingFeeBFPrice = $DispensingFeeBifocalData[temple];
			
			//Dispensing Fee SV
			$QueryDispFeeSV="SELECT temple from extra_product_orders  WHERE  order_id='$listItem[primary_key]' AND category='Dispensing Fee SV' ";
			$DispFeeSVResult=mysqli_query($con,$QueryDispFeeSV) or die  ('I cannot select items because: ' . mysqli_error($con));
			$DispensingFeeDataSV=mysqli_fetch_array($DispFeeSVResult,MYSQLI_ASSOC);
			$DispensingFeeSVPrice = $DispensingFeeDataSV[temple];
			
			   /* if (($DispensingFeeProgPrice<> '') &&  ($mylang == 'lang_french')){echo  "<br> Honoraires du professionnel: ";echo  $DispensingFeeProgPrice . '$';}
				if (($DispensingFeeProgPrice<> '') &&  ($mylang == 'lang_english')){echo  "<br> Dispensing Fee: ";echo  '$' . $DispensingFeeProgPrice ;}
				
				 if (($DispensingFeeBFPrice<> '') &&  ($mylang == 'lang_french')){echo  "<br> Honoraires du professionnel: ";echo  $DispensingFeeBFPrice . '$';}
				if (($DispensingFeeBFPrice<> '') &&  ($mylang == 'lang_english')){echo  "<br> Dispensing Fee: ";echo  '$' . $DispensingFeeBFPrice ;}
				
				 if (($DispensingFeeSVPrice<> '') &&  ($mylang == 'lang_french')){echo  "<br> Honoraires du professionnel: ";echo  $DispensingFeeSVPrice . '$';}
				if (($DispensingFeeSVPrice<> '') &&  ($mylang == 'lang_english')){echo  "<br> Dispensing Fee: ";echo  '$' . $DispensingFeeSVPrice ;}*/
		
					
				if (($RSS_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Cot&eacute;s protecteurs amovibles: ";echo  $RSS_Price . '$';}
				if (($RSS_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Removable side shield: ";echo  '$' . $RSS_Price ;}
				
				
				if (($PSS_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Cot&eacute;s protecteurs: ";echo  $PSS_Price . '$';}
				if (($PSS_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Side shield: ";echo  '$' . $PSS_Price ;}
				 
					
				if (($DustBar_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Pare-Poussi&egrave;re: ";echo  $DustBar_Price . '$';}
				if (($DustBar_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Dust Bar: ";echo  '$' . $DustBar_Price ;}
				
				if (($Cushion_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Coussinet amovibles: ";echo  $Cushion_Price . '$';}
				if (($Cushion_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Removable Cushion: ";echo  '$' . $Cushion_Price ;}

			  
				if (($Price_Prism<> '') &&  ($mylang == 'lang_french')){echo  "<br> Prisme: ";echo  $Price_Prism . '$';}
				if (($Price_Prism<> '') &&  ($mylang == 'lang_english')){echo  "<br> Prism: ";echo  '$' . $Price_Prism ;}
				
				if (($Tint_Price<> '') &&  ($mylang == 'lang_french')){echo  "<br> Teinte: ";echo  $Tint_Price . '$';}
				if (($Tint_Price<> '') &&  ($mylang == 'lang_english')){echo  "<br> Tint: ";echo  '$' . $Tint_Price ;}
				
				if (($FramePrice <> '') &&  ($mylang == 'lang_french')){echo  "<br> Monture: ";echo  $FramePrice . '$'; }
			    if (($FramePrice <> '') &&  ($mylang == 'lang_english')){ echo  "<br> Frame: $";echo  $FramePrice ;	}


				
				?>
                <?php 
				if     (($DejaPayeParClient <> 0) && ($mylang == 'lang_french')){
				echo    '<br>Pay&eacute; tiers partie: -'. $DejaPayeParClient . '$';	
				}elseif(($DejaPayeParClient <> 0) && ($mylang == 'lang_english')){
				echo    '<br>Paid by tiers: '.   '-$'.$DejaPayeParClient;		
				}
				?>
                
                
                
                
                
                
				<?php if ($mylang =='lang_french'){
				 echo '<br>Sous-total: '.  $itemSubtotal . '$';
				}else{
				 echo '<br>Subtotal: $' . $itemSubtotal;
				}?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides">
                <strong><?php echo $lbl_distpd_txt;?></strong>  
				<?php echo  $listItem[re_pd] ?>	
                
                 <strong><?php echo ' PD PRES:';?></strong>  
				<?php echo  $listItem[re_pd_near] ?>		
                
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
                
                <strong><?php echo ' PD PRES:';?> </strong>
				<?php if( $listItem[le_pd_near] <> '?' )  echo $listItem[le_pd_near]; ?>
                
                
                &nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_height_txt;?></strong> 
				
            	<?php 	if (($listItem[le_height]=='None')&&  ($mylang == 'lang_french')) {echo 'Non';}elseif($listItem[le_height]<> '?'){ echo $listItem[le_height]; } ?>
                
			
				</td>
                
                
                </tr>
              <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_frame2_txt;?></strong>
				<?php 
				if ($e_order_string_frame!=""){
					echo  $e_order_string_frame;
					}
				else{
					echo  $e_order_string_edging;
					}
				?></td>
              </tr>
              
	
	 <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo 'CODE SOURCE MONTURE:';?></strong>
				<?php 
					echo  $listItem[code_source_monture];
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
             
             
              <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b><?php echo $lbl_specinstr_txt;?></b> <?php echo  $listItem[special_instructions] ?></td>
              </tr>   
			  
			              
             <?php  
			 
			 $AfficherDispensingFee = false;
			 
			 if ($DispensingFeeProgPrice <> '') 
			 $AfficherDispensingFee = true;
			 
			  if ($DispensingFeeBFPrice <> '') 
			 $AfficherDispensingFee = true;
			 
			   if ($DispensingFeeSVPrice <> '') 
			 $AfficherDispensingFee = true;
			 

			 if ($DispensingFeeSVPrice <> '')
			 $AfficherDispensingFee = 'oui';
						
			
			if ($AfficherDispensingFee ==true){
			 ?>
                <tr>
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french')
				echo 'Honoraire Professionnel :';
				else 
				echo 'Dispensing Fee : '; ?>
				</b> <?php 
				 if ($mylang == 'lang_french')
				echo  $DispensingFeeProgPrice . $DispensingFeeBFPrice . $DispensingFeeSVPrice  . '$';
				else 
				echo '$' .  $DispensingFeeProgPrice . $DispensingFeeBFPrice . $DispensingFeeSVPrice;
				 ?></td>
              </tr> 
   
              <?php
			}//end if $AfficherDispensingFee
			 
			 if ($listItem[coupon_dsc]!=0)
			 	include("removeCoupon.inc.php");
			 else
			 	include("applyCoupon.inc.php");
				
				
				 
			//Payment section
			$queryAlreadyPaid     = "SELECT * FROM payments_safety WHERE order_id = $listItem[primary_key]";
			$resultAlreadyPaid    = mysqli_query($con,$queryAlreadyPaid)	or die ( "Query failed: " . mysqli_error($con));
			$nbrResultAlreadyPaid = mysqli_num_rows($resultAlreadyPaid);
			
			if (($nbrResultAlreadyPaid > 0) && ($mylang == 'lang_french')){
			$DataAlreadyPaid = mysqli_fetch_array($resultAlreadyPaid,MYSQLI_ASSOC);
			$PaidAmount      = $DataAlreadyPaid[payment_amount];
			$PaidAt          = $DataAlreadyPaid[paid_at];
			//echo '<br>Paid amount:'. $PaidAmount ;
			echo "<tr bgcolor=\"fbe6d8\"><td align=\"center\" class=\"formCellNosides\" colspan=\"8\"><b>Un paiement est d&eacute;ja appliqu&eacute; sur cette commande.Pour le modifier, modifier les champs n&eacute;cessaires et cliquer sur Appliquer le paiement</b></td></tr>";
			}elseif(($nbrResultAlreadyPaid > 0) && ($mylang == 'lang_english')){
			$DataAlreadyPaid = mysqli_fetch_array($resultAlreadyPaid,MYSQLI_ASSOC);
			$PaidAmount      = $DataAlreadyPaid[payment_amount];
			$PaidAt          = $DataAlreadyPaid[paid_at];
			echo "<tr bgcolor=\"#fbe6d8\"><td align=\"center\" class=\"formCellNosides\" colspan=\"8\"><b>A payment has already been recorded for this order.To update the payment, simply modify what you need to and click on Apply Payment</b></td></tr>";
			//echo '<br>Paid amount:'. $PaidAmount ;
			}
			
			include("applyPayment.inc.php");
			?>
            
              <tr>
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo '# Optipro:';
				}else {
				echo '# Optipro:';
				}
			?>
				</b> <?php echo  $listItem[order_num_optipro] ?></td>
            </tr> 
            
            <tr>
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo 'Autoris&eacute; par:';
				}else {
				echo 'Authorized By : ';
				}
			?>
				</b> <?php echo  $listItem[authorized_by] ?></td>
            </tr> 
</table>