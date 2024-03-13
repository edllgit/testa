<?php
	$uniqid = rand(100000, 999999);
?>
              <tr bgcolor="#999999">
					<td colspan="4" align="left"><div align="center"><b>
						Credit Card Data</b>
			  	  </div></td>
   	</tr>
              <tr>
					<td align="left" ><div align="right">
						Card Type
					</div></td>
              	<td align="left"><select name="cc_type" id="cc_type" class="formField">
					<option value="">Credit Card Type</option>
					<option value="American Express">AMEX</option>
					<option value="Discover">Discover</option>
					<option value="MasterCard">MasterCard</option>
					<option value="VISA">VISA</option>
				</select></td>
              	<td align="left" nowrap><div align="right">
              		Card Number
              		</div></td>
              	<td align="left"><input name="cc_no" type="text" id="cc_no" size="20" class="formField"></td>
              	</tr>
              <tr>
					<td align="left" ><div align="right">
						Exp Date
					</div></td>
              	<td align="left"><select name="cc_month" id="cc_month" class="formField">
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
              		<select name="cc_year" id="cc_year" class="formField">
						<option value="">Year</option>
						<option value="11">2011</option>
						<option value="12">2012</option>
						<option value="13">2013</option>
						<option value="14">2014</option>
						<option value="15">2015</option>
					</select></td>
              	<td align="left" nowrap><div align="right">
              		CVV
              		</div></td>
              	<td align="left"><input name="cvv" type="text" id="cvv" size="5" class="formField">
           	    <input type="hidden" name="order_num" id="order_num" value="<?php echo "$order_num"; ?>" />
           	    <input type="hidden" name="uniqid" id="uniqid" value="<?php echo "$uniqid"; ?>" />
           	    <input type="hidden" name="first_name" id="first_name" value="<?php echo "$_SESSION[FIRST_NAME]"; ?>" />
           	    <input type="hidden" name="last_name" id="last_name" value="<?php echo "$_SESSION[LAST_NAME]"; ?>" />
           	    <input type="hidden" name="address1" id="address1" value="<?php echo "$_SESSION[ADDRESS1]"; ?>" />
           	    <input type="hidden" name="zip" id="zip" value="<?php echo "$_SESSION[ZIP]"; ?>" />
           	    <input type="hidden" name="total_cost" id="total_cost" value="<?php echo "$_SESSION[grandTotal]"; ?>" /></td>
              	</tr>
