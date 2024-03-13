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
include "config.inc.php";
include "../includes/getlang.php";

global $drawme;					
$prod_table="ifc_frames_french";
$user_id=$_SESSION["sessionUser_Id"];
if ($mylang == 'lang_french') $prod_table="ifc_frames_french";
//require_once "../upload/phpuploader/include_phpuploader.php";

if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");	

  $queryLab = "SELECT main_lab, password FROM accounts where user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab=mysqli_query($con,$queryLab)	or die ("Could not select items");
  $DataLab=mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
  $LabNum=$DataLab[main_lab];	
  $CurrentPwd = $DataLab[password];
  
  $queryAccesPremium = "SELECT acces_frames_premium, main_lab FROM accounts WHERE user_id ='" . $_SESSION["sessionUser_Id"] . "'";
  $resultAccessPremium=mysqli_query($con,$queryAccesPremium)	or die ("ERROR:".mysqli_error($con)." sql=".$queryAccesPremium);
  $DataAccessPremium=mysqli_fetch_array($resultAccessPremium,MYSQLI_ASSOC);

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
			<form action="credit_history_detail.php" method="post" enctype="multipart/form-data" name="PRESCRIPTION" id="PRESCRIPTION" onSubmit="return validateChoice(this);">
			<div class="loginText">
				<?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
    		</div>
            <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <div id="headerBox" class="header">            	
                        <?php if ($mylang == 'lang_french'){
						echo 'MES CREDITS';
						}else {
						echo 'MY&nbsp;CREDIT&nbsp;HISTORY';
						}?>              
                        </div>
                    </td>
                 </tr>
            </table>	      
       
			<div>
        <table width="770" border="0" align="center" cellpadding="3" cellspacing="0" class="formBox">
         <tr>
           <td bgcolor="#17A2D2" class="tableHead">&nbsp;
               
           </td>
           <td bgcolor="#17A2D2" class="tableHead">&nbsp;</td>
         </tr>
         <tr>
           <td width="100%" align="center" valign="top" class="formCellNosides">


<br>
<?php 
$query     = "SELECT distinct mcred_order_num, mcred_memo_num,   mcred_acct_user_id  from memo_credits_temp WHERE  mcred_acct_user_id = '$user_id'    order by mcred_order_num";
//echo '<br><br>'. $query;
$result    = mysqli_query($con,$query)		or die ("Could not find lab list");
$NbrResult = mysqli_num_rows($result);
?>

<?php if ($mylang == 'lang_french'){
		echo '<p>S&eacute;lectionner une commande dans le menu ci-dessous<br><br><b>(Noter que les crédits traités avant le 2013-10-01 n\'apparaitrons pas dans cet outil).</p>';
		}else {
		echo '<p>Please select an order from the menu below <br><br><b>(Note that credits older than 2013-10-01 might now appear in this menu).</p>';
		}?>

<?php if ($NbrResult >0) {?> 
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
		echo '<p><u>There are currently no credit in your account</u></p>';
		}
 ?>
</form>	
</td>
          <tr>
            
        </table>
	</div>


</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->


</body>
</html>