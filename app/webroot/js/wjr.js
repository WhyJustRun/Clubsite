$(function() {
	$("a.lightbox").fancybox();
	
	$.ketchup
    .createErrorContainer(function(form, el) {
      return $('<ul/>', {
               'class': 'ketchup-custom'
             }).insertAfter(el);
    })
    .addErrorMessages(function(form, el, container, messages) {
      container.html('');
    
      for(i = 0; i < messages.length; i++) {
        $('<li/>', {
          text: messages[i]
        }).appendTo(container);
      }
    })
    .showErrorContainer(function(form, el, container) {
      container.slideDown('fast');
    })
    .hideErrorContainer(function(form, el, container) {
      container.slideUp('fast');
    });
    
    // Add required to the recaptcha field. This has to be done after page load as the element is created dynamically, and before ketchup parses the data-validate tags
	$('#recaptcha_response_field').attr('data-validate', 'validate(required)');
	$('form').ketchup();
	jQuery.timeago.settings.allowFuture = true;
	$('time.timeago').timeago();
});