<?php 
require_once(__DIR__.'/../constants/aws.constant.php');
include "../Connections/directlens.php";
include "../includes/getlang.php";
include "config.inc.php";

session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print $sitename;?></title>
   
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
      		<div class="loginText"><?php echo $lbl_user_txt;?> <?php print $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;
        	<a href='logout.php'><?php echo $lbl_btn_logout;?></a></div>
        	<?=$_SESSION["sessionUser_Id"];?></div>
      <div class="header">Notre politique de confidentialité</div>
      <div class="messageText" style="line-height:15px">
      <strong>Quelles sont les informations que nous recueillons ?</strong> <br /> 
      <br />
      Nous recueillons des informations lorsque vous vous inscrivez sur notre site Web, passer une commande, vous vous abonnez à notre infolettre ou remplissez un formulaire.<br />
      <br />
		Lors d'une commande ou de votre inscription sur ​​notre site Web, vous pouvez être invité à inscrire : nom, adresse courriel, adresse postale ou numéro de téléphone</span><br />
	    <br />
	    <strong>Comment utilisions nous vos informations ?</strong> <br />
	    <br />
	    Any of the information we collect from you may be used in one of the following ways: <br />
	    <br />
	    - To improve customer service<br />
	    (your information helps us to more effectively respond to your customer service requests and support needs)<br />
	    <br />
	    - To process transactions<br />
	    <blockquote>Your information, whether public or private, will not be sold, exchanged, transferred, or given to any other company for any reason whatsoever, without your consent, other than for the express purpose of delivering the purchased product or service requested.</blockquote>
	    - To send periodic emails<br />
	    <blockquote>The email address you provide for order processing, will only be used  to send you information and updates pertaining to your order.</blockquote>
	    <strong>How do we protect your information?</strong> <br />
	    <br />
	    We implement a variety of security measures to maintain the safety of your personal information when you place an order or enter, submit, or access your personal information. <br />
        <br />
        We offer the use of a secure server. All supplied sensitive/credit information is transmitted via Secure Socket Layer (SSL) technology and then encrypted into our Payment gateway providers database only to be accessible by those authorized with special access rights to such systems, and are required to?keep the information confidential.<br />
        <br />
        After a transaction, your private information (credit cards, social security numbers, financials, etc.) will not be stored on our servers.<br />
        <br />
        <strong>Do we use cookies?</strong> <br />
        <br />
        Yes (Cookies are small files that a site or its service provider transfers to your computers hard drive through your Web browser (if you allow) that enables the sites or service providers systems to recognize your browser and capture and remember certain information<br />
        <br />
We use cookies to understand and save your preferences for future visits.<br />
<br />
<strong>Do we disclose any information to outside parties?</strong> <br />
<br />
We do not sell, trade, or otherwise transfer to outside parties your personally identifiable information. This does not include trusted third parties who assist us in operating our website, conducting our business, or servicing you, so long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety. However, non-personally identifiable visitor information may be provided to other parties for marketing, advertising, or other uses.<br />
<br />
<strong>Third party links</strong> <br />
<br />
Occasionally, at our discretion, we may include or offer third party products or services on our website. These third party sites have separate and independent privacy policies. We therefore have no responsibility or liability for the content and activities of these linked sites. Nonetheless, we seek to protect the integrity of our site and welcome any feedback about these sites.<br />
<br />
<strong>Childrens Online Privacy Protection Act Compliance</strong> <br />
<br />
We are in compliance with the requirements of COPPA (Childrens Online Privacy Protection Act), we do not collect any information from anyone under 13 years of age. Our website, products and services are all directed to people who are at least 13 years old or older.<br />
<br />
<strong>Online Privacy Policy Only</strong> <br />
<br />
This online privacy policy applies only to information collected through our website and not to information collected offline.<br />
<br />
<strong>Your Consent</strong> <br />
<br />
By using our site, you consent to our <a style='text-decoration:none; color:#3C3C3C;' href='http://www.freeprivacypolicy.com/' target='_blank'>privacy policy</a>.<br />
<br />
<strong>Changes to our Privacy Policy</strong> <br />
<br />
If we decide to change our privacy policy, we will post those changes on this page, and/or update the Privacy Policy modification date below. <br />
<br />
This policy was last modified on 2011-09-27<br />
<br />
<strong>Contacting Us</strong> <br />
<br />
If there are any questions regarding this privacy policy you may contact us using the information below. <br />
<br />
www.mybbgclub.com<br />
1816 Production Court <br />
Louisville KY 40299<br />
USA<br />
<br />
<b>Tel.</b> (502)671-0735&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Toll Free.</b> 1 (877) 570-3522&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Fax.</b> 1(877) 590-3522<br />
<br />
This policy is powered by Trust Guard, your <a style='color:#000; text-decoration:none;' href='http://www.trust-guard.com/PCI-Compliance-s/65.htm' target='_blank'>PCI compliance</a> authority.<br/><br/>
</div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->



</body>
</html>
<?php
mysql_free_result($languages);

mysql_free_result($languagetext);
?>