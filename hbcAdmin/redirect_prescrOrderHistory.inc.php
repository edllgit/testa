<?php
$queryLeLab = "SELECT lab from orders where order_num = $listItem[order_num]";
$resultLeLab=mysql_query($queryLeLab)		or die  ('I cannot select items because: ' . mysql_error());
$DataLeLab=mysql_fetch_array($resultLeLab);
$MainLabPK = $DataLeLab[lab];

if ($MainLabPK == 37){
$queryPC = "SELECT product_code from ifc_exclusive WHERE primary_key =  " . $listItem[order_product_id];
}else{
$queryPC = "SELECT product_code from exclusive WHERE primary_key =  " . $listItem[order_product_id];
}

$resultPC=mysql_query($queryPC)		or die  ('I cannot select items because: ' . mysql_error());
$usercount=mysql_num_rows($resultPC);
if ($usercount > 0){
$DataPc=mysql_fetch_array($resultPC);
}else{
//No product has been found, the products needs to be in ifc_ca_exclusive
$queryPC = "SELECT product_code from ifc_ca_exclusive WHERE primary_key =  " . $listItem[order_product_id];
$resultPC=mysql_query($queryPC)		or die  ('I cannot select items because: ' . mysql_error());
$DataPc=mysql_fetch_array($resultPC);
}
  
  if( $listItem["eye"]=="R.E."){
		$listItem[le_pd]  = "0";
		$listItem[le_sphere]  = "0";
		$listItem[le_cyl] ="0";
		$listItem[le_pr_ax2]  ="0";
		$listItem[le_pr_ax]  ="0";
		$listItem[le_axis]  ="0";
		$listItem[le_add]  ="0";
		$listItem[le_height]  ="0";
		$listItem[le_pd]  ="0";
		$listItem[le_pd_near]  ="0";
		}elseif($listItem["eye"]=="L.E."){
	$listItem[re_pd] 	= "0";
	$listItem[re_sphere]= "0";
	$listItem[re_cyl] 	="0";
	$listItem[re_pr_ax2]="0";
	$listItem[re_pr_ax] ="0";
	$listItem[re_axis] 	="0";
	$listItem[re_add] 	="0";
	$listItem[re_height]="0";
	$listItem[re_pd] 	="0";
	$listItem[re_pd_near]="0";
		}
		
		
		
	$conversion_right = 'no';	
	$conversion_left = 'no';
	
	
	if ( $listItem[re_cyl]<> '0'){
	$conversion_right = 'yes';
	$re_sphere_conv = $listItem[re_sphere]+$listItem[re_cyl];
	if ($listItem[re_sphere]>0) $re_sphere_conv="+".$re_sphere_conv;
	$re_cyl_conv="-".ABS($listItem[re_cyl]);
	$re_axis_conv=$listItem[re_axis]+90;
	if ($re_axis_conv>180) $re_axis_conv =$re_axis_conv-180;
	}


	if ( $listItem[le_cyl]<> '0'){
	$conversion_left = 'yes';
	$le_sphere_conv = $listItem[le_sphere]+$listItem[le_cyl];
	if ($listItem[le_sphere]>0) $le_sphere_conv="+".$le_sphere_conv;
	$le_cyl_conv="-".ABS($listItem[le_cyl]);
	$le_axis_conv=$listItem[le_axis]+90;
	if ($le_axis_conv>180) $le_axis_conv =$le_axis_conv-180;
	}

		
		
  
  ?>

         <tr >
                <td colspan="8" bgcolor="#555555"><font color="#FFFFFF">Product - <?php echo $listItem[order_product_name] ?>
				&nbsp;&nbsp;&nbsp;&nbsp;Product Code: <?php echo $DataPc[product_code] ?> 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EYE:
				<?php if ($listItem[eye]=='L.E.') echo 'Left Eye ONLY  '; ?>
                <?php if ($listItem[eye]=='R.E.') echo 'Right Eye ONLY  '; ?> 
                <?php if ($listItem[eye]=='Both') echo 'Both Eyes  ';?>
                <?php 
				$queryAcct = "SELECT account_num FROM accounts WHERE user_id = (SELECT user_id from orders where order_num = $listItem[order_num])";
				$resultAcct=mysql_query($queryAcct)		or die ('Error' . $queryAcct);
			    $DataAcct=mysql_fetch_array($resultAcct);
				 ?>
                <font color="#FFFFFF">&nbsp;&nbsp;&nbsp;Account Num : <?php echo  $DataAcct[account_num];?></font></td>
                
                
 		 </tr>
         
              <tr >
                <td colspan="5" align="left" bgcolor="#FFFFFF"><strong>Coating:</strong>
               <?php echo $listItem[order_product_coating] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <strong>Photochromatic:</strong>&nbsp;&nbsp;<?php echo $listItem[order_product_photo] ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
               <strong>Polarized:</strong>&nbsp;<?php echo $listItem[order_product_polar] ?></td>
              </tr>
              
            
              
              <tr >
                <td width="75px" align="left" bgcolor="#E5E5E5"><strong>RE Sphere</strong></td>
                <td align="left" width="75px" bgcolor="#E5E5E5"><strong>RE Cylinder</strong></td>
                <td align="left" width="75px" bgcolor="#E5E5E5"><strong>RE Axis</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>RE Addition</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>RE Prism</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>RE Dist.PD</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>RE Height</strong></td>
               
              </tr>
              
              <?php if ($conversion_right == 'yes') {?>
              
              <tr >
                <td align="left"><?php echo $listItem[re_sphere] . '  ('. $re_sphere_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[re_cyl]    . '  ('. $re_cyl_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[re_axis]   . '  ('. $re_axis_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[re_add] ?></td>
                <td align="left"><?php echo $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td align="left"><?php echo $listItem[re_pd] ?></td>
                <td align="left"><?php echo $listItem[re_height] ?></td>
              </tr>
              
              <?php }else{ ?>
              
                <tr >
                <td align="left"><?php echo $listItem[re_sphere] ?></td>
                <td align="left"><?php echo $listItem[re_cyl] ?></td>
                <td align="left"><?php echo $listItem[re_axis] ?></td>
                <td align="left"><?php echo $listItem[re_add] ?></td>
                <td align="left"><?php echo $listItem[re_pr_ax]."&nbsp;".$listItem[re_pr_io]."&nbsp;&nbsp;".$listItem[re_pr_ax2]."&nbsp;".$listItem[re_pr_ud]?></td>
                <td align="left"><?php echo $listItem[re_pd] ?></td>
                <td align="left"><?php echo $listItem[re_height] ?></td>
              </tr>
              
              
              <?php }?>

             <tr>
                <td align="left" bgcolor="#E5E5E5"><strong>LE Sphere</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>LE Cylinder</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>LE Axis</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>LE Addition</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>LE Prism</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>LE Dist.PD</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>LE Height</strong></td>
              </tr>
              
              
              
              
              
                 <?php if ($conversion_left == 'yes') {?>
              
            <tr >
                <td align="left"><?php echo $listItem[le_sphere] . '  ('. $le_sphere_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[le_cyl]    . '  ('. $le_cyl_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[le_axis]   . '  ('. $le_axis_conv    . ')' ?></td>
                <td align="left"><?php echo $listItem[le_add] ?></td>
                <td align="left"><?php echo $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
                <td align="left"><?php echo $listItem[le_pd] ?></td>
                <td align="left"><?php echo $listItem[le_height] ?></td>
    		</tr>
    
     <?php }else{ ?>
    
        
            <tr >
                <td align="left"><?php echo $listItem[le_sphere] ?></td>
                <td align="left"><?php echo $listItem[le_cyl] ?></td>
                <td align="left"><?php echo $listItem[le_axis] ?></td>
                <td align="left"><?php echo $listItem[le_add] ?></td>
                <td align="left"><?php echo $listItem[le_pr_ax]."&nbsp;".$listItem[le_pr_io]."&nbsp;&nbsp;".$listItem[le_pr_ax2]."&nbsp;".$listItem[le_pr_ud]?></td>
                <td align="left"><?php echo $listItem[le_pd] ?></td>
                <td align="left"><?php echo $listItem[le_height] ?></td>
    		</tr>
            <?php }?>
    
    
    
         <?php if (($listItem[PT] !="0")&&($listItem[PA]!="0")&&($listItem[vertex]!="0")){?>
                   <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF" class="formCellNosides"><strong>PT: </strong><?php echo $listItem[PT] ?>&nbsp;&nbsp;&nbsp;<strong>PA: </strong><?php echo $listItem[PA] ?>&nbsp;&nbsp;&nbsp;<strong>Vertex:</strong> <?php echo $listItem[vertex] ?></td>
                </tr>
                <?php }?>
              <tr >
                <td colspan="7" align="left" bgcolor="#FFFFFF"><strong>FRAME:
                &nbsp;&nbsp;&nbsp;Eye: A: </strong><?php echo $listItem[frame_a] ?>&nbsp;&nbsp;&nbsp;<strong>B: </strong><?php echo $listItem[frame_b] ?><strong>&nbsp;&nbsp;&nbsp;ED: </strong><?php echo $listItem[frame_ed] ?><strong>&nbsp;&nbsp;&nbsp;DBL: </strong><?php echo $listItem[frame_dbl] ?>&nbsp;&nbsp;&nbsp;<strong>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <strong>Frame Type:</strong> 
				<?php if ($listItem[frame_type] =="Plastique")
				{
				echo "Plastic ";
				}else{
				echo $listItem[frame_type] . ' ';
				}
				
			  $queryofFrame = "SELECT * FROM extra_product_orders WHERE order_num = $listItem[order_num] and category = 'Frame' ";
			  $resultofFrame=mysql_query($queryofFrame)		or die  ('I cannot select items because: ' . mysql_error());
			  $Compteur=mysql_num_rows($resultofFrame);
			  if ($Compteur > 0){
			  $DataofFrame=mysql_fetch_array($resultofFrame);
				
				 ?>

                
                <?php 
				echo " Frame Model : ";
				echo  $DataofFrame[temple_model_num];
				?>
                
                  <?php 
				echo "     Frame Color : ";
				echo  $DataofFrame[color];
				}
				?>
                
                
                
                
                </td>
              </tr>
              <tr >
                <td colspan="6" align="left" bgcolor="#FFFFFF"><strong>Special
                    Instructions : </strong><?php echo $listItem[special_instructions] ?>&nbsp;&nbsp;</td>
              </tr>
              
              
        <?php        
		$queryTint = "SELECT * from extra_product_orders WHERE  category = 'tint' and order_num =  " . $listItem[order_num];
		$resultTint=mysql_query($queryTint)		or die  ('I cannot select items because: ' . mysql_error());
		$usercountT=mysql_num_rows($resultTint);
if ($usercountT > 0){
$DataTint=mysql_fetch_array($resultTint);
}else{
$DataTint[tint] = "";
}


if ($DataTint[tint] == "Solid 60")
{
$DataTint[from_perc] = 60;
$DataTint[to_perc] = 60;
}

if ($DataTint[tint] == "Solid 80")
{
$DataTint[from_perc] = 82;
$DataTint[to_perc] = 82;
}


  echo '<tr><td colspan="6">Tint: ';
  if ($DataTint[tint] <> ""){
  echo  $DataTint[tint] . ' ' . $DataTint[tint_color] . ' From: ' . $DataTint[from_perc]  . '% To:'.  $DataTint[to_perc].  '%'; 
  }else{
  echo 'No tint';
  }
	echo '</td></tr>';
   ?>
              
            
            
