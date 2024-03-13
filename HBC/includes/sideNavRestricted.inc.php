
<div id="menu">
	<br /><br />
  	<ul>
	<?php  if($_SESSION["sessionUser_Id"]==""){ ?>
        <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
        <li><a href='requestAccount.php'><?php echo $lbl_btn_registration;?></a><br /><br /></li>
    <?php }?>
        <li><a href='index.php'><?php echo $lbl_btn_home;?></a></li>
        <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
        <li><a href='basket.php'><?php echo $lbl_btn_viewbasket;?></a></li>
        <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
        <li><a href='lens_cat_selection.php'>PACK MONTAGE<?php //echo $lbl_btn_lensesprescr;?></a></li>
        <li><a href='prescription.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
        <li><a href='sv_form.php'>Unifocaux</a></li>
        <li><a href='price_lists.php'><?php echo 'PRIX & PROMOS';?></a></li>
        <li><a href='cleari.php'><?php echo $lbl_btn_prod_serv;?></a></li>
        <li><a href='/labadmin' target="_blank"><?php echo 'IFC ADMIN';?></a></li>
        <li><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
        <li><a href="conditions.php">CONDITIONS</a></li>	        
        <li><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
	</ul>
</div>
<br />

<p>&nbsp;</p>
