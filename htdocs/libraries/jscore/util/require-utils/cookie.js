define(function () {
	var module = {
		createCookie : function(name,value,days) {
			var expires, date;
			if (days) {
				date = new Date();
				date.setTime(date.getTime()+(days*24*60*60*1000));
				expires = "; expires="+date.toGMTString();
			}
			else {
				expires = "";
			}
			this.setCookie(name+"="+value+expires+"; path=/");
		},

		getCookie : function(name) {
			var nameEQ = name + "=",
					ca = this.getCookies().split(';'),
					i, c;

			for (i=0; i < ca.length; i++) {
				c = ca[i];

				while (c.charAt(0) === ' ') {
					c = c.substring(1,c.length);
				}

				if (c.indexOf(nameEQ) === 0) {
					return c.substring(nameEQ.length,c.length);
				}
			}
			return null;
		},

		eraseCookie : function(name) {
			this.createCookie(name,"",-1);
		},

		getCookies : function(){
			return document.cookie;
		},

		setCookie : function(cookie){
			document.cookie = cookie;
		}
	};

	return module;
});