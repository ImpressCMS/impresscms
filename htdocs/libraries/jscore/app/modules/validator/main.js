/*
  Module: Validate
  Handles loose validation of forms
  Handles ajaxSubmission if required

  Method: initialize
  determines if there is a form that requires validation.
  if valid - check for ajax req and handle submission and callback
*/
define([
  'jquery'
  , 'mediator'
  , 'locale/errors'
  , 'plugins/forms/jquery.form'
  , 'plugins/forms/jquery.validate'
]
, function($, mediator, errors) {
  var module = {
    initialize: function() {
      $(document).ready(function() {
        var notifSettings = {
          type: 'error'
          , timeout: 5
        };

        $('form').each(function() {
          var $this = $(this)
          , formName = $this.attr('name')
          , ajax = $this.hasClass('ajax') ? true : false
          , errorSymbol = $('<i class="marker icon-warning-sign error">&nbsp;</i>');

          $this.validate({
            errorElement: 'span'
            , wrapper : '.icon-warning-sign'
            , highlight: function(element, errorClass) {
              if(!$(element).siblings('.icon-warning-sign').length) {
                $(element).addClass(errorClass).parent().prepend(errorSymbol.clone());
              }
            }
            , unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
                $(element).siblings('.icon-warning-sign').remove();
            }
            , submitHandler: function(form) {
              if(ajax) {
                form.ajaxSubmit({
                  success: function(data) {
                    mediator.publish('formCallback', formName, 'success', data);
                  }
                  , error: function(data) {
                    mediator.publish('formCallback', formName, 'error', data);
                  }
                });
              } else {
                form.submit();
              }
            }
            , invalidHandler: function(event, validator) {
              var count = validator.numberOfInvalids();
              if(count) {
                mediator.publish('addNotification', errors.required , notifSettings);
              }
            }
          });
        });
      });
    }
  };
  return module;
});
