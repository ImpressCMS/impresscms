define(function (require) {
  var $ = require('jquery')
  , module = {
    initialize: function(ele) {
      $(window).load(function() {
        ele
        .fitVids()
        .flexslider({
          animation: "slide",
          useCSS: false,
          animationLoop: false,
          smoothHeight: true
        });

      });
    }
  };
  return module;
});