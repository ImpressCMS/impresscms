/*
  Module: Popup
  Handles Handles behavior of debugger popup

  Method: initialize
*/
define(function(require) {
  var $ = require('jquery')
  , popupHTML = require('hbs!templates/debugger/popup')
  , mediator = require('mediator')
  , data = {}
  , styles
  , scripts
  , markup = null
  , newdoc = null
  , i
  , app = {
    initialize: function(options) {
      $(document).ready(function() {
        styles = $('link[rel="stylesheet"]');
        data.styles = [];
        data.icms = icms;
        data.hasBootstrap = window.hasBootstrap !== 'undefined' ? window.hasBootstrap : false;

        for (i=0; i<styles.size(); i++) {
          if($(styles[i]).attr('href') !== 'undefined') {
            data.styles[i] = styles[i].href;
          }
        }

        data.close = options.close;
        data.config = icms.config;
        data.dump = options.dump;

        markup = popupHTML(data);
        console.log('hey', data);

        mediator.subscribe('commonReady', function() {
          newdoc = window.openWithSelfMain("", "", 680, 450, true);
          newdoc.window.icms = {};
          $.extend(newdoc.window.icms, window.icms);
          newdoc.window.routeReady = {};
          $.extend(newdoc.window.routeReady, window.routeReady);
          newdoc.window.hasBootstrap = window.hasBootstrap !== 'undefined' ? window.hasBootstrap : false;
          newdoc.document.write(markup);
        });
      });
    }
  };
  return app;
});