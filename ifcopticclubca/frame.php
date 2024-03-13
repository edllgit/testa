<?php 
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

//Démarrer la session
session_start();

//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
require_once(__DIR__.'/../constants/url.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include "config.inc.php";

global $drawme;
$prod_table="ifc_frames_french";

//require_once "../upload/phpuploader/include_phpuploader.php"; 

$_SESSION['svFormVars']="";//RESET FORM VARS
$_SESSION['prFormVars']="";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	
if($_SESSION["account_type"]=="restricted"){
	header("Location:order_history.php");
}

$where_clause=" WHERE active='1' ";
if ($_POST['model']!="none"){
	$where_clause=" WHERE model='".$_POST[model]."'";	
}else {
	$where_clause = " WHERE ifc_frames_french.active = 1 ";
}

$AfficherPageCommande = true;
$queryBasket = "SELECT COUNT(*) as nbrResult FROM orders WHERE order_num = -1 AND user_id = '".  $_SESSION["sessionUser_Id"] . "' AND order_product_type NOT IN ('exclusive') AND order_status not in ('pre-basket')";
$ResultBasket=mysqli_query($con,$queryBasket)		or die ("Erreur durant le chargement des modeles disponibles");
$DataBasket=mysqli_fetch_array($ResultBasket,MYSQLI_ASSOC);

$queryAccesPremium = "SELECT acces_frames_premium from accounts WHERE user_id ='" . $_SESSION["sessionUser_Id"] . "'";
$resultAccessPremium=mysqli_query($con,$queryAccesPremium)	or die ("ERROR:".mysqli_error($con)." sql=".$queryAccesPremium);
$DataAccessPremium=mysqli_fetch_assoc($resultAccessPremium);
	
if ($_SESSION["CompteEntrepot"] == 'yes')
$where_clause.= " AND display_entrepot= 'yes' AND frame_on_sale <> 'yes' ";
else
$where_clause.= " AND display_on_ifcca = 'yes' AND frame_on_sale <> 'yes' ";
			

if ($_POST['collection'] <> 'none'){	 
	if ($_POST[collection]=='FUGLIES'){
		$where_clause.= " AND ifc_frames_french.misc_unknown_purpose IN ('FUGLIES_A','FUGLIES_B','FUGLIES_C') ";
	}elseif ($_POST[collection]=='MILANO 6769'){
		$where_clause.= " AND ifc_frames_french.misc_unknown_purpose IN ('MILANO 6769 CONSIGNE','MILANO 6769 BRERA','MILANO 6769') ";
	}elseif ($_POST[collection]=='ISEE'){
		$where_clause.= " AND ifc_frames_french.misc_unknown_purpose IN ('ISEE','ISEE 2') ";
	}else{
		$where_clause.= " AND ifc_frames_french.misc_unknown_purpose = '$_POST[collection]'";
	}
}	
					
mysqli_query($con,"SET CHARACTER SET UTF8");

$query="SELECT  * FROM ifc_frames_french ".$where_clause."  ORDER BY model, color"; 
//echo '<br>'. $query;
$result=mysqli_query($con,$query)		or die ("Could not select products because ".mysqli_error($con)." query=".$query);
$prodCount=mysqli_num_rows($result);	
$_SESSION['lens_category']=$_POST['lens_category'];

if ($_SESSION["sessionUser_Id"]=='entrepotifc'){
	//echo 'Compteentrepot:'. $_SESSION["CompteEntrepot"].'<br>';
	//echo $query;	
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo $sitename;?></title>

<script type="text/javascript" src=" https://ajax.googleapis.com/ajax/libs/jquery/1.6/jquery.js"></script>
<script src="js/popup.js" type="text/javascript"></script>
<script type="text/javascript">

function slideSwitch() {
    var $active = $('#slideshow IMG.active');

    if ( $active.length == 0 ) $active = $('#slideshow IMG:last');

    // use this to pull the images in the order they appear in the markup
    var $next =  $active.next().length ? $active.next()
        : $('#slideshow IMG:first');

    // uncomment the 3 lines below to pull the images in random order
    
    $active.addClass('last-active');

    $next.css({opacity: 0.0})
        .addClass('active')
        .animate({opacity: 1.0}, 100, function() {
            $active.removeClass('active last-active');
        });
}

$(function() {
    setInterval( "slideSwitch()", 500 );
});

</script>

<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />

<style type="text/css">

body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
#slideshow {
	position:relative;
	width:550px;
	height:250px;
}
#slideshow IMG {
	position:absolute;
	top:10;
	left:10;
	z-index:8;
	opacity:0.0;
}
#slideshow IMG.active {
    z-index:10;
    opacity:1.0;
}

#slideshow IMG.last-active {
    z-index:9;
}
</style>

<script language="JavaScript" type="text/javascript">

<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}
//-->
</script>

<link href="products.css" rel="stylesheet" type="text/css" />
<link href="css/popup.css" rel="stylesheet" type="text/css" />
</head>


<body>
<div id="backgroundPopup"></div>
<div id="popupForm">
	<a id="popupFormClose">x</a>    
    <div id="returnMessage">x</div>		   
</div>

<div id="container">
  <div id="masthead">
  <img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/ifc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  	<?php include("includes/sideNav.inc.php");	?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
	<div class="loginText">
		<?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?> 
	</div>
    
    
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
    
   
	<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
    	<tr>
        	<td>
            	<div id="headerBox" class="header" style="width:400px">            	
                <?php if ($mylang == 'lang_french'){?>
                    Choisissez votre monture
                <?php }else {?>
                    Choose Your Frame
                <?php }?>                
                </div>
            </td>
         </tr>
    </table>
    
     <?php if ( $_SESSION["product_line"]=='safety'){//Client de l'entrepot est connecté dans un compte SAFE, on doit l'aviser ?>
  <div>
           	<?php
					if ($mylang == 'lang_french') { 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>Vous êtes présentement connectés dans un compte SAFE:' . $_SESSION["sessionUser_Id"].', veuillez vous reconnecter dans <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					}else{ 
						echo '<p style="background-color:#E5ABAC"; align="center"><strong>You are currently logged in a SAFETY account: ' . $_SESSION["sessionUser_Id"].', Please re-connect in <a href="'.constant('DIRECT_LENS_URL').'/ifcopticclubca/login.php">ifc.ca</a></strong></p>';
					} 		

			?>
   </div>


<?php 
}//End IF Client est dans un compte SAFE   ?>
	      
    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
        <tr>
            <td width="57%" bgcolor="#17A2D2" class="tableHead"> 
                <?php echo $prodCount;?>                 
                <?php if ($mylang == 'lang_french'){?>
                    Montures disponibles
                <?php }else {?>
                    Frames Available
                <?php }?>                    
            </td>
            <td width="43%" bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
        </tr>
    </table>
    
    
    <?php if ($AfficherPageCommande){ ?> 
<div> 
        <?php
		//display table of products
		
		if ($_POST['lens_category']=='sv') 
		$url="sv_frame_form.php"; 
		elseif ($_POST['lens_category']=='sv-entrepot') 
		$url="sv_frame_form.php";
		elseif ($_POST['lens_category']=='sv-glass') 
		$url="sv_frame_form.php";
		elseif ($_POST['lens_category']=='ireader-entrepot') 
		$url="sv_frame_form.php";
		elseif ($_POST['lens_category']=='iaction-sv-entrepot') 
		$url="sv_frame_form.php";
		else $url="prescription_frame.php";

			if ($prodCount==0){
				echo "<table width=\"700\" border=\"0\" cellpadding=\"0\" align=\"center\">";
				
				echo "<tr><td align=\"center\" valign=\"middle\"><div class=\"home_features_header\">Sorry, no items found.</div></td></tr>";
				echo "</table>";
			}else{
				echo "<table width=\"700\"  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">";
				$count=0;
				while ($productData=mysqli_fetch_array($result,MYSQLI_ASSOC)){			 
					if ($count==0)
						echo "<tr valign=\"top\">";
					$count++;
					
					
					//$productData[prod_tn]="https://".constant('AWS_S3_BUCKET').".s3.amazonaws.com/ifcopticclub/prod_images/".$productData[image];
					$productData[prod_tn]="http://www.direct-lens.com/ifcopticclub/prod_images/".$productData[image];
					
					$productData['model']=stripslashes($productData['model']);
					$productData['price_us']=stripslashes($productData['price_us']);
					echo "<td width=\"33%\" align=\"center\">";

					echo "<div class=\"item-box\">";
					echo "<div class=\"product-box\"><a href=\"".$url."?prod=$productData[ifc_frames_id]\"><img src=\"$productData[prod_tn]\" alt=\"$productData[model]\" border=\"0\" title=\"$productData[model]\" width=\"190\"></a></div>";
					
					echo "<a href=\"#\" border=\"0\" onClick=\"doPopup($productData[ifc_frames_id])\" id=\"$productData[ifc_frames_id]\"><img src=\"http://direct-lens-public.s3.amazonaws.com/ifcopticclub.ca/design_images/360view.gif\" width=\"91\" height=\"22\" hspace=\"5\" border=\"0\" align=\"right\" style=\"margin-top:12px\"/></a>";
					echo "<div class=\"product-name\"><a href=\"".$url."?prod=$productData[ifc_frames_id]\">$productData[upc]</a></div>";
					
					if ($productData['misc_unknown_purpose']=="FREE"){?> 
						<img src="http://www.direct-lens.com/ifcopticclubca/design_images/free_small.png" 
                        width="40" height="40" border="0" align="right">
					<?php 
                    }
					else if ($productData['misc_unknown_purpose']=="FREE PLUS"){ ?>
						<img src="http://www.direct-lens.com/ifcopticclubca/design_images/free_plus_small.png" 
                        width="40" height="40" border="0" align="right">
    				<?php 
					}
					else if ($productData['misc_unknown_purpose']=="PREMIUM"){ ?>
						<img src="http://www.direct-lens.com/ifcopticclubca/design_images/premium_small.png" 
                        width="40" height="40" border="0" align="right">
    				<?php 
					}
					else if ($productData['misc_unknown_purpose']=="PREMIUM PLUS"){ ?>
						<img src="http://www.direct-lens.com/ifcopticclubca/design_images/premium_plus_small.png" 
                   		width="40" height="40" border="0" align="right">    
					<?php 
					} 

		if ($productData['express']=="1"){ ?>
			<img src="http://www.direct-lens.com/ifcopticclubca/design_images/logo-express.gif" width="40" height="40" border="0" align="right">
<?php  } 
				if ($mylang == 'lang_french'){			
					echo "<div class=\"priceText\">TYPE :  $productData[type]</div>";
					echo "<div class=\"priceText\">GENRE : $productData[gender]</div>";
					echo "<div class=\"priceText\">MATIERE : $productData[material]</div>";
					echo "<div class=\"priceText\">COULEUR : $productData[color]</div>";
					echo "<div class=\"priceText\">TAILLE : $productData[boxing]&nbsp;&nbsp;A: $productData[frame_a]</div>";
					echo "<div class=\"priceText\" style=\"padding-top:4px\"><a href=\"".$url."?prod=$productData[ifc_frames_id]\">CHOISIR CETTE MONTURE</a></div>";				
                } else {
					echo "<div class=\"priceText\">TYPE: $productData[type_en]</div>";
					echo "<div class=\"priceText\">GENDER: $productData[gender_en]</div>";
					echo "<div class=\"priceText\">MATERIAL: $productData[material_en]</div>";
					echo "<div class=\"priceText\">COLOUR: $productData[color_en]</div>";
					echo "<div class=\"priceText\">SIZE: $productData[boxing] &nbsp;&nbsp;A: $productData[frame_a]</div>";
					echo "<div class=\"priceText\" style=\"padding-top:4px\"><a href=\"".$url."?prod=$productData[ifc_frames_id]\">CHOOSE THIS FRAME</a></div>"; 
                }  		
					echo "</div>";

					echo "</td>";
					if (($count%3)==0){
						echo "</tr>";
						$count=0;
					}
				}//END WHILE
				
			if ($count==1)
				echo "<td width=\"33%\" align=\"center\">&nbsp;</td><td width=\"34%\" align=\"center\">&nbsp;</td></tr>";
			if ($count==2)
				echo "<td width=\"34%\" align=\"center\">&nbsp;</td></tr>";

			echo "</table>";
			
	
		}//END IF PROD COUNT
		
?>

                       </td></tr>
               </table>
               
<?php }//END IF AfficherPageCommande ?>
             </div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->

</body>
</html>
