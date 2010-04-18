function lookup(inputString) {
 if(inputString.length < 3) {
  $('#suggestions').fadeOut(); // Hide the suggestions box
 } else {
  $.post(""+suggestpath+"", {queryString: ""+inputString+""}, function(data) { // Do an AJAX call
   $('#suggestions').fadeIn(); // Show the suggestions box
   $('#suggestions').html(data); // Fill the suggestions box
  });
 }
}

var currentSelection = 0;
var currentUrl = '';
$(document).ready(function() {
	// Register keypress events on the whole document
	$(document).keypress(function(e) {
		switch(e.keyCode) { 
			case 37:
				$('#inputString').focus().blur(); // Disable keyboard
			break;
			// User pressed "up" arrow
			case 38:
				navigate('up');
			break;
			case 39:
				$('#suggestions ul li a:first').focus(); // Enable keyboard
			break;
			// User pressed "down" arrow
			case 40:
				navigate('down');
				$('#suggestions ul li a:first').blur();
			break;
			// User pressed "enter"
			case 13:
				if(currentUrl != '') {
					window.location = currentUrl;
				}
			break;
		}
	});
	
	// Add data to let the hover know which index they have
	for(var i = 0; i < $("#suggestions ul li a").size(); i++) {
		$("#suggestions ul li a").eq(i).data("number", i);
	}
	
	// Simulote the "hover" effect with the mouse
	$("#suggestions ul li a").hover(
		function () {
			currentSelection = $(this).data("number");
			setSelected(currentSelection);
		}, function() {
			$("#suggestions ul li a").removeClass("itemhover");
			currentUrl = '';
		}
	);
});

function navigate(direction) {
	// Check if any of the menu items is selected
	if($("#suggestions ul li .itemhover").size() == 0) {
		currentSelection = -1;
	}
	
	if(direction == 'up' && currentSelection != -1) {
		if(currentSelection != 0) {
			currentSelection--;
		}
	} else if (direction == 'down') {
		if(currentSelection != $("#suggestions ul li").size() -1) {
			currentSelection++;
		}
	}
	setSelected(currentSelection);
}

function setSelected(menuitem) {
	$("#suggestions ul li a").removeClass("itemhover");
	$("#suggestions ul li a").eq(menuitem).addClass("itemhover");
	currentUrl = $("#suggestions ul li a").eq(menuitem).attr("href");
}
