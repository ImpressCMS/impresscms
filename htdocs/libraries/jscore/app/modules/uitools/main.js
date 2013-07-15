/* global icms: true */
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
define([
  'jquery'
  , 'core'
  , 'i18n!nls/labels'
  , 'hb!modules/uiTools/templates/modal'
  , 'plugins/password/passfield'
  , 'css!plugins/password/passfield.css'
  , 'bootstrap/popover'
  , 'bootstrap/modal'
]
, function($, Core, labels, modalTemplate) {
  var modalMarkup = null
  , modalData = {
    id: null
    , title: null
    , content: null
    , text: labels
  }
  , module = {
    initialize: function() {
      if(typeof window.hasBootstrap === 'undefined' || window.hasBootstrap === false) {
        require(['css!app/modules/uitools/uitools.css']);
      }
      $(document).ready(function() {
        module.ui();
        module.passwords();
        module.helptip();
        module.checkAll();
        module.modals();
        module.mobileMenus();
        Core.mediator.publish('uitoolsReady');
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

    , passwords: function() {
      $('input[type=password]').passField({
        'showTip': false
        , 'showWarn': false
        , 'showGenerate' : false
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
          , html: true
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

          var _this = $(this), options = {}, frameHeight, frameScrolling;

          options.width = typeof _this.data('width') !== 'undefined' ? Math.floor(parseInt(_this.data('width'), 10)) : 500;
          options.height = typeof _this.data('height') !== 'undefined' ? Math.floor(parseInt(_this.data('height'), 10)) : 560;
          options.marginLeft = options.width / 2;
          options.marginTop = options.height / 2;

          frameHeight = typeof _this.data('height') !== 'undefined' ? options.height - 100 : '100%';
          frameScrolling = typeof _this.data('scrolling') !== 'undefined' ? _this.data('scrolling') : 'no';
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
                $('#' + modalData.id).modal('hide');
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
    , mobileMenus: function() {
      // Create the dropdown base
      var toSel = $('.toSel')
      , dash = ['', '', '&ndash;&ndash;', '&nbsp;&nbsp;&ndash;&ndash;', '&nbsp;&nbsp;&nbsp;&nbsp;&ndash;&ndash;'];

      toSel.each(function() {
        var menu = $(this)
        , label = typeof(menu.data('label')) !== 'undefined' ? menu.data('label') : 'Navigation'
        , sel = $('<select class="mobileMenu"><option value="false" text="Navigation">'+label+'</option></select>');

        if(!menu.hasClass('rendered')) {
          menu.find('a').each(function() {
            var el = $(this)
            , depth = el.parents('ul').size()
            , oLabel = $('<span />')
            , text = typeof(el.data('prefix')) !== 'undefined' ? el.data('prefix') + el.text() : dash[depth] + el.text();

            if(typeof(el.data('hidden') !== 'undefined') && el.data('hidden') !== true) {
              $('<option />', {
                'value': el.attr('href').match(/void/) ? false : el.attr('href'),
                'text': oLabel.html(text).text(),
                'selected' : el.hasClass('active') || el.parent().hasClass('active') ? 'selected' : false
              }).attr('class', el.attr('class')).appendTo(sel);
            }
          });
          menu.addClass('rendered').after(sel);
        }
      });

      $('.mobileMenu').on({
        change: function() {
          var _this = $(this).find('option:selected');
          if(_this.val() !== 'false' || _this.val() ==='#' || !_this.val.match(/void/)) {
            window.location = _this.val();
          }
        }
      });
    }
  };
  return module;
});