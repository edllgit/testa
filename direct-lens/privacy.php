<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
require_once(__DIR__.'/../constants/aws.constant.php');
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";

$user_id=$_SESSION["sessionUser_Id"];
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Privacy Policy</title>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #B4C8F3;
}

-->
</style>
<link href="dl.css" rel="stylesheet" type="text/css">
<script src="formFunctions.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/CalendarPopup.js"></SCRIPT>
<SCRIPT LANGUAGE="JavaScript">document.write(getCalendarStyles());</SCRIPT>
<SCRIPT LANGUAGE="JavaScript" SRC="includes/date_validation.js"></SCRIPT>
<style type="text/css">
<!--
.select1 {width:100px}
-->
</style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center"><table width="917" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td><img src="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/top_piece.jpg" alt="Direct Lens" width="917" height="158"></td>
      </tr>
      <tr>
        <td valign="top" background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/mid_sect_bg_long.jpg"><table width="900" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="215" valign="top"><div id="leftColumn">
	<?php 
	include("includes/sideNav.inc.php");
	?></div></td>
    <td width="685" valign="top">
	
    
    
    <br><br>
   <div class="header">TERMS & CONDITIONS</div>
<div style="font-family:arial"><strong>Cancellations</strong>: Cancellation of an Rx will be at no charge, providing the lenses have not been generated or edged.</div>
<br><br><br>
<div style="font-family:arial"><strong>Non - Adaptation:</strong> All requests for progressive non-adapts should be returned within 90 days from the date of purchase, along with a copy of the original invoice and the re-do invoice number.</div>
<br><br><br>
<div style="font-family:arial"><strong>Dr. Change or Re-Do:</strong> A 15% discount on invoice applies only when the original invoice number is supplied at the time of the re-order.</div>
<br><br><br>
<div style="font-family:arial"><strong>Balanced Lenses:</strong> Less 50%</div>
<br><br><br>
<div style="font-family:arial"><strong>Return for Defect:</strong> Always include a copy of the original invoice with the return.</div>
<br><br><br>
<div style="font-family:arial"><strong>Shipping Charges:</strong> Shipping charges will be applied for orders under $40.</div>
<br><br><br>
<div style="font-family:arial"><strong>Shipping Delays:</strong> The shipping is done within 7 days following your purchase. Regarding a RUSH, please contact us.</div>
<br><br><br>
<div style="font-family:arial"><strong>Placing Orders:</strong> To avoid errors and delays, orders accepted by fax or online only. </div>
<br><br><br>
<div style="font-family:arial">HD lens orders placed at Direct-Lens.com receive a $5 rebate per order.</div>
<br><br><br>
<div style="font-family:arial"><strong>Payment:</strong> Credit card payments accepted. 1.5% late fees will be applied to all past due accounts.</div>
 <br><br><br>
<div style="font-family:arial"><strong>Company:</strong>Our company is registered under the name Directlab Network</div>   
    <br><br>
    
      <div class="header">Our Privacy Policy</div>
    
<br><br>
<!-- START PRIVACY POLICY CODE --><div style="font-family:arial"><strong>What information do we collect?</strong> <br /><br />We collect information from you when you register on our site, place an order, subscribe to our newsletter or fill out a form.  <br /><br />When ordering or registering on our site, as appropriate, you may be asked to enter your: name, e-mail address, mailing address or phone number.<br /><br /><strong>What do we use your information for?</strong> <br /><br />Any of the information we collect from you may be used in one of the following ways: <br /><br />- To improve customer service<br />(your information helps us to more effectively respond to your customer service requests and support needs)<br /><br />- To process transactions<br /><blockquote>Your information, whether public or private, will not be sold, exchanged, transferred, or given to any other company for any reason whatsoever, without your consent, other than for the express purpose of delivering the purchased product or service requested.</blockquote><br />- To send periodic emails<br /><blockquote>The email address you provide for order processing, will only be used  to send you information and updates pertaining to your order.</blockquote><br /><br /><strong>How do we protect your information?</strong> <br /><br />We implement a variety of security measures to maintain the safety of your personal information when you place an order or enter, submit, or access your personal information. <br /> <br />We offer the use of a secure server. All supplied sensitive/credit information is transmitted via Secure Socket Layer (SSL) technology and then encrypted into our Payment gateway providers database only to be accessible by those authorized with special access rights to such systems, and are required to?keep the information confidential.<br /><br />After a transaction, your private information (credit cards, social security numbers, financials, etc.) will not be stored on our servers.<br /><br /><strong>Do we use cookies?</strong> <br /><br />Yes (Cookies are small files that a site or its service provider transfers to your computers hard drive through your Web browser (if you allow) that enables the sites or service providers systems to recognize your browser and capture and remember certain information<br /><br /> We use cookies to understand and save your preferences for future visits.<br /><br /><strong>Do we disclose any information to outside parties?</strong> <br /><br />We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.<br /><br /><strong>Third party links</strong> <br /><br /> Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.<br /><br /><strong>Childrens Online Privacy Protection Act Compliance</strong> <br /><br />We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.<br /><br /><strong>Online Privacy Policy Only</strong> <br /><br />This online privacy policy applies only to information collected through our website and not to information collected offline.<br /><br /><strong>Your Consent</strong> <br /><br />By using our site, you consent to our <a style='text-decoration:none; color:#3C3C3C;' href='http://www.freeprivacypolicy.com/' target='_blank'>privacy policy</a>.<br /><br /><strong>Changes to our Privacy Policy</strong> <br /><br />If we decide to change our privacy policy, we will post those changes on this page, and/or update the Privacy Policy modification date below. <br /><br />This policy was last modified on 2014-03-13<br /><br /><strong>Contacting Us</strong> 

<br /><br />If there are any questions regarding this privacy policy you may contact us using the information below. <br /><br />www.direct-lens.com<br /><b>Fax.</b> 1(877) 590-3522<br><br><span></span><span></span>This policy is powered by Trust Guard, your <a style='color:#000; text-decoration:none;' href='http://www.trust-guard.com/PCI-Compliance-s/65.htm' target='_blank'>PCI compliance</a> authority.<span></span><span></span><span></span><!-- END PRIVACY POLICY CODE -->
</td>
  </tr>
</table>
		 </td>
      </tr>
      <tr>
        <td background="https://<?php echo constant('AWS_S3_BUCKET'); ?>.s3.amazonaws.com/ifcopticclub.ca/design_images/bot_piece_bg.jpg"><p>&nbsp;</p><br>
          </td>
      </tr>
    </table>
	</td>
  </tr>
</table>
</body>
</html>