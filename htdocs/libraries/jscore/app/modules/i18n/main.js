define(function(require) {
  var $ = require('jquery')
  // , uiI18N = require('plugins/jquery.ui/jquery.ui.i18n')
  , mediator = require('mediator')
  , module = {
    inititialize: function() {
      $(document).ready(function() {
        mediator.subscribe('uitoolsReady', function() {
          // $.datepicker.setDefaults($.datepicker.regional[icms.config.i18n.locale]);
        });
      });
    }
  };
  return module;
});