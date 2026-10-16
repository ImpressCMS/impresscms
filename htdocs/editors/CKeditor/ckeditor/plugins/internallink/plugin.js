(function() {
    CKEDITOR.plugins.add('internallink', {
        icons: 'link',
        hidpi: true,
        init: function(editor) {
            var commandName = 'openInternalLinkDialog';

            editor.addCommand(commandName, new CKEDITOR.dialogCommand('internallinkDialog'));

            editor.ui.addButton('InternalLink', {
                label: 'Internal Link',
                command: commandName,
                toolbar: 'links'
            });

            CKEDITOR.dialog.add('internallinkDialog', this.path + 'dialogs/internallink.js');
        }
    });
})();

