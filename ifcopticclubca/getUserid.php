<?php
include("../Connections/sec_connect.inc.php");
include "../includes/getlang.php";
$user_id   = $_REQUEST['user_id'];
$query     = "SELECT * FROM accounts where user_id ='$user_id'";
$rptResult = mysql_query($query)		or die  ('I cannot select items because: ' . mysql_error());
$nbrResult = mysql_num_rows($rptResult);
if($nbrResult>0){
	  if ($mylang == 'lang_french') { 
           	echo "<br><span style=\"color:red;\">Le nom d'utilisateur ". $user_id . " n'est pas disponible". "</span>";
      }else{ 
            echo "<br><span style=\"color:red;\">Username "            . $user_id . "  is not available". "</span>";
      } 
	  
}else{
	
	 if ($mylang == 'lang_french') { 
           	echo "<br><span style=\"color:green;\">Le nom d'utilisateur ". $user_id . " est disponible". "</span>";
      }else{ 
            echo "<br><span style=\"color:green;\">Username "            . $user_id . "  is available".  "</span>";
      } 
}
?>

