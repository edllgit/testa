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
include "../sec_connectEDLL.inc.php";
include "config.inc.php";
include "../includes/getlang.php";
include"config.inc.php";
global $drawme;
			
$prod_table="ifc_frames_french";
if ($mylang == 'lang_french') $prod_table="ifc_frames_french";
//require_once "../upload/phpuploader/include_phpuploader.php"; 
 
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	
	include "../sec_connectEDLL.inc.php";
if($_SESSION["account_type"]=="restricted"){
	header("Location:order_history.php");
}
	
	  
  $queryAccesPremium = "SELECT  main_lab FROM accounts WHERE user_id ='" . $_SESSION["sessionUser_Id"] . "'";
  $resultAccessPremium=mysqli_query($con,$queryAccesPremium)	or die ("ERROR:".mysqli_error($con)." sql=".$queryAccesPremium);
  $DataAccessPremium=mysqli_fetch_array($resultAccessPremium,MYSQLI_ASSOC);	
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
  				<?php include("includes/sideNav.inc.php");	?>
			</div><!--END leftnav-->
		</div><!--END leftcolumn-->
		<div id="rightColumn">
			<form action="frame.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateChoice(this);">
			<div class="loginText">
				<?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
    		</div>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="headerBox" class="header">            	
                        <?php if ($mylang == 'lang_french'){?>
                            Achat de Packages
                        <?php }else {?>
                            Purchase Packages
                        <?php }?>                
                        </div>
                    </td>
                 </tr>
            </table>	      
       
			<div>
        <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
         <tr>
           <td bgcolor="#ee7e32" class="tableHead">
                &nbsp;&nbsp;&nbsp;
                <?php if ($mylang == 'lang_french'){?>
                    Monture et choix de verres
                <?php }else {?>
                    Frame And Lens Choices
                <?php }?>
           </td>
           <td bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
         </tr>
         <tr>
           <td width="57%" align="center" valign="top" class="formCellNosides grey-border-right"> 
            <div class="home_features_header">Collections</div>
                <div align="center">
                    <img src="http://www.direct-lens.com/safety/design_images/logo-ibasic.gif" hspace="5" vspace="5" width="90">
                    <img src="http://www.direct-lens.com/safety/design_images/logo-basic.gif" hspace="5" vspace="5" width="90">
                    <img src="http://www.direct-lens.com/safety/design_images/logo-wrap-rx.gif" hspace="5" vspace="5" width="90"><br>                  
                    <img src="http://www.direct-lens.com/safety/design_images/logo-classic.gif" hspace="5" vspace="5" width="90">
                    <img src="http://www.direct-lens.com/safety/design_images/logo-metro.gif" hspace="5" vspace="5" width="90">
                </div>            
            <div align="center" style="margin-bottom:15px">
            <table width="390" cellpadding="0" cellspacing="3" style="background-color:#eff9fd">
                <tr>
                    <td colspan="2">
                        <div style="padding:5px 0px 5px 0px" class="tableSubHead">
                        <?php if ($mylang == 'lang_french'){?>
                            Choisissez un modèle spécifique ou sélectionnez un type de verres :
                        <?php }else {?>
                            Choose a Specific Model and Select Type of Lenses 
                        <?php }?>                            
                        </div>                            
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="model" id="model" class="formText" style="margin-right:5px; margin-bottom:3px">
                        <option value="none">
                        <?php if ($mylang == 'lang_french'){?>
                            Modèles
                        <?php }else {?>
                            Models
                        <?php }?>
                        </option>
                        <?php
						
						
						if ($DataAccessPremium[main_lab] == 3)
						$LabInventaire = 3;
						
						if ($DataAccessPremium[main_lab] == 21)
						$LabInventaire = 22;
						
						//If acces is yes, the customer can access the premium and premium plus frames collection 
						if ($DataAccessPremium['acces_frames_premium'] =='yes')
						{
					    $sql= "SELECT safety_frames_french.model, safety_frames_french.misc_unknown_purpose, safety_frames_french.collection, safety_frames_french.color,safety_frames_french.color_en    FROM safety_frames_french WHERE  active = 1 GROUP BY model, color";
						}else{
						$sql= "SELECT safety_frames_french.model, safety_frames_french.misc_unknown_purpose, safety_frames_french.collection, safety_frames_french.color,safety_frames_french.color_en  FROM safety_frames_french WHERE  active = 1 GROUP BY model, color";
						}	
						echo '<br>Query: '. $sql;
                        $result=mysqli_query($con,$sql)	or die ("ERROR:".mysqli_error($con)." sql=".$sql);
                        while ($item=mysqli_fetch_array($result,MYSQLI_ASSOC)){
						
						 if ($mylang == 'lang_french'){
							 $couleur =  $item[color];
							 }else{
							 $couleur =  $item[color_en];
							 }
						
						if (substr($item[model],4,1)=='P'){
							 if ($mylang == 'lang_french'){
							 $Detail = ' (Avec protection latérale)';
							 }else{
							 $Detail = ' (Permanent side shield include)';
							 }
						}else{
						$Detail = '';
						}
						
                        if ($item[model] <> '')
                        echo "<option value=\"$item[model]\"> $item[model] - $couleur - $item[collection]  $Detail</option>";			
                        }
                        ?>
                        </select>
                    </td>
                    <td>&nbsp;</td>
                </tr>                  
                <input type="hidden" name="gender" value="all"  /> 
                <input type="hidden" name="type" value="all"  /> 
                <input type="hidden" name="material" value="all"  />  
                <input type="hidden" name="color" value="all"  />  
                <input type="hidden" name="boxing" value="all"  />  
            
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
            </table>
            </div>
            
               
            
            </td>
            <td width="43%" align="center" valign="top" class="formCellNosides">
           
                <?php if ($mylang == 'lang_french'){?>
                
                <div class="home_features_header">Les verres</div>
                
                <div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                Progressifs</div>                

                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="bifocal" /> 
                Bi-focaux</div>
                           
                <div style="font-size:14px; margin-left:20px;padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv"  />
                Unifocaux</div>
                
                <?php }else {?>
                
                <div class="home_features_header">Lenses</div>
                
                <div style="font-size:14px; margin-left:20px; margin-top:15px; padding:3px"> 
                <input name="lens_category" type="radio" onClick="CheckSelection();" id="lens_category" value="all" />
                Progressives</div>
            
                <div style="font-size:14px; margin-left:20px; padding:3px"> 
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="bifocal" /> 
                Bifocal</div>            
                             
                <div style="font-size:14px; margin-left:20px;padding:3px">
                <input type="radio" name="lens_category" onClick="CheckSelection();" id="lens_category" value="sv"  />
                Sv</div>
                <br /><br />
                
                <?php
                }
                ?>
            </td>
          <tr>
            
        </table>
	</div>

    <div align="center" style="margin:11px">
        <input name="Submit" disabled="disabled" type="submit" value="<?php echo $btn_submit_txt;?>"/>
    </div>
    
</form>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->

</body>
</html>