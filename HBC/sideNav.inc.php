<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<div id="menu">
  <ul >
  <li>&nbsp;</li>
 <?php  if($_SESSION["sessionUser_Id"]==""){ ?>
     <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
    <li><a href='requestAccount.php'><?php echo $lbl_btn_registration;?></a></li>
<?php }?>
     <?php if ($mylang == 'lang_france') { 
				 echo '<li><a href="conditions.php">CONDITIONS</a></li> ';
				 }else if ($mylang == 'lang_english'){
				 echo '<li><a href="conditions.php">CONDITIONS</a> </li>';
				 } else {
				 echo '<li><a href="conditions.php">CONDITIONS</a></li> ';
				 } ?>
	
    <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
    <li><a href='basket.php'><?php echo $lbl_btn_viewbasket;?></a></li>
    <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
  </ul>
  <br>
		 <ul >
        <li><a href='lens_cat_selection.php'>PACK MONTURE<?php //echo $lbl_btn_lensesprescr;?></a></li>
        <li><a href='prescription.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
             <?php if ($mylang == 'lang_france') {  ?>
		<li><a href='price_lists.php'><?php echo 'PRIX & PROMOS';?></a></li>
		<?php  }else{ ?>
		<li><a href='price_lists.php'><?php echo 'PRICES & PROMOS';?></a></li>
		<?php } ?>
             <li><a href='news.php'><?php if ($mylang == 'lang_france') {  ?>
		<?php echo 'NOUVELLES';?></a>
		<?php  }else{ ?>
		<?php echo 'NEWS';?></a>
		<?php } ?></a>
   		 </li>  
         
       
        
             

          </ul><br>
		  <ul >
           <li ><a href='/labadmin' target="_blank"><?php echo 'IFC ADMIN';?></a></li>
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='index.php'><?php echo $lbl_btn_home;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  </div>
          <br />
  <div>
  <?php  include("../translator.php"); ?>
      </div>
<p>&nbsp;</p>
<link rel="shortcut icon" href="../favicon.ico"/>