<?php 
require_once(__DIR__.'/../constants/url.constant.php');
//extract data from the post
extract($_POST);


if($_GET[frompage]=="regular_ordering_process"){
$return_url = constant('DIRECT_LENS_URL')."/lensnet/payment_thanks.php";
}elseif($_GET[frompage]=="customer_order_history"){
$return_url = constant('DIRECT_LENS_URL')."/lensnet/payment_thanks_order_history.php";
}

//set POST variables
//$url = 'https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do';//DEMO URL 
$url = 'https://www.myvirtualmerchant.com/VirtualMerchant/process.do';//LIVE URL 

if ($country=='CA')
$country='CAN';

if ($country=='US')
$country='USA';

$fields = array(
						//Information about the customer
						'ssl_first_name'	  		=> urlencode($first_name),  
						'ssl_last_name' 	 		=> urlencode($last_name),   
						'ssl_city' 	  				=> urlencode($city),	
						'ssl_phone' 		 		=> urlencode($phone),	    
						'ssl_avs_address'   		=> urlencode($address1),
						'ssl_avs_zip' 		  		=> urlencode($zip),      
						'ssl_invoice_number'    	=> urlencode($master_order_id),
						'ssl_email' 		 		=> urlencode($email),
						'ssl_address2'   			=> urlencode($address2),
						'ssl_state'	  				=> urlencode($state),       
						'ssl_country'   			=> urlencode($country),     
						
						//Information about the transaction
						'ssl_amount'   				=> urlencode($total_amount),
						'ssl_show_form'  			=> "false",
						'ssl_transaction_type' 		=> urlencode("ccsale"),//Sert à indiquer qu'il s'agit d'une Vente et non seulement une autorisation
						
						//Information  necessary because we use our own payment form
						'ssl_card_number'   		=> urlencode($cc_num),	
						'ssl_exp_date'   			=> urlencode($exp_date),		
						'ssl_cvv2cvc2'   			=> urlencode($cvc_num),	
						
						//Configure the receip of the transaction
						'ssl_result_format'         => urlencode("HTML"), 	// DEFAULT = HTML			
						'ssl_receipt_link_method'	=> urlencode("REDG"),
						'ssl_receipt_link_url'	  	=> urlencode($return_url),
						'ssl_receipt_link_text'	  	=> urlencode("Return on LensnetClub.com"),
						
						//Information about our Account with VirtualMerchant  --> DEMO CREDENTIALS
						//'ssl_merchant_id'		     => urlencode("000309"),
						//'ssl_user_id'     		     => urlencode("webpage"),
						//'ssl_pin' 	      		     => urlencode("TCQ4LZ")
						
						//Information about our Account with VirtualMerchant --> LIVE CREDENTIALS
						'ssl_merchant_id'		     => urlencode("651103"),
						'ssl_user_id'     		     => urlencode("webpage"),
						'ssl_pin' 	      		     => urlencode("264708")
		
				);
				
//DEBUG
echo '<p align="center">Please wait while we process your transaction</p>';

//url-ify the data for the POST
foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
rtrim($fields_string, '&');

//open connection
$ch = curl_init();

//set the url, number of POST vars, POST data
curl_setopt($ch,CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

//execute post
$result = curl_exec($ch);

//close connection
curl_close($ch);

?>