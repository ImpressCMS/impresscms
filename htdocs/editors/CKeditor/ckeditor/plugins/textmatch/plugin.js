/* Official CKEditor 4 textmatch plugin - full source. */

'use strict';

( function() {

	CKEDITOR.plugins.add( 'textmatch', {} );

	CKEDITOR.plugins.textMatch = {};

	CKEDITOR.plugins.textMatch.match = function( range, callback ) {
		var textAndOffset = CKEDITOR.plugins.textMatch.getTextAndOffset( range ),
			fillingCharSequence = CKEDITOR.dom.selection.FILLING_CHAR_SEQUENCE,
			fillingSequenceOffset = 0;

		if ( !textAndOffset ) { return; }
		if ( textAndOffset.text.indexOf( fillingCharSequence ) == 0 ) {
			fillingSequenceOffset = fillingCharSequence.length;
			textAndOffset.text = textAndOffset.text.replace( fillingCharSequence, '' );
			textAndOffset.offset -= fillingSequenceOffset;
		}

		var result = callback( textAndOffset.text, textAndOffset.offset );
		if ( !result ) { return null; }

		return {
			range: CKEDITOR.plugins.textMatch.getRangeInText( range, result.start, result.end + fillingSequenceOffset ),
			text: textAndOffset.text.slice( result.start, result.end )
		};
	};

	CKEDITOR.plugins.textMatch.getTextAndOffset = function( range ) {
		if ( !range.collapsed ) { return null; }

		var text = '', offset = 0,
			textNodes = CKEDITOR.plugins.textMatch.getAdjacentTextNodes( range ),
			nodeReached = false,
			elementIndex,
			startContainerIsText = ( range.startContainer.type != CKEDITOR.NODE_ELEMENT );

		if ( startContainerIsText ) {
			elementIndex = indexOf( textNodes, function( current ) { return range.startContainer.equals( current ); } );
		} else {
			elementIndex = range.startOffset - ( textNodes[ 0 ] ? textNodes[ 0 ].getIndex() : 0 );
		}

		var max = textNodes.length;
		for ( var i = 0; i < max; i += 1 ) {
			var currentNode = textNodes[ i ];
			text += currentNode.getText();
			if ( !nodeReached ) {
				if ( startContainerIsText ) {
					if ( i == elementIndex ) { nodeReached = true; offset += range.startOffset; }
					else { offset += currentNode.getText().length; }
				} else {
					if ( i == elementIndex ) { nodeReached = true; }
					if ( i > 0 ) { offset += textNodes[ i - 1 ].getText().length; }
					if ( max == elementIndex && i + 1 == max ) { offset += currentNode.getText().length; }
				}
			}
		}

		return { text: text, offset: offset };
	};

	CKEDITOR.plugins.textMatch.getRangeInText = function( range, start, end ) {
		var resultRange = new CKEDITOR.dom.range( range.root ),
			elements = CKEDITOR.plugins.textMatch.getAdjacentTextNodes( range ),
			startData = findElementAtOffset( elements, start ),
			endData = findElementAtOffset( elements, end );

		resultRange.setStart( startData.element, startData.offset );
		resultRange.setEnd( endData.element, endData.offset );
		return resultRange;
	};

	CKEDITOR.plugins.textMatch.getAdjacentTextNodes = function( range ) {
		if ( !range.collapsed ) { throw new Error( 'Range must be collapsed.' ); return []; }

		var collection = [], siblings, elementIndex, node, i;

		if ( range.startContainer.type != CKEDITOR.NODE_ELEMENT ) {
			siblings = range.startContainer.getParent().getChildren();
			elementIndex = range.startContainer.getIndex();
		} else {
			siblings = range.startContainer.getChildren();
			elementIndex = range.startOffset;
		}

		i = elementIndex;
		while ( node = siblings.getItem( --i ) ) {
			if ( node.type == CKEDITOR.NODE_TEXT ) { collection.unshift( node ); }
			else { break; }
		}

		i = elementIndex;
		while ( node = siblings.getItem( i++ ) ) {
			if ( node.type == CKEDITOR.NODE_TEXT ) { collection.push( node ); }
			else { break; }
		}

		return collection;
	};

	function findElementAtOffset( elements, offset ) {
		var max = elements.length, currentOffset = 0;
		for ( var i = 0; i < max; i += 1 ) {
			var current = elements[ i ];
			if ( offset >= currentOffset && currentOffset + current.getText().length >= offset ) {
				return { element: current, offset: offset - currentOffset };
			}
			currentOffset += current.getText().length;
		}
		return null;
	}

	function indexOf( arr, checker ) {
		for ( var i = 0; i < arr.length; i++ ) {
			if ( checker( arr[ i ] ) ) { return i; }
		}
		return -1;
	}
} )();
