<?php 
$image1 = "../images/".$img_livehelp; 
$image2 = "../images/".$img_watchvid; 
?>
<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<div align="center" style="padding-top:7px; padding-bottom:7px">
  <table cellpadding="0" cellspacing="0" border="0" class="chat-box">
    <tr><td align="center"><img src='<?php echo $image1;?>' border="0" onClick="window.open('http://www.websitealive8.com/2326/rRouter.asp?groupid=2326&websiteid=0&departmentid=0&dl='+escape(document.location.href),'','width=400,height=400');" style="cursor:pointer;">
</td></tr><tr></table></div>
  <div id="menu">
  <ul >
    <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
    <li><a href='requestAccount.php'><?php echo $lbl_btn_openacct;?></a></li>
   <li><a href='conditions.php'><?php if ($mylang == 'lang_france') {  ?>
		<?php echo 'CONDITIONS D\'UTILISATION';?></a>
		<?php  }else{ ?>
		<?php echo 'RULES AND CONDITIONS';?></a>
		<?php } ?></a>
    </li>
  <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
    <li><a href='mySalespeople.php'><?php echo $lbl_btn_mysales;?></a></li>
    <li><a href='SalesAccount.php'><?php echo $lbl_btn_salesreports;?></a></li>
  </ul>
		  <br>
		 <ul >
            <li><a href='login.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
             <?php if ($mylang == 'lang_france') {  ?>
		<li><a href='price_lists.php'><?php echo 'PRIX & PROMOS';?></a></li>
		<?php  }else{ ?>
		<li><a href='price_lists.php'><?php echo 'PRICES & PROMOS';?></a></li>
		<?php } ?>
        

         
         
         
          <!--  <li><a href='login.php'><?php //echo $lbl_btn_frames;?></a></li>-->
           <!-- <li><a href='price_lists.php'>PRICE LISTS</a></li>-->
          </ul>   <li><a href='fax.php'><?php if ($mylang == 'lang_france') {  ?>
		<?php echo 'COMMANDER PAR FAX';?></a>
		<?php  }else{ ?>
		<?php echo 'HOW TO ORDER BY FAX';?></a>
		<?php } ?></a>
   		 </li>
		  <ul >
            <li ><a href='/labadmin'><?php echo 'LAB ADMIN';?></a></li>
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  <ul >
  <br />
  <?php  include("../translator.php"); ?>
  </div>
  <link rel="shortcut icon" href="../favicon.ico"/>
	