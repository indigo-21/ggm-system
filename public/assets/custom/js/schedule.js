$('.daterange-am-pm').daterangepicker({
    singleDatePicker: true,
    timePicker: true,
    timePicker24Hour: false, // enables AM/PM
    timePickerSeconds: false,
    autoUpdateInput: false,
    startDate: moment(),
    locale: {
        format: 'MMMM DD, YYYY A',
        cancelLabel: 'Clear'
    }
});