<?php 
session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");
	
	
require('../Connections/sec_connect.inc.php');

$primary_key=$_GET[pkey];
	
	$query="select * from exclusive WHERE primary_key='$primary_key'"; //TEAM LEADERS SECTIOn
	$result=mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
	$prodResult=mysql_query($query)		or die ("Could not find product");
	$productData=mysql_fetch_array($prodResult);
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Lens Specifications</title>
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
-->
</style>
<link href="ln.css" rel="stylesheet" type="text/css" />

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="350" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="http://www.direct-lens.com/direct-lens/design_images/top_piece_ls.jpg" width="350" height="30" /></td>
      </tr>
      <tr>
        <td background="http://www.direct-lens.com/direct-lens/design_images/mid_sect_bg_ls.jpg"><table width="300" border="0" align="center" cellpadding="6" cellspacing="0"  class="formBox">
          <tr >
            <td colspan="2" bgcolor="#000099" class="tableHead"><?php echo $productData[product_name];?></td>
          </tr>
          <tr >
            <td width="110" align="right" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Description:</td>
            <td width="164" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[description];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">ABBE Value: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[abbe];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Specific
              Gravity: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[density];?>g/cm3</td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Sphere
              Range: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_min];?> &ndash; +<?php echo $productData[sphere_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Sphere
              Over Range: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_over_min];?> &ndash; +<?php echo $productData[sphere_over_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Cylinder
              Range: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[cyl_min];?> &ndash; +<?php echo $productData[cyl_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Cylinder
              Over Range: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[sphere_over_min];?> &ndash; +<?php echo $productData[cyl_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Addition
              Range: </td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php echo $productData[add_min];?> &ndash; +<?php echo $productData[add_max];?></td>
          </tr>
          <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Price:</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides"><?php 	if ($_SESSION["sessionUserData"]["currency"]=="US"){
				echo $productData[price]." US";}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				echo $productData[price_can]." CA";}
				else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				echo $productData[price_eur]." EUR";}?></td>
          </tr>
          
          
          <?php if ($productData[logo_file]== 11)  { //Accuform ?>
		   <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Documents:</td>
          </tr>
          <?php } ?>
          
            <?php if ($productData[logo_file]== 12)  {//IPL ?>
		   <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Documents:</td>
          </tr><?php }	   ?>
          
          
         <?php
		 $reqDocum = "SELECT * FROM products_info_exclusive where exclusive_primary_key = " . $_REQUEST[pkey];
		
		$resultDocum=mysql_query($reqDocum)	or die  ('I cannot select items because: ' . mysql_error());
		$DataDocum=mysql_fetch_array($resultDocum);
		$nbrResultat = mysql_num_rows($resultDocum);
		
		if ($nbrResultat > 0 )
		   {
		   ?>  
             
          
           <tr >
            <td align="right" valign="middle" bgcolor="#FFFFFF" class="formCellNosidesRA style1 style2">Related documents:</td>
            <td valign="middle" bgcolor="#FFFFFF" class="formCellNosides">
			
			
          </tr>
          
          <?php  } ?>
           
        </table></td>
      </tr>
      <tr>
        <td><img src="http://www.direct-lens.com/direct-lens/design_images/bot_piece_ls.jpg" width="350" height="30" /></td>
      </tr>
    </table></td>
  </tr>
</table>



</body>
</html>