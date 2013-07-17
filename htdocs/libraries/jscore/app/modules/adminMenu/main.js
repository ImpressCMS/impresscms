/* global icms: true */
/*
  Module: Admin menu
  If you are an admin - you should see a pretty admin menu now.
  
  Method: initialize
  Loads the adminMenu CSS
  Sets up the dom for the menu
  Passes our menu data to our template and appends container to dom.
*/
define([
  'jquery'
  , 'i18n!nls/labels'
  , 'hb!modules/adminMenu/templates/adminMenu.tpl'
  , 'css!modules/adminMenu/media/adminMenu.css'
  , 'bootstrap/affix'
]
, function($, labels, adminTpl) {
  var app = {
    initialize: function() {
      var hideMenu = icms.config.showProjectMenu ? '' : ' hideProjectMenu'
      , adminHTML = adminTpl({
        labels: labels
        , config: icms.config
        , user: icms.user
        , admenu: icms.config.adminMenu
      });

      $('body').addClass('adminMenu' + hideMenu).append(adminHTML);
      // $('#admin-menu').affix();
    }
  };
  return app;
});
