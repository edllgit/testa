<?php
session_start() ;
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?>
<?php 

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
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
        <div class="loginText">
          <div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
          <?php print $_SESSION["sessionUser_Id"];?></div>
        <div class="header">  <?php if ($mylang == 'lang_france') {
	  echo "Prix & Promos";
	  }else {
	  echo "Prices and Promos";
	  }
	    ?> </div>
        <?php
		  
		  if ($mylang == 'lang_france') {
	print '<div class="linksSubheader"><a href="pdf/liste_prix_innovative_fr.pdf" >Liste de prix Innovative</a></div>';
			}else if ($mylang == 'lang_english'){
	print '<div class="linksSubheader"><a href="pdf/liste_prix_innovative_en.pdf" >Price List Innovative</a></div>';
			}else {
			print '<div class="linksSubheader"><a href="pdf/liste_prix_innovative_sp.pdf">Price List Innovative Spanish</a></div>';
			}
		
			?>
            
            
             <?php
		  
		  if ($mylang == 'lang_france') {
	print '<div class="linksSubheader"><a href="pdf/full_price_list_fr.pdf" >Liste de prix</a></div>';
			}else{
	print '<div class="linksSubheader"><a href="pdf/full_price_list_en.pdf" >Price List</a></div>';
			}
		
			?>
            
            
                 <?php
		  
		  if ($mylang == 'lang_france') {
	print '<div class="linksSubheader"><a href="pdf/major_brand_fr.pdf" >Grandes marques</a></div>';
			}else{
	print '<div class="linksSubheader"><a href="pdf/major_brand_en.pdf" >Major Brands</a></div>';
			}
		
			?>

<?php
  if ($mylang == 'lang_france') {
	//print '<img src="/lensnet/images/Promotion_topic3_fr.jpg" width="650" />';
			}else{
	//print '<img src="/lensnet/images/Promotion_topic3_en.png" width="700"  />';
			}
?>

</div><!--END right column-->
</div><!--END maincontent-->
<div id="footerBox">
   <?php include("footer.inc.php"); ?>
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>