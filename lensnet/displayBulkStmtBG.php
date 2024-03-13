<?php
	if ($listItem['product_line']=="directlens")
		{$dl_logo_file="http://www.direct-lens.com/lensnet/images/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="http://www.direct-lens.com/lensnet/images/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="http://www.direct-lens.com/lensnet/images/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	else
		{$dl_logo_file="http://www.direct-lens.com/lensnet/images/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		?>

<table width="750" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="http://www.direct-lens.com/lensnet/images/<?php echo "$logo_file"; ?>" /><td align="right"><img src="http://www.direct-lens.com/lensnet/images/<?php echo "$dl_logo_file"; ?>" width="200" height="60" /></td></tr></table>
<table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead">BUYING GROUP
                	INFORMATION </td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Contact
                	Name:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[contact_first] $listItem[contact_last]";?></strong></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">Buying Group: </td>
                <td class="formCellNosides"><strong><?php echo "$listItem[bg_name]";?></strong></td>
              </tr>
            </table>
			<!--<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead">BILLING ADDRESS </td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Address 1:</td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$listItem[BG_address1]";?></strong></td>
              </tr>  <tr >
                <td width="130" align="right" class="formCellNosides">Address 2:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[BG_address2]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">City:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[BG_city]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">State:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[BG_state]";?></strong> </td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">Postal Code:  </td>
                <td class="formCellNosides"><strong><?php echo "$listItem[BG_zip]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides">Country:</td>
                <td class="formCellNosides"><strong><?php echo "$listItem[BG_country]";?></strong></td>
              </tr>
            </table>-->

<!--Print main lab address info-->
<table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead">Remit payment to:
		</td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Lab:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "{$_SESSION[labAdminData][lab_name]}"; ?></strong></td>
                </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Address:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "{$_SESSION[labAdminData][address1]} {$_SESSION[labAdminData][address2]}"; ?></strong></td>
                </tr>
				              <tr >
                <td width="130" align="right" class="formCellNosides">City, State, Postal Code</td>
                <td width="520" class="formCellNosides"><strong><?php echo "{$_SESSION[labAdminData][city]} {$_SESSION[labAdminData][state]} {$_SESSION[labAdminData][zip]}"; ?></strong></td>
                </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides">Phone:</td>
                <td width="520" class="formCellNosides"><strong><?php echo "{$_SESSION[labAdminData][phone]}"; ?></strong></td>
                </tr></table>
                
            <table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
<tr><td colspan="11" class="formCellNosides">BALANCE CARRIED FORWARD</td><td class="formCellNosides"><div align="right"><?php echo "\$$acctBalance"; ?></div></td></tr></table>

			<table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
<tr bgcolor="#000099"><td colspan="10" class="tableHead"><?php echo "$heading"; ?></td>
</tr>
              <tr>
                <td class="formCellNosides">Order</td>
                <td class="formCellNosides">Order Date</td>
                <td class="formCellNosides">Ship Date</td>
                <td class="formCellNosides">Name</td>
                <td class="formCellNosides">Patient Ref</td>
<!--                <td class="formCellNosides">First</td>
                <td class="formCellNosides">Last</td>
-->                <!--<td class="formCellNosides">Status</td>-->
                <td class="formCellNosides"><div align="right">Order Total</div></td>
                <td class="formCellNosides"><div align="center">Pmt Status</div></td>
                <td class="formCellNosides"><div align="center">Pmt Type</div></td>
                <td class="formCellNosides"><div align="right">Pmt Total</div></td>
                <td class="formCellNosides"><div align="right">Balance</div></td>
              </tr>
