<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";

include"config.inc.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	
	
require('../Connections/sec_connect.inc.php');
require('includes/dl_order_functions.inc.php');
 	
$primary_key=$_GET[pkey];
	
$query="select * from ifc_ca_exclusive WHERE primary_key='$primary_key'"; //TEAM LEADERS SECTIOn
$result=mysql_query($query)	or die ("Sélection d'article impossible parce que : " . mysql_error());
		
$prodResult=mysql_query($query)	or die ("Produit non trouvé");
$productData=mysql_fetch_array($prodResult);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.style1 {color: #6571C8}
.style2 {font-size: 11px}
#container #maincontent #rightColumn table tr td table tr td .formBox tr .tableHead {
	color: #000;
}
-->
</style>

</head>

<body>
<div id="container">
  	<div id="masthead">
  		<img src="http://www.direct-lens.com/safety/design_images/ifc-masthead.jpg" width="1050" height="175" alt="IFCCLUB"/>
	</div>
	<div id="maincontent">
		<div id="leftColumn">
			<div id="leftnav">
  				<?php include("includes/sideNav.inc.php"); ?>
			</div><!--END leftnav-->
		</div><!--END leftcolumn-->
        <div id="rightColumn">
            <div class="loginText">
                <div class="loginText">
                    <?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href='logout.php'>DÉCONNEXION</a>
                    <?=$_SESSION["sessionUser_Id"];?>
                </div>
            </div>
            <div class="header">
            	<?php 	if ($mylang == 'lang_french') {  ?>
            	Spécification des verres
                <?php  	}else{ ?>
                Lenses Specs
                <?php 	} ?>  
            
            </div>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="350" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td background="images/mid_sect_bg_ls.jpg"><table width="300" border="0" align="center" cellpadding="6" cellspacing="0"  class="formBox">
          <tr >
            <td colspan="2" bgcolor="#CCCCCC" class="tableHead"><?php echo $productData[product_name];?></td>
          </tr>
          <tr >
            <td width="110" align="right" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Description :</td>
            <td width="164" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[description];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
            <?php 	if ($mylang == 'lang_french') {  ?>
            	Valeur ABBE                   
             <?php  	}else{ ?>
           ABBE Value
			<?php 	} ?>  
                     : </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[abbe];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2"> <?php 	if ($mylang == 'lang_french') {  ?>
            	Densité                 
             <?php  	}else{ ?>
             Density
			<?php 	} ?>  </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[density];?>g/cm3</td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            	Sphère minimum                 
             <?php  	}else{ ?>
             Min. Sphere
			<?php 	} ?> 
            : </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_min];?> &ndash; +<?php echo $productData[sphere_max];?></td>
          </tr>       
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            	Sphère globale                
             <?php  	}else{ ?>
             Global Sphere
			<?php 	} ?> 
             :</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_over_min];?> &ndash; +<?php echo $productData[sphere_over_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            	Cylindre minimum                   
             <?php  	}else{ ?>
             Min. Cylinder
			<?php 	} ?> 
             :</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[cyl_min];?> &ndash; +<?php echo $productData[cyl_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
              <?php 	if ($mylang == 'lang_french') {  ?>
            	 Cylindre global                 
             <?php  	}else{ ?>
            Global Cylinder
			<?php 	} ?> 
            :</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_over_min];?> &ndash; +<?php echo $productData[cyl_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            Addition                   
             <?php  	}else{ ?>
             Adition
			<?php 	} ?> 
             : </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[add_min];?> &ndash; +<?php echo $productData[add_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            	Prix Free               
             <?php  	}else{ ?>
             Price Free
			<?php 	} ?> 
          :</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides">
			<?php 		echo $productData[price_eur_free]." CA";		?></td>
          </tr>
          
           <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">
             <?php 	if ($mylang == 'lang_french') {  ?>
            	Prix Free Plus                   
             <?php  	}else{ ?>
           Price Free Plus
			<?php 	} ?>
            :</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides">
			<?php 		echo $productData[price_eur_free_plus]." CA";		?></td>
          </tr>
           	 
          
         <?php
		 $reqDocum = "SELECT * FROM products_info_exclusive where exclusive_primary_key = " . $_REQUEST[pkey];
		
		$resultDocum=mysql_query($reqDocum)	or die  ('I cannot select items because: ' . mysql_error());
		$DataDocum=mysql_fetch_array($resultDocum);
		$nbrResultat = mysql_num_rows($resultDocum);
		//echo $nbrResultat; ?>
		
           
        </table></td>
      </tr>
    </table></td>
  </tr>
</table>

 
        </div>
	</div>
    <div id="footerBox">
      
    </div>
</div>



</body>
</html>
<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>
