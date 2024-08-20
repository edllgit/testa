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
include("inc/pw_functions.inc.php");

global $drawme;
?> 
<?php //require_once "upload/phpuploader/include_phpuploader.php"; ?> 
<?php 

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
  $queryLab = "SELECT main_lab, product_line FROM accounts WHERE user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
  $DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
  $LabNum=$DataLab[main_lab];	
  $Product_Line = $DataLab[product_line];	

if ($Product_Line == 'eye-recommend'){//Compte prestige, on affiche le message par pop-up(Demande de Brian)	
echo '<div  align="center" class="alert alert-danger">
    <strong>Please note: Starting January 1st, any discounts will now be reflected on your statement and not on invoice.</strong>
  </div>';

}//End if Prestige
	
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens - Prescription Search</title>
<!-- Bootstrap core CSS -->
<link href="../../bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- Custom styles for this template -->
<link href="css/signin.css" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->

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


<?php //} ?>
<link href="dl.css" rel="stylesheet" type="text/css" />

<?php include "includes/js/prescription_form.js.inc.php";?>

</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center">
      <table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
   <td width="215" valign="top"><div id="leftColumn"><?php 
	include("includes/sideNav.inc.php");
	?></div></td>
    <td width="685" valign="top">
<form action="prescription.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION">
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
       
             
           
             
             
             <div>
               <table width="650" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td bgcolor="#000099" class="tableHead"> <?php if ($mylang == 'lang_french'){
				echo '&nbsp;&nbsp;&nbsp;FILTRE DE VERRES:';
				}else {
				echo '&nbsp;&nbsp;&nbsp;CHOOSE A LENS FILTER';
				}
				?></td>
                   </tr>

                    <tr><td align="center"><div align="center"><input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/></div></td></tr>
                    
                     <tr>
                   <td align="center" class="formCellNosides">
   
   
       
<?php if (($mylang == 'lang_french') && ($Product_Line <> 'eye-recommend')){ ?>
             
  <div style="font-size:14px; margin-left:60px; padding:3px;"><b>CATÉGORIE DE VERRES</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>TYPES DE VERRE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- b>FABRICANT</b --!></div> 
  
<div style="font-size:14px; margin-left:60px; margin-top:15px; padding:3px"> <input name="lens_category" type="radio" id="radio" value="all" checked="checked" />Tous&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Precision+ S" />Precision+ S&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="ESSILOR" ESSILOR/--!></div>
    

<div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="bifocal" />Bifocal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Precision+360" />Precision+360&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- input type="radio" name="lens_category" id="radio" value="SOLA" SOLA/ --!></div>
 
<!-- div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="glass" />Verre&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- input type="radio" name="lens_category" id="radio" value="Econo Choice" />Econo Choice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="SHAMIR" SHAMIR/ ></div --!>
     
<div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="all prog" />Tous Progressifs&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Maxiwide" />Maxiwide&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="MY WORLD" MY WORLD/ --!></div>
  
    
<!-- div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="prog cl" />Progressif Classique&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Econo Choice Ultra One" />Econo Choice Ultra One&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="RODENSTOCK" />RODENSTOCK</div --!>

<!-- div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="prog ds" />Progressif DS
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="ELPS HD" />ELPS HD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="PRECISION" />PRECISION</div --!>
 

<!--div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="prog ff" />Progressif FF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="iAction" />iAction&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="OPTOTECH" />OPTOTECH</div --!>
<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision Active" />Precision Active</div--!>  
<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Infocus Single Vision" />Infocus Single Vision</div --!>
<div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Simple Vision" />Simple Vision&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="iAction SV" />iAction SV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="SEIKO" SEIKO/ --!></div>

<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iAction" />iAction</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Alpha" />Alpha</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Alpha HD" />Alpha HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iOffice" />iOffice</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iReader" />iReader</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iRelax" />iRelax</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iRoom" />iRoom</div> 

<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Lifestyle" />Office Premium</div> 

<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Simple Vision" />Simple Vision</div>  
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Simple Vision Stock" />Simple Vision Stock</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="FT28" />FT28</div>
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Seiko" />Seiko</div>





<!-- div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iFree" />iFree</div--!> 

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Infocus Flat Top" />Infocus Flat Top</div>
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Infocus RX Direct Progressive" />Infocus RX Direct Progressive</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Infocus Single Vision" />Infocus Single Vision</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Innovative II DS" />Innovative II DS</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Life II" />Life II</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Life XS" />Life XS</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Mini Pro HD" />Mini Pro HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Acuform" />Universal (Formerly Optimize Acuform)</div--!> 

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="FIT" />Optimize Fit</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Horizon" />Optimize Horizon+</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="DMT" />Innovative (Formerly DMT)</div --!> 


<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Anti-Fatigue" />Eye Fatigue</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Ovation" />Ovation</div>  
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="PSI HD" />PSI HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision Active" />Precision Active</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision Daily" />Precision Daily</div --!>

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Pro EZ HD"/>Pro EZ HD</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Purelife HD" />Purelife HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="SelectionRx" />SelectionRx</div>   
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="ST-25" />ST-25</div>  
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="ST-28" />ST-28</div--!> 
  
<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Truehd" />TrueHD</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Vision Classique HD" />Vision Classique HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Vision Pro HD" />Vision Pro HD</div --!> 

<?php 
      }elseif (($mylang <> 'lang_french')&& ($Product_Line <> 'eye-recommend')) {
?>
<div style="font-size:14px; margin-left:60px; padding:3px;"><b>LENS CATEGORY</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>LENS TYPE</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- b>MANUFACTURER</b --!></div> 
  
<div style="font-size:14px; margin-left:60px; margin-top:15px; padding:3px"> <input name="lens_category" type="radio" id="radio" value="all" checked="checked" />All&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Precision+ S" />Precision+ S&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="ESSILOR" ESSILOR/ --!></div>
    
<div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="bifocal" />Bifocal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Precison+360" />Precision+360&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="SOLA" SOLA/ --!></div>
 
<!--div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="glass" />Glass&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Econo Choice" />Econo Choice&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="SHAMIR" SHAMIR/ ></div --!>
     
<div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="all prog"/>All Progressives&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="maxiwide" />Maxiwide&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="MY WORLD" MY WORLD/ --!></div>
  
    
<!-- div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="prog cl" />Classic Progressives &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="Econo Choice Ultra One" />Econo Choice Ultra One&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="RODENSTOCK" />RODENSTOCK</div --!>

<!--div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="prog ds" />Progressive DS
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="ELPS HD" />ELPS HD&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="PRECISION" />PRECISION</div --!>
 
<!--div style="font-size:14px; margin-left:60px; padding:3px"><input type="radio" name="lens_category" id="radio" value="prog ff" / >Progressif FF&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="iAction" />iAction&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!--input type="radio" name="lens_category" id="radio" value="OPTOTECH" />OPTOTECH</div --!>

 <!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Infocus Single Vision" />Infocus Single Vision</div --!>
<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision Active" />Precision Active</div --!> 

<div style="font-size:14px; margin-left:60px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Single Vision" />Single Vision&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="iAction SV" />iAction SV&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<!-- input type="radio" name="lens_category" id="radio" value="SEIKO" SEIKO/ --!></div>


<!-- div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Identity by Optotech" />Identity by Optotech&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="lens_category" id="radio" value="OPTIMIZE" />OPTIMIZE</div --!> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iAction" />iAction</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Alpha" />Alpha</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Alpha HD" />Alpha HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iOffice" />iOffice</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iReader" />iReader</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iRelax" />iRelax</div> 
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="iRoom" />iRoom</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Lifestyle" />Office Premium</div> 

<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Single Vision" />Single Vision</div>  
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Single Vision Stock" />Single Vision Stock</div>  
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="FT28" />FT28</div>
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Seiko" />Seiko</div>





<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Infocus Flat Top" />Infocus Flat Top</div>
<div style="font-size:14px; margin-left:265px; padding:3px;"> <input type="radio" name="lens_category" id="radio" value="Infocus RX Direct Progressive" />Infocus RX Direct Progressive</div--!>

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Innovative II DS" />Innovative II DS</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Life II" />Life II</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Life XS" />Life XS</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Mini Pro HD" />Mini Pro HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Acuform" />Universal (Formerly Optimize Acuform)</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="IPL" />Alpha (Formerly Optimize IPL)</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="FIT" />Optimize Fit</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Horizon" />Optimize Horizon+</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="DMT" />Innovative (Formerly DMT)</div--!> 

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Anti-Fatigue" />Eye Fatigue</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Ovation" />Ovation</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="PSI HD" />PSI HD</div --!> 

<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision Daily" />Precision Daily</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Precision SV HD" />Precision SV HD</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Pro EZ HD"/>Pro EZ HD</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Purelife HD" />Purelife HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="SelectionRx" />SelectionRx</div>   
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="ST-25" />ST-25</div>  
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="ST-28" />ST-28</div--!> 
 
<!--div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Truehd" />TrueHD</div>
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Vision Classique HD" />Vision Classique HD</div> 
<div style="font-size:14px; margin-left:265px; padding:3px"> <input type="radio" name="lens_category" id="radio" value="Vision Pro HD" />Vision Pro HD</div --!>   

				<?php
                }elseif ($Product_Line == 'eye-recommend') {
				//SECTION EYE RECOMMEND
?>

<table width="600px">
<tr align="center">
    <td width="170"  style="font-size:14px; margin-left:60px; padding:3px;"><b>Lens Category</b></td>
    <td width="200"  style="font-size:14px; margin-left:60px; padding:3px;"><b>Lens Type</b></td>
    <td width="130"  style="font-size:14px; margin-left:75px; padding:3px;"><b>Software Design</b></td>
</tr>


<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input name="lens_category" type="radio" id="radio" value="all" checked="checked" />All</div></td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input name="lens_category" type="radio" id="radio" value="iAction" />iAction</div></td>
    <!--td width="150" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input name="lens_category" type="radio" id="radio" value="IOT" />IOT</div></td --!>
</tr>


<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="bifocal" />Bifocal</div></td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iAction SV" />iAction SV</div></td>
   <!--td width="150 "style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="OPTOTECH" />OPTOTECH</div></td--!>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="all prog" />All Progressives</div></td>
    <!--td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iFree" />iFree</div></td--!>
    <!--td width="150" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="SHAMIR" />SHAMIR</div></td --!>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="prog ds" />Progressive DS</div></td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iOffice" />iOffice</div></td>   
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iReader" />iReader</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>


<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="prog ff" />Progressive FF</div></td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iRelax" />iRelax</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Single Vision" />Single Vision</div></td>
    <!--td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Acuform" />Universal (Formerly Optimize Acuform)</div></td --!>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="stock" />Stock</div></td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="IPL" />Alpha (Formerly Optimize IPL)</div></td >
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<!--tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="DMT" />Innovative (Formerly DMT)</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>
  
<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="210" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Lifestyle" />Office Premium</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Anti-Fatigue" />Eye Fatigue</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="HD EZ" />HD EZ</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="revolution" />Revolution</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="revolution sv" />Revolution SV</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr --!>

<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Single Vision" />Single Vision</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

<!-- tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="camber" />Ultimate Freestyle (Camber)</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr --!>
	
	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="FT28" /> FT28</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Precision+ S" /> Precision+ S</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Precision+360" /> Precision+360</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="maxiwide" /> MaxiWide</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="iRoom" /> iRoom</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

	<tr align="center">
    <td width="170" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
    <td width="200" style="font-size:14px; margin-left:60px; padding:3px;"><div style="margin-left:60px;" align="left"><input type="radio" name="lens_category" id="radio" value="Seiko" /> Seiko</div></td>
    <td width="150" style="font-size:14px; margin-left:60px; padding:3px;">&nbsp;</td>
</tr>

   </table>   
<?php }//End IF Eye Recommend  ?>


                  
                    </td>
          </table>
             </div>
	
<div align="center" style="margin:11px">
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
		  </form></td>
  </tr>
</table> </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>

<?php
if ($Product_Line == 'eye-recommend'){//Compte prestige, on affiche le message par pop-up(Demande de Brian)	
echo '<div  align="center" class="alert alert-danger">
    <strong>Please note: Starting January 1st, any discounts will now be reflected on your statement and not on invoice.</strong>
  </div>';

}//End if Prestige

?>	
		<script type='text/javascript'>
	//this is to show the header..
	//ShowAttachmentsTable();
	</script>
    

</body>
</html>