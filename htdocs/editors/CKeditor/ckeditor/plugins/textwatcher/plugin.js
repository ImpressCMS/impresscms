/* Official CKEditor 4 textwatcher plugin - full source. */

'use strict';

( function() {
	CKEDITOR.plugins.add( 'textwatcher', {} );

	function TextWatcher( editor, callback, throttle ) {
		this.editor = editor;
		this.lastMatched = null;
		this.ignoreNext = false;
		this.callback = callback;
		this.ignoredKeys = [ 16,17,18,91,35,36,37,38,39,40,33,34 ];
		this._listeners = [];
		this.throttle = throttle || 0;
		this._buffer = CKEDITOR.tools.throttle( this.throttle, testTextMatch, this );

		function testTextMatch( selectionRange ) {
			var matched = this.callback( selectionRange );
			if ( matched ) {
				if ( matched.text == this.lastMatched ) { return; }
				this.lastMatched = matched.text;
				this.fire( 'matched', matched );
			} else if ( this.lastMatched ) {
				this.unmatch();
			}
		}
	}

	TextWatcher.prototype = {
		attach: function() {
			var editor = this.editor;
			this._listeners.push( editor.on( 'contentDom', onContentDom, this ) );
			this._listeners.push( editor.on( 'blur', unmatch, this ) );
			this._listeners.push( editor.on( 'beforeModeUnload', unmatch, this ) );
			this._listeners.push( editor.on( 'setData', unmatch, this ) );
			this._listeners.push( editor.on( 'afterCommandExec', unmatch, this ) );
			if ( editor.editable() ) { onContentDom.call( this ); }
			return this;

			function onContentDom() {
				var editable = editor.editable();
				this._listeners.push( editable.attachListener( editable, 'keyup', check, this ) );
			}
			function check( evt ) { this.check( evt ); }
			function unmatch() { this.unmatch(); }
		},
		check: function( evt ) {
			if ( this.ignoreNext ) { this.ignoreNext = false; return; }
			if ( evt && evt.name == 'keyup' && ( CKEDITOR.tools.array.indexOf( this.ignoredKeys, evt.data.getKey() ) != -1 ) ) { return; }
			var sel = this.editor.getSelection(); if ( !sel ) { return; }
			var selectionRange = sel.getRanges()[ 0 ]; if ( !selectionRange ) { return; }
			this._buffer.input( selectionRange );
		},
		consumeNext: function() { this.ignoreNext = true; return this; },
		unmatch: function() { this.lastMatched = null; this.fire( 'unmatched' ); return this; },
		destroy: function() { CKEDITOR.tools.array.forEach( this._listeners, function( obj ) { obj.removeListener(); } ); this._listeners = []; }
	};

	CKEDITOR.event.implementOn( TextWatcher.prototype );
	CKEDITOR.plugins.textWatcher = TextWatcher;
} )();
