<link rel="shortcut icon" href="../favicon.ico" type="image/x-icon" />
 <div id="menu">
  <ul>
        <li><a href='login.php'><?php echo $lbl_btn_custlogin;?></a></li>
        <li><a href='requestAccount.php'><?php echo $lbl_btn_openacct;?></a></li>
        <li><a href='conditions.php'><?php if ($mylang == 'lang_french') {  ?>
        <?php echo 'CONDITIONS D\'UTILISATION';?></a>
        <?php  }else{ ?>
        <?php echo 'TERMS AND CONDITIONS';?></a>
        <?php } ?></a>
        </li>
        <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>

  </ul>
	<br>
        
		  <ul >
          
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  <ul >
  <br />
  <?php  include("../translator.php"); ?>
  </div>
  <link rel="shortcut icon" href="../favicon.ico"/>