(function ($) {
  $(document).ready(function() {
  // Begin domReady

    $('a[rel="external"]').click(function(){
      $(this).attr('target', '_blank');
    });

    $("ul.sf-menu").superfish();

  // End DomReady
  });
}(jQuery));