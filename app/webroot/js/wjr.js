$(function() {
    $("a.lightbox").fancybox();

    // Add required to the recaptcha field. This has to be done after page load as the element is created dynamically, and before ketchup parses the data-validate tags
    $('#recaptcha_response_field').attr('data-validate', 'validate(required)');
    $('time.timeago').each(function(i, e) {
        var time = moment($(e).attr('datetime'));
        $(e).html(time.fromNow());
    });

    $('.date-picker').datetimepicker({ pickTime: false });
    cakebootstrap();
    errorstrap();
    $('input, textarea').placeholder();

    orienteerAppCheckKetchupFormsAreValidOnSubmit();

    $('.oa-wysiwyg').each(function(idx, element) {
        // defined in wjr-text-editor.TEXT_EDITOR.js
        orienteerAppWYSIWYG(element);
    });

    $("[data-toggle='tooltip']").tooltip();

    // Set browser support (we require browsers to completely support CORS)
    $.reject({
        reject: {
            msie5: true, msie6: true, msie7: true,
            msie8: true, msie9: true,
            firefox1: true, firefox2: true, firefox3: true,
            opera7: true, opera8: true, opera9: true,
            opera10: true, opera11: true,
            safari2: true, safari3: true
        },
        imagePath: '/img/jreject/',
        display: ["chrome", "firefox", "safari", "gcf"],
        header: 'Did you know your web browser is out of date?',
        paragraph1: 'Your browser is not supported by this website. We recommend you upgrade your browser.'
    });

    ko.bindingHandlers.tooltip = {
        init: function(element, valueAccessor) {
            var local = ko.utils.unwrapObservable(valueAccessor()),
                options = { container: 'body' };

            ko.utils.extend(options, ko.bindingHandlers.tooltip.options);
            ko.utils.extend(options, local);

            $(element).tooltip(options);

            ko.utils.domNodeDisposal.addDisposeCallback(element, function() {
                $(element).tooltip("destroy");
            });
        },
        options: {
            placement: "right",
            trigger: "click"
        }
    };
});

// Callback should take a person object with id, name. Callback can also be called with null (no person selected)
// Maintain input will keep the selected user's name in the input after the input loses focus. "allowNew" will keep the input populated even if there is no user in the system with the given name. "createNew" will add an option to create a new user with the given name.
function orienteerAppPersonPicker(selector, options, callback) {
    var defaults = {
        'allowNew': false,
        'allowFake': true,
        'createNew': false,
    };
    options = options || {};
    options = $.extend({}, defaults, options);
    displayName = null;

    $(selector).typeahead({
        source: function(typeahead, query) {
                    $.ajax({
                        url: "/users/index.json?term=" + query + "&allowFake=" + (options['allowFake'] ? 'true' : 'false'),
                        success: function(data) {
                            if (options['createNew']) {
                                data.push({
                                    position: "bottom",
                                    name: query,
                                    identifiableName: 'Create New User (' + query + ')'
                                        });
                                    }
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

function orienteerAppCheckKetchupFormsAreValidOnSubmit() {
    $('[data-validate="ketchup"]').each(function(index, element) {
        jqe = $(element);
        jqe.submit(function(eventObject) {
            jqe.ketchup();
            return jqe.ketchup('isValid');
        });
    });
}
