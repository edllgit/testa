//Fonction Zebra
function zebraRows(selector, className)
{
  $(selector).removeClass(className).addClass(className);
}

//filter results based on query
function filter(selector, query) {
  query =   $.trim(query); //trim white space
  query = query.replace(/ /gi, '|'); //add OR for regex query
 
  $(selector).each(function() {
    ($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).hide().removeClass('visible') : $(this).show().addClass('visible');
  });
}

//alert('passe 11');

 //default each row to visible
  $('tbody tr').addClass('visible');
   //alert('passe 22');


	//alert('passe 33');
	
	
	/*$('#filter').keyup(function(event) {
    //if esc is pressed or nothing is entered
    //if (event.keyCode == 27  
      //if esc is pressed we want to clear the value of search box
     alert('espace pressed');
    }*/
	
	$('#filter').show();

$(document).ready(function(){
    $("#filter").keyup(function(event){
	  if (event.keyCode == 27 || $(this).val() == ''){//Si la touche escape a été appuyé
	  	//alert('Escape pressed');
	  	$(this).val('');//Effacer le contenu du filtre
	  	$('tbody tr').removeClass('visible').show().addClass('visible');//REndre tous les éléments visible puisqu'aucun filtre n'est appliqué
	  }
	  
	   if ($(this).val() != ''){//Si le filtre n'est pas vide
	   	filter('tbody tr', $(this).val());
		//reapply zebra rows
    	$('.visible td').removeClass('odd');
    	zebraRows('.visible:odd td', 'odd');
	  }
	 
	  
    });
});

//Partie Mouse Over
$(document).ready(function() {// Se déclenchera automatiquement au chargement de la page
	
zebraRows('tbody tr:odd td', 'odd');
 	
	
$('tbody tr').hover(function(){
  $(this).find('td').addClass('hovered');
}, function(){
  $(this).find('td').removeClass('hovered');
});
});