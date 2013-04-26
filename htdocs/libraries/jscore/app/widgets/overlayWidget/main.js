/*
  Module: Edit Block
  Handles displaying the edit block modal for admins

  Method: initialize
*/
define(function(require) {
  var $ = require('jquery')
  , ui = require('plugins/jquery.ui/jquery.ui')
  , tools = require('util/core/tools')
  , labels = require('locale/labels')
  , app = {
    initialize: function(ele, options) {
      if(typeof ele !== 'undefined') {
        tools.loadCSS(icms.config.jscore + 'plugins/jquery.ui/css/' + icms.config.uiTheme + '/jquery.ui.css', 'jquery-ui');
      }
      
      app.bindDialog(ele, data);
    }
    , bindDialog: function(ele, data) {
      $(document).ready(function() {
        $(ele).on({
          click: function(e) {
            e.preventDefault();
            var _this = $(this)
            , thisOpts = _this.data('options');
            
            $(markup).dialog({
              modal: true
              , title: _this.data('title')
              , close: function() {
                $('.ui-dialog, .blockEditModalWrapper').remove();
              }
            });
          }
        });
      });
    }
  };
  return app;
});
