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

        $('form').on({
          submit: function() {
            var $this = $(this), eles, i, input
            , notifSettings = {
              type: 'error'
              , timeout: 5
            };

            if($this.hasClass('doValidation')) {
              eles = $this.find('.required');
              // Loop through required elements and ensure a value is present - otherwise add error
              for(i=0; i<eles.length;i++){
                if($(eles[i]).find('input').val() === '') {
                  $(eles[i]).find('input:first').addClass('error');
                }
                if($(eles[i]).find('textarea').val() === '') {
                  $(eles[i]).find('textarea:first').addClass('error');
                }
                if($(eles[i]).find('select').val() === '') {
                  $(eles[i]).find('select:first').addClass('error');
                }
              }

              // Are ethere any errors?
              if($this.find('.error').length) {
                // If we have errors on the form - animate the window to the first field with an error
                $("html, body").animate({ scrollTop: parseInt($this.find('.error:first').closest('.fieldWrapper').offset().top, 10)}, 600);

                // Notify the user of the error
                mediator.publish('addNotification', errors.required , notifSettings);

                // When the user changes teh value of the field - remove the error state
                $this.find('.error').on({
                  focus: function() {
                    $(this).removeClass('error');
                  }
                });
              } else {
                return true;
              }
            }
            return false;
          }
        });


      });
    }
  };
  return module;
});
