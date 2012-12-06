define(function(require) {
  var $ = require('jquery')
  , boiler = require('themeBase/app/modules/boiler/main')
  , app = {
    initialize: function() {
      boiler.initialize();
    }
  };
  return app;
});