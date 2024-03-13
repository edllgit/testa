<?php 
require_once('../Connections/directlens.php'); 
include "../includes/getlang.php";
?>
<?php
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

//Search by Order number
if($_POST["rpt_search"]=="search by order number"){
	$rptQuery="SELECT orders.*, accounts.company
	FROM orders, accounts
	WHERE  orders.user_id = accounts.user_id AND order_num =$_POST[order_num]";
}
//echo $rptQuery;



//var_dump($_POST);
//echo '<br><br>';
$LongeurOrdernum = strlen($_POST[the_order_num]);

if(($_POST["Submitbtn"]=="Update Redo Reason") && ($LongeurOrdernum == 7)){
	//echo '<br>Passe Update Redo Reason';
	//echo '<br>Order Number:'. $_POST["the_order_num"];
	$New_Redo_Reason = $_POST["redo_reason_id"];
	$ORDERNUM	     = $_POST[the_order_num];
	
	$queryUpdate="UPDATE  orders	SET   redo_reason_id = $New_Redo_Reason	WHERE order_num   =  $ORDERNUM";	
	//echo '<br>'. $queryUpdate;
	$result     = mysql_query($queryUpdate) or die ("Could not select items");// EN COMMENTAIRE POUR LE MOMENT
	
	$queryRedo    = "SELECT redo_reason_en FROM redo_reasons WHERE redo_reason_id = $New_Redo_Reason";
	//echo '<br>'. $queryRedo;
	$resultRedo   = mysql_query($queryRedo) or die ("Could not select items");// EN COMMENTAIRE POUR LE MOMENT
	$DataRedo     = mysql_fetch_array($resultRedo);

	$Changement 	  = "Redo reason updated to ". $DataRedo[redo_reason_en];
	$todayDate 		  = date("Y-m-d g:i a");// current date
	$currentTime 	  = time($todayDate); //Change date into time
	$timeAfterOneHour = $currentTime-((60*60)*4);
	$datecomplete	  = date("Y-m-d H:i:s",$timeAfterOneHour);
	
	$queryRedoPwd    = "SELECT * FROM access_redo WHERE password = $_POST[redo_password]";
	$resultRedoPwd   = mysql_query($queryRedoPwd) or die ("Could not select items");// EN COMMENTAIRE POUR LE MOMENT
	$DataRedoPws     = mysql_fetch_array($resultRedoPwd);
	
	$queryHistorique = "INSERT INTO status_history (order_num, order_status, update_time, redo_approved_by) 
	VALUES($ORDERNUM, '$Changement','$datecomplete', '$DataRedoPws[name]')";
	//echo '<br>'. $queryHistorique;
	$resultHistorique     = mysql_query($queryHistorique) or die ("Could not select items");// EN COMMENTAIRE POUR LE MOMENT

	
	$Confirmation_Message = "$Changement";
}
?>
<html>
<head>
<title>Change the redo reason of an order</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />

</script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
window.onload = function() {
document.getElementById("redo_password").onblur = function() {
var xmlhttp;
var redo_password=document.getElementById("redo_password");
if (redo_password.value != "")
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
xmlhttp.send("redo_password="+encodeURIComponent(redo_password.value));
document.getElementById("status").innerHTML="Vérification en cours...";
}
};
};
</script> 
</head>

<body>
<table border="0" cellspacing="0" cellpadding="0" width="95%">
<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="Who_product_redirected1" id="Who_product_redirected1" action="change_redo_reason.php">
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField">
            	<tr bgcolor="#000000">
            		<td align="center" colspan="6"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo 'Change the redo reason of an order'; ?></font></b></td>
            		</tr>

				<tr align="center" bgcolor="#DDDDDD">
					<td nowrap="nowrap">
					Order number&nbsp;&nbsp;<input name="order_num" type="text" id="order_num" size="25" class="formField">&nbsp;&nbsp;&nbsp;
                    <input name="submit" type="submit" id="submit" value="<?php echo 'Search by order number'; ?>" class="formField">
                    <input name="rpt_search" type="hidden" id="rpt_search" value="search by order number" class="formField"></div></td>
				</tr>


			
</form>
</table>


<?php
if ($Confirmation_Message <> '')
echo '<p align="center">'.$Confirmation_Message.' for order '.$ORDERNUM.'. Don\'t forget to update the rebate amount accordingly.</p>';
 ?>

<form method="post" name="update_redo_reason" id="update_redo_reason" action="change_redo_reason.php" >
<?php 		
if ($rptQuery!=""){
	$rptResult=mysql_query($rptQuery) or die  ('I cannot select items because: ' . mysql_error());
	$usercount=mysql_num_rows($rptResult);
	$rptQuery="";
}
	

if (($usercount != 0) && ($_POST["rpt_search"] <> '')){//some products were found
	echo "<br><table width=\"90%\" border=\"1\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\" >";
	echo "<tr>
			<th width=\"8%\" align=\"center\">Order Num</th>
			<th width=\"33%\" align=\"center\">Customer</th>
			<th width=\"15%\" align=\"center\">Patient</th>
			<th width=\"15%\" align=\"center\">Ref Num</th>
			<th width=\"10%\" align=\"center\">Status</th>
			<th width=\"30%\" align=\"center\">Current Redo Reason</th>
		 </tr>";

	while ($listItem=mysql_fetch_array($rptResult)){

$queryRedoReason  = "SELECT * FROM redo_reasons WHERE redo_reason_id = ". $listItem[redo_reason_id];
//echo '<br>'. $queryRedoReason ;
$resultRedoReason = mysql_query($queryRedoReason) or die  ('I cannot select items because: ' . mysql_error());
$DataRedoReason   = mysql_fetch_array($resultRedoReason);

	echo "<tr>
			<td align=\"center\">".$listItem[order_num]."</td>
			<td align=\"center\">".$listItem[company]."</td>
			<td align=\"center\">".$listItem[order_patient_first] . ' ' . $listItem[order_patient_last]."</td>
			<td align=\"center\">".$listItem[patient_ref_num] ."</td>
			<td align=\"center\">".$listItem[order_status] ."</td>
			<td align=\"center\">".$DataRedoReason[redo_reason_en]."</td>
		</tr>";
	$order_num = $listItem[order_num];
	$LeStatus = $listItem[order_status];
	
	}//END WHILE
	
	echo "</form></table>";

}else{
	if (($_POST["rpt_search"] <> '') && ($Confirmation_Message ==''))
	echo "<div class=\"formField\">".'No order found'."</div>";
}//END USERCOUNT CONDITIONAL


if ($LeStatus == 'cancelled'){
	echo '<div align="center"><p>Since this order is either cancelled, we cannot change the redo reason.</p></div>';
	exit();
}




if($_POST["rpt_search"]=="search by order number"){
echo '<div align="center"><br><p>1- Select the new redo reason:&nbsp;';?>
<select name="redo_reason_id" class="form-control"  id="redo_reason_id">             
<?php
  		$queryRedo="SELECT * FROM redo_reasons ORDER by redo_reason_number"; /* select all openings */
		$resultRedo=mysql_query($queryRedo)			or die ("Could not select items");

		 while ($DataRedoReason=mysql_fetch_array($resultRedo)){
			 echo "<option value=\"$DataRedoReason[redo_reason_id]\"";
			 echo ">";
		 if ($mylang == 'lang_french'){
			$name=stripslashes($DataRedoReason[redo_reason_fr]);
			echo "$name&nbsp;($DataRedoReason[rebate_percentage])</option>";
			}else {
			$name=stripslashes($DataRedoReason[redo_reason_en]);
			echo "$name&nbsp;($DataRedoReason[rebate_percentage])</option>";
			}
		
		 }
			?>
     </select></p>
    </div> 
    <br>
    <div align="center">
    <p>2- Type your employee password:&nbsp;
     <input  name="redo_password" title="Please Call Charles if you don't have an employee password" alt="Please Call Charles if you don't have an employee password"  type="password" class="formField2" id="redo_password" value="" size="6" maxlength="6" max="6">
                        <input name="validate_pwd" type="button" id="validate_pwd" autocomplete="off" class="formField2" value="Valider">
                         <span id="status"></span></p>
                         
    </div>
    <br>
    
    <div align="center">
    <input name="Submitbtn" type="submit" id="Submitbtn" class="formField2" value="Update Redo Reason" disabled>
    <input name="rpt_search" type="hidden" id="rpt_search" value="update redo reason" class="formField">
    <input name="the_order_num" type="hidden" id="the_order_num" value="<?php echo $order_num; ?>" class="formField">
    </div>

 <?php }//End If form has been submitted ?>

</td>
	  </tr>
</table>

</form>	
  <p>&nbsp;</p>
</body>
</html>