<?php 
include "../Connections/directlens.php";
include "../includes/getlang.php";
include"config.inc.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php print $sitename;?></title>
    
<link href="ifc.css" rel="stylesheet" type="text/css" />
<link href="ifc_pt.css" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="favicon.ico"/>

<script type="text/javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location = "index.php";
}
</script>
</head>

<body>
<div id="container">    
 	<div id="maincontent">
    	<div id="nav-int"><?php include("includes/new-menu.php"); ?></div>    
            <div class="loginText">
                <?php echo $lbl_user_txt;?><?php echo $_SESSION["sessionUser_Id"];?>&nbsp;&nbsp;|&nbsp;&nbsp;<a href='logout.php'><?php echo $lbl_btn_logout;?></a>
            </div>
            <div class="header">				
				<?php 	if ($mylang == 'lang_french') {  ?>
                    Accessoires
                <?php  	}else{ ?>
                   	Accessories
                <?php 	} ?> 
            </div>            
            <div class="Subheader" style="height:600px;">        
                <?php 	if ($mylang == 'lang_french') {  ?>
                <p>À venir...</p>
                <?php  	}else{ ?>
                <p>To Come</p>
                <?php 	} ?>                
            </div>
	</div><!--END maincontent-->
   <?php include("footer.inc.php"); ?>
</div><!--END containter-->
</body>
</html>
