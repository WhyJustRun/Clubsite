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
    $('input, textarea').placeholder();
    
    
    
    // HiDPI resolution images
    if(window.devicePixelRatio > 1) {
	    $('img[data-2x-src]').each(function (index, img) {
	    	function load2xImage() {
		    	if(img.getAttribute('width') != undefined || img.getAttribute('height') != undefined) {
		    		img.setAttribute('src', img.getAttribute('data-2x-src'));
		    	} else if(img.complete) {
		    		img.setAttribute('width', img.offsetWidth);
		    		img.setAttribute('height', img.offsetHeight);
			    	img.setAttribute('src', img.getAttribute('data-2x-src'));
		    	} else {
			    	setTimeout(load2xImage, 5);
		    	}
			}
	    	
	    	load2xImage();
	    });
    }
});


// Callback should take a person object with id, name. Callback can also be called with null (no person selected)
// Maintain input will keep the selected user's name in the input after the input loses focus. Allow new will keep the input populated even if there is no user in the system with the given name.
function orienteerAppPersonPicker(selector, options, callback) {
    options = options || {};
    options['allowNew'] = options['allowNew'] || false;
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
        if(!options['allowNew']) {
            if($(selector).val() == "") {
                callback(null)
            } else if($(selector).val() != displayName){
                $(selector).val(displayName);
            }
        }
    });
}