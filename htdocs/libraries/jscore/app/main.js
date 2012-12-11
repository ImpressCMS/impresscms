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
  // , common = require('modules/common/main')
  , notifier = require('modules/notify/main')
  , adminMenu = require('modules/adminMenu/main')
  , uitools = require('modules/uitools/main')
  , validator = require('modules/validator/main')
  , i18n = require('modules/i18n/main')
  , mediator = require('mediator')
  , _private = {
    openWithSelfMain: function(url,name,width,height,returnwindow) {
      var options = "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no"
      , new_window = window.open(url, name, options);

      if (typeof returnwindow !== 'undefined') {
        return new_window;
      }
    }
    , xoopsGetElementById: function(id) {
      return $('#' + id);
    }
    , setElementColor: function(id, color){
      $('#' + id).css({color: '#' + color});
    }
    , setElementFont: function(id, font){
      $('#' + id).css({fontFamily: font});
    }
    , setElementSize: function(id, size){
      $('#' + id).css({fontSize: size});
    }
    , setVisible: function(id) {
      $('#' + id).css({visibility: 'visible'});
    }
    , setHidden: function(id) {
      $('#' + id).css({visibility: 'hidden'});
    }
  }
  , app = {
    initialize: function() {
      $.extend(window, _private);
      mediator.publish('commonReady');

      log.initialize();
      routes.initialize();
      moduleActivator.execute();
      // common.initialize();
      uitools.initialize();
      validator.initialize();

      if(icms.config.adminMenu !== false) {
        adminMenu.initialize();
      }

      if(icms.config.i18n !== false) {
        i18n.initialize();
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
