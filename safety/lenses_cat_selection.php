<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

include "config.inc.php";
global $drawme;
						
$prod_table="ifc_frames";

if ($mylang == 'lang_france') $prod_table="ifc_frames_french";
require_once "../upload/phpuploader/include_phpuploader.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	
	require('../Connections/sec_connect.inc.php');
if($_SESSION["account_type"]=="restricted"){
	header("Location:order_history.php");
}
	
  $queryLab = "Select main_lab,password from accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysql_query($queryLab)	or die ("Could not select items");
  $DataLab=mysql_fetch_array($resultLab);
  $LabNum=$DataLab[main_lab];	
  $CurrentPwd = $DataLab[password];
 
  //Defalt password we redirect the customer to the page where he will update it and confirm his email
  if ( $CurrentPwd =='111111'){
  header("Location:update_account.php");
  exit();
  }
	
	
	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>

<script type="text/javascript">
function CheckSelection() {
document.forms[0].Submit.disabled=false;
}
//-->
</script>   

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
<?php include "js/lens_selection.js.inc.php";?>
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
<div id="rightColumn"><form action="lensRedirection.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateChoice(this);">
		      <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		      <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header">Ordonnance</div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ps_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table>
		      
       
             <div>
               <table width="270" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
                 <tr>
                   <td bgcolor="#ee7e32" class="tableHead">&nbsp;&nbsp;&nbsp;Choix de verres</td>
                   <td bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
                 </tr>
                     <tr>
                      
                   <td width="100%" align="center" valign="top" class="formCellNosides">
                   <div class="home_features_header">Les verres</div>
<?php if ($mylang == 'lang_france'){		?>
<div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                     Tous Progressifs</div>

                   <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 14" /> 
                    Progressif 14mm</div>
                    
                     <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 16" /> 
                    Progressif 16mm</div>
                    
                     <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 20" /> 
                    Progressif 20mm</div>
                       <div style="font-size:14px; margin-left:40px; padding:5px"><b>ou</b></div>
                       <div style="font-size:14px; margin-left:20px;padding:3px"><input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv" />
  Unifocaux </div>
                     <?php 
				}else {
				?>
<div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                     Tous Progressif</div>

                      <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 14" /> 
                    Progressif 14mm</div>
                    
                     <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 16" /> 
                    Progressif 16mm</div>
                    
                     <div style="font-size:14px; margin-left:20px; padding:3px"> <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="prog 20" /> 
                    Progressif 20mm</div>
                       <div style="font-size:14px; margin-left:40px; padding:5px"><b>ou</b></div>
                       <div style="font-size:14px; margin-left:20px;padding:3px"><input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv" />
  Unifocaux </div>
                     <?php
                }
				?>
                   
                  
                    </td>
                
                   
               </table>
             </div>
             
         
         
			
<div align="center" style="margin:11px">
		      <input name="Submit" id="Submit" disabled="disabled" type="submit" value="<?php echo $btn_submit_txt;?>"/>
		    </div>
		  </form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<?php include("footer.inc.php"); ?></div><!--END containter-->
</body>
</html>