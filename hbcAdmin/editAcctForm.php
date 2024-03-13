<?php require_once('../Connections/directlens.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysql_select_db($database_directlens, $directlens);
$query_sales_reps = "SELECT * FROM sales_reps where id = '$accountData[generation_lab]'";
$sales_reps = mysql_query($query_sales_reps, $directlens) or die(mysql_error());
$row_sales_reps = mysql_fetch_assoc($sales_reps);
$totalRows_sales_reps = mysql_num_rows($sales_reps);mysql_select_db($database_directlens, $directlens);
$query_sales_reps = "SELECT * FROM sales_reps";
$sales_reps = mysql_query($query_sales_reps, $directlens) or die(mysql_error());
$row_sales_reps = mysql_fetch_assoc($sales_reps);
$totalRows_sales_reps = mysql_num_rows($sales_reps);
?>
<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<link href="admin.css" rel="stylesheet" type="text/css" />
<form name="form3" method="post" action="getAccount.php" onSubmit="return formCheck(this);">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="10"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_admacctfrm_txt; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td width="7%" align="left" ><div align="right">
												<?php echo $adm_title_txt; ?>						
					</div></td>
					<td colspan="5" align="left" nowrap="nowrap">
						<select name="title" class="formField" id="title">
							<option value=""><?php echo $adm_select_txt; ?></option>
							<option value="<?php echo $adm_dr_txt; ?>" <?php if($accountData["title"]==$adm_dr_txt) echo " selected"; ?>><?php echo $adm_dr_txt; ?></option>
							<option value="<?php echo $adm_mr_txt; ?>" <?php if($accountData["title"]==$adm_mr_txt) echo " selected"; ?>><?php echo $adm_mr_txt; ?></option>
							<option value="<?php echo $adm_ms_txt; ?>" <?php if($accountData["title"]==$adm_ms_txt) echo " selected"; ?>><?php echo $adm_ms_txt; ?></option>
							<option value="<?php echo $adm_mrs_txt; ?>" <?php if($accountData["title"]==$adm_mrs_txt) echo " selected"; ?>><?php echo $adm_mrs_txt; ?></option>
						</select>					
                        
                        <div align="right">
						<b><?php echo 'Contact name:'; ?><input name="contact_name" type="text" class="formField" id="contact_name" value="<?php echo $accountData["contact_name"]; ?>" size="20"></b>
						</div>
                        </td>
                        
                       
                        
                        
					<td width="17%" align="left" nowrap><div align="right">
												<?php echo $adm_fname_txt; ?>						
					</div></td>
					<td width="19%" align="left">
						<input name="first_name" type="text" class="formField" id="first_name" value="<?php echo $accountData["first_name"]; ?>" size="20">					</td>
					<td width="9%" align="left" nowrap><div align="right">
												<?php echo $adm_lname_txt; ?>						
					</div></td>
					<td width="20%" align="left">
						<input name="last_name" type="text" class="formField" id="last_name" value="<?php echo $accountData["last_name"]; ?>" size="20">					</td>
						

					
				</tr>
				<tr>
					<td align="left" ><div align="right">
						<?php echo $adm_acctnum_txt; ?>
					</div></td>
					<td colspan="3" align="left" ><?php echo $accountData["account_num"]; ?></td>
                    <td align="left" ><div align="right">
						<b><?php echo 'Member since:'; ?></b>
					</div></td>
                    <td align="left"><?php echo $accountData["member_since"]; ?></td>
					<td align="left" ><div align="right">
							 <?php echo $adm_company_txt; ?> 
					</div></td>
					<td align="left">
						<input name="company" type="text" id="company" size="20" value="<?php echo $accountData["company"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_buygrp_txt; ?> 
					</div></td>
					<td align="left">
						<select name="buying_group" class="formField" id="buying_group">
							<?php
	$query="select primary_key, bg_name from buying_groups order by bg_name";
	$result=mysql_query($query)
		or die ("Could not find bg list");
	while ($bgList=mysql_fetch_array($result)){
		echo "<option value=\"$bgList[primary_key]\""; if($accountData["buying_group"]==$bgList[primary_key]) echo " selected"; echo ">$bgList[bg_name]</option>";
}
?>
</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
							<?php echo $adm_orderby_txt; ?>  
					</div></td>
					<td colspan="5" align="left" ><input name="purchase_unit" type="radio" value="single" <?php if($accountData["purchase_unit"]=="single") echo " checked"; ?> class="formField" /><?php echo $adm_single_txt; ?>
								<input name="purchase_unit" type="radio" value="pair" <?php if($accountData["purchase_unit"]=="pair") echo " checked"; ?> class="formField" /><?php echo $adm_pair_txt; ?></td>
					<td align="left" ><div align="right">
						<?php echo $adm_bustype_txt; ?></div></td>
					<td align="left"><select name="business_type" class="formField">
						<option value="Optometrist Office"<?php if($accountData["business_type"]=="Optometrist Office") echo " selected"; ?>><?php echo $adm_optoffice_txt; ?></option>
						<option value="Optician Office"   <?php if($accountData["business_type"]=="Optician Office")    echo " selected"; ?>><?php echo $adm_optioffice_txt; ?></option>
						<option value="Lab"               <?php if($accountData["business_type"]=="Lab")                echo " selected"; ?>><?php echo $adm_lab_txt; ?></option>
					</select>					</td>
					<td align="left" nowrap><div align="right"> <?php echo $adm_currency_txt; ?> </div></td>
					<td align="left">	<select name="currency" id="currency" class="formField">
		<option value="US" <?php if($accountData["currency"]=="US") echo " selected"; ?>>US Dollar</option>
		<option value="CA" <?php if($accountData["currency"]=="CA") echo " selected"; ?>>CA Dollar</option>
		<option value="EUR" <?php if($accountData["currency"]=="EUR") echo " selected"; ?>>EU Euro</option>
	</select></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right">
							<?php echo $adm_discounts_txt; ?> 
					</div></td>
					<td width="4%" align="left" ><div align="right">
						<?php echo $adm_rx_txt; ?>
					</div></td>
					<td width="8%" align="left" ><div align="right">
						<?php echo $adm_myworld_txt; ?>
					</div></td>
					<td align="left" >
						<input name="innovative_dsc" type="text" id="innovative_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovative_dsc"]; ?>" class="formField" /></td>
					<td align="left" nowrap="nowrap" ><div align="right">
						<?php echo $adm_visionpro_txt; ?>
					</div></td>
					<td align="left" ><input name="visionpro_dsc" type="text" id="visionpro_dsc" size="4" maxlength="4" value="<?php echo $accountData["visionpro_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						 <?php echo $adm_fax_txt; ?> 
					</div></td>
					<td align="left">
						<input name="fax" type="text" id="fax" size="20" value="<?php echo $accountData["fax"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_vatnum_txt; ?>
					</div></td>
					<td align="left">
						<input name="VAT_no" type="text" id="VAT_no" size="20" value="<?php echo $accountData["VAT_no"]; ?>" class="formField" />					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_infocus_txt; ?> 
					</div></td>
					<td align="left" >
						<input name="infocus_dsc" type="text" id="infocus_dsc" size="4" maxlength="4" value="<?php echo $accountData["infocus_dsc"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap" ><div align="right">
						<?php echo $adm_vispropoly_txt; ?>
					</div></td>
					<td align="left" ><input name="visionpropoly_dsc" type="text" id="visionpropoly_dsc" size="4" maxlength="4" value="<?php echo $accountData["visionpropoly_dsc"]; ?>" class="formField" /></td>
					
					
					<td align="left" ><div align="right">
						 <?php echo $adm_email_txt; ?> 
					</div></td>
					<td align="left">
						<input name="email" type="text" id="email" size="20" value="<?php echo $accountData["email"]; ?>" class="formField" />

					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				  <td align="right"><?php echo $adm_shipdatenot_txt; ?></td>
					<td align="left"><input name="email_notification" type="text"  size="20" value="<?php echo $accountData["email_notification"]; ?>" class="formField" /></td>
												
	
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_precision_txt; ?>
					</div></td>
					<td align="left" >
						<input name="precision_dsc" type="text" id="precision_dsc" size="4" maxlength="4" value="<?php echo $accountData["precision_dsc"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap" ><div align="right">
						<?php echo $adm_viseco_txt; ?>
					</div></td>
					<td align="left" ><input name="visioneco_dsc" type="text" id="visioneco_dsc" size="4" maxlength="4" value="<?php echo $accountData["visioneco_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						<?php echo $adm_login_txt; ?> 
					</div></td>
					<td align="left"><b><?php echo $accountData["user_id"]; ?></b> </td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_password_txt; ?> 
					</div></td>
					<td align="left">
						<input name="password" type="password" id="password" size="20" value="<?php echo $accountData["password"]; ?>" class="formField" /><a target="_blank" style="text-decoration:none;" href="showcustomerpassword.php?user_id=<?php echo $accountData["user_id"]; ?>">Show Password</a>					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right"> <?php echo $adm_gener_txt; ?> </div></td>
					<td align="left" ><input name="generation_dsc" type="text" id="generation_dsc" size="4" maxlength="4" value="<?php echo $accountData["visioneco_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right"> <?php echo $adm_truehd_txt; ?> </div></td>
					<td align="left" ><input name="truehd_dsc" type="text" id="truehd_dsc" size="4" maxlength="4" value="<?php echo $accountData["visioneco_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						<?php echo $adm_acctappr_txt; ?>  
					</div></td>
				  <td colspan="3" align="left">
				  <select name="approved" class="formField">
					<option value = "pending" <?php if($accountData["approved"]=="pending") echo " selected"; ?>>pending</option>
					<option value = "approved" <?php if($accountData["approved"]=="approved") echo " selected"; ?>>approved</option>
					<option value = "declined" <?php if($accountData["approved"]=="declined") echo " selected"; ?>>declined</option>
				 </select>	
						
										
					&nbsp;&nbsp;&nbsp;
					<?php echo $adm_accttype_txt; ?> 	
					<select name="account_type" class="formField">
					<option value = "restricted" <?php if($accountData["account_type"]=="restricted") echo " selected"; ?>>restricted</option>
                    <option value = "consolidated" <?php if($accountData["account_type"]=="consolidated") echo " selected"; ?>>consolidated</option>
					<option value = "normal" <?php if($accountData["account_type"]=="normal") echo " selected"; ?>>normal</option>
					</select>	
					
					
					&nbsp;&nbsp;&nbsp;
					<?php echo $adm_language_txt; ?>	
					<select name="language" class="formField">
					<option value = "french" <?php if($accountData["language"]=="french") echo " selected"; ?>>French</option>
					<option value = "english" <?php if($accountData["language"]=="english") echo " selected"; ?>>English</option>
					</select>			
					&nbsp;&nbsp;
                    Depot Number: <input name="depot_number" type="text" id="depot_number" size="10" maxlength="15" value="<?php echo $accountData["depot_number"]; ?>" class="formField" />
					&nbsp;&nbsp; 
                   	<br />Bill to: &nbsp;&nbsp;&nbsp;
                    
				    <select name = "bill_to" id="bill_to" class="formField">
                        <option value="">Select One</option>
                        <option value ="B00020"	<?php if($accountData["bill_to"]=="B00020") echo " selected"; ?>>B00020</option>
                        <option value ="B00021" <?php if($accountData["bill_to"]=="B00021") echo " selected"; ?>>B00021</option>
                        <option value ="B00022" <?php if($accountData["bill_to"]=="B00022") echo " selected"; ?>>B00022</option>
                        <option value ="B00023" <?php if($accountData["bill_to"]=="B00023") echo " selected"; ?>>B00023</option>
                        <option value ="B00024" <?php if($accountData["bill_to"]=="B00024") echo " selected"; ?>>B00024</option>
                        <option value ="B00025" <?php if($accountData["bill_to"]=="B00025") echo " selected"; ?>>B00025</option>
                        <option value ="B00026" <?php if($accountData["bill_to"]=="B00026") echo " selected"; ?>>B00026</option>
                        <option value ="B00027" <?php if($$accountData["bill_to"]=="B00027") echo " selected"; ?>>B00027</option>
					</select>                    
                    		
					&nbsp;&nbsp;</td>
		
				</tr>
				
				
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right"> <?php echo $adm_easyfithd_txt; ?> </div></td>
					<td align="left" ><input name="easy_fit_dsc" type="text" id="easy_fit_dsc" size="4" maxlength="4" value="<?php echo $accountData["easy_fit_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">Private 1 (GRM Swiss)</div></td>
					<td align="left" ><input name="private_1_dsc" type="text" id="private_1_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_1_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						<?php echo $adm_credhold_txt; ?> 
					</div></td>
					<td colspan="3" align="left"><input name="credit_hold" type="checkbox" class="formField" id="credit_hold" value="1" <?php if($accountData["credit_hold"]=="1") echo " checked"; ?> />
						<?php echo $adm_ifchecked_txt; ?> </td>
	
				</tr>
				
				<tr bgcolor="#FFFFFF">
				  <td align="left" bgcolor="#DDDDDD" >&nbsp;</td>
				  <td colspan="2" align="left" bgcolor="#DDDDDD" ><div align="right">Glass</div></td>
				  <td align="left" bgcolor="#DDDDDD" ><input name="glass_dsc" type="text" id="glass_dsc" size="4" maxlength="4" value="<?php echo $accountData["glass_dsc"]; ?>" class="formField" /></td>
				  <td align="left" bgcolor="#DDDDDD" ><div align="right">Private 2 (Gen 3)</div></td>
				  <td align="left" bgcolor="#DDDDDD" ><input name="private_2_dsc" type="text" id="private_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_2_dsc"]; ?>" class="formField" /></td>
				 <td align="left" ><div align="right">
						Display double warning
					</div></td>
				 <td colspan="3" align="left"><input name="display_double_warning" type="checkbox" class="formField" id="display_double_warning" value="yes" <?php if($accountData["display_double_warning"]=="yes") echo " checked"; ?> />
                 
                 
			  </tr>
				<tr bgcolor="#FFFFFF">
				  <td align="left" >&nbsp;</td>
				  <td colspan="2" align="left" ><div align="right">Eco</div></td>
				  <td align="left" ><input name="eco_dsc" type="text" id="eco_dsc" size="4" maxlength="4" value="<?php echo $accountData["eco_dsc"]; ?>" class="formField" /></td>
				  <td align="left" ><div align="right">Private 3 (Neco)</div></td>
				  <td align="left" ><input name="private_3_dsc" type="text" id="private_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_3_dsc"]; ?>" class="formField" /></td>
				  <td align="left" nowrap="nowrap" ><div align="right"> Credit Limit
				    
			      </div></td>
				  <td colspan="3" align="left"><input name="cl_limit_amt" type="text" id="cl_limit_amt" size="20" value="<?php echo $accountData["cl_limit_amt"]; ?>" class="formField" />
				    If not empty, customer must pay for new orders with a credit<br />
card if account balance is equal or greater than limit</td>
			  </tr>
			  
			  
			  <tr>
				  <td  bgcolor="#DDDDDD" align="left" >&nbsp;</td>
				  <td   bgcolor="#DDDDDD"colspan="2" align="left" ><div align="right">Vot (Ovation)</div></td>
				  <td bgcolor="#DDDDDD" align="left" ><input name="vot_dsc" type="text" id="vot_dsc" size="4" maxlength="4" value="<?php echo $accountData["vot_dsc"]; ?>" class="formField" /></td>
				  <td  bgcolor="#DDDDDD" align="left" ><div align="right">Private 4 (Essi.)</div></td>
				  <td  bgcolor="#DDDDDD" align="left" ><input name="private_4_dsc" type="text" id="private_4_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_4_dsc"]; ?>" class="formField" /></td>
                  
                  
				  <td align="left" nowrap="nowrap" ><div align="right"> Allow Special Instructions
				    
			      </div></td>
				  <td colspan="3" align="left"><input name="allow_special_instruction" type="checkbox" class="formField" id="allow_special_instruction" value="yes" <?php if($accountData["allow_special_instruction"]=="yes") echo " checked"; ?> /> If checked, the customer will be able to type his own special instruction in his LensnetClub orders</td>

			  </tr>
			  
			  <tr bgcolor="#FFFFFF">
				  <td   align="left" >&nbsp;</td>
				  <td  colspan="2" align="left" ><div align="right">Glass 2</div></td>
				  <td  align="left" ><input name="glass_2_dsc" type="text" id="glass_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["glass_2_dsc"]; ?>" class="formField" /></td>
				  

				  <td  align="left" ><div align="right">Private 5</div></td>
				  <td   align="left" ><input name="private_5_dsc" type="text" id="private_5_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_5_dsc"]; ?>" class="formField" /></td>
				  <td align="left" ><div align="right">
						<b>Automatically Load Last Rx with Both Eyes</b>(Lensnet)
					</div></td>
				 <td colspan="3" align="left"><input name="auto_load_last_rx" type="checkbox" class="formField" id="auto_load_last_rx" value="yes" <?php if($accountData["auto_load_last_rx"]=="1") echo " checked"; ?> />
						
			  </tr>
			  
			  
			  	<tr >
				  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
				  <td bgcolor="#DDDDDD"  colspan="2" align="left" ><div align="right">Rodenstock</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="rodenstock_dsc" type="text" id="rodenstock_dsc" size="4" maxlength="4" value="<?php echo $accountData["rodenstock_dsc"]; ?>" class="formField" /></td>
				  

				  <td bgcolor="#DDDDDD"  align="left" ><div align="right">Rodenstock HD</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="rodenstock_hd_dsc" type="text" id="rodenstock_hd_dsc" size="4" maxlength="4" value="<?php echo $accountData["rodenstock_hd_dsc"]; ?>" class="formField" /></td>	
				   <td align="left" ><div align="right"><b>SELECTED PROMOTION</b><br><b>(Lensnet-Directlens ONLY)</b></div></td>
				 <td colspan="3" align="left">
                 Summer Si$$le!<input name="selected_promotion" type="checkbox" class="formField" id="selected_promotion" value="sizzling summer" <?php if($accountData["selected_promotion"]=="sizzling summer") echo " checked"; ?> /><br>
				 AR TOONIE<input name="selected_promotion" type="checkbox" class="formField" id="selected_promotion" value="ar toonie" <?php if($accountData["selected_promotion"]=="ar toonie") echo " checked"; ?> >(If selected, please type the coupon code)&nbsp;<b>Promo code:</b> <input name="promo_code" type="text" class="formField" id="promo_code" value="<?php echo $accountData["promo_code"]; ?>" /></td>
			  </tr>
			  
			  
			  <tr >
				  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
				  <td bgcolor="#FFFFFF"  colspan="2" align="left" ><div align="right">Glass 3</div></td>
				  <td bgcolor="#FFFFFF"  align="left" ><input name="glass_3_dsc" type="text" id="glass_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["glass_3_dsc"]; ?>" class="formField" /></td>
				  

				  <td bgcolor="#FFFFFF"  align="left" ><div align="right">Innovation FF</div></td>
				  <td bgcolor="#FFFFFF"  align="left" ><input name="innovation_ff_dsc" type="text" id="innovation_ff_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ff_dsc"]; ?>" class="formField" /></td>	
				  <td align="left" ><div align="right"><b>Free Edging</b><br></div></td>
				 <td colspan="3" align="left">
                 <input name="free_edging" type="checkbox" class="formField" id="free_edging" value="yes" <?php if($accountData["free_edging"]=="yes") echo " checked"; ?> /><br></td>
			  </tr>
			  
              
              
              
              <tr >
				  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
				  <td bgcolor="#DDDDDD"  colspan="2" align="left" ><div align="right">Innovation DS</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="innovation_ds_dsc" type="text" id="innovation_ds_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ds_dsc"]; ?>" class="formField" /></td>
				  

				  <td bgcolor="#DDDDDD"  align="left" ><div align="right">Innovation II DS</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="innovation_ii_ds_dsc" type="text" id="innovation_ii_ds_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ii_ds_dsc"]; ?>" class="formField" /></td>	
				   
                   
                  <?php if (($accountData["product_line"]=='safety') || ($accountData["product_line"]=='ifcclubca')) {?>
                 <td align="left" ><div align="right"><b>Safety Plan</b><br></div></td>
				 <td colspan="3" align="left">
                <?php $safety_plan = $accountData["safety_plan"] ; ?>
                 <select name = "safety_plan" id="safety_plan" class="formField">
                 <option value ="" <?php if($safety_plan=="")  echo " selected"; ?>>Select One</option>
                 <option value ="discounted price"  <?php if($safety_plan=="discounted price")  echo " selected"; ?>>Discounted Prices</option>
                 <option value ="interco price" 	<?php if($safety_plan=="interco price")     echo " selected"; ?>>Interco Prices</option>
                 <option value ="regular price" 	<?php if($safety_plan=="regular price")     echo " selected"; ?>>Regular Prices</option>
				</select>
                
                <b>Charge Dispensing Fee</b> <input name="charge_dispensing_fee" type="checkbox" class="formField" id="charge_dispensing_fee" value="yes" <?php if($accountData["charge_dispensing_fee"]=="yes") echo " checked"; ?> />
                
                </td>
                 <?php  }else{
				echo "<input type=\"hidden\" name=\"safety_plan\" value=\"\">";	 
				} ?>
                
			  </tr>
              
              
               
			  <tr >
				  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
				  <td bgcolor="#FFFFFF"  colspan="2" align="left" ><div align="right">Innovation FF HD</div></td>
				  <td bgcolor="#FFFFFF"  align="left" ><input name="innovation_ff_hd_dsc" type="text" id="innovation_ff_hd_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ff_hd_dsc"]; ?>" class="formField" /></td>
				  
  
				  <td bgcolor="#FFFFFF"   align="left" ><div align="right">Private 6/ GRM DR</div></td>
				  <td bgcolor="#FFFFFF"  align="left" ><input name="private_6_dsc" type="text" id="private_6_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_6_dsc"]; ?>" class="formField" /></td>
                  	  <td  align="left" >&nbsp;</td>
				 
			  </tr>
              
               <tr >
				  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">sVision</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="svision_dsc" type="text" id="svision_dsc" size="4" maxlength="4" value="<?php echo $accountData["svision_dsc"]; ?>" class="formField" /></td>
                  	  
				  
           
				  <td bgcolor="#DDDDDD"   align="left" ><div align="right">Svision 2</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="svision_2_dsc" type="text" id="svision_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["svision_2_dsc"]; ?>" class="formField" /></td>
                  	
                    
                    
                  <td align="left" ><div align="right"><b>Access Short Order Form</b><br></div></td>
				 <td colspan="3" align="left">
                 <input name="access_short_order_form" type="checkbox" class="formField" id="access_short_order_form" value="yes" <?php if($accountData["access_short_order_form"]=="yes") echo " checked"; ?> /><br></td>
				 
                  
  
				 
			  </tr>
              
              
              
              
              <tr>
              
              
               	  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
                  <td bgcolor="#FFFFFF" colspan="2"   align="left" ><div align="right">Optovision</div></td>
				  <td bgcolor="#FFFFFF"   align="left" ><input name="optovision_dsc" type="text" id="optovision_dsc" size="4" maxlength="4" value="<?php echo $accountData["optovision_dsc"]; ?>" class="formField" /></td>
                  	  
				  
				  
           
           
           
				   <td bgcolor="#FFFFFF"    align="left" ><div align="right">Private 7/ GRM SCT</div></td>
				   <td bgcolor="#FFFFFF"   align="left" ><input name="private_7_dsc" type="text" id="private_7_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_7_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
              </tr>
              
              
                  <tr>
              
              
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Nesp</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="nesp_dsc" type="text" id="nesp_dsc" size="4" maxlength="4" value="<?php echo $accountData["nesp_dsc"]; ?>" class="formField" /></td>
                  	  

           
				   <td bgcolor="#DDDDDD"    align="left" ><div align="right">Conant</div></td>
				   <td bgcolor="#DDDDDD"   align="left" ><input name="conant_dsc" type="text" id="conant_dsc" size="4" maxlength="4" value="<?php echo $accountData["conant_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
              </tr>
              
              
                 <tr>
              
              
               	  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
                  <td bgcolor="#FFFFFF" colspan="2"   align="left" ><div align="right">Private Grm 1 (Nesp)</div></td>
				  <td bgcolor="#FFFFFF"   align="left" ><input name="private_grm_1_dsc" type="text" id="private_grm_1_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_grm_1_dsc"]; ?>" class="formField" /></td>
                  	  
				  
				  
           
           
           
				   <td bgcolor="#FFFFFF"    align="left" ><div align="right">Private Grm 2 (infinity)</div></td>
				   <td bgcolor="#FFFFFF"   align="left" ><input name="private_grm_2_dsc" type="text" id="private_grm_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_grm_2_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
              </tr>
              
              
                <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Private Grm 3</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="private_grm_3_dsc" type="text" id="private_grm_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["private_grm_3_dsc"]; ?>" class="formField" /></td>
                  	  

           
				   <td bgcolor="#DDDDDD"    align="left" ><div align="right">Svision 3</div></td>
				   <td bgcolor="#DDDDDD"   align="left" ><input name="svision_3_dsc" type="text" id="svision_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["svision_3_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
              </tr>
              
              
                <tr>
               	  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
                  <td bgcolor="#FFFFFF" colspan="2"   align="left" ><div align="right">Innovative Plus(+)</div></td>
				  <td bgcolor="#FFFFFF"   align="left" ><input name="innovative_plus_dsc" type="text" id="innovative_plus_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovative_plus_dsc"]; ?>" class="formField" /></td>
                  	  

           
				    <td bgcolor="#FFFFFF"    align="left" ><div align="right">Selection Rx</div></td>
				   <td bgcolor="#FFFFFF"   align="left" ><input name="selection_rx_dsc" type="text" id="selection_rx_dsc" size="4" maxlength="4" value="<?php echo $accountData["selection_rx_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
              </tr>
              
                <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Younger Prog</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="younger_prog_dsc" type="text" id="younger_prog_dsc" size="4" maxlength="4" value="<?php echo $accountData["younger_prog_dsc"]; ?>" class="formField" /></td>
                  	
                     <td bgcolor="#DDDDDD"    align="left" ><div align="right">Ovation Rx</div></td>
				   <td bgcolor="#DDDDDD"   align="left" ><input name="ovation_dsc" type="text" id="ovation_dsc" size="4" maxlength="4" value="<?php echo $accountData["ovation_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>  

              </tr>     
              
              
              
              
              
              
              
              
              
              
                <tr>
               	  <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>
                  <td bgcolor="#FFFFFF" colspan="2"   align="left" ><div align="right">Innovation FF 159</div></td>
				  <td bgcolor="#FFFFFF"   align="left" ><input name="innovation_ff_159_dsc" type="text" id="innovation_ff_159_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ff_159_dsc"]; ?>" class="formField" /></td>
                  	
                     <td bgcolor="#FFFFFF"    align="left" ><div align="right">Innovation FF HD 159</div></td>
				   <td bgcolor="#FFFFFF"   align="left" ><input name="innovation_ff_hd_159_dsc" type="text" id="innovation_ff_hd_159_dsc" size="4" maxlength="4" value="<?php echo $accountData["innovation_ff_hd_159_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#FFFFFF"   align="left" >&nbsp;</td>  

              </tr>              
              
              
              
              
              
              <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Identity</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="identity_dsc" type="text" id="identity_dsc" size="4" maxlength="4" value="<?php echo $accountData["identity_dsc"]; ?>" class="formField" /></td>
                  	
                   <td bgcolor="#DDDDDD"   align="left" ><div align="right">Image</div></td>
				   <td bgcolor="#DDDDDD"   align="left" ><input name="image_dsc" type="text" id="image_dsc" size="4" maxlength="4" value="<?php echo $accountData["image_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>  

              </tr>  
              
              
              
              
              
                  
              <tr>
               	  <td   align="left" >&nbsp;</td>
                  <td  colspan="2"   align="left" ><div align="right">Axial Mini hko</div></td>
				  <td    align="left" ><input name="axial_mini_hko_dsc" type="text" id="axial_mini_hko_dsc" size="4" maxlength="4" value="<?php echo $accountData["axial_mini_hko_dsc"]; ?>" class="formField" /></td>
                  	
                   <td    align="left" ><div align="right">Axial Mini somo</div></td>
				   <td   align="left" ><input name="axial_mini_somo_dsc" type="text" id="axial_mini_somo_dsc" size="4" maxlength="4" value="<?php echo $accountData["axial_mini_somo_dsc"]; ?>" class="formField" /></td>
                   <td    align="left" >&nbsp;</td>  

              </tr>  
              
              
              
                       
              
              <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Az2Ph2</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="az2ph2_dsc" type="text" id="az2ph2_dsc" size="4" maxlength="4" value="<?php echo $accountData["az2ph2_dsc"]; ?>" class="formField" /></td>
                  	
                   <td bgcolor="#DDDDDD"   align="left" ><div align="right">Optimize (OPTZ Regular)</div></td>
				   <td bgcolor="#DDDDDD"   align="left" ><input  title="Everything but Acuform and IPL 1.5/1.6" name="optimize_dsc" type="text" id="optimize_dsc" size="4" maxlength="4" value="<?php echo $accountData["optimize_dsc"]; ?>" class="formField" /></td>
                   <td bgcolor="#DDDDDD"   align="left" ></td>  

              </tr>  
              
              
                
              <tr>
               	  <td  align="left" >&nbsp;</td>
                  <td  colspan="2"   align="left" ><div align="right">Optimize 2(OPTZ Special Promo)</div></td>
				  <td  align="left" ><input  title="ALL IPL and Acuform" name="optimize_2_dsc" type="text" id="optimize_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["optimize_2_dsc"]; ?>" class="formField" /></td>
                  	
                  
                   <td   align="left" ><div align="right">Optimize 3 (OPTZ Promo)</div></td>
                   <td colspan="2"  align="left" ><input title="IPL and Acuform 1.5 and 1.6" name="optimize_3_dsc" type="text" id="optimize_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["optimize_3_dsc"]; ?>" class="formField" /></td>  

              </tr>  
              
              
              
              
              
              <tr>
               	   <td  align="left" >&nbsp;</td>
                  <td  colspan="2"   align="left" ><div align="right">Optimize 4 (OPTZ Special)</div></td>
				  <td  align="left" ><input title="Everything except IPL and Acuform" name="optimize_4_dsc" type="text" id="optimize_4_dsc" size="4" maxlength="4" value="<?php echo $accountData["optimize_4_dsc"]; ?>" class="formField" /></td>
                  	
                  	
                  
                   <td   align="left" ><div align="right">Revolution</div></td>
                   <td colspan="2"  align="left" ><input name="revolution_dsc" type="text" id="revolution_dsc" size="4" maxlength="4" value="<?php echo $accountData["revolution_dsc"]; ?>" class="formField" /></td>  

              </tr>  
              
              
             
             
             
                 
              <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">FF BY IOT</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="ff_by_iot_dsc" type="text" id="ff_by_iot_dsc" size="4" maxlength="4" value="<?php echo $accountData["ff_by_iot_dsc"]; ?>" class="formField" /></td>
                  	
                   <td bgcolor="#DDDDDD"   align="left" ><div align="right">&nbsp;</div></td>
				   <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                   <td bgcolor="#DDDDDD"   align="left" ></td>  

              </tr>  
              
              
              
                <tr>
               	   <td  align="left" >&nbsp;</td>
                  <td  colspan="2"   align="left" ><div align="right">Horizon</div></td>
				  <td  align="left" ><input title="Everything except IPL and Acuform" name="horizon_dsc" type="text" id="horizon_dsc" size="4" maxlength="4" value="<?php echo $accountData["horizon_dsc"]; ?>" class="formField" /></td>
                  	
                  	
                  
                   <td   align="left" ><div align="right">Fit</div></td>
                   <td colspan="2"  align="left" ><input name="fit_dsc" type="text" id="fit_dsc" size="4" maxlength="4" value="<?php echo $accountData["fit_dsc"]; ?>" class="formField" /></td>  

              </tr>  
              
              
              
              
              
                  
              <tr>
               	  <td bgcolor="#DDDDDD"   align="left" >&nbsp;</td>
                  <td bgcolor="#DDDDDD" colspan="2"   align="left" ><div align="right">Goyette Swiss</div></td>
				  <td bgcolor="#DDDDDD"   align="left" ><input name="goyette_swiss_dsc" type="text" id="goyette_swiss_dsc" size="4" maxlength="4" value="<?php echo $accountData["goyette_swiss_dsc"]; ?>" class="formField" /></td>
                   
				   <td bgcolor="#DDDDDD"   align="left" ><div align="right">Goyette Crystal</div></td>
                  <td colspan="2" bgcolor="#DDDDDD"   align="left" ><input name="goyette_crystal_dsc" type="text" id="goyette_crystal_dsc" size="4" maxlength="4" value="<?php echo $accountData["goyette_crystal_dsc"]; ?>" class="formField" /></td>

              </tr>  
              
              
              
              <tr><td>&nbsp;</td></tr>
              
              
               <tr  >
			
				  <td colspan="3"  bgcolor="#DDDDDD"   align="left" ><div align="right">Bbg 1</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="bbg_1_dsc" type="text" id="bbg_1_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_1_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3" bgcolor="#DDDDDD"   align="left" ><div align="left">Bbg 2&nbsp;&nbsp;<input name="bbg_2_dsc" type="text" id="bbg_2_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_2_dsc"]; ?>" class="formField" /></div></td>
                  	 
				 
			  </tr>
			  
              
              
             
              
               <tr  >
			
				  <td colspan="3"   bgcolor="#FFFFFF"  align="left" ><div align="right">Bbg 3</div></td>
				  <td  bgcolor="#FFFFFF"  align="left" ><input name="bbg_3_dsc" type="text" id="bbg_3_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_3_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3"  bgcolor="#FFFFFF"  align="left" ><div align="left">Bbg 4&nbsp;&nbsp;<input name="bbg_4_dsc" type="text" id="bbg_4_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_4_dsc"]; ?>" class="formField" /></div></td>
                  	 
				 
			  </tr>
			  
			  
			  
                      
               <tr  >
				  <td colspan="3"  bgcolor="#DDDDDD"   align="left" ><div align="right">Bbg 5</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="bbg_5_dsc" type="text" id="bbg_5_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_5_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3" bgcolor="#DDDDDD"   align="left" ><div align="left">Bbg 6&nbsp;&nbsp;<input name="bbg_6_dsc" type="text" id="bbg_6_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_6_dsc"]; ?>" class="formField" /></div></td>
                  	 
			  </tr>      
			  
			  
			    <tr  >
				  <td colspan="3"   bgcolor="#FFFFFF"   align="left" ><div align="right">Bbg 7</div></td>
				  <td bgcolor="#FFFFFF"  align="left" ><input name="bbg_7_dsc" type="text" id="bbg_7_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_7_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3" bgcolor="#FFFFFF"    align="left" ><div align="left">Bbg 8&nbsp;&nbsp;<input name="bbg_8_dsc" type="text" id="bbg_8_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_8_dsc"]; ?>" class="formField" /></div></td>
                  	 
			  </tr>   
              
              
                  <tr  >
				  <td colspan="3"  bgcolor="#DDDDDD"   align="left" ><div align="right">Bbg 9</div></td>
				  <td bgcolor="#DDDDDD"  align="left" ><input name="bbg_9_dsc" type="text" id="bbg_9_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_9_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3" bgcolor="#DDDDDD"   align="left" ><div align="left">Bbg 10&nbsp;&nbsp;<input name="bbg_10_dsc" type="text" id="bbg_10_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_10_dsc"]; ?>" class="formField" /></div></td>
                  	 
			  </tr>   
              
              
                  <tr  >
				  <td colspan="3"  bgcolor="#FFFFFF"    align="left" ><div align="right">Bbg 11</div></td>
				  <td  bgcolor="#FFFFFF"  align="left" ><input name="bbg_11_dsc" type="text" id="bbg_11_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_11_dsc"]; ?>" class="formField" /></td>
                  	
				  
  
				  <td colspan="3"  bgcolor="#FFFFFF"    align="left" ><div align="left">Bbg 12&nbsp;&nbsp;<input name="bbg_12_dsc" type="text" id="bbg_12_dsc" size="4" maxlength="4" value="<?php echo $accountData["bbg_12_dsc"]; ?>" class="formField" /></div></td>
                  	 
			  </tr> 
              
              
              
 					<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="1" align="left" ><div align="right">Offer to customer:	</div></td>
					<td colspan="1" align="left" >
                    <textarea rows="4" style="font-size:10px;" name="offer"><?php echo $accountData["offer"] ?></textarea
					></td>
                    
                  
					<td align="left" >&nbsp;</td>
					<td colspan="1" align="left" ><div align="right">Shipping code:	</div></td>
					<td colspan="1" align="left" > <?php echo $accountData["shipping_code"] ;?></td>

                    
				
					<td colspan="2" align="left" ><div align="right">Coupon codes:	</div></td>
					<td colspan="3" align="left" >
                    <textarea rows="4" style="font-size:10px;" name="coupon_code"><?php echo $accountData["coupon_code"] ?></textarea
					></td>
				</tr>      
              
              
              
			  
				<tr bgcolor="#FFFFFF">
				  <td colspan="3" align="left" bgcolor="#dddddd" ><div align="right"> <?php echo $adm_salesrep_txt; ?></div></td>
				  <td colspan="3" align="left" bgcolor="#dddddd" ><select name="mysalesrep" id="mysalesrep">
				    <option value="" <?php if (!(strcmp("", $accountData["sales_rep"]))) {echo "selected=\"selected\"";} ?>>Select a Sales Rep</option>
				    <?php
do {  
?>
				    <option value="<?php echo $row_sales_reps['id']?>"<?php if (!(strcmp($row_sales_reps['id'], $accountData["sales_rep"]))) {echo "selected=\"selected\"";} ?>><?php echo $row_sales_reps['rep_name']?></option>
				    <?php
} while ($row_sales_reps = mysql_fetch_assoc($sales_reps));
  $rows = mysql_num_rows($sales_reps);
  if($rows > 0) {
      mysql_data_seek($sales_reps, 0);
	  $row_sales_reps = mysql_fetch_assoc($sales_reps);
  }
?>
				    </select></td>
				  <td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><div align="right"> <?php echo $adm_commission_txt; ?></div></td>
				  <td align="left" bgcolor="#DDDDDD"><input name="mycommission" type="text" class="formField" id="mycommission" value="<?php echo $accountData["sales_commission"]; ?>" size="20" /></td>
				  <td align="left" bgcolor="#dddddd"><div align="right">Account Rebate</div></td>
				  <td align="left" bgcolor="#dddddd"><input name="account_rebate" type="text" class="formField" id="account_rebate" value="<?php echo $accountData["account_rebate"]; ?>" size="3" />
				    %</td>
			  </tr>
				

				<tr bgcolor="#DDDDDD">
					<td colspan="10" align="left" bgcolor="#000000" ><div align="center">
							<font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b><?php echo $adm_titlemast_billadd; ?></b></font>
					</div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="5" align="left" >&nbsp;</td>
					<td align="left" ><div align="right">
							 <?php echo $adm_addr1_txt; ?> 
					</div></td>
					<td align="left">
						<input name="bill_address1" type="text" id="bill_address1" size="20" value="<?php echo $accountData["bill_address1"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_addr2_txt; ?>
					</div></td>
					<td align="left">
						<input name="bill_address2" type="text" id="bill_address2" size="20" value="<?php echo $accountData["bill_address2"]; ?>" class="formField" />					</td>
				</tr>
				<tr>
					<td colspan="7" align="left" ><div align="right">
							 <?php echo $adm_city_txt; ?> 
					</div></td>
					<td align="left">
						<input name="bill_city" type="text" id="bill_city" size="20" value="<?php echo $accountData["bill_city"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_state_txt; ?> 
					</div></td>
					<td align="left"><input name="bill_state" type="text" id="bill_state" size="20" value="<?php echo $accountData["bill_state"]; ?>" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="7" align="left" ><div align="right">
							 <?php echo $adm_zip_txt; ?> 
					</div></td>
					<td align="left">
						<input name="bill_zip" type="text" id="bill_zip" size="20" value="<?php echo $accountData["bill_zip"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_country_txt; ?> 
					</div></td>
					<td align="left">
						<select name = "bill_country" id="bill_country" class="formField">
					<?php $Bill_Country = $accountData["bill_country"] ; ?>
                   <option value="">Select One</option> 
                  <option value ="AU" <?php if($Bill_Country=="AU") echo " selected"; ?>>Australie</option>
                  <option value ="BE" <?php if($Bill_Country=="BE") echo " selected"; ?>>Benin</option>
                  <option value ="BF" <?php if($Bill_Country=="BF") echo " selected"; ?>>Burkina Faso</option>
                  <option value ="CA" <?php if($Bill_Country=="CA") echo " selected"; ?>>Canada</option>
                  <option value ="CAM"<?php if($Bill_Country=="CAM") echo " selected"; ?>>Cameroun</option>
                  <option value ="CR" <?php if($Bill_Country=="CR") echo " selected"; ?>>Caribbean</option>
                  <option value ="CB" <?php if($Bill_Country=="CB") echo " selected"; ?>>Congo-Brazzaville</option>
                  <option value ="CI" <?php if($Bill_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
                  <option value ="FR" <?php if($Bill_Country=="FR") echo " selected"; ?>>France</option>
                  <option value ="GA" <?php if($Bill_Country=="GA") echo " selected"; ?>>Gabon</option>
                  <option value ="IT" <?php if($Bill_Country=="IT") echo " selected"; ?>>Italy</option>
                  <option value ="MA" <?php if($Bill_Country=="MA") echo " selected"; ?>>Mali</option>
                  <option value ="SE" <?php if($Bill_Country=="SE") echo " selected"; ?>>Senegal</option>
                  <option value ="RDC"<?php if($Bill_Country=="RDC") echo " selected"; ?>>Republique democratique du Congo</option>
                  <option value ="TO" <?php if($Bill_Country=="TO") echo " selected"; ?>>Togo</option>
                  <option value ="US" <?php if($Bill_Country=="US") echo " selected"; ?>>United States</option>
						</select>					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="10" align="left" bgcolor="#000000" ><div align="center">
						<font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b><?php echo $adm_titlemast_ship; ?></b></font>
					</div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
						<?php echo $adm_labprefs_txt; ?>  												
					</div></td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_myworld_txt; ?>
					</div>					</td>
					<td colspan="3" align="left" nowrap="nowrap" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[innovative_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$innovative_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$innovative_lab[lab_name]</b>";
	?>	</td>
					<td align="left" ><div align="right">
						 <?php echo $adm_addr1_txt; ?> 
					</div></td>
					<td align="left">
						<input name="ship_address1" type="text" id="ship_address1" size="20" value="<?php echo $accountData["ship_address1"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												<?php echo $adm_addr2_txt; ?>
					</div></td>
					<td align="left">
						<input name="ship_address2" type="text" id="ship_address2" size="20" value="<?php echo $accountData["ship_address2"]; ?>" class="formField">					</td>
				</tr>
				<tr>
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_infocus_txt; ?>  
															</div></td>
					<td colspan="3" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[infocus_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$infocus_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$infocus_lab[lab_name]</b>";
	?>					</td>
					<td align="left" ><div align="right">
						 <?php echo $adm_city_txt; ?> 
					</div></td>
					<td align="left">
						<input name="ship_city" type="text" id="ship_city" size="20" value="<?php echo $accountData["ship_city"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												<?php echo $adm_state_txt; ?>					
					</div></td>
					<td align="left"><input name="ship_state" type="text" id="ship_state" size="20" value="<?php echo $accountData["ship_state"]; ?>" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_precisionvp_txt; ?>
															</div></td>
					<td colspan="3" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[precision_vp_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$precision_vp_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$precision_vp_lab[lab_name]</b>";
	?>					</td>
					<td align="left" ><div align="right">
						 <?php echo $adm_zip_txt; ?> 
					</div></td>
					<td align="left">
						<input name="ship_zip" type="text" id="ship_zip" size="20" value="<?php echo $accountData["ship_zip"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												<?php echo $adm_country_txt; ?>						
					</div></td>
					<td align="left">
						<select name = "ship_country" id="ship_country" class="formField">
						<?php $Ship_Country = $accountData["ship_country"] ; ?>
                      <option value="">Select One</option>      
                  <option value ="AU" <?php if($Ship_Country=="AU") echo " selected"; ?>>Australie</option>        
                  <option value ="BE" <?php if($Ship_Country=="BE") echo " selected"; ?>>Benin</option>
                  <option value ="BF" <?php if($Ship_Country=="BF") echo " selected"; ?>>Burkina Faso</option>
                  <option value ="CA" <?php if($Ship_Country=="CA") echo " selected"; ?>>Canada</option>
                  <option value ="CAM"<?php if($Ship_Country=="CAM") echo " selected"; ?>>Cameroun</option>
                  <option value ="CR" <?php if($Ship_Country=="CR") echo " selected"; ?>>Caribbean</option>
                  <option value ="CB" <?php if($Ship_Country=="CB") echo " selected"; ?>>Congo-Brazzaville</option>
                  <option value ="CI" <?php if($Ship_Country=="CI") echo " selected"; ?>>Cote d'Ivoire</option>
                  <option value ="FR" <?php if($Ship_Country=="FR") echo " selected"; ?>>France</option>
                  <option value ="GA" <?php if($Ship_Country=="GA") echo " selected"; ?>>Gabon</option>
                  <option value ="IT" <?php if($Ship_Country=="IT") echo " selected"; ?>>Italy</option>
                  <option value ="MA" <?php if($Ship_Country=="MA") echo " selected"; ?>>Mali</option>
                  <option value ="SE" <?php if($Ship_Country=="SE") echo " selected"; ?>>Senegal</option>
                  <option value ="RDC"<?php if($Ship_Country=="RDC") echo " selected"; ?>>Republique democratique du Congo</option>
                  <option value ="TO" <?php if($Ship_Country=="TO") echo " selected"; ?>>Togo</option>
                  <option value ="US" <?php if($Ship_Country=="US") echo " selected"; ?>>United States</option>
							</select>					</td>
				</tr>
				<tr>
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_vispropoly_txt; ?> 
															</div></td>
					<td colspan="3" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[visionpropoly_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$visionpropoly_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$visionpropoly_lab[lab_name]</b>";
	?>					</td>
					<td align="left" ><div align="right">
						 <?php echo $adm_phone_txt; ?> 
					</div></td>
					<td align="left">
						<input name="phone" type="text" id="phone" size="20" value="<?php echo $accountData["phone"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
					<?php echo $adm_otherphone_txt; ?>						
					</div></td>
					<td align="left">
						<input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $accountData["other_phone"]; ?>" class="formField">					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" >&nbsp;</td>
				  <td colspan="2" align="left" ><div align="right"> <?php echo $adm_gener_txt; ?> </div></td>
				  <td colspan="3" align="left" ><?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[generation_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$generation_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$generation_lab[lab_name]</b>";
	?></td>
				  <td align="left" >&nbsp;</td>
				  <td align="left">&nbsp;</td>
				  <td align="left" nowrap="nowrap">&nbsp;</td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#FFFFFF" >&nbsp;</td>
				  <td colspan="2" align="left" bgcolor="#FFFFFF" ><div align="right"> <?php echo $adm_truehd_txt; ?> </div></td>
				  <td colspan="3" align="left" bgcolor="#FFFFFF" ><?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[truehd_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$truehd_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$truehd_lab[lab_name]</b>";
	?></td>
				  <td align="left" bgcolor="#FFFFFF" >&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
				<tr bgcolor="#DDDDDD">
				  <td align="left" bgcolor="#FFFFFF" >&nbsp;</td>
				  <td colspan="2" align="left" bgcolor="#FFFFFF" ><div align="right"><?php echo $adm_easyfithd_txt; ?> </div></td>
				  <td colspan="3" align="left" bgcolor="#FFFFFF" ><?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[easy_fit_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$easy_fit_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$truehd_lab[lab_name]</b>";
	?></td>
				  <td align="left" bgcolor="#FFFFFF" >&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" nowrap="nowrap" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_main_txt; ?>
					</div></td>
					<td colspan="3" align="left" >	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[main_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$main_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$main_lab[lab_name]</b>";
	?>					</td>
					<td align="left" >&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						<?php echo $adm_other_txt; ?>
					</div></td>
					<td colspan="3" align="left" ><?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[other_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$other_lab=mysql_fetch_array($result);
		echo "n/a";
		//echo "<b>$other_lab[lab_name]</b>";
	?></td>
					<td align="left" >&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
                
                
    
                
                
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="10" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$accountData[primary_key]"; ?>">
            			<input type="hidden" name="user_id" value="<?php echo "$accountData[user_id]"; ?>" />
            			<input type="hidden" name="notifyApproved" value="<?php echo "$accountData[approved]"; ?>">
                        <input type="submit" name="editAcct" id="editAcct" value="<?php echo $btn_editacct_txt; ?>" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="<?php echo $btn_cancel_txt; ?>" onClick="window.open('adminHome.php', '_top')" class="formField">
<br>
<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b><?php echo $adm_editmsg_txt; ?></b></font></td>
            		</tr>
			</table>
</form>
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_stockdisc_txt; ?></font></b></td>
            		</tr>
<?php
$stockQuery="SELECT * from prices order by product_name";
$stockResult=mysql_query($stockQuery)
	or die ($adm_nostock_txt);
$tableCounter=0;
echo "<form action=\"getAccount.php\" method=\"post\" name=\"discountForm\"><tr bgcolor=\"#DDDDDD\">";
while ($stockData=mysql_fetch_array($stockResult)){
	$field_name="key_" . $stockData[primary_key];
	$dscQuery="SELECT * from stock_discounts WHERE user_id = '$accountData[user_id]' AND product_name = '$stockData[product_name]'";
	$dscResult=mysql_query($dscQuery)
		or die ($adm_nostock_txt);
	$dscCount=mysql_num_rows($dscResult);
	if($dscCount!=0){
		$dscData=mysql_fetch_array($dscResult);
		$discount=$dscData[discount];
	}else{
		$discount=0;
	}
	if($tableCounter==3){
		if ($counter%2==0)
			$bgcolor="#FFFFFF";
		else
			$bgcolor="#DDDDDD";
		$counter++;
		echo "</tr><tr bgcolor=$bgcolor valign=\"top\">";
		$tableCounter=0;
	}	
	echo "<td align=\"right\">$stockData[product_name]:</td><td><input name=\"$field_name\" type=\"text\" size=\"4\" maxlength=\"4\" value=\"$discount\" class=\"formField\" /></td>";
	$tableCounter++;
}
if($tableCounter!=3)
	echo"</tr>";
echo "<tr><td align=\"center\" colspan=\"6\"><input type=\"hidden\" name=\"user_id\" value=\"$accountData[user_id]\" /><input type=\"hidden\" name=\"pkey\" value=\"$accountData[primary_key]\" /><input name=\"updateDisc\" type=\"submit\" value=\"".$btn_updiscounts_txt."\" class=\"formField\" /></td></tr></form>";
?>
</table>
            <?php
mysql_free_result($sales_reps);
?>
