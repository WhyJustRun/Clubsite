$(document).ready(function() {
    $.editable.addInputType('wysiwyg', {
        element : function(settings, original) {
            var textarea = $('<textarea />');
            if (settings.rows) {
                textarea.attr('rows', settings.rows);
            } else {
                textarea.height(settings.height);
            }
            if (settings.cols) {
                textarea.attr('cols', settings.cols);
            } else {
                textarea.width(settings.width);
            }
            $(this).append(textarea);
            return(textarea);
        },
        plugin : function(settings, original) {
            orienteerAppWYSIWYG($('textarea', this));
        }
    });
    
    $('.content-block').editable('/contentBlocks/edit', { 
        type      : 'wysiwyg',
        cancel    : 'Cancel',
        submit    : 'Save',
        tooltip   : 'Click to edit…',
        onblur    : 'ignore',
    });
        
    $('.page-resource').editable('/pages/edit', { 
        type      : 'wysiwyg',
        cancel    : 'Cancel',
        submit    : 'Save',
        tooltip   : 'Click to edit…',
        onblur    : 'ignore',
    });
    
    $('.page-resource-title').editable('/pages/edit', { 
        type      : 'text',
        cancel    : 'Cancel',
        submit    : 'Save',
        name      : 'name',
        tooltip   : 'Click to edit…',
        onblur    : 'ignore',
    });
    
    $('.page-resource, .page-resource-title, .content-block').addClass('editable');
});