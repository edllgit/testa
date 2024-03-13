<?php /*?><?php
session_start();
//ini_set('display_errors',1); 
// error_reporting(E_ALL);
 
//include('../includes/phpmailer_email_functions.inc.php');
require_once('../includes/class.ses.php');
//if ($_SESSION["labAdminData"]["username"]==""){
//	echo "You are not logged in. Click <a href='index.htm'>here</a> to login.";
//	exit();
//}
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");


$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];


if ($_POST[customer] <> ''){
	$query = "INSERT INTO payment_history (amount, date_received, date_recorded, customer, check_account, deposited_date,deposed_by,comment,who_fill_form) 
	VALUES('$_POST[amount]','$_POST[date_received]','$_POST[date_recorded]','$_POST[customer]','$_POST[check_account]','$_POST[deposited_date]','$_POST[deposed_by]','$_POST[comment]','$_POST[who_fill_form]')";
	$result=mysql_query($query)		or die ("Could not create record ".mysql_error(). $query );

	$queryAccount = "Select company, account_rebate from accounts WHERE primary_key = ".$_POST[customer];
	$resultAccount=mysql_query($queryAccount)		or die ("Could not create record ".mysql_error(). $queryAccount );
	$DataAccount=mysql_fetch_array($resultAccount);

	$message="Un nouveau paiement par cheque est dans le systeme: \r\n";
	$message.="Date recu: $_POST[date_received]\r\n";
	$message.="Date enregistre dans le systeme: $_POST[date_recorded]\r\n";
	$message.="Client #: $_POST[customer], $DataAccount[company] \r\n";
	$message.="Montant: $_POST[amount]$\r\n";
	$message.="Account: $_POST[check_account]\r\n";
	$message.="Date depose: $_POST[deposited_date]\r\n";
	$message.="Commentaire: $_POST[comment]\r\n";
	$message.="Qui a remplis le formulaire: $_POST[who_fill_form]\r\n";
	$message.="Rabais de ce client: $DataAccount[account_rebate]%\r\n";
	$send_to_address = array('rapports@direct-lens.com');
	$curTime= date("m-d-Y");	
	$to_address=$send_to_address;
	$from_address='donotreply@entrepotdelalunette.com';
	$subject='Nouveau paiement par cheque :'.$curTime;
	$response=office365_mail($to_address, $from_address, $subject, null, $message);
	
	echo '<p align="center">Payment recorded successfully</p>';
}


?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/dhtmlxcalendar.css"></link>
<link rel="stylesheet" type="text/css" href="../includes/dhtml/codebase/skins/dhtmlxcalendar_dhx_skyblue.css"></link>
<script src="../includes/dhtml/codebase/dhtmlxcalendar.js"></script>
<script>

var myCalendar;
function doOnLoad() {
    myCalendar = new dhtmlXCalendarObject(["date_received", "deposited_date"]);
}

</script>

</head>

<body onLoad="doOnLoad();">
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		
$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y-m-d", $ladate);
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="payment_form" id="payment_form" action="payment_notification.php">
            <table bgcolor="#CCCCCC" border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td align="center" colspan="4"  class="tableHead"> 
		<?php if ($mylang == 'lang_french'){
		echo 'ENREGISTRER UN CHEQUE';
		}else {
		echo 'RECORD A CHECK WE RECEIVED';
		}
	?></td>
              </tr>


    <tr>
              <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Laboratoire du client';
		}else {
		echo 'Main Lab of customer';
		}
	?></td><td> 
            	
				<select OnChange="document.payment_form.submit();" name="lab" class="formField">
					<option  value="" selected="selected">Select Lab</option>
					<?php
	$query="select primary_key, lab_name from labs  WHERE primary_key not in (11,15,19,8,12,23,25,26,30,10,35)order by lab_name";
	$result=mysql_query($query)		or die ("Could not find lab list");
	while ($labList=mysql_fetch_array($result)){
		echo "<option value=\"$labList[primary_key]\"";
		if ($_POST[lab] == $labList[primary_key]) echo ' selected';
		echo ">$labList[lab_name]</option>";
}
?>
				</select></td>     
               <tr>
               
               
               
                <tr>
              <td align="left" class="formCellNosides">
			  <?php if ($mylang == 'lang_french'){
		echo 'Nom du client / Compagnie';
		}else {
		echo 'Customer name or Company';
		}
	?></td><td> <select name="customer" id="customer" class="formField">
				<option value=""><?php echo $adm_selectaccount_txt;?></option>
				<?php
	$query="select primary_key, company, last_name, first_name from accounts where main_lab='$_POST[lab]' and approved='approved' order by company, last_name";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		echo "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
			</select></td>     
               <tr>


              <td align="left" class="formCellNosides">
			  <?php if ($mylang == 'lang_french'){
		echo 'Date recu';
		}else {
		echo 'Date received';
		}
	?></td><td><input name="date_received" value="<?php echo $datecomplete ;?>" type="text" id="date_received" size="23"></td>
              </tr>
              
              <tr>
              <td align="left" class="formCellNosides">
			  <?php if ($mylang == 'lang_french'){
		echo 'Date de la sauvegarde';
		}else {
		echo 'Date recorded';
		}
	?></td><td><?php echo $datecomplete ;?><input name="date_recorded" type="hidden" value="<?php echo $datecomplete ;?>" id="date_recorded" size="23"></td>     
               <tr>
               
               
                 
              <tr>
              <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Montant';
		}else {
		echo 'Amount';
		}
	?></td><td><input name="amount"  type="text" id="amount" size="10"></td>     
               <tr>
               
               
               
             
               
                     <tr>
              <td align="left" class="formCellNosides">
             <?php if ($mylang == 'lang_french'){
		echo 'Compte concerné';
		}else {
		echo 'Check Account';
		}
	?></td><td>
              <select name="check_account" id="check_account">
               <option value="">Select an account</option>
               <option value="aitlensclub">AitLens Club</option>
               <option value="bbg">Bbg Club</option>
               <option value="directlab">Directlab</option>
               <option value="lensnetclub">LensNetClub</option>
               <option value="mylensclub">MyLensClub</option>
              </select> </td>
            </tr>
            <tr>  
            
            
              <tr>
              <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Date du dépot';
		}else {
		echo 'Date deposited';
		}
	?></td><td> <input value="<?php echo $datecomplete ;?>" name="deposited_date" type="text" id="deposited_date" size="23"></td>     
               <tr>   
            
            
            
               <tr>
              <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Déposé par';
		}else {
		echo 'Deposed by';
		}
	?></td><td> <input name="deposed_by" type="text" id="deposed_by" size="23"></td>     
               <tr>   
			  
			  <tr>
              <td align="left"  class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Commentaire';
		}else {
		echo 'Comments';
		}
	?></td><td>
              <textarea name="comment" size="8"   id="comment"></textarea>
               </td>
              </tr>
			  
              
                <tr>
              <td align="left" class="formCellNosides"><?php if ($mylang == 'lang_french'){
		echo 'Qui remplis ce formulaire';
		}else {
		echo 'Who fill this form';
		}
	?></td><td> <input name="who_fill_form" type="text" id="who_fill_form" size="23"></td>     
               <tr>


          </table>
        </div>
		    <div align="center" style="margin:11px">
		      	<p>
	      		<input name="openAcct" type="submit" class="formText" value="submit">
		      	</p>
		      
		    </div>
		  </form></td></tr></table>
  <p>&nbsp;</p>

</body>
</html><?php */?>