/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
 */

/*
IMPRESSCMS CKEDITOR CONFIG - William Hall (Mr. Theme) mrtheme@impresscms.org
*/

CKEDITOR.editorConfig = function(config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.resize_minWidth = 450;
	config.skin = 'bootstrapck';
	/*
	 * // Protect PHP code tags (<?...?>) so CKEditor will not break them when //
	 * switching from Source to WYSIWYG. // Uncommenting this line doesn't mean
	 * the user will not be able to type PHP // code in the source. This kind of
	 * prevention must be done in the server // side
	 */
	config.protectedSource.push(/<\?[\s\S]*?\?>/g); // PHP Code

	/*
	 * Basic Config (ANONYMOUS TOOLBAR)
	 */
	config.toolbar_Basic = [ [ 'Format', '-', 'Bold', 'Italic', '-',
			'NumberedList', 'BulletedList', '-', 'Link', 'Unlink' ] ];

	/*
	 * Medium Config (REGISTERED TOOLBAR)
	 */
	config.toolbar_Normal = [
			[ 'Source' ],
			[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
					'SpellChecker', 'Scayt' ],
			[ 'Undo', 'Redo', 'Find', 'Replace', '-', 'SelectAll',
					'RemoveFormat' ],
			[ 'Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar' ],
			'/',
			[ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript',
					'Superscript' ],
			[ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',
					'Blockquote' ],
			[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
			[ 'Link', 'Unlink', 'Anchor' ], '/',
			[ 'Format', 'Font', 'FontSize' ], [ 'TextColor', 'BGColor' ], ];

	/*
	 * Full Config (ADMIN TOOLBAR)
	 */
	config.toolbar_Full = [
			[ 'Source' ],
			[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-',
					'SpellChecker', 'Scayt' ],
			[ 'Undo', 'Redo', 'Find', 'Replace', '-', 'SelectAll',
					'RemoveFormat' ],
			[ 'Image', 'oembed', 'Flash', 'Table', 'HorizontalRule', 'Smiley',
					'SpecialChar' ],
			'/',
			[ 'Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript',
					'Superscript' ],
			[ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent',
					'Blockquote' ],
			[ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
			[ 'Link', 'Unlink', 'Anchor' ], '/',
			[ 'Format', 'Font', 'FontSize' ], [ 'TextColor', 'BGColor' ],
			[ 'Maximize', 'ShowBlocks' ], ];

	config.smiley_path = CKEDITOR.basePath + '../../../uploads/';

	config.smiley_images = [ 'smil3dbd4d4e4c4f2.gif', 'smil3dbd4d6422f04.gif',
			'smil3dbd4d75edb5e.gif', 'smil3dbd4d8676346.gif',
			'smil3dbd4d99c6eaa.gif', 'smil3dbd4daabd491.gif',
			'smil3dbd4dbc14f3f.gif', 'smil3dbd4dcd7b9f4.gif',
			'smil3dbd4ddd6835f.gif', 'smil3dbd4df1944ee.gif',
			'smil3dbd4e02c5440.gif', 'smil3dbd4e1748cc9.gif',
			'smil3dbd4e29bbcc7.gif', 'smil3dbd4e398ff7b.gif',
			'smil3dbd4e4c2e742.gif', 'smil3dbd4e5e7563a.gif',
			'smil3dbd4e7853679.gif' ];

	config.smiley_descriptions = [ ':-D', ':-)', ':-(', ':-o', ':-?', '8-)',
			':lol:', ':-x', ':-p', ':oops:', ':cry:', ':evil:', ':roll:',
			';-)', ':pint:', ':hammer:', ':idea:' ];

};
