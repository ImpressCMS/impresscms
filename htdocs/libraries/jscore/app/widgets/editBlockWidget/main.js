/* global icms: true */
/*
  Module: Edit Block
  Handles displaying the edit block dropdown for admins

  Method: initialize
*/
define([
  'jquery'
  , 'util/core/tools'
  , 'locale/labels'
  , 'hbs!templates/editBlock/editBlock'
]
, function($, tools, labels, editHTML) {
  var data = {}
  , markup
  , app = {
    initialize: function(ele) {
      if(typeof ele !== 'undefined') {
        data.labels = labels;
        data.block = {
          moduleUrl: icms.config.url + '/modules'
          , imagesetUrl: icms.config.imageset
        };
        app.addDrop(ele, data);
      }
    }

    , addDrop: function(ele, data) {
      var _this = ele;
      data.block.blockId = _this.data('blockid');
      data.block.canDelete = _this.data('candelete');
      data.block.retUrl = _this.data('returl');

      markup = editHTML(data);

      ele.append(markup);
    }
  };
  return app;
});
