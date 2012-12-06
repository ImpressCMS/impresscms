define(function(require) {
  var $ = require('jquery')
  , _boilerPlugins = require('themeBase/app/lib/boiler-core-plugins')
  , app = {
    initialize: function() {
      $(document).ready(function() {
      // Begin domReady
        $("ul.sf-menu").superfish();
        $('.sf-with-ul').parent().addClass('hasChildren');

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
    }
  };
  return app;
});