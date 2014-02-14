function orienteerAppWYSIWYGUpdateTextareas() {
}

// hack. does async loading of the wysiwyg editor
function orienteerAppWYSIWYG(element) {
    // If we are not on a jeditable textarea, use the fixed toolbar. There are bugs with jeditable and fixed toolbar opening as of Feb 2014.
    if ($(element).parent().parent().hasClass('editable')) {
        $(element).redactor();
    } else {
        $(element).redactor({ toolbarFixed: true, toolbarFixedBox: true });
    }
}
