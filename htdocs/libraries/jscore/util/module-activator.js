/*	loads module based on data-module and data-module-parameters
 *
 *	-will dynamically load data-module-cssonly if parameter is defined
 *	-runs module's 'init' function after load
 *	-returns exports with public 'execute' function
 *
 */

define(['util/tools'], function (tools) {
  var exports = {};
  exports.execute = function (element) {
    $("[data-module-cssonly]", element).each(function () {
      var item = $(this)
      , css = item.data("module-cssonly");

      tools.loadCSS(css);
    });
    
    $("[data-module]", element).each(function () {
      var item = $(this)
      , module = item.data("module")
      , parameters = item.data("module-parameters");

      require([module], function (mod) {
        if (mod.css) {
          tools.loadCSS(mod.css);
        }
        
        if (mod.initialize) {
          mod.initialize(item, parameters);
        }
      });
    });
  };
  return exports;
});