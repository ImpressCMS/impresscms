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
  , labels = require('locale/labels')
  , adminHTML = require('text!templates/adminMenu/adminMenu.html')
  , Handlebars = require('util/use!handlebars')
  , defaultData = {
    labels: labels
    , config: icms.config
    , user: icms.user
    , admenu: icms.config.adminMenu
  }
  , adminTemplate
  , markup
  , app = {
    initialize: function() {
      tools.loadCSS(icms.config.jscore + 'app/modules/adminMenu/adminMenu.css', 'icms-adminMenu');
      $(document).ready(function() {
        $('body').addClass('adminMenu').prepend('<div id="admin-menu-wrapper" />');
        app.buildMenu(icms.config.adminmenu);
      });
    }
    , buildMenu: function(data) {
      adminTemplate = Handlebars.compile(adminHTML);
      console.log(defaultData);
      markup = adminTemplate(defaultData);

      $('#admin-menu-wrapper').append(markup);
    }
  };
  return app;
});
