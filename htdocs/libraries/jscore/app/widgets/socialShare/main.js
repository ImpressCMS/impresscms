/*
  Module: Edit Block
  Handles displaying the edit block modal for admins

  Method: initialize
*/
define(function(require) {
  var $ = require('jquery')
  , tools = require('util/core/tools')
  , activeServices = {
    fb : null
    , twit : null
  }
  , app = {
    initialize: function(ele, options) {
      $.extend(activeServices, tools.serializeArgs(options));

      if(activeServices.fb) {
        if(!$('#fb-root').length) {
          ele.before('<div id="fb-root"></div>');
        }
        app.facebook();
      }
    }
    , facebook: function() {
      (function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) {return;}js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));
    }
    , twitter: function() {

    }
    , addServices: function(services) {

    }
  };
  return app;
});
