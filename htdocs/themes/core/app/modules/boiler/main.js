define(function(require) {
  var $ = require('jquery')
  , _boilerPlugins = require('themeBase/app/lib/boiler-core-plugins')
  , hover = require('hoverIntent')
  , app = {
    initialize: function() {
        $(document).ready(function() {

          $('#primary_navigation li').has('ul').addClass('hasChildren').hoverIntent({
            over: function() {
              $(this).children('ul').stop(true,true).slideDown(400);
            },
            timeout: 500,
            out: function() {
              $(this).children('ul').slideUp(100);
            }
          });

          boiler.dom.left.css({
            minHeight: boiler.dom.left.parent().height() + 20
          });

          $(window).resize(function() {
            boiler.dom.left.attr('style', '').css({
              minHeight: boiler.dom.left.parent().height() + 20
            });
          });

        });
    }
  };
  return app;
});