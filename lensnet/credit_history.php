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
global $drawme;

$user_id=$_SESSION["sessionUser_Id"];
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
        $(".formBox").dropShadow({left:6, top:6, blur:5, opacity:0.7});
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

<script type="text/javascript" src="../includes/formvalidator.js"></script>
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
      <div class="header">
		  	</div><div class="loginText"></div>
		    <br>
		    <br>
		    <div class="Subheader"></div>

		
</div><!--END rightcolumn-->

             
 <form method="post" name="customer_credit_history" id="customer_credit_history" action="credit_history_detail.php">           
 <br><br><br>

	<div class="bigwelcome" align="center">	
	<?php if ($mylang == 'lang_french'){
		echo 'MES CREDITS';
		}else {
		echo 'MY CREDIT HISTORY';
		}?>
        </div>
<br>

<?php if ($mylang == 'lang_french'){
		echo '<br><p>S&eacute;lectionner une commande dans le menu ci-dessous<br><br><b>(Noter que les crédits traités avant le 2013-10-01 n\'apparaitrons pas dans cet outil).</p>';
		}else {
		echo '<br><p>Please select an order from the menu below <br><br><b>(Note that credits older than 2013-10-28 will not appear in this menu).</p>';
		}?>


<p></b><br><br>

<?php 
$query     = "SELECT distinct mcred_order_num, mcred_memo_num,   mcred_acct_user_id  from memo_credits_temp WHERE  mcred_acct_user_id = '$user_id' order by mcred_order_num";
$result    = mysqli_query($con,$query)		or die ("Could not find lab list");
$nbrResult = mysqli_num_rows($result);
?>


<?php if ($nbrResult >0) {?> 
<select name="mcred_order_num" >
<option value="" selected="selected">
		<?php if ($mylang == 'lang_french'){
		echo 'S&eacute;lectionner un cr&eacute;dit';
		}else {
		echo 'Select a credit';
		}?></option>
   <?php
   while ($DataCredit=mysqli_fetch_array($result,MYSQLI_ASSOC)){
      echo "<option value=\"$DataCredit[mcred_memo_num]\">$DataCredit[mcred_order_num]</option>";
   }?>
</select>

<input type="submit" name="view_detail" value="<?php if ($mylang == 'lang_french'){
		echo 'Voir le d&eacute;tail';
		}else {
		echo 'View Credit Detail';
		}?>" id="view_detail">
<?php }elseif ($mylang == 'lang_french'){
		echo '<p><u>Il n\'y aucun cr&eacute;dit dans votre compte pr&eacute;sentement.</u></p>';
		}else {
		echo '<p><u>There are currently no credits applied in your account</u></p>';
		}
	
	
	 ?>
</form>		
</div>
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>