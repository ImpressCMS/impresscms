/*
	Module: Main
	The main app module

		Method: initialize
		Initializes the routes.
    Scrape the page for widgets
*/
define(function(require) {
  var $ = require('jquery')
  , log = require('util/core/log')
  , routes = require('app/routes')
  , moduleActivator = require('util/require-utils/module-activator')
  , notifier = require('modules/notify/main')
  , adminMenu = require('modules/adminMenu/main')
  , uitools = require('modules/uitools/main')
  , validator = require('modules/validator/main')
  , mediator = require('mediator')
  , app = {
    initialize: function() {
      log.initialize();
      routes.initialize();
      moduleActivator.execute();
      uitools.initialize();
      validator.initialize();

      if(icms.config.adminMenu !== false) {
        adminMenu.initialize();
      }

      $(document).ready(function() {
        $('a[rel="external"]').click(function(){
          $(this).attr('target', '_blank');
        });

        if(icms.redirectmessage !== false) {
          notifier.initialize(icms.redirectMessage);
        }
        mediator.subscribe('addNotification', function(message, options) {
          notifier.showMessage(message, options);
        });
      });
    }
  };
  return app;
});
