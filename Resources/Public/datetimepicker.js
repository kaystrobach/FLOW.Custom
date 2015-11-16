$( document ).ready(
    function() {
        jQuery.datetimepicker.setLocale('en');
        jQuery.each(
            jQuery('[data-date-format]'),
            function(index, value) {
                var format = $(value).attr('data-date-format');
                var enableDatePicker = true;
                var enableTimePicker = true;

                if((format.indexOf('H') < 0) && (format.indexOf('H') < 0)) {
                    enableTimePicker = false;
                }

                if((format.indexOf('d') < 0) && (format.indexOf('m') < 0) && (format.indexOf('Y') < 0) && (format.indexOf('Y') < 0)) {
                    enableDatePicker = false;
                }

                jQuery(value).datetimepicker({
                    datepicker: enableDatePicker,
                    timepicker: enableTimePicker,
                    format: format,
                    mask:true,
                    todayButton: true,
                    defaultSelect: true,
                    allowBlank: true
                });
            }
        );
    }
);

