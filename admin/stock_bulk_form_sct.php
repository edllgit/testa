<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
//if($_SESSION["sessionUser_Id"]=="")	header("Location:loginfail.php");
require('../Connections/sec_connect.inc.php');
$query="SELECT distinct lens_type FROM dlab_stock_products"; /* select all openings */

$result=mysql_query($query)		or die('Could not select items because 3: ' . mysql_error());
$usercount=mysql_num_rows($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Inventory Stock Saint-Catharines</title>
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
<link href="../dl.css" rel="stylesheet" type="text/css">

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
    <td width="215" valign="top"><div id="leftColumn">&nbsp;</div></td>
    <td width="685" valign="top"><form action="stock_bulk_form_sct.php" method="post" name="stock" id="stock">
      <div class="header"><?php echo 'Inventory Stock Saint-Catharines';?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td align="right" bgcolor="#000099" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="none" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysql_fetch_array($result)){echo "<option value=\"$listProducts[lens_type]\">";$name=stripslashes($listProducts[lens_type]);echo "$name</option>";}?>
		        </select>
		      &nbsp;
		      <input name="Submit" type="submit" class="formText" id="Submit" value="See this product stock in SCT">
		      &nbsp;
		      <input name="from_bulk_form" type="hidden" id="from_form" value="true">
		    </div></td>
                </tr></table>
    </form>
	<?php
	
if ($_POST[from_bulk_form]=="true"){

	$product_name=$_POST[product_name];
		
	$query="SELECT * from dlab_stock_products WHERE lens_type='$product_name'"; //TEAM LEADERS SECTIOn
		
	$result		 = mysql_query($query)  or die  ($lbl_error1_txt. mysql_error());
	$prodResult  = mysql_query($query)	or die ($lbl_error2_bulk);
	$productData = mysql_fetch_array($prodResult);
		
	switch ($productData[style]) {
		case "SV":    $style="Single Vision";    	  break;
		case "PR":    $style="Progressive";   		  break;
		case "SV AS": $style="Single Vision Aspheric";break;
	}
			 
}
	
	?>
	  <table width="650" border="0" align="center" cellpadding="6" cellspacing="0"  class="formBox">
        <tr>
          <td colspan="3" bgcolor="#000099" class="tableHead"><?php print $productData[lens_type];?></td>
        </tr>
      </table>
	  
	  	  	  <?php
//require('Connections/sec_connect.inc.php');

print "<div class=\"Subheader\">".$lbl_units_bulk.$_SESSION[sessionUserData][purchase_unit]."</div>";



if ($_POST[from_bulk_form]=="true"){

	$product_name=$_POST[product_name];

	$query="select cylinder from dlab_stock_products WHERE lens_type='$product_name' and cylinder <> '' group by cylinder"; /* select all openings */
	$result=mysql_query($query)		or die  ($lbl_error1_txt . mysql_error());

	$ColNum=mysql_num_rows($result);
		
	$query="select sphere,cylinder,stock_product_id  from dlab_stock_products WHERE lens_type='$product_name' order by sphere,cylinder desc"; /* select all openings */
	$result=mysql_query($query)		or die  ($lbl_error1_txt . mysql_error());

	$MaxCyl=-100;
	$MinCyl=100;

	while($listProduct=mysql_fetch_array($result)){
		$count++;
 		$CylValue[$count]=$listProduct[cylinder];
	
		if ($MaxCyl<$listProduct[cylinder]){
 			$MaxCyl=$listProduct[cylinder];}
	
		if ($MinCyl>$listProduct[cylinder]){
 			$MinCyl=$listProduct[cylinder];}
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

	$query="SELECT  cylinder, sphere, stock_product_id  from dlab_stock_products WHERE lens_type='$product_name' AND sphere>=0.00 group by sphere desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysql_query($query)		or die  ($lbl_error1_txt . mysql_error(). $query);
		
	$PosRowCount=mysql_num_rows($result);
	$RowCounter=0;
	
	if ($PosRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error3_bulk."</td></tr>";}

	while($listSphere=mysql_fetch_array($result)){
		$RowCounter++;
		print "<tr>";
	
		$text=money_format('%.2n', $listSphere[sphere]);
		
		if ($listSphere[sphere]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D7E1FF\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sphere];
		$query="select cylinder,stock_product_id  from dlab_stock_products WHERE lens_type='$product_name' AND sphere='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysql_query($query)			or die  ($lbl_error1_txt . mysql_error() . $query);
		
		$cylcount=mysql_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysql_fetch_array($result2)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cylinder];
			$RowKeyValues[$counter]=$listCyl[stock_product_id];
			
		}
		
		

		
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				
				$queryStockValue="SELECT * FROM dlab_stock_products,   dlab_stock_inventory WHERE  dlab_stock_inventory.stock_product_id = dlab_stock_products.stock_product_id AND dlab_stock_products.stock_product_id = $RowKeyValues[$i]";
			
			$resultStockValue=mysql_query($queryStockValue)		or die  ('I cannot select items because 2: ' . mysql_error());
			$DataStockValue=mysql_fetch_array($resultStockValue);
			$StockValue= $DataStockValue[qty_sct];
				
				
				$flag="true";
				$text="<input  type=\"text\" name=\"PosQuantity[$RowCounter][$col]\" value=\"$StockValue\"   alt=\"\" title=\"\"  size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"PosKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
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

	$query="select sphere,cylinder,stock_product_id  from dlab_stock_products WHERE lens_type='$product_name' AND sphere<0.00 group by sphere desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysql_query($query)		or die  ('I cannot select items because 1: ' . mysql_error());
	$RowCounter=0;
	$NegRowCount=mysql_num_rows($result);
	
	echo "<input name=\"NegRowCount\" type=\"hidden\" value=\"$NegRowCount\"/>
	<input name=\"continue_redirect\" type=\"hidden\" value=\"stock_bulk.php\"/>
	<input name=\"ColCount\" type=\"hidden\" value=\"$ColNum\" />
	<input name=\"fromBulkAdd\" type=\"hidden\" value=\"true\" />
	<input name=\"PosRowCount\" type=\"hidden\" value=\"$PosRowCount\" />";
	
	if ($NegRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error4_bulk."</td></tr>";}

	while($listSphere=mysql_fetch_array($result)){
		$RowCounter++;
		print "<tr>";
	
		$text=money_format('%.2n', $listSphere[sphere]);
		
		if ($listSphere[sphere]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D7E1FF\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sphere];
		$query="select cylinder, stock_product_id  from dlab_stock_products WHERE lens_type='$product_name' AND sphere='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysql_query($query)			or die  ($lbl_error1_txt . mysql_error());
		
		$cylcount=mysql_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysql_fetch_array($result2)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cylinder];
			$RowKeyValues[$counter]=$listCyl[stock_product_id];
		}
		
		
	
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				
			$queryStockValue="SELECT * FROM dlab_stock_products,   dlab_stock_inventory WHERE  dlab_stock_inventory.stock_product_id = dlab_stock_products.stock_product_id AND dlab_stock_products.stock_product_id = $RowKeyValues[$i]";
			
			$resultStockValue=mysql_query($queryStockValue)		or die  ('I cannot select items because 2: ' . mysql_error());
			$DataStockValue=mysql_fetch_array($resultStockValue);
			$StockValue= $DataStockValue[qty_sct];
				
				$flag="true";
				$text="<input type=\"text\"  name=\"NegQuantity[$RowCounter][$col]\" value=\"$StockValue\"    size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"NegKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
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

 print"</form>";
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