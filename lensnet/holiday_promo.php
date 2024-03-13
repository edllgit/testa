<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$query="SELECT * FROM salespeople WHERE acct_user_id = '{$_SESSION[sessionUser_Id]}' ORDER BY removed, last_name, first_name";
$result=mysql_query($query)	or die ("Could not find salespeople");
$salescount=mysql_num_rows($result);
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
<div id="rightColumn"> <div class="header">
    	<?php if ($mylang == 'lang_french'){
				echo 'Chaleur e$tivale! ';
				}else {
				echo 'Summer Si$$le! ';
				}
		?>
                </div><div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
		  <?php
		    echo '<p align="center">To view the detail for only one salesperson click on this person name.<br> To view all orders that qualify for the promotion, click on the total amount</p>';
		 
			echo "<table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
				<tr>
					<td colspan=\"4\" bgcolor=\"#000099\" class=\"tableHead\">";
					if($message!="") echo "$message"; else echo "&nbsp;";
					echo "</td>
				</tr>
				<tr>
				<th align=\"left\"  class=\"formCellNosides\"><div align=\"left\">".$lbl_salespersonid_txt."</div></th>
					<th align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">Name</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Promo Amount for June</div></th>
					</tr>";
			while($salesData=mysql_fetch_array($result)){
				
				$queryAmountPromo  = "SELECT SUM( royalty_amount ) as TotalpourCesalesperson FROM royalty WHERE  salesperson_id  = '$salesData[sales_id]' and user_id = '" . $_SESSION["sessionUser_Id"] . "'";
				$resultAmountPromo = mysql_query($queryAmountPromo)	or die ("An error occured, Please contact us.");
				$DataAmountPromo=mysql_fetch_array($resultAmountPromo);
				
				if ($DataAmountPromo[TotalpourCesalesperson] == 0)
				$DataAmountPromo[TotalpourCesalesperson]=0;
				
				$totalSalesPerson = "$$DataAmountPromo[TotalpourCesalesperson]";	

				
				
				echo "<tr>
					<td align=\"left\"        class=\"formCellNosides\"><div align=\"left\">$salesData[sales_id]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">
					<a href=\"view_promo_detail_id.php?user_id=".$_SESSION["sessionUser_Id"]."&salespersonid=$salesData[sales_id]\">$salesData[first_name] $salesData[last_name]</a></div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"center\">$totalSalesPerson";
					echo "</div></td></tr>";
			}
			
			$queryTotalPromo  = "SELECT SUM( royalty_amount ) as GrandTotal  FROM royalty WHERE  user_id = '" . $_SESSION["sessionUser_Id"] . "'";
			$resultTotalPromo = mysql_query($queryTotalPromo)	or die ("An error occured, Please contact us.");
			$DataTotalPromo   = mysql_fetch_array($resultTotalPromo);
			
			
			
			echo '<tr><td  align="right" colspan="2">Total:</td><td><a style="text-decoration:none;" href="view_promo_detail_company.php">$'.$DataTotalPromo[GrandTotal].'</a></td></tr></table>';


			echo "</table>";

	
		  ?>



 </div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
</body>
</html>
<?php
		
$ip_address	 	= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
$ip_address2 	= $_SERVER['HTTP_X_FORWARDED_FOR'];	
$salesperson_id = 'Main/ Promotion Summary Page';
$visit_date     = date("Y-m-d"); 
$the_user_id    = $_SESSION["sessionUser_Id"];
$queryVisit     = "INSERT INTO royalty_visit (user_id, salesperson_id, visit_date, ip_address, ip_address2) VALUES ('$the_user_id', '$salesperson_id', '$visit_date', '$ip_address','$ip_address2')";
$resultVisit    = mysql_query($queryVisit)	or die (".");
?>