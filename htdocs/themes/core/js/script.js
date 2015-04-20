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
});



