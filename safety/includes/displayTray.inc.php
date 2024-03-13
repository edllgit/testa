<?php

if ($_POST[deleteTrayItem]=="true"){
		$DELETE_ITEM=$_POST[tray_ref];
		
		$_SESSION["RE"][$DELETE_ITEM]="";
		$_SESSION["LE"][$DELETE_ITEM]="";
		$_SESSION["TRAY_REF"][$DELETE_ITEM]="";
		
		$_SESSION["ITEM_NUMBER"]=$_SESSION["ITEM_NUMBER"]-1;
	}
?>
			  
<table width="770" border="0" align="center" cellpadding="3" cellspacing="0"  class="formBox">
<tr ><td colspan="8" bgcolor="#17A2D2" class="tableHead"><?php echo $lbl_currenttrays_txt;?>&nbsp; </td>
  </tr></table>
<?php

if ($_SESSION["ITEM_NUMBER"]== 0){ /* no trays to list */
	print "<table width=\"770\" border=\"0\" align=\"center\" cellpadding=\"3\" cellspacing=\"0\"  class=\"formBox\"><tr><td colspan=\"8\" class=\"formCell\">There are currently no tray items.</td></tr></table>";
	}else{
	
		for ($i=1;$i<$_SESSION["COUNT"]+1;$i++){
		
			if ($_SESSION["TRAY_REF"][$i]!=""){
				include("displayTrayItems.inc.php");}
			}//END OF FOR LOOP
			
}//end of 0 usercount
?>

<form action="basket.php" method="post" name="stock2" id="stock2">  <div align="center" style="margin:11px">&nbsp;
		      <input name="AddToBasket" type="submit" class="formText" value="<?php echo $btn_addtraybasket_txt;?>" tabindex="1">
		      <input name="continue_redirect" type="hidden" value="stock.php"/><input name="from_tray_form" type="hidden" id="from_tray_form" value="true">
		    </div></form>
			
			
			