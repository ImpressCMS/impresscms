/* global icms: true */
define([
  'jquery'
  , 'util/core/tools'
  , 'plugins/colorpicker/jquery.miniColors'
]
, function($, tools) {
  var app = {
    initialize: function(ele, options) {
      tools.loadCSS(icms.config.jscore + 'plugins/colorpicker/jquery.miniColors.css', 'core-jquery-colorPicker');
      if(typeof ele !== 'undefined') {
        app.bindColor(ele, options);
      }
    }

    , bindColor: function(ele) {
      $(document).ready(function() {
        $(ele).miniColors();
      });
    }
  };
  return app;
});