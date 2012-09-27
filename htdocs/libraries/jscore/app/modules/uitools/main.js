/*
  Module: UI Tools
  Provides UI layer provided by Twitter Bootstrap
  See: http://twitter.github.com/bootstrap/javascript.html

  Method: initialize
  Loads required javascript and css

  Method: ui
  Binds some default behaviors to elements that exist.
*/
define(function(require) {
  var $ = require('jquery')
  , tools = require('util/tools')
  , bs = require('plugins/twitter_bootstrap')
  , mediator = require('mediator')
  , module = {
    initialize: function(message, options) {
      // tools.loadCSS(icms.config.jscore + 'app/modules/uitools/uitools.css', 'core-uitools');
      $(document).ready(function() {
        module.ui();
        mediator.publish('uitoolsReady');
      });
    }

    , ui: function() {
      var hash = window.location.hash;
      $('.tabbable .nav a').click(function (e) {
        e.preventDefault();
        // $(this).siblings().removeClass('active');
        $(this).tab('show');
      });

      // Using btn's like tabs.
      $('.btn-toggles .btn-group .btn').click(function(e) {
        e.preventDefault();
        var _this = $(this)
        , content = _this.closest('.btn-toggles').find('.tab-content')
        , currActive = content.find('.tab-pane.active')
        , nextActive = content.find(_this.attr('data-toggle'));

        _this.siblings().removeClass('active');
        currActive.removeClass('active');
        _this.addClass('active');
        nextActive.addClass('active');
      });

      // if a tab is present let's just general assume we wanna click a link with an equal href.
      if(typeof hash !== 'undefined' || hash !== '') {
        hash = hash === '#lost' ? hash + 'pass-form' : hash;
        $('a[href="' + hash + '"]').click();
      }
    }
  };
  return module;
});
