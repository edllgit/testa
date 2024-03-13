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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Stock Lenses Search</title>

<script type="text/javascript">
	function test() { //v2.0
		alert("test");
	}
</script>

<script type="text/javascript">
	function copyRE() { //v2.0
	document.stock.MATERIAL2.selectedIndex=document.stock.MATERIAL.selectedIndex;
	document.stock.INDEX2.length=0;
	document.stock.INDEX2.options[document.stock.INDEX2.options.length]=new Option(<?php echo $lbl_selectindex_tray;?>,"");
			
	for(i=1;i<document.stock.INDEX.length;i++){
	document.stock.INDEX2.options[document.stock.INDEX2.options.length]=new Option(document.stock.INDEX.options[i].value,document.stock.INDEX.options[i].value);
	}
	
	document.stock.INDEX2.selectedIndex=document.stock.INDEX.selectedIndex;
	document.stock.COATING2.length=0;
	document.stock.COATING2.options[document.stock.COATING2.options.length]=new Option(<?php echo $lbl_selectcoating_tray;?>,"");
			
	for(i=1;i<document.stock.COATING.length;i++){
	
							if (document.stock.COATING.options[i].value=='UC')
								var optionText=<?php echo $lbl_uncoated_tray;?>;
								
							if (document.stock.COATING.options[i].value=='AR')
								var optionText=<?php echo $lbl_antireflect_tray;?>;
		
							if (document.stock.COATING.options[i].value=='SR')
								var optionText=<?php echo $lbl_scratchresist_tray;?>;
	
							if (document.stock.COATING.options[i].value=='SR AR')
								var optionText=<?php echo $lbl_scratchreflect_tray;?>;
								
	document.stock.COATING2.options[document.stock.COATING2.options.length]=new Option(optionText,document.stock.COATING.options[i].value);
	}
	
	document.stock.COATING2.selectedIndex=document.stock.COATING.selectedIndex;
	document.stock.SPHERE2.length=0;
	document.stock.SPHERE2.options[document.stock.SPHERE2.options.length]=new Option(<?php echo $lbl_select_tray;?>,"");
			
	for(i=1;i<document.stock.SPHERE.length;i++){
	document.stock.SPHERE2.options[document.stock.SPHERE2.options.length]=new Option(document.stock.SPHERE.options[i].value,document.stock.SPHERE.options[i].value);
	}
						
	document.stock.SPHERE2.selectedIndex=document.stock.SPHERE.selectedIndex;
	document.stock.CYLINDER2.length=0;
	document.stock.CYLINDER2.options[document.stock.CYLINDER2.options.length]=new Option("Select","");
			
	for(i=1;i<document.stock.CYLINDER.length;i++){
	document.stock.CYLINDER2.options[document.stock.CYLINDER2.options.length]=new Option(document.stock.CYLINDER.options[i].value,document.stock.CYLINDER.options[i].value);
	}
						
	document.stock.CYLINDER2.selectedIndex=document.stock.CYLINDER.selectedIndex;
}
</script>

<script type="text/javascript">

function MM_openBrWindow(theURL,winName,features) { //v2.0
 window.open(theURL,winName,features);
}

function validate(theForm)  {
  if (theForm.TRAY.value== "")
  {
    alert(<?php echo $lbl_alert_tray;?>);
    theForm.TRAY.focus();
    return (false);
  }
 }
	
</script>

<script src="formFunctions.js" type="text/javascript"></script>

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
    
    
    
   
    <td width="685" valign="top"><form action="stock.php" method="post" name="stock" id="stock" onSubmit="return validate(this)">
      <div class="header"><?php echo $lbl_titlemast_tray;?></div>
	  <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
      
       <?php 
	  $AfficherPageCommande = true;
	  $queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('stock_tray','stock','exclusive')";
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

              <tr >
                <td colspan="2" align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
                </tr>
              <tr >
                <td width="95" align="right" class="formCellNosides"><?php echo $lbl_trayref_txt;?></td>
                <td width="541" class="formCellNosides"><input name="TRAY" type="text" id="TRAY" value="<?php print $_POST[TRAY];?>" size="10"></td>
                </tr>
            </table>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr>
                <td colspan="2" align="center"  class="formCellNosides">&nbsp;</td>
                <td align="center"  class="formCellNosides"><?php echo $lbl_material_txt_tray;?></td>
                <td align="left" class="formCellNosides"><?php echo $lbl_index_txt_tray;?></td>
                <td align="left" class="formCellNosides"><?php echo $lbl_coating_txt_tray;?></td>
                <td align="left" class="formCellNosides"><?php echo $lbl_sphere_txt_tray;?></td>
                <td align="left" class="formCellNosides"><?php echo $lbl_cylinder_txt_tray;?></td>
              </tr>
              <tr>
                <td align="center"  class="formCellNosides"><a href="#" onclick="copyRE()"><img src="http://www.direct-lens.com/direct-lens/design_images/copy_arrow.gif" alt="Copy" width="17" height="17" border="0" title="Copy R.E. to L.E." /></a> <a href="#" onclick="test()"><img src="http://www.direct-lens.com/direct-lens/design_images/copy_arrow.gif" alt="test" width="17" height="17" border="0" title="test" /></a></td>
                <td align="right"  class="formCell"><?php echo $lbl_material_re_txt;?></td>
                <td  class="formCellNosides"><select name="MATERIAL" class="pullDownText" id="MATERIAL"  onChange="fetchIndex('getIndex.php','MATERIAL','INDEX','COATING','SPHERE','CYLINDER',this.value)">
                    <option value="" selected><?php echo $lbl_material_re1;?></option>
                   <option value="<?php echo $lbl_material_le1_abr;?>"><?php echo $lbl_material_re2;?></option>
                    <option value="<?php echo $lbl_material_le2_abr;?>"><?php echo $lbl_material_re3;?></option>
                    <option value="<?php echo $lbl_material_le3_abr;?>"><?php echo $lbl_material_re4;?></option>
                    <option value="<?php echo $lbl_material_le4_abr;?>"><?php echo $lbl_material_re5;?></option>
                    <option value="<?php echo $lbl_material_le5_abr;?>"><?php echo $lbl_material_re6;?></option>
                </select></td>
                <td  class="formCellNosides"><select name="INDEX" class="pullDownText" id="INDEX" onChange="fetchCoating('getCoating.php','INDEX','COATING','SPHERE','CYLINDER',MATERIAL.value,this.value)">
                  <option value="">-</option>
                </select></td>
                <td   class="formCellNosides">
                  <select name="COATING" class="pullDownText" id="COATING" onChange="fetchSphere('getSphere.php','SPHERE','COATING','CYLINDER',MATERIAL.value,INDEX.value,this.value);">
                    <option value="">-</option>
                  </select>               </td>
                <td   class="formCellNosides"><select name="SPHERE" class="pullDownText" id="SPHERE" onChange="	fetchCylinder('getCylinder.php','CYLINDER','SPHERE',MATERIAL.value,INDEX.value,COATING.value,this.value)">
                  <option>-</option>
                </select></td>
                <td  class="formCellNosides"><select name="CYLINDER" class="pullDownText" id="CYLINDER">
                  <option value="">-</option>
                </select></td>
              </tr>
              <tr>
                <td colspan="2" align="right"class="formCell"><?php echo $lbl_material_le_txt;?></td>
                <td  class="formCellNosides"><select name="MATERIAL2" class="pullDownText" id="MATERIAL2"  onChange="fetchIndex('getIndex.php','MATERIAL2','INDEX2','COATING2','SPHERE2','CYLINDER2',this.value)">
                    <option value="" selected><?php echo $lbl_material_re1;?></option>
                    <option value="<?php echo $lbl_material_le1_abr;?>"><?php echo $lbl_material_re2;?></option>
                    <option value="<?php echo $lbl_material_le2_abr;?>"><?php echo $lbl_material_re3;?></option>
                    <option value="<?php echo $lbl_material_le3_abr;?>"><?php echo $lbl_material_re4;?></option>
                    <option value="<?php echo $lbl_material_le4_abr;?>"><?php echo $lbl_material_re5;?></option>
                    <option value="<?php echo $lbl_material_le5_abr;?>"><?php echo $lbl_material_re6;?></option>
                </select></td>
                <td  class="formCellNosides"><select name="INDEX2" class="pullDownText" id="INDEX2" onChange="fetchCoating('getCoating.php','INDEX2','COATING2','SPHERE2','CYLINDER2',MATERIAL2.value,this.value)">
                    <option value="">-</option>
                </select></td>
                <td   class="formCellNosides"><select name="COATING2" class="pullDownText" id="COATING2" onChange="fetchSphere('getSphere.php','SPHERE2','COATING2','CYLINDER2',MATERIAL2.value,INDEX2.value,this.value);">
                    <option value="">-</option>
                  </select>                </td>
                <td   class="formCellNosides"><select name="SPHERE2" class="pullDownText" id="SPHERE2" onChange="	fetchCylinder('getCylinder.php','CYLINDER2','SPHERE2',MATERIAL2.value,INDEX2.value,COATING2.value,this.value)">
                    <option>-</option>
                </select></td>
                <td  class="formCellNosides"><select name="CYLINDER2" class="pullDownText" id="CYLINDER2">
                    <option value="">-</option>
                </select></td>
              </tr>
            </table>
		    <div align="center" style="margin:11px">&nbsp;
		      <input name="Submit" type="submit" class="formText" value="<?php echo $btn_submit_txt;?>" tabindex="1">
		      <input name="from_form" type="hidden" id="from_form" value="yes">
		    </div>
		  </form>		    <?php 
	if ($_POST[from_form]=="yes"){
		$_POST[from_form]="false";
		include("includes/stockSearch.inc.php");}
		
	if ($_POST[fromTrayAdd]=="true"){
		$COUNT=$_SESSION["COUNT"]+1;

		$_SESSION["COUNT"]=$COUNT;
		$_SESSION["TRAY_REF"][$COUNT]=$_SESSION["TEMP_TRAY_REF"];

		$_SESSION["RE"][$COUNT]=$_POST[RE_RADIO];
		$_SESSION["LE"][$COUNT]=$_POST[LE_RADIO];
		$RE=$_SESSION["RE"][$COUNT];
		$LE=$_SESSION["LE"][$COUNT];
		
		$_SESSION["ITEM_NUMBER"]=$_SESSION["ITEM_NUMBER"]+1;
		
		include("includes/displayTray.inc.php");}
		
	elseif($_SESSION["ITEM_NUMBER"]!=0){
		include("includes/displayTray.inc.php");
		}
	?>
    
    
    
    <?php }//end if $AfficherPageCommande       ?>     
    
    
    
    
    
    
    
    
    
    </td>
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