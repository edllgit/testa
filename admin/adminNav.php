<link href="admin.css" rel="stylesheet" type="text/css" />
<?php
require_once(__DIR__.'/../constants/url.constant.php');

if ($_SESSION["access_admin_id"] <> ""){
$queryAccess 	= "SELECT * FROM access_admin WHERE id=" . $_SESSION["access_admin_id"];
$resultAccess	=	mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
$AccessData		=	mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);
$verification_cost				  = $AccessData[verification_cost];
$stc_fast_print					  = $AccessData[stc_fast_print];	
$edit_opticiendumarche_account    = $AccessData[edit_opticiendumarche_account];
$approve_opticiendumarche_account = $AccessData[approve_opticiendumarche_account];
$eagle_fast_shipping_tool	= $AccessData[eagle_fast_shipping_tool];
$ifc_fast_shipping_tool		= $AccessData[ifc_fast_shipping_tool];
$fast_shipping_tool			= $AccessData[fast_shipping_tool];
$fast_redirecting_tool		= $AccessData[fast_redirecting_tool];
$lnc_reward_     			= $AccessData[lnc_reward_];
$create_rebilling_credit	= $AccessData[create_rebilling_credit];
$Approve_Memo_Credit   		= $AccessData[approve_memo_credit];
$customer_password_history  = $AccessData[customer_password_history];
$edit_dl_account			= $AccessData[edit_dl_account];
$approve_dl_account			= $AccessData[approve_dl_account];
$edit_ln_account			= $AccessData[edit_ln_account];
$approve_ln_account			= $AccessData[approve_ln_account];
$edit_ifc_account			= $AccessData[edit_ifc_account];
$approve_ifc_account		= $AccessData[approve_ifc_account];
$approve_ifc_account		= $AccessData[approve_ifc_account];
$manage_labadmin_access		= $AccessData[manage_labadmin_access];
$order_report				= $AccessData[order_report];
$all_product_total			= $AccessData[all_product_total];
$dream_ar_total				= $AccessData[dream_ar_total];
$exclusive_product_total	= $AccessData[exclusive_product_total];
$index_total				= $AccessData[index_total];
$re_billing_statement		= $AccessData[re_billing_statement];
$coupon_code_usage			= $AccessData[coupon_code_usage];
$list_stock_products		= $AccessData[list_stock_products];
$list_exclusive_products	= $AccessData[list_exclusive_products];
$add_exclusive_product		= $AccessData[add_exclusive_product];
$add_stock_price			= $AccessData[add_stock_price];
$frame_collections			= $AccessData[frame_collections];
$frames						= $AccessData[frames];
$frames_temple_colors		= $AccessData[frames_temple_colors];
$edit_lab					= $AccessData[edit_lab];
$add_lab					= $AccessData[add_lab];
$coupon_codes				= $AccessData[coupon_codes];
$upload_new_promotion		= $AccessData[upload_new_promotion];
$collection_rewards			= $AccessData[collection_rewards];	
$report_promo				= $AccessData[report_promo];
$credit_reception		    = $AccessData[credit_reception];
$credit_reception_eagle	    = $AccessData[credit_reception_eagle];
}	

if ($edit_dl_account 	== "yes")$AfficherAccount = "Yes";
if ($approve_dl_account == "yes")$AfficherAccount = "Yes";
if ($edit_ln_account 	== "yes")$AfficherAccount = "Yes";
if ($approve_ln_account == "yes") $AfficherAccount = "Yes";
?>

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="0" class="formField">

   <?php  if ($AfficherAccount == "Yes"){  ?>
    <tr bgcolor="#000000">
    	<td><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><b>ACCOUNTS</b></font></td>
    </tr>
    <?php  } //End if AfficherAccount?>

    <?php  if ($edit_dl_account == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
    	<td align="left"><form name="form1" method="post" action="getAccount.php">
    			<div class="subHeadNav">DIRECT-LENS:</div> EXISTING ACCOUNT<br>
<select name="acctName" id="acctName" class="formField"><option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='approved' and product_line='directlens' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select><input type="submit" name="Submit" value="Go" class="formField"></form></td></tr>
      <?php }//End if edit_dl_account  ?>
        <?php  if ($approve_dl_account == "yes"){ ?><tr bgcolor="#DDDDDD"><td align="left"><form name="form1" method="post" action="getAccount.php">WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField"><option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='directlens' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select><input type="submit" name="Submit" value="Go" class="formField"></form></td>
   	  </tr>
        <?php }//End if $approve_dl_account  ?>
      
        <?php  if ($edit_ln_account == "yes"){ ?>
        <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    			<div class="subHeadNav">LENS NET CLUB</div> Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="select primary_key, company, last_name, first_name from accounts where approved='approved' and product_line='lensnetclub' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   </select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
   	  </tr>
 
        <?php }//End if $edit_ln_account  ?>

    <?php  if ($approve_ln_account == "yes"){ ?>
        <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    			 WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='lensnetclub' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select>
    			<input type="submit" name="Submit" value="Go" class="formField">
</form></td>
   	  </tr>

       <?php }//End if $approve_ln_account  ?>
       
     


       
       
       
       

       
       <?php  if ($edit_ifc_account == "yes"){ ?>
       <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#DDDDDD"><form name="form1" method="post" action="getAccount.php">
    				<div class="subHeadNav">IFC OPTIC CA CLUB</div> Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts where approved='approved' and product_line='ifcclubca' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
        <?php }//End if $edit_ifc_account  ?>

    <?php  if ($approve_ifc_account == "yes"){ ?>
        <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#DDDDDD"><form name="form1" method="post" action="getAccount.php">
    			 WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='ifcclubca' order by company, last_name";
	$result=mysqli_query($con,$query)
		or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
       <?php }//End if $approve_ifc_account  ?>
       
           
       
      
       
       
       
      
       
      <?php  if ($edit_ifc_account == "yes"){ ?>
       <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    				<div class="subHeadNav">MILANO6769 CANADA</div> Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts where approved='approved' and product_line='milano6769' order by company, last_name";
	$result=mysqli_query($con,$query)		or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
        <?php }//End if $edit_ifc_account  ?>

    <?php  if ($approve_ifc_account == "yes"){ ?>
        <tr bgcolor="#FFFFFF">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    			 WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='milano6769' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
       <?php }//End if $approve_ifc_account  ?>
       
  
       
       
       
       
       <?php //SAFETY  ?>
         <?php  if ($edit_opticiendumarche_account == "yes"){ ?>
       <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#DDDDDD"><form name="form1" method="post" action="getAccount.php">
    				<div class="subHeadNav">SAFETY</div> Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='approved' and product_line='safety' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?></select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
        <?php }//End if $edit_opticiendumarche_account  ?>

    <?php  if ($approve_opticiendumarche_account == "yes"){ ?>
        <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#DDDDDD"><form name="form1" method="post" action="getAccount.php">
    			 WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='safety' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
       <?php }//End if $approve_opticiendumarche_account  ?>
       
       
       
       
       
       
       
        <?php //EYE RECOMMEND  ?>
         <?php  if ($edit_opticiendumarche_account == "yes"){ ?>
       <tr bgcolor="#FFFFFF">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    				<div class="subHeadNav">PRESTIGE</div> Existing Account<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='approved' and product_line='eye-recommend' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}

?></select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
        <?php }//End if $edit_opticiendumarche_account  ?>

    <?php  if ($approve_opticiendumarche_account == "yes"){ ?>
        <tr bgcolor="#FFFFFF">
    	<td align="left" bgcolor="#FFFFFF"><form name="form1" method="post" action="getAccount.php">
    			 WAITING FOR APPROVAL<br>
    			<select name="acctName" id="acctName" class="formField">
    				<option value="">Select Account</option>
    				<?php
	$query="SELECT primary_key, company, last_name, first_name FROM accounts WHERE approved='pending' and product_line='eye-recommend' order by company, last_name";
	$result=mysqli_query($con,$query) or die ("Could not find account list");
	while ($accountList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
   				</select>
    			<input type="submit" name="Submit" value="Go" class="formField">

    			</form></td>
  </tr>
       <?php }//End if $approve_opticiendumarche_account  ?>
       
       
       
       
            
       
       
      
<?php  
if ($order_report 	== "yes")
$AfficherReports = "yes";
if ($dream_ar_total == "yes")
$AfficherReports = "yes";
if ($all_product_total 	== "yes")
$AfficherReports = "yes";
if ($exclusive_product_total == "yes")
$AfficherReports = "yes";
if ($index_total == "yes")
$AfficherReports = "yes";
if ($re_billing_statement == "yes")
$AfficherReports = "yes";
if ($coupon_code_usage == "yes")
$AfficherReports = "yes";
if ($_people_report == "yes")
$AfficherReports = "yes";
if ($customer_password_history == "yes")
$AfficherReports = "yes";
?> 
      
    <?php  if ($AfficherReports == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">REPORTS</font></b></td>
   	</tr>
    <?php }//End if $AfficherReports  ?>
    

    
     <?php  if ($order_report == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
		<td align="left"><p><a style="text-decoration:none;" href="report.php?reset=y">Order Reports</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>

	<?php  if ($_SESSION["access_admin_id"]==2){    ?>
	<tr>
		<td align="left"><p><a href="reports_all_products.php">All Products Totals</a></p></td>
	  </tr>
 	<?php }//End if Access=Ted #2  ?>
	
      <?php  if ($verification_cost == "yes"){ ?>
    <tr bgcolor="#FFFFFF">
		<td align="left"><p><a style="text-decoration:none;" href="cost.php?reset=y"><b>EDLL</b> Cost Verifying Tool</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>
     
     <?php  if ($verification_cost == "yes"){ ?>
    <tr bgcolor="#FFFFFF">
		<td align="left"><p><a style="text-decoration:none;" href="cost_hko.php?reset=y"><b>HKO</b> Cost Verifying Tool</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>
	
	 <?php  if ($verification_cost == "yes"){ ?>
    <tr bgcolor="#FFFFFF">
		<td align="left"><p><a style="text-decoration:none;" href="cost_gkb.php?reset=y"><b>GKB</b> Cost Verifying Tool</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>
	
	<?php  if ($verification_cost == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
		<td align="left"><p><a style="text-decoration:none;" href="cost_validation_knr.php"><b>KNR</b> Cost Verifying Tool</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>
    
	
	<?php  if ($verification_cost == "yes"){ ?>
    <tr bgcolor="#FFFFFF">
		<td align="left"><p><a style="text-decoration:none;" href="cost_validation_hbc.php"><b>HBC</b> Cost Verifying Tool</a></p></td>
   	</tr>
     <?php }//End if $order_report  ?>
    

      
       <?php  if ($coupon_code_usage == "yes"){ ?>
       <tr bgcolor="#DDDDDD">
		<td align="left"><p><a  style="text-decoration:none;" href="reports_coupons.php">Coupon code Usage</a></p></td>
	  </tr>
      <?php }//End if $coupon_code_usage  ?>
      
      
     
      
      
            
      
<?php  
if ($list_stock_products 	== "yes")
$AfficherProduct = "yes";
if ($list_exclusive_products == "yes")
$AfficherProduct = "yes";
if ($add_exclusive_product 	== "yes")
$AfficherProduct = "yes";
if ($add_stock_price == "yes")
$AfficherProduct = "yes";
?> 
      
     <?php  if ($AfficherProduct == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" valign="middle" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">PRODUCTS</font></b></td>
    </tr>
     <?php }//End if $AfficherProduct  ?>
     
    
     <?php  if ($list_stock_products == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#FFFFFF"><a style="text-decoration:none;" href="getCategory.php?category=stock">List Stock Products</a></td>
    </tr>
      <?php }//End if $list_stock_products  ?>
    
    
     <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_dl_products.php">List DIRECTLENS Exclusives Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
    
    
     <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_ifcca_products.php">List IFC.CA Exclusives Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
    
     <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_safety_products.php">List SAFETY Exclusives Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
    
    <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_LN_products.php">List LENSNETCLUB Exclusives Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
    
    <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_er_products.php">List EYE RECOMMEND Exclusives Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
	
	 <?php  if ($list_exclusive_products == "yes"){ ?>
	<tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a target="_blank" style="text-decoration:none;" href="list_hbc_products.php">List HBC Products</a></td>
    </tr>
    <?php }//End if $list_exclusive_products  ?>
    
    
    
    <?php /*?><?php  if ($add_exclusive_product == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#FFFFFF"><a style="text-decoration:none;" href="newExclusiveProduct.php">Add
      a Direct-lens Exclusive Product </a></td>
    </tr>
    <?php }//End if $add_exclusive_product  ?>
    
    
     <?php  if ($add_stock_price == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="newProduct.php">Add
	  a Stock Price</a></td>
    </tr>
      <?php }//End if $add_stock_price  ?><?php */?>
    
    
<?php  
if ($frame_collections 	== "yes")
$AfficherFrame = "yes";
if ($frames == "yes")
$AfficherFrame = "yes";
if ($frames_temple_colors 	== "yes")
$AfficherFrame = "yes";
?> 
    
    
    <?php  if ($AfficherFrame == "yes"){ ?>
     <tr bgcolor="#DDDDDD">
      <td align="left" valign="middle" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">FRAMES</font></b></td>
    </tr>  
     <?php }//End if $AfficherFrame  ?>
    
    
      
     <?php  if ($frames == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
        <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="newFrame.php">Frames</a></td>
  	</tr>
    
    <tr bgcolor="#DDDDDD">
        <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="frame_report.php">Frames Report</a></td>
  	</tr>
    <?php }//End if $frames  ?>
    
      
      
     <?php  if (($edit_lab == "yes") || ($add_lab == "yes" ))   { ?>
    <tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">LABS</font></b></td>
    </tr>
      <?php }//End if AfficherLab  ?>
    
    
    
     <?php  if ($edit_lab == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
		<td align="left" bgcolor="#DDDDDD"><form action="getLab.php" method="post" name="form2" id="form2">
				Select Lab <br />
				<select name="lab" class="formField">
					<option value="" selected="selected">Select Lab</option>
					<?php
	$query="SELECT primary_key, lab_name FROM labs order by lab_name";
	$result=mysqli_query($con,$query) or die ("Could not find lab list");
	while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
?>
				</select>
		<input type="submit" name="Submit" value="Go" class="formField" /><br />
		(edit  lab)
		</form></td>
  </tr>
        <?php }//End if $edit_lab  ?>
      
  
       
     
     <?php  if (($coupon_codes == "yes") || ($upload_new_promotion == "yes" ))   { ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">PROMOTIONS</font></b></td>
    </tr>
     <?php } //End if Afficher Promo ?>
    
    
    
     <?php  if ($coupon_codes == "yes"){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#FFFFFF"><a style="text-decoration:none;" href="newCoupon.php">Coupon Codes </a></td>
    </tr>
    <?php } //End if coupon_codes ?>
    
    

     
	 	
    
    
      <?php  if ($lnc_reward_ == "yes"){ ?>
      
       
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">LNC REWARD </font></b></td>
    </tr>
   
     
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="lnc_reward.php">LensnetClub Rewards </a></td>
    </tr>
     <?php } //End if upload_new_promotion ?>
    
    
       <?php  if ($fast_shipping_tool == "yes"){ ?>
      
       
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">FAST SHIPPING/OUTIL D'EXP&Eacute;DITION RAPIDE</font></b></td>
    </tr>
   
     
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="shipping.php">Fast Shipping Tool/Outil d'exp&eacute;dition rapide</a></td>
    </tr>
	
	 <tr bgcolor="#A2C958">
      <td align="left" bgcolor="#A2C958"><a style="text-decoration:none;" href="shipping_hbc.php">HBC Fast Shipping Tool/Outil d'exp&eacute;dition rapide HBC</a></td>
    </tr>
    
        
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="tray.php">Tray Search Tool</a></td>
    </tr>
     <?php } //End if fast_shipping_tool ?>
     
     
     
  
       
   
    
    
      <?php /*?> <?php  if ($eagle_fast_shipping_tool == "yes"){ ?>
      
       
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">EAGLE FAST SHIPPING</font></b></td>
    </tr>
   
     
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="shipping_ifc.php">EAGLE Fast Shipping Tool</a></td>
    </tr>
     <?php } //End if eagle_fast_shipping_tool ?><?php */?>
    
    
    
        <?php  if ($fast_redirecting_tool == "yes"){ ?>
      
       
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">FAST REDIRECTING</font></b></td>
    </tr>
   
     
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="redirecting.php">Fast Redirecting Tool</a></td>
    </tr>
     <?php } //End if fast_redirecting_tool ?>
    
    

    <?php  if (($credit_reception == "yes") || ($Approve_Memo_Credit == "yes")){?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">NEW CREDIT </font></b></td>
    </tr>
    <?php } ?>
    
     <tr bgcolor="#DDDDDD">
		<td align="left"><p><a style="text-decoration:none;" href="email_credit.php">Email a Credit</a></p></td>
	  </tr>
    
    
     <?php  if ($credit_reception == "yes"){?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="credit_reception.php">Credit/Lens Reception</a></td>
    </tr>
    <?php } ?>
    
       
     <?php  if ($credit_reception_eagle == "yes"){?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="credit_reception.php">Credit/Lens Reception Eagle</a></td>
    </tr>
    <tr>
      <td align="left"><a style="text-decoration:none;" href="credit_search_eagle.php">Credit Search Eagle</a></td>
    </tr>
    <?php } ?>
    
    
     <?php  if ($Approve_Memo_Credit == "yes"){?>
    <tr>
      <td align="left"><a style="text-decoration:none;" href="credit_search.php">Credit Search</a></td>
    </tr>
   <?php } ?>
    
	
	 <?php if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" href="fast_print_no_prices.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Print <b>STC</b> Orders  <u>without Prices</u> (for Swiss)';
            }else {
            echo 'Print <b>STC</b> Orders <u>without Prices</u> (for Swiss)';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	 <?php if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" href="fast_print_no_prices_hbc.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Print <b>HBC</b> Orders  <u>without Prices</u> (for Swiss)';
            }else {
            echo 'Print <b>HBC</b> Orders <u>without Prices</u> (for Swiss)';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
  
    
    
  

  <?php  if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" href="fast_print.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo '<b>STC</b> Fast Print';
            }else {
            echo '<b>STC</b> Fast Print';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print 
  
	
  
   if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" href="fast_print_hbc.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo '<b>HBC</b> Fast Print';
            }else {
            echo '<b>HBC</b> Fast Print';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
  
 
  
  
    <?php if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" href="report_send_frame_swiss.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Generate Daily Report';
            }else {
            echo 'Generate Daily Report';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 <?php 
	//NEW CREDIT MANAGEMENT HBC
	if (($credit_reception == "yes") || ($Approve_Memo_Credit == "yes")){?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">CREDITS MANAGEMENT: HBC</font></b></td>
    </tr>
    <?php } ?>
    
    
     <?php  if ($credit_reception == "yes"){?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#DDDDDD"><a style="text-decoration:none;" href="credit_reception_hbc.php">HBC Credit/Lens Reception</a></td>
    </tr>
    <?php } ?>
        
    
     <?php  if ($Approve_Memo_Credit == "yes"){?>
    <tr>
      <td align="left"><a style="text-decoration:none;" href="credit_search_hbc.php">HBC Credit Search</a></td>
    </tr>
   <?php } ?>

      <tr bgcolor="#DDDDDD">
		<td align="left"><p><a style="text-decoration:none;" href="email_credit_hbc.php">Email a Credit</a></p></td>
	  </tr>        
         
  
	
   
  
  
   <?php if ($stc_fast_print  == 'yes'){ 
   ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">EDLL: SWISS FRAMES MANAGEMENT</font></b></td>
    </tr>
  <tr><td>  <span><a style="text-decoration:none;" href="send_frame_swiss.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Send <b>EDLL</b> Frames to Swiss (Add frames to the report)';
            }else {
            echo 'Send <b>EDLL</b> Frames to Swiss (Add frames to the report)';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	
	 <?php if ($stc_fast_print  == 'yes'){ ?>
  <tr><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/cron_report_daily_send_frame_swiss.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'View list of <b>EDLL</b> frames that leaves today (SWISS)';
            }else {
            echo 'View list of <b>EDLL</b> frames that leaves today (SWISS)';
            }
            ?>
            </a></span></td></tr>
	
	
  <?php  }//end if stc_fast_print ?>
	
	
  <?php if ($stc_fast_print  == 'yes'){
/*	  ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">HBC: SWISS FRAMES MANAGEMENT</font></b></td>
    </tr>
  <tr bgcolor="#A2C958"><td>  <span><a style="text-decoration:none;" href="frame_sent_swiss_hbc.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Send <b>HBC</b> Frames to Swiss (Add frames to the report)';
            }else {
            echo 'Send <b>HBC</b> Frames to Swiss (Add frames to the report)';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
    
 
  
   <?php if ($stc_fast_print  == 'yes'){ ?>
  <tr bgcolor="#A2C958"><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/cron_report_daily_send_hbc_frame_swiss.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'View list of <b>HBC</b> frames that leaves today (SWISS)';
            }else {
            echo 'View list of <b>HBC</b> frames that leaves today (SWISS)';
            }
            ?>
            </a></span></td></tr>
  <?php  */}//end if stc_fast_print ?>
 
	
	 <?php if ($stc_fast_print  == 'yes'){ ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">EDLL: KNR FRAMES MANAGEMENT</font></b></td>
    </tr>
  <tr><td>  <span><a style="text-decoration:none;" href="send_frame_knr.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Send <b>EDLL</b> Frames to KNR';
            }else {
            echo 'Send <b>EDLL</b> Frames to KNR';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
  
  
  <?php if ($stc_fast_print  == 'yes'){ ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">HBO: KNR FRAMES MANAGEMENT</font></b></td>
    </tr>
  <tr><td>  <span><a style="text-decoration:none;" href="send_hbc_frame_knr.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Send <b>HBO</b> Frames to KNR';
            }else {
            echo 'Send <b>HBO</b> Frames to KNR';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	
	
	
   <?php if ($stc_fast_print  == 'yes'){ ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">EDLL: FRAMES SENT TO THE LAB REPORT</font></b></td>
    </tr>
  <tr><td>  <span><a style="text-decoration:none;" href="report_frames_sent_to_the_lab.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'VOIR RAPPORTS <b>EDLL</b> Frames sent to the lab  Saint-Catharines';
            }else {
            echo 'VIEW REPORTS <b>EDLL</b> Frames sent to the lab at Saint-Catharines';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	
		
   <?php if ($stc_fast_print  == 'yes'){ ?>
	 <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">HBC: FRAMES SENT TO THE LAB REPORT</font></b></td>
    </tr>
  <tr><td>  <span><a style="text-decoration:none;" href="report_frames_sent_to_the_lab_hbc.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'VOIR RAPPORTS <b>HBC</b> Frames sent to the lab  Saint-Catharines';
            }else {
            echo 'VIEW REPORTS <b>HBC</b> Frames sent to the lab at Saint-Catharines';
            }
            ?>
            </a></span></td></tr>
  <?php  }//end if stc_fast_print ?>
	
	
	
  <?php if ($_SESSION["access_admin_id"] <>'48'){ ?>
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000"><b><font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif">WHERE PRODUCTS ARE REDIRECTED</font></b></td>
    </tr>    
  <tr><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/who_product_redirected.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Produits <b>Entrepot de la lunette</b>';
            }else {
            echo '<b>EDLL</b> Products';
            }
            ?>
            </a></span></td></tr>
            
         <tr><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/who_product_redirected_safe.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Produits SADE';
            }else {
            echo '<b>SAFETY</b> Products';
            }
            ?>
            </a></span></td></tr>
            
            
               <tr><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/who_product_redirected_lnc.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Produits <b>LensNet Club</b>';
            }else {
            echo '<b>LENSNETCLUB</b> Products';
            }
            ?>
            </a></span></td></tr>
            
            <tr><td>  <span><a style="text-decoration:none;" target="_blank" href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/who_product_redirected_dl.php">
            <?php if ($mylang == 'lang_french' || $mylang == 'lang_France'){
            echo 'Produits <b>Direct-Lens</b>';
            }else {
            echo '<b>DIRECT-LENS</b> Products';
            }
            ?>
            </a></span></td></tr>
 <?php  }//end IF  ID=48 ?>

	
	
	
    
    
    <tr bgcolor="#DDDDDD">
      <td align="left" bgcolor="#000000">&nbsp;</td>
    </tr>
    <tr bgcolor="#DDDDDD">
    	<td align="left" bgcolor="#DDDDDD"><p><a href="logout.php">Logout</a> <b>[<?php echo $_SESSION[adminData][username]?>]</b></p></td>
  </tr>
</table>
