function lookup(inputString) {
 if(inputString.length < 3) {
  jQuery('#suggestions').fadeOut(); // Hide the suggestions box
 } else {
  jQuery.post(""+suggestpath+"", {queryString: ""+inputString+""}, function(data) { // Do an AJAX call
   jQuery('#suggestions').fadeIn(); // Show the suggestions box
   jQuery('#suggestions').html(data); // Fill the suggestions box
  });
 }
}

var currentSelection = 0;
var currentUrl = '';
jQuery(document).ready(function() {
	// Register keypress events on the whole document
	jQuery(document).keypress(function(e) {
	 if(jQuery('#inputString').has(':focus')) {
		switch(e.keyCode) { 
			case 37:
				jQuery('#inputString').blur(); // Disable keyboard
			break;
			// User pressed "up" arrow
			case 38:
				navigate('up');
			break;
			case 39:
				jQuery('#suggestions ul li a:first').focus(); // Enable keyboard
			break;
			// User pressed "down" arrow
			case 40:
				navigate('down');
				jQuery('#suggestions ul li a:first').blur();
			break;
			// User pressed "enter"
			case 13:
				if(currentUrl != '') {
					window.location = currentUrl;
				}
			break;
		}
	}
	});
	// Add data to let the hover know which index they have
	for(var i = 0; i < jQuery("#suggestions ul li a").size(); i++) {
		jQuery("#suggestions ul li a").eq(i).data("number", i);
	}
	
	// Simulote the "hover" effect with the mouse
	jQuery("#suggestions ul li a").hover(
		function () {
			currentSelection = jQuery(this).data("number");
			setSelected(currentSelection);
		}, function() {
			jQuery("#suggestions ul li a").removeClass("itemhover");
			currentUrl = '';
		}
	);
});

function navigate(direction) {
	// Check if any of the menu items is selected
	if(jQuery("#suggestions ul li .itemhover").size() == 0) {
		currentSelection = -1;
	}
	
	if(direction == 'up' && currentSelection != -1) {
		if(currentSelection != 0) {
			currentSelection--;
		}
	} else if (direction == 'down') {
		if(currentSelection != jQuery("#suggestions ul li").size() -1) {
			currentSelection++;
		}
	}
	setSelected(currentSelection);
}

function setSelected(menuitem) {
	jQuery("#suggestions ul li a").removeClass("itemhover");
	jQuery("#suggestions ul li a").eq(menuitem).addClass("itemhover");
	currentUrl = jQuery("#suggestions ul li a").eq(menuitem).attr("href");
}
