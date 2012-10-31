(function ($) {
  $(document).ready(function() {
  // Begin domReady
    $("ul.sf-menu").superfish();
    $('.sf-with-ul').parent().addClass('hasChildren');
  // End DomReady
  });
}(jQuery));