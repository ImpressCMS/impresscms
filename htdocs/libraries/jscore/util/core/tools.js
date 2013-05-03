define(['jquery'], function ($) {
  var module = {
    loadCSS:function(url, id) {

      if(typeof id !== 'undefined') {
        if($('#' + id).length) {
          return;
        }
      }

      var link = document.createElement("link");
      link.type = "text/css";
      link.rel = "stylesheet";
      link.href = url;
      link.id = typeof id !== 'undefined' ? id : null;
      document.getElementsByTagName("head")[0].appendChild(link);
    }

    , serializeJSON: function(ele) {
      var json = {}, result;
      $.map(ele.serializeArray(), function(n, i){
        result = n['value'] === '' ? null : n['value'];
        json[n['name']] = result;
      });
      return json;
    }

    , serializeArgs: function(args) {
      var json = {}, result, i;
      args = args.split('&');
      for(i=0;i<args.length; i++) {
        result = args[i].split('=');
        json[result[0]] = result[1];
      }
      return json;
    }
  };
  return module;
});
