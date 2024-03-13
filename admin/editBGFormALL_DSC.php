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
							
						<div align="right">						</div></td>
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
						Discounts
					</div></td>
					<td align="left"><div align="right">
						My World
					</div></td>
					<td align="left" nowrap="nowrap"><input name="innovative_dsc" type="text" id="innovative_dsc" size="2" maxlength="2" value="<?php echo $BGData["innovative_dsc"]; ?>" class="formField" /></td>
					<td align="left"><div align="right">
						Infocus
					</div></td>
					<td align="left"><input name="infocus_dsc" type="text" id="infocus_dsc" size="2" maxlength="2" value="<?php echo $BGData["infocus_dsc"]; ?>" class="formField" /></td>
					<td align="left">&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" >&nbsp;</td>
				  <td align="left"><div align="right"> Precision </div></td>
				  <td align="left" nowrap="nowrap"><input name="precision_vp_dsc" type="text" id="precision_vp_dsc" size="2" maxlength="2" value="<?php echo $BGData["precision_vp_dsc"]; ?>" class="formField" /></td>
				  <td align="left"><div align="right"> Vision Pro </div></td>
				  <td align="left"><input name="visionpropoly_dsc" type="text" id="visionpropoly_dsc" size="2" maxlength="2" value="<?php echo $BGData["visionpropoly_dsc"]; ?>" class="formField" /></td>
				  <td align="left">&nbsp;</td>
			  </tr>
				<tr>
					<td align="left" bgcolor="#DDDDDD" >&nbsp;</td>
					<td align="left" bgcolor="#DDDDDD"><div align="right">
						Generation
					</div></td>
					<td align="left" nowrap="nowrap" bgcolor="#DDDDDD"><input name="generation_dsc" type="text" id="generation_dsc" size="2" maxlength="2" value="<?php echo $BGData["generation_dsc"]; ?>" class="formField" /></td>
					<td align="left" bgcolor="#DDDDDD"><div align="right">
						TrueHD
					</div></td>
					<td align="left" bgcolor="#DDDDDD"><input name="truehd_dsc" type="text" id="truehd_dsc" size="2" maxlength="2" value="<?php echo $BGData["truehd_dsc"]; ?>" class="formField" /></td>
					<td align="left" bgcolor="#DDDDDD">&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" bgcolor="#FFFFFF" >&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF"><div align="right"> Easy Fit HD </div></td>
				  <td align="left" nowrap="nowrap" bgcolor="#FFFFFF"><input name="easy_fit_dsc" type="text" id="easy_fit_dsc" size="2" maxlength="2" value="<?php echo $BGData["truehd_dsc"]; ?>" class="formField" /></td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
				  <td align="left" bgcolor="#FFFFFF">&nbsp;</td>
			  </tr>
				            	<tr bgcolor="#FFFFFF">
            		<td colspan="6" align="center" bgcolor="#FFFFFF"><input type="hidden" name="pkey" value="<?php echo "$BGData[primary_key]"; ?>">
                        <input type="submit" name="editBG" id="editBG" value="Edit Group" class="formField">
&nbsp;
<input name="deleteBG" type="submit" id="deleteBG" value="Delete Group" class="formField">
&nbsp;
<input name="cancel" type="button" id="cancel" value="Cancel" onClick="window.open('adminHome.php', '_top')" class="formField">
<br>
<font color="#FF0000" size="1" face="Arial, Helvetica, sans-serif"><b>Edit Group
OR Delete Group cannot be reversed.</b></font></td>
            		</tr>
			</table>
  		</form>