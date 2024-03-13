          <?php    
	session_start(); 
	
	unlink("../holdingfiles/".$_SESSION['PrescrData']['myupload']);

	$_SESSION['PrescrData']['myupload'] = "none";
			
	header("Location:prescription_retry.php");
			?>    