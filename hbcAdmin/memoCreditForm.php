            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php if($heading=="") echo "CREATE MEMO ORDER"; else echo "$heading"; ?></font></b></td>
            		</tr>
			<tr><td>
			<form name="goto_date" id="goto_date" method="post" action="displayMemoCredit.php" onSubmit="return formCheck(this);"><table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo $message;?>
			<tr bgcolor="#DDDDDD">
			  <td width="20%" align="right" valign="middle"><div class="formField2">Order Number:</div></td>
			  <td valign="middle" nowrap><span class="formField2"><?php echo $orderData[order_num];?></span><span class="formField2">
			  	<input name="order_num" type="hidden" id="order_num" value="<?php echo $orderData[order_num];?>">
			  </span></td>
			  <td width="10%" align="right" valign="middle" nowrap><div class="formField2">
				Order Total:
			</div></td>
			  		<td valign="middle" nowrap><span class="formField2"><?php echo $orderData[order_total]; ?>
			  			<input name="prev_order_total" type="hidden" id="prev_order_total" value="<?php echo $orderData[order_total];?>">
			  			<input name="prev_additional_dsc" type="hidden" id="prev_additional_dsc" value="<?php echo $orderData[additional_dsc];?>">
			  		</span></td>
			</tr>
			<tr>
				<td align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Customer Name:
				</div></td>
				<td align="left" valign="middle" nowrap><?php echo $userData[company];?></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td width="20%" align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
				Customer Account: 
			</div></td>
			  <td align="left" valign="middle" nowrap><?php echo $userData[account_num];?><span class="formField2">
			  	<input name="acct_user_id" type="hidden" id="acct_user_id" value="<?php echo $orderData[user_id];?>" />
			  </span></td>
			</tr>
			<tr>
				<td align="right" valign="middle">&nbsp;</td>
				<td valign="middle">&nbsp;</td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Patient Reference:
				</div></td>
				<td align="left" valign="middle" nowrap><?php echo $orderData[patient_ref_num];?><span class="formField2">
					<input name="patient_ref_num" type="hidden" id="patient_ref_num" value="<?php echo $orderData[patient_ref_num];?>" />
				</span></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					Credit Type:
				</div></td>
				<td valign="middle" nowrap><div class="formField3">
					<input name="mcred_cred_type" type="radio" value="credit" class="formField" checked="checked">
					Credit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="mcred_cred_type" type="radio" value="debit" disabled="disabled" class="formField">Debit
					</div></td>
				<td align="right" nowrap><div class="formField3">
					Patient First Name:
				</div></td>
				<td valign="middle"><?php echo $orderData[order_patient_first];?><span class="formField2">
					<input name="order_patient_first" type="hidden" id="order_patient_first" value="<?php echo $orderData[order_patient_first];?>" />
				</span></td>
				</tr>
			<tr>
				<td align="right" valign="middle"><div class="formField2">
					Discount Type:
				</div></td>
				<td valign="middle"><div class="formField3">
					<input name="mcred_disc_type" type="radio" value="absolute" class="formField" checked="checked">
					Absolute Amount&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="mcred_disc_type" type="radio" value="percent" class="formField">
					Percentage
					
				</div></td>
				<td align="right" valign="middle" nowrap><div class="formField3">
					Patient Last Name:
				</div></td>
				<td align="left" valign="middle"><?php echo $orderData[order_patient_last];?><span class="formField2">
					<input name="order_patient_last" type="hidden" id="order_patient_last" value="<?php echo $orderData[order_patient_last];?>" />
				</span></td>
			</tr>
            
            
            
            
            
            
            
            
            
           <?php  
		   $queryOptipoints ="SELECT lnc_reward_points as point_balance from accounts where user_id = '$orderData[user_id]'";
		   $resultOptipoints=mysql_query($queryOptipoints)	or die  ('I cannot select items because: ' . mysql_error());
		   $DataOptiPoints=mysql_fetch_array($resultOptipoints);
		  ?>
            
            <tr>
				<td align="right" valign="middle"><div class="formField2">
					&nbsp;
				</div></td>
				<td valign="middle"><div class="formField3">
				&nbsp;</div></td>
				<td align="right" valign="middle" nowrap><div class="formField3">
				Customer Optipoints balance
				</div></td>
				<td align="left" valign="middle"><input type="text" size="4" readonly="readonly" name="point_balance" id="point_balance" value="<?php echo $DataOptiPoints[point_balance] ;?>" />   Points<span class="formField2">
					
				</span></td>
			</tr>
            
            
            
            
            
            
            
            
			<tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					Discount Value:
				</div></td>
				<td valign="middle"><input name="mcred_amount" type="text" id="mcred_amount" size="10" class="formField">
					(10.00)</td>
				<td align="right" valign="middle" nowrap>Discount Detail:</td>
				<td align="left" valign="middle"><textarea name="mcred_detail" id="mcred_detail"  class="formField"></textarea></td>
			</tr>
			<tr>
				<td align="right" valign="middle"><div class="formField2">
					Reason:
				</div></td>
				<td valign="middle"><select name="mcred_memo_code" class="formField">
				<?php $codeQuery="select * from memo_codes WHERE active='yes' AND mc_lab='$lab_pkey' and mc_primary_key NOT IN (48,58)"; //get lab's memo codes
									$codeResult=mysql_query($codeQuery)
										or die  ('I cannot select items because: ' . mysql_error());
									while($codeData=mysql_fetch_array($codeResult)){
										$codeData[memo_code]=stripslashes($codeData[memo_code]);
										$codeData[mc_description]=stripslashes($codeData[mc_description]);
										echo "<option value=\"$codeData[memo_code]\">$codeData[memo_code] - $codeData[mc_description]</option>";
									}
									?>
				</select></td>
				<td align="right" valign="middle" nowrap>(If Credit Reason is Other, Please explain)</td>
				<td align="left" valign="middle"><input name="other_detail" type="text" class="formField" id="other_detail" value="" size="50"></td>
			</tr>
			<tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					Date:
				</div></td>
				<td valign="middle"><input name="mcred_date" type="text" class="formField" id="mcred_date" value="<?php echo $today; ?>" size="11">
							</td>
				<td align="right" valign="middle" nowrap><br />OptiPoints to substract<br /> <b>NE PAS UTILISER DE SIGNE NEGATIF<br /></b>&nbsp;<b>DO NOT USE ANY NEGATIVE SIGN</b></td>
				<td align="left" valign="middle"><input name="optipoints_to_substract" type="text" class="formField" id="optipoints_to_substract" value="0" size="5"></td>
			</tr>
            
            <tr bgcolor="#DDDDDD">
				<td align="right" valign="middle"><div class="formField2">
					Discount already offered:
				</div></td>
				<td valign="middle"><?php echo $orderData[additional_dsc] . ' ' .  $orderData[discount_type] ; ?></td>
				<td align="right" valign="middle" nowrap>OptiPoints Reason</td>
				<td align="left" valign="middle"><input name="optipoints_reason" type="text" class="formField" id="optipoints_reason" value="" size="50"></td>
			</tr>
            
            
			<tr bgcolor="#FFFFFF">
				<td colspan="4" align="center" valign="middle"><input type="hidden" name="lastMemoNum" value="<?php echo $lastMemoNum; ?>">
				<input type="submit" name="issueMemo" value="Issue Memo" class="formField"></td>
				</tr>
			</table>
			</form></td>
  </tr>
</table>
<?php	
$memoCount=mysql_num_rows($memoResult);
if($memoCount > 0){	

?>
<script language="JavaScript" type="text/JavaScript">
<!--
alert('Un credit a deja ete emis pour cette commande / A credit has already been emited for this order');
//-->
</script>


<?php
	echo "<table width=\"100%\" border=\"0\" cellpadding=\"2\" cellspacing=\"0\" class=\"formField3\">";
	echo "<tr bgcolor=\"#000000\"><td align=\"center\" colspan=\"5\"><b><font color=\"#FFFFFF\" size=\"1\" face=\"Helvetica, sans-serif, Arial\">EXISTING MEMO ORDERS FOR ORDER NUMBER $orderData[order_num]</font></b></td></tr>";
	echo "<tr><td width=\"15%\" nowrap><b>Memo Date</b></td><td width=\"15%\" nowrap><b>Memo Order Number</b></td><td width=\"15%\" nowrap align=\"right\" style=\"padding-right:110px\"><b>Value</b></td><td width=\"5%\" nowrap><b>Reason</b></td><td width=\"10%\" nowrap><b>Delete</b></td></tr>";
	while($memoData=mysql_fetch_array($memoResult)){
		$mcred_abs_amount = "";
		$timestamp=strtotime($memoData[mcred_date]);
		$mcred_date=date("m/d/Y", $timestamp);
		if($memoData[mcred_cred_type]=="credit")
			$mcred_abs_amount = "- ";
		$mcred_abs_amount .= $memoData[mcred_abs_amount];
		echo "<tr><td width=\"15%\" nowrap>$mcred_date</td><td width=\"15%\" nowrap><a href=\"displayMemoCredit.php?mcred_memo_num=$memoData[mcred_memo_num]\">$memoData[mcred_memo_num]</a></td><td width=\"15%\" nowrap align=\"right\" style=\"padding-right:130px\">$mcred_abs_amount</td><td width=\"5%\" nowrap>$memoData[mcred_memo_code]</td><td width=\"10%\" nowrap>";
		
		$timestamp=strtotime($memoData[mcred_date]);
		$mcred_date_month=date("m", $timestamp);
		$tomorrow = mktime(0,0,0,date("m"),date("d"),date("Y"));
		$actualmonth = date("m", $tomorrow);

		echo 'Cannot be deleted (please contact IT support)';
		echo "</td></tr>";
	}
	echo "</table>";
}
?>
 &nbsp;<br>
 
 	<?php 
	$queryVerif="select count(*) as nbrResult from memo_credits_temp WHERE   mcred_approbation = 'pending' AND mcred_order_num='$orderData[order_num]'"; //get lab's memo codes
	$resultVerif=mysql_query($queryVerif)		or die  ('I cannot select items because: ' . mysql_error());
	$DataVerif=mysql_fetch_array($resultVerif);
	if ($DataVerif[nbrResult] > 0)
	{
	?>
	<script language="JavaScript" type="text/JavaScript">
	<!--
	alert('Un credit en attente d\'approbation a deja ete emis pour cette commande. Veuillez valider. / A credit for this order is waiting for approbal. Please check');
	//-->
	</script>
	<?php 
	}