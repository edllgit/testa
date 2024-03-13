<style type="text/css">
<!--
.style1 {font-size: 7px}
-->
</style>

<?php 
if ($_POST[from_upload_form]=="true"){

	if ($_FILES['image1']['tmp_name'] != ""){ /* if admin has selected a file to upload */
		$fileToUpload=($_FILES['image1']['tmp_name']);
		$upload_file_path="../logos/";
		$name="logo_Lab_".  $_POST[the_lab_primary_key]. ".jpg";
		$upload_file_path .= $name;
		if(!copy($fileToUpload, $upload_file_path)){
			$message= "<center><font color=\"red\"size=\"2\" face=\"Helvetica, sans-serif, Arial\">File was not uploaded. $fileToUpload</font></center>";
			exit();
			}
			else{
			$message="<center><font color=\"red\" size=\"2\" face=\"Helvetica, sans-serif, Arial\">FILE UPLOADED</font></center>";
				
				if ($_POST[the_lab_primary_key] <> '')
				{
				
			//	$queryActualLogo = "Select  logo_file =  FROM labs WHERE primary_key = ". $_POST[the_lab_primary_key];
			//	$resultActualLogo=mysql_query($queryActualLogo)	or die ("Error: Could not get logo" . mysql_error() );
				//$DataActualLogo=mysql_fetch_array($resultActualLogo);
			//	if ($DataActualLogo[logo_file] <> '')
			//	{
				//We delete the old logo file
				
			//	}
				
				//UPDATE LOGO IN THE LABS TABLE
				$queryLogo = "UPDATE LABS SET logo_file = '$name'  WHERE primary_key = ". $_POST[the_lab_primary_key];
				$resultLogo=mysql_query($queryLogo)	or die ("Error: Could not create access" . mysql_error() );
				}
			}
		}
	else{
		$message="<center><font color=\"red\" size=\"2\" face=\"Helvetica, sans-serif, Arial\">Please select a file to upload.</font></center>";
		}//END IF FILE NOT EMPTY
	
	$_POST[from_upload_form]="false";
	}//END from form conditional
 ?>


 <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
<form action="getLab.php" method="post" enctype="multipart/form-data" name="form2">
     Current logo:
   <?php  
    if ($labData["logo_file"] <> ''){ ?>
    <img width="150" src="../logos/<?php echo  $labData["logo_file"]; ?>" />
   <?php  }else{
  echo  'No logo at the moment';
   }   ?>
   
    <input name="image1" type="file" id="image1">     
    <input type="submit" name="Upload File" id="edit" value="Go">
    <input name="from_upload_form" type="hidden" id="from_upload_form" value="true">
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <input name="the_lab_primary_key" type="hidden" value="<?php  echo $labData["primary_key"] ; ?>">  
    <input name="lab" id ="lab" type="hidden" value="<?php  echo $labData["primary_key"] ; ?>">  
    
</form>

            	
                <form name="form4" method="post" action="auto_connect.php" class="formField">
                <tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "Auto connect to this Lab"; ?></font></b></td>
            		</tr>
                   
                 <tr><td align="center"><a target="_blank" href="auto_connect.php?lab_id=<?php echo $_POST[lab] ; ?>">Connect to this Lab</a></td></tr>   
                     <tr><td>&nbsp;</td></tr>   
                 </form>   
          </table>


<form action="getLab.php" method="post" enctype="multipart/form-data" name="from3" class="formField" id="from3" onSubmit="return formCheck(this);">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Lab
						Name						
					</div></td>
					<td align="left">
						<input name="lab_name" type="text" id="lab_name" size="20" value="<?php echo $labData["lab_name"]; ?>" class="formField">					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Country 
					</div></td>
					<td colspan="3" align="left">
						<select name = "country" id="country" class="formField">
							<option value = "CA" <?php if($labData["country"]=="CA") echo " selected"; ?>>Canada</option>
							<option value = "IT" <?php if($labData["country"]=="IT") echo " selected"; ?>>Italy</option>
							<option value = "US" <?php if($labData["country"]=="US") echo " selected"; ?>>United
							States</option>
						</select>					</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right">
												Address 1						
					</div></td>
					<td align="left">
						<input name="address1" type="text" id="address1" size="20" value="<?php echo $labData["address1"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												Address 2						
					</div></td>
					<td colspan="3" align="left">
						<input name="address2" type="text" id="address2" size="20" value="<?php echo $labData["address2"]; ?>" class="formField">					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												City						
					</div></td>
					<td align="left">
						<input name="city" type="text" id="city" size="20" value="<?php echo $labData["city"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												State/Province						
					</div></td>
					<td align="left"><input name="state" type="text" id="state" size="20" value="<?php echo $labData["state"]; ?>" class="formField" /></td>
					<td align="left"><div align="right">
						 Zip/Postal Code 
					</div></td>
					<td align="left">
						<input name="zip" type="text" id="zip" size="10" value="<?php echo $labData["zip"]; ?>" class="formField" />					</td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
												Phone						
					</div></td>
					<td align="left">
						<input name="phone" type="text" id="phone" size="20" value="<?php echo $labData["phone"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												Tollfree
						Phone						
					</div></td>
					<td colspan="3" align="left">
						<input name="tollfree_phone" type="text" id="tollfree_phone" size="20" value="<?php echo $labData["tollfree_phone"]; ?>" class="formField">					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Fax						
					</div></td>
					<td align="left">
						<input name="fax" type="text" id="fax" size="20" value="<?php echo $labData["fax"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
						Tollfree
						Fax 												
					</div></td>
					<td align="left">
						<input name="tollfree_fax" type="text" id="tollfree_fax" size="20" value="<?php echo $labData["tollfree_fax"]; ?>" class="formField">					</td>
					<td align="left"><div align="right">
						Fax Order Notification:<br />
	<span class="style1">fax number required</span>
					</div></td>
					<td align="left"><input name="fax_notify" type="checkbox" value="yes" <?php if($labData["fax_notify"]=="yes") echo " checked"; ?> /></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
												Email						
					</div></td>
					<td align="left">
						<input name="email" type="text" id="email" size="35" value="<?php echo $labData["lab_email"]; ?>" class="formField">
							
						<div align="right">						</div></td>
					<td align="left"><div align="right">Buying Level</div></td>
					<td align="left"><select name="buying_level" class="formField" id="buying_level">
					  <?php
	$query="select buying_level from buying_levels";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
	echo "<option value=\"$labList[buying_level]\""; if($labData["buying_level"]==$labList[buying_level]) echo " selected"; echo ">$labList[buying_level]</option>";
}
?>
			      </select></td>
					<td align="left"><div align="right">
						Stock Shipping Charge
					</div></td>
					<td align="left"><input name="ship_chg_stock" type="text" id="ship_chg_stock" size="6" value="<?php echo $labData["ship_chg_stock"]; ?>" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right"> Late Notifications Emails </div></td>
				  <td  align="left"><input name="notification_email" type="text" id="notification_email" size="35" value="<?php echo $labData["notification_email"]; ?>" class="formField" />
				    <div align="right"> </div></td>
				  <td><div align="right">Sales Reports Email</div> </td>
                  <td> <input name="reports_email" type="text" id="reports_email" size="35" value="<?php echo $labData["reports_email"]; ?>" class="formField" /></td>
				  <td  align="left"><div align="right">Redo Reports Email </div></td> <td><input name="redo_report_email" type="text" id="redo_report_email" size="30" value="<?php echo $labData["redo_report_email"]; ?>" class="formField" /></td>
			  </tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" bgcolor="#FFFFFF" ><div align="right">
						Login
					</div></td>
					<td align="left" bgcolor="#FFFFFF"><b><?php echo $labData["username"]; ?></b>					</td>
					<td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><div align="right">
						Password
					</div></td>
					<td align="left" bgcolor="#FFFFFF"><input name="password" type="text" class="formField" id="password" value="<?php echo $labData["password"]; ?>" size="12" />					</td>
					<td align="left" bgcolor="#FFFFFF"><div align="right">
						RX Shipping Charge
					</div>					</td>
					<td align="left" bgcolor="#FFFFFF"><input name="ship_chg_rx" type="text" id="ship_chg_rx" size="6" value="<?php echo $labData["ship_chg_rx"]; ?>" class="formField" /></td>
				</tr>
				    
                    
                   
			   
                    
                      <tr><td>&nbsp;</td></tr>     
                    
				<tr bgcolor="#FFFFFF">
					<td align="left" bgcolor="#DDDDDD" ><div align="right">
						<b>Lab Prefs</b></font>
					</div></td>
					<td align="left" bgcolor="#DDDDDD">&nbsp;</td>
					<td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right">
						<?php if ($labData["precision_vp_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Precision/Vision Pro'. '</font>'; 
						}else{
						echo 'Precision/Vision Pro';
						}
						?>
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><select name="precision_vp_lab" class="formField" id="precision_vp_lab">
							  <option   value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["precision_vp_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
					</select></td>
					<td align="left" bgcolor="#DDDDDD"><div align="right">
						<?php if ($labData["infocus_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Infocus'. '</font>'; 
						}else{
						echo 'Infocus';
						}
						?>
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><select name="infocus_lab" class="formField" id="infocus_lab">
							  <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["infocus_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
					</select></td>
				</tr>
                
                
				
				
				
				<tr bgcolor="#DDDDDD">
                  <td align="left" bgcolor="#FFFFFF" ><div align="right"> 
				  	    <?php if ($labData["innovative_lab"]==0)
						{
						echo '<font color="#FF0000">'.'My World'. '</font>'; 
						}else{
						echo 'My World';
						}
						?> </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="innovative_lab" class="formField" id="innovative_lab">
                        <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovative_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><div align="right"> 
                   	    <?php if ($labData["visionpropoly_lab"]==0)
						{
						echo '<font color="#FF0000">'.' Vision Pro Poly'. '</font>'; 
						}else{
						echo ' Vision Pro Poly';
						}
						?> 
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="visionpropoly_lab" id="visionpropoly_lab" class="formField">
                        <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["visionpropoly_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" bgcolor="#FFFFFF"><div align="right"> 
				  		<?php if ($labData["generation_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Generation'. '</font>'; 
						}else{
						echo 'Generation';
						}
						?>  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="generation_lab" id="generation_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["generation_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		<?php if ($labData["truehd_lab"]==0)
						{
						echo '<font color="#FF0000">'.'TrueHD'. '</font>'; 
						}else{
						echo 'TrueHD';
						}
						?>
                    </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="truehd_lab" id="truehd_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["truehd_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                  </select></td>
				  <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right"> 
                 	    <?php if ($labData["easy_fit_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Easy Fit HD'. '</font>'; 
						}else{
						echo 'Easy Fit HD';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="easy_fit_lab" id="easy_fit_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["easy_fit_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                                    </select></td>
				  <td align="left" bgcolor="#DDDDDD"><div align="right">  
				  		<?php if ($labData["other_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Other'. '</font>'; 
						}else{
						echo 'Other';
						}
						?> </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="other_lab" id="other_lab" class="formField">
                      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["other_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
                  </select></td>
			  </tr>
              
              <tr >
				  <td align="left"><div align="right">
				  		<?php if ($labData["vot_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Vot'. '</font>'; 
						}else{
						echo 'Vot';
						}
						?> </div>
                  </td>
				  <td align="left"><select name="vot_lab" id="vot_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["vot_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
				 
				  <td align="left"><div align="right">
				 		 <?php if ($labData["eco_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco'. '</font>'; 
						}else{
						echo 'Eco';
						}
						?> </div></div></td>
				  <td align="left"><select name="eco_lab" id="eco_lab" class="formField">
				     <option value="">Select a Lab</option> <?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
				  <td align="left" bgcolor="#FFFFFF" ><div align="right"> 
                 		 <?php if ($labData["visioneco_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Vision Eco'. '</font>'; 
						}else{
						echo 'Vision Eco';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="visioneco_lab" id="visioneco_lab" class="formField">
                  <option value="">Select a Lab</option>
				    <?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["visioneco_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
                
				
              
              
				<tr bgcolor="#DDDDDD">
                
                 <td align="left" nowrap="nowrap"><div align="right">
                 	    <?php if ($labData["glass_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Glass'. '</font>'; 
						}else{
						echo 'Glass';
						}
						?></div></td>
				  <td align="left"><select name="glass_lab" id="glass_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["glass_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right"> 
                      	<?php if ($labData["glass_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Glass 2'. '</font>'; 
						}else{
						echo 'Glass 2';
						}
						?></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="glass_2_lab" id="glass_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["glass_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                     <td align="left"><div align="right">
					 	<?php if ($labData["glass_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Glass 3'. '</font>'; 
						}else{
						echo 'Glass 3';
						}
						?></div></td>
				  <td align="left"><select name="glass_3_lab" id="glass_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["glass_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				
			  </tr>
              
              
              <tr  bgcolor="#FFFFFF">
				
				  <td bgcolor="#FFFFFF" align="left"><div align="right">
              	       <?php if ($labData["rodenstock_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Rodenstock'. '</font>'; 
						}else{
						echo 'Rodenstock';
						}
						?>
                  </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="rodenstock_lab" id="rodenstock_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["rodenstock_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				 <td bgcolor="#FFFFFF" align="left"><div align="right">
               		    <?php if ($labData["rodenstock_hd_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Rodenstock HD'. '</font>'; 
						}else{
						echo 'Rodenstock HD';
						}
						?>
                 </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="rodenstock_hd_lab" id="rodenstock_hd_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["rodenstock_hd_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                     <td bgcolor="#FFFFFF" align="left"><div align="right">
                   	  <?php if ($labData["innovation_ff_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation FF'. '</font>'; 
						}else{
						echo 'Innovation FF';
						}
						?>
                   </div></td>
				  <td bgcolor="#FFFFFF" align="left"><select name="innovation_ff_lab" id="innovation_ff_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
              
              
			  
              <tr bgcolor="#DDDDDD">
                
                 <td align="left" nowrap="nowrap"><div align="right">
               		   <?php if ($labData["innovation_ff_hd_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation FF HD'. '</font>'; 
						}else{
						echo 'Innovation FF HD';
						}
						?>
                 </div></td>
				  <td align="left"><select name="innovation_ff_hd_lab" id="innovation_ff_hd_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_hd_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                      <td bgcolor="#DDDDDD" align="left"><div align="right">
                        <?php if ($labData["innovation_ds_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation DS'. '</font>'; 
						}else{
						echo 'Innovation DS';
						}
						?>
                     </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="innovation_ds_lab" id="innovation_ds_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ds_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                     <td align="left"><div align="right">
                       <?php if ($labData["innovation_ii_ds_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation II DS'. '</font>'; 
						}else{
						echo 'Innovation II DS';
						}
						?>
                     </div></td>
				  <td align="left"><select name="innovation_ii_ds_lab" id="innovation_ii_ds_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ii_ds_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				
			  </tr>
              
              <tr>
                  <td align="left"><div align="right">
                        <?php if ($labData["svision_lab"]==0)
						{
						echo '<font color="#FF0000">'.'sVision'. '</font>'; 
						}else{
						echo 'sVision';
						}
						?>
                  </div></td>
				  <td align="left"><select name="svision_lab" id="svision_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["svision_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                       <td align="left"><div align="right">
                        <?php if ($labData["svision_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'sVision 2'. '</font>'; 
						}else{
						echo 'sVision 2';
						}
						?>
                       </div></td>
				  <td align="left"><select name="svision_2_lab" id="svision_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["svision_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                       <td align="left"><div align="right">
                       <?php if ($labData["svision_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'sVision 3'. '</font>'; 
						}else{
						echo 'sVision 3';
						}
						?>
                       </div></td>
				  <td align="left"><select name="svision_3_lab" id="svision_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["svision_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                    
              </tr>
              
              
              
              
              
                 
              <tr>
                  <td bgcolor="#DDDDDD" align="left"><div align="right">
                  		<?php if ($labData["conant_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Conant'. '</font>'; 
						}else{
						echo 'Conant';
						}
						?>
                  </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="conant_lab" id="conant_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["conant_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                       <td bgcolor="#DDDDDD"  align="left"><div align="right">
                       <?php if ($labData["optovision_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Optovision'. '</font>'; 
						}else{
						echo 'Optovision';
						}
						?>
                       </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="optovision_lab" id="optovision_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["optovision_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                    
                       
                      <td bgcolor="#DDDDDD"  align="left"><div align="right">
                        <?php if ($labData["selection_rx_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Selection Rx'. '</font>'; 
						}else{
						echo 'Selection Rx';
						}
						?>
                      </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="selection_rx_lab" id="selection_rx_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["selection_rx_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
              </tr>
              
              

             
              <tr>
                  <td  align="left"><div align="right">
                  		<?php if ($labData["ovation_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Ovation'. '</font>'; 
						}else{
						echo 'Ovation';
						}
						?>
                  </div></td>
				  <td  align="left"><select name="ovation_lab" id="ovation_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["ovation_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
     
                       <td   align="left"><div align="right">
                    <?php if ($labData["identity_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Identity'. '</font>'; 
						}else{
						echo 'Identity';
						}
						?>
                       </div></td>
				  <td   align="left"><select name="identity_lab" id="identity_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["identity_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?> </select></td>
                    
 
                          <td   align="left"><div align="right">
                    <?php if ($labData["innovation_ff_hd_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation FF HD 2'. '</font>'; 
						}else{
						echo 'Innovation FF HD 2';
						}
						?>
                       </div></td>
				  <td   align="left"><select name="innovation_ff_hd_2_lab" id="innovation_ff_hd_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_hd_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?> </select></td>
              </tr>
  
      




  <tr>
                  <td  align="left"><div align="right">
                  		<?php if ($labData["dl_somo_lab"]==0)
						{
						echo '<font color="#FF0000">'.'DL Somo'. '</font>'; 
						}else{
						echo 'DL Somo';
						}
						?>
                  </div></td>
				  <td  align="left"><select name="dl_somo_lab" id="dl_somo_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["dl_somo_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
     
                  <td  align="left"><div align="right">
                  		<?php if ($labData["revolution_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Revolution'. '</font>'; 
						}else{
						echo 'Revolution';
						}
						?>
                  </div></td>
				  <td  align="left"><select name="revolution_lab" id="revolution_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["revolution_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
 
                  <td   align="left"><div align="right">&nbsp;</div></td>
				  <td   align="left">&nbsp;</td>
              </tr>























                    <tr><td>&nbsp;</td></tr>     
              
              
              
              
              
				<tr bgcolor="#DDDDDD">
				<td align="left" bgcolor="#DDDDDD" ><div align="right">
						<b>Privates Collections</b>
					</div></td>
					<td align="left" bgcolor="#DDDDDD">&nbsp;</td>
                
                
				  <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right">
                 	   <?php if ($labData["private_1_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 1/ GRM Swiss'. '</font>'; 
						}else{
						echo 'Private 1/ GRM Swiss';
						}
						?>
                 </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_1_lab" id="private_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_1_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td align="left" bgcolor="#DDDDDD"><div align="right">
                   		<?php if ($labData["private_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 2/ GRM HKO'. '</font>'; 
						}else{
						echo 'Private 2/ GRM HKO';
						}
						?>
                 </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_2_lab" id="private_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                
			  </tr>
			  
			  
			  
			  
			  
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 	   <?php if ($labData["private_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 3/ Easy One'. '</font>'; 
						}else{
						echo 'Private 3/ Easy One';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_3_lab" id="private_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				   <td align="left" bgcolor="#FFFFFF" ><div align="right">
                   	   <?php if ($labData["private_4_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 4/ Essilor'. '</font>'; 
						}else{
						echo 'Private 4/ Essilor';
						}
						?>
                   </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_4_lab" id="private_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_4_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
					
				 <td align="left" bgcolor="#FFFFFF" ><div align="right">
                	   <?php if ($labData["private_5_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 5/ Copie de other'. '</font>'; 
						}else{
						echo 'Private 5/ Copie de other';
						}
						?>
                </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_5_lab" id="private_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_5_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
              
              
              
              
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                    	<?php if ($labData["private_6_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 6/ GRM DRUMMOND'. '</font>'; 
						}else{
						echo 'Private 6/ GRM DRUMMOND';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_6_lab" id="private_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_6_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
				   <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		<?php if ($labData["private_7_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 7/ GRM Sct'. '</font>'; 
						}else{
						echo 'Private 7/ GRM Sct';
						}
						?>
                   </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_7_lab" id="private_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_7_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
					
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">
                  		<?php if ($labData["private_8_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private 8/ GRM <b>CONANT</b>'. '</font>'; 
						}else{
						echo 'Private 8/ GRM <b>CONANT</b>';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_8_lab" id="private_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_8_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
			  
              
                   
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 		<?php if ($labData["nesp_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Nesp'. '</font>'; 
						}else{
						echo 'Nesp';
						}
						?>
                  </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="nesp_lab" id="nesp_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["nesp_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
       
       
       
       
       <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					<?php if ($labData["private_grm_1_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private GRM 1(Nesp)'. '</font>'; 
						}else{
						echo 'Private GRM 1(Nesp)';
						}
						?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_grm_1_lab" id="private_grm_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_grm_1_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
					
                    
                    
                    
					
				  <td align="left" bgcolor="#FFFFFF" ><div align="right">
                 		<?php if ($labData["private_grm_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private GRM 2(Infinity)'. '</font>'; 
						}else{
						echo 'Private GRM 2(Infinity)';
						}
						?>
                  </b></div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="private_grm_2_lab" id="private_grm_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_grm_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  </tr>
			  
			  
			  
			<tr>

              <td align="left" bgcolor="#DDDDDD" ><div align="right">
            		  <?php if ($labData["private_grm_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private Grm 3'. '</font>'; 
						}else{
						echo 'Private Grm 3';
						}
						?>
             </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="private_grm_3_lab" id="private_grm_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_grm_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  
            
                
			 	

              <td align="left" bgcolor="#DDDDDD" ><div align="right">
            		  <?php if ($labData["younger_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Younger Prog'. '</font>'; 
						}else{
						echo 'Younger Prog';
						}
						?>
             </div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="younger_lab" id="younger_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["younger_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    <td  align="left" bgcolor="#DDDDDD" ><div align="right">
                 		<?php if ($labData["axial_grm_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Axial GRM'. '</font>'; 
						}else{
						echo 'Axial GRM';
						}
						?>
                  </b></div></td>
				  <td align="left" bgcolor="#DDDDDD"><select name="axial_grm_lab" id="axial_grm_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["axial_grm_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td> 
     
			  </tr>
              
              
              <tr>
              <td align="left"  ><div align="right">
            		  <?php if ($labData["generation_grm_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Generation Grm'. '</font>'; 
						}else{
						echo 'Generation Grm';
						}
						?>
             </div></td>
				  <td align="left"><select name="generation_grm_lab" id="generation_grm_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["generation_grm_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                     <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					<?php if ($labData["axial_mini_hko_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Axial Mini hko'. '</font>'; 
						}else{
						echo 'Axial Mini hko';
						}
						?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="axial_mini_hko_lab" id="axial_mini_hko_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["axial_mini_hko_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
			  
              
              
              
              
               <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					<?php if ($labData["axial_mini_somo_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Axial mini Somo'. '</font>'; 
						}else{
						echo 'Axial Mini somo';
						}
						?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="axial_mini_somo_lab" id="axial_mini_somo_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["axial_mini_somo_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
              </tr>
              
              
              
              
           	 <tr>
           
                <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					<?php if ($labData["nesp_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Nesp 2'. '</font>'; 
						}else{
						echo 'Nesp 2';
						}
						?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="nesp_2_lab" id="nesp_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["nesp_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
               <td align="left" bgcolor="#FFFFFF" ><div align="right">
      					<?php if ($labData["az2ph2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Az2Ph2'. '</font>'; 
						}else{
						echo 'Az2Ph2';
						}
						?>
      </div></td>
				  <td align="left" bgcolor="#FFFFFF"><select name="az2ph2_lab" id="az2ph2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["az2ph2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
             
               <td>&nbsp;</td>
             <td>&nbsp;</td>
              </tr>
              
              
              
              
              

              
              
                <tr><td>&nbsp;</td></tr>   
			   <tr><th>Lensnet Club/AIT</th></tr>
	       
           
           
			  <tr >
				  <td bgcolor="#DDDDDD" align="left"><div align="right">
                  	   <?php if ($labData["eco_1_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 1 / HKO'. '</font>'; 
						}else{
						echo 'Eco 1 / HKO';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_1_lab" id="eco_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_1_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
                		<?php if ($labData["eco_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 2 / Conant OMC'. '</font>'; 
						}else{
						echo 'Eco 2 / Conant OMC';
						}
						?>
                  </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_2_lab" id="eco_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
				      
				 <td  bgcolor="#DDDDDD"align="left"><div align="right">
                 		<?php if ($labData["eco_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 2 / Conant OMC'. '</font>'; 
						}else{
						echo 'Eco 2 / Conant OMC';
						}
						?>
                 Eco 3 / Swiss</div></td>
				  <td  bgcolor="#DDDDDD"align="left"><select  name="eco_3_lab" id="eco_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
			 
				  
				<tr >
				 
                   <td align="left"><div align="right">
                 	   <?php if ($labData["eco_4_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 4 / DR'. '</font>'; 
						}else{
						echo 'Eco 4 / DR';
						}
						?>
                   </div></td>
				  <td align="left"><select  name="eco_4_lab" id="eco_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_4_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>   
                    
				 <td align="left"><div align="right">
                	   <?php if ($labData["eco_5_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 5 / VOT'. '</font>'; 
						}else{
						echo 'Eco 5 / VOT';
						}
						?>
                 </div></td>
				  <td align="left"><select  name="eco_5_lab" id="eco_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_5_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
				      
				 <td align="left"><div align="right">
                		<?php if ($labData["eco_6_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 6 / Local'. '</font>'; 
						}else{
						echo 'Eco 6 / Local';
						}
						?>
                 </div></td>
				  <td align="left"><select name="eco_6_lab" id="eco_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_6_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
              
              
              
              
              
              <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
               		   <?php if ($labData["eco_7_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 7 / Kodak HKO'. '</font>'; 
						}else{
						echo 'Eco 7 / Kodak HKO';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_7_lab" id="eco_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_7_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
				 <td bgcolor="#DDDDDD" align="left"><div align="right">
                		<?php if ($labData["eco_or_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco OR / Infinissima'. '</font>'; 
						}else{
						echo 'Eco OR / Infinissima';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_or_lab" id="eco_or_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_or_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                     <td bgcolor="#DDDDDD" align="left"><div align="right">
                     	<?php if ($labData["eco_conant_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco Conant / Shanghai'. '</font>'; 
						}else{
						echo 'Eco Conant / Shanghai';
						}
						?>
                     </div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="eco_conant_lab" id="eco_conant_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_conant_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>                  
			  </tr>
			   

                 <tr >			      
				 <td align="left"><div align="right">
            		    <?php if ($labData["eco_8_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 8 / SCT'. '</font>'; 
						}else{
						echo 'Eco 8 / SCT';
						}
						?>
                </div></td>
				  <td align="left"><select  name="eco_8_lab" id="eco_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)
		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_8_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                        
					 <td align="left"><div align="right">
                   	    <?php if ($labData["eco_9_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 9/ Eco Private Brantford (godin33club)'. '</font>'; 
						}else{
						echo 'Eco 9/ Eco Private Brantford (godin33club)';
						}
						?>
                     </div></td>
				  <td align="left"><select  name="eco_9_lab" id="eco_9_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_9_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    
                        
                       <td   align="left"><div align="right"><a style="text-decoration:none;" title="Cree pour permettde d emettre des coupons uniquement sur cette collection" href="#">
                        <?php if ($labData["innovative_plus_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovative +'. '</font>'; 
						}else{
						echo 'Innovative +';
						}
						?>
                       </a></div></td>
				  <td align="left"><select name="innovative_plus_lab" id="innovative_plus_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovative_plus_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
              
              
              
              
              
                          <tr >			      
				 <td bgcolor="#DDDDDD"  align="left"><div align="right">
                		<?php if ($labData["eco_10_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco 10 (Natural)'. '</font>'; 
						}else{
						echo 'Eco 10 (Natural)';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="eco_10_lab" id="eco_10_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_10_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                        
				 <td bgcolor="#DDDDDD"  align="left"><div align="right">
                		<?php if ($labData["private_collection_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Private Collection'. '</font>'; 
						}else{
						echo 'Private Collection';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="private_collection_lab" id="private_collection_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["private_collection_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                        
                   <td  bgcolor="#DDDDDD"   align="left"><div align="right">
                		<?php if ($labData["eco_eagle_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco Eagle'. '</font>'; 
						}else{
						echo 'Eco Eagle';
						}
						?>
                 </div></td>
				  <td  bgcolor="#DDDDDD" align="left"><select name="eco_eagle_lab" id="eco_eagle_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_eagle_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
              
              
              
              
              
               <tr>			      
				 <td   align="left"><div align="right">
                		<?php if ($labData["innovation_ff_159_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation FF 159'. '</font>'; 
						}else{
						echo 'Innovation FF 159';
						}
						?>
                 </div></td>
				  <td align="left"><select name="innovation_ff_159_lab" id="innovation_ff_159_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_159_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                        
				 <td  align="left"><div align="right">
                		<?php if ($labData["innovation_ff_hd_159_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Innovation FF HD 159'. '</font>'; 
						}else{
						echo 'Innovation FF HD 159';
						}
						?>
                 </div></td>
				  <td align="left"><select name="innovation_ff_hd_159_lab" id="innovation_ff_hd_159_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["innovation_ff_hd_159_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    

                        
                  <td align="left"><div align="right">
                		<?php if ($labData["image_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Image'. '</font>'; 
						}else{
						echo 'Image';
						}
						?>
                 </div></td>
				  <td align="left"><select name="image_lab" id="image_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["image_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
                  
			  </tr>
                
              
  

                          <tr >			      
				 <td bgcolor="#DDDDDD"  align="left"><div align="right">
                		<?php if ($labData["essilor_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Essilor 2 (1.6, 1.67)'. '</font>'; 
						}else{
						echo 'Essilor 2 (1.6, 1.67)';
						}
						?>
                 </div></td>
				  <td bgcolor="#DDDDDD"  align="left"><select name="essilor_2_lab" id="essilor_2_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["essilor_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                       
				 <td  align="left"><div align="right">
                		<?php if ($labData["eco_visionease_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Eco Vision-Ease'. '</font>'; 
						}else{
						echo 'Eco Vision-Ease';
						}
						?>
                 </div></td>
				  <td align="left"><select name="eco_visionease_lab" id="eco_visionease_lab" class="formField">
				   <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["eco_visionease_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                        
                  <td  bgcolor="#DDDDDD"   align="left"><div align="right">&nbsp;</td>
				  <td  bgcolor="#DDDDDD" align="left">&nbsp;</td>
                  
			  </tr>
          
   

              
              <tr><td>&nbsp;</td></tr>
              <tr><td>&nbsp;</td></tr>
              <tr><th>MydenClub</th></tr>
                     
                 <tr>

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Infinysim  =  Swiss" href="#">
                		<?php if ($labData["den_1_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 1'. '</font>'; 
						}else{
						echo 'Den 1';
						}
						?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="den_1_lab" id="den_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_1_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Econo FF, Pro EZ, Sola One =  HKO" href="#">
						<?php if ($labData["den_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 2'. '</font>'; 
						}else{
						echo 'Den 2';
						}
						?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="den_2_lab" id="den_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="My Den FF Active =  Swiss" href="#">
                    	<?php if ($labData["den_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 3'. '</font>'; 
						}else{
						echo 'Den 3';
						}
						?>
                    </a></div></td>
				  <td  bgcolor="#DDDDDD" align="left"><select  name="den_3_lab" id="den_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td align="left">&nbsp;&nbsp;</td>
                  
                  
			  </tr>
              
              
                  <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="Identity by Optotech =  HKO" href="#">
                		<?php if ($labData["den_4_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 4'. '</font>'; 
						}else{
						echo 'Den 4';
						}
						?>
                 </a></div></td>
				  <td align="left"><select  name="den_4_lab" id="den_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_4_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right">
                    	<?php if ($labData["den_5_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 5'. '</font>'; 
						}else{
						echo 'Den 5';
						}
						?>
                     </div></td>
				  <td align="left"><select name="den_5_lab" id="den_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_5_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
				  
				        
					 <td align="left"><div align="right">
                     	<?php if ($labData["den_6_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Den 6'. '</font>'; 
						}else{
						echo 'Den 6';
						}
						?>
                     </div></td>
				  <td align="left"><select name="den_6_lab" id="den_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["den_6_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                  
			  </tr>
              
              
              
              
              
              
              <tr><td>&nbsp;</td></tr>
               <tr><td>&nbsp;</td></tr>
                  <tr><th>BBG</th></tr>
                     
                 <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="My World = Swiss" href="#">
                 		<?php if ($labData["bbg_1_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 1'. '</font>'; 
						}else{
						echo 'Bbg 1';
						}
						?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_1_lab" id="bbg_1_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_1_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Precision HD = Swiss" href="#">
                        <?php if ($labData["bbg_2_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 2'. '</font>'; 
						}else{
						echo 'Bbg 2';
						}
						?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_2_lab" id="bbg_2_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_2_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                    
                    <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Innovative by Optotech =  HKO" href="#">
                        <?php if ($labData["bbg_3_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 3'. '</font>'; 
						}else{
						echo 'Bbg 3';
						}
						?>
                    </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_3_lab" id="bbg_3_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_3_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
				  <td bgcolor="#DDDDDD" align="left">&nbsp;&nbsp;</td>
                  
                  
			  </tr>
              
              
                  <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="Compact Ultra, Lens Choice, SolaOne, Sola Easy =  HKO" href="#">
                  	   <?php if ($labData["bbg_4_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 4'. '</font>'; 
						}else{
						echo 'Bbg 4';
						}
						?>
                 </a></div></td>
				  <td align="left"><select  name="bbg_4_lab" id="bbg_4_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_4_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="Gt2, Gt2-3D = VOT" href="#">
                        <?php if ($labData["bbg_5_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 5'. '</font>'; 
						}else{
						echo 'Bbg 5';
						}
						?>
                     </a></div></td>
				  <td align="left"><select name="bbg_5_lab" id="bbg_5_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_5_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="ClearI = Somo" href="#">
                        <?php if ($labData["bbg_6_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 6'. '</font>'; 
						}else{
						echo 'Bbg 6';
						}
						?>
                     </a></div></td>
				  <td align="left"><select name="bbg_6_lab" id="bbg_6_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_6_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
			   
              
              
              
              
              
                   <tr >

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Summit, Smallfit, Ovation, CMF, ID Life style, PSI => DR" href="#">
                  	    <?php if ($labData["bbg_7_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 7'. '</font>'; 
						}else{
						echo 'Bbg 7';
						}
						?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="bbg_7_lab" id="bbg_7_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_7_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="Innovative +  = HKO  " href="#">
                        <?php if ($labData["bbg_8_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 8'. '</font>'; 
						}else{
						echo 'Bbg 8';
						}
						?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_8_lab" id="bbg_8_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_8_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td bgcolor="#DDDDDD" align="left"><div align="right"><a href="#" style="text-decoration:none;" title="" >
                       <?php if ($labData["bbg_9_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 9'. '</font>'; 
						}else{
						echo 'Bbg 9';
						}
						?>
                     </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select name="bbg_9_lab" id="bbg_9_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_9_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
              
              
              
              
              
              
              
              
              
              
              
    
                <tr >

				      
				 <td align="left"><div align="right"><a style="text-decoration:none;" title="FT35, FT28 = Conant" href="#">
                	   <?php if ($labData["bbg_10_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 10'. '</font>'; 
						}else{
						echo 'Bbg 10';
						}
						?>
                </a></div></td>
				  <td align="left"><select  name="bbg_10_lab" id="bbg_10_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_10_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                        
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="SV, ST28 = SWISS" href="#">
                     <?php if ($labData["bbg_11_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 11'. '</font>'; 
						}else{
						echo 'Bbg 11';
						}
						?>
                     </a></div></td>
				  <td align="left"><select name="bbg_11_lab" id="bbg_11_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_11_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                    
                
			      
					 <td align="left"><div align="right"><a style="text-decoration:none;" title="SelectionRx = SCT" href="#">
                    	 <?php if ($labData["bbg_12_lab"]==0)
						{
						echo '<font color="#FF0000">'.'Bbg 12'. '</font>'; 
						}else{
						echo 'Bbg 12';
						}
						?>
                     </a></div></td>
				  <td align="left"><select name="bbg_12_lab" id="bbg_12_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["bbg_12_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                  
			  </tr>
              
              
                
              <tr><td>&nbsp;</td></tr>
               <tr><td>&nbsp;</td></tr>
              
              
              
              
             <tr><th align="left" colspan="2">Opticien du march&eacute;</th></tr>
                     
                 <tr>

				      
				 <td bgcolor="#DDDDDD" align="left"><div align="right"><a style="text-decoration:none;" title="" href="#">
                		<?php if ($labData["hd_premier_choix_lab"]==0)
						{
						echo '<font color="#FF0000">'.'HD Premier Choix'. '</font>'; 
						}else{
						echo 'HD Premier Choix';
						}
						?>
                 </a></div></td>
				  <td bgcolor="#DDDDDD" align="left"><select  name="hd_premier_choix_lab" id="hd_premier_choix_lab" class="formField">
				      <option value="">Select a Lab</option><?php
	$query="select primary_key, lab_name from labs order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\""; if($labData["hd_premier_choix_lab"]==$labList[primary_key]) echo " selected"; echo ">$labList[lab_name]</option>";
}
?>
				    </select></td>
                    
                    
                                           
				  <td bgcolor="#DDDDDD" align="left"><div align="right">&nbsp;</div></td>
				  <td bgcolor="#DDDDDD" align="left">&nbsp;</td>
                    

                  <td bgcolor="#DDDDDD" align="left"><div align="right">&nbsp;</div></td>
				  <td  bgcolor="#DDDDDD" align="left">&nbsp;</td>
				  <td align="left">&nbsp;&nbsp;</td>
                  
                  
			  </tr>
           
              
              
              
			   
			   
			               <tr >
			                 <th>&nbsp;</th>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
              </tr>
			               <tr >
			                 <th>STOCK COLLECTIONS</th>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
			                 <td align="left">&nbsp;</td>
              </tr>
                     <?php
				       
			   	$stockcollectionsQuery="SELECT * FROM stock_collections WHERE active='1'";
				$stockcollectionsResult=mysql_query($stockcollectionsQuery)
					or die ("ERROR ".mysql_error());
					$lab_count=0;
			  	while($stockCollectionsItem=mysql_fetch_array($stockcollectionsResult)){
					
					$labRedirectionsQuery="SELECT * FROM labs_stock_redirections WHERE main_lab_id='$labData[primary_key]' AND stock_collections_id='$stockCollectionsItem[stock_collections_id]'";
					$labRedirectionsResult=mysql_query($labRedirectionsQuery)
						or die ("ERROR ".mysql_error());
					
					$redirection_labs_ids=array();
					while($labRedirectionItem=mysql_fetch_array($labRedirectionsResult)){
						array_push($redirection_labs_ids,$labRedirectionItem[labs_id]);
					}
					
					$lab_count++;
					if (($lab_count % 2)==1){$color="#DDDDDD";} else {$color="#FFFFFF";}
					echo '<tr bgcolor="'.$color.'"><td align="right">';
					
				 if ($labData["bbg_10_lab"]==0)
						{
						echo '<font color="#FF0000">'.$stockCollectionsItem['stock_collection']. '</font>'; 
						}else{
						echo $stockCollectionsItem['stock_collection'];
						}
						
					
					
					
					echo '</td><td>';
					?>
					
					<select name="<?php echo 'stock_redirection_lab'.$stockCollectionsItem['stock_collections_id'];?>" id="<?php echo 'stock_redirection_lab'.$stockCollectionsItem['stock_collections_id'];?>"class="formField">
				      <option value="">Select a Lab</option>
					  <?php
						$query="select primary_key, lab_name from labs order by lab_name";
						$result=mysql_query($query)
							or die ("Could not find lab list");
						while ($labList=mysql_fetch_array($result)){
							echo "<option value=\"$labList[primary_key]\""; 
							
							foreach($redirection_labs_ids as $v){
								if ($v==$labList[primary_key]) echo " selected"; 
							}
							
							echo ">$labList[lab_name]</option>";
}
?>
				    </select>
					
					
					
					<?php
					echo '</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td><td>&nbsp;</td></tr>';
				}
			  ?>
                  

                  </td>
                  
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                  <td align="left">&nbsp;</td>
                     <td align="left">&nbsp;</td>
              </tr>
 
			  
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$labData[primary_key]"; ?>">
                        <input type="submit" name="editLab" id="editLab" value="Edit Lab" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField">
<br>
<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b>Edit Lab
cannot be reversed.</b></font></td>
            		</tr>
			</table>
</form>