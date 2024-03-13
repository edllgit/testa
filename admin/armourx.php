<?php
include("../Connections/sec_connect.inc.php");

if ($_REQUEST[todo]=='activer'){
$queryUpdate   = "UPDATE ifc_frames_french SET display_on_ifcca = 'yes' WHERE misc_unknown_purpose = 'ARMOURX' "; 
}elseif ($_REQUEST[todo]=='desactiver'){
$queryUpdate   = "UPDATE ifc_frames_french SET display_on_ifcca = 'no' WHERE misc_unknown_purpose = 'ARMOURX' "; 
}


$resultUpdate  = mysql_query($queryUpdate) or die ( "Query failed: " . mysql_error());
//Redirection vers la page de gestion des montures	
header("Location:newFrame.php");	
exit();
?>
