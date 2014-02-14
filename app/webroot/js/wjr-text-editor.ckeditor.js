function orienteerAppWYSIWYGUpdateTextareas() {
    _.invoke(CKEDITOR.instances, 'updateElement');
}

// hack. does async loading of the wysiwyg editor
function orienteerAppWYSIWYG(element) {
    CKEDITOR.replace($(element).attr('name'));
}
