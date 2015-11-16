$( document ).ready(
    function() {
        jQuery.datetimepicker.setLocale('en');
        jQuery.each(
            jQuery('[data-date-format]'),
            function(index, value) {
                var format = $(value).attr('data-date-format');
                var locale = $(value).attr('data-date-locale');
                var enableDatePicker = true;
                var enableTimePicker = true;

                if((format.indexOf('H') < 0) && (format.indexOf('H') < 0)) {
                    enableTimePicker = false;
                }

                if((format.indexOf('d') < 0) && (format.indexOf('m') < 0) && (format.indexOf('Y') < 0) && (format.indexOf('Y') < 0)) {
                    enableDatePicker = false;
                }

                if(!locale) {
                    locale = 'en';
                }

                jQuery.datetimepicker.setLocale(locale);

                jQuery(value).datetimepicker({
                    dayOfWeekStart: 1,
                    weeks: true,
                    lang: locale,
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

