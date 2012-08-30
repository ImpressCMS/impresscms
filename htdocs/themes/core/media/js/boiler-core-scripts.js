(function ($) {
  $(document).ready(function() {
  // Begin domReady

    $('a[rel="external"]').click(function(){
      $(this).attr('target', '_blank');
    });

    $("ul.sf-menu").superfish();

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

  // End DomReady
  });
}(jQuery));