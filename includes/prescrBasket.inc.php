<?php 

/*
//Code pour Summer Si$$le! Promo
$querySelectedPromo  = "SELECT selected_promotion FROM accounts WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
$resultSelectedPromo = mysql_query($querySelectedPromo) or die  ('I cannot select items because 11: ' . mysql_error());
$DataSelectedPromo   = mysql_fetch_array($resultSelectedPromo);
$DebutNomProduit = substr($listItem[order_product_name],0,5);

$Royaute = 0;
	

//Partie pour évaluer s'il y aura des royautés (Summer Si$$le!)
if (($DataSelectedPromo[selected_promotion]=='sizzling summer')   && ($DebutNomProduit <>'Promo')) {
	$Royaute = 0;
	$queryLensCategory   = "SELECT lens_category FROM exclusive WHERE  primary_key =  (SELECT order_product_id FROM orders WHERE primary_key=". $listItem[primary_key]. ')';
	
	//echo $queryLensCategory.'<br>';
	$resultLensCategory  = mysql_query($queryLensCategory) or die  ('I cannot select items because 12: ' . mysql_error());
	$DataLensCategory    = mysql_fetch_array($resultLensCategory);
	$LensCategory        = $DataLensCategory[lens_category]; 

	
	// echo '<br>Lens Category: ' .$LensCategory;
	//1- On évalue si le produit commandé est du lens_Category prog cl, Si oui, on ajoute 2,50$ 
	if ($LensCategory =='prog cl')
	$Royaute = $Royaute + 2.5;
	
	//2- On évalue si le produit commandé est du lens_Category prog ds ou prog ff, Si oui, on ajoute 5$ 
	if (($LensCategory =='prog ds') || ($LensCategory =='prog ff'))
	$Royaute = $Royaute + 5;
	
	//3- On évalue si le coating est  parmis ces coatings:/*Dream AR, Smart AR,ITO AR,Xlr,MultiClear AR,Aqua Dream AR,CrizalF,Blue AR, sI UN DE CES COATING + 2.5$
	 //echo '<br>Coating: ' .$listItem[order_product_coating];
	 
   switch($listItem[order_product_coating])
   {
	   case "Dream AR":		 $Royaute = $Royaute + 2.5; break;
	   case "Smart AR":		 $Royaute = $Royaute + 2.5; break;
	   case "ITO AR":		 $Royaute = $Royaute + 2.5; break;
	   case "Xlr":			 $Royaute = $Royaute + 2.5; break;
	   case "MultiClear AR": $Royaute = $Royaute + 2.5; break;
	   case "Aqua Dream AR": $Royaute = $Royaute + 2.5; break;
	   case "CrizalF":		 $Royaute = $Royaute + 2.5; break;
	   case "Blue AR":		 $Royaute = $Royaute + 2.5; break;
	   default: 		     $Royaute = $Royaute + 0; break;
   }
   
   

	//4- On évalue si le produit a un transition si oui,  + 2.5$
	switch($listItem[order_product_photo])
   {
	   case "Drivewear":	 	 $Royaute = $Royaute + 2.5; break;
	   case "Grey":	 	         $Royaute = $Royaute + 2.5; break;
	   case "Brown":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Yellow":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Violet":			 $Royaute = $Royaute + 2.5; break;
	   case "Pink": 			 $Royaute = $Royaute + 2.5; break;
	   case "Orange": 			 $Royaute = $Royaute + 2.5; break;
	   case "Green":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Blue":		 		 $Royaute = $Royaute + 2.5; break;
	   case "Extra Active Grey": $Royaute = $Royaute + 2.5; break;
	   default: 		         $Royaute = $Royaute + 0;   break;
   }
   
   
   //4- On évalue si le produit a un polarized si oui,  + 2.5$
   //echo '<br>Polar: ' .$listItem[order_product_polar];
	switch($listItem[order_product_polar])
   {
	   case "Grey":	 	 $Royaute = $Royaute + 2.5; break;
	   case "Brown":	 $Royaute = $Royaute + 2.5; break;
	   case "Green":	 $Royaute = $Royaute + 2.5; break;
	   case "G-15": 	 $Royaute = $Royaute + 2.5; break;
	   case "G15": 		 $Royaute = $Royaute + 2.5; break;
	   default: 		 $Royaute = $Royaute + 0; break;
   }

}//End IF

//FIN Summer Si$$le!*/
?>



<link href="../dl.css" rel="stylesheet" type="text/css" />
<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
         <tr >
                <td colspan="7" bgcolor="#D7E1FF" class="tableSubHead"><?php echo $lbl_productname_txt_stock;?> - <?php print $listItem[order_product_name] ?></td>
                <td bgcolor="#D7E1FF" class="tableSubHead">&nbsp;</td><form id="<?php print $listItem[order_item_number] ?>" name="<?php print $listItem[order_item_number] ?>" method="post" action="basket.php">
                <td bgcolor="#D7E1FF"  class="formCellNosidesRA" ><input name="pkey" type="hidden" value="<?php print $listItem[primary_key]?>" /> 
                  
                    <input name="Submit" type="submit" class="formText"value="<?php echo $btn_remove_txt;?>" />
                    <input name="delete" type="hidden" value="true" />
					</td>     </form>   
  </tr>
         <tr >
           <td colspan="8" bgcolor="#D7E1FF" class="tableSubHead">
           <?php if($listItem[myupload] != ""){
           	echo "The file ".$listItem[myupload]." has been uploaded as a lens profile.";
		   }
		   ?>
             <?php 
			switch($_SESSION["sessionUserData"]["currency"]){
			case 'CA':     $CustomerCurrency = '$'; 	  	  break;
			case 'US':     $CustomerCurrency = '$'; 	    break;
			case 'EUR':    $CustomerCurrency = "&#128;";   break;
			}?>
		   </td>
         </tr>
         
         <?php //Affiché détail de la promo Summer Si$$le! ICi ?>

         <?php  
			/*if ($Royaute > 0){ ?>
				
				
           <tr >
                <td colspan="9" bgcolor="#FF3333" class="tableHead"><p><?php 
				
				if ($mylang == 'lang_french'){
				echo 'Chaleur E$tivale Opportunité: Vous pourriez recevoir  '.  $Royaute.'$  sur cette commande.<br> Des conditions s\'appliquent: Appeler votre service à la clientèle au 1-855 770-2124';
				}else {
				echo 'Opportunity: You can collect $'.  $Royaute.'  on this order.<br> Conditions apply: Please call Customer Service at 1-855 770-2124';
				}
				
				?></p></td>
              </tr>
			
			
			
			<?php } */?>
         
         

              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><strong><?php echo $lbl_coating_txt_pl;?></strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_coating] ?></td>
                <td align="center" bgcolor="#FFFFFF"  class="formCellNosidesRA"><strong><?php echo $lbl_photochrom2_txt;?></strong></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_photo] ?></td>
                <td align="center" bgcolor="#FFFFFF" class="formCellNosidesRA"><strong><?php echo $lbl_polarized2_txt;?></strong></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_product_polar] ?></td>
              </tr>
              <tr >
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_patient_txt;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[order_patient_first] ?>&nbsp;<?php print $listItem[order_patient_last] ?>&nbsp;</td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA">&nbsp;<b><?php echo $lbl_refnum_preslenses;?></b></td>
                <td bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[patient_ref_num] ?></td>
                 <td bgcolor="#FFFFFF" class="formCellNosides"><b>Tray:</b> <?php print $listItem[tray_num] ?></td>
                <td bgcolor="#FFFFFF" class="formCellNosidesRA"><b><?php echo $lbl_salesperid2_txt;?> </b></td>
                <td colspan="3" align="center" bgcolor="#FFFFFF" class="formCellNosides"><?php print $listItem[salesperson_id] ?></td>
              </tr>
              <tr >
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_sphere_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_cylinder_txt_stock;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo 'Thickness';?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_axis_txt_pl;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_addition_txt_pl;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosides"><strong><?php echo $lbl_prism_txt_pl;?></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesCenter"><strong></strong></td>
                <td align="center" bgcolor="#E5E5E5" class="formCellNosidesRA"><strong><?php echo $lbl_price_txt;?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php echo $lbl_re_txt_pl;?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_cyl] ?></td>
                <td align="center" class="formCellNosides"><?php if ($listItem['RE_CT'] <> '') echo  $listItem['RE_CT'] . ' CT'; elseif ($listItem['RE_ET'] <> '')  echo $listItem['RE_ET'] . ' ET'  ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_axis] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_add] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td rowspan="6" align="center" valign="top" class="formCellNosidesCenter">
				<?php /*?><form id="<?php print $listItem[order_item_number] ?>" name="<?php print $listItem[order_item_number] ?>" method="post" action="basket.php" onSubmit="return validate_quantity(this)">
<input name="quantity" type="text" class="formText" id="quantity" value="<?php print $listItem[order_quantity] ?>" size="3" /><input name="Submit" type="submit" class="formText" value="<?php echo $btn_update_txt;?>" /><input name="update_quantity" type="hidden" value="true" />
<input name="pkey" type="hidden" value="<?php print $listItem[primary_key]?>" /></form><?php */?></td>
                <td rowspan="6" align="right" valign="top" class="formCellNosidesRA"><b><?php echo  $CustomerCurrency;?>
				<?php print $listItem[order_product_price];?></b><?php 
				if ($over_range!=0){print "<br> Over range: ";print $over_range;}?><?php 
				if ($e_total_price!=0){print "<br> Extra prod: ";print $e_total_price;}?><?php 
				if ($listItem[coupon_dsc]!=0){print "<br> Coupon disc.: -";
				print $listItem[coupon_dsc];}?><br><?php echo $lbl_subtotal_txt;?> <?php print $itemSubtotal;?></td>
              </tr>
              <tr >
                <td colspan="6" align="left" class="formCellNosides"><strong><?php echo $lbl_distpd_txt;?></strong>  <?php print $listItem[re_pd] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_nearpd_txt;?></strong>  <?php print $listItem[re_pd_near] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_height_txt;?></strong> <?php print $listItem[re_height] ?></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
                  <?php echo $lbl_le_txt_pl;?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_sphere] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_cyl] ?></td>
                <td align="center" class="formCellNosides"> <?php if ($listItem['LE_CT'] <> '') echo  $listItem['LE_CT'] . ' CT'; elseif ($listItem['LE_ET'] <> '')  echo $listItem['LE_ET'] . ' ET'  ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_axis] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_add] ?></td>
                <td align="center" class="formCellNosides"><?php print $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
                </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_distpd_txt;?> </strong><?php print $listItem[le_pd] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_nearpd_txt;?> </strong><?php print $listItem[le_pd_near] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_height_txt;?></strong> <?php print $listItem[le_height] ?></td>
                </tr>
                <?php if (($listItem[PT] !="0")&&($listItem[PA]!="0")&&($listItem[vertex]!="0")){?>
                   <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_pt_txt;?> </strong><?php print $listItem[PT] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_pa_txt;?> </strong><?php print $listItem[PA] ?>&nbsp;&nbsp;&nbsp;<strong><?php echo $lbl_vertex_txt_pl;?></strong> <?php print $listItem[vertex] ?></td>
                </tr>
                <?php }?>
                
                
                    
            
                
                
                
                
                
              <tr>
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_frame2_txt;?></strong>
				<?php 
				if ($e_order_string_frame!=""){
					print $e_order_string_frame;}
				else{
					print $e_order_string_edging;}
				?></td>
              </tr>
              
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong><?php echo $lbl_other_txt;?></strong> 
				<?php 
					print $e_order_string_engraving.$e_order_string_tint;
				?></td>
              </tr>
              <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b><?php echo $lbl_specinstr_txt;?></b> <?php print $listItem[special_instructions] ?></td>
              </tr>   
			  
			  <tr >
                <td colspan="8" align="left" bgcolor="#FFFFFF" class="formCellNosides"><b>
				<?php if ($mylang == 'lang_french'){
				echo 'Note interne:';
				}else {
				echo 'Internal note:';
				}
			?>
				</b> <?php print $listItem[internal_note] ?></td>
              </tr> 
              
              
           
			      
              <?php
			
			 if ($listItem[coupon_dsc]!=0)
			 	include("includes/removeCoupon.inc.php");
			 else
			 	include("includes/applyCoupon.inc.php");?>
</table>
