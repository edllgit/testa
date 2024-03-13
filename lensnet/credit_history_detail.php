<?php 
session_start();
require_once(__DIR__.'/../constants/mysql.constant.php');
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
$user_id=$_SESSION["sessionUser_Id"];

if ($_POST[mcred_order_num] <> ''){
$mcred_order_num = $_POST[mcred_order_num];
}else{
echo '<p>Error: no order number has been submitted. please go back and try again.</p>';	
exit();
}

session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>

<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>

<script type="text/javascript" src="../includes/formvalidator.js"></script>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      <div class="header">
		  	</div><div class="loginText"></div>
		    <br>
		    <br>
		    <div class="Subheader"></div>

		
</div><!--END rightcolumn-->

<form method="post" name="customer_credit_history" id="customer_credit_history" action="credit_history_detail.php">           


	<div align="center">	
	<?php if ($mylang == 'lang_french'){
		echo '<h3>Detail du credit '. $mcred_order_num. '</h3>';
		}else {
		echo '<h3>Credit '. $mcred_order_num. ' detail</h3>';
		}?></div>
                
<?php  
$queryDetail  = "SELECT * FROM memo_credits_status_history WHERE mcred_memo_num = '$mcred_order_num'";
$resultDetail = mysql_query($queryDetail)		or die ("Could not find lab list");

$queryActualStatus  = "SELECT mcred_approbation FROM memo_credits_temp WHERE mcred_memo_num = '$mcred_order_num'";
$resultActualStatus = mysql_query($queryActualStatus)		or die ("Could not find lab list");
$DataStatus 	    = mysql_fetch_array($resultActualStatus);  

$queryLab = "SELECT lab FROM `direct54_dirlens`.orders  WHERE order_num = " . substr($mcred_order_num,1,7);
$ResultLab = mysql_query($queryLab)	or die  ('I cannot select items because  : ' . mysql_error() .'<br>'.$queryLab );	
$DataLab   = mysql_fetch_array($ResultLab);
$MainLab   = $DataLab['lab']; 

$query ="SELECT memo_codes.mc_description, accounts.account_num, accounts.company, memo_credits.*, orders.order_total, orders.order_patient_first,orders.order_patient_last, orders.patient_ref_num FROM     
memo_credits,  orders , accounts, memo_codes
WHERE mcred_memo_num = '" . $mcred_order_num . "' 
AND orders.order_num = memo_credits.mcred_order_num 
AND memo_codes.mc_lab =  $MainLab
AND accounts.user_id = orders.user_id
AND memo_codes.memo_code = memo_credits.mcred_memo_code  ";	

$nom_bd = constant('MYSQL_DB_DIRECT_LENS');
mysql_select_db($nom_bd);
$orderResult=mysql_query($query)	or die  ('I cannot select items because 3 : ' . mysql_error());	
$Data=mysql_fetch_array($orderResult);

$queryLogo ="SELECT  logo_file FROM labs WHERE primary_key = (SELECT distinct lab FROM orders WHERE order_num = " .  substr($mcred_order_num,1,7) . ")";
$ResultLogo=mysql_query($queryLogo)	or die  ('I cannot select items because 1 : ' . mysql_error());	
$DataLogo=mysql_fetch_array($ResultLogo);
$queryUser = "SELECT distinct language from accounts WHERE user_id = '" . $Data[mcred_acct_user_id] . "'" ;
$ResultUser=mysql_query($queryUser)	or die  ('I cannot select items because 2 : ' . mysql_error());	
$DataUser=mysql_fetch_array($ResultUser);
$CustomerLanguage = $DataUser[language];
?>        
<div align="center">
<table width="65%" border="1" cellpadding="2" cellspacing="0" class="formField">              
   <tr bgcolor="#999999"><td  align="center" colspan="4">
   <h4><?php if ($mylang == 'lang_french'){
		echo 'Historique de cette demande de cr&eacute;dit';
		}else {
		echo 'Status history of the credit request';
		}?></h4></td></tr>
   
   <tr>   
       <th width="150px"><?php if ($mylang == 'lang_french'){
		echo 'Statut';
		}else {
		echo 'Status';
		}?></th>
       <th width="200px">
	   <?php if ($mylang == 'lang_french'){
		echo 'Mise &agrave; jour';
		}else {
		echo 'Update time';
		}?></th>
   </tr>
   
   <?php while ($DataDetail = mysql_fetch_array($resultDetail)){   ?>
            <tr>
                <td align="center"><?php if ($mylang == 'lang_french'){
		echo $DataDetail[request_status_fr];
		}else {
		echo $DataDetail[request_status];
		}?>&nbsp;</td>
                <td align="center"><?php echo $DataDetail[update_time]; ?>&nbsp;</td>
            </tr>
   <?php } //End while ?>       
</table> 
</div>
<br /><br>

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
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>