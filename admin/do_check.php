<?php
include("../Connections/sec_connect.inc.php");

$model_num=$_REQUEST['model_num'];
$data=mysql_query("SELECT * FROM ifc_frames_french where model ='$model_num'");
if(mysql_num_rows($data)>0)
{
print "<span style=\"color:red;\">Ce modèle existe déja !</span>";
}
else
{
print "<span style=\"color:green;\">Ce modèle n'existe pas déja</span>";
}
?>
