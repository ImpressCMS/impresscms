/* global icms: true */
define([
  'jquery'
  , 'css!plugins/colorpicker/jquery.miniColors.css'
  , 'plugins/colorpicker/jquery.miniColors'
]
, function($) {
  var app = {
    initialize: function(ele, options) {
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