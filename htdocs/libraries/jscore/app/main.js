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
  , mediator = require('mediator')
  , app = {
    initialize: function() {
      routes.initialize();
      moduleActivator.execute();

      $(document).ready(function() {
        if(icms.redirectmessage !== false) {
          notifier.initialize(icms.redirectMessage);
        }
        mediator.subscribe('addNotification', function(message, options) {
          notifier.initialize(message, options);
        });
      });
    }
  };
  return app;
});
