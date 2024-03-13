<link href="admin.css" rel="stylesheet" type="text/css" />
<?php
$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);
	
$result=mysql_query("SELECT DATE_ADD('$orderData[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($totalPriceDsc, $order_shipping_cost, 2);

if($discountdate_15 >= $today){
	$discountamt=bcmul('.02', $item_total, 2);
	$pass_disc=".02";
	$discount = "2%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.01', $item_total, 2);
	$pass_disc=".01";
	$discount = "1%";
}
$discounted_total_cost = bcsub($totalPriceDsc, $discountamt, 2);
$new_result=mysql_query("SELECT DATE_FORMAT('$orderData[order_date_processed]','%m-%d-%Y')");
$order_date=mysql_result($new_result,0,0);
?>


<form name="form3" method="post" action="https://direct-lens.com/labAdmin/process_cc.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="8"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_entpayorder_txt;?><input type="hidden" name="today" value="<?php echo "$today"; ?>" />
            		</font></b></td>
           		</tr>

              <tr bgcolor="#DDDDDD">
					<td width="34" align="left"><div align="right">
						<?php echo $adm_ordernumber2_txt;?></div></td>
              		<td width="62" align="left"><?php echo "$_GET[order_num]"; ?>
						<input type="hidden" name="order_num" value="<?php echo "$_GET[order_num]"; ?>" /></td>
              		<td width="34" align="left"><div align="right"> <?php echo $adm_orderdate_txt;?> </div></td>
              		<td width="167" align="left"><?php echo "$order_date"; ?></td>
              		<td width="24" align="left" nowrap><div align="right">
              		<?php echo $adm_orderamountminus_txt;?>
              		</div></td>
              		<td width="121" align="left"><?php echo $lbl_moneysym_txt;?><?php echo "$item_total"; ?></td>
              		<td width="40" align="left">&nbsp;</td>
              		<td width="441" align="left">&nbsp;</td>
              </tr>
              <tr>
              	<td align="left" colspan="3"><div align="right">
              		<?php echo $adm_totorderamount_txt;?></div></td>
              	<td align="left"><?php echo $adm_moneysym_txt;?><?php echo "$totalPriceDsc " . $userData["currency"]; ?>
              		<input type="hidden" name="currency" value="<?php echo $userData["currency"]; ?>"></td>
              	<td align="left" nowrap><div align="right">
              		<?php echo $adm_earlypayment_txt;?></div></td>
              	<td align="left"><?php if(!$discountamt) echo "Not Eligible"; else echo "\$$discountamt ($discount disc)"; ?>
              		<input type="hidden" name="pass_disc" value="<?php echo "$pass_disc"; ?>"></td>
              	<td align="left"><img src="../images/spacer.gif" width="40" height="1" /></td>
              	<td align="left">&nbsp;</td>
              </tr>
              <tr bgcolor="#DDDDDD">
              	<td colspan="3" align="left"><div align="right">
              		<?php echo $adm_totalamountto_txt;?></div></td>
              	<td align="left"><?php echo $adm_moneysym_txt;?><?php echo "$discounted_total_cost " . $userData["currency"]; ?>
           		  <input type="hidden" name="total_cost" value="<?php echo "$discounted_total_cost"; ?>">              	</td>
              	<td align="left" nowrap><div align="right">
              		<?php echo $adm_shippingcharges_txt;?></div></td>
              	<td colspan="3" align="left"><?php echo $adm_moneysym_txt;?><?php echo "$order_shipping_cost"; ?></td>
              	</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" ><div align="right"> <?php echo $adm_payby_txt;?></div></td>
					<td nowrap="nowrap"><div align="right">
						<input name="pmt_type" type="radio" class="formField" value="credit card" checked="checked" />
					</div></td>
					<td align="left" nowrap><div align="left"> <?php echo $adm_creditcard_txt;?></div></td>
					<td align="left"><div align="right">
						<input name="pmt_type" type="radio" value="check" class="formField3" />
					</div></td>
					<td align="left" nowrap><div align="left"> <?php echo $adm_check_txt;?></div></td>
					<td align="left"><div align="right"></div></td>
					<td colspan="2" align="left"><?php echo $adm_checknumber_txt;?>					  <input name="check_num" type="text" class="formField3" id="check_num" size="8" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right"> <?php echo $adm_acctnum_txt;?> </div></td>
					<td nowrap="nowrap"><?php echo $userData["account_num"]; ?>
					<input type="hidden" name="user_id" value="<?php echo $userData["user_id"]; ?>" /></td>
					<td align="left" nowrap><div align="right">
												<?php echo $adm_fname_txt;?>						
					</div></td>
					<td align="left">
					<input name="first_name" type="text" class="formField3" id="first_name" value="<?php echo $userData["first_name"]; ?>" size="20">					</td>
					<td align="left" nowrap><div align="right">
												<?php echo $adm_lname_txt;?>						
					</div></td>
					<td colspan="3" align="left">
					<input name="last_name" type="text" class="formField3" id="last_name" value="<?php echo $userData["last_name"]; ?>" size="20">					</td>
				</tr>
				<tr bgcolor="#FFFFFF">
					<td align="left" nowrap="nowrap" >&nbsp;</td>
					<td>&nbsp;</td>
					<td align="left" ><div align="right">
							 <?php echo $adm_company_txt;?> 
					</div></td>
					<td align="left">
						<input name="company" type="text" id="company" size="20" value="<?php echo $userData["company"]; ?>" class="formField3" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_buygrp_txt;?>  
					</div></td>
					<td colspan="3" align="left">
						<select name="buying_group" class="formField3" id="buying_group">
							<?php
	$query="select primary_key, bg_name from buying_groups order by bg_name";
	$result=mysql_query($query)
		or die ("Could not find bg list");
	while ($bgList=mysql_fetch_array($result)){
		echo "<option value=\"$bgList[primary_key]\""; if($userData["buying_group"]==$bgList[primary_key]) echo " selected"; echo ">$bgList[bg_name]</option>";
}
?>
</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="8" align="left" bgcolor="#000000" ><div align="center">
							<font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b><?php echo $adm_titlemast_billadd;?></b></font>
					</div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="3" align="left" ><div align="right">
							 <?php echo $adm_company_txt;?> </div></td>
					<td align="left">
						<input name="bill_address1" type="text" id="bill_address1" size="20" value="<?php echo $userData["bill_address1"]; ?>" class="formField3" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_entpayorder_txt;?> 
					</div></td>
					<td colspan="3" align="left">
						<input name="bill_address2" type="text" id="bill_address2" size="20" value="<?php echo $userData["bill_address2"]; ?>" class="formField3" />					</td>
				</tr>
				<tr>
					<td colspan="3" align="left" ><div align="right">
							 <?php echo $adm_city_txt;?> 
					</div></td>
					<td align="left">
						<input name="bill_city" type="text" id="bill_city" size="20" value="<?php echo $userData["bill_city"]; ?>" class="formField3" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_state_txt;?></div></td>
					<td colspan="3" align="left"><select id="bill_state" name="bill_state" class="formField3">
						<optgroup label="Canadian Provinces">
							<option value="AB" <?php if($userData["bill_state"]=="AB") echo " selected"; ?>>Alberta</option>
							<option value="BC" <?php if($userData["bill_state"]=="BC") echo " selected"; ?>>British
							Columbia</option>
							<option value="MB" <?php if($userData["bill_state"]=="MB") echo " selected"; ?>>Manitoba</option>
							<option value="NB" <?php if($userData["bill_state"]=="NB") echo " selected"; ?>>New
							Brunswick</option>
							<option value="NF" <?php if($userData["bill_state"]=="NF") echo " selected"; ?>>Newfoundland</option>
							<option value="NT" <?php if($userData["bill_state"]=="NT") echo " selected"; ?>>Northwest
							Territories</option>
							<option value="NS" <?php if($userData["bill_state"]=="NS") echo " selected"; ?>>Nova
							Scotia</option>
							<option value="NU" <?php if($userData["bill_state"]=="NU") echo " selected"; ?>>Nunavut</option>
							<option value="ON" <?php if($userData["bill_state"]=="ON") echo " selected"; ?>>Ontario</option>
							<option value="PE" <?php if($userData["bill_state"]=="PE") echo " selected"; ?>>Prince
							Edward Island</option>
							<option value="QC" <?php if($userData["bill_state"]=="QC") echo " selected"; ?>>Quebec</option>
							<option value="SK" <?php if($userData["bill_state"]=="SK") echo " selected"; ?>>Saskatchewan</option>
							<option value="YT" <?php if($userData["bill_state"]=="YT") echo " selected"; ?>>Yukon
							Territory</option>
						</optgroup>
							<optgroup label="U.S. States">
							<option value="AL" <?php if($userData["bill_state"]=="AL") echo " selected"; ?>>Alabama</option>
							<option value="AK" <?php if($userData["bill_state"]=="AK") echo " selected"; ?>>Alaska</option>
							<option value="AZ" <?php if($userData["bill_state"]=="AZ") echo " selected"; ?>>Arizona</option>
							<option value="AR" <?php if($userData["bill_state"]=="AR") echo " selected"; ?>>Arkansas</option>
							<option value="CA" <?php if($userData["bill_state"]=="CA") echo " selected"; ?>>California</option>
							<option value="CO" <?php if($userData["bill_state"]=="CO") echo " selected"; ?>>Colorado</option>
							<option value="CT" <?php if($userData["bill_state"]=="CT") echo " selected"; ?>>Connecticut</option>
							<option value="DE" <?php if($userData["bill_state"]=="DE") echo " selected"; ?>>Delaware</option>
							<option value="DC" <?php if($userData["bill_state"]=="DC") echo " selected"; ?>>District
							of Columbia</option>
							<option value="FL" <?php if($userData["bill_state"]=="FL") echo " selected"; ?>>Florida</option>
							<option value="GA" <?php if($userData["bill_state"]=="GA") echo " selected"; ?>>Georgia</option>
							<option value="HI" <?php if($userData["bill_state"]=="HI") echo " selected"; ?>>Hawaii</option>
							<option value="ID" <?php if($userData["bill_state"]=="ID") echo " selected"; ?>>Idaho</option>
							<option value="IL" <?php if($userData["bill_state"]=="IL") echo " selected"; ?>>Illinois</option>
							<option value="IN" <?php if($userData["bill_state"]=="IN") echo " selected"; ?>>Indiana</option>
							<option value="IA" <?php if($userData["bill_state"]=="IA") echo " selected"; ?>>Iowa</option>
							<option value="KS" <?php if($userData["bill_state"]=="KS") echo " selected"; ?>>Kansas</option>
							<option value="KY" <?php if($userData["bill_state"]=="KY") echo " selected"; ?>>Kentucky</option>
							<option value="LA" <?php if($userData["bill_state"]=="LA") echo " selected"; ?>>Louisiana</option>
							<option value="ME" <?php if($userData["bill_state"]=="ME") echo " selected"; ?>>Maine</option>
							<option value="MD" <?php if($userData["bill_state"]=="MD") echo " selected"; ?>>Maryland</option>
							<option value="MA" <?php if($userData["bill_state"]=="MA") echo " selected"; ?>>Massachusetts</option>
							<option value="MI" <?php if($userData["bill_state"]=="MI") echo " selected"; ?>>Michigan</option>
							<option value="MN" <?php if($userData["bill_state"]=="MN") echo " selected"; ?>>Minnesota</option>
							<option value="MS" <?php if($userData["bill_state"]=="MS") echo " selected"; ?>>Mississippi</option>
							<option value="MO" <?php if($userData["bill_state"]=="MO") echo " selected"; ?>>Missouri</option>
							<option value="MT" <?php if($userData["bill_state"]=="MT") echo " selected"; ?>>Montana</option>
							<option value="NE" <?php if($userData["bill_state"]=="NE") echo " selected"; ?>>Nebraska</option>
							<option value="NV" <?php if($userData["bill_state"]=="NV") echo " selected"; ?>>Nevada</option>
							<option value="NH" <?php if($userData["bill_state"]=="NH") echo " selected"; ?>>New
							Hampshire</option>
							<option value="NJ" <?php if($userData["bill_state"]=="NJ") echo " selected"; ?>>New
							Jersey</option>
							<option value="NM" <?php if($userData["bill_state"]=="NM") echo " selected"; ?>>New
							Mexico</option>
							<option value="NY" <?php if($userData["bill_state"]=="NY") echo " selected"; ?>>New
							York</option>
							<option value="NC" <?php if($userData["bill_state"]=="NC") echo " selected"; ?>>North
							Carolina</option>
							<option value="ND" <?php if($userData["bill_state"]=="ND") echo " selected"; ?>>North
							Dakota</option>
							<option value="OH" <?php if($userData["bill_state"]=="OH") echo " selected"; ?>>Ohio</option>
							<option value="OK" <?php if($userData["bill_state"]=="OK") echo " selected"; ?>>Oklahoma</option>
							<option value="OR" <?php if($userData["bill_state"]=="OR") echo " selected"; ?>>Oregon</option>
							<option value="PA" <?php if($userData["bill_state"]=="PA") echo " selected"; ?>>Pennsylvania</option>
							<option value="PR" <?php if($userData["bill_state"]=="PR") echo " selected"; ?>>Puerto
							Rico</option>
							<option value="RI" <?php if($userData["bill_state"]=="RI") echo " selected"; ?>>Rhode
							Island</option>
							<option value="SC" <?php if($userData["bill_state"]=="SC") echo " selected"; ?>>South
							Carolina</option>
							<option value="SD" <?php if($userData["bill_state"]=="SD") echo " selected"; ?>>South
							Dakota</option>
							<option value="TN" <?php if($userData["bill_state"]=="TN") echo " selected"; ?>>Tennessee</option>
							<option value="TX" <?php if($userData["bill_state"]=="TX") echo " selected"; ?>>Texas</option>
							<option value="UT" <?php if($userData["bill_state"]=="UT") echo " selected"; ?>>Utah</option>
							<option value="VT" <?php if($userData["bill_state"]=="VT") echo " selected"; ?>>Vermont</option>
							<option value="VA" <?php if($userData["bill_state"]=="VA") echo " selected"; ?>>Virginia</option>
							<option value="WA" <?php if($userData["bill_state"]=="WA") echo " selected"; ?>>Washington</option>
							<option value="WV" <?php if($userData["bill_state"]=="WV") echo " selected"; ?>>West
							Virginia</option>
							<option value="WI" <?php if($userData["bill_state"]=="WI") echo " selected"; ?>>Wisconsin</option>
							<option value="WY" <?php if($userData["bill_state"]=="WY") echo " selected"; ?>>Wyoming</option>
							</optgroup>
					</select></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="3" align="left" ><div align="right">
							 <?php echo $adm_zip_txt;?> 
					</div></td>
					<td align="left">
						<input name="bill_zip" type="text" id="bill_zip" size="20" value="<?php echo $userData["bill_zip"]; ?>" class="formField3" />					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 <?php echo $adm_country_txt;?> 
					</div></td>
					<td colspan="3" align="left">
						<select name = "bill_country" id="bill_country" class="formField3">
							<option value="">Select One</option>
							<option value = "CA" <?php if($userData["bill_country"]=="CA") echo " selected"; ?>>Canada</option>
							<option value = "US" <?php if($userData["bill_country"]=="US") echo " selected"; ?>>United
							States</option>
						</select>					</td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="8" align="left" bgcolor="#000000" ><div align="center"> <font color="#FFFFFF" size="1" face="Arial, Helvetica, sans-serif"><b><?php echo $adm_titlemast_ccard;?></b></font> </div></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td colspan="3" align="left" ><div align="right"><?php echo $adm_cardtype_txt;?> </div></td>
					<td align="left">
						<select name="cc_type" id="cc_type" class="formField3">
							<option value=""><?php echo $adm_ccardtype_txt;?></option>
							<option value="American Express">AMEX</option>
							<option value="Discover">Discover</option>
							<option value="MasterCard">MasterCard</option>
							<option value="VISA">VISA</option>
					</select></td>
					<td align="left" nowrap="nowrap"><div align="right"><?php echo $adm_cardnumber_txt;?></div></td>
					<td colspan="3" align="left">
						<input name="cc_no" type="text" id="cc_no" size="20" class="formField3" />					</td>
				</tr>
				<tr>
					<td colspan="3" align="left" ><div align="right"><?php echo $adm_expdate_txt;?> </div></td>
					<td align="left">
						<select name="cc_month" id="cc_month" class="formField3">
							<option value="">Month</option>
							<option value="01">01 - Jan</option>
							<option value="02">02 - Feb</option>
							<option value="03">03 - Mar</option>
							<option value="04">04 - Apr</option>
							<option value="05">05 - May</option>
							<option value="06">06 - Jun</option>
							<option value="07">07 - Jul</option>
							<option value="08">08 - Aug</option>
							<option value="09">09 - Sep</option>
							<option value="10">10 - Oct</option>
							<option value="11">11 - Nov</option>
							<option value="12">12 - Dec</option>
						</select>
						<select name="cc_year" id="cc_year" class="formField3">
							<option value="">Year</option>
							<option value="08">2008</option>
							<option value="09">2009</option>
							<option value="10">2010</option>
							<option value="11">2011</option>
							<option value="12">2012</option>
						</select>					</td>
					<td align="left" nowrap="nowrap"><div align="right"><?php echo $adm_cvv_txt;?></div></td>
					<td colspan="3" align="left">
						<input name="cvv" type="text" id="cvv" size="5" class="formField3" />					</td>
				</tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="8" align="center" bgcolor="#FFFFFF">
            			<input type="submit" name="billCard" id="billCard" value="<?php echo $btn_entpymnt_txt;?>" class="formField3"></td>
            		</tr>
	</table>
</form>