<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");

require('../Connections/sec_connect.inc.php');

$product_id=$_POST[product_id];

mysql_query("SET CHARACTER SET UTF8");
$query="select * from ifc_ca_exclusive WHERE primary_key='$product_id' "; 
$result=mysql_query($query)	or die  ('I cannot select items because: ' . mysql_error());
	
$listItem=mysql_fetch_array($result);
$usercount=mysql_num_rows($result);


$frame_type=$_SESSION['PrescrData']['FRAME_TYPE'];

 if ($mylang == 'lang_french'){
 $Commande = " Pack Montage ";
 }
 
 if ($mylang == 'lang_english'){
 $Commande = " Package ";
 }
  
$AfficherPageCommande = true;
$queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('exclusive') AND order_status NOT IN ('pre-basket')";
$ResultBasket=mysql_query($queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
$DataBasket=mysql_fetch_array($ResultBasket);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>

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
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
window.onload = function() {
document.getElementById("employee_password").onblur = function() {
var xmlhttp;
var employee_password=document.getElementById("employee_password");
if (employee_password.value != "")
{
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest();
  }
else
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
xmlhttp.onreadystatechange=function()
  {
  if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
    document.getElementById("status").innerHTML=xmlhttp.responseText;
		if (xmlhttp.responseText == "<span style=\"color:red;\">Le mot de passe que vous avez saisi est incorrect</span>"){
			document.getElementById("Submitbtn").disabled=true;
		}else{
			document.getElementById("Submitbtn").disabled=false;
		}
    }
  };
xmlhttp.open("POST","do_check.php",true);
xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
xmlhttp.send("employee_password="+encodeURIComponent(employee_password.value));
document.getElementById("status").innerHTML="Vérification en cours...";
}
};
};
</script>
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
<div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
  <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>
  
  <div id="headerBox" class="header">
	<?php 	if ($mylang == 'lang_french') {  ?>
    Unifocaux
    <?php  	}else{ ?>
    Single Vision
    <?php 	} ?>    
  </div>
  
  </td><td><div id="headerGraphic">
  
  
  <?php if ($mylang == 'lang_french'){ ?>
 <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/detail_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/detail_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
  </div></td></tr></table>
<div class="Subheader"><?php echo $lbl_product_txt;?> 

<b><?php
if ($mylang == 'lang_french'){
	
	if ($_SESSION["sessionUser_Id"] == 'warehousestc')
	echo  " Pack - "	    . $listItem[product_name_en] ;
	else
	echo  " Pack Montage - ". $listItem[product_name] ;
	
 }else{
		
	if ($_SESSION["sessionUser_Id"] == 'warehousestc')
	echo  " Package - "	    . $listItem[product_name_en] ;
	else
	echo  " Pack Montage - ". $listItem[product_name] ;
	}
?>
</b>
</div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="5" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_patinfo_txt;?></td>
                <td bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_submast_slspercon;?>&nbsp;</td>
              </tr>
              <tr >
                <td bgcolor="#D5EEF7" class="formCellNosides"><?php echo $lbl_lname_txt;?></td>
                <td bgcolor="#D5EEF7" class="formCellNosides"><?php echo $lbl_fname_txt;?></td>
                <td bgcolor="#D5EEF7" class="formCellNosides"><?php echo $adm_refnumber_txt;?></td>
                <td colspan="3" bgcolor="#D5EEF7" class="formCellNosides"><?php echo $adm_salespersonid_txt;?></td>
              </tr>
              <tr >
                <td class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['LAST_NAME'];?></td>
                <td class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['FIRST_NAME'];?></td>
                <td class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['PATIENT_REF_NUM'];?></td>
                <td class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['SALESPERSON_ID'];?></td>
              </tr>
              
              
               <?php if ($_SESSION['PrescrData']['EYE']=="L.E."){
			  		include("includes/prescriptionDetail_LE.inc.php");}
			  	else if ($_SESSION['PrescrData']['EYE']=="R.E."){
			  		include("includes/prescriptionDetail_RE.inc.php");}
				else {
			  		include("includes/prescriptionDetail_Both.inc.php");}
					?>
            </table>
            
            
             <?php if ($_SESSION["CompteEntrepot"] == 'yes'){ ?>
         	<div>
				  <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">

                  <tr>
                    <td width="350" colspan="1" align="center" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                    <td colspan="2" align="center" bgcolor="#17A2D2" class="tableHead"><?php echo 'PROMO 2 pour 1';?></td>
                    <td width="350" colspan="1" align="center" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
                  </tr>
                  <tr>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td width="250" align="center" class="formCellNosides"><?php echo 'Référence unique';?>&nbsp;</td>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                   </tr>
                   
                  <tr>
                    <td  width="300" colspan="1"  align="center" class="formCellNosides">&nbsp;</td>
                    <td  width="250" align="center" class="formCellNosides"><?php echo $_SESSION['prFormVars']['REFERENCE_PROMO'];?><?php echo $_SESSION['PrescrData']['REFERENCE_PROMO'];?></td>
                    <td  width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                    <td  width="300" colspan="1" align="center" class="formCellNosides">&nbsp;</td>
                  </tr>
               </table>
              </div>     
              <?php } ?>
            
            
            
            <?php 
				if ($_SESSION['PrescrData']['myupload']){
				?>
       <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
       <tr><td colspan="6" bgcolor="#17A2D2" class="tableHead">&nbsp;</td></tr>
       <tr><td colspan="6"  class="formCellNosides">LENS PROFILE: <?php echo $_SESSION['PrescrData']['myupload']?>&nbsp;</td></tr>
      </table>
            <?php } ?>
		 
      
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
             
             <?php if (($_SESSION['PrescrData']['PT']!="")&&($_SESSION['PrescrData']['PA']!="")&&($_SESSION['PrescrData']['VERTEX']!="")){?>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_pt_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['PT']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $lbl_pa_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['PA']?></td>
                <td align="center" class="formCellNosidesRA"><?php echo $adm_vertex_txt;?></td>
                <td align="center" class="formCellNosides"><?php echo 
$_SESSION['PrescrData']['VERTEX']?></td>
                <td align="center" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
                <td align="center" class="formCellNosidesRA">&nbsp;</td>
                <td align="center" class="formCellNosides">&nbsp;</td>
              </tr>
              <?php }?>

             
             
             
              <tr >
              
                <td align="center" class="formCellNosidesRA"><?php  echo $adm_tint_txt;?></td>
                <td colspan="9" align="left" class="formCellNosides">
                
                <?php
				if (($_SESSION['PrescrData']['TINT_COLOR']=="Grey") && ($mylang == 'lang_french'))
				$couleurFr = 'Gris';
				
				if (($_SESSION['PrescrData']['TINT_COLOR']=="Brown") && ($mylang == 'lang_french'))
				$couleurFr = 'Brun';
				
				if (($_SESSION['PrescrData']['TINT_COLOR']=="G-15") && ($mylang == 'lang_french'))
				$couleurFr = 'G-15';
				
				if (($_SESSION['PrescrData']['TINT_COLOR']=="Grey") && ($mylang == 'lang_english'))
				$couleurFr = 'Grey';
				
				if (($_SESSION['PrescrData']['TINT_COLOR']=="Brown") && ($mylang == 'lang_english'))
				$couleurFr = 'Brown';
				
				if (($_SESSION['PrescrData']['TINT_COLOR']=="G-15") && ($mylang == 'lang_english'))
				$couleurFr = 'G-15';
				 ?>
                
               <?php 
			   if(($_SESSION['PrescrData']['TINT']==    "Solid")  && ($mylang == 'lang_french')) {echo 'Unie: '    . $couleurFr. ': '. $_SESSION['PrescrData']['FROM_PERC'] . '%';}
			   if(($_SESSION['PrescrData']['TINT']== "Gradient")  && ($mylang == 'lang_french')) {echo 'Dégradé: ' . $couleurFr. ': '. $_SESSION['PrescrData']['FROM_PERC'] . '%  a '. $_SESSION['PrescrData']['TO_PERC'].'%';}
			   if(($_SESSION['PrescrData']['TINT']==    "Solid")  && ($mylang <> 'lang_french')) {echo 'Solid: '   . $couleurFr. ': '. $_SESSION['PrescrData']['FROM_PERC'] . '%';}
			   if(($_SESSION['PrescrData']['TINT']== "Gradient")  && ($mylang <> 'lang_french')) {echo 'Gradient: '. $couleurFr. ': '. $_SESSION['PrescrData']['FROM_PERC'] . '%  to '. $_SESSION['PrescrData']['TO_PERC'].'%';}
			    ?>
                </td>
                
              </tr>
              <tr >
                <td colspan="6" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_lenschar_txt_pl;?></td>
              </tr>
              <tr >
                <td width="143" align="right" class="formCellNosidesRA"><?php echo $adm_prodname_txt;?></td>
                <td colspan="3" class="formCellNosides"><b><?php echo $listItem[product_name];?></b></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_material_txt_pl;?></td>
                <td width="180" class="formCellNosides"><b><?php echo $listItem[index_v] ?></b></td>
                <td width="174" align="right" class="formCellNosidesRA"><?php echo $adm_photochr_txt;?></td>
                
                <td width="127" class="formCellNosides"><b><?php if (($listItem[photo] == 'None')&& ($mylang == 'lang_french')){echo 'Non';}else{echo $listItem[photo];} ?></b></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $adm_coating_txt;?></td>
                <td class="formCellNosides"><b><?php echo $listItem[coating] ?></b></td>
                <td align="right" class="formCellNosidesRA"><?php echo $adm_polarized_txt;?></td>
                
                <td class="formCellNosides"><b><?php if (($listItem[polar] == 'None')&& ($mylang == 'lang_french')){echo 'Non';}else{echo $listItem[polar];}?></b></td>
              </tr>
              <tr >
                <td align="right" class="formCellNosidesRA"><?php echo $lbl_specinstr_txt;?></td>
                <td colspan="3" class="formCellNosides"><b><?php echo $_SESSION['PrescrData']['SPECIAL_INSTRUCTIONS'];?></b></td>
              </tr>
              
               <tr >
                <td align="right" class="formCellNosidesRA"><?php echo 'Internal Note';?></td>
                <td colspan="3" class="formCellNosides"><b><?php echo $_SESSION['PrescrData']['INTERNAL_NOTE'];?></b></td>
              </tr>
              
               <tr>
              <td align="right" class="formCellNosidesRA"><?php if ($mylang == 'lang_french'){ 
				echo 'Initiales:';
				}else{
				echo 'Initials:';
				}
				?></td>
             
             <td align="left" class="formCellNosides"><?php 
				echo $_SESSION['PrescrData']['ENGRAVING'];
				?></td>
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
				$F_result=mysql_query($F_query)
					or die  ('I cannot select items because: ' . mysql_error());
				$F_listItem=mysql_fetch_array($F_result);
			 	 $frame_text="<div>Monture:</div><div>Suppléments:</div>";
			  	}
			//PRISM

  			 //Dispensing Fee / Honoraire professionnel
			  if ($_SESSION['PrescrData']['DISPENSING_FEE_SV'] <> ''){
					if ($mylang == 'lang_french') {  
					//$dispensing_fee_sv="<div>Honoraires du professionnel (SV): ".  $_SESSION['PrescrData']['DISPENSING_FEE_SV']."$</div>";                  
					}else{ 
					//$dispensing_fee_sv="<div>Dispensing Fee (SV): $".  $_SESSION['PrescrData']['DISPENSING_FEE_SV']."</div>";     
					}
			  }


			 //Cushion - Coussinets
			  if ($_SESSION['PrescrData']['CUSHION'] <> 0 ){
					if ($mylang == 'lang_french') {  
					$cushion_text="<div>Coussinet: ".  $_SESSION['PrescrData']['CUSHION_SELLING_PRICE']."$</div>";                  
					}else{ 
					$cushion_text="<div>Cushion: $".  $_SESSION['PrescrData']['CUSHION_SELLING_PRICE']."</div>";  
					}
			  }
			  
			  //Dust Bar - Pare-Poussière
			  if ($_SESSION['PrescrData']['DUST_BAR']=='on'){
					if ($mylang == 'lang_french') {  
					$dustbar_text="<div>Pare-Poussière: ".  $_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE']."$</div>";                  
					}else{ 
					$dustbar_text="<div>Dust Bar: $".       $_SESSION['PrescrData']['DUST_BAR_SELLING_PRICE']."</div>";  
					}
			  }

			  //ENGRAVING
			  if ($_SESSION['PrescrData']['ENGRAVING']!=""){
			  
			 $E_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Engraving' ";
			$E_result=mysql_query($E_query)
					or die  ('I cannot select items because: ' . mysql_error());
			$E_listItem=mysql_fetch_array($E_result);
			  $engraving_text="<div>Engraving:</div>";
			  }
			  
			    //Mirror
				if ($_SESSION['PrescrData']['MIRROR']<>"None"){
			 $QueryMirror="SELECT * from extra_prod_price_lab LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Mirror' ";			
			  $resultMirror = mysql_query($QueryMirror)					or die  ('I cannot select items because: ' . mysql_error());
			  $DataMirror   = mysql_fetch_array($resultMirror);
			  $mirror_text  = "<div>Mirror&nbsp;:&nbsp;<b>" .$_SESSION['PrescrData']['MIRROR']."</b></div>";
			  }
				
			  
			  //echo '<br>Passe 4';
			  //TINT
			    if ($_SESSION['PrescrData']['TINT']!="None"){
				$T_query="SELECT * from extra_prod_price_lab
				LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
				WHERE lab_id='$main_lab_id' AND category='Tint' AND tint='$tint' ";
				
			$T_result=mysql_query($T_query)
					or die  ('I cannot select items because: ' . mysql_error());
			$T_listItem=mysql_fetch_array($T_result);
			
			  	//$tinting_text="</div>Tinting:</div>";
				$tinting_text="</div>Teint&eacute;</div>";
			  }
			  //EDGE AND MOUNT
			    if ($_SESSION['PrescrData']['JOB_TYPE']!="Uncut"){
					 if (($_SESSION['PrescrData']['FRAME_MODEL']!="")&&($_SESSION['PrescrData']['ORDER_TYPE']=="Provide")){//IS FRAME PACKAGE
						$EM_query="SELECT * from extra_prod_price_lab
						LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
						WHERE lab_id='$main_lab_id' AND category='Edging_Frame' AND frame_type='$frame_type' ";
					 }
					 else{
						$EM_query="SELECT * from extra_prod_price_lab
						LEFT JOIN (extra_products) ON (extra_prod_id = prod_id) 
						WHERE lab_id='$main_lab_id' AND category='Edging' AND frame_type='$frame_type' ";
					 }
				
					$EM_result=mysql_query($EM_query)
					or die  ('I cannot select items because: ' . mysql_error());
			$EM_listItem=mysql_fetch_array($EM_result);
			
			  	//$edging_text="<div>Taillé et Monté:</div>";
				$edging_text="<div></div>";
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
				//$frame_text
			  echo "<tr ><td align=\"right\" bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">".$lbl_extrchrge_txt."</td>
                <td bgcolor=\"#FFFFFF\" class=\"formCellNosides\">&nbsp;</td>
                <td align=\"right\" bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">$mirror_text$engraving_text$tinting_text$edging_text$dispensing_fee_sv$cushion_text$dustbar_text</td>
                <td bgcolor=\"#FFFFFF\" class=\"formCellNosidesRA\" valign=\"top\">";
				//echo "<div>".$PR_price."</div>";
				//echo "<div>".$E_price."</div>";
				//echo "<div>".$T_price."</div>";
				//echo "<div>".$EM_price."</div>";
				//echo "<div>".$F_price."</div>";
				//echo "<div>".$HI_Fee."</div>";
				
			  echo "</td></tr>";
			  ?>
              <tr >
                <td align="right" bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td bgcolor="#E5E5E5" class="formCellNosides">&nbsp;</td>
                <td align="right" bgcolor="#E5E5E5" class="formCellNosidesRA"><?php echo $lbl_overrngch_txt;?></td>
                <td bgcolor="#E5E5E5" class="formCellNosidesRA"><?php 
				$over_range_re=0;
				$over_range_le=0;
				$over_range_total=0;
			if ($_SESSION['PrescrData']['EYE']!="L.E."){
				if (($_SESSION['PrescrData']['RE_SPHERE']>$listItem[sphere_max])||($_SESSION['PrescrData']['RE_SPHERE']<$listItem[sphere_min])){
				$over_range_re=10.00;
					echo "R.E. $";
				
					$over_range=money_format('%.2n',$over_range_re);
					echo $over_range;
					echo "<br>";
				}
			}//END LE CONDITIONAL
			
			if ($_SESSION['PrescrData']['EYE']!="R.E."){
				if (($_SESSION['PrescrData']['LE_SPHERE']>$listItem[sphere_max])||($_SESSION['PrescrData']['LE_SPHERE']<$listItem[sphere_min])){
					$over_range_le=10.00;
					echo "L.E. $";
				
					$over_range=money_format('%.2n',$over_range_le);
					echo $over_range;
					echo "<br>";
			}
			}//END RE CONDITIONAL
				
				$over_range_total=$over_range_re+$over_range_le;?>				</td>
              </tr>
              <tr >
                <td align="right" bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
                <td bgcolor="#D5EEF7" class="formCellNosides">&nbsp;</td>
                <td align="right" bgcolor="#D5EEF7" class="formCellNosidesRA">
				<?php echo $lbl_pperpair_txt;?>
                <?php 	if ($mylang == 'lang_french') {  ?>
             Ce montant n'inclut pas les extras (Teinte, Prisme, etc).                  
             <?php  	}else{ ?>
             This amount does not include any extras(Prism, Tint, ...). 
			<?php 	} ?> 
            
				 </td>
                <td bgcolor="#D5EEF7" class="formCellNosidesRA"><?php 
				
				
			if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
				$price=$listItem[e_lab_us_price];}	
			else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
				$price=$listItem[e_lab_can_price];}
								
			$price = $listItem['price_can'];
			$price = money_format('%.2n',$price);
			
			//WARRANTY
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 6;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 3;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 1) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 3;
			}
			
				
						
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price + 10;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 5;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 2) &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 5;
			}
			

			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="Both")){
			$price = $price+ 40;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="L.E.")){
			$price = $price + 20;
			}
			
			if (($_SESSION['PrescrData']['WARRANTY']== 'extension') &&  ($_SESSION['PrescrData']['EYE']=="R.E.")){
			$price = $price + 20;
			}
				
				
				
				
				
				if (($_SESSION['PrescrData']['EYE']=="R.E.")||($_SESSION['PrescrData']['EYE']=="L.E.")){
				$price=money_format('%.2n',$price/2);
			}
				
				if ($mylang == 'lang_french') {  
                echo "<b>".$price . '$';
                }else{ 
                echo '$<b>'.$price  ;
                }  ?> 
				
                
                
                
                </b></td>
              </tr>
      </table>
			<form id="form1" name="form1" method="post" action="basket.php">
			
         <div>
           	<?php
            if ($DataBasket[nbrResult] > 0){
				$AfficherPageCommande = false;
					if ($mylang == 'lang_french') { 
						echo '<p align="center">Pour pouvoir commander des ensemble verres et montures , veuillez d\'abord terminer les commandes de monture qui sont actuellement dans votre panier d\'achat.</p>';
					}else{ 
						echo '<p align="center">To order some packages, please process the stock frame orders that are already in your basket.</p>';
					} 		
			}
			?>
        </div>
            
             <?php if ($AfficherPageCommande){ ?> 
             
             
             
             <?php if ($_SESSION["CompteEntrepot"] == 'yes')
			      { ?>
                  <div align="center"> 
                  	  <div class="header">
					  <?php if ($mylang == 'lang_french'){ ?>*Mot de passe employé:<?php }else{ ?>*Employee Password:<?php } ?>
                      <input name="employee_password" type="password" class="formText" id="employee_password" value="" size="6" max="6" maxlength="6" />
                      <input name="validate_password" type="button" id="validate_password" 
                       value="<?php if ($mylang == 'lang_french'){ ?>Valider le mot de passe<?php }else{ ?>Validate Password<?php } ?>"/>
                      </div>
                      <span id="status"></span>
                  </div>
            <?php } ?>
             
             
             
            <div align="center" style="margin:11px"><input name="back" type="button" value="<?php echo $btn_edpres_txt;?>"  onclick="window.open('<?php print $_SESSION['REFERRER'];?>', '_top')"/>
			&nbsp;
			<input name="Submitbtn" id="Submitbtn" type="submit" value="<?php echo $btn_addbask_txt;?>"  <?php if ($_SESSION["CompteEntrepot"] == 'yes') echo 'disabled="disabled"'; ?> />
			&nbsp;&nbsp;&nbsp;&nbsp;<span class="plainText"><?php //echo $lbl_quantitypairs_txt;?> </span> 
			  <label>
			  <input name="quantity" type="hidden" class="formText" id="quantity" value="1" size="4" />
			  </label>
			  <input name="product_id" type="hidden" id="product_id" value="<?php echo $_POST[product_id]?>" />
			  <input name="fromPrescription" type="hidden" id="fromPrescription" value="true" />
			  <input name="continue_redirect" type="hidden" value="prescription.php"/>
			  <input name="overRange" type="hidden" id="overRange" value="<?php echo $over_range_total?>" />
			  <input name="high_index_addition" type="hidden" id="high_index_addition" value="<?php echo $HI_Fee?>" />
			</div>
             <?php } ?> 
	  </form>
</div><!--END rightcolumn-->
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