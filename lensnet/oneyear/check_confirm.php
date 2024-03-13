<?php 
session_start();
include_once("../../Connections/sec_connect.inc.php");
include("promo_functions.inc.php");
//echo 'Logged in as: '.  $_SESSION["sessionUser_Id"];  
//Since the customer will pay by check we activate his collections
include('inc/header.php'); 
include "../includes/dl_process_order_functions.inc.php";
?>

<?php if ($_SESSION['Language_Promo']== 'french')
{
echo "<h2>Votre compte est maintenant actif et pr&ecirc;t &agrave; commander</h2>";     
}else{
echo "<h2>Your account is now active and ready to order</h2>";    
}  
 ?>  
   

<?php 
$queryUser = "SELECT primary_key from accounts WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
$ResultUser=mysql_query($queryUser)	or die  ('I cannot select items because: ' . mysql_error());
$DataUser=mysql_fetch_array($ResultUser);
$Acct_Primary_Key = $DataUser[primary_key];

$queryCollectionActives = "SELECT count(id) as NbrActive FROM acct_collections WHERE acct_id = $Acct_Primary_Key";
$ResultCollectionActive=mysql_query($queryCollectionActives)	or die  ('I cannot select items because: ' . mysql_error());
$DataCollectionActive=mysql_fetch_array($ResultCollectionActive);
$NbrCollectionActive = $DataCollectionActive[NbrActive];

//If no collections are activated yet, we activate them
if ($NbrCollectionActive ==0){
ActivateLensnetCollections($Acct_Primary_Key);
}else{
//Collections are already activated which means that he was already a customer, we update OneYear variables
$Promo_oneyear = $_SESSION['Promo_oneyear']; 

//We need to check if the customer already had a promo, and IF it is still active
$queryValiderPromo = "SELECT oneyear_type, oneyear_dt, oneyear_ar_credit FROM accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"]. "'";
$ResultValiderPromo=mysql_query($queryValiderPromo)	or die  ('I cannot select items because: ' . mysql_error() . $queryValiderPromo );
$DataValiderPromo=mysql_fetch_array($ResultValiderPromo);

$Oneyear_type  	   = $DataValiderPromo[oneyear_type];
$Oneyear_dt  	   = $DataValiderPromo[oneyear_dt];
$Oneyear_ar_credit = $DataValiderPromo[oneyear_ar_credit];
$Credits_to_cummulate = 0;

$datedujour = mktime(0,0,0,date("m"),date("d"),date("Y"));
$today_date = date("Y-m-d", $datedujour);



 if (($Oneyear_dt  >= $today_date) && ($Oneyear_dt <> '0000-00-00'))//Promo still valid, we need to take the remaining AR Credits and cummulate them with the new promo bought
 {
 $Credits_to_cummulate = $Oneyear_ar_credit;
 $nbjours = round((strtotime($Oneyear_dt) - strtotime($today_date))/(60*60*24)); 
	if ($nbjours > 0){
	$Days_to_add = $Days_to_add +  $nbjours;
	}	
 }else{
 $Days_to_add = 30;
 }
 
 $Futuredate = mktime(0,0,0,date("m"),date("d")+$Days_to_add,date("Y"));
 $Date_in_30_days = date("Y-m-d", $Futuredate);
  


switch($Promo_oneyear)
	{
	
	//Promo 1000$
	case '1000-futureshop':
	$oneyear_type = '1000-futureshop';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;

	case '1000-lens':
	$oneyear_type = '1000-lens';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;
	
	case '1000-optipoints':
	$oneyear_type = '1000-optipoints';
	$oneyear_dt = '0000-00-00';
	$oneyear_ar_credit = 0;
	break;
	
	case '1000-ar':
	$oneyear_type = '1000-ar';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 15 + $Credits_to_cummulate ;
	break;
	
	
	//Promo 5000$
	case '5000-futureshop':
	$oneyear_type = '5000-futureshop';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000 + $Credits_to_cummulate;
	break;
	
	case '5000-lens':
	$oneyear_type = '5000-lens';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000 + $Credits_to_cummulate;
	break;


	case '5000-optipoints':
	$oneyear_type = '5000-optipoints';
	$oneyear_dt = $Date_in_30_days;
	$oneyear_ar_credit = 1000 + $Credits_to_cummulate;
	break;

}//End switch Promo one year

$queryOneYear="UPDATE accounts SET  oneyear_type 		= '$oneyear_type'";

if (($oneyear_dt <> '0000-00-00') && ($oneyear_ar_credit <> 0)){
$queryOneYear.=  ",  oneyear_dt   		= '$oneyear_dt',
					 oneyear_ar_credit =  $oneyear_ar_credit";
}


$queryOneYear .= " WHERE user_id = '". $_SESSION["sessionUser_Id"]. "'";
			 
$resultOneYear=mysql_query($queryOneYear)		or die ("Could not create account" . mysql_error() . $queryOneYear );



//Then we send the email to tell the team about this sale
$queryDetail  = "SELECT company from accounts WHERE user_id = '" .  $_SESSION["sessionUser_Id"] . "'";
$ResultDetail=mysql_query($queryDetail)	or die  ('I cannot select items because: ' . mysql_error());
$DataDetail=mysql_fetch_array($ResultDetail);

switch($oneyear_type){

	//Promo 1000$
	case '1000-futureshop':
	$promo = '1000-futureshop';
	$OrderAmount = -1000;
	break;

	case '1000-lens':
	$promo = '1000-lens';
	$OrderAmount = -1100;
	break;
	
	case '1000-optipoints':
	$promo = '1000-optipoints';
	$OrderAmount = -1000;
	break;
	
	case '1000-ar':
	$promo = '1000-ar';
	$OrderAmount = -1000;
	break;
	
	//Promo 5000$
	case '5000-futureshop':
	$promo = '5000-futureshop';
	$OrderAmount = -5000;
	break;
	
	case '5000-lens':
	$promo = '5000-lens';
	$OrderAmount = -5300;
	break;

	case '5000-optipoints':
	$promo = '5000-optipoints';
	$OrderAmount = -5000;
	break;
}


//Create an order to insert the 'Rebate' in the customer Monthly statement
$orderNum=getNewOrderNum();

//Get user main lab
$queryMainLab = "SELECT main_lab from accounts WHERE user_id = '" . $_SESSION["sessionUser_Id"] . "'";
$resultMainLab=mysql_query($queryMainLab)	or die  ('I cannot select items because: ' . mysql_error());
$DataMainLab=mysql_fetch_array($resultMainLab);
$MainLab = $DataMainLab[main_lab];

//Create an order in the customer account
$queryOrder="insert into orders ";
$queryOrder.="(user_id,order_num,eye,order_date_processed,order_item_date,order_quantity,order_product_name,order_product_price,order_product_discount,order_status, order_date_shipped, order_total,lab) 
values ('" . $_SESSION["sessionUser_Id"] . "',$orderNum, 'Both','$today_date', '$today_date' ,1, 'Promo One Year', '$OrderAmount','$OrderAmount', 'filled', '$today_date', '$OrderAmount', $MainLab)";
//echo $queryOrder;
$ResultOrder=mysql_query($queryOrder)	or die  ('I cannot select items because: ' . mysql_error());

//Then we send the email to tell the team about this sale
sendEmail($DataDetail[company], $promo, 'thahn@direct-lens.com', 'check');
sendEmail($DataDetail[company], $promo, 'dbeaulieu@direct-lens.com','check');


}
?>

<?php if ($_SESSION['Language_Promo']== 'french')
{
 include('inc/footer_fr.php');     
}else{
 include('inc/footer.php'); 
}  
 ?>  