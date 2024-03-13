var popupStatus = 0;
var popupStatusLoggedIn = 0;

function loadPopup(){
	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.6"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#popupForm").fadeIn("slow");
		popupStatus = 1;
		
	}
}

function disablePopup(){

	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#popupForm").fadeOut("slow");
		popupStatus = 0;
	}
}

function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#popupForm").height();
	var popupWidth = $("#popupForm").width();
	//centering
	$("#popupForm").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});
	
}

function doPopup(id){

		$.ajax({
  		type: "POST",
  		url: "get360Images.php",
  		data: "pid="+id,
  		success: function(msg){
				
				$('#returnMessage').empty();
				$('#returnMessage').append(msg);
				centerPopup();
				loadPopup();
				
			
			}
		});	//END AJAX	
}


$(document).ready(function(){
						   

				
	$("#popupFormClose").click(function(){
		disablePopup();
	});

	
	$("#popupFormCloseMessage").click(function(){
		disablePopup();
	});

	$("#backgroundPopup").click(function(){
		if(popupStatusLoggedIn==1){				 
			disableLoggedInPopup();}
		if(popupStatus==1){
			disablePopup();}
	});

	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});

});