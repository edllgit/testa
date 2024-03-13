<?php 
require('../../Connections/sec_connect.inc.php');
include "../../includes/getlang.php";
session_start();

if($_SESSION["sessionUser_Id"]=="")
header("Location:loginfail.php");

$result=mysql_query("SELECT curdate()");/* get today's date */
	$today=mysql_result($result,0,0);

$user_id = $_SESSION["sessionUser_Id"];
	
if($_GET[frompage]=="process_order"){
}

$result=mysql_query("SELECT DATE_ADD('$_SESSION[order_date_processed]', interval 1 month)");/* add 1 month to order_processed_date */
$duedate=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 15 day)");/* add 15 days to order_processed_date */
$discountdate_15=mysql_result($result,0,0);

$result=mysql_query("SELECT DATE_SUB('$duedate', interval 10 day)");/* add 10 days to order_processed_date */
$discountdate_10=mysql_result($result,0,0);

$item_total=bcsub($_SESSION["currentTotal"], $_SESSION["order_shipping_cost"], 2);

/*
if($discountdate_15 >= $today){
	$discountamt=bcmul('.02', $item_total, 2);
	$pass_disc=".02";
	$discount = "2%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.01', $item_total, 2);
	$pass_disc=".01";
	$discount = "1%";
}
*/
//REVISED FUNCTIONS TO TEMPORARILY ELIMINATE DISCOUNT

if($discountdate_15 >= $today){
	$discountamt=bcmul('.00', $item_total, 2);
	$pass_disc=".00";
	$discount = "0%";
}
elseif($discountdate_10 >= $today){
	$discountamt=bcmul('.00', $item_total, 2);
	$pass_disc=".00";
	$discount = "0%";
}

$discounted_total_cost = bcsub($_SESSION["currentTotal"], $discountamt, 2);
include('inc/header.php');
?>
       
<?php if ($_SESSION["sessionUserData"]["currency"]=="US"){?>
	<form name="form1" method="post" action="https://vision.gtpaysecure.net/securepayments/a1/cc_collection.php">
<?php }else if ($_SESSION["sessionUserData"]["currency"]=="CA"){?>
	<form name="form1" method="post" action="https://visionca.gtpaysecure.net/securepayments/a1/cc_collection.php">
<?php }?>

 <?php if ($_SESSION['Language_Promo']== 'french')
{
echo '<h1>Confirmation</h1>
<h2>Utilisateur:';
}else{
echo '<h1>Review</h1>
<h2>User:';
}  
 ?>  


<?php 
if ($_SESSION["sessionUser_Id"]!=""){
echo $_SESSION["sessionUser_Id"];}
else{
echo "not logged in";}?></h2>

<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
    <tr>
    	<td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
    </tr>
    <tr>					
        <td width="229" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold"><?php echo "$_SESSION[Master_Order_ID]"; ?></span>
        <td width="149" align="left" nowrap class="formCellNosides"><div align="right">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Montant total qui sera charg&eacute;&nbsp;';    
        }else{
        echo  'Total Amount to be Charged&nbsp;';
        }  
         ?>  &nbsp;</div></td>
        <td width="201" align="left" class="formCellNosides"><span class="Subheader" style="font-weight:bold">&nbsp;$<?php echo $_POST[total_cost] . " " . $_SESSION["sessionUserData"]["currency"]; ?>
       </span></td>
    </tr>
    <tr>
        <td align="left"  class="formCellNosides">&nbsp;</td>
        <td align="left" class="formCellNosides">&nbsp;</td>
        <td align="left" nowrap class="formCellNosides">&nbsp;</td>
        <td align="left" class="formCellNosides">&nbsp;</td>
    </tr>
    <tr>
    	<td align="right" class="formCellNosides">
         <?php if ($_SESSION['Language_Promo']== 'french')
		{
		 echo 'Prenom';
		}else{
		 echo 'First Name';
		}  
		 ?>  
        &nbsp;</td>
    	<td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["first_name"]; ?></span></td>
    	<td align="right" nowrap class="formCellNosides">
		<?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Nom de famille';    
        }else{
        echo  'Last Name';
        }  
         ?>&nbsp;</td>
    	<td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["last_name"]; ?></span></td>
    </tr>
    <tr>
        <td align="right" class="formCellNosides"> 
		<?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Telephone';    
        }else{
        echo  'Phone';
        }  
         ?>&nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["phone"]; ?></span></td>
        <td align="right" nowrap class="formCellNosides"> 
		<?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Autre telephone';    
        }else{
        echo  'Other Phone';
        }  
         ?>&nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["other_phone"]; ?></span></td>
	</tr>
    <tr>
        <td align="right" class="formCellNosides">
		<?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Courriel';    
        }else{
        echo  'Email';
        }  
         ?>&nbsp;</td>
        <td colspan="3" align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["email"]; ?></span></td>
    </tr>
    <tr>
        <td height="30" colspan="4" align="center"  class="formCellNosides">
        <h2><?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse de facturation';    
        }else{
        echo  'Billing Address';
        }  
         ?></h2></td>	
	</tr>
    <tr>
        <td align="right" class="formCellNosides">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse 1';    
        }else{
        echo  'Address 1';
        }  
         ?>&nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_address1"]; ?></span></td>
        <td align="right" nowrap class="formCellNosides">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse 2';    
        }else{
        echo  'Address 2';
        }  
         ?>
         &nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_address2"]; ?></span></td>
    </tr>
    <tr>
        <td align="right" class="formCellNosides">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Ville';    
        }else{
        echo  'City';
        }  
         ?>
        &nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_city"]; ?></span></td>
        <td align="right" nowrap class="formCellNosides">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Etat/Province';    
        }else{
        echo  'State/Province';
        }  
         ?>
        &nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_state"]; ?></span></td>
    </tr>
    <tr>
        <td align="right" class="formCellNosides">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Code Postal';    
        }else{
        echo  'Zip/Postal Code';
        }  
         ?>
        &nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_zip"]; ?></span></td>
        <td align="right" nowrap class="formCellNosides">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Pays';    
        }else{
        echo  'Country';
        }  
         ?>
        &nbsp;</td>
        <td align="left" class="formCellNosides"><span class="Subheader" 
        	style="font-weight:bold"><?php echo $_SESSION["sessionUserData"]["bill_country"]; ?></span></td>
    </tr>
</table>

<div align="center" style="margin:11px">
	<p><input name="submitPmt" type="submit" class="formText" value="Continue"></p>
</div>

<?php if ($_SESSION["sessionUserData"]["currency"]=="US"){?>

<input type="hidden" name="CRESecureID" value="gt188417645" />
<input type="hidden" name="CRESecureAPIToken" value="c0fac2bda68d9ac56af9e089453300dc" />

<?php }else if ($_SESSION["sessionUserData"]["currency"]=="CA"){?>

<input type="hidden" name="CRESecureID" value="gt1768180" />
<input type="hidden" name="CRESecureAPIToken" value="b4cc30197cf237daec64ddfd929c6cd1" />

<?php }?>

<input type="hidden" name="return_url" value="https://www.lensnetclub.com/oneyear/payment_thanks.php" />
<input type="hidden" name="content_template_url" value="https://www.lensnetclub.com/payment.php" />
<input type="hidden" name="total_amt" value="<?php echo $_POST[total_cost]; ?>" />
<input type="hidden" name="customer_id" value="<?php echo $_SESSION["sessionUser_Id"];?>" />
<input type="hidden" name="lang" value="en_US" />
<input type="hidden" name="allowed_types" value="Visa|MasterCard|American Express|Discover" />
<input type="hidden" name="sess_id" value="<?php echo "$_SESSION[Master_Order_ID]"; ?>" />
<input type="hidden" name="sess_name" value="<?php echo "$_SESSION[Master_Order_ID]"; ?>" />

</form>
<br />
</form>
 <?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  
 ?>  