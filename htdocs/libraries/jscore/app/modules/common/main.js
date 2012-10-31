/*
  Module: Common
  Provides common functions to system
  
  Method: initialize
  if notification are needed initialize is triggered and adds the needed resources to the page.
*/
define(function(require) {
  var $ = require('jquery')
  , _private = {
    openWithSelfMain: function(url,name,width,height,returnwindow) {
      var options = "width=" + width + ",height=" + height + ",toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no"
      , new_window = window.open(url, name, options);

      if (typeof returnwindow !== 'undefined') {
        return new_window;
      }
    }
    , xoopsGetElementById: function(id) {
      return $('#' + id);
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
  }
  , app = {
    initialize: function() {
      var methods = _private;
      $.extend(window, methods);
    }
  };
  return app;
});