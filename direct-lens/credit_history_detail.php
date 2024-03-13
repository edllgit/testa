<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//D�marrer la session
session_start();

//Inclusions
require_once(__DIR__.'/../constants/mysql.constant.php');
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);
$user_id=$_SESSION["sessionUser_Id"];

if ($_POST[mcred_order_num] <> ''){
$mcred_order_num = $_POST[mcred_order_num];
}else{
echo '<p>Error: no order number has been submitted. please go back and try again.</p>';	
exit();
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Direct-Lens &mdash; Credit History</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}

-->
</style>
<link href="dl.css" rel="stylesheet" type="text/css">

<script src="formFunctions.js" type="text/javascript"></script>

<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}

</script>
</head>
<body>
<form method="post" name="customer_credit_history" id="customer_credit_history" action="credit_history_detail.php">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn">
	<?php 	include("includes/sideNav.inc.php"); ?></div></td>
    <td width="685" valign="top">
	<br><br><br>


<div align="center">	
	<?php if ($mylang == 'lang_french'){
		echo '<h3>Detail du credit '. $mcred_order_num. '</h3>';
		}else {
		echo '<h3>Credit '. $mcred_order_num. ' detail</h3>';
		}?></div>
        
        
        
<?php  
$queryDetail  = "SELECT * FROM memo_credits_status_history WHERE mcred_memo_num = '$mcred_order_num'";
$resultDetail = mysqli_query($con,$queryDetail)		or die ("Could not find lab list");

$queryActualStatus  = "SELECT mcred_approbation FROM memo_credits_temp WHERE mcred_memo_num = '$mcred_order_num'";
$resultActualStatus = mysqli_query($con,$queryActualStatus)		or die ("Could not find lab list");
$DataStatus 	    = mysqli_fetch_array($resultActualStatus,MYSQLI_ASSOC);  

$queryLab = "SELECT lab FROM `direct54_dirlens`.orders  WHERE order_num = " . substr($mcred_order_num,1,7);
$ResultLab = mysqli_query($con,$queryLab)	or die  ('I cannot select items because  : ' . mysqli_error($con) .'<br>'.$queryLab );	
$DataLab   = mysqli_fetch_array($ResultLab,MYSQLI_ASSOC);
$MainLab   = $DataLab['lab']; 

$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits.*, orders.order_total, orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num FROM     
memo_credits,  orders , accounts, memo_codes
WHERE mcred_memo_num = '" . $mcred_order_num . "' 
AND orders.order_num = memo_credits.mcred_order_num 
AND memo_codes.mc_lab =  $MainLab
AND accounts.user_id = orders.user_id
AND memo_codes.memo_code = memo_credits.mcred_memo_code  ";	

$nom_bd = constant('MYSQL_DB_DIRECT_LENS');
mysqli_select_db($con,$nom_bd);
$orderResult=mysqli_query($con,$query)	or die  ('I cannot select items because 3 : ' . mysqli_error($con));	
$Data=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);

$queryLogo ="SELECT  logo_file FROM labs WHERE primary_key = (SELECT distinct lab FROM orders WHERE order_num = " .  substr($mcred_order_num,1,7) . ")";
$ResultLogo=mysqli_query($con,$queryLogo)	or die  ('I cannot select items because 1 : ' . mysqli_error($con));	
$DataLogo=mysqli_fetch_array($ResultLogo,MYSQLI_ASSOC);
$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
$ResultUser=mysqli_query($con,$queryUser)	or die  ('I cannot select items because 2 : ' . mysqli_error($con));	
$DataUser=mysqli_fetch_array($ResultUser,MYSQLI_ASSOC);
$CustomerLanguage = $DataUser[language];

?>




        
<div align="center">
<table width="100%" border="1" cellpadding="2" cellspacing="0" class="formField">              
   <tr bgcolor="#999999"><td  align="center" colspan="4">
   <h4><?php if ($mylang == 'lang_french'){
		echo 'Historique de cette demande de cr&eacute;dit';
		}else {
		echo 'Status history of the credit request';
		}?></h4></td></tr>
   
   <tr>   
       <th width="450px"> <?php if ($mylang == 'lang_french'){
		echo 'Statut';
		}else {
		echo 'Status';
		}?></th>
       <th width="240px">
	   <?php if ($mylang == 'lang_french'){
		echo 'Mise &agrave; jour';
		}else {
		echo 'Update time';
		}?></th>
   </tr>
   
   <?php while ($DataDetail = mysqli_fetch_array($resultDetail,MYSQLI_ASSOC)){   ?>
            <tr>
               
                <td align="center">
                  <?php if ($mylang == 'lang_french'){
		echo $DataDetail[request_status_fr];
		}else {
		echo $DataDetail[request_status];
		}?>
        &nbsp;</td>
        
        <td align="center"><?php echo $DataDetail[update_time]; ?>&nbsp;</td>
            </tr>
   <?php } //End while ?>       
</table> 
</div>

<br><br>










<?php if ($DataStatus[mcred_approbation] =='approved') {?>
<div align="center">
<table width="400" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox"><tr ><td colspan="2" bgcolor="#000099" class="tableHead">
     <?php  if ($mylang == 'lang_french'){ ?>
    D&Eacute;TAIL DU CR&Eacute;DIT
	<?php }else{ ?>
	 MEMO ORDER INFORMATION
	<?php } ?>
    </td>
    </tr>
	<tr>
    <td align="right" class="formCellNosides">
    <?php  if ($mylang == 'lang_french'){ ?>
    Date du memo cr&eacute;dit: 
	<?php }else{ ?>	
    Memo Order Date:
	<?php } ?>
   </td>
   <td width="520" class="formCellNosides"><strong><?php echo $Data[mcred_date]; ?></strong></td>
   </tr>
    
    
	<tr>
    <td align="right" class="formCellNosides">   
	<?php  if ($mylang == 'lang_french'){ ?>
    Num&eacute;ro de commande: 
	<?php }else{ ?>
	Order Number:
	<?php } ?></td>
    <td width="520" class="formCellNosides"><strong><?php echo $Data[mcred_order_num]; ?></strong></td>
    </tr>
    
    
	<tr><td align="right" class="formCellNosides">
	<?php  if ($mylang == 'lang_french'){ ?>
    Total de la commande: 
	<?php }else{ ?>
	Order Total:
	<?php } ?></td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[order_total]; ?>
	</strong></td>
    </tr>
    
	<tr>
    <td align="right" class="formCellNosides">
    <?php  if ($mylang == 'lang_french'){ ?>
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
    <?php  if ($mylang == 'lang_french'){ ?>
    Num&eacute;ro de compte client: 
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
    <?php  if ($mylang == 'lang_french'){ ?>
    Num&eacute;ro de r&eacute;f&eacute;rence patient: 
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
    <?php  if ($mylang == 'lang_french'){ ?>
    Pr&eacute;nom patient: 
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
     <?php  if ($mylang == 'lang_french'){ ?>
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
     <?php  if ($mylang == 'lang_french'){ ?>
     Num&eacute;ro de memo cr&eacute;dit: 
	<?php }else{ ?>	
     Memo Order Number:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	<?php echo $Data[mcred_memo_num]; ?>
	</strong></td>
    </tr>
	
    <tr>
    <td align="right" class="formCellNosides">
     <?php  if ($mylang == 'lang_french'){ ?>
     Valeur du memo cr&eacute;dit: 
	<?php }else{ ?>	
    Memo Order Value:
	<?php } ?>
   </td><td width="520" class="formCellNosides"><strong>
	-<?php echo $Data[mcred_abs_amount]; ?>$
	</strong></td>
    </tr>
    
	<tr><td align="right" class="formCellNosides">
    <?php  if ($mylang == 'lang_french'){ ?>
    Raison du cr&eacute;dit:
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
			
		
			
			if ($mylang == 'lang_french')
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

</div>
<?php } ?>

</form>		
</td>
   
   <td width="215" valign="top"><br><br><br><br>
  </tr>
</table>


		 </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><br></td>
      </tr>
    </table>
	</td>
  </tr>
</table>

</body>
</html>