define(function(require) {
  var $ = require('jquery')
  , test = require('themeBase/app/modules/test')
  , app = {
    initialize: function() {
      console.log('In Theme Route');
      test.initialize();
    }
  };
  return app;
});