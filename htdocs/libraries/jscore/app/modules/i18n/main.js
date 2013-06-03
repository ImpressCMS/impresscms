define([
  'jquery'
  , 'mediator'
  // , 'plugins/jquery.ui/jquery.ui.i18n'
]
, function($, mediator) {
  var module = {
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