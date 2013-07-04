define([
	'themeBase/app/modules/boiler/main'
 ], function(boiler) {
  var app = {
    initialize: function() {
      boiler.initialize();
    }
  };
  return app;
});