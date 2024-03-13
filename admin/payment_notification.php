<?php
session_start();
include("admin_functions.inc.php");
include("../Connections/sec_connect.inc.php");

$lab_pkey=$_SESSION["lab_pkey"];
$lab_name=$_SESSION["labAdminData"]["lab_name"];


if ($_POST[customer] <> ''){
	$query = "INSERT INTO payment_history (date_received, date_recorded, customer, check_account, deposited_date,deposed_by,comment,who_fill_form) 
	VALUES('$_POST[date_received]','$_POST[date_recorded]','$_POST[customer]','$_POST[check_account]','$_POST[deposited_date]','$_POST[deposed_by]','$_POST[comment]','$_POST[who_fill_form]')";
	$result=mysql_query($query)		or die ("Could not create record ".mysql_error(). $query );

	$message="Un nouveau paiement par cheque est dans le systeme: \r\n";
	$message.="Date recu: $_POST[date_received]\r\n";
	$message.="Date enregistre dans le systeme: $_POST[date_recorded]\r\n";
	$message.="Client: $_POST[customer]\r\n";
	$message.="Account: $_POST[check_account]\r\n";
	$message.="Date depose: $_POST[deposited_date]\r\n";
	$message.="Commentaire: $_POST[comment]\r\n";
	$message.="Qui a remplis le formulaire: $_POST[who_fill_form]\r\n";
	$headers = "From: info@direct-lens.com\r\n";
	$headers .=	"Content-Type: text/plain; charset=\"iso-8859-1\"\r\n";
	$emailSent = mail("dbeaulieu@direct-lens.com", "Nouveau paiement par cheque", "$message", "$headers");


}


?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="../includes/date_validation.js"></SCRIPT>
<script language="JavaScript" type="text/JavaScript">
<!--

function checkAllDates(form){
		var ed=form.date_var;
		if (isDate(ed.value)==false){
			ed.focus()
			return false}
		return true
	}
//-->
</script>

</head>

<body>
  <table border="0" cellspacing="0" cellpadding="0" width="95%">
  	<tr valign="top">
  		<td width="25%"><?php
		include("adminNav.php");
		
$ladate = mktime(0,0,0,date("m"),date("d")-0,date("Y"));
$datecomplete = date("Y/m/d", $ladate);
		?></td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="1" bgcolor="#000000">&nbsp;</td>
  		<td width="1" bgcolor="#FFFFFF">&nbsp;</td>
  		<td width="75%">
<form  method="post" name="goto_date" id="goto_date" action="payment_notification.php">
            <table bgcolor="#CCCCCC" border="1" align="center" cellpadding="3" cellspacing="0"  class="formBox">
            <tr >
              <td align="center" colspan="4"  class="tableHead"> <?php if ($mylang == 'lang_french'){
		echo 'ENREGISTRER UN CHEQUE';
		}else {
		echo 'RECORD A CHECK WE RECEIVED';
		}
	?></td>
              </tr>

              <td align="left" class="formCellNosides">Date received</td><td><input name="date_received" value="<?php echo $datecomplete ;?>" type="text" id="date_received" size="23"></td>
              </tr>
              
              <tr>
              <td align="left" class="formCellNosides">Date recorded</td><td><?php echo $datecomplete ;?><input name="date_recorded" type="hidden" value="<?php echo $datecomplete ;?>" id="date_recorded" size="23"></td>     
               <tr>
               
               
                 
              <tr>
              <td align="left" class="formCellNosides">Amount</td><td><input name="amount"  type="text" id="amount" size="10"></td>     
               <tr>
               
               
               
                 <tr>
              <td align="left" class="formCellNosides">Main Lab of customer</td><td> 
              <select name="main_lab" id="main_lab" class="formField">
				<option value=""><?php echo $adm_selectaccount_txt;?></option>
				<?php
	$query="select primary_key, company, last_name, first_name from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company, last_name";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		print "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
			</select></td>     
               <tr>
               
               
               
                <tr>
              <td align="left" class="formCellNosides">Customer name or Company</td><td> <select name="customer" id="customer" class="formField">
				<option value=""><?php echo $adm_selectaccount_txt;?></option>
				<?php
	$query="select primary_key, company, last_name, first_name from accounts where main_lab='$_SESSION[lab_pkey]' and approved='approved' order by company, last_name";
	$result=mysql_query($query)
		or die ($adm_error1_txt);
	while ($accountList=mysql_fetch_array($result)){
		print "<option value=\"$accountList[primary_key]\">$accountList[company], $accountList[first_name] $accountList[last_name]</option>";
}
?>
			</select></td>     
               <tr>
               
                     <tr>
              <td align="left" class="formCellNosides">
               Check Account</td><td>
              <select name="check_account" id="check_account">
               <option value="">Select an account</option>
                <option value="directlab">Directlab</option>
                <option value="lensnetclub">LensNetClub</option>
                <option value="mylensclub">MyLensClub</option>
                <option value="bbg">Bbg Club</option>
              </select> </td>
            </tr>
            <tr>  
            
            
              <tr>
              <td align="left" class="formCellNosides">Date deposited</td><td> <input value="<?php echo $datecomplete ;?>" name="deposited_date" type="text" id="deposited_date" size="23"></td>     
               <tr>   
            
            
            
               <tr>
              <td align="left" class="formCellNosides">Deposed by</td><td> <input name="deposed_by" type="text" id="deposed_by" size="23"></td>     
               <tr>   
			  
			  <tr>
              <td align="left"  class="formCellNosides">Comments</td><td>
              <textarea name="comment" size="8"   id="comment"></textarea>
               </td>
              </tr>
			  
              
                <tr>
              <td align="left" class="formCellNosides">Who fill this form</td><td> <input name="who_fill_form" type="text" id="who_fill_form" size="23"></td>     
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
</html>