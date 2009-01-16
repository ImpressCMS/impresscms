/*
    dw_sizerdx.js version date: Feb 2006
    requires dw_cookies.js
    
    Feb 2006 Revisions: add queryName property (fix major oops for PHP compatibility)
    all global modifier to trim method re (another oops fixed)
    
    Mar 2005 Revisions: added setDefaults method, 
    set method now accepts array of selectors,
    reset fn now sets adjustList el's font-sizes to empty string,
    fixed bugs in dw_getElementsBySelector fn
*/

/*************************************************************************
  This code is from Dynamic Web Coding at dyn-web.com
  Copyright 2004-6 by Sharon Paine 
  See Terms of Use at www.dyn-web.com/bus/terms.html
  regarding conditions under which you may use this code.
  This notice must be retained in the code as is!
*************************************************************************/

var dw_fontSizerDX = {
    sizeUnit:       "px",
    defaultSize:    10,
    maxSize:        16,
    minSize:        8,
    queryName:      "dw_fsz",   // name to check query string for when passing size in URL
    queryNum:       true,       // check query string for number only (eg. index.html?18 )
    adjustList:     [],         // set method populates

    setDefaults: function(unit, dflt, mn, mx, sels) {
        this.sizeUnit = unit;       this.defaultSize = dflt;
        this.maxSize = mx;          this.minSize = mn;
        if (sels) this.set(dflt, mn, mx, sels);
    },

    set: function (dflt, mn, mx, sels) { 
        var ln = this.adjustList.length;        
        for (var i=0; sels[i]; i++) {
            this.adjustList[ln+i] = [];
            this.adjustList[ln+i]["sel"]  = sels[i];
            this.adjustList[ln+i]["dflt"] = dflt;
            this.adjustList[ln+i]["min"]   = mn || this.minSize;
            this.adjustList[ln+i]["max"]   = mx || this.maxSize;
            // hold ratio of this selector's default size to this.defaultSize for calcs in adjust fn 
            this.adjustList[ln+i]["ratio"] = this.adjustList[ln+i]["dflt"] / this.defaultSize;
        }
    },

    init: function() {
        if ( !document.getElementById || !document.getElementsByTagName ) return;
        var size, sizerEl, i;
        // check query string and cookie for fontSize
        // check size (in case default unit changed or size passed in url out of range)
        size = getValueFromQueryString( this.queryName, this.queryNum );
        if ( isNaN( parseFloat(size) ) || size > this.maxSize || size < this.minSize ) {
            size = getCookie("fontSize");
            if ( isNaN( parseFloat(size) ) || size > this.maxSize || size < this.minSize ) {
                size = this.defaultSize;
            }
        } 
        this.curSize = this.defaultSize;  // create curSize property to use in calculations 
        sizerEl = document.getElementById('sizer');
        if (sizerEl) sizerEl.style.display = "block";
        // if neither set nor setDefaults populates adjustList, apply sizes to body and td's
        if (this.adjustList.length == 0) {
            this.setDefaults( this.sizeUnit, this.defaultSize, this.minSize, this.maxSize, ['body', 'td'] );
        }
        if ( size != this.defaultSize ) this.adjust( size - this.defaultSize );
    },

    adjust: function(n) {
        if ( !this.curSize ) return; 
        var alist, size, list, i, j;
        // check against max/minSize
        if ( n > 0 ) {
            if ( this.curSize + n > this.maxSize ) n = this.maxSize - this.curSize;
        } else if ( n < 0 ) {
            if ( this.curSize + n < this.minSize ) n = this.minSize - this.curSize;
        }
        if ( n == 0 ) return;
        this.curSize += n;
        // loop through adjustList, calculating size, checking max/min
        alist = this.adjustList;
        for (i=0; alist[i]; i++) {
            size = this.curSize * alist[i]['ratio']; // maintain proportion 
            size = Math.max(alist[i]['min'], size); size = Math.min(alist[i]['max'], size);
            list = dw_getElementsBySelector( alist[i]['sel'] );
            for (j=0; list[j]; j++) { list[j].style.fontSize = size + this.sizeUnit; }
        }
        setCookie( "fontSize", this.curSize, 180, "/" );
    },

    reset: function() {
        if ( !this.curSize ) return; 
        var alist = this.adjustList, list, i, j;
        for (i=0; alist[i]; i++) {
            list = dw_getElementsBySelector( alist[i]['sel'] );
            for (j=0; list[j]; j++) { 
                // Reset adjustList elements to their default sizes
                //list[j].style.fontSize = alist[i]['dflt'] + this.sizeUnit;
                list[j].style.fontSize = '';  // restores original font size
            } 
        }
        this.curSize = this.defaultSize;
        deleteCookie("fontSize", "/");
    }

};

// resource: simon.incutio.com/archive/2003/03/25/getElementsBySelector
function dw_getElementsBySelector(selector) {
    if (!document.getElementsByTagName) return [];
    var nodeList = [document], tokens, bits, list, col, els, i, j, k;
    selector = selector.normalize();
    tokens = selector.split(' ');
    for (i=0; tokens[i]; i++) {
        if ( tokens[i].indexOf('#') != -1 ) {  // id
            bits = tokens[i].split('#'); 
            var el = document.getElementById( bits[1] );
            if (!el) return []; 
            if ( bits[0] ) {  // check tag
                if ( el.tagName.toLowerCase() != bits[0].toLowerCase() ) return [];
            }
            for (j=0; nodeList[j]; j++) {  // check containment
                if ( nodeList[j] == document || dw_contained(el, nodeList[j]) ) 
                    nodeList = [el];
                else return [];
            }
        } else if ( tokens[i].indexOf('.') != -1 ) {  // class
            bits = tokens[i].split('.'); col = [];
            for (j=0; nodeList[j]; j++) {
                els = dw_getElementsByClassName( bits[1], bits[0], nodeList[j] );
                for (k=0; els[k]; k++) { col[col.length] = els[k]; }
            }
            nodeList = [];
            for (j=0; col[j]; j++) { nodeList.push(col[j]); }
        } else {  // element 
            els = []; 
            for (j = 0; nodeList[j]; j++) {
                list = nodeList[j].getElementsByTagName(tokens[i]);
                for (k = 0; list[k]; k++) { els.push(list[k]); }
            }
            nodeList = els;
        }
    }
    return nodeList;
};

function dw_getElementsByClassName(sClass, sTag, oCont) {
    var result = [], list, i;
    var re = new RegExp("\\b" + sClass + "\\b", "i");
    oCont = oCont? oCont: document;
    if ( document.getElementsByTagName ) {
        if ( !sTag || sTag == "*" ) {
            list = oCont.all? oCont.all: oCont.getElementsByTagName("*");
        } else {
            list = oCont.getElementsByTagName(sTag);
        }
        for (i=0; list[i]; i++) 
            if ( re.test( list[i].className ) ) result.push( list[i] );
    }
    return result;
};

// 2nd arg: return whole query string if varName not found?
// (compatible with previous version, which just checked for number after ?)
function getValueFromQueryString(varName, bReturn) {
    var val = "";
    if (window.location.search) {
        var qStr = window.location.search.slice(1);
        var ar = qStr.split("&");
        var get = [], ar2;
        // portion before = becomes index (like $_GET)
        for (var i=0; ar[i]; i++) {
            if ( ar[i].indexOf("=") != -1 ) {
                ar2 = ar[i].split("=");
                get[ ar2[0] ] = ar2[1];
            }
        }
        val = get[varName];
        // if varName is not passed to this function or not found, return entire query string ?      
        if ( !val && bReturn ) {
            val = qStr;
        }
    }
    return val;
};

// returns true of oNode is contained by oCont (container)
function dw_contained(oNode, oCont) {
    if (!oNode) return; // in case alt-tab away while hovering (prevent error)
    while ( oNode = oNode.parentNode ) if ( oNode == oCont ) return true;
    return false;
};

if (!Array.prototype.push) {  // ie5.0
	Array.prototype.push =  function() {
		for (var i=0; arguments[i]; i++) this[this.length] = arguments[i];
		return this[this.length-1]; // return last value appended
	}
};

String.prototype.normalize = function() {
	var re = /\s\s+/g;
	return this.trim().replace(re, " ");
};

String.prototype.trim = function() {
	var re = /^\s+|\s+$/g;
	return this.replace(re, "");
};
