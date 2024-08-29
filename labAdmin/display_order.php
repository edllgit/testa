<?php
//AFFICHER LES ERREURS

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
include("admin_functions.inc.php");

//Démarrer la session
session_start();


if ($_SESSION[labAdminData][username]==""){
	echo "You are not logged in. Click <a href='/labAdmin'>here</a> to login.";
	exit();
}

include("lab_confirmation_func.inc.php");
include("fax_lab_confirm_func.inc.php");
include("../includes/calc_functions.inc.php");
include("../includes/est_ship_date_functions.inc.php");
include("../sales/salesmath.php");

$update_status = "yes";
                    
if ($_SESSION["accessid"] <> ""){
$queryAccess = "SELECT * FROM access WHERE id=" . $_SESSION["accessid"];
$resultAccess=mysqli_query($con,$queryAccess)		or die ('Error'. mysqli_error($con));
$AccessData=mysqli_fetch_array($resultAccess,MYSQLI_ASSOC);
$update_status = $AccessData[update_status];			
} 


if ($_POST[from_internal_note]=="true"){//PROCESS Update Internal note

	$order_num=$_POST[order_num];
	$internal_note=$_POST[internal_note];
	
	$message="<tr><td class=\"messageText\" colspan=\"4\">Internal note UPDATED!</td></tr>";
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	

	$query="UPDATE orders SET internal_note='$internal_note' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));

	$_POST[from_internal_note]="false";

}




if ($_POST[from_account_update]=="true"){//switch the user_id of the  order 

	$order_num = $_POST[order_num];
	$user_id   = $_POST[user_id];

	$message="<tr><td class=\"messageText\" colspan=\"4\">Job switched sucessfully!</td></tr>";
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	


	$queryOldUserid  = "SELECT user_id from orders WHERE order_num = " . $order_num;
	$resultOldUserID = mysqli_query($con,$queryOldUserid)	or die ("Could not select items t42");
	$DataOldUserID   = mysqli_fetch_array($resultOldUserID,MYSQLI_ASSOC);
	

	//Enregistrer dans l'historique de status le changement de user id
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$update_ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, update_ip2) 
									  VALUES($order_num,'Order switch from $DataOldUserID[user_id] to $user_id','manual','$datecomplete','$ip','$update_ip2')";						  
	$resultStatus=mysqli_query($con,$queryStatus)	or die ('Could not insert because: ' . mysqli_error($con));

	$query="UPDATE orders SET user_id='$user_id' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));
	//echo '<br>'. $query;
		
	$_POST[from_account_update]="false";

}

if ($_POST[from_special_instructions]=="true"){//PROCESS Update Special instruction

	$order_num=$_POST[order_num];
	$special_instructions=$_POST[special_instructions];
	
	$message="<tr><td class=\"messageText\" colspan=\"4\">Special Instructions UPDATED!</td></tr>";
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	
	$query="UPDATE orders SET special_instructions='$special_instructions' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));
		
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);
	$ip  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$update_ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$queryStatus="INSERT INTO status_history (order_num, order_status, update_type, update_time,update_ip, update_ip2) 
									  VALUES($order_num,'Update Special Instruction: $special_instructions ','manual','$datecomplete','$ip','$update_ip2')";						  
	$resultStatus=mysqli_query($con,$queryStatus)		or die ('Could not insert because: ' . mysqli_error($con));

	$_POST[from_special_instructions]="false";

}





if ($_POST[from_send_order]=="true"){//PROCESS Send Order to Customer button
	$order_num=$_POST[order_num];
	$message="<tr><td class=\"messageText\" colspan=\"4\">ORDER $order_num SENT TO CUSTOMER</td></tr>";
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	
	$userData=getUserData($order_num);
	
	$orderQuery="SELECT order_product_type, prescript_lab FROM orders WHERE order_num='$order_num'";
	$orderResult=mysqli_query($con,$orderQuery)		or die  ('I cannot select items because a1: ' . mysqli_error($con));
	$orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
	
	$labQuery="SELECT lab_email,logo_file FROM labs WHERE primary_key='$userData[main_lab]' limit 1"; //GET MAIN LAB EMAIL
	
	$labResult=mysqli_query($con,$labQuery) or die  ('I cannot select items because b2: ' . mysqli_error($con));
	$labData=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
	
	if ($orderData[order_product_type]=="exclusive"){
	
		sendPrescriptionConfirmation($labData[lab_email],$labData[logo_file],$order_num,$userData[email],$userData[user_id],$userData,"true",false);}
	else{
		sendStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$userData[email],$userData[user_id],$userData,false);}
		
	$_POST[from_send_order]="false";

}

if ($_POST[from_send_order_lab_manual]=="true"){//PROCESS Send Order to Labs Manual Redirection
	$order_num=$_POST[order_num];
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	
	if ($_POST['printit']=="false"){
		$sendPrices="false";
		$printit=false;}
	else{
		$sendPrices="true";
		$printit=true;}
		
	$userData=getUserData($order_num);
	
	$orderQuery="SELECT order_product_type,order_product_name, prescript_lab FROM orders WHERE order_num='$order_num'";
	$orderResult=mysqli_query($con,$orderQuery) or die  ('I cannot select items because j5: ' . mysqli_error($con));
	$orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
	
	if ($printit) {
		$query = "SELECT primary_key FROM labs WHERE username = '".$_SESSION[labAdminData][username]."'";
		$result = mysqli_query($con,$query);
		list($lab_id) = mysqli_fetch_array($result,MYSQLI_ASSOC);
	}
	else $lab_id=$_POST[manual_lab];
	
	$labQuery="SELECT primary_key,lab_email,logo_file,fax,fax_notify,lab_name from labs WHERE primary_key='$lab_id' limit 1"; //GET MAIN LAB EMAIL
	$labResult=mysqli_query($con,$labQuery)	or die  ('I cannot select items because c3: ' . mysqli_error($con));
	$labData=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
	
	$message="<tr><td class=\"messageText\" colspan=\"4\">ORDER $order_num SENT TO $labData[lab_name]</td></tr>";
	
	
	//Added by Charles on 16/05/2011 to make sure the prescript_lab in the orders is set to where we sent the job by email
	if (($order_num <> "") && ($_POST[manual_lab] <> "")) {
	$queryUpdatePrescriptLab = "Update orders set prescript_lab =" .$labData[primary_key] . "  where order_num = " . $order_num;
	//echo '<br>requete: ' . $queryUpdatePrescriptLab . '<br><br>';
	$resultUpdatePrescriptlab=mysqli_query($con,$queryUpdatePrescriptLab)	or die  ('I cannot update account because: ' . mysqli_error($con));
	
	//Save in status history of this order the change of prescript lab that has been done with the details of WHO did it
	$todayDate      = date("Y-m-d g:i a");// current date
	$todayDateshort = date("Y-m-d");// current date
	$currentTime    = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete     = date("Y-m-d H:i:s",$timeAfterOneHour);
	$access_id        = $_SESSION["accessid"];
	$ip				  = $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$update_ip2 	  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$provient_de      = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser          = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	

	$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type, update_ip, access_id, update_ip2, provient_de, browser)VALUES ('Order redirected to $labData[lab_name] on $todayDateshort','$order_num', '$datecomplete','manual redirection from order detail page', '$ip', '$access_id','$update_ip2', '$provient_de', '$browser')";
	$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
	}

	
	
	
	
	if ($labData[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray= str_split($labData[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		if ($orderData[order_product_type]=="exclusive"){//SEND AS FAX
			sendFaxPrescriptionConfirmation($abData[lab_email],$labData[logo_file],$order_num,"directl-config@interpage.net",$userData[user_id],$userData,"false",$faxNum,false);}
			else{
		sendFaxStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,"directl-config@interpage.net",$userData[user_id],$userData,$faxNum,false);}
	
		}
	
	/*if ($orderData[order_product_type]=="exclusive"){//ALWAYS SEND EMAIL
		sendPrescriptionConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$sendPrices,$printit);}
	else{
		sendStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$printit,$printit);}
	*/
	
	if ($orderData[order_product_type]=="exclusive"){//ALWAYS SEND EMAIL
		sendPrescriptionConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$sendPrices,$printit);}
		
	if ($orderData[order_product_type]=="stock_tray"){
		sendStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$printit,$printit);}
		
	if ($orderData[order_product_type]=="stock"){
		sendStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$printit,$printit);}
		
	if ($orderData[order_product_type]=="frame_stock_tray"){
		sendFrameStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,$printit,$printit);}
	
	$_POST[from_send_order_lab_manual]="false";

}

if ($_POST[from_send_order_lab]=="true"){//PROCESS Send Order to Labs button
	$order_num=$_POST[order_num];
	$message="<tr><td class=\"messageText\" colspan=\"4\">ORDER $order_num SENT TO LABS</td></tr>";
	

	
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	
	$userData=getUserData($order_num);
	
	$orderQuery="SELECT order_product_type,order_product_name, prescript_lab FROM orders WHERE order_num='$order_num'";
	$orderResult=mysqli_query($con,$orderQuery) or die  ('I cannot select items because e5: ' . mysqli_error($con));
	$orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);
	
	$labQuery="SELECT lab_email,logo_file,fax,fax_notify FROM labs WHERE primary_key='$userData[main_lab]' limit 1"; //GET MAIN LAB EMAIL
	$labResult=mysqli_query($con,$labQuery) or die  ('I cannot select items because d4: ' . mysqli_error($con));
	$labData=mysqli_fetch_array($labResult,MYSQLI_ASSOC);
	
	if ($labData[fax_notify]=="yes"){//PARSE THE FAX NUMBER INTO 10 DIGIT STRING
		$faxNumArray= str_split($labData[fax]);
		$numCount=count($faxNumArray);
		
		$faxNum="";
		for ($i=0;$i<$numCount;$i++){
			if (is_numeric($faxNumArray[$i])) {
				$faxNum.=$faxNumArray[$i];
			}
		}
		
		if ($orderData[order_product_type]=="exclusive"){//SEND AS FAX
		sendFaxPrescriptionLabConfirmation($userData[user_id],$order_num,$userData,$orderData[order_product_name],$faxNum);}
			else{
		sendFaxStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,"directl-config@interpage.net",$userData[user_id],$userData,$faxNum);}
	
		}
	
	if ($orderData[order_product_type]=="exclusive"){//ALWAYS SEND EMAIL
		sendPrescriptionLabConfirmation($userData[user_id],$order_num,$userData,$orderData[order_product_name],"true",false);}
	else{
		sendStockConfirmation($labData[lab_email],$labData[logo_file],$order_num,$labData[lab_email],$userData[user_id],$userData,false);}
	
	$_POST[from_send_order_lab]="false";

}

if ($_POST[from_additional_dsc]=="true"){//ADD OR UPDATE ADDITONAL DISCOUNT

	$discount_type=$_POST[discount_type];
	
	if ($discount_type=="%"){
		$additional_dsc=$_POST[additional_dsc_percentage];
	}
	else if ($discount_type=="$"){
		$additional_dsc=$_POST[additional_dsc_dollar];
	}
	else{
		$additional_dsc="";
		$discount_type="";
	}
	
	$order_num=$_POST[order_num];
	$order_status=$_POST[order_status];
	
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];

	$query="UPDATE orders SET additional_dsc='$additional_dsc', discount_type='$discount_type' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
		
	$gTotal=calculateTotal($order_num);
	addOrderTotal($order_num,$gTotal);

	$_POST[from_additional_dsc]="false";
}

if ($_POST[use_credit]=="true"){//ADD OR UPDATE ADDITONAL DISCOUNT

	$amnt = $_POST[applycred];
	$order_num=$_POST[order_num];
	$order_status=$_POST[order_status];
	$userData=getUserData($order_num);
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];
	$myid = $userData[user_id];
	
	$query="UPDATE orders SET applied_credit='$amnt' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
	$oldcredbalance = getmycredits($userData[user_id]);
	$newcredbalance = $oldcredbalance - $amnt;
	$query="UPDATE accounts SET mycredit='$newcredbalance' WHERE user_id='$myid'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));

	$finalcredit = $amnt;

	$_POST[from_credit]="false";
}


if ($_POST[from_shipping_form]=="true"){//ADD OR UPDATE SHIPPING

	$order_shipping_method=$_POST[order_shipping_method];
	$order_shipping_cost=$_POST[order_shipping_cost];
	
	if ($order_shipping_method=="Second Day - FREE"){
		$order_shipping_cost=0;}
	
	$order_num=$_POST[order_num];
	$order_status=$_POST[order_status];
	
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];

	$query="UPDATE orders SET order_shipping_method='$order_shipping_method', order_shipping_cost='$order_shipping_cost' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));

	$_POST[from_shipping_form]="false";
}

if ($_POST[from_extra_item_form]=="true"){//ADD OR UPDATE EXTRA ITEM

	$extra_product=$_POST[extra_product];
	$extra_product_price=$_POST[extra_product_price];
	
	$order_num=$_POST[order_num];
	$order_status=$_POST[order_status];
	
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];

	$query="UPDATE orders SET extra_product='$extra_product', extra_product_price='$extra_product_price' WHERE order_num='$order_num'";
	$result=mysqli_query($con,$query) or die ('Could not update because: ' . mysqli_error($con));
		
		
	$gTotal=calculateTotal($order_num);
	addOrderTotal($order_num,$gTotal);

	$_POST[from_extra_item_form]="false";
}

if ($_POST[from_status_update]=="true"){//PROCESS Status Update button

	if ($_POST[order_status]=="filled"){
		$order_date=date("Y-m-d");}
	else{$order_date="0000-00-00";}
	$order_num=$_POST[order_num];
	$order_status=$_POST[order_status];
	
	$_GET[po_num]=$_POST[po_num];
	$_GET[order_num]=$_POST[order_num];

	$query="UPDATE orders SET order_status='$order_status', order_date_shipped='$order_date' WHERE order_num='$order_num' AND order_num!='-1'";
	$result=mysqli_query($con,$query)		or die ('Could not update because: ' . mysqli_error($con));
	
	//Code rajouté par Charles 2010-07-22
	$todayDate = date("Y-m-d g:i a");// current date
	$currentTime = time($todayDate); //Change date into time
	//Add one hour equavelent seconds 60*60
	$timeAfterOneHour = $currentTime-((60*60)*4);	
	$datecomplete = date("Y-m-d H:i:s",$timeAfterOneHour);

	$access_id = $_SESSION["accessid"];

	$ip= $_SERVER['REMOTE_ADDR'];  //Get Ip of visitor
	$update_ip2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
	$provient_de  = $_SERVER['HTTP_REFERER']; //L'adresse de la page (si elle existe) qui a conduit le client à la page courante. 
	$browser      = $_SERVER['HTTP_USER_AGENT'];//Chaîne qui décrit le client HTML utilisé pour voir la page courante. Par exemple : Mozilla/4.5 [en] (X11; U; Linux 2.2.9 i586). 	

	$queryStatusHistory="INSERT INTO status_history (order_status, order_num, update_time,update_type, update_ip, access_id, update_ip2, provient_de, browser)VALUES ('$order_status','$order_num', '$datecomplete','manual', '$ip', '$access_id','$update_ip2', '$provient_de', '$browser')";
	$resultStatusHistory=mysqli_query($con,$queryStatusHistory)		or die ('Could not update because: ' . mysqli_error($con));
	//Fin code rajouté par Charles
	

		
	if ($_POST[order_status]=="cancelled"){//UPDATE INVENTORY NUMBER IF ORDER WAS CANCELLED
		//require_once 'inc.functions.php';
		//modifyInventory($order_num, 'reduce');
	}
	
	//RECALCULATE ESTIMATED SHIP DATE AND UPDATE est_ship_date table
//	if (($_POST[order_status]=="delay issue 0")||($_POST[order_status]=="delay issue 1")||($_POST[order_status]=="delay issue 2")||($_POST[order_status]=="delay issue 3")||($_POST[order_status]=="delay issue 4")||($_POST[order_status]=="delay issue 5")){
		
//	$est_ship_date=calculateEstShipDate($_POST[order_date_processed],$_POST[order_product_id]);
//	$new_est_ship_date=addThreeDaysToEstShipDate($est_ship_date);
//	}
//	else if ($_POST[order_status]=="delay issue 6"){
//		$new_est_ship_date="0000-00-00";
//	}
//	else if (($_POST[order_status]!="cancelled")&&($_POST[order_status]!="filled")){
//		$new_est_ship_date=calculateEstShipDate($_POST[order_date_processed],$_POST[order_product_id]);
//	}
	
//	addNewEstShipDate($new_est_ship_date,$_POST[order_id],$order_num,$_POST[order_date_processed]);
}

//$orderQuery="select primary_key,user_id, order_product_id, prescript_lab, order_status,additional_dsc,discount_type,extra_product,extra_product_price, order_date_processed, patient_ref_num,redo_order_num from orders WHERE order_num='$_GET[order_num]' limit 1"; //get order's user id and additional discount

$orderQuery="SELECT * FROM orders WHERE order_num='$_GET[order_num]' limit 1"; //get order's user id and additional discount
$orderResult=mysqli_query($con,$orderQuery) or die  ('I cannot select items because: ' . mysqli_error($con));
$orderData=mysqli_fetch_array($orderResult,MYSQLI_ASSOC);

mysqli_query($con,"SET CHARACTER SET UTF8");
$userQuery="SELECT * FROM accounts WHERE user_id='$orderData[user_id]'"; //find user's data
$userResult=mysqli_query($con,$userQuery)	or die  ('I cannot select items because: ' . mysqli_error($con));
$userData=mysqli_fetch_array($userResult,MYSQLI_ASSOC);

if($userData[buying_group]!=""){
	$bgQuery="SELECT bg_name FROM buying_groups WHERE primary_key='$userData[buying_group]'"; //find user's buying group data
	$bgResult=mysqli_query($con,$bgQuery)or die  ('I cannot select items because: ' . mysqli_error($con));
	$bgData=mysqli_fetch_array($bgResult,MYSQLI_ASSOC);
}	
?>
<html>
<head>
<title>Direct Lens Admin Area</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="admin.css" rel="stylesheet" type="text/css" />
<script src="../formFunctions.js" type="text/javascript"></script>
<script language="JavaScript" type="text/javascript">
<!--
function MM_openBrWindow(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}

function validate(theForm)
{

  if (theForm.TRAY.value== "")
  {
    alert("You must enter a value in the \"Tray Reference\" field.");
    theForm.TRAY.focus();
    return (false);
  }
  }

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

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
            <table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
            	<tr bgcolor="#000000">
            		<td align="center"><b><font color="#FFFFFF" size="1" face="Helvetica, sans-serif, Arial"><?php echo $adm_titlemast_displab;?></font></b></td>
            		</tr>
			<tr>
			  <td height="208">
			<table width="100%" border="0" cellpadding="2" cellspacing="0" class="formField3">
			<?php echo  $message;?>
			<tr>
			  <td colspan="2" valign="middle" bgcolor="#666666"><form name="form3" method="post" action="display_order.php">
                <span class="formField3 style1"><strong><?php echo $adm_orderstatus2_txt;?> </strong></span>
                		<select  <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?>  name="order_status" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?> class="formField3">
                  
                      <option value="processing"<?php if(strtolower($orderData[order_status])=="processing") echo " selected";?>>
					  <?php if ($mylang == 'lang_french') {?>Commande transmise<?php }else{ ?>Confirmed <?php } ?>
                      </option>
                      
                      <option value="order imported"<?php if(strtolower($orderData[order_status])=="order imported") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Commande en cours<?php }else{ ?>Order Imported <?php } ?>
                       </option>
                       
                       <option value="out for clip"<?php if(strtolower($orderData[order_status])=="out for clip") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Parti pour cLip<?php }else{ ?>Out for Clip<?php } ?>
                      </option>
                      
                      <option value="job started"<?php if(strtolower($orderData[order_status])=="job started") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Surfaçage<?php }else{ ?>Surfacing<?php } ?>
                       </option>
							
					  <option value="scanned shape to swiss"<?php if(strtolower($orderData[order_status])=="scanned shape to swiss") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Trace envoyée à Swiss<?php }else{ ?>Scanned shape to Swiss<?php } ?>
                       </option>

					  <option value="information in hand"<?php if(strtolower($orderData[order_status])=="information in hand") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Info transmise<?php }else{ ?>Info in Hand<?php } ?>
                       </option>

                      <option value="in coating"<?php if(strtolower($orderData[order_status])=="in coating") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Traitement AR<?php }else{ ?>In Coating<?php } ?>
                       </option>
                       
                        <option value="profilo"<?php if(strtolower($orderData[order_status])=="profilo") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Profilo<?php }else{ ?>Profilo<?php } ?>
                       </option>

                      <option value="in mounting"<?php if(strtolower($orderData[order_status])=="in mounting") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Au montage<?php }else{ ?>In Mounting<?php } ?>
                      </option>
                      
                       <option value="central lab marking"<?php if(strtolower($orderData[order_status])=="central lab marking") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Central Lab Marking<?php }else{ ?>Central Lab Marking<?php } ?>
                      </option>
                      
                       <option value="in mounting hko"<?php if(strtolower($orderData[order_status])=="in mounting hko") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Au montage HKO<?php }else{ ?>In Mounting HKO<?php } ?>
                      </option>
   

                      
                        <option value="in edging"<?php if(strtolower($orderData[order_status])=="in edging") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Au taillage<?php }else{ ?>In Edging<?php } ?>
                      </option>
                      
                        <option value="in edging hko"<?php if(strtolower($orderData[order_status])=="in edging hko") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Au taillage HKO<?php }else{ ?>In Edging HKO<?php } ?>
                      </option>
                      
                        <option value="in edging swiss"<?php if(strtolower($orderData[order_status])=="in edging swiss") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Au taillage Swiss<?php }else{ ?>In Edging Swiss<?php } ?>
                      </option>

				      <option value="in transit"<?php if(strtolower($orderData[order_status])=="in transit") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>En transit<?php }else{ ?>In Transit<?php } ?>
                      </option>

                      <option value="interlab"<?php if(strtolower($orderData[order_status])=="interlab") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Interlab P<?php }else{ ?>Interlab P<?php } ?>
                      </option>
                      
                       <option value="interlab qc"<?php if(strtolower($orderData[order_status])=="interlab qc") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Interlab QC<?php }else{ ?>Interlab QC<?php } ?>
                      </option>

					  <option value="on hold"<?php if(strtolower($orderData[order_status])=="on hold") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>En attente<?php }else{ ?>On Hold<?php } ?>
                      </option>

                      <option value="order completed"<?php if(strtolower($orderData[order_status])=="order completed") echo " selected";?>>
                       <?php if ($mylang == 'lang_french') {?>Commande en cours<?php }else{ ?>Order Completed<?php } ?>
                      </option>
                      
                      <option value="delay issue 0"<?php if(strtolower($orderData[order_status])=="delay issue 0") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 0<?php }else{ ?>Delay Issue 0<?php } ?>
                      </option>

                      <option value="delay issue 1"<?php if(strtolower($orderData[order_status])=="delay issue 1") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 1<?php }else{ ?>Delay Issue 1<?php } ?>
                      </option>

                      <option value="delay issue 2"<?php if(strtolower($orderData[order_status])=="delay issue 2") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 2<?php }else{ ?>Delay Issue 2<?php } ?>
                      </option>

                      <option value="delay issue 3"<?php if(strtolower($orderData[order_status])=="delay issue 3") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 3<?php }else{ ?>Delay Issue 3<?php } ?>
                      </option>

                      <option value="delay issue 4"<?php if(strtolower($orderData[order_status])=="delay issue 4") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 4<?php }else{ ?>Delay Issue 4<?php } ?>
                      </option>

                      <option value="delay issue 5"<?php if(strtolower($orderData[order_status])=="delay issue 5") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 5<?php }else{ ?>Delay Issue 5<?php } ?>
                      </option>
                      
                      <option value="delay issue 6"<?php if(strtolower($orderData[order_status])=="delay issue 6") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Délai 6<?php }else{ ?>Delay Issue 6<?php } ?>
                      </option>

                      <option value="filled"<?php if(strtolower($orderData[order_status])=="filled") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Expédiée<?php }else{ ?>Shipped<?php } ?>
                      </option>

                      <option value="cancelled"<?php if(strtolower($orderData[order_status])=="cancelled") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Annulée<?php }else{ ?>Cancelled<?php } ?>
                      </option>

                      
                   
                      
                      <option value="waiting for frame"<?php if(strtolower($orderData[order_status])=="waiting for frame") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture<?php }else{ ?>Waiting for Frame<?php } ?>
                      </option>
							
							 <option value="waiting for frame swiss"<?php if(strtolower($orderData[order_status])=="waiting for frame swiss") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture SWISS<?php }else{ ?>Waiting for Frame SWISS<?php } ?>
                      </option>
							
                      
                       <option value="waiting for frame hko"<?php if(strtolower($orderData[order_status])=="waiting for frame hko") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture HKO<?php }else{ ?>Waiting for Frame HKO<?php } ?>
                      </option>
					  
					  
					   <option value="waiting for frame gkb"<?php if(strtolower($orderData[order_status])=="waiting for frame GKB") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture GKB<?php }else{ ?>Waiting for Frame GKB<?php } ?>
                      </option>
					  
					   <option value="waiting for frame knr"<?php if(strtolower($orderData[order_status])=="waiting for frame KNR") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture KNR<?php }else{ ?>Waiting for Frame KNR<?php } ?>
                      </option>
                     
					  <option value="waiting for frame ovg"<?php if(strtolower($orderData[order_status])=="waiting for frame OVG") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture OVG<?php }else{ ?>Waiting for Frame OVG<?php } ?>
                      </option>
					
					 <option value="waiting for frame procrea"<?php if(strtolower($orderData[order_status])=="waiting for frame PROCREA") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de monture PROCREA<?php }else{ ?>Waiting for Frame PROCREA<?php } ?>
                      </option>
                      
					  <option value="waiting for lens"<?php if(strtolower($orderData[order_status])=="waiting for lens") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de verres<?php }else{ ?>Waiting for Lens<?php } ?>
                      </option>

                      
					  <option value="waiting for shape"<?php if(strtolower($orderData[order_status])=="waiting for shape") echo " selected";?>>
                      <?php if ($mylang == 'lang_french') {?>Attente de forme<?php }else{ ?>Waiting for Shape<?php } ?>
                      </option>

                      
					  <option value="re-do"<?php if(strtolower($orderData[order_status])=="re-do") echo " selected";?>>
                        <?php if ($mylang == 'lang_french') {?>Reprise interne<?php }else{ ?>Redo<?php } ?>
                      </option>
                     
                      
                      <option value="verifying"<?php if(strtolower($orderData[order_status])=="verifying") echo " selected";?>>
                        <?php if ($mylang == 'lang_french') {?>Inspection<?php }else{ ?>Verifying<?php } ?>
                      </option>
							
					  <option value=""<?php if(strtolower($orderData[order_status])=="") echo " selected";?>>
                        <?php if ($mylang == 'lang_french') {?> <?php }else{ ?> <?php } ?>
                      </option>
					
                    </select>
					
					
					<?php   

						 if(strtolower($orderData[order_status])!="filled") 
						 {?>
                   
                   <?php if ($update_status == "yes")
				   {  ?> 
                    <input name="update_order_status"   <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?> type="submit" id="update_order_status" value="<?php echo $btn_update_txt;?>" class="formField3">
                   
                   
              <?php }
						  } ?>
                    
                    
                    
                    <input name="from_status_update" type="hidden" id="from_status_update" value="true">
                    <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
                    <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
                    <input name="order_id" type="hidden" id="order_id" value="<?php echo $orderData[primary_key];?>">
                    <input name="order_product_id" type="hidden" id="order_product_id" value="<?php echo $orderData[order_product_id];?>">
                    <input name="order_date_processed" type="hidden" id="order_date_processed" value="<?php echo $orderData[order_date_processed];?>">
			  </form></td>
			  <td width="37%" align="center" valign="middle" bgcolor="#999999"><form action="display_order.php" method="post" name="confirmForm" id="confirmForm">
              
                  <input name="resend_order" type="submit" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?>  <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?> id="resend_confrim" value="<?php echo $btn_resendordercust_txt;?>" class="formField3">
                  <input name="from_send_order" type="hidden" id="from_send_order" value="true">
                  <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
				  <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
              
			    </form></td>
			  <td width="25%" align="center" valign="middle" bgcolor="#999999"><form action="display_order.php" method="post" name="confirmForm2" id="confirmForm2">
                
                  <input name="resend_confirm2"  <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?>  type="submit" id="resend_confirm2" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?> value="<?php echo $btn_resendorderlabs_txt;?>" class="formField3">
                  <input name="from_send_order_lab" type="hidden" id="from_send_order_lab" value="true">
                  <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
				  <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
                
			    </form></td>
			</tr>
			<tr>
			  <td width="20%" rowspan="2" align="right" valign="middle"><div class="formField2"><?php echo $adm_ordernumber2_txt;?></div></td>
			  <td width="18%" rowspan="2" valign="middle"><span class="formField2"><?php echo $_GET[order_num];?></span>
			  <?php if ($orderData[redo_order_num]!=0) echo "R (".$orderData[redo_order_num].")";?> 
             
              <?php if ($mylang == 'lang_french') {?>
              <br><a target="_blank" href="status_history.php?order_num=<?php echo $_GET[order_num];?>">HISTORIQUE DE STATUTS</a></td>
              <?php }else{ ?>
			  <a target="_blank" href="status_history.php?order_num=<?php echo $_GET[order_num];?>">STATUS HISTORY</a></td>
  			  <?php } ?>
              
			  <td colspan="2" align="center" valign="middle" bgcolor="#999999"><form action="display_order.php" method="post" name="confirmForm3" id="confirmForm3">
             
                
                  <select name="manual_lab" class="formField3" id="manual_lab" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?>>
                    <?php
	$query="select primary_key, lab_name,lab_email from labs WHERE primary_key NOT IN (30,8, 11, 12,15,19,23,24,42,40,26,38,28,29,31,32,34,33,45,37,47,44,66,67,63,36,22,43,39,41,64,56,53,58,62,59,61,35,52) order by lab_name";
	$result=mysqli_query($con,$query) or die ($adm_lablist_txt);
		echo "<option value=\"\""; echo " selected"; echo ">".$adm_selectlab_txt."</option>";
	while ($labList=mysqli_fetch_array($result,MYSQLI_ASSOC)){
		echo "<option value=\"$labList[primary_key]\">$labList[lab_name]</option>";
}
?>
                  </select>
                  <input name="resend_confirm3" type="button" id="resend_confirm3" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?> value="<?php echo $btn_sendorderlab_txt;?>" class="formField3"  <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  ?>  onClick="this.form.printit.value=false; this.form.submit();">&nbsp;
                  <input name="resend_confirm3_p" type="button" id="resend_confirm3_p" value="<?php echo $btn_printorder_txt;?>" class="formField3" onClick="this.form.target='_blank'; this.form.printit.value=true; this.form.submit();"> 
                  <!-- "  document.getElementById('printit_id') -->
                  <input name="printit" type="hidden" id="printit_id" value="false" />
                  <input name="from_send_order_lab_manual" type="hidden" id="from_send_order_lab_manual" value="true">
                  <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
				  <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
                
			    </form></td>
			  </tr>
			<tr><td colspan="2" rowspan="3" align="center" bgcolor="#FFFFFF" class="formField2"><form name="form3" method="post" action="display_order.php"><input name="from_additional_dsc" type="hidden" id="from_additional_dsc" value="true">
			      <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
			      <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
			      <div class="formField2"><?php echo $adm_adddisc_txt;?> </div>
			      <div class="formField"><?php echo $adm_typebypercent_txt;?>(DISABLED)
			      
			        <input name="discount_type" type="radio" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled')){
				 echo 'disabled="disabled"';
				 }
				 
				 if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  ?>  value="%" disabled <?php if ($orderData[discount_type]=="%") echo "checked"?> >
			      %
			      <input name="additional_dsc_percentage" <?php 
				// if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				
				 if ($manage_additional_discount == 'no'){
				 echo 'disabled="disabled"';
				 }
				 
				  ?>  type="text" class="formField3" id="additional_dsc_percentage" value="<?php  if ($orderData[discount_type]=="%") echo "$orderData[additional_dsc]"?>" size="7" >
			      &nbsp;&nbsp;
			      <?php echo $adm_byamount_txt;?> 
			       <input name="discount_type" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled') ) 
				 echo 'disabled="disabled"';
				  if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				 
				  if ($manage_additional_discount == 'no'){
				 echo 'disabled="disabled"';
				 }
				 
				  ?>  type="radio" value="$" <?php if ($orderData[discount_type]=="$") echo "checked"?> >
			       
			      $
			      <input name="additional_dsc_dollar" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				 
				 if ($manage_additional_discount == 'no'){
				 echo 'disabled="disabled"';
				 }
				 
				  ?>  type="text" class="formField3" id="additional_dsc_dollar" value="<?php  if ($orderData[discount_type]=="$") echo "$orderData[additional_dsc]"?>" size="7" >
			       &nbsp;<?php echo $adm_none_txt;?>
			       <input name="discount_type" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  if ($manage_additional_discount == 'no'){
				 echo 'disabled="disabled"';
				 }
				  ?>  type="radio" value="none"  <?php if ($orderData[discount_type]=="") echo "checked"?> >
			       <input name="Submit" <?php 
				 if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled'))
				 echo 'disabled="disabled"';
				  if ($PmtType == 'CC'){
				 echo 'disabled="disabled"';
				 }
				  if ($manage_additional_discount == 'no'){
				 echo 'disabled="disabled"';
				 }
				  ?>   type="submit" class="formField3" value="<?php echo $btn_update_txt;?>" ></div></form>
                   <div></div>
                   
                   <?php if ($mylang == 'lang_french'){?>
                   <h3>Le rabais par pourcentage(%) ne s'appliquera <b>pas</b> sur tous les items en extra (teinte, taillage, etc).
                  Si vous désirez donner une commande  (100% gratuite),<br> Svp utiliser le rabais par montant($).</h3></td>
                   <?php }else{ ?>
                   <h3>Rebate by Percentage(%) will <b>not</b> give any rebate on extra products(tint, edging, etc.)
                   If you want to give a free order please use the rebate by amount($).</h3></td>
                   <?php } ?>
                   
			  </tr>
              
              <tr><td align="right"><div class="formField3"><?php echo 'Frame sent Swiss:';?> </div></td>
			  <td align="left"><span class="formField3"><?php echo  $orderData[frame_sent_swiss];?></span></td>
			  </tr>
              
              
			<tr><td align="right"><div class="formField3"><?php echo $adm_ponumber_txt;?> </div></td>
			  <td align="left"><span class="formField3"><?php echo  $_GET[po_num];?></span></td>
			  </tr>
			<tr>
				<td height="22" align="right"><div class="formField3">
						<?php echo $adm_patientrefnumber_txt;?>
				</div></td>
				<td align="left"><span class="formField3"><?php echo  $orderData[patient_ref_num];?> </span></td>
                
                
                <?php 
              $queryAcct = "SELECT account_num FROM accounts WHERE user_id =  '$orderData[user_id]'"; 
			  $resultAcct=mysqli_query($con,$queryAcct)		or die ('Error' . $queryAcct);
			  $DataAcct=mysqli_fetch_array($resultAcct,MYSQLI_ASSOC);
                ?>
              
                
			</tr>
            <tr>
              <td height="22" align="right"><div class="formField3">
						<?php echo 'Account num : ';?>
				</div></td>
				<td align="left"><span class="formField3"><?php echo $DataAcct[account_num];?> </span></td>
            </tr>
			</table>
			</td></tr>
			<tr><td><?php
			$query  = "SELECT lab_name FROM labs WHERE primary_key = ". $orderData[prescript_lab];
			$result=mysqli_query($con,$query)		or die ('Error');
		    $labName=mysqli_fetch_array($result,MYSQLI_ASSOC);
			$the_lab =  $labName['lab_name'];
			
			
		     if ($mylang == 'lang_french')
			 echo 'Fabriquant: <b>'        . $the_lab . '</b>&nbsp;&nbsp;&nbsp;Trace: <b>' . $orderData[shape_name_bk] . '</b>';
			 else
			 echo 'Manufacturing Lab: <b>' . $the_lab . '</b>&nbsp;&nbsp;&nbsp;Shape: <b>' . $orderData[shape_name_bk] . '</b>';
			 ?>
			 <br><br>
             

             
             <form name="form3" method="post" action="display_order.php">
             
			 <?php if ($mylang == 'lang_french') {?>
             <span><strong><?php echo 'CHANGER DE COMPTE';?></strong></span>
             <?php }else{ ?>
             <span><strong><?php echo 'CHANGE ACCOUNT';?></strong></span>
             <?php } ?>
             
            <select  <?php if ($PmtType == 'CC') echo 'disabled';  ?> name="user_id" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?> id="user_id" class="formField" <?php  if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled')) echo 'disabled';?>>
            <?php
			$orderQuery2="SELECT * FROM orders WHERE order_num='$_REQUEST[order_num]'";
			$orderResult2=mysqli_query($con,$orderQuery2)		or die  ('I cannot select items because f6: ' . mysqli_error($con));
			$orderData2=mysqli_fetch_array($orderResult2,MYSQLI_ASSOC);
			
			
            $query="SELECT primary_key, company, last_name, first_name, user_id, product_line FROM accounts WHERE main_lab='$_SESSION[lab_pkey]' and approved='approved' order by product_line, company, last_name";
		    $resultAcct=mysqli_query($con,$query) or die ($adm_error1_txt);
            while ($accountList=mysqli_fetch_array($resultAcct,MYSQLI_ASSOC)){
				$ProductLine = "";
				switch ($accountList[product_line]){
					case 'eye-recommend':   $ProductLine = 'Prestige';	  break;
					case 'lensnetclub': 	$ProductLine = 'LensnetClub'; break;
					case 'aitlensclub':     $ProductLine = 'AitlensClub'; break;
					case 'directlens':      $ProductLine = 'Direct-Lens'; break;
					case 'safety': 		    $ProductLine = 'Safety';	  break;
					case 'ifcclubca':	    $ProductLine = 'Ifc.ca';	  break;
					case 'ifcclub': 		$ProductLine = 'Ifc.com';	  break;
					case 'milano6769':      $ProductLine = 'Milano6769';  break;
				}
            	
				echo "<option value=\"$accountList[user_id]\">$ProductLine&nbsp;&nbsp;$accountList[company], $accountList[first_name] $accountList[last_name]&nbsp;&nbsp; <b>$accountList[user_id]</b></option>";
            }
            ?>
            </select>
					
                    <input name="update_order_account"  <?php  if (($orderData[order_status]=='cancelled') || ($orderData[order_status]=='filled')) echo 'disabled';?>
                    <?php if ($PmtType == 'CC') echo 'disabled';  ?>
                      type="submit" <?php if ($update_status <> 'yes') echo 'disabled="disabled"'; ?>  id="update_order_account" value="<?php  if ($mylang == 'lang_french') { echo 'Déplacer dans ce compte';}else{echo 'Switch job to this account';}?>" class="formField3">
                    <input name="from_account_update" type="hidden" id="from_account_update" value="true">
                    <input name="order_num" type="hidden" id="order_num" value="<?php echo $_GET[order_num];?>">
                    <input name="order_id" type="hidden" id="order_id" value="<?php echo $orderData[primary_key];?>">
				    <input name="po_num" type="hidden" id="po_num" value="<?php echo $_GET[po_num];?>">
			  </form>
             

             
			 <?php
			include("accountData.inc.php");
			$order_num=$_GET[order_num];
			
			$totalTrayQuant=0;
			$totalBulkQuant=0;
			$totalStockFrameQuantity=0;
			$prescrQuantity=0;
					
			$lab_pkey=$_SESSION["lab_pkey"];
			
			//STOCK TRAY SECTION
			
			$query="SELECT * FROM orders WHERE lab='$lab_pkey' and order_num='$order_num' and order_product_type='stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN TRAY STOCK ORDERS
			$result=mysqli_query($con,$query)					or die  ('I cannot select items because g7: ' . mysqli_error($con));
			$stocktraycount=mysqli_num_rows($result);
			
			if ($stocktraycount != 0){
			
				echo  "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formField3\">
<tr ><td colspan=\"8\" bgcolor=\"#000000\" class=\"formField3\"><font color=\"#FFFFFF\">STOCK ITEMS � BY TRAY</font></td>
  </tr></table>";
					
						while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){

						$order_shipping_method=$listItem[order_shipping_method];
						$order_shipping_cost=$listItem[order_shipping_cost];
						$extra_product_price=+$listItem[extra_product_price];
						$counter++;
					$itemSubtotal=$itemSubtotal+$listItem[order_product_price];
					
					$itemSubtotalDsc=$itemSubtotalDsc+$listItem[order_product_discount];
					
					$totalTrayQuant=$totalTrayQuant+$listItem[order_quantity];
					if ($counter%2==0){
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("stockTrayHistoryLE.inc.php");
						$totalPrice=$totalPrice+$itemSubtotal;
						$totalStockPrice=$totalStockPrice+$itemSubtotal;
						
						$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
						$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
						$itemSubtotalDsc=0;
						$itemSubtotal=0;}
					else{
						include("stockTrayHistoryRE.inc.php");
					}
					} 
			
			
			}//End tray display section
			

			
			
			
			//FRAMES STOCK SECTION
			$StockFrameOrder = 'no';
						$query="SELECT * FROM orders WHERE lab='$lab_pkey' and order_num='$order_num' and order_product_type='frame_stock_tray' ORDER by order_item_date,primary_key";//SELECT ALL OPEN FRAME STOCK ORDERS
			$result=mysqli_query($con,$query)		or die  ('I cannot select items because: ' . mysqli_error($con));
			$totalStockFrameQuantity=mysqli_num_rows($result);
			
			if ($totalStockFrameQuantity != 0){
			$StockFrameOrder = 'yes';
				echo  "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formField3\">
<tr ><td colspan=\"8\" bgcolor=\"#000000\" class=\"formField3\"><font color=\"#FFFFFF\">FRAMES STOCK ITEMS</font></td>
  </tr></table>";
					
						while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){

						$order_shipping_method=$listItem[order_shipping_method];
						$order_shipping_cost=$listItem[order_shipping_cost];
						$extra_product_price=+$listItem[extra_product_price];
						$counter++;
					$itemSubtotal=$itemSubtotal+$listItem[order_product_price];
					
					$itemSubtotalDsc=$itemSubtotalDsc+$listItem[order_product_discount];
					
					$totalTrayQuant=$totalTrayQuant+$listItem[order_quantity];
						include("stockFramesHistory.inc.php");

					} 
			}//End Stock Frames  display section
			


			//STOCK BULK SECTION
			
			$query2="SELECT * from orders WHERE lab='$lab_pkey' and order_num='$order_num' and order_product_type='stock' ORDER by order_item_date";//SELECT ALL OPEN STOCK ORDERS
			$result2=mysqli_query($con,$query2)		or die  ($lbl_error1_txt . mysqli_error($con));
			$stockusercount=mysqli_num_rows($result2);
			if ($stockusercount != 0){
			
			 echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formField3\">
              <tr>
                <td bgcolor=\"#000000\"><font color=\"white\">".$lbl_stockitemsbulk_txt."</font></td>
              </tr>
            </table>";
					
					while ($listItem=mysqli_fetch_array($result2,MYSQLI_ASSOC)){
					$order_shipping_method=$listItem[order_shipping_method];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$extra_product_price=+$listItem[extra_product_price];
					$itemSubtotal=0;
					$itemSubtotal=$listItem[order_quantity]*$listItem[order_product_price];
					$totalPrice=$totalPrice+$itemSubtotal;
					$totalStockPrice=$totalStockPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*$listItem[order_product_discount];
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					$totalStockPriceDsc=$totalStockPriceDsc+$itemSubtotalDsc;
					
					$totalBulkQuant=$totalBulkQuant+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("stockHistory.inc.php");
					} 
			}
			
						$query="SELECT * from orders WHERE lab='$lab_pkey' and order_num='$order_num' and order_product_type='exclusive' ORDER by order_item_date";//SELECT ALL OPEN PRESCRIPTION ORDERS
			$result=mysqli_query($con,$query)		or die  ($lbl_error1_txt . mysqli_error($con));
			$usercount=mysqli_num_rows($result);
			if ($usercount != 0){
			
			 echo "<table width=\"100%\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formField3\">
              <tr>
                <td bgcolor=\"#000000\"><font color=\"#FFFFFF\">".$lbl_presitems_txt."&nbsp;</font></td>
              </tr>
            </table>";
					
					while ($listItem=mysqli_fetch_array($result,MYSQLI_ASSOC)){
						
					$bl_query="SELECT * from additional_discounts WHERE orders_id='$listItem[primary_key]'";//GET BUYING LEVEL DISCOUNT
					$bl_result=mysqli_query($con,$bl_query) or die  ($lbl_error1_txt . mysqli_error($con));
					$bl_listItem=mysqli_fetch_array($bl_result,MYSQLI_ASSOC);
					$buying_level_dsc=$bl_listItem[buying_level_discount];
					
					
					$e_query="SELECT * FROM extra_product_orders WHERE order_id='$listItem[primary_key]'";//GET EXTRA PRODUCT PRICES
					$e_result=mysqli_query($con,$e_query)	or die  ($lbl_error1_txt . mysqli_error($con));
					$e_usercount=mysqli_num_rows($e_result);
					$e_total_price=0;
					$e_products_string="";
					$e_order_string_engraving="";
					$e_order_string_tint="";
					$e_order_string_edging="";
					$e_order_string_frame="";
					//COLLECT FRAME DATA from orders table into string to be overwritten if there is edging data
					$e_order_string_edging="<b>".$adm_type_txt." </b>".$listItem[frame_type]." ";
					$e_order_string_edging.="<b>".$adm_eyea_txt."</b>".$listItem[frame_a]." ";
					$e_order_string_edging.="<b>".$adm_b_txt."</b>".$listItem[frame_b]." ";
					$e_order_string_edging.="<b>".$adm_ed_txt."</b>".$listItem[frame_ed]." ";
					$e_order_string_edging.="<b>".$adm_dbl_txt."</b>".$listItem[frame_dbl]." ";
				
					if ($e_usercount !=0){
						while ($e_listItem=mysqli_fetch_array($e_result,MYSQLI_ASSOC)){
							$e_total_price=$e_total_price+$e_listItem[price]+$e_listItem[high_index_addition];
							
							
					if ($e_listItem[category]=="Mirror"){
					//			$e_products_string.="<br />".'MIRROR:'." ".$e_listItem[price];
					}
				
				
				if ($e_listItem[category]=="Edging"){
								$e_products_string.="<br />".$adm_edging_txt." ".$e_listItem[price];
								
								$e_order_string_edging="<b>".$adm_type_txt."</b>".$e_listItem[frame_type]." ";
								$e_order_string_edging.="<b>".$adm_jobtype_txt." </b>".$e_listItem[job_type]." ";
								$e_order_string_edging.="<b>".$adm_ordertype_txt." </b>Frame ".$e_listItem[order_type]."<br>";
								
								$e_order_string_edging.="<b>".$adm_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_edging.="<b>".$adm_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_edging.="<b>".$adm_ed_txt." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_edging.="<b>".$adm_dbl_txt." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_edging.="<b>".$adm_temple_txt." </b>".$e_listItem[temple]."<br>";
								
								$e_order_string_edging.="<b>".$adm_supplier_txt." </b>".$e_listItem[supplier]." ";
								$e_order_string_edging.="<b>".$adm_shpmod_txt." </b>".$e_listItem[model]." ";
								$e_order_string_edging.="<b>".'Frame Model'." </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_edging.="<b>".$adm_color_txt." </b>".$e_listItem[color]."<br>";
								}

							if ($e_listItem[category]=="Engraving"){
								
								$e_products_string.="<br />".$adm_engraving_txt." ".$e_listItem[price];
								
								$e_order_string_engraving="<b>".$adm_engraving_txt." </b>".$e_listItem[engraving]." ";}
								
								
								
							if ($e_listItem[category]=="Tint"){
								
								$e_products_string.="<br />".$adm_tint_txt." ".$e_listItem[price];
								
								$e_order_string_tint="<b>".$adm_tint_txt." </b>".$e_listItem[tint].": ";
								if ($e_listItem[tint]=="Solid")
									{ $e_order_string_tint.= $e_listItem[from_perc]."% <b>".$adm_color_txt."</b> ".$e_listItem[tint_color];}
								else
									{$e_order_string_tint.= $e_listItem[from_perc]."% to ".$e_listItem[to_perc] ."% <b>".$adm_color_txt."</b> ".$e_listItem[tint_color];}
								}//END IF TINT
								
								
								
							
								
								/* pour IFC */
								
								if ($e_listItem[tint]=="Solid 82")	{
									$e_listItem[tint_color];
								}
								if ($e_listItem[tint]=="Solid 60")	{
									$e_listItem[tint_color];
								}								
								/* pour IFC */							
							
							if ($e_listItem[category]=="Prism"){
								$e_products_string.="<br />".$adm_prism_txt." ".$e_listItem[price];
								}
								
								
								
							if (($e_listItem[category]=="Frame") && ($mylang == 'lang_french')){
								
								if ($e_listItem[job_type] == 'Edge and Mount')
								$JobType = 'Taillé-Monté';
								else 
								$JobType  = $e_listItem[job_type];
							 
							 
							 if ($e_listItem[order_type]=='Provide')
							 $OrderType = 'Monture Fournie';
							 elseif($e_listItem[order_type]<> 'Provide')
							 $OrderType = 'Monture à envoyer';
							 
							 
								$e_products_string.="<br />".$adm_frame_txt." "		   . $e_listItem[price];
								$e_products_string.="<br />".$adm_hiindex_txt." "	   . $e_listItem[high_index_addition];
								$e_order_string_frame="<b>".$adm_type_txt." </b>"	   . $e_listItem[frame_type]." ";
								$e_order_string_frame.="<b>".$adm_jobtype_txt." </b>"  . $JobType." ";
								$e_order_string_frame.="<br><b>".'Statut:'." </b> "	   . $OrderType."<br>";
								$e_order_string_frame.="<b>".$adm_eyea_txt."</b>"	   . $e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>".$adm_b_txt." </b>"		   . $e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>".$adm_ed_txt." </b>"	   . $e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>".$adm_dbl_txt." </b>"	   . $e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>".$adm_temple_txt." </b>"   . $e_listItem[temple]."<br>";
								$e_order_string_frame.="<b>".$adm_supplier_txt." </b>" . $e_listItem[supplier]." ";
								$e_order_string_frame.="<b>".'Forme:'." </b>"		   . $e_listItem[model]." ";
								$e_order_string_frame.="<b>".'Modèle:'." </b>"		   . $e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>".$adm_color_txt.": </b>"    . $e_listItem[color]."<br>";
							}elseif($e_listItem[category]=="Frame"){
								$e_products_string.="<br />".$adm_frame_txt." ".$e_listItem[price];
								$e_products_string.="<br />".$adm_hiindex_txt." ".$e_listItem[high_index_addition];
								$e_order_string_frame="<b>".$adm_type_txt." </b>".$e_listItem[frame_type]." ";
								$e_order_string_frame.="<b>".$adm_jobtype_txt." </b>".$e_listItem[job_type]." ";
								$e_order_string_frame.="<br><b>".'Status:'." </b> ".$e_listItem[order_type]."<br>";
								$e_order_string_frame.="<b>".$adm_eyea_txt."</b>".$e_listItem[ep_frame_a]." ";
								$e_order_string_frame.="<b>".$adm_b_txt." </b>".$e_listItem[ep_frame_b]." ";
								$e_order_string_frame.="<b>".$adm_ed_txt." </b>".$e_listItem[ep_frame_ed]." ";
								$e_order_string_frame.="<b>".$adm_dbl_txt." </b>".$e_listItem[ep_frame_dbl]." ";
								$e_order_string_frame.="<b>".$adm_temple_txt." </b>".$e_listItem[temple]."<br>";
								$e_order_string_frame.="<b>".$adm_supplier_txt." </b>".$e_listItem[supplier]." ";
								$e_order_string_frame.="<b>".$adm_shpmod_txt." </b>".$e_listItem[model]." ";
								$e_order_string_frame.="<b>".$adm_framemod_txt." </b>".$e_listItem[temple_model_num]." ";
								$e_order_string_frame.="<b>".$adm_color_txt." </b>".$e_listItem[color]."<br>";
							}//END IF
						}//END WHILE
						$e_total_price=money_format('%.2n',$e_total_price);
					}
					$order_shipping_method=$listItem[order_shipping_method];
					$extra_product=$listItem[extra_product];
					$extra_product_price=$listItem[extra_product_price];
					$order_shipping_cost=$listItem[order_shipping_cost];
					$itemSubtotal=0;
					$over_range=$listItem[order_over_range_fee];
					$coupon_dsc=$listItem[coupon_dsc];
					//Charles
					//$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc;
$itemSubtotal=$listItem[order_quantity]*($listItem[order_product_price]+$e_total_price)+$over_range-$coupon_dsc;
					$totalPrice=$totalPrice+$itemSubtotal;
					
					$itemSubtotalDsc=$listItem[order_quantity]*($listItem[order_product_discount]+$e_total_price)+$over_range+$extra_product_price-$coupon_dsc+$buying_level_dsc;
					
					$totalPriceDsc=$totalPriceDsc+$itemSubtotalDsc;
					
					$prescrQuantity=$prescrQuantity+$listItem[order_quantity];
					$itemSubtotal=money_format('%.2n',$itemSubtotal);
						include("prescrOrderHistory.inc.php");
					} 
			}
			
			?>
				
				<?php if (($usercount!=0)||($stockusercount!=0)||($stocktraycount!=0)||($totalStockFrameQuantity!=0))
					include("displayHistoryFooter.inc.php");
				
				$pmtQuery="SELECT pmt_amount FROM payments WHERE order_num='$order_num'";
				$pmtResult=mysqli_query($con,$pmtQuery) or die  ('I cannot select items because h8: ' . mysqli_error($con));
				$pmtData=mysqli_fetch_array($pmtResult,MYSQLI_ASSOC);
				?>
	<div class="formField3">
		<a href="/labAdmin/report.php"><?php echo $adm_backtoorderlist_txt;?></a>
	</div></td>
  </tr>
</table>
 &nbsp;<br>
</td>
	  </tr>
</table>
  <p>&nbsp;</p>
  
</body>
</html>
