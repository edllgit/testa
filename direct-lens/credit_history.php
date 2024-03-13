<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

unset($_SESSION["order_numbers"]);
unset($_SESSION["orderCount"]);

$user_id=$_SESSION["sessionUser_Id"];

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
		echo '<h3>MES CREDITS</h3>';
		}else {
		echo '<h3>MY CREDIT HISTORY</h3>';
		}?>
<br>
<?php 
$query     = "SELECT distinct mcred_order_num, mcred_memo_num,   mcred_acct_user_id  from memo_credits_temp WHERE  mcred_acct_user_id = '$user_id'    order by mcred_order_num";
$result    = mysqli_query($con,$query)		or die ("Could not find lab list");
$NbrResult = mysqli_num_rows($result);
?>

<?php if ($mylang == 'lang_french'){
		echo '<p>S&eacute;lectionner une commande dans le menu ci-dessous<br><br><b>(Noter que les cr&eacute;dits trait&eacute;s avant le 2013-10-01 n\'apparaitrons pas dans cet outil).</p>';
		}else {
		echo '<p>Please select an order from the menu below <br><br><b>(Note that credits older than 2013-10-01 might now appear in this menu).</p>';
		}?>

<?php if ($NbrResult >0) {?> 
<select name="mcred_order_num" >
<option value="" selected="selected">
<?php if ($mylang == 'lang_french'){
		echo 'S&eacute;lectionner un credit';
		}else {
		echo 'Select a credit';
		}?></option>
   <?php
   while ($DataCredit=mysqli_fetch_array($result,MYSQLI_ASSOC)){
      echo "<option value=\"$DataCredit[mcred_memo_num]\">#$DataCredit[mcred_order_num] : $DataCredit[mcred_memo_num]</option>";
   }?>
</select>

<input type="submit" name="view_detail" value="<?php if ($mylang == 'lang_french'){
		echo 'Voir le d&eacute;tail';
		}else {
		echo 'View Credit Detail';
		}?>" id="view_detail">
<?php }elseif ($mylang == 'lang_french'){
		echo '<p><u>Il n\'y aucun cr&eacute;dit dans votre compte pr&eacute;sentement.</u></p>';
		}else {
		echo '<p><u>There are currently no credit in your account</u></p>';
		}
 ?>
</div>
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