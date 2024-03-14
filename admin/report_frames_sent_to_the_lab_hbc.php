<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//INCLUSIONS
include("admin_functions.inc.php");
include "../connexion_hbc.inc.php";
//Le fichier getlang est partag� avec le labAdmin..Ne pas modifier!
include "../includes/getlang.php";

session_start();

if ($_SESSION[adminData][username]==""){
	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
	exit();
}

?>
<html>
<head> 
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script language='JavaScript'>
checked = false;
function checkedAll () {
if (checked == false){checked = true}else{checked = false}
	for (var i = 0; i < document.getElementById('update_status').elements.length; i++) {
	document.getElementById('update_status').elements[i].checked = checked;
	}
}
</script>
</head>
<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		$Order_Num_Sans_Espace =  $_POST[order_num];
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace,"\n");
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace,"\r");
		$Order_Num_Sans_Espace =  trim($Order_Num_Sans_Espace," ");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="verification" id="verification" action="credit_reception_hbc.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="4"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial">Reports HBC Frames sent to the lab</font></b></td>
            	</tr>
                
                
				
				
				
				
				

				<tr bgcolor="#DDDDDD">
					<td align="center" valign="middle" nowrap bgcolor="#DDDDDD" ><div style="font-size:14px;" align="center">
					
						
						
						
						
						
                        
                    <div align="center"></div></td>				
					<td nowrap="nowrap">&nbsp;</td>
					<td align="left" nowrap >&nbsp;</td>
				</tr>
                		
			</table>
</form>

<form  method="post" name="update_status" id="" action="#">
<?php 
	$QuerySent="SELECT * FROM orders WHERE frame_sent_saintcath='sent' ORDER BY date_frame_sent_saintcath DESC, user_id ";
	$ResultSent	=	mysqli_query($con,$QuerySent)		or die  ('<strong>Errors occured during the process:<br>'. $QuerySent . mysqli_error($con));
	

	
echo "<table width=\"100%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" class=\"formField\">
<tr bgcolor=\"#000000\"></tr>";
	  echo "<tr>
				<td align=\"center\"><strong>Store</strong></td>
				<td align=\"center\"><strong>Order Number</strong></td>
				<td align=\"center\"><strong>Date Sent to the lab</strong></td>
				<td align=\"center\"><strong>Prescript Lab</strong></td>

				<td align=\"center\"><strong>Tray #</strong></td>
				<td align=\"center\"><strong>Patient</strong></td>
				<td align=\"center\"><strong>Status</strong></td>
				
	        </tr>
				<tr>";		  
$OrderAbleToUpdate = 0;
while ($listItem=mysqli_fetch_array($ResultSent,MYSQLI_ASSOC)){

	/*$queryFrame	="SELECT temple_model_num, color FROM extra_product_orders WHERE order_num= $listItem[order_num]  AND category='Frame'";
	$ResultFrame=mysqli_query($con,$queryFrame)		or die  ('<strong>Errors occured during the process:<br>'. $queryFrame . mysqli_error($con));
	$DataFrame 	=mysqli_fetch_array($ResultFrame,MYSQLI_ASSOC);
	*/
	
	$DateEnvoieLab = substr($listItem[date_frame_sent_saintcath],0,10);
		
	switch($listItem[prescript_lab]){
		
		case '0': 	$Laboratoire ="None";  				break;	
		case '3': 	$Laboratoire ="Saint-Catharines";  	break;	
		case '10':	$Laboratoire ="Swiss";  			break;
		case '21':	$Laboratoire ="Not redirected Yet"; break;
		case '2':	$Laboratoire ="HKO"; break;
		case '4':	$Laboratoire ="GKB";  				break;
		case '73':	$Laboratoire ="KNR";	  			break;
		default:  $Laboratoire="UNKNOWN";		
	}
	

	
echo "<tr>
 		 <td style=\"font-size:16px;\" align=\"center\">$listItem[user_id]</td> 
		 <td style=\"font-size:16px;\" align=\"center\">$listItem[order_num]</td> 
		 <td style=\"font-size:16px;\" align=\"center\">$DateEnvoieLab</td>
		 <td style=\"font-size:16px;\" align=\"center\">$Laboratoire</td>
		
		 <td style=\"font-size:16px;\" align=\"center\">$listItem[tray_num]&nbsp;</td>
		 <td style=\"font-size:16px;\" align=\"center\">$listItem[order_patient_first]&nbsp;$listItem[order_patient_last]</td>
		 <td style=\"font-size:16px;\" align=\"center\">$listItem[order_status]</td>
	 </tr>";
}//END WHILE
				  
				  
echo "</table>";


 if ($OrderAbleToUpdate > 0){
 
				 if ($OrderAbleToUpdate > 0){
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }else{
				 echo "<p align=\"center\"><input name=\"update_status\" style=\"font-size:14px;\"  type=\"submit\" id=\"rpt_search\" disabled=\"disabled\" value=\"Update to Request Received\" class=\"formField\"></p>";
				 }				  
 }else{
 $noOrdertoUpdate =true;
 }
 

echo "</form><br><br><br>";




if($DisplayUpdateDetail){
//On bati le form pour passer les updates par un champ cach�
echo '<p align="center">Update saved</p>';

}

?>

            

</td>
	  </tr>
</table>
  <p>&nbsp;</p>
</body>
</html>