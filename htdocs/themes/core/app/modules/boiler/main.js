define(function(require) {
  var $ = require('jquery')
  , _boilerPlugins = require('themeBase/app/libs/boiler-core-plugins')
  , _hover = require('hoverIntent')
  , app = {
    initialize: function() {
      $(document).ready(function() {

        if(boiler.dom.navigation.length) {
          app.mobileMenus();
          $('#primary_navigation li').has('ul').addClass('hasChildren').hoverIntent({
            over: function() {
              $(this).children('ul').stop(true,true).slideDown(400);
            },
            timeout: 500,
            out: function() {
              $(this).children('ul').slideUp(100);
            }
          });
        }

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
    , mobileMenus: function() {
      // Create the dropdown base
      var toSel = $('.toSel')
      , sel, i, menu, depth;

      toSel.each(function() {
        sel = $('<select class="mobileMenu"><option value="false" text="Navigation">Navigation</option></select>');
        menu = $(this);
        menu.after(sel);
        menu.find('a').each(function() {
          var el = $(this)
          , depth = el.parentsUntil(toSel).length;

          $('<option />', {
            'value': el.attr('href').match(/void/) ? false : el.attr('href'),
            'text': depth > 1 ? '--' + el.text() : el.text(),
            'selected' : el.hasClass('active') ? 'selected' : false
          }).appendTo(sel);
        });
      });

      $('.mobileMenu').on({
        change: function() {
          var _this = $(this).find("option:selected");
          if(_this.val() !== 'false') {
            window.location = _this.val();
          }
        }
      });
    }
  };
  return app;
});