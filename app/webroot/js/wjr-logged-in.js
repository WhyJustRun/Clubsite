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
        submit : function (settings, original) {
            orienteerAppWYSIWYGUpdateTextareas();
        },
        plugin : function(settings, original) {
            // Hacktastic. This fixes an issue with the scroll position jumping on chrome when opening the textarea..
            var textarea = $('textarea', this);
            setTimeout(function() {
                orienteerAppWYSIWYG(textarea);
            }, 1);
        }
    });

    var editableContent = $('.page-resource, .page-resource-title, .content-block');
    editableContent.addClass('editable');
    // Allow link clicks to pass through without opening editor. NOTE: doesn't fix the case when the content is edited.. seems like it would be really hacky to get that working because jeditable doesn't have enough callback hooks.
    editableContent.find('a').click(function(e) {
        e.stopPropagation();
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
});
