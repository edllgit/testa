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
include("includes/pw_functions.inc.php");
global $drawme;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />


</head>
<body>
<div id="container">    
   
    <div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">Terms and Conditions of Use </div>
            <div class="messageText" style="line-height:15px">  
            
            <div class="header2">Payment</div>
            <p>The terms are NET, payable on the 15th of next month (15 days after receipt of the statement). Payments by credit card 
            (VISA, Mastercard, AMEX) are accepted.</p>
            
            <div class="header2">Cancellation</div>
            <p>Cancellation of an order is possible as long as the lenses are not in production, otherwise, cancellation
             will result in a fee of 50%.</p>
            
            <div class="header2">RX changing and Redo</div>
            <p>A discount of 50% on invoice can be issued if the number of the first order is register at the second order. To be eligible for a 50% discount,
            we can not find more than 3 changes to the prescription (eg, frame height
            adjustment, sphere, cylinder, axis, addition, pupillary distance).</p>
            
            <div class="header2">Order by phone</div>
            <p>Order by phone and fax is available, costs $ 2.50 will be charged. When taking a telephone order,
            prescription will be repeated in case of prescribing error, a 50% discount will be issued for recovery.</p>
            
            <div class="header2">Non-adapt (only on progressivet)</div>
            <p>Any request for non-adapt must be returned within 90 days of the order date. The number of the first
            order and the reason for non-adapt must be submitted at the second order.</p>
            
            <div class="header2">No refund</div> 

			</div>
        </div>
        <?php include("footer.inc.php"); ?>
	</div>

</div>

</body>
</html>
