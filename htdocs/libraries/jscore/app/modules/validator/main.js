/*
  Module: Validator
  Handles loose validation of forms

  Method: initialize
  determines if there is a form that requires validation.
*/
define(function(require) {
  var mediator = require('mediator')
  , errors = require('locale/errors')
  , module = {
    initialize: function(message, options) {
      $(document).ready(function() {
        var forms = $('form'), i, j, reqs = null;
        for(i=0; i<forms.length; i++){
          reqs = $(forms[i]).find('.required');
          if(reqs !== null) {
            $(forms[i]).addClass('doValidation');
            reqs = null;
          }
        }

        $('form').submit(function() {
          var $this = $(this), eles, i, input
          , notifSettings = {
            type: 'error'
            , timeout: 5
          };

          if($this.hasClass('doValidation')) {
            eles = $this.find('.required');
            for(i=0; i<eles.length;i++){
              if($(eles[i]).find('input').val() === '') {
                $(eles[i]).find('input').addClass('error');
              }
            }
            if($this.find('.error').length) {
              mediator.publish('addNotification', errors.required , notifSettings);
            } else {
              return true;
            }
          }
          return false;
        });
      });
    }
  };
  return module;
});
