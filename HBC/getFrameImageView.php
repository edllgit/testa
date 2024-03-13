<?php

session_start();
require('Connections/sec_connect.inc.php');

	$query="select * from frames where frames_id='$_GET[FRAMES_ID]'";
	$result=mysql_query($query)
		or die ("Could not find collection");
	
	echo "<table width=\"100%\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">";

while ($frameItem=mysql_fetch_array($result)){
	echo "<tr><td>";

	echo "<div align=\"center\" class=\"frameImage\"><img src=\"frames_images/".$frameItem[frame_image].".jpg\"/></div>";
	echo "<div class=\"frameImageModelText\">MODEL: $frameItem[model_num]</div>";
	echo "</td></tr>";
}

	echo "</table>";

?>
