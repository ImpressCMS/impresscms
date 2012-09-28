(function ($) {
  $(document).ready(function() {
  // Begin domReady
    boiler.dom.left.css({
      minHeight: boiler.dom.left.parent().height() + 20
    });

    $(window).resize(function() {
      boiler.dom.left.attr('style', '').css({
        minHeight: boiler.dom.left.parent().height() + 20
      });
    });
  // End DomReady
  });
}(jQuery));