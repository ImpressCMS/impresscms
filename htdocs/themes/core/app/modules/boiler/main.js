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
      , dash = ['', '', '--', '&nbsp;&nbsp;--', '&nbsp;&nbsp;&nbsp;&nbsp;--'];

      toSel.each(function() {
        var menu = $(this)
        , label = typeof(menu.data('label')) !== 'undefined' ? menu.data('label') : 'Navigation'
        , sel = $('<select class="mobileMenu"><option value="false" text="Navigation">'+label+'</option></select>');

        menu.find('a').each(function() {
          var el = $(this)
          , depth = el.parents("ul").size()
          , oLabel = $('<span />')
          , text = typeof(el.data('prefix')) !== 'undefined' ? el.data('prefix') + el.text() : dash[depth] + el.text();

          if(typeof(el.data('hidden') !== 'undefined') && el.data('hidden') !== true) {
            $('<option />', {
              'value': el.attr('href').match(/void/) ? false : el.attr('href'),
              'text': oLabel.html(text).text(),
              'selected' : el.hasClass('active') ? 'selected' : false
            }).attr('class', el.attr('class')).appendTo(sel);
          }
        });
        menu.after(sel);
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