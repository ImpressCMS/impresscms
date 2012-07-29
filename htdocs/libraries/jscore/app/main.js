/*
	Module: Main
	The main app module

		Method: initialize
		Initializes the routes.
    Scrape the page for widgets
*/
define(function(require) {
  var $ = require('jquery')
  , routes = require('app/routes')
  , moduleActivator = require('util/module-activator')
  , notifier = require('modules/notify/main')
  , app = {
    initialize: function() {
      routes.initialize();
      moduleActivator.execute();

      if(icms.redirectmessage !== false) {
        notifier.initialize(icms.redirectMessage);
      }
    }
  };
  return app;
});
