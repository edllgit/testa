<link href="admin.css" rel="stylesheet" type="text/css" />
<form name="form3" method="post" action="getAccount.php" onSubmit="return formCheck(this);">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="11"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td width="7%" align="left" ><div align="right">
												Title						
					</div></td>
					<td colspan="6" align="left" nowrap="nowrap">
						<select name="title" class="formField" id="title">
							<option value="">Select</option>
							<option value="Dr." <?php if($accountData["title"]=="Dr.") echo " selected"; ?>>Dr.</option>
							<option value="Mr." <?php if($accountData["title"]=="Mr.") echo " selected"; ?>>Mr.</option>
							<option value="Ms." <?php if($accountData["title"]=="Ms.") echo " selected"; ?>>Ms.</option>
							<option value="Mrs." <?php if($accountData["title"]=="Mrs.") echo " selected"; ?>>Mrs.</option>
						</select>					</td>
					<td width="17%" align="left" nowrap><div align="right">
												First Name						
					</div></td>
					<td width="19%" align="left">
						<input name="first_name" type="text" class="formField" id="first_name" value="<?php echo $accountData["first_name"]; ?>" size="20">					</td>
					<td width="9%" align="left" nowrap><div align="right">
												Last Name						
					</div></td>
					<td width="20%" align="left">
						<input name="last_name" type="text" class="formField" id="last_name" value="<?php echo $accountData["last_name"]; ?>" size="20">					</td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
						Account No
					</div></td>
					<td colspan="6" align="left" ><?php echo $accountData["account_num"]; ?></td>
					<td align="left" ><div align="right">
							 Company 
					</div></td>
					<td align="left">
						<input name="company" type="text" id="company" size="20" value="<?php echo $accountData["company"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Buying Group 
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
							Order
							by  
					</div></td>
					<td colspan="6" align="left" ><input name="purchase_unit" type="radio" value="single" <?php if($accountData["purchase_unit"]=="single") echo " checked"; ?> class="formField" />single
								<input name="purchase_unit" type="radio" value="pair" <?php if($accountData["purchase_unit"]=="pair") echo " checked"; ?> class="formField" />pair</td>
					<td align="left" ><div align="right">
						Business
						Type   
										</div></td>
					<td align="left"><select name="business_type" class="formField">
						<option value="Optometrist Office"<?php if($accountData["business_type"]=="Optometrist Office") echo " selected"; ?>>Optometrist Office</option>
						<option value="Optician Office"<?php if($accountData["business_type"]=="Optician Office") echo " selected"; ?>>Optician Office</option>
						<option value="Lab"<?php if($accountData["business_type"]=="Lab") echo " selected"; ?>>Lab</option>
					</select>					</td>
					<td align="left" nowrap><div align="right"> Currency </div></td>
					<td align="left"><input name="currency" type="radio" value="US" <?php if($accountData["currency"]=="US") echo " checked"; ?> class="formField" />US
								<input name="currency" type="radio" value="CA" <?php if($accountData["currency"]=="CA") echo " checked"; ?> class="formField" />CA</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right">
							Discounts 
					</div></td>
					<td width="4%" align="left" ><div align="right">
						RX
					</div></td>
					<td width="8%" align="left" ><div align="right">
						Innovative
					</div></td>
					<td width="4%" align="left" >
						<input name="innovative_dsc" type="text" id="innovative_dsc" size="2" maxlength="2" value="<?php echo $accountData["innovative_dsc"]; ?>" class="formField" /></td>
					<td width="3%" align="left" ><div align="right">
						Stock
					</div></td>
					<td width="5%" align="left" ><div align="right">
						Tokai
					</div></td>
					<td width="4%" align="left" ><input name="tokai_dsc" type="text" id="tokai_dsc" size="2" maxlength="2" value="<?php echo $accountData["tokai_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						 Fax 
					</div></td>
					<td align="left">
						<input name="fax" type="text" id="fax" size="20" value="<?php echo $accountData["fax"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 VAT Number 
					</div></td>
					<td align="left">
						<input name="VAT_no" type="text" id="VAT_no" size="20" value="<?php echo $accountData["VAT_no"]; ?>" class="formField" />					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Infocus 
					</div></td>
					<td align="left" >
						<input name="infocus_dsc" type="text" id="infocus_dsc" size="2" maxlength="2" value="<?php echo $accountData["infocus_dsc"]; ?>" class="formField" />					</td>
					<td align="left" >&nbsp;</td>
					<td align="left" nowrap="nowrap" ><div align="right">
						Co-Optix
					</div></td>
					<td align="left" ><input name="co_optix_dsc" type="text" id="co_optix_dsc" size="2" maxlength="2" value="<?php echo $accountData["co_optix_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						 Email 
					</div></td>
					<td colspan="3" align="left">
						<input name="email" type="text" id="email" size="50" value="<?php echo $accountData["email"]; ?>" class="formField" />
						
							<div align="right">
						</div></td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Precision
					</div></td>
					<td colspan="4" align="left" >
						<input name="precision_dsc" type="text" id="precision_dsc" size="2" maxlength="2" value="<?php echo $accountData["precision_dsc"]; ?>" class="formField" />					</td>
					<td align="left" ><div align="right">
						Login 
					</div></td>
					<td align="left"><b><?php echo $accountData["user_id"]; ?></b> </td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Password 
					</div></td>
					<td align="left">
						<input name="password" type="text" id="password" size="20" value="<?php echo $accountData["password"]; ?>" class="formField" />					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Vision Pro
					</div></td>
					<td colspan="4" align="left" ><input name="visionpro_dsc" type="text" id="visionpro_dsc" size="2" maxlength="2" value="<?php echo $accountData["visionpro_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						Account
						Approval  
					</div></td>
					<td colspan="3" align="left"><select name="approved" class="formField">
							<option value = "pending" <?php if($accountData["approved"]=="pending") echo " selected"; ?>>pending</option>
							<option value = "approved" <?php if($accountData["approved"]=="approved") echo " selected"; ?>>approved</option>
							<option value = "declined" <?php if($accountData["approved"]=="declined") echo " selected"; ?>>declined</option>
						</select>	</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Vision Pro Poly
					</div></td>
					<td colspan="4" align="left" ><input name="visionpropoly_dsc" type="text" id="visionpropoly_dsc" size="2" maxlength="2" value="<?php echo $accountData["visionpropoly_dsc"]; ?>" class="formField" /></td>
					<td align="left" ><div align="right">
						Credit Hold 
					</div></td>
					<td colspan="3" align="left"><input name="credit_hold" type="checkbox" class="formField" id="credit_hold" value="1" <?php if($accountData["credit_hold"]=="1") echo " checked"; ?> />
						If checked, customer must pay for new orders with a credit
					card </td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="11" align="left" bgcolor="#000000" ><div align="center">
							<font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b>Billing
							Address</b></font>
					</div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="6" align="left" >&nbsp;</td>
					<td align="left" ><div align="right">
							 Address 1 
					</div></td>
					<td align="left">
						<input name="bill_address1" type="text" id="bill_address1" size="20" value="<?php echo $accountData["bill_address1"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Address 2 
					</div></td>
					<td align="left">
						<input name="bill_address2" type="text" id="bill_address2" size="20" value="<?php echo $accountData["bill_address2"]; ?>" class="formField" />					</td>
				</tr>
				<tr>
					<td colspan="8" align="left" ><div align="right">
							 City 
					</div></td>
					<td align="left">
						<input name="bill_city" type="text" id="bill_city" size="20" value="<?php echo $accountData["bill_city"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 State/Province 
					</div></td>
					<td align="left"><select id="bill_state" name="bill_state" class="formField">
						<optgroup label="Canadian Provinces">
							<option value="AB" <?php if($accountData["bill_state"]=="AB") echo " selected"; ?>>Alberta</option>
							<option value="BC" <?php if($accountData["bill_state"]=="BC") echo " selected"; ?>>British
							Columbia</option>
							<option value="MB" <?php if($accountData["bill_state"]=="MB") echo " selected"; ?>>Manitoba</option>
							<option value="NB" <?php if($accountData["bill_state"]=="NB") echo " selected"; ?>>New
							Brunswick</option>
							<option value="NF" <?php if($accountData["bill_state"]=="NF") echo " selected"; ?>>Newfoundland</option>
							<option value="NT" <?php if($accountData["bill_state"]=="NT") echo " selected"; ?>>Northwest
							Territories</option>
							<option value="NS" <?php if($accountData["bill_state"]=="NS") echo " selected"; ?>>Nova
							Scotia</option>
							<option value="NU" <?php if($accountData["bill_state"]=="NU") echo " selected"; ?>>Nunavut</option>
							<option value="ON" <?php if($accountData["bill_state"]=="ON") echo " selected"; ?>>Ontario</option>
							<option value="PE" <?php if($accountData["bill_state"]=="PE") echo " selected"; ?>>Prince
							Edward Island</option>
							<option value="QC" <?php if($accountData["bill_state"]=="QC") echo " selected"; ?>>Quebec</option>
							<option value="SK" <?php if($accountData["bill_state"]=="SK") echo " selected"; ?>>Saskatchewan</option>
							<option value="YT" <?php if($accountData["bill_state"]=="YT") echo " selected"; ?>>Yukon
							Territory</option>
							</optgroup>
							<optgroup label="U.S. States">
							<option value="AL" <?php if($accountData["bill_state"]=="AL") echo " selected"; ?>>Alabama</option>
							<option value="AK" <?php if($accountData["bill_state"]=="AK") echo " selected"; ?>>Alaska</option>
							<option value="AZ" <?php if($accountData["bill_state"]=="AZ") echo " selected"; ?>>Arizona</option>
							<option value="AR" <?php if($accountData["bill_state"]=="AR") echo " selected"; ?>>Arkansas</option>
							<option value="CA" <?php if($accountData["bill_state"]=="CA") echo " selected"; ?>>California</option>
							<option value="CO" <?php if($accountData["bill_state"]=="CO") echo " selected"; ?>>Colorado</option>
							<option value="CT" <?php if($accountData["bill_state"]=="CT") echo " selected"; ?>>Connecticut</option>
							<option value="DE" <?php if($accountData["bill_state"]=="DE") echo " selected"; ?>>Delaware</option>
							<option value="DC" <?php if($accountData["bill_state"]=="DC") echo " selected"; ?>>District
							of Columbia</option>
							<option value="FL" <?php if($accountData["bill_state"]=="FL") echo " selected"; ?>>Florida</option>
							<option value="GA" <?php if($accountData["bill_state"]=="GA") echo " selected"; ?>>Georgia</option>
							<option value="HI" <?php if($accountData["bill_state"]=="HI") echo " selected"; ?>>Hawaii</option>
							<option value="ID" <?php if($accountData["bill_state"]=="ID") echo " selected"; ?>>Idaho</option>
							<option value="IL" <?php if($accountData["bill_state"]=="IL") echo " selected"; ?>>Illinois</option>
							<option value="IN" <?php if($accountData["bill_state"]=="IN") echo " selected"; ?>>Indiana</option>
							<option value="IA" <?php if($accountData["bill_state"]=="IA") echo " selected"; ?>>Iowa</option>
							<option value="KS" <?php if($accountData["bill_state"]=="KS") echo " selected"; ?>>Kansas</option>
							<option value="KY" <?php if($accountData["bill_state"]=="KY") echo " selected"; ?>>Kentucky</option>
							<option value="LA" <?php if($accountData["bill_state"]=="LA") echo " selected"; ?>>Louisiana</option>
							<option value="ME" <?php if($accountData["bill_state"]=="ME") echo " selected"; ?>>Maine</option>
							<option value="MD" <?php if($accountData["bill_state"]=="MD") echo " selected"; ?>>Maryland</option>
							<option value="MA" <?php if($accountData["bill_state"]=="MA") echo " selected"; ?>>Massachusetts</option>
							<option value="MI" <?php if($accountData["bill_state"]=="MI") echo " selected"; ?>>Michigan</option>
							<option value="MN" <?php if($accountData["bill_state"]=="MN") echo " selected"; ?>>Minnesota</option>
							<option value="MS" <?php if($accountData["bill_state"]=="MS") echo " selected"; ?>>Mississippi</option>
							<option value="MO" <?php if($accountData["bill_state"]=="MO") echo " selected"; ?>>Missouri</option>
							<option value="MT" <?php if($accountData["bill_state"]=="MT") echo " selected"; ?>>Montana</option>
							<option value="NE" <?php if($accountData["bill_state"]=="NE") echo " selected"; ?>>Nebraska</option>
							<option value="NV" <?php if($accountData["bill_state"]=="NV") echo " selected"; ?>>Nevada</option>
							<option value="NH" <?php if($accountData["bill_state"]=="NH") echo " selected"; ?>>New
							Hampshire</option>
							<option value="NJ" <?php if($accountData["bill_state"]=="NJ") echo " selected"; ?>>New
							Jersey</option>
							<option value="NM" <?php if($accountData["bill_state"]=="NM") echo " selected"; ?>>New
							Mexico</option>
							<option value="NY" <?php if($accountData["bill_state"]=="NY") echo " selected"; ?>>New
							York</option>
							<option value="NC" <?php if($accountData["bill_state"]=="NC") echo " selected"; ?>>North
							Carolina</option>
							<option value="ND" <?php if($accountData["bill_state"]=="ND") echo " selected"; ?>>North
							Dakota</option>
							<option value="OH" <?php if($accountData["bill_state"]=="OH") echo " selected"; ?>>Ohio</option>
							<option value="OK" <?php if($accountData["bill_state"]=="OK") echo " selected"; ?>>Oklahoma</option>
							<option value="OR" <?php if($accountData["bill_state"]=="OR") echo " selected"; ?>>Oregon</option>
							<option value="PA" <?php if($accountData["bill_state"]=="PA") echo " selected"; ?>>Pennsylvania</option>
							<option value="PR" <?php if($accountData["bill_state"]=="PR") echo " selected"; ?>>Puerto
							Rico</option>
							<option value="RI" <?php if($accountData["bill_state"]=="RI") echo " selected"; ?>>Rhode
							Island</option>
							<option value="SC" <?php if($accountData["bill_state"]=="SC") echo " selected"; ?>>South
							Carolina</option>
							<option value="SD" <?php if($accountData["bill_state"]=="SD") echo " selected"; ?>>South
							Dakota</option>
							<option value="TN" <?php if($accountData["bill_state"]=="TN") echo " selected"; ?>>Tennessee</option>
							<option value="TX" <?php if($accountData["bill_state"]=="TX") echo " selected"; ?>>Texas</option>
							<option value="UT" <?php if($accountData["bill_state"]=="UT") echo " selected"; ?>>Utah</option>
							<option value="VT" <?php if($accountData["bill_state"]=="VT") echo " selected"; ?>>Vermont</option>
							<option value="VA" <?php if($accountData["bill_state"]=="VA") echo " selected"; ?>>Virginia</option>
							<option value="WA" <?php if($accountData["bill_state"]=="WA") echo " selected"; ?>>Washington</option>
							<option value="WV" <?php if($accountData["bill_state"]=="WV") echo " selected"; ?>>West
							Virginia</option>
							<option value="WI" <?php if($accountData["bill_state"]=="WI") echo " selected"; ?>>Wisconsin</option>
							<option value="WY" <?php if($accountData["bill_state"]=="WY") echo " selected"; ?>>Wyoming</option>
							</optgroup>
					</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="8" align="left" ><div align="right">
							 Zip/Postal Code 
					</div></td>
					<td align="left">
						<input name="bill_zip" type="text" id="bill_zip" size="20" value="<?php echo $accountData["bill_zip"]; ?>" class="formField" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Country 
					</div></td>
					<td align="left">
						<select name = "bill_country" id="bill_country" class="formField">
							<option value="">Select One</option>
							<option value = "CA" <?php if($accountData["bill_country"]=="CA") echo " selected"; ?>>Canada</option>
							<option value = "US" <?php if($accountData["bill_country"]=="US") echo " selected"; ?>>United
							States</option>
						</select>					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="11" align="left" bgcolor="#000000" ><div align="center">
						<font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b>Shipping
						Address</b></font>
					</div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
						Lab Prefs  												
					</div></td>
					<td colspan="2" align="left" ><div align="right">
						Innovative
					</div>					</td>
					<td colspan="4" align="left" nowrap="nowrap" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[innovative_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$innovative_lab=mysql_fetch_array($result);
		echo "<b>$innovative_lab[lab_name]</b>";
	?>
	</td>
					<td align="left" ><div align="right">
						 Address 1 
					</div></td>
					<td align="left">
						<input name="ship_address1" type="text" id="ship_address1" size="20" value="<?php echo $accountData["ship_address1"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												Address
						2						
					</div></td>
					<td align="left">
						<input name="ship_address2" type="text" id="ship_address2" size="20" value="<?php echo $accountData["ship_address2"]; ?>" class="formField">					</td>
				</tr>
				<tr>
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Infocus  
															</div></td>
					<td colspan="4" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[infocus_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$infocus_lab=mysql_fetch_array($result);
		echo "<b>$infocus_lab[lab_name]</b>";
	?>
					</td>
					<td align="left" ><div align="right">
						 City 
					</div></td>
					<td align="left">
						<input name="ship_city" type="text" id="ship_city" size="20" value="<?php echo $accountData["ship_city"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												State/Province						
					</div></td>
					<td align="left"><select id="ship_state" name="ship_state" class="formField"><optgroup label="Canadian Provinces">
							<option value="AB" <?php if($accountData["ship_state"]=="AB") echo " selected"; ?>>Alberta</option>
							<option value="BC" <?php if($accountData["ship_state"]=="BC") echo " selected"; ?>>British
							Columbia</option>
							<option value="MB" <?php if($accountData["ship_state"]=="MB") echo " selected"; ?>>Manitoba</option>
							<option value="NB" <?php if($accountData["ship_state"]=="NB") echo " selected"; ?>>New
							Brunswick</option>
							<option value="NF" <?php if($accountData["ship_state"]=="NF") echo " selected"; ?>>Newfoundland</option>
							<option value="NT" <?php if($accountData["ship_state"]=="NT") echo " selected"; ?>>Northwest
							Territories</option>
							<option value="NS" <?php if($accountData["ship_state"]=="NS") echo " selected"; ?>>Nova
							Scotia</option>
							<option value="NU" <?php if($accountData["ship_state"]=="NU") echo " selected"; ?>>Nunavut</option>
							<option value="ON" <?php if($accountData["ship_state"]=="ON") echo " selected"; ?>>Ontario</option>
							<option value="PE" <?php if($accountData["ship_state"]=="PE") echo " selected"; ?>>Prince
							Edward Island</option>
							<option value="QC" <?php if($accountData["ship_state"]=="QC") echo " selected"; ?>>Quebec</option>
							<option value="SK" <?php if($accountData["ship_state"]=="SK") echo " selected"; ?>>Saskatchewan</option>
							<option value="YT" <?php if($accountData["ship_state"]=="YT") echo " selected"; ?>>Yukon
							Territory</option>
							</optgroup>
							<optgroup label="U.S. States">
							<option value="AL" <?php if($accountData["ship_state"]=="AL") echo " selected"; ?>>Alabama</option>
							<option value="AK" <?php if($accountData["ship_state"]=="AK") echo " selected"; ?>>Alaska</option>
							<option value="AZ" <?php if($accountData["ship_state"]=="AZ") echo " selected"; ?>>Arizona</option>
							<option value="AR" <?php if($accountData["ship_state"]=="AR") echo " selected"; ?>>Arkansas</option>
							<option value="CA" <?php if($accountData["ship_state"]=="CA") echo " selected"; ?>>California</option>
							<option value="CO" <?php if($accountData["ship_state"]=="CO") echo " selected"; ?>>Colorado</option>
							<option value="CT" <?php if($accountData["ship_state"]=="CT") echo " selected"; ?>>Connecticut</option>
							<option value="DE" <?php if($accountData["ship_state"]=="DE") echo " selected"; ?>>Delaware</option>
							<option value="DC" <?php if($accountData["ship_state"]=="DC") echo " selected"; ?>>District
							of Columbia</option>
							<option value="FL" <?php if($accountData["ship_state"]=="FL") echo " selected"; ?>>Florida</option>
							<option value="GA" <?php if($accountData["ship_state"]=="GA") echo " selected"; ?>>Georgia</option>
							<option value="HI" <?php if($accountData["ship_state"]=="HI") echo " selected"; ?>>Hawaii</option>
							<option value="ID" <?php if($accountData["ship_state"]=="ID") echo " selected"; ?>>Idaho</option>
							<option value="IL" <?php if($accountData["ship_state"]=="IL") echo " selected"; ?>>Illinois</option>
							<option value="IN" <?php if($accountData["ship_state"]=="IN") echo " selected"; ?>>Indiana</option>
							<option value="IA" <?php if($accountData["ship_state"]=="IA") echo " selected"; ?>>Iowa</option>
							<option value="KS" <?php if($accountData["ship_state"]=="KS") echo " selected"; ?>>Kansas</option>
							<option value="KY" <?php if($accountData["ship_state"]=="KY") echo " selected"; ?>>Kentucky</option>
							<option value="LA" <?php if($accountData["ship_state"]=="LA") echo " selected"; ?>>Louisiana</option>
							<option value="ME" <?php if($accountData["ship_state"]=="ME") echo " selected"; ?>>Maine</option>
							<option value="MD" <?php if($accountData["ship_state"]=="MD") echo " selected"; ?>>Maryland</option>
							<option value="MA" <?php if($accountData["ship_state"]=="MA") echo " selected"; ?>>Massachusetts</option>
							<option value="MI" <?php if($accountData["ship_state"]=="MI") echo " selected"; ?>>Michigan</option>
							<option value="MN" <?php if($accountData["ship_state"]=="MN") echo " selected"; ?>>Minnesota</option>
							<option value="MS" <?php if($accountData["ship_state"]=="MS") echo " selected"; ?>>Mississippi</option>
							<option value="MO" <?php if($accountData["ship_state"]=="MO") echo " selected"; ?>>Missouri</option>
							<option value="MT" <?php if($accountData["ship_state"]=="MT") echo " selected"; ?>>Montana</option>
							<option value="NE" <?php if($accountData["ship_state"]=="NE") echo " selected"; ?>>Nebraska</option>
							<option value="NV" <?php if($accountData["ship_state"]=="NV") echo " selected"; ?>>Nevada</option>
							<option value="NH" <?php if($accountData["ship_state"]=="NH") echo " selected"; ?>>New
							Hampshire</option>
							<option value="NJ" <?php if($accountData["ship_state"]=="NJ") echo " selected"; ?>>New
							Jersey</option>
							<option value="NM" <?php if($accountData["ship_state"]=="NM") echo " selected"; ?>>New
							Mexico</option>
							<option value="NY" <?php if($accountData["ship_state"]=="NY") echo " selected"; ?>>New
							York</option>
							<option value="NC" <?php if($accountData["ship_state"]=="NC") echo " selected"; ?>>North
							Carolina</option>
							<option value="ND" <?php if($accountData["ship_state"]=="ND") echo " selected"; ?>>North
							Dakota</option>
							<option value="OH" <?php if($accountData["ship_state"]=="OH") echo " selected"; ?>>Ohio</option>
							<option value="OK" <?php if($accountData["ship_state"]=="OK") echo " selected"; ?>>Oklahoma</option>
							<option value="OR" <?php if($accountData["ship_state"]=="OR") echo " selected"; ?>>Oregon</option>
							<option value="PA" <?php if($accountData["ship_state"]=="PA") echo " selected"; ?>>Pennsylvania</option>
							<option value="PR" <?php if($accountData["ship_state"]=="PR") echo " selected"; ?>>Puerto
							Rico</option>
							<option value="RI" <?php if($accountData["ship_state"]=="RI") echo " selected"; ?>>Rhode
							Island</option>
							<option value="SC" <?php if($accountData["ship_state"]=="SC") echo " selected"; ?>>South
							Carolina</option>
							<option value="SD" <?php if($accountData["ship_state"]=="SD") echo " selected"; ?>>South
							Dakota</option>
							<option value="TN" <?php if($accountData["ship_state"]=="TN") echo " selected"; ?>>Tennessee</option>
							<option value="TX" <?php if($accountData["ship_state"]=="TX") echo " selected"; ?>>Texas</option>
							<option value="UT" <?php if($accountData["ship_state"]=="UT") echo " selected"; ?>>Utah</option>
							<option value="VT" <?php if($accountData["ship_state"]=="VT") echo " selected"; ?>>Vermont</option>
							<option value="VA" <?php if($accountData["ship_state"]=="VA") echo " selected"; ?>>Virginia</option>
							<option value="WA" <?php if($accountData["ship_state"]=="WA") echo " selected"; ?>>Washington</option>
							<option value="WV" <?php if($accountData["ship_state"]=="WV") echo " selected"; ?>>West
							Virginia</option>
							<option value="WI" <?php if($accountData["ship_state"]=="WI") echo " selected"; ?>>Wisconsin</option>
							<option value="WY" <?php if($accountData["ship_state"]=="WY") echo " selected"; ?>>Wyoming</option>
							</optgroup>
					</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Precision/Vision Pro
															</div></td>
					<td colspan="4" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[precision_vp_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$precision_vp_lab=mysql_fetch_array($result);
		echo "<b>$precision_vp_lab[lab_name]</b>";
	?>
					</td>
					<td align="left" ><div align="right">
						 Zip/Postal Code 
					</div></td>
					<td align="left">
						<input name="ship_zip" type="text" id="ship_zip" size="20" value="<?php echo $accountData["ship_zip"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												Country						
					</div></td>
					<td align="left">
						<select name = "ship_country" id="ship_country" class="formField">
							<option value="">Select One</option>
							<option value = "CA" <?php if($accountData["ship_country"]=="CA") echo " selected"; ?>>Canada</option>
							<option value = "US" <?php if($accountData["ship_country"]=="US") echo " selected"; ?>>United
								States</option>
							</select>					</td>
				</tr>
				<tr>
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Vision Pro Poly 
															</div></td>
					<td colspan="4" align="left" >
	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[visionpropoly_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$visionpropoly_lab=mysql_fetch_array($result);
		echo "<b>$visionpropoly_lab[lab_name]</b>";
	?>
					</td>
					<td align="left" ><div align="right">
						 Phone 
					</div></td>
					<td align="left">
						<input name="phone" type="text" id="phone" size="20" value="<?php echo $accountData["phone"]; ?>" class="formField">					</td>
					<td align="left" nowrap><div align="right">
												Other Phone						
					</div></td>
					<td align="left">
						<input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $accountData["other_phone"]; ?>" class="formField">					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" >&nbsp;</td>
					<td colspan="2" align="left" ><div align="right">
						Main
					</div></td>
					<td colspan="4" align="left" >	<?php
	$query="select primary_key, lab_name from labs where primary_key='$accountData[main_lab]'";
	$result=mysql_query($query)
		or die ("Could not find lab");
	$main_lab=mysql_fetch_array($result);
		echo "<b>$main_lab[lab_name]</b>";
	?>
					</td>
					<td align="left" >&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="11" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$accountData[primary_key]"; ?>">
            			<input type="hidden" name="user_id" value="<?php echo "$accountData[user_id]"; ?>" />
            			<input type="hidden" name="notifyApproved" value="<?php echo "$accountData[approved]"; ?>">
                        <input type="submit" name="editAcct" id="edit" value="Edit Account" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField">
<br>
<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b>Edit Account cannot be reversed.</b></font></td>
            		</tr>
			</table>
</form>
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="2"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Stock Discounts</font></b></td>
            		</tr>
<?php
$stockQuery="SELECT prices.product_name as prd_name, prices.primary_key as price_key from prices

LEFT JOIN (stock_discounts) ON (prices.product_name = stock_discounts.product_name) 

WHERE stock_discounts.user_id = '$accountData[user_id]' order by prices.product_name";

$stockResult=mysql_query($stockQuery)
	or die ("Could not find stock discounts");
echo "<form action=\"getAccount.php\" method=\"post\" name=\"discountForm\">";
while ($stockData=mysql_fetch_array($stockResult)){
	$field_name="key_" . $stockData[price_key];
	echo "<tr><td align=\"right\" >$stockData[prd_name]:</td><td><input name=\"$field_name\" type=\"text\" size=\"3\" value=\"$stockData[discount]\" class=\"formField\" /></td></tr>";
}
echo "<tr><td align=\"center\" colspan=\"2\"><input name=\"submit\" type=\"submit\" value=\"submit\" class=\"formField\" /></td></tr></form>";
?>
</table>