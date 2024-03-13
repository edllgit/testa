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

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");


//Empêcher Roy eyewear d'accéder a cette page
if($_SESSION["sessionUser_Id"]=="jackdirect")
	header("Location:lens_cat_selection.php");

if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$currency="price";}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$currency="price_can";}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$currency="price_eur";}
		
$query="SELECT * FROM  products,prices WHERE type='stock' AND products.product_name not like '%tokai%'
AND d_index <> '1.560' AND prices.dropdown_order <> 0  AND products.product_name NOT LIKE '%Somo Poly 1.59%' AND products.product_name NOT LIKE '%Somo%' AND products.product_name <> 'Somo 1.74 ASP UTC AR'
AND products.product_name <> 'DLAB CR-39 Transitions Brown AR' AND products.product_name=prices.product_name AND prices.".$currency."!=0 group by products.product_name,mfg asc order by prices.dropdown_order"; /* select all openings */
$result=mysqli_query($con,$query)		or die($lbl_error1_txt . mysqli_error($con));
$usercount=mysqli_num_rows($result);

$queryValidateProductLine  = "SELECT product_line from accounts WHERE user_id = '".$_SESSION["sessionUser_Id"]. "'";
$resultValidateProductLine = mysqli_query($con,$queryValidateProductLine)		or die(mysqli_error($con));
$DataValidateProductLine   = mysqli_fetch_array($resultValidateProductLine,MYSQLI_ASSOC);
$ProductLine = $DataValidateProductLine[product_line];

if (($ProductLine <> 'directlens') && ($ProductLine <> 'eye-recommend')){
//Redirection vers l'Index de direct-lens	
header("Location:index.php");
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Direct-Lens &mdash; Stock Lenses Bulk</title>
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
<script language="JavaScript" type="text/javascript">

function validate(theForm)
{
 if (theForm.product_name.value=="")
  {
    alert(<?php echo $lbl_alert1_bulk;?>);
    theForm.product_name.focus();
    return (false);
  }
}
</script>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn"><?php 
	include("includes/sideNav.inc.php");
	?></div></td>
    <td width="685" valign="top"><form action="stock_bulk_form.php" method="post" name="stock" id="stock" onSubmit="return validate(this)">
      <div class="header"><?php echo $lbl_titlemast_bulk;?> </div>
      
      <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('stock_tray','stock','exclusive') AND order_num = -1";
	       // echo   $queryBasket;
	        $ResultBasket=mysqli_query($con,$queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
		    $DataBasket=mysqli_fetch_array($ResultBasket,MYSQLI_ASSOC);
			//echo 'Element dans le basket autre que frames stock: '.  $DataBasket[nbrResult];
			
			if ($DataBasket[nbrResult] > 0){
				$AfficherPageCommande = false;
					if ($mylang == 'lang_french') { 
						echo '<p align="center">Pour pouvoir commander des verres de stock, veuillez d\'abord terminer les commandes qui sont actuellement dans votre panier d\'achat.</p>';
					}else{ 
						echo '<p align="center">To order some stock lenses, please process the orders that are already in your basket.</p>';
					} 		
			}
	    ?> 
      
      
  
      <?php if ($AfficherPageCommande){  

	  ?> 
      
     
      
      
       
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr>
                <td align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr>
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listProducts[product_name]\">";$name=stripslashes($listProducts[product_name]);echo "$name</option>";}?>
		        </select>
		      &nbsp;
		      <input name="Submit" type="submit" class="formText" id="Submit" value="<?php echo $btn_go_txt;?>">
		      &nbsp;
		      <input name="from_bulk_form" type="hidden" id="from_bulk_form" value="true">
		    </div></td>
                </tr></table></form></td>
                
        <?php }//end if $AfficherPageCommande       ?>         
                
                
                
  </tr>
</table>
		 </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>
</body>
</html>



