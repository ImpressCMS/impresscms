define([
  'jquery'
  , 'themeBase/app/modules/flexslider/flexcore'
], function ($) {
  var module = {
    initialize: function(ele) {
      $(document).ready(function() {
        ele
        .fitVids()
        .flexslider({
          animation: 'slide',
          useCSS: false,
          animationLoop: false,
          smoothHeight: true
        });

      });
    }
  };
  return module;
});