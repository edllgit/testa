<?php
//AFFICHER LES ERREURS
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
?>

<script language="javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location.reload();
		//window.location = "http://c.direct-lens.com/"
		return false;
}
</script>
<div style="padding:0 10px;">
<?php
//echo $mylang."--".$row_languages['mysql_table']."--".$_COOKIE["mylang"];
//echo $row_languages['mysql_table']."++".$mylang."++".strcmp($row_languages['mysql_table'], $mylang);

?>
<select name="mylang" onChange="ChangeLang(this.value)">
<?php do {  ?>
	
    <option value="<?php echo $row_languages['mysql_table']?>"<?php if (!(strcmp($row_languages['mysql_table'], $mylang))) {echo "selected=\"selected\"";} ?>><?php echo $row_languages['languagename']?></option>

<?php echo strcmp($row_languages['mysql_table'], $mylang);
 } while ($row_languages = mysqli_fetch_array($languages,MYSQLI_ASSOC));
  $rows = mysqli_num_rows($languages);
  if($rows > 0) {
      mysqli_data_seek($languages, 0);
	  $row_languages = mysqli_fetch_array($languages,MYSQLI_ASSOC);
  }?>
</select>

</div>