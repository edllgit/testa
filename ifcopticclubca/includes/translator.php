<script type="text/javascript">
function ChangeLang(mylang){
		var date = new Date();
		date.setTime(date.getTime()+(30*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
		document.cookie = "mylang="+mylang+expires+"; path=/";
		window.location.reload();
		return false;
}
</script>
<div style="padding:0 10px;">

<select name="mylang" onChange="ChangeLang(this.value)">
    <option value="lang_english" <?php if (!(strcmp("lang_english", $mylang))) {echo "selected=\"selected\"";} ?>>
	English</option>
    <option value="lang_french" <?php if (!(strcmp("lang_french", $mylang))) {echo "selected=\"selected\"";} ?>>
	Fran&ccedil;ais</option>    
</select>

</div>