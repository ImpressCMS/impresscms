/* Official CKEditor 4 xml plugin - full source. */

( function() {
	/* global ActiveXObject */
	CKEDITOR.plugins.add( 'xml', {} );

	CKEDITOR.xml = function( xmlObjectOrData ) {
		var baseXml = null;
		if ( typeof xmlObjectOrData == 'object' ) baseXml = xmlObjectOrData;
		else {
			var data = ( xmlObjectOrData || '' ).replace( /&nbsp;/g, '\xA0' );
			if ( 'ActiveXObject' in window ) {
				try { baseXml = new ActiveXObject( 'MSXML2.DOMDocument' ); } catch ( e ) { try { baseXml = new ActiveXObject( 'Microsoft.XmlDom' ); } catch ( err ) {} }
				if ( baseXml ) { baseXml.async = false; baseXml.resolveExternals = false; baseXml.validateOnParse = false; baseXml.loadXML( data ); }
			} else if ( window.DOMParser ) {
				baseXml = ( new DOMParser() ).parseFromString( data, 'text/xml' );
			}
		}
		this.baseXml = baseXml;
	};

	CKEDITOR.xml.prototype = {
		selectSingleNode: function( xpath, contextNode ) {
			var baseXml = this.baseXml;
			if ( contextNode || ( contextNode = baseXml ) ) {
				if ( 'selectSingleNode' in contextNode ) return contextNode.selectSingleNode( xpath );
				else if ( baseXml.evaluate ) { var result = baseXml.evaluate( xpath, contextNode, null, 9, null ); return ( result && result.singleNodeValue ) || null; }
			}
			return null;
		},
		selectNodes: function( xpath, contextNode ) {
			var baseXml = this.baseXml, nodes = [];
			if ( contextNode || ( contextNode = baseXml ) ) {
				if ( 'selectNodes' in contextNode ) return contextNode.selectNodes( xpath );
				else if ( baseXml.evaluate ) { var result = baseXml.evaluate( xpath, contextNode, null, 5, null ); if ( result ) { var node; while ( ( node = result.iterateNext() ) ) nodes.push( node ); } }
			}
			return nodes;
		},
		getInnerXml: function( xpath, contextNode ) {
			var node = this.selectSingleNode( xpath, contextNode ), xml = [];
			if ( node ) { node = node.firstChild; while ( node ) { if ( node.xml ) xml.push( node.xml ); else if ( window.XMLSerializer ) xml.push( ( new XMLSerializer() ).serializeToString( node ) ); node = node.nextSibling; } }
			return xml.length ? xml.join( '' ) : null;
		}
	};
} )();
