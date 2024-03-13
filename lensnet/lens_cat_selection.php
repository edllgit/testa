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
include("includes/pw_functions.inc.php");

global $drawme;
//require_once "../upload/phpuploader/include_phpuploader.php";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	

if($_SESSION["account_type"]=="restricted")
	{
	header("Location:order_history.php");
	}
	
  $queryLab = "SELECT main_lab FROM accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
  $DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
  $LabNum=$DataLab[main_lab];	
	
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

<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

</head>


<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php   
	include("includes/sideNav.inc.php");
	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><form action="prescription.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION">
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      
<?php /*?><div align="left"><a href="http://www.direct-lens.com/lensnet/images/sizzle.png"><img src="http://www.direct-lens.com/lensnet/images/sizzle.png" alt="Summer si$$le" width="1000" /></a></div> <?php */?>
       
       
       
             <div>
               <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td bgcolor="#1F3A71" class="tableHead"> <?php if ($mylang == 'lang_french'){
				echo '&nbsp;&nbsp;&nbsp;SÉLECTIONNER UN FILTRE DANS LE MENU DÉROULANT:';
				}else {
				echo '&nbsp;&nbsp;&nbsp;CHOOSE A LENS FILTER FROM THE DROPDOWN MENU';
				}
				?></td>
                   </tr>
                   
                 
                   
                   <tr><td><div align="center" style="margin:11px">
		    			   		<input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		   					</div>
                   </td></tr>
                   
                   
                     <tr>
                   <td align="center" class="formCellNosides">
                   
<?php if ($mylang == 'lang_french'){		?>
		<div  align="center"><select name="lens_category" id="lens_category" size="32">
                 <option   disabled="disabled" value="">*CATÉGORIE DE VERRES*</option>
                 <option   value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>Tous</option> 
                 <option   value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                 <option   value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                 <option   value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>Tous Progressifs</option>
                 <option   value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressif Classique</option>
                 <option   value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressif DS</option>
                 <option   value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressif FF</option>
                 <option   value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                 <option  disabled="disabled" value="">&nbsp;</option>
                 <option   disabled="disabled" value="">*TYPE DE VERRES*</option>
                 <option   value="AO Compact" <?php if ($_POST['lens_category']=="AO Compact") echo 'selected="selected"'; ?>>AO COMPACT</option> 
                 <option   value="Compact Ultra HD" <?php if ($_POST['lens_category']=="Compact Ultra HD") echo 'selected="selected"'; ?>>COMPACT ULTRA HD</option> 
				 <option   value="EZ" <?php if ($_POST['lens_category']=="EZ") echo 'selected="selected"'; ?>>EZ BY CZV</option> 
				 <option   value="FT28" <?php if ($_POST['lens_category']=="FT28") echo 'selected="selected"'; ?>>FT28</option> 
                 <option   value="FT35" <?php if ($_POST['lens_category']=="FT35") echo 'selected="selected"'; ?>>FT35</option> 
                 <option   value="FT45" <?php if ($_POST['lens_category']=="FT45") echo 'selected="selected"'; ?>>FT45</option> 
                 <option   value="Innovative 1.0" <?php if ($_POST['lens_category']=="Innovative 1.0") echo 'selected="selected"'; ?>>INNOVATIVE 1.0</option> 
                 <option   value="Innovative 2.0" <?php if ($_POST['lens_category']=="Innovative 2.0") echo 'selected="selected"'; ?>>INNOVATIVE 2.0</option> 
                 <option   value="Innovative 3.0" <?php if ($_POST['lens_category']=="Innovative 3.0") echo 'selected="selected"'; ?>>INNOVATIVE 3.0</option> 
                 <option   value="RD" <?php if ($_POST['lens_category']=="RD") echo 'selected="selected"'; ?>>RD</option> 
                 <option   value="SelectionRx" <?php if ($_POST['lens_category']=="SelectionRx") echo 'selected="selected"'; ?>>SELECTIONRX</option> 
  				 <option   value="Single Vision" <?php if ($_POST['lens_category']=="Single Vision") echo 'selected="selected"'; ?>>SINGLE VISION</option> 
				 <option   value="Sola Easy" <?php if ($_POST['lens_category']=="Sola Easy") echo 'selected="selected"'; ?>>SOLA EASY</option>  
                 <option   value="Trifocal 7x28" <?php if ($_POST['lens_category']=="Trifocal 7x28") echo 'selected="selected"'; ?>>Trifocal 7x28</option>
   				 <option   value="Trifocal 8x35" <?php if ($_POST['lens_category']=="Trifocal 8x35") echo 'selected="selected"'; ?>>Trifocal 8x35</option> 
                 <option   disabled="disabled" value="">&nbsp;</option>
				 <option   disabled="disabled" value="">*FABRICANTS*</option>
                 <option   value="IOT" <?php if ($_POST['lens_category']=="IOT") echo 'selected="selected"'; ?>>IOT</option> 
                 <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option>  
                 <option   value="SOLA/ZEISS" <?php if ($_POST['lens_category']=="SOLA/ZEISS") echo 'selected="selected"'; ?>>SOLA/ZEISS</option>   
        </select></div>
              <?php 
				}else {
				?>
			<div align="center"><select name="lens_category" id="lens_category" size="32" >
                  <option  disabled="disabled" value="">*LENS CATEGORY*</option>
                  <option  value="all" <?php if ($_POST['lens_category']=="all") echo 'selected="selected"'; ?>>All</option>
                  <option  value="bifocal" <?php if ($_POST['lens_category']=="bifocal") echo 'selected="selected"'; ?>>Bi-focal</option>
                  <option  value="glass" <?php if ($_POST['lens_category']=="glass") echo 'selected="selected"'; ?>>Glass</option>
                  <option  value="all prog" <?php if ($_POST['lens_category']=="all prog") echo 'selected="selected"'; ?>>All Progressives</option>
                  <option  value="prog cl" <?php if ($_POST['lens_category']=="prog cl") echo 'selected="selected"'; ?>>Progressive Classic</option>
                  <option  value="prog ds" <?php if ($_POST['lens_category']=="prog ds") echo 'selected="selected"'; ?>>Progressive DS</option>
                  <option  value="prog ff" <?php if ($_POST['lens_category']=="prog ff") echo 'selected="selected"'; ?>>Progressive FF</option>
                  <option  value="sv" <?php if ($_POST['lens_category']=="sv") echo 'selected="selected"'; ?>>Sv</option>
                  <option  disabled="disabled" value="">&nbsp;</option>
                  <option  disabled="disabled" value="">*LENS TYPE*</option>
                  <option   value="AO Compact" <?php if ($_POST['lens_category']=="AO Compact") echo 'selected="selected"'; ?>>AO COMPACT</option> 
                  <option   value="Compact Ultra HD" <?php if ($_POST['lens_category']=="Compact Ultra HD") echo 'selected="selected"'; ?>>COMPACT ULTRA HD</option> 
                  <option   value="EZ" <?php if ($_POST['lens_category']=="EZ") echo 'selected="selected"'; ?>>EZ BY CZV</option> 
                  <option   value="FT28" <?php if ($_POST['lens_category']=="FT28") echo 'selected="selected"'; ?>>FT28</option> 
                  <option   value="FT35" <?php if ($_POST['lens_category']=="FT35") echo 'selected="selected"'; ?>>FT35</option> 
                  <option   value="FT45" <?php if ($_POST['lens_category']=="FT45") echo 'selected="selected"'; ?>>FT45</option> 
                  <option   value="Innovative 1.0" <?php if ($_POST['lens_category']=="Innovative 1.0") echo 'selected="selected"'; ?>>INNOVATIVE 1.0</option> 
                  <option   value="Innovative 2.0" <?php if ($_POST['lens_category']=="Innovative 2.0") echo 'selected="selected"'; ?>>INNOVATIVE 2.0</option> 
                  <option   value="Innovative 3.0" <?php if ($_POST['lens_category']=="Innovative 3.0") echo 'selected="selected"'; ?>>INNOVATIVE 3.0</option> 
                  <option   value="RD" <?php if ($_POST['lens_category']=="RD") echo 'selected="selected"'; ?>>RD</option> 
                  <option   value="SelectionRx" <?php if ($_POST['lens_category']=="SelectionRx") echo 'selected="selected"'; ?>>SELECTIONRX</option> 
                  <option   value="Single Vision" <?php if ($_POST['lens_category']=="Single Vision") echo 'selected="selected"'; ?>>SINGLE VISION</option> 
                  <option   value="Sola Easy" <?php if ($_POST['lens_category']=="Sola Easy") echo 'selected="selected"'; ?>>SOLA EASY</option> 
                  <option   value="Trifocal 7x28" <?php if ($_POST['lens_category']=="Trifocal 7x28") echo 'selected="selected"'; ?>>TRIFOCAL 7x28</option>
                  <option   value="Trifocal 8x35" <?php if ($_POST['lens_category']=="Trifocal 8x35") echo 'selected="selected"'; ?>>TRIFOCAL 8x35</option> 
                  <option   disabled="disabled" value="">&nbsp;</option>
                  <option   disabled="disabled" value="">*MANUFACTURER*</option>
                  <option   value="IOT" <?php if ($_POST['lens_category']=="IOT") echo 'selected="selected"'; ?>>IOT</option> 
                  <option   value="OPTOTECH" <?php if ($_POST['lens_category']=="OPTOTECH") echo 'selected="selected"'; ?>>OPTOTECH</option>  
                  <option   value="SOLA/ZEISS" <?php if ($_POST['lens_category']=="SOLA/ZEISS") echo 'selected="selected"'; ?>>SOLA/ZEISS</option>  
           </select></div>
            <?php 
				}
				?>
                     </td>
                   
               </table>
             </div>
             
         
         
			
<div align="center" style="margin:11px">
		      <input name="submitbutton" type="submit" value="<?php echo $btn_submit_txt;?>"/>
              
              
              
            <?php /*?>  <?php if ($mylang == 'lang_french'){
				echo '<br><img src="http://www.direct-lens.com/lensnet/images/crizal_fr.jpg"  />';
				}else {
				echo '<br><img src="http://www.direct-lens.com/lensnet/images/crizal_en.jpg"  />';
				}
				?><?php */?>
           
              
              
		    </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>