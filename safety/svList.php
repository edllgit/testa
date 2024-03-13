<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
session_start();

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
	
require('../Connections/sec_connect.inc.php');
require('includes/dl_order_functions.inc.php');
mysql_query("SET CHARACTER SET UTF8");

catchOrderData();
$_SESSION['PACKAGE']=$_POST['PACKAGE'];//CATCH PACKAGE TYPE

//NO PRISM
$_SESSION['PrescrData']['RE_PR_IO']="None";
$_SESSION['PrescrData']['LE_PR_IO']="None";
$_SESSION['PACKAGE']=$_POST['PACKAGE'];//CATCH PACKAGE TYPE

$RE_SPHERE=$_SESSION['PrescrData']['RE_SPH_NUM'] .  $_SESSION['PrescrData']['RE_SPH_DEC'];
$LE_SPHERE=$_SESSION['PrescrData']['LE_SPH_NUM'] .  $_SESSION['PrescrData']['LE_SPH_DEC'];

$ModelChoisit = $_SESSION['PrescrData']['TEMPLE_MODEL'];
$Filtre6000   = " AND 7 = 7" ;
//Si le client choisit un de ces modeles il est limitié en sphere entre +2 à -2
switch($ModelChoisit){
	case '6001_BLK' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_BLUE': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_BRNZ': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_CRY' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_RED' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_GREY': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_SIL' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6001_WHT' : $Filtre6000 = " AND collection = '6000'";   break;	
	case '6002_BLUE': $Filtre6000 = " AND collection = '6000'";   break;	
	case '6002_PINK': $Filtre6000 = " AND collection = '6000'";   break;	
}

if (($LE_SPHERE > -2.01) && ($LE_SPHERE < 2.01) && ($RE_SPHERE > -2.01) && ($RE_SPHERE < 2.01)){
	$Filtre6000   = " AND 8 = 8" ;
}

if (($LE_SPHERE > 0) || ($RE_SPHERE > 0))
{
		//Une des sphère est negative, on calcule  TOUJOURS le diamètre nécessaire pour un verre STOCK
		
		//Logique de Calcul
		//1: Frame_A + Frame_DBL  
		//2: Resultat divisé par 2
		//3: Résultat: On soustrait le plus petit des 2 PD
		//4: Résultat: On le multiplie par 2
		//5: Résultat: On additionne le Frame_ED
		
		$Frame_A     = $_SESSION['PrescrData']['FRAME_A'];
		$Frame_DBL   = $_SESSION['PrescrData']['FRAME_DBL'];
		$Frame_ED    = $_SESSION['PrescrData']['FRAME_ED'];
		$LE_PD       = $_SESSION['PrescrData']['LE_PD']; 
		$RE_PD       = $_SESSION['PrescrData']['RE_PD']; 
		$PlusPetitPD = $LE_PD ;
		if ($RE_PD < $LE_PD)
		$PlusPetitPD = $RE_PD ;
		
		/*echo '<br>Frame A:'     . $Frame_A ;
		echo '<br>Frame DBL:' 	  . $Frame_DBL ;
		echo '<br>Frame ED:'  	  . $Frame_ED ;
		echo '<br>LE PD:'	  	  . $LE_PD ;
		echo '<br>RE PD:'	  	  . $RE_PD ;
		echo '<br>Plus petit PD:' . $PlusPetitPD ;*/
		
		$DiametreRequis = $Frame_A + $Frame_DBL;//35 + 37 = 72
		//echo '<br>Etape 1 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis / 2;
		//echo '<br>Etape 2 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis - $PlusPetitPD;
		//echo '<br>Etape 3 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis * 2;
		//echo '<br>Etape 4 :' . $DiametreRequis;
		$DiametreRequis = $DiametreRequis + $Frame_ED + 3;//+3 ajouté le 26 juin 2015 Demandé par Lynn	
		//echo '<br>Diamètre Requis:'. $DiametreRequis;	
		if ($DiametreRequis > 65)
		$FiltreDiameter = " AND diameter >=  $DiametreRequis";
}





//Set the correct Thickness depending of the requested spheres
switch($RE_SPHERE) {
	case '+0.00':
	case '-0.00':
	$_SESSION['PrescrData']['RE_CT'] = '3.0';
	$_SESSION['PrescrData']['RE_ET'] = '';
	break;
	
	case $RE_SPHERE > 0:
	$_SESSION['PrescrData']['RE_ET'] = '3.0';
	$_SESSION['PrescrData']['RE_CT'] = '';
	break;
	
	case $RE_SPHERE < 0:
	$_SESSION['PrescrData']['RE_CT'] = '3.0';
	$_SESSION['PrescrData']['RE_ET'] = '';
	break;
}

switch($LE_SPHERE) {
	case '+0.00':
	case '-0.00':
	$_SESSION['PrescrData']['LE_CT'] = '3.0';
	$_SESSION['PrescrData']['LE_ET'] = '';
	break;
		
	case $LE_SPHERE > 0:
	$_SESSION['PrescrData']['LE_ET'] = '3.0';
	$_SESSION['PrescrData']['LE_CT'] = '';
	break;

	case $LE_SPHERE < 0:
	$_SESSION['PrescrData']['LE_CT'] = '3.0';
	$_SESSION['PrescrData']['LE_ET'] = '';
	break;
}



$SelectedTint=$_POST[TINT];

$queryExpress = "SELECT  express FROM safety_frames_french WHERE safety_frames_id = ". $_POST[frame_id];
$resultExpress=mysql_query($queryExpress)		or die ("Could not select items");
$DataExpress=mysql_fetch_array($resultExpress);
if ($DataExpress[express] == 1){
$Express= " ";
}else{
$Express = " AND safety_exclusive.expressable = 0";
}

$Lens_Category=$_POST[lens_category];
switch ($Lens_Category) {
	case 'all'      :$lenscategory = " "; 								  break;
	case 'bifocal'  :$lenscategory = " AND lens_category IN ('bifocal')"; break;  
	case 'prog cl'  :$lenscategory = " AND lens_category IN ('prog cl')"; break;   
	case 'prog ds'  :$lenscategory = " AND lens_category IN ('prog ds')"; break;    
	case 'prog ff'  :$lenscategory = " AND lens_category IN ('prog ff')"; break;
	case 'prog 14'  :$lenscategory = " AND lens_category IN ('prog 14')"; break; 
	case 'prog 16'  :$lenscategory = " AND lens_category IN ('prog 16')"; break; 
	case 'prog 20'  :$lenscategory = " AND lens_category IN ('prog 20')"; break; 
	case 'glass'    :$lenscategory = " AND lens_category IN ('glass')";   break;      
	case 'sv' 	    :$lenscategory = " AND lens_category IN ('sv')";      break;        
}

$COATING=$_POST[COATING];
switch ($COATING) {
	case 'ANY':$COATING = " "; break;
	case 'HC':$COATING = " AND coating IN('Hard Coat','Super Hard Coat')";break;
	case 'AR':$COATING = " AND coating IN ('AR','Dream AR','Smart AR')";break;        
	case 'AR+ETC':$COATING = " AND coating IN ('AR+ETC','Dream AR','Smart AR')";break;                                
}

$INDEX=$_POST[INDEX];
switch ($INDEX) {
	case 'ANY' :$INDEX = " "; 					    break;
	case '1.57':$INDEX = " AND index_v IN (1.57)";  break;
	case '1.58':$INDEX = " AND index_v IN (1.58)";  break;
	case '1.60':$INDEX = " AND index_v IN (1.60)";  break;
	case '1.70':$INDEX = " AND index_v IN (1.70)";  break;
	case '1.50':$INDEX = " AND index_v IN (1.50)";  break;
	case '1.67':$INDEX = " AND index_v IN (1.67)";  break;
	case '1.53':$INDEX = " AND index_v IN (1.53)";  break;
	case '1.56':$INDEX = " AND index_v IN (1.56)";  break;
	case '1.74':$INDEX = " AND index_v IN (1.74)";  break;
	case '1.59':$INDEX = " AND index_v IN (1.59)";  break;
	case '1.54':$INDEX = " AND index_v IN (1.54)";  break;
	case '1.80':$INDEX = " AND index_v IN (1.80)";  break;
	case '1.90':$INDEX = " AND index_v IN (1.90)";  break;
	case '1.52':$INDEX = " AND index_v IN (1.52)";  break;
	default:    $INDEX = " AND index_v IN ($INDEX)";break;
}

$LE_HEIGHT=$_POST[LE_HEIGHT];
$RE_HEIGHT=$_POST[RE_HEIGHT];

if ($LE_HEIGHT =="") $LE_HEIGHT = 0;
if ($RE_HEIGHT =="") $RE_HEIGHT = 0;

$PHOTO=$_POST[PHOTO];
$POLAR=$_POST[POLAR];

$ORDER_PATIENT_LAST=$_POST[LAST_NAME];


$RE_CYL=$_SESSION['PrescrData']['RE_CYL_NUM'] . $_SESSION['PrescrData']['RE_CYL_DEC'];
$LE_CYL=$_SESSION['PrescrData']['LE_CYL_NUM'] . $_SESSION['PrescrData']['LE_CYL_DEC'] ;
$RE_AXIS=$_SESSION['PrescrData']['RE_AXIS'];
$LE_AXIS=$_SESSION['PrescrData']['LE_AXIS'];

$_SESSION['PACKAGE']=$_POST['PACKAGE'];//CATCH PACKAGE TYPE

	 
$query="SELECT * FROM acct_collections 
LEFT JOIN liste_collection_info on (liste_collection_info.liste_collection_id = acct_collections.collection_id) 
LEFT JOIN safety_exclusive on (liste_collection_info.collection_name = safety_exclusive.collection) 
WHERE prod_status='active' 
AND min_height <= $LE_HEIGHT
AND max_height >= $RE_HEIGHT
$Express
$COATING
$INDEX 
$filtrePrix 
$lenscategory 
$Filtre6000
AND photo='$PHOTO' 
AND polar='$POLAR'
AND (sphere_over_max>=$RE_SPHERE) 
AND (sphere_over_min<=$RE_SPHERE) 
AND (sphere_over_max>=$LE_SPHERE) 
AND (sphere_over_min<=$LE_SPHERE) 
AND (cyl_max>=$RE_CYL) 
AND (cyl_over_min<=$RE_CYL) 
AND (cyl_max>=$LE_CYL) 
AND (cyl_over_min<=$LE_CYL) ".$collectionString." 
AND acct_collections.acct_id = '".$_SESSION["id"]."' 
order by index_v "; //EXCLUSIVE

if ($_SESSION["sessionUser_Id"] == 'entrepotsafe'){
	//echo '<br><br>'.$query. '<br><br>';
}
$result=mysql_query($query)	or die  ("Please resubmit your form.<br><a href='javascript:history.back()'>Go Back 1 step.</a>". mysql_error()." ".$query);
$usercount=mysql_num_rows($result);
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

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
function CheckSelection() {
document.forms[0].Submit.disabled=false;
}
//-->
</script>
</head>

<body>
<div id="container">
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNav.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td>

<div id="headerBox" class="header">
	<?php 	if ($mylang == 'lang_french') {  ?>
    Unifocaux
    <?php  	}else{ ?>
    Single Vision
    <?php 	} ?>    
</div>

</td><td><div id="headerGraphic">


<?php if ($mylang == 'lang_french'){ ?>
  <img src="http://www.direct-lens.com/safety/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
   <?php }else{ ?>
  <img src=" http://www.direct-lens.com/direct-lens/design_images/list_graphic.gif" alt="prescription search" width="445" height="40" />
  <?php } ?>
</div></td></tr></table>
           <?php
		   if ($_SESSION['PrescrData']['EYE']=="L.E."){
		   $queryDoublons = "SELECT order_num from orders where
			le_axis   = '$LE_AXIS'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE' 
			and order_patient_last = '$ORDER_PATIENT_LAST'";		   
		   }
		   		   
		   if ($_SESSION['PrescrData']['EYE']=="R.E."){
		    $queryDoublons = "SELECT order_num from orders where
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'";
		   }
		   
		   
		   if ($_SESSION['PrescrData']['EYE']=="Both"){
           $queryDoublons = "SELECT order_num from orders where
			 le_axis   = '$LE_AXIS'
			and re_axis   = '$RE_AXIS'
			and re_cyl    = '$RE_CYL'
			and le_cyl    = '$LE_CYL'
			and le_sphere = '$LE_SPHERE'
			and re_sphere = '$RE_SPHERE'
			and order_patient_last = '$ORDER_PATIENT_LAST'";
		   }
		   
		   		
				if ($usercount != 0){
					echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"svDetail.php\">";
				}
				else{
					echo "<form id=\"form1\" name=\"form1\" method=\"post\" action=\"prescription_retry.php\">";
				}
				
					 
		   $resultDoublons = mysql_query($queryDoublons)	or die  ("Erreur 1:". mysql_error() . $queryDoublons);
		   $countDoublons  = mysql_num_rows($resultDoublons);		   
		   $queryAfficherWarning = "SELECT display_double_warning from accounts where user_id = '". 
		   $_SESSION["sessionUser_Id"] . "'";
		   $resultAfficherWarning = mysql_query($queryAfficherWarning)	or die  ("Erreur 2:". mysql_error() . $queryAfficherWarning);
		   $DataAfficherWarning=mysql_fetch_array($resultAfficherWarning);
		   $AfficherWarning = $DataAfficherWarning['display_double_warning'];

		   if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_french')){
		  	 echo '<br><font color="#FF0000" >Attention: Une commande avec le même numéro de référence et RX est déjà dans le système </font>.<br> ';
		   }
		   
		   if (($countDoublons > 0) && ($AfficherWarning == 'yes') && ($mylang == 'lang_english')){
		  	 echo '<br><font color="#FF0000" >Warning: An order with the same reference number and Rx is already in the system</font>.<br> ';
		   }
		   ?>

		    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
		    <div class="Subheader"><?php echo $lbl_srchres_txt;?></div>
		   
      <div class="plainText"><?php echo $lbl_numofitemsfnd_txt;?> <?php echo $usercount?> </div>
		    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
              <tr >
                <td colspan="9" bgcolor="#ee7e32" class="tableHead">&nbsp;</td>
                </tr>
              <tr>
                <td align="center"  class="formCell"><?php echo $adm_prodname_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_material_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $adm_coating_txt;?></td>
                <td align="center" class="formCell"><?php echo $lbl_photochro_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_polarized_txt_pl;?></td>
                <td align="center" class="formCell"><?php echo $lbl_overrang_txt;?></td>
                <td align="center" class="formCell"><?php echo $adm_price_txt;?><br /><?php 
				
				if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab US"){
					echo " E-Lab US";}
				else if ($_SESSION["sessionUserData"]["e_lab"]=="E-Lab CAN"){
					echo " E-Lab CA";}
				else {
					
				if ($_SESSION["sessionUserData"]["currency"]=="US"){
					echo " US";}
				else if ($_SESSION["sessionUserData"]["currency"]=="CA"){
					echo " CA";}
				else if ($_SESSION["sessionUserData"]["currency"]=="EUR"){
					echo " EUR";}
				}
				?></td>
                <td align="center" class="formCell"><?php echo $adm_select_txt;?></td>
              </tr> 
			  <?php

if ($usercount == 0){ /* no positions to list */
	if ($mylang == 'lang_french') {  ?>
	<tr><td colspan=\"9\" class=\"formCell\">Désolé, aucun article trouvé.</td></tr>
	<?php  	
	}else{ ?>
	<tr><td colspan=\"9\" class=\"formCell\">Sorry, no items found.</td></tr>
	<?php 	
	}     	
}else{
	echo "<tr>";
	while ($listItem=mysql_fetch_array($result)){
	$item++;
	echo "<td align=\"left\" class=\"formCell\">";
			
			//echo "<a href=\"#\" onClick=\"MM_openBrWindow('lens_specs.php?pkey=$listItem[primary_key]','popup','toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=no,width=350,height=450')\">";
			
			echo "<a target=\"_blank\"  href=\"lens_specs.php?pkey=$listItem[primary_key]\">". $listItem[product_name];
			echo "</a></td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[index_v];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			echo $listItem[coating];
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			if (($listItem[photo]=='None') && ($mylang == 'lang_french')) {
			echo 'Non';}else{
			echo $listItem[photo];
			}
			echo "</td>";
			
			echo "<td  align=\"center\" class=\"formCell\">";
			if (($listItem[polar] == 'None') && ($mylang == 'lang_french')){ 
			echo 'Non';}else{
			echo $listItem[polar];
			}
			echo "</td>";
			
			echo "<td  align=\"right\" class=\"formCell\">";
			$over_range_re=0;
			$over_range_le=0;
			
			if ($_SESSION['PrescrData']['EYE']!="L.E."){
			
				if (($RE_SPHERE>$listItem[sphere_max])||($RE_SPHERE<$listItem[sphere_min])||($RE_CYL<$listItem[cyl_min])){
				$over_range_re=10.00;
				
					if ($mylang=='lang_french'){
					echo "O.D.";
					}else{
					echo "R.E. $";
					}
				
					$over_range=money_format('%.2n',$over_range_re);
					if ($mylang=='lang_french'){
					echo $over_range. '$';
					}else{
					echo $over_range;
					}
					echo "<br>";
				}
			}
			
			if ($_SESSION['PrescrData']['EYE']!="R.E."){
				if (($LE_SPHERE>$listItem[sphere_max])||($LE_SPHERE<$listItem[sphere_min])||($LE_CYL<$listItem[cyl_min])){
					$over_range_le=10.00;
					if ($mylang=='lang_french'){
					echo "O.G.";
					}else{
					echo "L.E. $";
					}
				
					$over_range=money_format('%.2n',$over_range_le);
					if ($mylang=='lang_french'){
					echo $over_range. '$';
					}else{
					echo $over_range;
					}
					echo "<br>";
				}
			}
			echo "</td><td  align=\"center\" class=\"formCell\">";
			
			
			//Pour obtenir le prix, on doit additionner le prix de la monture et des verres
			if ($_SESSION[safety_plan] == 'regular price'){
			$price = $listItem["price"] 		; //   + $_SESSION['PrescrData']['FRAME_SELLING_PRICE'] ;
			}elseif($_SESSION[safety_plan] == 'interco price'){
			$price = $listItem["price_interco"]	; //   + $_SESSION['PrescrData']['FRAME_INTERCO'] ;
			}elseif($_SESSION[safety_plan] == 'discounted price'){
			$price = $listItem["price_discounted"] ; //+ $_SESSION['PrescrData']['FRAME_DISCOUNTED_PRICE'] ;
			}
			
			$price= money_format('%.2n',$price); 
			if ($mylang == 'lang_french') {
			echo $price."$";
			}else{
			echo "$" . $price;
			}
			

			echo "</td><td  align=\"center\" class=\"formCell\">";
			echo "<input type=\"radio\" name=\"product_id\" id=\"product_id\"  onClick=\"CheckSelection();\"  value=\"$listItem[primary_key]\"";
			
			if ($item==1){
				echo  "/>";
				}
			else{
				echo"/>";}
			echo "</td></tr>";
			   }//end of while
}//end of 0 usercount
?>

          </table> <?php
if ($usercount != 0){ 

 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"   onClick=\"history.back(); return false\" target=\"main\"/>&nbsp;";
 echo "<input   name=\"Submit\" type=\"submit\" disabled=\"disabled\" value=\"".$adm_proceed_txt."\";/></div></form>";
			}
			else{
			 echo"<div align=\"center\" style=\"margin:11px\"><input name=\"back\" type=\"button\" value=\"".$adm_backtoform_txt."\"   onClick=\"history.back(); return false\" target=\"main\"/></div></form>";
			}
			
			?>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
 <?php include("footer.inc.php"); ?>
</div><!--END containter-->
<?php

	$_SESSION['PrescrData']['myupload'] = $_POST["uploadhold"];
//////////if there is a new lens stored
	if(($_FILES['myupload']['name'])){
	$target_path = "C:\\cdrive\\websites\\directlens\\holdingfiles\\";
	$target_path = $target_path . basename( $_FILES['myupload']['name']);
	$_SESSION['PrescrData']['myupload'] = basename( $_FILES['myupload']['name']);
	move_uploaded_file($_FILES['myupload']['tmp_name'], $target_path);
	}

//////////if there is already a lens stored...but no new lens
	if((!$_FILES['myupload']['name']) && (($_SESSION['PrescrData']['myupload']) && ($_SESSION['PrescrData']['myupload'] != "none"))){
	///no need to do anything- already done
	}

//////////if there is nothing
	if((!$_FILES['myupload']['name']) && (!$_SESSION['PrescrData']['myupload']) || ($_SESSION['PrescrData']['myupload'] == "none")){
		///no need to do anything
	}

	
?>


</body>
</html>