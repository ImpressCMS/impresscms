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
  , editHTML = require('hbs!templates/editBlock/editBlock')
  , data = {}
  , markup
  , firstrun = true
  , app = {
    initialize: function(ele, options) {
      if(typeof ele !== 'undefined') {
        if(firstrun) {
          firstrun = false;
          tools.loadCSS(icms.config.jscore + 'plugins/jquery.ui/css/' + icms.config.uiTheme + '/jquery.ui.css', 'jquery-ui');
        }
        data.labels = labels;
        data.block = {
          moduleUrl: icms.config.url + '/modules'
          , imagesetUrl: icms.config.imageset
        };
        app.bindDialog(ele, data);
      }
    }
    , loadResources:  function() {

    }
    , bindDialog: function(ele, data) {
        $(document).ready(function() {
          $(ele).on({
            click: function(e) {
              e.preventDefault();
              var _this = $(this);
              data.block.blockId = _this.data('blockid');
              data.block.canDelete = _this.data('candelete');
              data.block.retUrl = _this.data('returl');

              markup = editHTML(data);

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
