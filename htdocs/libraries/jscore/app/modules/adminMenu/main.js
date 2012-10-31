/*
  Module: Admin menu
  If you are an admin - you should see a pretty admin menu now.
  
  Method: initialize
  Loads the adminMenu CSS
  Sets up the dom for the menu

  Method: buildMenu
  Passes our menu data to our template and appends container to dom.
*/
define(function(require) {
  require('handlebars');
  var $ = require('jquery')
  , tools = require('util/core/tools')
  , labels = require('locale/labels')
  , adminHTML = require('hbs!templates/adminMenu/adminMenu')
  , defaultData = {
    labels: labels
    , config: icms.config
    , user: icms.user
    , admenu: icms.config.adminMenu
  }
  , markup
  , app = {
    initialize: function() {
      tools.loadCSS(icms.config.jscore + 'app/modules/adminMenu/adminMenu.css', 'icms-adminMenu');
      $(document).ready(function() {
        $('body').addClass('adminMenu').append('<div id="admin-menu-wrapper" />');
        app.buildMenu(icms.config.adminmenu);
      });
    }
    , buildMenu: function(data) {
      markup = adminHTML(defaultData);
      $('#admin-menu-wrapper').append(markup);

    }
  };
  return app;
});
