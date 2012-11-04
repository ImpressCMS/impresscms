define(function(require) {
  var $ = require('jquery')
  , tools = require('util/core/tools')
  , cpicker = require('plugins/colorpicker/jquery.miniColors')
  , app = {
    initialize: function(ele, options) {
      tools.loadCSS(icms.config.jscore + 'plugins/colorpicker/jquery.miniColors.css', 'core-jquery-colorPicker');
      if(typeof ele !== 'undefined') {
        app.bindColor(ele, options);
      }
    }

    , bindColor: function(ele, options) {
      $(document).ready(function() {
        $(ele).miniColors();
      });
    }
  };
  return app;
});