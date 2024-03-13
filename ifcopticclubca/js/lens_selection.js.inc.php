<script language="JavaScript" type="text/javascript">																										  
function validateChoice(theForm){
	if((theForm.model.value!="none") && (theForm.collection.value!="none")){
		<?php if ($mylang == 'lang_french'){ ?>
		alert("Veuillez sélectionner un modèle OU une collection, pas les deux.")
		<?php }else{ ?>
		alert("Please select either a model OR a collection, not Both.")
		<?php } ?>
		return (false);
	}	
}	
</script>
