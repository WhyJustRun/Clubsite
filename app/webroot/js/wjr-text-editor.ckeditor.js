function orienteerAppWYSIWYGUpdateTextareas() {
    _.invoke(CKEDITOR.instances, 'updateElement');
}

function orienteerAppWYSIWYG(element) {
    CKEDITOR.replace($(element).attr('name'));
}
