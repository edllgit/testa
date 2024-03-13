<?php 
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//Démarrer la session
session_start();
//Inclusions
include "../sec_connectEDLL.inc.php";
include "../includes/getlang.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>LensNet Club</title>
<link rel="shortcut icon" href="favicon.ico"/>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.featureList-1.0.0.js"></script>
<!--[if !IE]>-->
<script type="text/javascript" src="js/jquery.dropshadow.js"></script>
<!--<![endif]-->

<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}

</script>
<link href="ln.css" rel="stylesheet" type="text/css" />
<link href="ln_pt.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="css/flstyle.css" />

	<script language="javascript">
		$(document).ready(function() {
				$.featureList(
				$("#tabs li a"),
				$("#output li"), {
					start_item	:0
					}
				);
		});
        </script>			   
<!--[if !IE]>-->
	<script language="javascript">
		$(document).ready(function() {
        $("#container").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		 $("#leftFeatureColumn").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		 $("#middleFeatureColumn").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		 $("#rightFeatureColumn").dropShadow({left:6, top:6, blur:5, opacity:0.7});
		});
        </script>	
<!--<![endif]-->
	
<link href="css/featuresbox.css" rel="stylesheet" type="text/css" />

</head>

<body>
<div id="container">
  <div id="masthead">
  <img src="http://www.direct-lens.com/lensnet/images/ln-masthead.jpg" width="1050" height="165" alt="LensNet Club"/>
</div>
<div id="maincontent">
<div id="leftColumn">
<div id="leftnav">
  <?php include("includes/sideNavHome.inc.php"); ?>
</div><!--END leftnav-->
</div><!--END leftcolumn-->
<div id="rightColumn">
      <?php 
		if(!isset($_COOKIE["mylang"])&&!isset($mylang)){
			include "../translatorlist.php";
			} else {
		?>  
      <div class="bigwelcome"><?php echo $lbl_welcome_lensnet;?>
		  <?php if ($mylang=='lang_english'){  ?>
          <p style="font-size:13px;">Please be advised of our new customer service toll free number:</p><p style="font-size:19px;">1-855-770-2124</p>
          <?php }else{  ?>
          <p>&nbsp;</p>
          <?php }  ?>
      </div>
      <?php } ?>
      
      	  <div >
  	      <?php if ($mylang=='lang_english'){  ?>
          <p style="font-size:21px;" align="center"><a style="text-decoration:none; color:#878787" href="requestAccount.php">Open an account</a></p>
          <?php }else{  ?>
          <p style="font-size:21px;" align="center"><a style="text-decoration:none; color:#878787" href="requestAccount.php">Ouvrir un compte</a></p>
          <?php }  ?>
          </div>
          
          
      
		<div id="feature_list">
			<ul id="tabs">
               
                 <li>
					<a href="javascript:;">
					<h3><?php echo $lbl_feature2_head;?></h3>
					<div class="featuresTabSubhead"><?php echo $lbl_feature2_subhead;?></div>
					</a>
		    	</li>
                
                <li>
					<a href="requestAccount.php">
					<h3>
						<?php if ($mylang=='lang_english'){  ?>
                        Open an account
                        <?php }else{  ?>
                        Ouvrir un compte
                        <?php }  ?>
                    </h3>
					<div class="featuresTabSubhead"><?php echo $lbl_feature3_subhead;?></div>
					</a>
		    	</li>
               

      <?php /*?>       <li>				
				  <?php if ($mylang=='lang_english'){  ?>
                      <a href="javascript:;">	
                          <h3>&nbsp;</h3>
                          <div class="featuresTabSubhead">&nbsp;</div>
                      </a>
                  <?php }else{  ?>
                      <a href="javascript:;">	                      
                          <h3>&nbsp;</h3>
                          <div class="featuresTabSubhead">&nbsp;</div>
                      </a>                        
                  <?php }  ?>                    	
		    	</li>
                          
			 
                
                <li>
					<a href="javascript:;">
					<h3><?php echo $lbl_feature1_head;?></h3>
					<div class="featuresTabSubhead"><?php echo $lbl_feature1_subhead;?></div>
					</a>
		    	</li> <?php */?>
                                         
			</ul>
            
            
            
			<ul id="output">
            
            
            	<li>
					<img src="http://www.direct-lens.com/lensnet/images/features2.jpg" />
                      <div id="features2">
                      <div class="features2head"><?php echo $lbl_feature2_head;?></div>
                      <div class="features2subhead"><?php echo $lbl_feature2_subhead;?></div>
                      <?php echo $lbl_feature2_body;?></div>
	  		  </li>
              
              <li>
					<img src="http://www.direct-lens.com/lensnet/images/features1.jpg" />
                      <div id="features3">
                      <h3>&nbsp;
						
                    </h3>
                      </div>
	  		  </li>
              
              
				<?php /*?><li>
					  <?php if ($mylang=='lang_english'){  ?>
                      <img width="590" src="http://www.direct-lens.com/lensnet/images/features4n.jpg" />
                      <?php }else{  ?>
                      <img width="590" src="http://www.direct-lens.com/lensnet/images/features4frn.jpg" />
                      <?php }  ?>                    

                      <div id="features4">&nbsp;</div>
	  		  </li>            
				
			
              
              <li>
					<img src="http://www.direct-lens.com/lensnet/images/features1.jpg" />
                      <div id="features1">
                      <div class="features1head"><?php echo $lbl_feature1_head;?></div>
                      <div class="features1subhead"><?php echo $lbl_feature1_subhead;?></div>
                      <?php echo $lbl_feature1_body;?></div>
	  		  </li>
              
				<?php */?>

			</ul>

	  </div><!--FEATURES LIST -->
     <div class="bodycopyHome" id="words"><?php echo $lbl_welcome_txt_lensnet_home;?>
      &nbsp;
     </div>
</div><!--END rightcolumn-->
</div><!--END maincontent-->
<div id="footer1">
  
</div>
</div><!--END containter-->
<link rel="shortcut icon" href="favicon.ico"/>
</body>
</html>