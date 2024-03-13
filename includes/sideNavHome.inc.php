<div id="menu">

    <ul>
        <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
        <li><a href="labAdmin/"><?php echo $lbl_btn_labadmlogin;?></a></li>
        <li><a href='requestAccount.php'><?php echo $lbl_btn_openacct;?></a></li>
    </ul>
   	<br/>
    <ul>

	<?php if  ($_SESSION["sessionUser_Id"]<>"jackdirect"){  ?>
         <li><a href='login.php'><?php echo $lbl_btn_lensesbytray;?></a></li>
		 <li><a href='login.php'><?php echo $lbl_btn_lensesbulk;?></a></li>
     <?php  }  ?>

	  
        <li><a href='login.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
        <li><a href='login.php'><?php echo $lbl_btn_frames;?></a></li>
    </ul><br/>
    <ul>
        <li><a href='http://static.direct-lens.com/promotions/promo.pdf' target="_blank"><?php echo $lbl_btn_promotions;?></a></li>
    </ul><br/>
    <ul>
    <li><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
    <li><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
    </ul>
    <ul>
    	<li><a href='Direct-Lens.url'><span class="style1"><?php echo $lbl_btn_dwnld_shortcut;?></span></a></li>
    </ul><br />
    <?php  include("translator.php"); ?>
</div>
