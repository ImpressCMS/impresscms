define(function(require) {
  var $ = require('jquery')
  , tools = require('util/core/tools')
  , pass = require('plugins/password/passfield')
  , app = {
    initialize: function(ele, options) {
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