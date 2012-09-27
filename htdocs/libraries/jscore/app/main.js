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
  , adminMenu = require('modules/adminMenu/main')
  , uitools = require('modules/uitools/main')
  , validator = require('modules/validator/main')
  , mediator = require('mediator')
  , app = {
    initialize: function() {
      //Prevents IE from breaking on console entries.
      if (!window.console) {
        window.console = {
          log:function(){},
          warn:function(){}
        };
      }

      routes.initialize();
      moduleActivator.execute();
      uitools.initialize();
      validator.initialize();

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

        if(icms.config.adminMenu !== false) {
          adminMenu.initialize();
        }
      });
    }
  };
  return app;
});
