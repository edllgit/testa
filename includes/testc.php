<?php 
session_start();



	if (($_SESSION['PrescrData']['JOB_TYPE']=="Edge and Mount")||($_SESSION['PrescrData']['JOB_TYPE']=="Taill�-mont�")){
			echo 'je lance la fonction addEdgingItem';
			//addEdgingItem($order_id);
		}
		echo $_SESSION['PrescrData']['JOB_TYPE'];
		
		
		if ($_SESSION['PrescrData']['JOB_TYPE']=="Taill�-mont�"){
		'<br>suppos� marcher';
		}
		//var_dump($_SESSION['PrescrData']['JOB_TYPE']);


?>

