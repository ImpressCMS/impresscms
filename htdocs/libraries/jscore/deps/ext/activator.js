/**
 * Activator
 * @return function loads widgets by data attribute
 */
define(function () {
  var exports = {};
  exports.execute = function (element) {
    $('[data-module]', element).each(function () {
      var item = $(this)
      , module = item.data('module')
      , parameters = item.data('module-parameters')
      , initMod = item.data('init') === false ? false : true;

      require([module], function (mod) {
        if (initMod) {
          mod.initialize(item, parameters);
        }
      });
    });
  };
  return exports;
});