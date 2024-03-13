<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
//if($_SESSION["sessionUser_Id"]=="")
	//header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');
		
$query="SELECT distinct lens_type FROM dlab_stock_products"; /* select all openings */
$result=mysql_query($query)		or die($lbl_error1_txt . mysql_error());
$usercount=mysql_num_rows($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Inventory Stock Trois-Rivieres</title>
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
<link href="../dl.css" rel="stylesheet" type="text/css">

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
	//include("includes/sideNav.inc.php");
	?></div></td>
    <td width="685" valign="top"><form action="stock_bulk_form_tr.php" method="post" name="stock" id="stock" onSubmit="return validate(this)">
      <div class="header"><?php echo 'Inventory Stock Trois-Rivieres';?> </div>
      
      <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('stock_tray','stock','exclusive')";
	       // echo   $queryBasket;
	        $ResultBasket=mysql_query($queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
		    $DataBasket=mysql_fetch_array($ResultBasket);
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
      
      
  
      <?php if ($AfficherPageCommande){  ?>     
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysql_fetch_array($result)){echo "<option value=\"$listProducts[lens_type]\">";$name=stripslashes($listProducts[lens_type]);echo "$name</option>";}?>
		        </select>
		      &nbsp;
		      <input name="Submit" type="submit" class="formText" id="Submit" value="See this product stock in TR">
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
