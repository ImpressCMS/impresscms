/* Official CKEditor 4 ajax plugin - full source. */

/* global ActiveXObject */

( function() {
	CKEDITOR.plugins.add( 'ajax', { requires: 'xml' } );
	CKEDITOR.ajax = ( function() {
		function createXMLHttpRequest() {
			if ( !CKEDITOR.env.ie || location.protocol != 'file:' ) {
				try { return new XMLHttpRequest(); } catch(e) {}
			}
			try { return new ActiveXObject( 'Msxml2.XMLHTTP' ); } catch(e) {}
			try { return new ActiveXObject( 'Microsoft.XMLHTTP' ); } catch(e) {}
			return null;
		}
		function checkStatus( xhr ) {
			return ( xhr.readyState == 4 && ( ( xhr.status >= 200 && xhr.status < 300 ) || xhr.status == 304 || xhr.status === 0 || xhr.status == 1223 ) );
		}
		function getResponse( xhr, type ) {
			if ( !checkStatus( xhr ) ) { return null; }
			switch ( type ) {
				case 'text': return xhr.responseText;
				case 'xml': var xml = xhr.responseXML; return new CKEDITOR.xml( xml && xml.firstChild ? xml : xhr.responseText );
				case 'arraybuffer': return xhr.response;
				default: return null;
			}
		}
		function load( url, callback, responseType ) {
			var async = !!callback; var xhr = createXMLHttpRequest(); if ( !xhr ) return null;
			if ( async && responseType !== 'text' && responseType !== 'xml' ) { xhr.responseType = responseType; }
			xhr.open( 'GET', url, async );
			if ( async ) { xhr.onreadystatechange = function() { if ( xhr.readyState == 4 ) { callback( getResponse( xhr, responseType ) ); xhr = null; } }; }
			xhr.send( null );
			return async ? '' : getResponse( xhr, responseType );
		}
		function post( url, data, contentType, callback, responseType ) {
			var xhr = createXMLHttpRequest(); if ( !xhr ) return null; xhr.open( 'POST', url, true );
			xhr.onreadystatechange = function() { if ( xhr.readyState == 4 ) { if ( callback ) { callback( getResponse( xhr, responseType ) ); } xhr = null; } };
			xhr.setRequestHeader( 'Content-type', contentType || 'application/x-www-form-urlencoded; charset=UTF-8' );
			xhr.send( data );
		}
		return {
			load: function( url, callback, responseType ) { responseType = responseType || 'text'; return load( url, callback, responseType ); },
			post: function( url, data, contentType, callback ) { return post( url, data, contentType, callback, 'text' ); },
			loadXml: function( url, callback ) { return load( url, callback, 'xml' ); },
			loadText: function( url, callback ) { return load( url, callback, 'text' ); },
			loadBinary: function( url, callback ) { return load( url, callback, 'arraybuffer' ); }
		};
	} )();
} )();
