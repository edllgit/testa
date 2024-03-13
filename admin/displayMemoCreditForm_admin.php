<?php
session_start();

require_once(__DIR__.'/../constants/mysql.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../Connections/directlens.php";
$lab_pkey=$_SESSION["lab_pkey"];
$logo_file=$_SESSION["labAdminData"]["logo_file"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Place Order</title>
<link href="../dl.css" rel="stylesheet" type="text/css" />
<style type="text/css">
<!--
body {
	font-family:Arial;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}

-->
</style>

</head>

<?php 
$queryLab = "SELECT lab FROM `direct54_dirlens`.orders  WHERE order_num = " . substr($_REQUEST[memo_num],1,7);
$ResultLab = mysql_query($queryLab)	or die  ('I cannot select items because  : ' . mysql_error() .'<br>'.$queryLab );	
$DataLab   = mysql_fetch_array($ResultLab);
$MainLab   = $DataLab['lab']; 




	$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits_temp.*, orders.order_total, orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num from     memo_credits_temp,  orders , accounts, memo_codes
	WHERE mcred_memo_num = '" . $_REQUEST[memo_num] . "' 
	AND orders.order_num = memo_credits_temp.mcred_order_num 
	AND memo_codes.mc_lab =  $MainLab
	AND accounts.user_id = orders.user_id
	AND memo_codes.memo_code = memo_credits_temp.mcred_memo_code  ";
	
	//echo '<br><br>'. $query . '<br><br>';
	
	
	$nom_bd = constant('MYSQL_DB_DIRECT_LENS');
	mysql_select_db($nom_bd);
	$orderResult=mysql_query($query)	or die  ('I cannot select items because 3 : ' . mysql_error());	
	$Data=mysql_fetch_array($orderResult);
	
	
	
	
	//echo 'pts' .$Data[optipoints_to_substract];
	 ?>



<body style="font-family:Arial;" onLoad="window.print()">
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>


   <?php 
	$queryLogo ="Select  logo_file from labs where primary_key = (select distinct lab from orders where order_num = " . $Data[mcred_order_num] . ")";
	$ResultLogo=mysql_query($queryLogo)	or die  ('I cannot select items because 1 : ' . mysql_error());	
	$DataLogo=mysql_fetch_array($ResultLogo);
	//var_dump($DataLogo);
	$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
	$ResultUser=mysql_query($queryUser)	or die  ('I cannot select items because 2 : ' . mysql_error());	
	$DataUser=mysql_fetch_array($ResultUser);
	$CustomerLanguage = $DataUser[language];
	
?>
<td align="left"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/logos/<?php echo $DataLogo[logo_file]; ?>"/></td>

<td align="right"><img src="<?php echo constant('DIRECT_LENS_URL'); ?>/logos/direct-lens_logo.gif" width="200" height="60" /></td>
</tr></table>
	
     
     
     
  


	<table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
    <tr>
	<td><div class="header2">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Memo Credit pour votre commande #: 
	<?php }else{ ?>
	Memo Credit for your Order #:
    <?php } echo $Data[mcred_order_num]; ?></div>
    </td>
    </tr>
    </table>
	
	<table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">
     <?php  if ($CustomerLanguage == 'french'){ ?>
    DÉTAIL DU CRÉDIT
	<?php }else{ ?>
	 MEMO ORDER INFORMATION
	<?php } ?>
    </td>
    </tr>
	<tr>
    <td align="right" class="formCellNosides">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Date du memo crédit: 
	<?php }else{ ?>	
    Memo Order Date:
	<?php } ?>
   </td>
   <td width="520" class="formCellNosides"><strong><?php echo $Data[mcred_date]; ?></strong></td>
   </tr>
    
    
	<tr>
    <td align="right" class="formCellNosides">   
	<?php  if ($CustomerLanguage == 'french'){ ?>
    Numéro de commande: 
	<?php }else{ ?>
	Order Number:
	<?php } ?></td>
    <td width="520" class="formCellNosides"><strong><?php echo $Data[mcred_order_num]; ?></strong></td>
    </tr>
    
    
	<tr><td align="right" class="formCellNosides">
	<?php  if ($CustomerLanguage == 'french'){ ?>
    Total de la commande: 
	<?php }else{ ?>
	Order Total:
	<?php } ?></td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[order_total]; ?>
	</strong></td>
    </tr>
    
	<tr>
    <td align="right" class="formCellNosides">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Nom du client
	<?php }else{ ?>
	Customer Name:
	<?php } ?>
    </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $Data[company]; ?>
	</strong></td>
    </tr>
    
	<tr>
    <td align="right" class="formCellNosides">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Numéro de compte client: 
	<?php }else{ ?>	
    Customer Account:
	<?php } ?>
     </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $Data[account_num]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides" nowrap>
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Numéro de référence patient: 
	<?php }else{ ?>	
    Patient Reference Number:
	<?php } ?>
     </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $Data[patient_ref_num]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides" nowrap>
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Prénom patient: 
	<?php }else{ ?>	
    Patient First Name:
	<?php } ?>
     </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $Data[order_patient_first]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides" nowrap>
     <?php  if ($CustomerLanguage == 'french'){ ?>
    Nom de famille patient: 
	<?php }else{ ?>	
    Patient Last Name:
	<?php } ?>
     </td>
    <td width="520" class="formCellNosides"><strong>
	<?php echo $Data[order_patient_last]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides">
     <?php  if ($CustomerLanguage == 'french'){ ?>
     Numéro de memo crédit: 
	<?php }else{ ?>	
     Memo Order Number:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[mcred_memo_num]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides">
     <?php  if ($CustomerLanguage == 'french'){ ?>
     Valeur du memo crédit: 
	<?php }else{ ?>	
    Memo Order Value:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	-<?php echo $Data[mcred_abs_amount]; ?>$
	</strong></td>
    </tr>
    
	<tr><td align="right" class="formCellNosides">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Raison du crédit:
	<?php }else{ ?>	
    Reason Code:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[mcred_memo_code]; ?>
	 - 
	<?php echo $Data[mc_description]; ?>
	</strong></td>
    </tr>
    
    <tr>
    <?php if ( $Data[optipoints_to_substract] > 0)
	{ 
			echo '<tr><td><img width="200" src="../images//Logo_Opti-Points.png" /></td></tr>';
			
		
			
			if ($CustomerLanguage == 'french')
			{
			echo '<td colspan="2"><p style="font-family:Arial;">Cette commande a été créditée grâce à vos Opti-Points! Cette demande de crédit n\'est pas couverte selon les politiques de garanties limitées de LensNet Club.';
			echo "<br><br>Raison: $Data[optipoints_reason]". "<br>Nb de points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}else {
			echo '<td colspan="2"><p style="font-family:Arial;">Your credit request cannot be covered under the terms of the Limited Warranty as a manufacturer\'s defect. However, we have covered your request under your available Opti-Points.';
			echo "<br><br>Reason: $Data[optipoints_reason]". "<br>Number of points: $Data[optipoints_to_substract] Opti-Points</p></td>";
			}
    }  ?>
    </tr>
    </table>

</body>
</html>
