<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/connexion_hbc.inc.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
    
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}
.select1 {width:100px}
-->
</style>

</head>
<body>
<div id="container">
  	<div id="masthead">
  		<img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/hbc-masthead.jpg" width="1050" height="175" alt="MyBBGClub"/>
	</div>
	<div id="maincontent">
		<div id="leftColumn">
			<div id="leftnav">
  				<?php include("includes/sideNav.inc.php"); ?>
			</div><!--END leftnav-->
		</div><!--END leftcolumn-->
        <div id="rightColumn">
            <div class="loginText">
                <div class="loginText">
                    <?php echo $lbl_user_txt;?> <?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;
                    <a href='logout.php'><?php echo $lbl_btn_logout;?></a>
                    <?=$_SESSION["sessionUser_Id"];?>
                </div>
            </div>
            <div class="header">Terms and Conditions of Use </div>
            <div class="messageText" style="line-height:15px">
  
				
<?php /*				
<div class="header">Acceptance of Services</div> 

<p>IFC Club website offers their users through the Internet an access to the available information while connecting. These general conditions are applicable to whole products and services provided by IFC Club. By using offered services, the client declares using services provided by IFC Club in accordance with the applicable right and these conditions.</p>

<div class="header">Username and Password </div> 
<p>You are responsible for the confidentiality of your identity. IFC Club can’t be held liable for an unauthorized use of your account. If the confidentiality of your personal details is compromised, you must advise our service as soon as possible in order to avoid a probable fraudulent use of your account. IFC Club will not be held liable for any loss or damage resulting from the non-observance of safety rules mentioned in this section. Regarding this point, it’s important to read carefully the RESPONSIBILITY LIMITS included in these conditions in order to be aware of your responsibility for IFC Club services.</p>

<div class="header">External Links</div>  
<p>IFC Club can establish a link with others websites or sources. IFC Club doesn’t have a means to control these external sources and can’t guarantee their availability. Furthermore, IFC Club  can’t approve contents of these websites or sources and any responsibility for their contents is excluded. </p>

<div class="header">Responsibility Limit</div>  
<p>IFC Club, subsidiary companies and companies affiliated to the IFC Club Website will not be held liable for damages as well as for direct, indirect, compensatory damages or any other damage to users, any person or legal entity, caused by technical or technological equipment in ifcclub.ca or due to a technical or a human mistake that can happen at any step, technical defect of the network (electronic or communication), computer systems online, servers, computer equipment, software, data transmissions or any other technical problem such as web congestion or the congestion of the website. </p>

<div class="header">Purpose </div>  
<p>These sales conditions aim to define contractual relations and obligations between IFC Club  and the purchaser as well as applicable conditions to any purchase done through the website ifcclub.ca. 
  The purchase of a product through this website involves an unconditional acceptance of these sales conditions by the purchaser to the full exemption from any partner laboratory of DirectLab network. 
  These conditions will prevail over any other general or particular conditions not expressly agreed by IFC Club. 
  IFC Club can modify its sales conditions at any time. In that case, applicable conditions will be the ones in force from the order date by the purchaser. 
  If necessary, you will be informed about modifications to the conditions below through email. 
  To open an account on IFC Club, you must provide a valid email address. </p>
<div class="header">Characteristics of Goods and Services </div>  
<p>Products and services are those listed in the catalog published on IFC Club website. 
  Each order is accompanied by a copy of the order placed and prescription.  </p>
<p>IFC Club is an online service where special requests cannot be made. For special requests, we offer the option of ordering online via DirectLab (www.direct-lens.com) through which full techni¬cal support and special requests can be made.</p>
<p> If a lens is unavailable, IFC Club will replace your product with another of equal or greater value. The replacement will be indicated on the invoice. 
  An extended warranty program is available at various costs depending on the package and prescription and can be requested during the online purchase in the appropriate section. </p>
<div class="header">Rates</div>   
<p>Fees listed in the catalog are prices “a la carte.“ The prices listed in the order page do not include the cost of order processing, transportation and delivery if they occur outside the geographical boundaries as listed below and in accordance with these conditions. 
  IFC Club reserves the right to change prices at any time, except that the price displayed on the order page the day the order was placed. </p>
<div class="header">Additional Rates </div>  
<p>Fax orders are possible at $2 per pair. A toll free number will be provided on request to an accredited laboratory. 
  A processing fee of $1 applies or each order. 
  Transportation is free for a minimum of 3 pairs of lenses a day or with an amount equal to or greater than $100. (According to geographical areas provided below). This minimum is required to meet total shipping costs by ground or additional fees will be charged. </p>
<div class="header">Area</div>  
<p>The online sale of products and services presented in this site is for buyers who reside in Canada and continental United States. For all other and international inquiries, please contact the custo¬mer service for our shipping rates. For a service overnight in continental USA by FedEx or UPS, the freight cost will be shared 50-50% with a minimum of 3 orders.. </p>
<div class="header">Orders </div>  

<p>The buyer who wishes to buy a product or service must: 

<ul>
  <li>Open an account which shows all data requested and a valid email address.</li>
  <li>Complete the online order form providing all relevant patients’ references, the frame, the prescription and product characteristics; </li>
  <li>Validate the order after reviewing it; </li>
  <li>Make payment in accordance with required conditions: (VISA, MASTERCARD, AMEX available up to such time as a credit limit is approved); </li>
  <li>Confirming the order and by payment. </li>
</ul>
<p>The confirmation of the order involves the acceptance of these sales conditions, the recognition of having complete perfect knowledge and waiver of its own conditions of purchase or other conditions. </p>
<p>All data provided and the confirmation recorded will be the valid proof of the transaction. The confirmation constitutes signing and acceptance of transactions. IFC Club will email confirmation of the order recorded. IFC Club is an online sales system that confirms at every step the production status of an order as well as the estimated shipping date. </p>
<div class="header">Withdrawal</div>   

<p>Beyond a 30 minute cut-off, IFC Club is unable to cancel an order already in progress. If an order is placed and must be withdrawn, the client must call customer service to receive a cancelation number. No credits will be approved without the cancellation number. </p>
<div class="header">Payment Terms </div>  

<p>Payment is due upon order.
  Payments will be made by credit card and will be made through SSL (Secure Socket Layer) such that the transmitted information is encrypted by software where a third party is unable to read it in transit on the network. 
  The buyer’s account will be debited once the order is placed. </p>
<div class="header">Delivery </div>  
<p>Deliveries are made to the address indicated in the client account and are shipped by ground. If the shipment is requested as a “special delivery,” IFC Club will share the cost at 50% with a minimum placement of 3 orders. 
  The purchaser is responsible for any risk when products have left the Laboratory or its accredited representative.  </p>
<p>In case of damage during transportation, a complaint must be made with the carrier within three days after delivery. 
  Estimated date of shipment is only indicative. For lenses in Index CR39 (with a combined power (maximum 8D) from +6 to -8, cyl -3.00 without prism). </p>
<div class="header">Warranty </div>  
<p>There is no after-sale warranty with the exception of buying an extended warranty on the Hard Coat, AR coatings or non-adapt. We are able to offer our low prices up to 60% of usual prices by elimi¬nating the costs associated with order management, returns and credit administration.  </p>
<p>IFC Club  guarantees the quality and tolerances for each order. In case of non-compliance of a product received, please return the defective lenses and IFC Club will issue a credit if warranted in accordance with the verification of the partner laboratory. 
  All claims, requests for exchange or refund must be made by post with a DirectLab laboratory in charge of order management within 20 days of the receipt of the order.  </p>
<p>A Customer Service Agent will contact you upon approval of your account for future transactions. </p>
<div class="header">Disclaimer </div>  
<p>The vendor, in the process of selling online, only has an obligation of means; his liability cannot be held for damages resulting from the use of Internet such as data loss, hacking, viruses, service breaks or other unintended issues or problems arising. </p>
<div class="header">Intellectual Property </div>  
<p>All elements from IFC Club website remain the exclusive intellectual property of IFC Club. 
  It is strictly prohibited to reproduce in whole or in part and to repost or use for any purpose whatsoe¬ver any of the element of IFC Club including the price lists, software and videos. </p>
<div class="header">Filing – Proof</div>   
<p>IFC Club will keep order forms as well as invoices on a reliable and durable support as a true copy in accordance with the laws in force. 
  IFC Club registers will be considered by the parties as proof of communications, orders, payments and other transactions between the parties. 
  I have read and accepted to the conditions as above for permission to open my account and use IFC Club for my online purchases. </p>
</div>
*/
				?>
        </div>
	</div>
    <div id="footer1">
      
    </div>
</div>



</body>
</html>
<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>