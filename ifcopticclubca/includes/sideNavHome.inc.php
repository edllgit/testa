<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
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
		 <ul>
            <li><a href='login.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
             <?php if ($mylang == 'lang_france') {  ?>
		<li><a href='price_lists.php'><?php echo 'PRIX & PROMOS';?></a></li>
		<?php  }else{ ?>
		<li><a href='price_lists.php'><?php echo 'PRICES & PROMOS';?></a></li>
		<?php } ?>
         </ul>   

		<li><a href='fax.php'><?php if ($mylang == 'lang_france') {  ?>
		<?php echo 'COMMANDER PAR FAX';?></a>
		<?php  }else{ ?>
		<?php echo 'HOW TO ORDER BY FAX';?></a>
		<?php } ?></a>
   		 </li>


		  <ul>
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  <ul >
  <br />
  <?php  include("../translator.php"); ?>
  </div>
  <link rel="shortcut icon" href="../favicon.ico"/>
		