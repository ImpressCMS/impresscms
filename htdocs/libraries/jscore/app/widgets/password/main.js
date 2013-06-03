/* global icms: true */
define([
  'jquery'
  , 'util/core/tools'
  , 'plugins/password/passfield'
]
, function($, tools) {
  var app = {
    initialize: function(ele) {
      tools.loadCSS(icms.config.jscore + 'plugins/password/passfield.css', 'core-jquery-password');

      if(typeof ele !== 'undefined') {
        app.enhancePass(ele);
      }
    }

    , enhancePass: function(ele) {
      // 2k2tzske
      ele.passField({
        'showTip': true
        , 'showWarn': true
        , 'showGenerate' : true
      });
    }
  };
  return app;
});