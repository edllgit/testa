<?php

//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
session_start();
require_once(__DIR__.'/../constants/aws.constant.php');
include "../connexion_hbc.inc.php";
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
	$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits.*, orders.order_total, orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num FROM     
	memo_credits,  orders , accounts, memo_codes
	WHERE mcred_memo_num = '" . $_REQUEST[memo_num] . "' 
	AND orders.order_num = memo_credits.mcred_order_num 
	AND memo_codes.mc_lab =  1
	AND accounts.user_id = orders.user_id
	AND memo_codes.memo_code = memo_credits.mcred_memo_code  ";
	
	//echo '<br><br>'. $query . '<br><br>';

	$nom_bd ='hbc';
	mysqli_select_db($con,$nom_bd);
	$orderResult=mysqli_query($con,$query)	or die  ('I cannot select items because 3 : ' . mysqli_error($con));	
	$Data=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
	//echo '<br><br>';
	//var_dump($Data);
	?>


<body style="font-family:Arial;" onLoad="window.print()">
<table width="650" border="0"cellpadding="3" cellspacing="0" align="center"><tr>


   <?php 
	$queryLogo ="SELECT  logo_file FROM labs WHERE primary_key = (SELECT distinct lab FROM orders WHERE order_num = " .  substr($_REQUEST[memo_num],1,5) . ")";
	//echo '<br>'. $queryLogo;
	$ResultLogo=mysqli_query($con,$queryLogo)	or die  ('I cannot select items because 1 : ' . mysqli_error($con));	
	$DataLogo=mysqli_fetch_array($ResultLogo,MYSQLI_ASSOC);
	//var_dump($DataLogo);
	$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
	$ResultUser=mysqli_query($con,$queryUser)	or die  ('I cannot select items because 2 : ' . mysqli_error($con));	
	$DataUser=mysqli_fetch_array($ResultUser,MYSQLI_ASSOC);
	$CustomerLanguage = $DataUser[language];
	
	$queryPrescriptLab  = "SELECT lab FROM orders WHERE order_num = " .  substr($_REQUEST[memo_num],1,5);
	$resultPrescriptLab = mysqli_query($con,$queryPrescriptLab)	or die  ('I cannot select items because 1 : ' . mysqli_error($con));
	$DataPrescriptLab   = mysqli_fetch_array($resultPrescriptLab,MYSQLI_ASSOC);
	$LeLab 				= $DataPrescriptLab[lab];

?>

<?php if (($LeLab==66) || ($LeLab==67)){?>	
	  		<td align="left"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/edll_2018.png" width="200" height="60" /></td>
<?php }else{  ?>		
	  		<td align="right"><img src="http://www.direct-lens.com/logos/direct-lens_logo.gif" width="200" height="60" /></td>	
<?php }//End IF ?>						  
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
    
    
    
    <tr><td align="right" class="formCellNosides">
    <?php  if ($CustomerLanguage == 'french'){ ?>
    Détail du crédit:
	<?php }else{ ?>	
    Credit Detail:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[mcred_detail]; ?>
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
