<?php
session_start();
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>LensNet Club</title>


<!--[if !IE]>-->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<script type="text/javascript">
      window.onload = function()  
      {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		     
      }
    </script>
<!--<![endif]-->
   
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
    
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
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      
      <div class="bigwelcome">
		<?php 
		 if ($mylang == 'lang_french') {
		    echo 'Prix & Promos';
			}else {
			echo 'Prices & Promos';
			}
		?>  
     
			
		</div><!--END rightcolumn-->
	
		<?php /*?>  
		  if ($mylang == 'lang_french') {
	echo '<br><div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/liste_prix_innovative_fr2013.pdf" target="_blank">Liste de prix Innovative</a></div>';
		
			}else if ($mylang == 'lang_english'){
					
	echo '<br><div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/liste_prix_innovative_en2013.pdf" target="_blank">Price List Innovative</a></div>';
			}else {
			echo '<br><div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/liste_prix_innovative_sp.pdf2013" target="_blank">Price List Innovative Spanish</a></div>';
			}
		
			?><br><br>
            
            
             <?php
		  
		  if ($mylang == 'lang_french') {
	echo '<div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/full_price_list_fr.pdf" target="_blank">Liste de prix</a></div>';
			}else{
	echo '<div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/full_price_list_en.pdf" target="_blank">Price List</a></div>';
			}
		
			?><br><br>
            
            
                 <?php
		  
		  if ($mylang == 'lang_french') {
	echo '<div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/major_brand_fr.pdf" target="_blank">Grandes marques</a></div>';
			}else{
	echo '<div><a style="text-decoration:none;" href="http://static.direct-lens.com/lensnet/pdf/major_brand_en.pdf" target="_blank">Major Brands</a></div>';
			}
		
			?><br><br><?php */?>


</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>