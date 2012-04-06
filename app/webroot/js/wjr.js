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
    jQuery.timeago.settings.allowFuture = true;
    $('time.timeago').timeago();
    
    $('.date-picker').datepicker();
});


// Callback should take a person object with id, name. Callback can also be called with null (no person selected)
function orienteerAppPersonPicker(selector, options, callback) {
    options = options || {};
    displayName = null;
    
    $(selector).typeahead({
        source: function(typeahead, query) {
            $.ajax({
                url: "/users/index.json?term=" + query,
                success: function(data) {
                    typeahead.process(data);
                }
            });
        },
        onselect: function(person) {
            callback(person)
            if(options['maintainInput']) {
                displayName = person.name;
                $(selector).val(displayName);
            }
            $(selector).blur();
        },
        property: "identifiableName"
    });
    
    $(selector).blur(function() {
        if($(selector).val() == "") {
            callback(null)
        } else if($(selector).val() != displayName){
            $(selector).val(displayName);
        }
    });
}