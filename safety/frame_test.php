<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include "config.inc.php";
global $drawme;

$prod_table="ifc_frames";

if ($mylang == 'lang_france') $prod_table="ifc_frames_french";

?> 
<?php require_once "../upload/phpuploader/include_phpuploader.php"; ?> 
<?php 
session_start();

if($_SESSION["sessionUser_Id"]=="")

	header("Location:loginfail.php");
	
	require('../Connections/sec_connect.inc.php');


if($_SESSION["account_type"]=="restricted")
	{
	header("Location:order_history.php");
	}
	
  $queryLab = "Select main_lab from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysql_query($queryLab)	or die ("Could not select items");
  $DataLab=mysql_fetch_array($resultLab);
  $LabNum=$DataLab[main_lab];
  
$where_clause=" WHERE active='1' ";

if ($_POST['gender']!="all"){
	$where_clause.=" AND gender='".$_POST[gender]."' ";
	
}
if ($_POST['type']!="all"){
	$where_clause.=" AND type='".$_POST[type]."' ";
	
}
if ($_POST['material']!="all"){
	$where_clause.=" AND material='".$_POST[material]."' ";
	
}
if ($_POST['color']!="all"){
	$where_clause.=" AND color='".$_POST[color]."' ";
	
}

if ($_POST['boxing']!="all"){
	$where_clause.=" AND boxing='".$_POST[boxing]."' ";
	
}
//IF model selected overwrite where clause
if ($_POST['model']!="none"){
	$where_clause=" WHERE model='".$_POST[model]."' ";
	
}
 
mysql_query("SET CHARACTER SET UTF8");
$query="SELECT * FROM $prod_table".$where_clause." ORDER BY model, color"; 
$result=mysql_query($query)
		or die ("Could not select products because ".mysql_error()." query=".$query);

$prodCount=mysql_num_rows($result);	

$_SESSION['lens_category']=$_POST['lens_category'];
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<link href="products.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php   
	if ($_SESSION['account_type']=='restricted')
	{
	include("includes/sideNavRestricted.inc.php");
	}else{
	include("includes/sideNav.inc.php");
	}
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="frame.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION">
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      
       
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td width="57%" bgcolor="#17A2D2" class="tableHead"> <?php print $prodCount;?> Models Found</td>
                   <td width="43%" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                 </tr>
                
                       </table>
                       
                       
                       
                       
                       <?php
		//display table of products

			if ($prodCount==0){
				print "<table width=\"700\" border=\"0\" cellpadding=\"0\" align=\"center\">";
				
				print "<tr><td align=\"center\" valign=\"middle\"><div class=\"home_features_header\">Sorry, no items found.</div></td></tr>";
				print "</table>";
			}else{
				print "<table width=\"700\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
				$count=0;
				while ($productData=mysql_fetch_array($result)){
														 
					if ($count==0)
						print "<tr valign=\"top\">";
					$count++;
					$productData[prod_tn]="prod_images/".$productData[image];
					$productData['model']=stripslashes($productData['model']);
					$productData['price_us']=stripslashes($productData['price_us']);
					print "<td width=\"33%\" align=\"center\">";

					print "<div class=\"item-box\">";
					print "<div class=\"product-box\"><a href=\"prescription.php?prod=$productData[ifc_frames_id]\"><img src=\"$productData[prod_tn]\" alt=\"$productData[model]\" border=\"0\" title=\"$productData[model]\" width=\"190\"></a></div>";
					
					print "<a href=\"#\" border=\"0\" class=\"rotatepopup\" id=\"$productData[ifc_frames_id]\"><img src=\"images/360view.gif\" width=\"91\" height=\"22\" hspace=\"5\" border=\"0\" align=\"right\" style=\"margin-top:12px\"/></a>";
					print "<div class=\"product-name\"><a href=\"prescription.php?prod=$productData[ifc_frames_id]\">$productData[model]</a></div>";
					
					print "<div class=\"priceText\">TYPE: $productData[type]</div>";
					print "<div class=\"priceText\">KIND: $productData[gender]</div>";
					print "<div class=\"priceText\">MATERIAL: $productData[material]</div>";
					print "<div class=\"priceText\">COLORS: $productData[color]</div>";
					print "<div class=\"priceText\">SZE: $productData[boxing]</div>";
					print "</div>";
	
					print "</td>";
					if (($count%3)==0){
						print "</tr>";
						$count=0;
					}
				}//END WHILE
				
			if ($count==1)
				print "<td width=\"33%\" align=\"center\">&nbsp;</td><td width=\"34%\" align=\"center\">&nbsp;</td></tr>";
			if ($count==2)
				print "<td width=\"34%\" align=\"center\">&nbsp;</td></tr>";

			print "</table>";
			
	
		}//END IF PROD COUNT
		
?>
                       
                       
                       
                       
                       </td></tr>
               </table>
             </div>
             
         
         
			

		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footerBox">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>