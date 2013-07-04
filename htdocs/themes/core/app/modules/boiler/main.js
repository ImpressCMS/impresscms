/* global boiler: true */
define([
  'jquery'
  , 'hoverIntent'
], function($) {
  var boiler = {
    wrap: function(elements, size, wrapElement, wrapClass) {
      if(elements === null || elements === 'undefined') {
        return;
      }
      if(size === null || size === 'undefined') {
        size = 3;
      }
      if(wrapElement === null || wrapElement === 'undefined') {
        wrapElement = 'div';
      }
      if(wrapClass === null || wrapClass === 'undefined') {
        wrapClass = '';
      }

      var elSize = elements.length
      , i = 0;
      for (i=0; i<elSize; i=i+3){
        elements.eq(i).add(elements.eq(i+1)).add(elements.eq(i+2)).wrapAll('<div class="boilerwrap ' + wrapClass + '" />');
      }
    }

    , dom: {
      body: $('body'),
      header: $('#header'),
      logo: $('#logo'),
      search: $('#header_search'),
      navigation: $('#primary_navigation'),
      page: $('#page'),
      preface: $('#preface_wrapper'),
      main: $('#main'),
      left: $('#leftside_wrapper'),
      contentZone: $('#content_wrapper'),
      content: $('#content'),
      right: $('#rightside_wrapper'),
      postscript: $('#postscript_wrapper'),
      footer: $('#footer')
    }
  }

  , app = {
    initialize: function() {
      // expose boiler api to window.
      window.boiler = boiler;

      $(document).ready(function() {

        if(boiler.dom.navigation.length) {
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
  };
  return app;
});