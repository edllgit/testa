<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
	<tr>
		<td colspan="10" bgcolor="#000099" class="tableHead"><div align="center">
			Buying Group Members
		</div></td>
	</tr>
	<tr bgcolor="#E7F2FF">
		<td class="formCell">
			Company		</td>
		<td class="formCell">
			Address		</td>
		<td class="formCell">
			Phone		</td>
		<td colspan="7" align="left" class="formCell"><div align="center">
			Additional Discounts
		</div></td>
	</tr>
	<tr bgcolor="#E7F2FF">
		<td class="formCell">&nbsp;</td>
		<td class="formCell">&nbsp;</td>
		<td class="formCell">&nbsp;</td>
		<td class="formCell"><div align="center">
			My World
		</div></td>
		<td class="formCell"><div align="center">
			Infocus
		</div></td>
		<td class="formCell"><div align="center">
			Precision
		</div></td>
		<td class="formCell"><div align="center">
			VisionPro
		</div></td>
	    <td class="formCell"><div align="center"> Generation </div></td>
	    <td class="formCell"><div align="center"> TrueHD </div></td>
	    <td class="formCell"><div align="center"> Easy Fit HD </div></td>
	</tr>
	<?php
	$count=0;
while($acctData=mysql_fetch_array($memResult)){
//	if(($count%2)==0)
//		$bgcolor="#ffffff";
//	else
//		$bgcolor="#E7F2FF";
	print "<tr>
		<td class=\"formCell\">$acctData[company]</td>
		<td class=\"formCell\">$acctData[bill_address1]";
		if($acctData[bill_address2]!="") print " $acctData[bill_address2]";
		print " $acctData[bill_city], $acctData[bill_state] $acctData[bill_zip]</td>
		<td nowrap class=\"formCell\">$acctData[phone]</td>
		<td align=\"center\" class=\"formCell\">$acctData[innovative_dsc]%</td>
		<td align=\"center\" class=\"formCell\">$acctData[infocus_dsc]%</td>
		<td align=\"center\" class=\"formCell\">$acctData[precision_dsc]%</td>
		<td align=\"center\" class=\"formCell\">$acctData[visionpropoly_dsc]%</td>
		<td align=\"center\" class=\"formCell\">$acctData[generation_dsc]%</td>
		<td align=\"center\" class=\"formCell\">$acctData[truehd_dsc]%</td>
	</tr>";
	$count++;
}
?>
</table>
