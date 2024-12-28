/**
 * @license Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

	// Different toolbars, based on the user's group

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

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

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

	config.allowedContent = true;
	config.versionCheck = false;
};
