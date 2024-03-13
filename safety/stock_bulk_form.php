<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
?>
<?php
session_start();
if($_SESSION["sessionUser_Id"]=="")	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');

if ($_SESSION["sessionUserData"]["currency"]=="US"){
		$currency="price";}
else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
		$currency="price_can";}
else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
		$currency="price_eur";}
		
$query="select * from products,prices WHERE type='stock' AND products.product_name=prices.product_name AND prices.".$currency."!=0 group by products.product_name,mfg asc"; /* select all openings */

$result=mysql_query($query)		or die('Could not select items because: ' . mysql_error());
$usercount=mysql_num_rows($result);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>



   
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

-->
</style>

<script src="formFunctions.js" type="text/javascript"></script>
<style type="text/css">
<!--
.select1 {width:100px}
.style1 {color: #6571C8}
-->
</style>
</head>

<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<form action="stock_bulk_form.php" method="post" name="stock" id="stock">
      <div class="header"><?php echo $lbl_titlemast_bulk;?></div><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td align="right" bgcolor="#17A2D2" class="formCellNosides">&nbsp;</td>
              </tr>
              <tr >
                <td align="right" class="formCellNosides"><div align="center" style="margin:11px">
		      <select name="product_name" class="formText" id="product_name">
                <option value="none" selected><?php echo $lbl_slctprod_txt_bulk;?></option>
		        <?php while ($listProducts=mysql_fetch_array($result)){echo "<option value=\"$listProducts[product_name]\">";$name=stripslashes($listProducts[product_name]);echo "$name</option>";}?>
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
	
	$query="select products.product_name,products.mfg,products.abbe,products.description,style,products.density,prices.price,prices.price_can,prices.price_eur from prices,products WHERE products.product_name='$product_name' AND products.product_name=prices.product_name"; //TEAM LEADERS SECTIOn
	
$result=mysql_query($query)
		or die  ($lbl_error1_txt. mysql_error());
		
	$prodResult=mysql_query($query)
		or die ($lbl_error2_bulk);
	$productData=mysql_fetch_array($prodResult);
	
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
	
	  <table width="770" border="0" align="center" cellpadding="6" cellspacing="0"  class="formBox">
        <tr >
          <td colspan="3" bgcolor="#17A2D2" class="tableHead"><?php print $productData[product_name];?></td>
          <td width="60" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
          <td width="115" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_subtitleprice_bulk;?> <?php if ($_SESSION["sessionUserData"]["currency"]=="US"){
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

if ($_POST[from_bulk_form]=="true"){

	$product_name=$_POST[product_name];

	$query="select cyl_add from products WHERE product_name='$product_name' group by cyl_add"; /* select all openings */
	$result=mysql_query($query)
		or die  ($lbl_error1_txt . mysql_error());

	$ColNum=mysql_num_rows($result);
		
	$query="select sph_base,cyl_add,primary_key from products WHERE product_name='$product_name' order by sph_base,cyl_add desc"; /* select all openings */
	$result=mysql_query($query)
		or die  ($lbl_error1_txt . mysql_error());

	$MaxCyl=-100;
	$MinCyl=100;

	while($listProduct=mysql_fetch_array($result)){
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
	
	print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\"  class=\"formBox\">";
     print "<tr bgcolor=\"#D5EEF7\"><td  class=\"formStockBulk\" align=\"center\"><b>".$lbl_plusspherecyl_txt."</b></td>";
	 
	 
	 for ($i=1;$i<=$ColNum;$i++){//CYLINDER LABELS
	 	$text=money_format('%.2n',$CylValues[$i]);
	 
	 	if ($CylValues[$i]>0){
		$text="+".$text;}
		else if ($CylValues[$i]==0){
		$text="-".$text;}
		
		print "<td  class=\"formStockBulk\" align=\"center\"><b>$text</b></td>";
		}//CYLINDER LABELS
	
	print "</tr>";

	$query="select sph_base,cyl_add,primary_key from products WHERE product_name='$product_name' AND sph_base>='0.00' group by sph_base desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysql_query($query)
		or die  ($lbl_error1_txt . mysql_error());
		
	$PosRowCount=mysql_num_rows($result);
	$RowCounter=0;
	
	if ($PosRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error3_bulk."</td></tr>";}

	while($listSphere=mysql_fetch_array($result)){
		$RowCounter++;
		print "<tr >";
	
		$text=money_format('%.2n', $listSphere[sph_base]);
		
		if ($listSphere[sph_base]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D5EEF7\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sph_base];
		$query="select cyl_add,primary_key from products WHERE product_name='$product_name' AND sph_base='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysql_query($query)
			or die  ($lbl_error1_txt . mysql_error());
		
		$cylcount=mysql_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysql_fetch_array($result2)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cyl_add];
			$RowKeyValues[$counter]=$listCyl[primary_key];
		}
	
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				$flag="true";
				$text="<input type=\"text\" name=\"PosQuantity[$RowCounter][$col]\"  size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"PosKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
			else {
				if ($flag=="false")
				$text="&nbsp;";}
			}
		print "$text</td>";
		}//COL LOOP
	
		print "</tr>";
	
		}//ROWS WHILE

	print "</table>";
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"1\" cellspacing=\"0\"  class=\"formBox\">";
     print "<tr bgcolor=\"#D5EEF7\"><td  class=\"formStockBulk\" align=\"center\"><b>-Sphere/Cyl</b></td>";
	 
	 
	 for ($i=1;$i<=$ColNum;$i++){//CYLINDER LABELS
	 	$text=money_format('%.2n',$CylValues[$i]);
	 
	 	if ($CylValues[$i]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"><b>$text</b></td>";
		}//CYLINDER LABELS
	
	print "</tr>";

	$query="select sph_base,cyl_add,primary_key from products WHERE product_name='$product_name' AND sph_base<'0.00' group by sph_base desc"; //SELECT SPHERE AND GROUP THEM
	$result=mysql_query($query)
		or die  ('I cannot select items because: ' . mysql_error());
	$RowCounter=0;
	$NegRowCount=mysql_num_rows($result);
	
	if ($NegRowCount==0){
	$columns=$ColNum+1;
	print "<tr><td  colspan=\"$columns\" class=\"formStockBulk\" align=\"center\">".$lbl_error4_bulk."</td></tr>";}

	while($listSphere=mysql_fetch_array($result)){
		$RowCounter++;
		print "<tr >";
	
		$text=money_format('%.2n', $listSphere[sph_base]);
		
		if ($listSphere[sph_base]>=0)
		$text="+".$text;
		
		print "<td  class=\"formStockBulk\" align=\"center\"  bgcolor=\"#D5EEF7\"><b>$text</b></td>";
		
		$Sphere=$listSphere[sph_base];
		$query="select cyl_add,primary_key from products WHERE product_name='$product_name' AND sph_base='$Sphere'"; //SELECT CYL based on Sphere 
		$result2=mysql_query($query)
			or die  ($lbl_error1_txt . mysql_error());
		
		$cylcount=mysql_num_rows($result2);
		$counter=0;
		
		for ($i=0;$i<100;$i++){//ZERO out previous values
			$RowCylValues[$i]="";
			$RowKeyValues[$i]="";
			}
	
		while($listCyl=mysql_fetch_array($result2)){
			$counter++;
			$RowCylValues[$counter]=$listCyl[cyl_add];
			$RowKeyValues[$counter]=$listCyl[primary_key];
		}
	
		for ($col=1;$col<=$ColNum;$col++){
			print "<td  class=\"formStockBulk\" align=\"center\">";
			
			$flag="false";
		
			for ($i=1;$i<=$cylcount;$i++){
			
			if ($RowCylValues[$i]==$CylValues[$col]){
				$flag="true";
				$text="<input type=\"text\" name=\"NegQuantity[$RowCounter][$col]\"  size=\"3\" class=\"formText\"/><input type=\"hidden\" name=\"NegKey[$RowCounter][$col]\" value=\"$RowKeyValues[$i]\"/>";}
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

 print"<div align=\"center\" style=\"margin:11px\"><input name=\"Submit\" type=\"submit\" class=\"formText\" value=\"".$lbl_basket_bulk."\" /><input name=\"fromBulkAdd\" type=\"hidden\" value=\"true\" /><input name=\"PosRowCount\" type=\"hidden\" value=\"$PosRowCount\" /><input name=\"NegRowCount\" type=\"hidden\" value=\"$NegRowCount\"/><input name=\"continue_redirect\" type=\"hidden\" value=\"stock_bulk.php\"/><input name=\"ColCount\" type=\"hidden\" value=\"$ColNum\" /></div></form>";
?></td>
  </tr>
</table>
	</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
   <?php include("footer.inc.php"); ?>
</div>
</div><!--END containter-->
</body>
</html>
