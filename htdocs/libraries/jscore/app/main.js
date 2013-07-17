/* global icms: true */
/*
  Module: Main
  The main app module

    Method: initialize
    Initializes the routes.
    Scrape the page for widgets
*/
require([
  'jquery'
  , 'core'
  , 'ext/router'
  , 'ext/activator'
  , 'modules/notify/main'
  , 'modules/uitools/main'
  , 'modules/validator/main'
]
, function($, Core, routes, activator, notifier, uitools, validator) {
  var _private = {
    appendSelectOption: function(selectMenuId, optionName, optionValue){
      $('<option />', {
        'value': optionValue,
        'text': optionName,
        'selected' : 'selected'
      }).appendTo($('#' + selectMenuId));
    }
    , changeDisplay: function(id) {
      $('#' + id).toggle();
    }
    , disableElement: function(target){
      var targetDom = $('#' + target)
      , state = targetDom.attr('disabled');
      targetDom.attr('disabled', !state);
    }
    , justReturn: function() {
      return;
    }
    , makeBold: function(id) {
      var el = $('#' + id);
      el.css({
        'font-weight': el.css('font-weight') === 'bold' ? 'normal' : 'bold'
      });
    }
    , openWithSelfMain: function(url,name,width,height,returnwindow) {
      var options = 'width=' + width + ',height=' + height + ',toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no'
      , newWindow = window.open(url, name, options);

      if (typeof returnwindow !== 'undefined') {
        return newWindow;
      }
    }
    , setElementColor: function(id, color){
      $('#' + id).css({color: '#' + color});
    }
    , setElementFont: function(id, font){
      $('#' + id).css({fontFamily: font});
    }
    , setElementSize: function(id, size){
      $('#' + id).css({fontSize: size});
    }
    , setVisible: function(id) {
      $('#' + id).css({visibility: 'visible'});
    }
    , setHidden: function(id) {
      $('#' + id).css({visibility: 'hidden'});
    }
    , showImgSelected: function(imgId, selectId, imgDir, extra, icmsUrl) {
      var url = typeof(icmsUrl === 'undefined') ? './' : icmsUrl
      , imgDom = $('#' + imgId)
      , selectDom = $('#' + selectId);

      if (selectDom.options[selectDom.selectedIndex].value !== '') {
        imgDom.src = url + '/' + imgDir + '/' + selectDom.options[selectDom.selectedIndex].value + extra;
      } else {
        imgDom.src = url + '/images/blank.gif';
      }
    }
    , xoopsGetElementById: function(id) {
      return $('#' + id);
    }
    , xoopsGetFormElement: function(fname, ctlname) {
      var el = $('form[name=' + fname + ']').find('input[name=' + ctlname + ']');
      return el.length ? el : null;
    }
    , xoopsSavePosition: function(id) {
      var textareaDom = $('#' + id);
      if (textareaDom.createTextRange) {
        textareaDom.caretPos = document.selection.createRange().duplicate();
      }
    }
    , xoopsSetElementProp: function(name, prop, val) {
      var elt = $('#' + name);
      if (typeof elt !== 'undefined') {
        elt.attr(prop, val);
      }
    }
  }
  , app = {
    initialize: function() {
      // assigning Core to icms object
      icms.routeReady = $.Deferred();
      icms.core = Core;
      icms.core.mediator.subscribe('addNotification', function(message, options) {
        notifier.showMessage(message, options);
      });

      if(icms.config.adminMenu !== false) {
        require(['modules/adminMenu/main'], function(adminMenu) {
          adminMenu.initialize();
        });
      }

      $.extend(window, _private);
      icms.core.mediator.publish('commonReady');

      routes.initialize();
      activator.execute();
      uitools.initialize();
      validator.initialize();

      $(document).ready(function() {
        $('a[rel="external"]').click(function(){
          $(this).attr('target', '_blank');
        });

        if(icms.redirectmessage !== false) {
          notifier.showMessage(icms.redirectMessage);
        }
      });
    }
  };
  return app.initialize();
});