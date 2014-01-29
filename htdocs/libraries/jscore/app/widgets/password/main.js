/* global icms: true */
define([
  'jquery'
  , 'css!plugins/password/passfield.css'
  , 'plugins/password/passfield'
]
, function() {
  var app = {
    initialize: function(ele) {
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