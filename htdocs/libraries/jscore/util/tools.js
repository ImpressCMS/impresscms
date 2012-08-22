define(['jquery'], function ($) {
	var module = {
		loadCSS:function(url, id) {
			
			if(typeof id !== 'undefined') {
				if($(id).length) {
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

	};
	return module;
});
