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
include_once('includes/dl_order_functions.inc.php');
include_once('includes/dl_ex_prod_functions.inc.php');
global $drawme;


if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");


if (isset($_POST[product_id])){
$product_id=$_POST[product_id];
}elseif ((isset($_POST[selected_lens]) && ($_POST[selected_lens] <> 0))){
$product_id=$_POST[selected_lens];
require('includes/dl_order_functions.inc.php');
catchOrderData();
}

$query="SELECT * FROM exclusive WHERE primary_key='$product_id' "; //TEAM LEADERS SECTION
$result=mysqli_query($con,$query) or die  ('I cannot select items because: ' . mysqli_error($con));
		
$listItem=mysqli_fetch_array($result,MYSQLI_ASSOC);
$usercount=mysqli_num_rows($result);


/*
//Code pour Summer Si$$le! 
$querySelectedPromo  = "SELECT selected_promotion FROM accounts WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
$resultSelectedPromo = mysqli_query($con,$querySelectedPromo) or die  ('I cannot select items because: ' . mysqli_error($con));
$DataSelectedPromo   = mysqli_fetch_array($resultSelectedPromo,MYSQLI_ASSOC);
$DebutNomProduit = substr($listItem[order_product_name],0,5);

//Partie pour évaluer s'il y aura des royautés (Summer Si$$le! Promotion)
if (($DataSelectedPromo[selected_promotion]=='sizzling summer')   && ($DebutNomProduit <>'Promo')) {
	$Royaute = 0;
	
	$queryLensCategory   = "SELECT lens_category FROM exclusive WHERE  primary_key = ".$product_id;
	$resultLensCategory  = mysqli_query($con,$queryLensCategory) or die  ('I cannot select items because: ' . mysqli_error($con));
	$DataLensCategory    = mysqli_fetch_array($resultLensCategory,MYSQLI_ASSOC);
	$LensCategory        = $DataLensCategory[lens_category]; 
	//1- On évalue si le produit commandé est du lens_Category prog cl, Si oui, on ajoute 2,50$ 
	if ($LensCategory =='prog cl')
	$Royaute = $Royaute + 2.5;
	
	//2- On évalue si le produit commandé est du lens_Category prog ds ou prog ff, Si oui, on ajoute 5$ 
	if (($LensCategory =='prog ds') || ($LensCategory =='prog ff'))
	$Royaute = $Royaute + 5;
	
	//3- On évalue si le coating est  parmis ces coatings:/*Dream AR, Smart AR,ITO AR,Xlr,MultiClear AR,Aqua Dream AR,CrizalF,Blue AR, sI UN DE CES COATING + 2.5$
	 //echo '<br>Coating: ' .$listItem[order_product_coating];
   switch($listItem[coating])
   {
	   case "Dream AR":		 $Royaute = $Royaute + 2.5; break;
	   case "Smart AR":		 $Royaute = $Royaute + 2.5; break;
	   case "ITO AR":		 $Royaute = $Royaute + 2.5; break;
	   case "Xlr":			 $Royaute = $Royaute + 2.5; break;
	   case "MultiClear AR": $Royaute = $Royaute + 2.5; break;
	   case "Aqua Dream AR": $Royaute = $Royaute + 2.5; break;
	   case "CrizalF":		 $Royaute = $Royaute + 2.5; break;
	   case "Blue AR":		 $Royaute = $Royaute + 2.5; break;
	   default: 		     $Royaute = $Royaute + 0; break;
   }
   
   

	//4- On évalue si le produit a un transition si oui,  + 2.5$
	switch($listItem[photo])
   {
	   case "Drivewear":	 	 $Royaute = $Royaute + 2.5; break;
	   case "Grey":	 	         $Royaute = $Royaute + 2.5; break;
	   case "Brown":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Yellow":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Violet":			 $Royaute = $Royaute + 2.5; break;
	   case "Pink": 			 $Royaute = $Royaute + 2.5; break;
	   case "Orange": 			 $Royaute = $Royaute + 2.5; break;
	   case "Green":		 	 $Royaute = $Royaute + 2.5; break;
	   case "Blue":		 		 $Royaute = $Royaute + 2.5; break;
	   case "Extra Active Grey": $Royaute = $Royaute + 2.5; break;
	   default: 		         $Royaute = $Royaute + 0;   break;
   }
   
   
   //4- On évalue si le produit a un polarized si oui,  + 2.5$
   //echo '<br>Polar: ' .$listItem[order_product_polar];
	switch($listItem[polar])
   {
	   case "Grey":	 	 $Royaute = $Royaute + 2.5; break;
	   case "Brown":	 $Royaute = $Royaute + 2.5; break;
	   case "Green":	 $Royaute = $Royaute + 2.5; break;
	   case "G-15": 	 $Royaute = $Royaute + 2.5; break;
	   case "G15": 		 $Royaute = $Royaute + 2.5; break;
	   default: 		 $Royaute = $Royaute + 0; break;
   }

}//End IF*/
//FIN Summer Si$$le! Promotion

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Direct-Lens &mdash; Prescription Detail</title>
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
<link href="dl.css" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158" /></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn">
		      <?php 
	include("includes/sideNav.inc.php");
	?>
        </div>
</td>
    <td width="685" valign="top">
		       <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td><div id="headerBox" class="header"><?php echo $lbl_titlemast_prescript;?></div></td><td><div id="headerGraphic"><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/direct-lens/design_images/detail_graphic.gif" alt="prescription search" width="445" height="40" /></div></td></tr></table><div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?></div>
			<div class="Subheader"><?php echo $lbl_product_txt;?> <b><?php print $listItem[product_name] ?></b></div>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              
                <?php  
			/*if ($Royaute > 0){
				$Royaute=money_format('%.2n',$Royaute);	 ?>
				
				
            <tr>
                <td colspan="5" bgcolor="#FF3333" class="tableHead">
                <p>
				<?php 
				if ($mylang == 'lang_french'){
					echo 'Chaleur E$tivale Opportunité: Vous pourriez recevoir  '.  $Royaute.'$  sur cette commande.<br> Des conditions s\'appliquent: Appeler votre service à la clientèle au 1-855 770-2124';
				}else {
					echo 'Summer Si$$le Opportunity: You can collect $'.  $Royaute.'  on this order.<br> Conditions apply: Please call Customer Service at 1-855 770-2124';
				}
				?></p>
                </td>
              </tr>
			
			
            
			<?php }*/?>
              
              
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_patinfo_txt;?></td>
                <td bgcolor="#000099" class="tableHead"><?php echo $lbl_submast_slspercon;?>&nbsp;</td>
              </tr>
              <tr >
                <td bgcolor="#D7E1FF" class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td bgcolor="#D7E1FF" class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td bgcolor="#D7E1FF" class="formCellNosides"><?php echo $adm_refnumber_txt;?></td>
                <td bgcolor="#D7E1FF" class="formCellNosides"><?php echo 'Tray (Lab Only)';?></td>
                <td bgcolor="#D7E1FF" class="formCellNosides"><?php echo $adm_salespersonid_txt;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php print 
$_SESSION['PrescrData']['LAST_NAME'];?></td>
                <td class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FIRST_NAME'];?></td>
                <td class="formCellNosides"><?php print 
$_SESSION['PrescrData']['PATIENT_REF_NUM'];?></td>
  <td class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TRAY_NUM'];?></td>
                <td class="formCellNosides"><?php print 
$_SESSION['PrescrData']['SALESPERSON_ID'];?></td>
              </tr>
            </table>

		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="7" bgcolor="#000099" class="tableHead"><?php echo $lbl_mast1;?>&nbsp;</td>
              </tr>
       
			  <?php if ($_SESSION['PrescrData']['EYE']=="L.E."){
			  		include("includes/prescriptionDetail_LE.inc.php");}
			  	else if ($_SESSION['PrescrData']['EYE']=="R.E."){
			  		include("includes/prescriptionDetail_RE.inc.php");}
				else {
			  		include("includes/prescriptionDetail_Both.inc.php");}
					?>
			  
              
            </table>
            <?php 
			$myupload = $_SESSION['PrescrData']['myupload'];
	
				if ($myupload){
				?>
       <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
       <tr><td colspan="6" bgcolor="#000099" class="tableHead">&nbsp;</td></tr>
       <tr><td colspan="6"  class="formCellNosides">LENS PROFILE: <?php echo $myupload?>&nbsp;</td></tr>
       </table>
            <?php } ?>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="10" bgcolor="#000099" class="tableHead"><?php echo $lbl_frameandother_txt;?></td>
              </tr>
<?php if (($_SESSION['PrescrData']['PT']!="")&&($_SESSION['PrescrData']['PA']!="")&&($_SESSION['PrescrData']['VERTEX']!="")){?>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_pt_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['PT']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $lbl_pa_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['PA']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_vertex_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['VERTEX']?></td>
                <td align="center" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
              <?php }?>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $adm_engraving_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['ENGRAVING']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_tint_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TINT']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_from_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FROM_PERC']?>%</td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_to_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TO_PERC']?>%</td>
                <td align="center" class="formCellNosidesRA"><?php echo $lbl_tintcolor_txt;?> </td>
                <td width="75" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TINT_COLOR']?></td>
                </tr>
              <tr >
                <td align="right" bgcolor="#D7E1FF" class="formCellNosides"><b><?php echo $lbl_framemast_txt;?></b></td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                </tr>
              <tr >
                <td width="62" align="right" class="formCellNosidesRA"><?php echo $adm_eyea_txt;?> </td>
                <td width="77" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_A']?></td>
                <td width="64" align="center" class="formCellNosidesRA"><?php echo $adm_b_txt;?></td>
                <td width="72" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_B']?></td>
                <td width="39" align="center" class="formCellNosidesRA"><?php echo $adm_ed_txt;?></td>
                <td width="61" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_ED']?></td>
                <td width="31" align="center" class="formCellNosidesRA"><?php echo $adm_dbl_txt;?></td>
                <td width="51" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_DBL']?></td>
                <td width="56" align="center" class="formCellNosidesRA"><?php echo $adm_temple_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TEMPLE']?></td>
                </tr>
              <tr >
                <td height="28" align="right" class="formCellNosidesRA"><?php echo $adm_type_txt;?> </td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_TYPE'];?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_frame_txt;?> </td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['ORDER_TYPE']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_jobtype_txt;?></td>
                <td colspan="2" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['JOB_TYPE']?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $adm_supplier_txt;?></td>
                <td align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['SUPPLIER']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_frameshp_txt;?></td>
                <td align="left" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['FRAME_MODEL']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_framemod_txt;?></td>
                <td align="left" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['TEMPLE_MODEL']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_color_txt;?></td>
                <td colspan="2" align="center" class="formCellNosides"><?php print 
$_SESSION['PrescrData']['COLOR']?></td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                </tr>
            </table>
		    <table width="650" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="4" bgcolor="#000099" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?></td>
              </tr>
              <tr >
                <td width="143" align="right" class="formCellNosidesRA"><?php echo $adm_prodname_txt;?></td>
                <td colspan="3" class="formCellNosides"><b><?php print $listItem[product_name];?></b></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_material_txt_pl;?></td>
                <td width="180" class="formCellNosides"><b><?php print $listItem[index_v] ?></b></td>
                <td width="174" align="right" class="formCellNosidesRA"><?php echo $adm_photochr_txt;?></td>
                <td width="127" class="formCellNosides"><b><?php print $listItem[photo];?></b></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $adm_coating_txt;?></td>
                <td class="formCellNosides"><b><?php print $listItem[coating] ?></b></td>
                <td align="right" class="formCellNosidesRA"><?php echo $adm_polarized_txt;?></td>
                <td class="formCellNosides"><b><?php print $listItem[polar];?></b></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_specinstr_txt;?></td>
                <td colspan="3" class="formCellNosides"><b><?php print $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?></b></td>
                </tr>
              <tr >
                <td align="right" class="formCellNosides">&nbsp;</td>
                <td class="formCellNosides">&nbsp;</td>
                <td align="right" class="formCellNosidesRA">&nbsp;</td>
                <td class="formCellNosides">&nbsp;</td>
              </tr>
			  
			  <?php
			  $main_lab_id=$_SESSION["sessionUserData"]["main_lab"];
			  $tint=$_SESSION['PrescrData']['TINT'];
			  $frame_type=$_SESSION['PrescrData']['FRAME_TYPE'];
			  
			  //FRAME
			  if (($_SESSION['PrescrData']['FRAME_MODEL']!="")&&($_SESSION['PrescrData']['ORDER_TYPE']=="Provide")){
			  
			  	$frame_model_num=$_SESSION['PrescrData']['FRAME_MODEL'];
			 	$F_query="SELECT * FROM frames 
				LEFT JOIN (frames_collections) ON (frames.frames_collections_id=frames_collections.frames_collections_id) 
				WHERE model_num='$frame_model_num'";
				$F_result=mysqli_query($con,$F_query) or die  ('I cannot select items because: ' . mysqli_error($con));
				$F_listItem=mysqli_fetch_array($F_result,MYSQLI_ASSOC);
			
			 	 $frame_text="<div>Frame:</div><div>High Index Fee:</div>";
			  	}
			//PRISM
				if (($_SESSION['PrescrData']['RE_PR_IO']!="None")||($_SESSION['PrescrData']['RE_PR_UD']!="None")||($_SESSION['PrescrData']['LE_PR_IO']!="None")||($_SESSION['PrescrData']['LE_PR_UD']!="None")){
			  
			 $PR_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Prism' ";
			$PR_result=mysqli_query($con,$PR_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$PR_listItem=mysqli_fetch_array($PR_result,MYSQLI_ASSOC);
		
			  $prism_text="<div>Prism:</div>";
			  }
			  //ENGRAVING
			  if ($_SESSION['PrescrData']['ENGRAVING']!=""){
			  
			 $E_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Engraving' ";
			$E_result=mysqli_query($con,$E_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$E_listItem=mysqli_fetch_array($E_result,MYSQLI_ASSOC);
		
			  $engraving_text="<div>Engraving:</div>";
			  }
			  //TINT
			    if ($_SESSION['PrescrData']['TINT']!="None"){
				$T_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Tint' AND tint='$tint' ";
				
			$T_result=mysqli_query($con,$T_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$T_listItem=mysqli_fetch_array($T_result,MYSQLI_ASSOC);
		
			
			  	$tinting_text="</div>Tinting:</div>";
			  }
			  //EDGE AND MOUNT
			    if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
					$EM_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Edging' AND frame_type='$frame_type' ";
				
			$EM_result=mysqli_query($con,$EM_query) or die  ('I cannot select items because: ' . mysqli_error($con));
			$EM_listItem=mysqli_fetch_array($EM_result,MYSQLI_ASSOC);
			
			
			  	//$edging_text="<div>Edge and Mount:</div>";
			  }
			  
			  if ($_SESSION["sessionUserData"]["currency"]=="US"){
					$PR_price=$PR_listItem[price_us];
					$E_price=$E_listItem[price_us];
					$T_price=$T_listItem[price_us];
					$EM_price=$EM_listItem[price_us];
					$F_price=$F_listItem[price_US];
				
					$indexString="US".str_replace(".","",$listItem[index_v]);
					$HI_Fee=$F_listItem[$indexString];
					$F_price=money_format('%.2n',$F_price);
					$HI_Fee=money_format('%.2n',$HI_Fee);
	
				}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
					$PR_price=$PR_listItem[price_can];
					$E_price=$E_listItem[price_can];
					$T_price=$T_listItem[price_can];
					$EM_price=$EM_listItem[price_can];
					$F_price=$F_listItem[price_CA];
				
					$indexString="CA".str_replace(".","",$listItem[index_v]);
					$HI_Fee=$F_listItem[$indexString];
					$F_price=money_format('%.2n',$F_price);
					$HI_Fee=money_format('%.2n',$HI_Fee);
					
				}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
					$PR_price=$PR_listItem[price_eur];
					$E_price=$E_listItem[price_eur];
					$T_price=$T_listItem[price_eur];
					$EM_price=$EM_listItem[price_eur];
					$F_price=$F_listItem[price_EUR];
					
					$indexString="EUR".str_replace(".","",$listItem[index_v]);
					$HI_Fee=$F_listItem[$indexString];
					$F_price=money_format('%.2n',$F_price);
					$HI_Fee=money_format('%.2n',$HI_Fee);
					
				}
				
			  print "<tr ><td align=\"right\" bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">".$lbl_extrchrge_txt."</td>
                <td bgcolor=\"#FFFFFF\" class=\"formCellNosides\">&nbsp;</td>
                <td align=\"right\" bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">$prism_text$engraving_text$tinting_text$frame_text</td>
                <td bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">";
				print "<div>".$PR_price."</div>";
				print "<div>".$E_price."</div>";
				print "<div>".$T_price."</div>";

				print "<div>".$F_price."</div>";
				print "<div>".$HI_Fee."</div>";
				
			  print "</td></tr>";
			  ?>
              <tr >
                <td align="right" bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="right" bgcolor="#E5E5E5" class="formCellNosidesRA"><?php echo $lbl_overrngch_txt;?></td>
                <td bgcolor="#E5E5E5" class="formCellNosidesRA"><?php 
				$over_range_re=0;
				$over_range_le=0;
				$over_range_total=0;
			
			if ($_SESSION["sessionUserData"]["product_line"] <> 'eye-recommend'){
					//The customer is NOT part or Prestige Program
					if ($_SESSION['PrescrData']['EYE']!="L.E."){
						if (($_SESSION['PrescrData']['RE_SPHERE']>$listItem[sphere_max])||($_SESSION['PrescrData']['RE_SPHERE']<$listItem[sphere_min])){
						$over_range_re=10.00;
							print "R.E. $";
						
							$over_range=money_format('%.2n',$over_range_re);
							print $over_range;
							print "<br>";
						}
					}//END LE CONDITIONAL
					
					if ($_SESSION['PrescrData']['EYE']!="R.E."){
						if (($_SESSION['PrescrData']['LE_SPHERE']>$listItem[sphere_max])||($_SESSION['PrescrData']['LE_SPHERE']<$listItem[sphere_min])){
							$over_range_le=10.00;
							print "L.E. $";
						
							$over_range=money_format('%.2n',$over_range_le);
							print $over_range;
							print "<br>";
						}
					}//END RE CONDITIONAL
			}//End IF Customer is NOT part of Prestige
			
			
			if ($_SESSION["sessionUserData"]["product_line"] == 'eye-recommend'){
					//The customer IS part or Prestige Program

						if (($_SESSION['PrescrData']['RE_SPHERE']>$listItem[sphere_max]) && ($_SESSION['PrescrData']['EYE']!="L.E.")){
							switch($_SESSION["sessionUserData"]["prestige_level"]){
								case 'high':   $over_range_le =  5.50;   	break;	
								case 'medium': $over_range_le =  7.50;   	break;	
								case 'low':    $over_range_le =  9.00;   	break;	
							}
							print "R.E. $";
							$over_range=money_format('%.2n',$over_range_re);
							print $over_range;
							print "<br>";
						}

						if (($_SESSION['PrescrData']['LE_SPHERE']>$listItem[sphere_max]) && ($_SESSION['PrescrData']['EYE']!="R.E.")){
							switch($_SESSION["sessionUserData"]["prestige_level"]){
								case 'high':   $over_range_le =  5.50;   	break;	
								case 'medium': $over_range_le =  7.50;   	break;	
								case 'low':    $over_range_le =  9.00;   	break;	
							}
							print "L.E. $";
							$over_range=money_format('%.2n',$over_range_le);
							print $over_range;
							print "<br>";
						}
			}//End IF Customer is  part of Prestige
			

			
				
				$over_range_total=$over_range_re+$over_range_le;?>				</td>
              </tr>
              <tr >
                <td align="right" bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td bgcolor="#D7E1FF" class="formCellNosides">&nbsp;</td>
                <td align="right" bgcolor="#D7E1FF" class="formCellNosidesRA"><?php echo $lbl_pperpair_txt;?> </td>
               
               <?php 
			switch($_SESSION["sessionUserData"]["currency"]){
			case 'CA':     $CustomerCurrency = '$'; 	  	  break;
			case 'US':     $CustomerCurrency = '$'; 	    break;
			case 'EUR':    $CustomerCurrency = "&#128;";   break;
			}
			
			
			
			   ?>
               
               
               
                <td bgcolor="#D7E1FF" class="formCellNosidesRA"><b><?php echo $CustomerCurrency;?><?php 
				
				if ($_SESSION["sessionUserData"]["currency"]=="US"){
				$price=$listItem[price];}
			else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
				$price=$listItem[price_can];}
			else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
				$price=$listItem[price_eur];}
				
			if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
				$price=$listItem[e_lab_us_price];}	
			else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
				$price=$listItem[e_lab_can_price];}
				
				if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
				$price=money_format('%.2n',$price/2);
			}
				print $price ?></b></td>
              </tr>
            </table>
			<form id="form1" name="form1" method="post" action="basket.php">
			<div align="center" style="margin:11px"><input name="back" type="button" value="<?php echo $btn_edpres_txt;?>"  onclick="window.open('prescription_retry.php', '_top')"/>
			&nbsp;
			<input name="Submit" type="submit" value="<?php echo $btn_addbask_txt;?>" />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="plainText"><?php //echo $lbl_quantitypairs_txt;?> </span> 
			  <label>
			  <input name="quantity" type="hidden"  id="quantity" value="1"  />
			  </label>
			 
             
     <?php     
if (isset($_POST[product_id])){?>
	  <input name="product_id" type="hidden" id="product_id" value="<?php print $_POST[product_id]?>" />
<?php }elseif ((isset($_POST[selected_lens]) && ($_POST[selected_lens] <> 0))){?>
	 <input name="product_id" type="hidden" id="product_id" value="<?php print $_POST[selected_lens]?>" />
<?php } ?>
            
			 
             
             
             
             
              <input name="fromPrescription" type="hidden" id="fromPrescription" value="true" />
			  <input name="continue_redirect" type="hidden" value="prescription.php"/>
			  <input name="overRange" type="hidden" id="overRange" value="<?php print $over_range_total?>" />
			  <input name="high_index_addition" type="hidden" id="high_index_addition" value="<?php print $HI_Fee?>" />
			</div>
			</form>
		  </td>
  </tr>
</table></td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p></br>
          </td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
<?php 

?>
</html>