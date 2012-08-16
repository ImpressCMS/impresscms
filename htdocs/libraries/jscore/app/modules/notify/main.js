/*
  Module: Notify
  Handles display notification if needed
  See: http://richardhsu.github.com/jquery.ambiance/#_ for options

  Method: initialize
  if notification are needed initialize is triggered and adds the needed resources to the page.
*/
define(function(require) {
  var $ = require('jquery')
  , tools = require('util/tools')
  , notifyCore = require('plugins/jquery.ambiance')
  , notifyDefaults = {
    timeout: 4
  }
  , app = {
    initialize: function(message, options) {
      tools.loadCSS(icms.config.jscore + 'app/modules/notify/notify.css', 'jquery-notify');
      app.showMessage(message, options);
    }
    , showMessage: function(message, options) {
      if(typeof message !== 'undefined' && message !== false) {
        notifyDefaults.message = message;
        $.extend(notifyDefaults, options);
        $.ambiance(notifyDefaults);
      }
    }
  };
  return app;
});