<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";

session_start();
if($_SESSION["sessionUser_Id"]=="")
	header("Location:loginfail.php");
include("includes/pw_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$query="SELECT * FROM royalty WHERE user_id = '$_SESSION[sessionUser_Id]'  order by royalty_date";
$result=mysql_query($query)	or die ("Could not find salespeople");
$salescount=mysql_num_rows($result);

$querySalesperson = "SELECT * FROM accounts  WHERE  user_id  = '$_SESSION[sessionUser_Id]'";
$resultSalesPerson=mysql_query($querySalesperson)	or die ("Could not find salespeople");
$SalespersonData=mysql_fetch_array($resultSalesPerson);
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
				} echo 'Detail for Company'. ':&nbsp;' . $SalespersonData[company];?></div>
    <div class="loginText"><?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?></div>
    
    <br>
    
		  <?php
		   
		  if ($salescount == 0){/* no salespeople */
		  	echo "<div class=\"formText\" align=\"center\"><b>&nbsp;No details.</b></div>";
		}else{
			echo "<table width=\"500\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\">
				<tr>
					<td colspan=\"9\" bgcolor=\"#000099\" class=\"tableHead\">";
					if($message!="") echo "$message"; else echo "&nbsp;";
					echo "</td>
				</tr>
				<tr>
					<th align=\"left\"  class=\"formCellNosides\"><div align=\"left\">Order Num</div></th>
					<th align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">Product Name</div></th>
					<th align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">Amount</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Date</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Lens Category</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Coating</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Polarized</div></th>
					<th align=\"center\" nowrap class=\"formCellNosides\"><div align=\"center\">Transitions</div></th>
					</tr>";
			while($RoyaltyData=mysql_fetch_array($result)){
				

				
				echo "<tr>
					<td align=\"left\"        class=\"formCellNosides\"><div align=\"left\">$RoyaltyData[order_num]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">";
					
					if (strlen($RoyaltyData[product_name]) > 65)
					{
						echo substr($RoyaltyData[product_name],0,64);
					}else{
						echo $RoyaltyData[product_name];	
					}
					
					
					echo "</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">$$RoyaltyData[royalty_amount]</div></td>
					<td align=\"left\" nowrap class=\"formCellNosides\"><div align=\"left\">$RoyaltyData[royalty_date]</div></td>";
					
					$couleurLensCategory =false;
					
					if ($RoyaltyData[lens_category] == 'prog cl')  
					$couleurLensCategory = true;
					
					if ($RoyaltyData[lens_category] == 'prog ff')  
					$couleurLensCategory = true;
					
					if ($RoyaltyData[lens_category] == 'prog ds')  
					$couleurLensCategory = true;
					
					
					if ($couleurLensCategory){
					echo "<td style=\"background-color:#FF0;\"  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[lens_category]";
					}else{
					echo "<td  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[lens_category]";
					}
				
				
				$couleurCoating =false;
				if ($RoyaltyData[coating] == 'Dream AR')  
					$couleurCoating = true;
					
				if ($RoyaltyData[coating] == 'Smart AR')  
					$couleurCoating = true;	
					
				if ($RoyaltyData[coating] == 'ITO AR')  
					$couleurCoating = true;	
					
				if ($RoyaltyData[coating] == 'Xlr')  
					$couleurCoating = true;
					
				if ($RoyaltyData[coating] == 'MultiClear AR')  
					$couleurCoating = true;
					
				if ($RoyaltyData[coating] == 'Aqua Dream AR')  
					$couleurCoating = true;
					
				if ($RoyaltyData[coating] == 'CrizalF')  
					$couleurCoating = true;
					
				if ($RoyaltyData[coating] == 'Blue AR')  
					$couleurCoating = true;
					

					if ($couleurCoating){
					echo "<td style=\"background-color:#FF0;\"  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[coating]";
					}else{
					echo "<td  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[coating]";
					}
					
					
					
				$couleurPolar =false;
				
				if ($RoyaltyData[polar] == 'Grey')  
					$couleurPolar = true;
					
				if ($RoyaltyData[polar] == 'Brown')  
					$couleurPolar = true;
					
				if ($RoyaltyData[polar] == 'Green')  
					$couleurPolar = true;	
				
				if ($RoyaltyData[polar] == 'G-15')  
					$couleurPolar = true;	
				
	   			 if ($RoyaltyData[polar] == 'G15')  
					$couleurPolar = true;	
	    
	    
	    		if ($couleurPolar){
				echo "<td style=\"background-color:#FF0;\"  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[polar]";
				}else{
				echo "<td  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[polar]";
				}
				
				
				$couleurPhoto =false;
				
				if ($RoyaltyData[photo] == 'Drivewear')  
					$couleurPhoto = true;
					
				if ($RoyaltyData[photo] == 'Grey')  
					$couleurPhoto = true;
					
				if ($RoyaltyData[photo] == 'Brown')  
					$couleurPhoto = true;
				
				if ($RoyaltyData[photo] == 'Yellow')  
					$couleurPhoto = true;
	    		
				if ($RoyaltyData[photo] == 'Violet')  
					$couleurPhoto = true;
				
				if ($RoyaltyData[photo] == 'Pink')  
					$couleurPhoto = true;
				
	    	 	if ($RoyaltyData[photo] == 'Orange')  
					$couleurPhoto = true;
					
				if ($RoyaltyData[photo] == 'Green')  
					$couleurPhoto = true;         
	    		 	
	    		 if ($RoyaltyData[photo] == 'Blue')  
					$couleurPhoto = true;         
	    		 	
				if ($RoyaltyData[photo] == 'Extra Active Grey')  
					$couleurPhoto = true;         
	    		 	
				        
				if ($couleurPhoto){
				echo "<td style=\"background-color:#FF0;\"  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[photo]";
				}else{
				echo "<td  align=\"left\" class=\"formCellNosides\" nowrap ><div align=\"center\">$RoyaltyData[photo]";
				}
					
				echo "</div></td></tr>";
			}
			
			
			$queryTotalPromo  = "SELECT SUM( royalty_amount ) as GrandTotal  FROM royalty WHERE  user_id = '" . $_SESSION["sessionUser_Id"] . "'";
			$resultTotalPromo = mysql_query($queryTotalPromo)	or die ("An error occured, Please contact us.");
			$DataTotalPromo   = mysql_fetch_array($resultTotalPromo);
			
			
		
			echo '<tr><td  align="right" colspan="3">Total:</td><td>$'.$DataTotalPromo[GrandTotal].'</td></tr>';
			echo '</table>';
			
			

		}
		  ?>

 <p align="center"><a style="text-decoration:none" href="holiday_promo.php">Back to 
		 <?php if ($mylang == 'lang_french'){
				echo 'Chaleur E$tivale!';
				}else {
				echo 'Summer Si$$le! ';
				}
				?> Promo</a></p>
         
 <p align="center"><img width="400"  src="https://www.direct-lens.com/images/legend.jpg" /></p>
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
$salesperson_id = 'Company Detail Page';
$visit_date     = date("Y-m-d"); 
$the_user_id    = $_SESSION["sessionUser_Id"];
$queryVisit     = "INSERT INTO royalty_visit (user_id, salesperson_id, visit_date, ip_address, ip_address2) VALUES ('$the_user_id', '$salesperson_id', '$visit_date', '$ip_address','$ip_address2')";
$resultVisit    = mysql_query($queryVisit)	or die (".");
?>