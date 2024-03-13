<?php 
session_start();
require('../../Connections/sec_connect.inc.php');
include "../../includes/getlang.php";

$queryAccount = "SELECT oneyear_type ,oneyear_dt,oneyear_ar_credit, oneyear_ar_credit_used from accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
$resultAccount=mysql_query($queryAccount)		or die  ('I cannot select items because: ' . mysql_error());
$DataAccount=mysql_fetch_array($resultAccount);

$oneyear_type   		= $DataAccount[oneyear_type];
$oneyear_dt				= $DataAccount[oneyear_dt];
$oneyear_ar_credit		= $DataAccount[oneyear_ar_credit];
$oneyear_ar_credit_used	= $DataAccount[oneyear_ar_credit_used];

if ($oneyear_type == ''){
$oneyear_type = $_SESSION['Promo_oneyear'];
}


if ($oneyear_type  <> ''){

//echo  $oneyear_type;

	switch($oneyear_type){
	
	//Promo 1000$
	case '1000-futureshop':
	$TotaltoPay = 1000;
	break;

	case '1000-lens':
	$TotaltoPay = 1000;
	break;
	
	case '1000-optipoints':
	$TotaltoPay = 1000;
	break;
	
	case '1000-ar':
	$TotaltoPay = 1000;
	break;
	
	
	//Promo 5000$
	case '5000-futureshop':
	$TotaltoPay = 5000;
	break;
	
	case '5000-lens':
	$TotaltoPay = 5000;
	break;


	case '5000-optipoints':
	$TotaltoPay = 5000;
	break;
	
	}

}
include('inc/header.php'); 
?>
    
<form action="reviewOrderCreditInfo.php" method="post" name="pmtForm" id="pmtForm" onSubmit="return formCheck(this);">
    <h1>Order Details</h1>
    <p>User: 
    <?php 
    if ($_SESSION["sessionUser_Id"]!=""){
    echo $_SESSION["sessionUser_Id"];}
    else{
    echo "not logged in";}?></p>
    <table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
      <tr >
        <td colspan="4" bgcolor="#000099" class="tableHead">&nbsp;</td>
        </tr>
    
      <input type="hidden" name="currency" value="<?php echo $_SESSION["sessionUserData"]["currency"]; ?>"></td>
      
      <tr>
        <td align="left"  class="formCellNosides"><div align="right">
		 <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Montant total qui sera charg&eacute;&nbsp;';    
        }else{
        echo  'Total Amount to be Charged&nbsp;';
        }  
         ?>  
         </div></td>
        <td align="left" class="formCellNosides"><span class="Subheader">
        
        &nbsp;$<?php echo $TotaltoPay  . " " . $_SESSION["sessionUserData"]["currency"]; ?>
        
        <input type="hidden" name="total_cost" value="<?php echo $TotaltoPay; ?>">
        </span></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
            &nbsp;
            </div></td>
        <td align="left" class="formCellNosides">&nbsp;</td>
        </tr>
      <tr>
        <td align="left"  class="formCellNosides"><div align="right">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Prenom';    
        }else{
        echo  'First Name';
        }  
         ?>   &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="first_name" type="text" id="first_name" size="25" value="<?php echo $_SESSION["sessionUserData"]["first_name"]; ?>"></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
            <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Nom de famille';    
        }else{
        echo  'Last Name';
        }  
         ?>&nbsp; 
            </div></td>
        <td align="left" class="formCellNosides"><input name="last_name" type="text" id="last_name" size="20" value="<?php echo $_SESSION["sessionUserData"]["last_name"]; ?>"></td>
      </tr>
      <tr>
            <td align="left"  class="formCellNosides"><div align="right">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'T&nbsp;l&nbsp;phone';    
        }else{
        echo  'Phone';
        }  
         ?>
             &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="phone" type="text" id="phone" size="25" value="<?php echo $_SESSION["sessionUserData"]["phone"]; ?>"></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
            <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Autre telephone';    
        }else{
        echo  'Other Phone';
        }  
         ?>
          &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="other_phone" type="text" id="other_phone" size="20" value="<?php echo $_SESSION["sessionUserData"]["other_phone"]; ?>"></td>
        </tr>
      <tr>
        <td align="left"  class="formCellNosides"><div align="right">
            <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Courriel';    
        }else{
        echo  'Email';
        }  
         ?>
           &nbsp;
            </div></td>
        <td colspan="3" align="left" class="formCellNosides"><input name="email" type="text" id="email" size="25" value="<?php echo $_SESSION["sessionUserData"]["email"]; ?>"></td>
        </tr>
      <tr bgcolor="#000099">
            <td height="30" colspan="4" align="left"  class="formCellNosides"><div align="center" class="tableHead"><br />
            
            <h2>
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse de facturation';    
        }else{
        echo  'Billing Address';
        }  
         ?>
            
            </h2></div></td>	
            </tr>
      <tr>
            <td align="left"  class="formCellNosides"><div align="right">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse 1';    
        }else{
        echo  'Address 1';
        }  
         ?>
                &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="address1" type="text" id="address1" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address1"]; ?>"></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
        <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Adresse 2';    
        }else{
        echo  'Address 2';
        }  
         ?>
          &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="address2" type="text" id="address2" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_address2"]; ?>"></td>
        </tr>
      <tr>
            <td align="left"  class="formCellNosides"><div align="right">
         <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Ville';    
        }else{
        echo  'City';
        }  
         ?>
          &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="city" type="text" id="city" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_city"]; ?>"></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
             <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Etat/Province';    
        }else{
        echo  'State/Province';
        }  
         ?>
           &nbsp;
        </div>              	</td>
        <td align="left" class="formCellNosides"><select id="state" name="state">
            <option value="">Select One</option>
            <optgroup label="Canadian Provinces">
            <option value="AB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AB") echo " selected"; ?>>Alberta</option>
            <option value="BC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="BC") echo " selected"; ?>>British
            Columbia</option>
            <option value="MB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MB") echo " selected"; ?>>Manitoba</option>
            <option value="NB" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NB") echo " selected"; ?>>New
            Brunswick</option>
            <option value="NF" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NF") echo " selected"; ?>>Newfoundland</option>
            <option value="NT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NT") echo " selected"; ?>>Northwest
            Territories</option>
            <option value="NS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NS") echo " selected"; ?>>Nova
            Scotia</option>
            <option value="NU" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NU") echo " selected"; ?>>Nunavut</option>
            <option value="ON" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ON") echo " selected"; ?>>Ontario</option>
            <option value="PE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PE") echo " selected"; ?>>Prince
            Edward Island</option>
            <option value="QC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="QC") echo " selected"; ?>>Quebec</option>
            <option value="SK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SK") echo " selected"; ?>>Saskatchewan</option>
            <option value="YT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="YT") echo " selected"; ?>>Yukon
            Territory</option>
            </optgroup>
            <optgroup label="U.S. States">
            <option value="AL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AL") echo " selected"; ?>>Alabama</option>
            <option value="AK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AK") echo " selected"; ?>>Alaska</option>
            <option value="AZ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AZ") echo " selected"; ?>>Arizona</option>
            <option value="AR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="AR") echo " selected"; ?>>Arkansas</option>
            <option value="CA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CA") echo " selected"; ?>>California</option>
            <option value="CO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CO") echo " selected"; ?>>Colorado</option>
            <option value="CT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="CT") echo " selected"; ?>>Connecticut</option>
            <option value="DE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DE") echo " selected"; ?>>Delaware</option>
            <option value="DC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="DC") echo " selected"; ?>>District
            of Columbia</option>
            <option value="FL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="FL") echo " selected"; ?>>Florida</option>
            <option value="GA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="GA") echo " selected"; ?>>Georgia</option>
            <option value="HI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="HI") echo " selected"; ?>>Hawaii</option>
            <option value="ID" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ID") echo " selected"; ?>>Idaho</option>
            <option value="IL" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IL") echo " selected"; ?>>Illinois</option>
            <option value="IN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IN") echo " selected"; ?>>Indiana</option>
            <option value="IA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="IA") echo " selected"; ?>>Iowa</option>
            <option value="KS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KS") echo " selected"; ?>>Kansas</option>
            <option value="KY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="KY") echo " selected"; ?>>Kentucky</option>
            <option value="LA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="LA") echo " selected"; ?>>Louisiana</option>
            <option value="ME" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ME") echo " selected"; ?>>Maine</option>
            <option value="MD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MD") echo " selected"; ?>>Maryland</option>
            <option value="MA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MA") echo " selected"; ?>>Massachusetts</option>
            <option value="MI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MI") echo " selected"; ?>>Michigan</option>
            <option value="MN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MN") echo " selected"; ?>>Minnesota</option>
            <option value="MS" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MS") echo " selected"; ?>>Mississippi</option>
            <option value="MO" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MO") echo " selected"; ?>>Missouri</option>
            <option value="MT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="MT") echo " selected"; ?>>Montana</option>
            <option value="NE" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NE") echo " selected"; ?>>Nebraska</option>
            <option value="NV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NV") echo " selected"; ?>>Nevada</option>
            <option value="NH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NH") echo " selected"; ?>>New
            Hampshire</option>
            <option value="NJ" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NJ") echo " selected"; ?>>New
            Jersey</option>
            <option value="NM" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NM") echo " selected"; ?>>New
            Mexico</option>
            <option value="NY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NY") echo " selected"; ?>>New
            York</option>
            <option value="NC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="NC") echo " selected"; ?>>North
            Carolina</option>
            <option value="ND" <?php if($_SESSION["sessionUserData"]["bill_state"]=="ND") echo " selected"; ?>>North
            Dakota</option>
            <option value="OH" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OH") echo " selected"; ?>>Ohio</option>
            <option value="OK" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OK") echo " selected"; ?>>Oklahoma</option>
            <option value="OR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="OR") echo " selected"; ?>>Oregon</option>
            <option value="PA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PA") echo " selected"; ?>>Pennsylvania</option>
            <option value="PR" <?php if($_SESSION["sessionUserData"]["bill_state"]=="PR") echo " selected"; ?>>Puerto
            Rico</option>
            <option value="RI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="RI") echo " selected"; ?>>Rhode
            Island</option>
            <option value="SC" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SC") echo " selected"; ?>>South
            Carolina</option>
            <option value="SD" <?php if($_SESSION["sessionUserData"]["bill_state"]=="SD") echo " selected"; ?>>South
            Dakota</option>
            <option value="TN" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TN") echo " selected"; ?>>Tennessee</option>
            <option value="TX" <?php if($_SESSION["sessionUserData"]["bill_state"]=="TX") echo " selected"; ?>>Texas</option>
            <option value="UT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="UT") echo " selected"; ?>>Utah</option>
            <option value="VT" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VT") echo " selected"; ?>>Vermont</option>
            <option value="VA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="VA") echo " selected"; ?>>Virginia</option>
            <option value="WA" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WA") echo " selected"; ?>>Washington</option>
            <option value="WV" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WV") echo " selected"; ?>>West
            Virginia</option>
            <option value="WI" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WI") echo " selected"; ?>>Wisconsin</option>
            <option value="WY" <?php if($_SESSION["sessionUserData"]["bill_state"]=="WY") echo " selected"; ?>>Wyoming</option>
            </optgroup>
        </select></td>
      </tr>
      <tr>
            <td align="left"  class="formCellNosides"><div align="right">
                <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Code Postal';    
        }else{
        echo  'Zip/Postal Code';
        }  
         ?>
          &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><input name="zip" type="text" id="zip" size="20" value="<?php echo $_SESSION["sessionUserData"]["bill_zip"]; ?>"></td>
        <td align="left" nowrap class="formCellNosides"><div align="right">
           <?php if ($_SESSION['Language_Promo']== 'french')
        {
        echo  'Pays';    
        }else{
        echo  'Country';
        }  
         ?>
          
           &nbsp;
            </div></td>
        <td align="left" class="formCellNosides"><select name = "country" id="country">
            <option value="">Select One</option>
            <option value = "CA" <?php if($_SESSION["sessionUserData"]["bill_country"]=="CA") echo " selected"; ?>>Canada</option>
            <option value = "US" <?php if($_SESSION["sessionUserData"]["bill_country"]=="US") echo " selected"; ?>>United
            States</option>
        </select></td>
      </tr>

    </table>
    <div align="center" style="margin:11px">
      <p><input type="hidden" name="uniqid" id="uniqid" value="<?php echo "$uniqid"; ?>" />
            
            <?php if ($_SESSION['Language_Promo']== 'french')
			{
			echo '<input name="submitPmt" type="submit" class="formText" value="Soumettre">';
			}else{
			echo '<input name="submitPmt" type="submit" class="formText" value="Continue">';
			}  
			 ?>    
           
          
      </p>
		</div>
</form>
 <?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  
 ?>         