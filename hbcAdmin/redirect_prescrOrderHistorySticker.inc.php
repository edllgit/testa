
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
if ($usercount > 0)
$DataPc=mysql_fetch_array($resultPC);
  
  
  if( $listItem["eye"]=="R.E."){
		$listItem[le_pd]      = "0";
		$listItem[le_sphere]  = "0";
		$listItem[le_cyl]     = "0";
		$listItem[le_pr_ax2]  = "0";
		$listItem[le_pr_ax]   = "0";
		$listItem[le_axis]    = "0";
		$listItem[le_add]     = "0";
		$listItem[le_height]  = "0";
		$listItem[le_pd]      = "0";
		$listItem[le_pd_near] = "0";
		}elseif($listItem["eye"]=="L.E."){
	$listItem[re_pd] 	 = "0";
	$listItem[re_sphere] = "0";
	$listItem[re_cyl] 	 = "0";
	$listItem[re_pr_ax2] = "0";
	$listItem[re_pr_ax]  = "0";
	$listItem[re_axis] 	 = "0";
	$listItem[re_add] 	 = "0";
	$listItem[re_height] = "0";
	$listItem[re_pd] 	 = "0";
	$listItem[re_pd_near]= "0";
		} ?>

         <tr>
                <td colspan="8" bgcolor="#555555"><font color="#FFFFFF"><strong>Commande :</strong> <?php echo $listItem[order_product_name] ?>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php /*?>Product Code: <?php echo $DataPc[product_code] ?> 
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;EYE:
				<?php if ($listItem[eye]=='L.E.') echo 'Left Eye ONLY'; ?>
                <?php if ($listItem[eye]=='R.E.') echo 'Right Eye ONLY'; ?> 
                <?php if ($listItem[eye]=='Both') echo 'Both Eyes'; ?><?php */?></font></td>
                
 		 </tr>

              <tr>
                <td align="left" bgcolor="#E5E5E5"><strong>Sph&egrave;re</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>Cylindre</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>Axes</strong></td>
                <td align="left" bgcolor="#E5E5E5"><strong>Addition</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>PD</strong></td>
                <td align="left" bgcolor="#E5E5E5"> <strong>Hauteur</strong></td>
               
              </tr>
              
              <tr >
                <td align="left"><?php echo $listItem[re_sphere] ?></td>
                <td align="left"><?php echo $listItem[re_cyl] ?></td>
                <td align="left"><?php echo $listItem[re_axis] ?></td>
                <td align="left"><?php echo $listItem[re_add] ?></td>
                <td align="left"><?php echo $listItem[re_pd_near] ?></td>
                <td align="left"><?php echo $listItem[re_height] ?></td>
              </tr>
                       
            <tr >
                <td align="left"><?php echo $listItem[le_sphere] ?></td>
                <td align="left"><?php echo $listItem[le_cyl] ?></td>
                <td align="left"><?php echo $listItem[le_axis] ?></td>
                <td align="left"><?php echo $listItem[le_add] ?></td>
                <td align="left"><?php echo $listItem[le_pd_near] ?></td>
                <td align="left"><?php echo $listItem[le_height] ?></td>
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
?>