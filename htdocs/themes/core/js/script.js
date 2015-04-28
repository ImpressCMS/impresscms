/* Custom scripts */

/* (dropdown-toggle) = Toggle*/
/* (button.noConflict) = Killed the conflict with jQuery UI "close-button"*/
$(function() {
      $('.dropdown-toggle').dropdown();
      $('.dropdown-menu').find('form').click(function(e) {
      e.stopPropagation();
      });
      
      if($.fn.button.noConflict) {
      $.fn.btn = $.fn.button.noConflict();
      }
	$('[data-toggle=tooltip]').tooltip();
    $(window).bind('scroll', function() {
        var navHeight = $("#navbar").height();
		var navWidth = $("#navbar_container").height();
		 //var navHeight = 150; 
        ($(window).scrollTop() > navHeight) ? $('nav').addClass('goToTop col-md-12') : $('nav').removeClass('goToTop col-md-12');
		 ($(window).scrollTop() > navWidth) ? $('#navbar_container').addClass('container-fluid navbar_container') : $('#navbar_container').removeClass('container-fluid navbar_container');
    });
	
	var $searchlink = $('#searchtoggle');
	var $searchbar  = $('#searchbar');
  
  $('#searchtoggle').on('click', function(e){
    e.preventDefault();
    
    if($(this).attr('id') == 'searchtoggle') {
      
      $searchbar.slideToggle(300, function(){
        // callback after search bar animation
      });
    }
  });
  var $searchtoggle_span = $('#searchtoggle_span');
  $('#searchtoggle_span').on('click', function(e){
	if(!$searchbar.is(":visible")) { 
        // if invisible we switch the icon to appear collapsable
        $searchtoggle_span.removeClass('glyphicon-search btn-warning').addClass('glyphicon-remove btn-danger');
      } else {
        // if visible we switch the icon to appear as a toggle
        $searchtoggle_span.removeClass('glyphicon-remove btn-danger').addClass('glyphicon-search btn-warning');
      }
  });
  
  $('.searchmobile').submit(function(e){
    e.preventDefault(); // stop form submission
  });
 $('#backTop').backTop({

  // the scroll position in px from the top
  'position' : 550,

  // scroll animation speed
  'speed' : 400,

  // red, white, black or green
  'color' : 'black',
});

});

	 
	
