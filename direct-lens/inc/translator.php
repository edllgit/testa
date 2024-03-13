<select name="mylang" onChange="ChangeLang(this.value)">
    <option value="lang_english" <?php if (!(strcmp("lang_english", $mylang))) {echo "selected=\"selected\"";} ?>>
	English</option>
    <option value="lang_french" <?php if (!(strcmp("lang_french", $mylang))) {echo "selected=\"selected\"";} ?>>
	Fran&ccedil;ais</option>    
</select>
