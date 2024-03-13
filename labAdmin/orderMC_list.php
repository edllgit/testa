<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
	<tr bgcolor="#000000"><td align="center" colspan="5"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">ORDERS MATCHING PATIENT REFERENCE NUMBER <?php echo $_POST[patient_ref_num];?></font></b></td></tr>
	<tr>
		<td nowrap><b>Order Number </b></td>
		<td nowrap><b>Order Date </b></td>
		<td nowrap><b>Patient
				Reference Number </b></td>
		<td nowrap><b>Patient First Name </b></td>
		<td nowrap><b>Patient Last Name </b></td>
	</tr>
	<?php
	while($listData=mysql_fetch_array($orderResult)){
		$new_result=mysql_query("SELECT DATE_FORMAT('$listData[order_date_processed]','%m-%d-%Y')");
		$order_date=mysql_result($new_result,0,0);
		echo "<tr><td nowrap><a href=\"createMemoCredit.php?order_num=$listData[order_num]\">$listData[order_num]</a></td><td nowrap>$order_date</td><td nowrap>$listData[patient_ref_num]</td><td nowrap>$listData[order_patient_first]</td><td nowrap>$listData[order_patient_last]</td></tr>";
	}
		?>
</table>
