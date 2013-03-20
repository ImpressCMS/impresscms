/*
 * Boiler Core
 * Version: 1.0
 * Author: William Hall http://mrtheme.com
 *
 * Provides routine methods and utilities for the boiler theme framework
 */
var boiler = {};
!function($) {
  boiler.wrap = function(elements, size, wrapElement, wrapClass) {
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
  };
  $(document).ready(function() {
    // Begin domReady
    boiler.dom = {
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
    };
    // End DomReady
  });
}(window.jQuery);