!function( $ ) {

    "use strict"
    
        $.ketchup
        .validation('url_or_empty', 'Must be a valid URL (include http://)',
            function (form, el, value) {
                return (!value || this.isUrl(value));
            }, function (form, e1) {}
        )
        .validation('date', 'Must be a valid date (format: yyyy-mm-dd)',
            function (form, el, value) {
                if (value === "") {
                    return true;
                } else {
                    var regex = /^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/;
                    return regex.test(value);
                }
            }, function (form, e1) {}
        )
        .validation('time', 'Must be a valid 24 hour time (format: hh:mm)',
            function (form, el, value) {
                if (value === "") {
                    return true;
                } else {
                   var regex = /^([01]?[0-9]|2[0-3]):[0-5][0-9]$/;
                   return regex.test(value);
                }
            }, function (form, e1) {}
        )
        .validation('date_after', 'End time must be after the start time',
            function (form, el, value, startDateEl, startTimeEl, endDateEl, endTimeEl) {
                var startDate = $("#" + startDateEl).val();
                var startTime = $("#" + startTimeEl).val();
                var endDate = $("#" + endDateEl).val();
                var endTime = $("#" + endTimeEl).val();
                if (endDate == "") {
                    return true;
                }
                return moment(startDate + " " + startTime).isBefore(endDate + " " + endTime);
            }, function (form, e1) {
            }
        )
        .validation('requires', 'Requires {arg2} to be entered as well',
            function (form, el, value, requiredEl, text) {
                return value == "" || ($("#" + requiredEl).val() != "");
            }, function (form, e1) {}
        )
        .createErrorContainer(function(form, el) {
            var g = el.parent();

            return $('<ul/>', {
                'class': 'help-block'
            }).appendTo(g);
        })

    .addErrorMessages(function(form, el, container, messages) {
        container.empty();
        for(i = 0; i < messages.length; i++) {
            $('<li/>', { text: messages[i] }).appendTo(container);
        }
    })

    .showErrorContainer(function(form, el, container) {
        container
        .closest('.form-group').addClass('has-error');
    container.show()
    })

    .hideErrorContainer(function(form, el, container) {
        container
        .closest('.form-group').removeClass('has-error')
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
