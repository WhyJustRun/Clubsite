!function( $ ) {

  "use strict"

  $.ketchup

  .createErrorContainer(function(form, el) {
    var g = el.closest('.controls');

    if (g) {
      return $('<ul/>', {
             'class': 'help-block'
           }).appendTo(g);
    } else {
      return $('<ul/>', {
             'class': 'help-inline'
           }).insertAfter(g);
    }
  })

  .addErrorMessages(function(form, el, container, messages) {
    container.empty();
    for(i = 0; i < messages.length; i++) {
      $('<li/>', { text: messages[i] }).appendTo(container);
    }
  })

  .showErrorContainer(function(form, el, container) {
    container
      .closest('.control-group').addClass('error');
    container.show()
  })

  .hideErrorContainer(function(form, el, container) {
    container
      .closest('.control-group').removeClass('error')
    container.hide()
  })

  .helper('inputsWithNameNotSelf', function(form, el) {
    return this.inputsWithName(form, el).filter(function() {
      return ($(this) != el);
    });
  })

  $(function () {
    $('form[data-validate="ketchup"]').ketchup()
  })

}( window.jQuery );