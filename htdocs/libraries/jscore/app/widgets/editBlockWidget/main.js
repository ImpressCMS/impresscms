/* global icms: true */
/*
  Module: Edit Block
  Handles displaying the edit block modal for admins

  Method: initialize
*/
define([
  'jquery'
  , 'util/core/tools'
  , 'locale/labels'
  , 'hbs!templates/editBlock/editBlock'
  , 'plugins/jquery.ui/jquery.ui'
]
, function($, tools, labels, editHTML) {
  var data = {}
  , markup
  , app = {
    initialize: function(ele) {
      if(typeof ele !== 'undefined') {
        tools.loadCSS(icms.config.jscore + 'plugins/jquery.ui/css/' + icms.config.uiTheme + '/jquery.ui.css', 'jquery-ui');
        data.labels = labels;
        data.block = {
          moduleUrl: icms.config.url + '/modules'
          , imagesetUrl: icms.config.imageset
        };
        app.bindDialog(ele, data);
      }
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
