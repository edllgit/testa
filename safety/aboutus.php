<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?>
<?php 
session_start();?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php echo $sitename;?></title>
   
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>
    
</head>

<body>
<div id="container">    
    <div id="masthead">
        <?php 	if ($mylang == 'lang_french') {  ?>
              <img src="http://www.direct-lens.com/safety/design_images/ifc-masthead.jpg" width="1050" />                   
        <?php  	}else{ ?>
              <img src="http://www.direct-lens.com/safety/design_images/ifc-masthead-en.jpg" width="1050" />
        <?php 	} ?>   
	</div>
    <div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">About Us</div>
            <div class="Subheader" style="height:400px;">
                <p>SAFE (Safety Advantage for Everyone) offers stylish frames at a great price!</p>
            
                <p>If you are looking at this from the perspective of your company, purchases of safety eyeglasses should cost you less while 
                obtaining more for your employees!</p>
            
                <p>We offer a wide range of products at competitive prices. All of our products are certified and CSA Z87.1-2010 AND z94.3-07: 
                Standards that guarantee the security aspect of our glasses. Quality lenses are provided to ensure visual satisfaction. You can 
                also take advantage of our exclusive Revolution polycarbonate lenses for minimized distortion. Add an anti-reflection coating to improve 
                vision and maximize your viewing experience.</p>
            
                <p>We invite companies to contact the nearest representative in your region. The representative will make an appointment to 
                show you the different frames and accessories that suit your workplace to save you time and money. </p>
            
                <p>Comfort, style and safety: That's the promise of SAFE (Safety Advantage for Everyone)!</p>               
            </div><!--END Subheader-->             
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->


</body>
</html>

<?php
mysql_free_result($languages);
mysql_free_result($languagetext);
?>