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
  , labels = require('locale/labels')
  , modalTemplate = require('hbs!templates/uiTools/modal')
  , modalMarkup = null
  , modalData = {
    id: null
    , title: null
    , content: null
    , text: labels
  }
  , module = {
    initialize: function(message, options) {
      if(typeof hasBootstrap === 'undefined' || hasBootstrap === false) {
        tools.loadCSS(icms.config.jscore + 'app/modules/uitools/uitools.css', 'core-uitools');
      }
      tools.loadCSS(icms.config.jscore + 'plugins/jquery.ui/css/' + icms.config.uiTheme + '/jquery.ui.css', 'core-jquery-ui');
      $(document).ready(function() {
        module.ui();
        module.helptip();
        module.checkAll();
        module.modals();
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

      // At this time the most logical way to handle this is to check the hash against known values
      // We can not just assume a hash is intended to be a click event on a matching anchor.
      if(typeof hash !== 'undefined' || hash !== '') {
        if(hash === '#lost') {
          $('a[href="' + hash + 'pass-form"]').click();
        }
      }

    }

    , showPass: function() {
      // Allows passwordfields to be shown
      // <ele class="showpass" data-pass="#somePassFieldSelecter"> (should always be id)
      $('.showpass').on({
        click : function(e) {
          e.preventDefault();
          var _this = $(this)
          , pass = $(this).data('pass')
          , passVal = $(pass).attr('value')
          , selector = pass.match('#') ? pass.replace('#', '') : pass;

          if(_this.hasClass('btn-info')) {
            _this.removeClass('btn-info').find('.icon-eye-open').removeClass('icon-white');
            $(pass).replaceWith('<input class="input-large" type="password" id="' + selector + '" name="' + selector + '" value="' + passVal + '">');
          } else {
            _this.addClass('btn-info').find('.icon-eye-open').addClass('icon-white');
            $(pass).replaceWith('<input class="input-large" type="text" id="' + selector + '" name="' + selector + '" value="' + passVal + '">');
          }

          return false;
        }
      });
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

    , checkAll: function() {
      $('.checkemallWrapper input').on({
        change: function() {
          var _this = $(this);
          if(_this.is(':checked')) {
            $(this).closest('.grouped').find('input[type="checkbox"]').attr('checked', true);
          } else {
            $(this).closest('.grouped').find('input[type="checkbox"]').attr('checked', false);
          }
        }
      });
    }

    , modals: function() {
      // Bootstrap modal is not very flexible by default - so lets extend it a bit and provide a littel wiggle room
      $('.modalButton').on({
        click: function(e) {
          e.preventDefault();

          var _this = $(this), options = {}, frameHeight;
          
          options.width = typeof _this.data('width') !== 'undefined' ? Math.floor(parseInt(_this.data('width'), 10)) : 500;
          options.height = typeof _this.data('height') !== 'undefined' ? Math.floor(parseInt(_this.data('height'), 10)) : 560;
          options.marginLeft = options.width / 2;
          options.marginTop = options.height / 2;

          frameHeight = typeof _this.data('height') !== 'undefined' ? options.height - 100 : '100%';
          frameScrolling = typeof _this.data('scrolling') !== 'undefined' ? _this.data('scrolling') - 100 : 'no';
          modalData.id = 'modal_' + Math.random().toString(36).substring(7);
          modalData.title = _this.attr('title');
          modalData.content = '<iframe src="' + $(this).attr('href') + '" scrolling="' + frameScrolling + '" width="100%" height="' + frameHeight + '" frameborder="0"></iframe>';

          modalMarkup = modalTemplate(modalData);
          $('body').append(modalMarkup);

          $('.modal-body').css({
            maxHeight: options.height - 150 + 'px'
            , overflow: 'hidden'
          });
          $('#' + modalData.id).css({
            width: options.width + 30 + 'px'
            , height: options.height + 'px'
            , marginLeft: -options.marginLeft + 'px'
            , marginTop: -options.marginTop + 'px'
          }).modal('show').on({
            shown: function() {
              $('#' + modalData.id).addClass('in');
              $('.close, .modal-backdrop').click(function() {
                $('#' + modalData.id).modal('close');
              });
            }
            , hidden: function() {
              $('.modal-backdrop, #' + modalData.id).remove();
            }
          });
          return false;
        }
      });
    }
  };
  return module;
});
