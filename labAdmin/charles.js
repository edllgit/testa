// JavaScript Document
<script type="text/javascript">
$(document).ready(function() {
  zebraRows('tbody tr:odd td', 'odd');
});
//used to apply alternating row styles
function zebraRows(selector, className)
{
  $(selector).removeClass(className).addClass(className);
}
//Partie Mouse Over
$(document).ready(function() {
$('tbody tr').hover(function(){
  $(this).find('td').addClass('hovered');
}, function(){
  $(this).find('td').removeClass('hovered');
});
});
//Recherche
//filter results based on query
function filter(selector, query) {
  query =   $.trim(query); //trim white space
  query = query.replace(/ /gi, '|'); //add OR for regex query
 
  $(selector).each(function() {
    ($(this).text().search(new RegExp(query, "i")) < 0) ? $(this).hide().removeClass('visible') : $(this).show().addClass('visible');
  });
}
</script>