<form name="form3" method="post" action="getBuying_group.php" onSubmit="return formCheck(this);" class="formField">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo "$heading"; ?></font></b></td>
            		</tr>

				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
												Group
						Name						
					</div></td>
					<td align="left">
						<input name="bg_name" type="text" id="bg_name" size="20" value="<?php echo $BGData["bg_name"]; ?>" class="formField">					</td>
					<td align="left" nowrap="nowrap"><div align="right">
							 Contact First Name
					</div></td>
					<td align="left"><input name="contact_first" type="text" id="contact_first" size="20" value="<?php echo $BGData["contact_first"]; ?>" class="formField" /></td>
					<td align="left"><div align="right">
						Contact Last Name
					</div></td>
					<td align="left"><input name="contact_last" type="text" id="contact_last" size="20" value="<?php echo $BGData["contact_last"]; ?>" class="formField" /></td>
				</tr>
				<tr>
					<td align="left" ><div align="right">
												Email						
					</div></td>
					<td align="left">
						<input name="email" type="text" id="email" size="40" value="<?php echo $BGData["bg_email"]; ?>" class="formField">
							
						<div align="right">
						</div></td>
					<td align="left"><div align="right">
						Login
					</div></td>
					<td align="left"><b><?php echo $BGData["username"]; ?></b></td>
					<td align="left"><div align="right">
						Password
					</div></td>
					<td align="left"><input name="password" type="text" id="password" size="20" value="<?php echo $BGData["password"]; ?>" class="formField" /></td>
				</tr>
				<tr bgcolor="#DDDDDD">
					<td align="left" ><div align="right">
						Global Discount
					</div></td>
					<td align="left"><input name="global_dsc" type="text" id="global_dsc" size="2" maxlength="2" value="<?php echo $BGData["global_dsc"]; ?>" class="formField" /></td>
					<td align="left" nowrap="nowrap">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
					<td align="left">&nbsp;</td>
				</tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$BGData[primary_key]"; ?>">
                        <input type="submit" name="editBG" id="editBG" value="Edit Group" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField">
<br>
<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b>Edit Group
cannot be reversed.</b></font></td>
            		</tr>
			</table>
  		</form>