<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
<div id="menu">
  <ul >
     <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
     	<?php if ($mylang == 'lang_french') {  ?>
		<li><a href='conditions.php'><?php echo 'CONDITIONS D\'UTILISATION';?></a></li>
		<?php  }else{ ?>
		<li><a href='conditions.php'><?php echo 'TERMS AND CONDITIONS';?></a></li>
		<?php } ?>
        <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
        <li><a href='basket.php'><?php echo $lbl_btn_viewbasket;?></a></li>
        <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
        
        <?php if ($mylang == 'lang_french') {  ?>
        <li><a href='credit_history.php'>MES CREDITS</a></li>
        <?php  }else{ ?>
        <li><a href='credit_history.php'>MY CREDIT HISTORY</a></li>
        <?php } ?>  
        
          
  </ul>
  <br>
		 <ul>
            <li><a href='login.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
			<?php if ($mylang == 'lang_french') {  ?>
            <li><a href='http://direct-lens.com'>ACHAT SUR DIRECT-LENS</a></li>
            <?php  }else{ ?>
            <li><a href='http://direct-lens.com'>PURCHASE TO DIRECT-LENS</a></li>
            <?php } ?>                            
            
    
            
        
            

          
            <li><a href='index.php'><?php echo $lbl_btn_home;?></a></li>
            <li><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  </div>
          <br />
  <div>
  <?php  include("../translator.php"); ?>
      </div>
<p>&nbsp;</p>
<link rel="shortcut icon" href="../favicon.ico"/>