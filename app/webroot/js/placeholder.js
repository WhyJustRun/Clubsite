$(document).ready(function() {
    if (! ("placeholder" in document.createElement("input"))) {
        $('*[placeholder]').each(function() {
            $this = $(this);
            var placeholder = $(this).attr('placeholder');
            if ($(this).val() === '') {
                $this.val(placeholder);
            }
            $this.bind('focus',
            function() {
                if ($(this).val() === placeholder) {
                    this.plchldr = placeholder;
                    $(this).val('');
                }
            });
            $this.bind('blur',
            function() {
                if ($(this).val() === '' && $(this).val() !== this.plchldr) {
                    $(this).val(this.plchldr);
                }
            });
        });
        $('form#new_mail').bind('submit',
        function() {
            $(this).find('*[placeholder]').each(function() {
                if ($(this).val() === $(this).attr('placeholder')) {
                    $(this).val('');
                }
            });
        });
    }
});
