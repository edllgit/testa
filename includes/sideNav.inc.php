<?php  
  $queryLab  	=	"SELECT main_lab, product_line, access_short_order_form FROM accounts WHERE user_id  = '" . $_SESSION["sessionUser_Id"] . "'";
  $resultLab 	=	mysql_query($queryLab)	or die ("Could not select items". $queryLab);
  $DataLab	 	=	mysql_fetch_array($resultLab);
  $LabNum	 	=	$DataLab[main_lab];	
  $Product_line	=	$DataLab[product_line];
  $AccessShortOrderForm  =	$DataLab[access_short_order_form];
?>

<div id="menu">
  	<ul>
       
        <li><a href="labAdmin/"><?php echo $lbl_btn_labadmlogin;?></a></li>
        <li><a href='myAccount.php'><?php echo $lbl_btn_myacct;?></a></li>
        <li><a href='basket.php'><?php echo $lbl_btn_viewbasket;?></a></li>
        
        <li><a href='order_history.php'><?php echo $lbl_btn_orderhist;?></a></li>
        <li><a href='credit_history.php'><?php if ($mylang == 'lang_french'){
        echo 'MES CREDITS';
        }else {
        echo 'MY CREDIT HISTORY';
        }?></a></li>
      
		<?php 	/*
		<li><a href='mySalespeople.php'><?php echo $lbl_btn_mysales;?></a></li>
        <li><a href='SalesAccount.php'><?php echo $lbl_btn_salesreports;?></a></li>
        */ ?>
         
        <?php /*if (($mylang == 'lang_french') && ($Product_line <> 'eye-recommend')) {  ?>
        <li><a href='my_points.php'>MES OPTI-POINTS</a></li>
        <?php  }elseif($Product_line <> 'eye-recommend') { ?>
        <li><a href='my_points.php'>MY PROGRAM</a></li>
        <?php } ?></li><?php */ ?>
        
		<?php /*
		<li><a href="tracengo.php">TRACE & GO ON THE WEB</a></li>  
		*/ ?>
        </ul>
  		<br>
		 <ul >
         <br>
		 
		 
        
			 <li><a href='stock_bulk.php'><?php echo $lbl_btn_lensesbulk;?></a></li>

			<?php  if ($Product_line <> 'eye-recommend'){?>
				
				<?php if  ($_SESSION["sessionUser_Id"]<>"jackdirect"){  ?>
				<li><a href='login.php'><?php echo $lbl_btn_lensesbulk;?></a></li>
				<?php  }  ?>
			
			
            <li><a href='stock.php'><?php echo $lbl_btn_lensesbytray;?></a></li>
           
		   <?php  }?> 
             
             
            <li><a href='lens_cat_selection.php'><?php echo $lbl_btn_lensesprescr;?></a></li>
            
           
            
            

            
			<?php if (($mylang == 'lang_french') && ($Product_line <> 'eye-recommend')) {  ?>
            <li><a href='http://ifcclub.ca'>ACHAT DE MONTURES<br />IFC</a></li>
            <?php  }elseif ($Product_line <> 'eye-recommend'){ ?>
            <li><a href='http://ifcclub.ca'>PURCHASE FRAMES<br />IFC</a></li>
            <?php } ?>  
			<?php if (($mylang == 'lang_french') && ($Product_line <> 'eye-recommend')) {  ?>
            <li><a href="http://lensnetclub.com" target="_blank">ACHAT DE VERRES<br />LENSNET CLUB</a></li>
            <?php  }elseif($Product_line <> 'eye-recommend'){ ?>
            <li><a href="http://lensnetclub.com" target="_blank">PURCHASE LENSES<br />LENSNET CLUB</a></li>    
            <?php } ?>  
            
          
			 <?php  /* if ($Product_line <> 'eye-recommend'){?>           
            <li><a href='price_lists.php'><?php echo $lbl_btn_pricelists;?></a></li>
            <?php  } */?> 
			 
			 
            
            <?php if ($mylang == 'lang_french') {  ?>
            <li><a href='http://direct-lens.com/privacy.php'>TERMS AND CONDITIONS</a></li>
            <?php  }else{ ?>
            <li><a href='http://direct-lens.com/privacy.php'>TERMS AND CONDITIONS</a></li>
            <?php } ?>  
          </ul><br>
		   <ul >
			   
          
            
			<?php /*  if ($Product_line <> 'eye-recommend'){?>   
            <li><a href='hd_info.php'><?php echo $lbl_btn_hdlensinfo;?></a></li>
            <?php  } */?> 
			   
          </ul><br>
		  <ul >
            <li ><a href='contact.php'><?php echo $lbl_btn_contactus;?></a></li>
            <li ><a href='http://direct-lens.com'><?php echo $lbl_btn_home;?></a></li>
            <li ><a href='logout.php'><?php echo $lbl_btn_logout;?></a></li>
          </ul>
		  </div>
          <br />
  <div>
     <?php  include("translator.php"); ?>
      </div>
<p>&nbsp;</p>