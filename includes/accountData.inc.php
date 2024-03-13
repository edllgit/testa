<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
	<tr >
		<td colspan="2" bgcolor="#000099" class="tableHead">ACCOUNT INFORMATION </td>
	</tr>
	<tr >
		<td width="130" align="right" class="formCellNosides">Name on Account :</td>
		<td width="520" class="formCellNosides"><strong><?php print "$userData[title] $userData[first_name] $userData[last_name]";?></strong></td>
	</tr>
	<tr >
		<td align="right" class="formCellNosides">Company:</td>
		<td width="520" class="formCellNosides"><strong><?php print 
$userData[company];?></strong> </td>
	</tr>
	<tr >
		<td align="right" class="formCellNosides">Buying Group: </td>
		<td class="formCellNosides"><strong><?php print 
$bgData[bg_name];?></strong></td>
	</tr>
</table>
