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
  , app = {
    initialize: function() {
      tools.loadCSS(icms.config.jscore + 'app/modules/adminMenu/adminMenu.css', 'icms-adminMenu');
      $(document).ready(function() {
        $('body').addClass('adminMenu').prepend('<div id="admin-menu-wrapper" />');
        app.buildMenu(icms.config.adminmenu);
      });
    }
    , buildMenu: function(data) {
    }
  };
  return app;
});
