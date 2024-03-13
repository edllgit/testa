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


if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$currency="price";}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$currency="price_can";}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$currency="price_eur";}
		
$query="SELECT * FROM products,prices WHERE type='stock' AND products.product_name NOT LIKE '%Somo%' AND prices.dropdown_order <> 0  AND products.product_name=prices.product_name AND products.product_name <> 'DLAB CR-39 Transitions Brown AR' AND prices.".$currency."!=0 group by products.product_name,mfg asc  order by prices.dropdown_order"; /* select all openings */

$result=mysqli_query($con,$query)		or die('Could not select items because: ' . mysqli_error($con));
$usercount=mysqli_num_rows($result);
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
.style1 {color: #6571C8}
-->
</style>
<link href="dl.css" rel="stylesheet" type="text/css">

<script src="formFunctions.js" type="text/javascript"></script>

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
    <td width="685" valign="top"><form action="stock_bulk_form.php" method="post" name="stock" id="stock">
      <div class="header"><?php echo $lbl_titlemast_bulk;?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="none" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysqli_fetch_array($result,MYSQLI_ASSOC)){echo "<option value=\"$listProducts[product_name]\">";$name=stripslashes($listProducts[product_name]);echo "$name</option>";}?>
		        </select>
		      &nbsp;
		      <input name="Submit" type="submit" class="formText" id="Submit" value="<?php echo $btn_go_txt;?>">
		      &nbsp;
		      <input name="from_bulk_form" type="hidden" id="from_form" value="true">
		    </div></td>
                </tr></table>
    </form>
	<?php
	
	if ($_POST[from_bulk_form]=="true"){

$product_name=$_POST[product_name];
	
	$query="SELECT products.product_name,products.mfg,products.abbe,products.description,style,products.density,prices.price,prices.price_can,prices.price_eur FROM prices,products WHERE products.product_name='$product_name' AND products.product_name=prices.product_name"; //TEAM LEADERS SECTIOn
	
$result=mysqli_query($con,$query) or die  ($lbl_error1_txt. mysqli_error($con));
		
	$prodResult=mysqli_query($con,$query) or die ($lbl_error2_bulk);
	$productData=mysqli_fetch_array($prodResult,MYSQLI_ASSOC);
	
			switch ($productData[style]) {
		case "SV":
    		$style="Single Vision";
    		break;
		case "PR":
   			$style="Progressive";
   			 break;
		case "SV AS":
   			$style="Single Vision Aspheric";
    		break;
			 }
			 
	}
	
	?>
	
	  <table width="650" border="0" align="center" cellpadding="6" cellspacing="0"  class="formBox">
        <tr >
          <td colspan="3" bgcolor="#000099" class="tableHead"><?php print $productData[product_name];?></td>
          <td width="60" bgcolor="#000099" class="tableHead">&nbsp;</td>
          <td width="115" bgcolor="#000099" class="tableHead"><?php echo $lbl_subtitleprice_bulk;?> <?php if ($_SESSION["sessionUserData"]["currency"]=="US"){
				print $productData[price]." US";}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				print $productData[price_can]." CA";}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				print $productData[price_eur]." EUR";}?></td>
        </tr>
        <tr >
          <td width="130" align="right" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA"><?php echo $lbl_descript_bulk;?></td>
          <td colspan="4" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php print $productData[description];?></td>
          </tr>
        <tr >
          <td align="right" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA"><?php echo $lbl_manufacturer_bulk;?></td>
          <td width="166" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php print $productData[mfg];?></td>
          <td width="117" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA"><?php echo $lbl_abbevalue_bulk;?></td>
          <td colspan="2" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php print $productData[abbe];?></td>
        </tr>
        <tr >
          <td align="right" valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA"><?php echo $lbl_type_bulk;?></td>
          <td valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php print $style;?></td>
          <td valign="top" bgcolor="#FFFFFF" class="formCellNosidesRA"><?php echo $lbl_specgrav_bulk;?></td>
          <td colspan="2" valign="top" bgcolor="#FFFFFF" class="formCellNosides"><?php print $productData[density];?>g/cm3</td>
        </tr>

      </table>
	  
	  	  	  <?php
//require('Connections/sec_connect.inc.php');

print "<div class=\"Subheader\">".$lbl_units_bulk.$_SESSION[sessionUserData][purchase_unit]."</div>";

if ($productData[product_name] == 'Somo 1.60 AR'){
	echo "<br><div align=\"center\" style=\"width:700px;\"><img  width=\"400\" src=\"http://www.direct-lens.com/images/NONDISPO.jpg\"></div>";
}
?>


<?php



if ($_POST[from_bulk_form]=="true"){

	$product_name=$_POST[product_name];

	$query="SELECT cyl_add FROM products WHERE product_name='$product_name' group by cyl_add"; /* select all openings */
	$result=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con));

	$ColNum=mysqli_num_rows($result);
		
	$query="SELECT sph_base,cyl_add,primary_key FROM products WHERE product_name='$product_name' order by sph_base,cyl_add desc"; /* select all openings */
	$result=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con));

	$MaxCyl=-100;
	$MinCyl=100;

	while($listProduct=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$count++;
 		$CylValue[$count]=$listProduct[cyl_add];
	
		if ($MaxCyl<$listProduct[cyl_add]){
 			$MaxCyl=$listProduct[cyl_add];}
	
		if ($MinCyl>$listProduct[cyl_add]){
 			$MinCyl=$listProduct[cyl_add];}
	}//END WHILE

	$CylTAmount=$MaxCyl;
	$CylValues[1]=$CylTAmount;

	for ($col=2;$col<=$ColNum;$col++){
		$CylTAmount=$CylTAmount-.25;
		$CylValues[$col]=$CylTAmount;
		}

	print"<form id=\"stockBulkOrder\" name=\"stockBulkOrder\" method=\"post\" action=\"basket.php\">";
	
	print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\"  class=\"formBox\">";
     print "<tr bgcolor=\"#D7E1FF\"><td  class=\"formStockBulk\" align=\"center\"><b>".$lbl_plusspherecyl_txt."</b></td>";
	 
	 
	 for ($i=1;$i<=$ColNum;$i++){//CYLINDER LABELS
	 	$text=money_format('%.2n',$CylValues[$i]);
	 
	 	if ($CylValues[$i]>0){
		$text="+".$text;}
		else if ($CylValues[$i]==0){
		$text="-".$text;}
		
		print "<td  class=\"formStockBulk\" align=\"center\"><b>$text</b></td>";
		}//CYLINDER LABELS
	
	print "</tr>";

	$query="select diameter,sph_base,cyl_add,primary_key from products WHERE product_name='$product_name' AND sph_base>='0.00' group by sph_base desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con));
		
	$PosRowCount=mysqli_num_rows($result);
	$RowCounter=0;
	
	if ($PosRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error3_bulk."</td></tr>";}

	while($listSphere=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$RowCounter++;
		print "<tr >";
	
		$text=money_format('%.2n', $listSphere[sph_base]);
		
		if ($listSphere[sph_base]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D7E1FF\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sph_base];
		$query="SELECT diameter,cyl_add,primary_key FROM products WHERE product_name='$product_name' AND sph_base='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con));
		
		$cylcount=mysqli_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cyl_add];
			$RowKeyValues[$counter]=$listCyl[primary_key];
			$diameter = $listCyl[diameter];
		}
		
		
		

		
		switch($diameter){
			//case "60": 	  $tintCode = "style=\"background-color:#FF0;\"";  	    break;
			case "65": 	 	  $tintCode = "style=\"background-color:#FF0;\"";  	    break;
			case "68":   	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "69":        $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "70":    	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "72":    	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "73": 	  	  $tintCode = "style=\"background-color:#CCC\""; 		break;
			case "74": 	      $tintCode = "style=\"background-color:#CCC\""; 		break;
			case "75": 	      $tintCode = "style=\"background-color:#CCC\""; 		break;
			case "76": 	      $tintCode = "style=\"background-color:#CCC\""; 		break;
			//case "80": 	  $tintCode = "style=\"background-color:#F60\""; 		break;
			//case "85": 	  $tintCode = "style=\"background-color:#F30\""; 		break;
		}
		

		
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				$flag="true";
				$text="<input type=\"text\" name=\"PosQuantity[$RowCounter][$col]\" $tintCode  alt=\"$diameter\" title=\"$diameter\"  size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"PosKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
			else {
				if ($flag=="false")
				$text="&nbsp;";}
			}
		print "$text</td>";
		}//COL LOOP
	
		print "</tr>";
	
		}//ROWS WHILE

	print "</table>";

	print "<table width=\"650\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\"  class=\"formBox\">";
     print "<tr bgcolor=\"#D7E1FF\"><td  class=\"formStockBulk\" align=\"center\"><b>-Sphere/Cyl</b></td>";
	 
	 
	 for ($i=1;$i<=$ColNum;$i++){//CYLINDER LABELS
	 	$text=money_format('%.2n',$CylValues[$i]);
	 
	 	if ($CylValues[$i]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"><b>$text</b></td>";
		}//CYLINDER LABELS
	
	print "</tr>";

	$query="SELECT sph_base,cyl_add,primary_key FROM products WHERE product_name='$product_name' AND sph_base<'0.00' group by sph_base desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
	$RowCounter=0;
	$NegRowCount=mysqli_num_rows($result);
	
	echo "<input name=\"NegRowCount\" type=\"hidden\" value=\"$NegRowCount\"/>
	<input name=\"continue_redirect\" type=\"hidden\" value=\"stock_bulk.php\"/>
	<input name=\"ColCount\" type=\"hidden\" value=\"$ColNum\" />
	<input name=\"fromBulkAdd\" type=\"hidden\" value=\"true\" />
	<input name=\"PosRowCount\" type=\"hidden\" value=\"$PosRowCount\" />";
	
	if ($NegRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error4_bulk."</td></tr>";}

	while($listSphere=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		$RowCounter++;
		print "<tr >";
	
		$text=money_format('%.2n', $listSphere[sph_base]);
		
		if ($listSphere[sph_base]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D7E1FF\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sph_base];
		$query="SELECT diameter,cyl_add,primary_key FROM products WHERE product_name='$product_name' AND sph_base='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysqli_query($con,$query) or die  ($lbl_error1_txt . mysqli_error($con));
		
		$cylcount=mysqli_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cyl_add];
			$RowKeyValues[$counter]=$listCyl[primary_key];
			$diameter = $listCyl[diameter];
		}
		
		switch($diameter){
			case "65": 	 	  $tintCode = "style=\"background-color:#FF0;\"";  	    break;
			case "68":   	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "70":    	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "72":    	  $tintCode = "style=\"background-color:#0FF\"";   		break;
			case "75": 	      $tintCode = "style=\"background-color:#CCC\""; 		break;
		}
		
		
		
	
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				$flag="true";
				$text="<input type=\"text\" $tintCode name=\"NegQuantity[$RowCounter][$col]\" alt=\"$diameter\" title=\"$diameter\"  size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"NegKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
			else {
				if ($flag=="false")
				$text="&nbsp;";}
			}
		print "$text</td>";
		}//COL LOOP
	
		print "</tr>";
	
		}//ROWS WHILE

	print "</table>";


}//END OF from_form conditional

 print"<div align=\"center\" style=\"margin:11px\"><input name=\"Submit\" type=\"submit\" class=\"formText\" value=\"".$lbl_basket_bulk."\" /></div>
 </form>";

?></td>
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
