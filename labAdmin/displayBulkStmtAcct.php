<?php
require_once(__DIR__.'/../constants/aws.constant.php');
		
		if ($listItem['product_line']=="directlens")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/direct-lens_logo.gif";
		$pl_text="Direct-Lens";}
	elseif ($listItem['product_line']=="mybbgclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/bbgclub_logo.gif";
		$pl_text="MyBBGClub";}
	elseif ($listItem['product_line']=="aitlensclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/aitclub_logo.gif";
		$pl_text="AIT Lens Club";}
	elseif ($listItem['product_line']=="ifcclub")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/ifc_club_logo.gif";
		$pl_text="IFC Optic CLUB";}
	elseif ($listItem['product_line']=="ifcclubca")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.ca";}
	elseif ($listItem['product_line']=="ifcclubus")
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-ca-large.jpg";
		$pl_text="IFC Optic CLUB.us";}
	else
		{$dl_logo_file="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/direct-lens/logos/lensnet_logo.gif";
		$pl_text="LensNet Club";}
		?>
		<?php include "../includes/getlang.php"; ?>
<table width="750" border="0"cellpadding="3" cellspacing="0" align="center"><tr><td align="left"><img src="<?php echo "$dl_logo_file"; ?>" /><td align="right"><img src="<?php echo "$dl_logo_file"; ?>" width="200" height="60" /></td></tr></table>
<table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead"><?php if ($mylang == 'lang_french'){
		echo 'DETAIL DU COMPTE:';
		}else {
		echo 'ACCOUNT INFORMATION:';
		}
		?> </td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Nom au compte:';
		}else {
		echo 'Name on account:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[title] $listItem[first_name] $listItem[last_name]";?></strong></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">
		<?php if ($mylang == 'lang_french'){
		echo 'Compagnie:';
		}else {
		echo 'Company:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$listItem[company]";?></strong> </td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Groupe d\'achat:';
		}else {
		echo 'Buying Group:';
		}
		?> </td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[bg_name]";?></strong></td>
              </tr>
			     <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Courriel:';
		}else {
		echo 'Email:';
		}
		?> </td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[email]";?></strong></td>
              </tr>
			    <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Telephone:';
		}else {
		echo 'Phone:';
		}
		?> </td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[phone]";?></strong></td>
              </tr>
			  
			  <?php  
			  if ($listItem[account_rebate] != '') {
			  ?>
			     <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Rabais au compte:';
		}else {
		echo 'Account rebate:';
		}
		?> </td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[account_rebate]";?>%</strong></td>
              </tr>
			  <?php 
			  }
			  ?>
			  
            </table>
			<table width="750" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="2" bgcolor="#000099" class="tableHead"><?php if ($mylang == 'lang_french'){
		echo 'ADRESSE DE FACTURATION:';
		}else {
		echo 'BILLING ADDRESS:';
		}
		?></td>
              </tr>
              <tr >
                <td width="130" align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Adresse 1:';
		}else {
		echo 'Address 1:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$listItem[bill_address1]";?></strong></td>
              </tr>  <tr >
                <td width="130" align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Adresse 2:';
		}else {
		echo 'Address 2:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$listItem[bill_address2]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Ville:';
		}else {
		echo 'City:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo "$listItem[bill_city]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Province:';
		}else {
		echo 'State:';
		}
		?></td>
                <td width="520" class="formCellNosides"><strong><?php echo 
"$listItem[bill_state]";?></strong> </td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Code Postal:';
		}else {
		echo 'Postal Code:';
		}
		?></td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[bill_zip]";?></strong></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Pays:';
		}else {
		echo 'Country:';
		}
		?></td>
                <td class="formCellNosides"><strong><?php echo 
"$listItem[bill_country]";?></strong></td>
              </tr>
            </table>

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
                <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Commande:';
		}else {
		echo 'Order:';
		}
		?></td>
                <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Date de commande:';
		}else {
		echo 'Order Date:';
		}
		?></td>
                <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Date d\'expedition:';
		}else {
		echo 'Ship Date:';
		}
		?></td>
  <?php /*?>                 <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Num P.O:';
		}else {
		echo 'P.O:';
		}
		?></td>

             <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Prenom:';
		}else {
		echo 'First:';
		}
		?></td>
   <?php */?>               
   	<td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Nom:';
		}else {
		echo 'Name:';
		}
		?></td>
                     <td class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Ref. Pat.:';
		}else {
		echo 'Pat. Ref:';
		}
		?></td>         <td class="formCellNosides"><div align="right">OrderTotal</div></td>
                <td class="formCellNosides"><div align="center"><?php if ($mylang == 'lang_french'){
		echo 'Status Paiement:';
		}else {
		echo 'Pmt Status:';
		}
		?></div></td>
                <td class="formCellNosides"><div align="center">Pmt Type</div></td>
                <td class="formCellNosides" nowrap="nowrap"><div align="right"><?php if ($mylang == 'lang_french'){
		echo 'Total Paiement';
		}else {
		echo 'Pmt Total';
		}
		?></div></td>
                <td class="formCellNosides"><div align="center">Balance</div></td>
              </tr>
