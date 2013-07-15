/* global icms: true */
/*
  Module: Admin menu
  If you are an admin - you should see a pretty admin menu now.
  
  Method: initialize
  Loads the adminMenu CSS
  Sets up the dom for the menu

  Method: buildMenu
  Passes our menu data to our template and appends container to dom.
*/
define([
  'jquery'
  , 'i18n!nls/labels'
  , 'hb!modules/adminMenu/templates/adminMenu.tpl'
  , 'css!modules/adminMenu/media/adminMenu.css'
]
, function($, labels, adminTpl) {
  var app = {
    initialize: function() {
      var hideMenu = icms.config.showProjectMenu ? '' : ' hideProjectMenu';
      $(document).ready(function() {
        $('body').addClass('adminMenu' + hideMenu).append('<div id="admin-menu-wrapper" />');
        app.buildMenu();
      });
    }
    , buildMenu: function() {
      var adminHTML = adminTpl({
        labels: labels
        , config: icms.config
        , user: icms.user
        , admenu: icms.config.adminMenu
      });

      $('#admin-menu-wrapper').append(adminHTML);
    }
  };
  return app;
});
