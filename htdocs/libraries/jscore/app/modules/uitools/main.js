/*
  Module: UI Tools
  Provides UI layer provided by Twitter Bootstrap
  See: http://twitter.github.com/bootstrap/javascript.html
  Includes UI layer from jquery UI

  Method: initialize
  Loads required javascript and css

  Method: ui
  Binds some default behaviors to elements that exist.
*/
define(function(require) {
  var $ = require('jquery')
  , tools = require('util/core/tools')
  , bs = require('plugins/twitter_bootstrap')
  , jui = require('plugins/jquery.ui/jquery.ui')
  , mediator = require('mediator')
  , module = {
    initialize: function(message, options) {
      tools.loadCSS(icms.config.jscore + 'app/modules/uitools/uitools.css', 'core-uitools');
      tools.loadCSS(icms.config.jscore + 'plugins/jquery.ui/css/' + icms.config.uiTheme + '/jquery.ui.css', 'core-jquery-ui');
      $(document).ready(function() {
        module.ui();
        module.helptip();
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

    , helptip: function() {
      var helptips = $('.helptip a');

      helptips.each(function() {
        var _this = $(this);
        _this.off().on({
          click: function(e) {
            e.preventDefault();
            return false;
          }
        }).popover({
          placement: 'left'
          , trigger: 'click'
          , content: _this.closest('label').find('.helptext').html()
        });
      });
    }
  };
  return module;
});
