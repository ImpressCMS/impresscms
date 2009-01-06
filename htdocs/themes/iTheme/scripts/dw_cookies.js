/*********************************************************************************
  dw_cookies.js - cookie functions for www.dyn-web.com
  Recycled from various sources 
**********************************************************************************/

// Modified from Bill Dortch's Cookie Functions (hidaho.com) 
// (found in JavaScript Bible)
function setCookie(name,value,days,path,domain,secure) {
  var expires, date;
  if (typeof days == "number") {
    date = new Date();
    date.setTime( date.getTime() + (days*24*60*60*1000) );
		expires = date.toGMTString();
  }
  document.cookie = name + "=" + escape(value) +
    ((expires) ? "; expires=" + expires : "") +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    ((secure) ? "; secure" : "");
}

// Modified from Jesse Chisholm or Scott Andrew Lepera ?
// (found at both www.dansteinman.com/dynapi/ and www.scottandrew.com/junkyard/js/)
function getCookie(name) {
  var nameq = name + "=";
  var c_ar = document.cookie.split(';');
  for (var i=0; i<c_ar.length; i++) {
    var c = c_ar[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameq) == 0) return unescape( c.substring(nameq.length, c.length) );
  }
  return null;
}

// from Bill Dortch's Cookie Functions (hidaho.com) 
function deleteCookie(name,path,domain) {
  if (getCookie(name)) {
    document.cookie = name + "=" +
      ((path) ? "; path=" + path : "") +
      ((domain) ? "; domain=" + domain : "") +
      "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }
}

