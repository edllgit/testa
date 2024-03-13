<?php  
  require_once(__DIR__.'/../../constants/url.constant.php');
  $queryLab  	=	"SELECT main_lab, product_line, access_short_order_form FROM accounts WHERE user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab 	=	mysqli_query($con,$queryLab)	or die ("Could not select items". $queryLab);
  $DataLab	 	=	mysqli_fetch_array($resultLab,MYSQLI_ASSOC);
  $LabNum	 	=	$DataLab[main_lab];	
  $Product_line	=	$DataLab[product_line];
  $AccessShortOrderForm  =	$DataLab[access_short_order_form];
?>

<div id="menu">
  	<ul>
       
        <li><a href="<?php echo constant('DIRECT_LENS_URL'); ?>/labAdmin/"><?php echo $lbl_btn_labadmlogin;?></a></li>
        <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
        <li><a href='basket.php'><?php echo $lbl_btn_viewbasket;?></a></li>
        
        <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
        <li><a href='credit_history.php'><?php if ($mylang == 'lang_french'){
        echo 'MES CREDITS';
        }else {
        echo 'MY CREDIT HISTORY';
        }?></a></li>

        </ul>
  		<br>
		 <ul >
         <br>
		 
		 
        
			

			<?php  if ($Product_line <> 'eye-recommend'){?>
				
				<?php if  ($_SESSION["sessionUser_Id"]<>"jackdirect"){  ?>
				<li><a href='stock_bulk.php'><?php echo $lbl_btn_lensesbulk;?></a></li>
				<?php  }  ?>
			
			
            
           
		   <?php  }?> 
             
             
            <li><a href='lens_cat_selection.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
            

            <?php if ($mylang == 'lang_french') {  ?>
            <li><a href='<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/privacy.php'>TERMS AND CONDITIONS</a></li>
            <?php  }else{ ?>
            <li><a href='<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens/privacy.php'>TERMS AND CONDITIONS</a></li>
            <?php } ?>  
          </ul><br>
		   <ul >
			   
  
          </ul><br>
		  <ul >
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='<?php echo constant('DIRECT_LENS_URL'); ?>/direct-lens'><?php echo $lbl_btn_home;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  </div>
          <br />
  <div>
     <?php  include("translator.php"); ?>
      </div>
<p>&nbsp;</p>