$( document ).ready(
    function() {
        jQuery.datetimepicker.setLocale('en');
        jQuery.each(
            jQuery('[data-date-format]'),
            function(index, value) {
                jQuery(value).datetimepicker({
                    format: $(value).attr('data-date-format'),
                    mask:true,
                    todayButton: true,
                    defaultSelect: true,
                    allowBlank: true
                });
            }
        );
    }
);

